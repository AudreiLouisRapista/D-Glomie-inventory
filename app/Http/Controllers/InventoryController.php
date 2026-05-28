<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InventoryController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // VIEW  —  render the inventory page
    // ─────────────────────────────────────────────────────────────
    public function inventory()
    {
        $categories = DB::table('category')->select('category.*')->get();
        $status     = DB::table('status')->select('status.*')->get();
        $totalInventory = DB::table('inventory')->count();
        $totalAvailableStock = DB::table('inventory')->select('inventory_remainingQty')->sum('inventory_remainingQty');
        $totalLowStock = DB::table('inventory')->where('status_id', 2)->count();
        $totalOutOfStock = DB::table('inventory')->where('status_id', 3)->count();

        return view('inventory', compact('categories', 'status', 'totalInventory', 'totalAvailableStock', 'totalLowStock', 'totalOutOfStock'));
    }


    // ─────────────────────────────────────────────────────────────
    // DATATABLE  —  server-side inventory list
    //
    // Join chain:
    //   inventory
    //     → category       (category_id)
    //     → product        (product_id)
    //     → status         (status_id)
    //     → batch subquery (latest non-expired batch per product)
    //         → purchase_items (to get unit_price for that batch)
    //
    // The subquery picks the single most-recent, non-expired batch
    // row per product so we never get duplicate inventory rows.
    // ─────────────────────────────────────────────────────────────
public function view_inventory(Request $request)
{
    $query = DB::table('inventory')
        ->join('category', 'inventory.category_id', '=', 'category.id')
        ->join('product',  'inventory.product_id',  '=', 'product.id')
        ->join('status',   'inventory.status_id',   '=', 'status.id')
        ->select(
            'inventory.id                     as inventory_ID',
            'inventory.inventory_startingQty  as invt_StartingQuantity',
            'inventory.inventory_newQty        as invt_NewQuantity',
            'inventory.inventory_sellingPrice  as invt_sellingPrice',
            'inventory.inventory_totalSold     as invt_totalSold',
            'inventory.status_id               as status_ID',
            'product.product_name',
            'category.category_name            as name',
            DB::raw('(
                SELECT pi.unit_price
                FROM purchase_items pi
                INNER JOIN batch b ON b.purchase_item_id = pi.id
                WHERE b.product_id = inventory.product_id
                ORDER BY b.id DESC
                LIMIT 1
            ) as unit_price'),
            DB::raw('(inventory.inventory_startingQty
                     + inventory.inventory_newQty
                     - inventory.inventory_totalSold)
                     as invt_remainingStock')
        );

    if ($request->filled('category_id_table') && $request->category_id_table !== 'all') {
        $query->where('inventory.category_id', $request->category_id_table);
    }

    if ($request->filled('product_id_table') && $request->product_id_table !== 'all') {
        $query->where('inventory.product_id', $request->product_id_table);
    }

    return DataTables::of($query)
        ->addColumn('action', function ($row) {
            return '
                <button class="btn btn-sm btn-warning btn-edit"
                        data-id="' . $row->inventory_ID . '"
                        title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-delete"
                        data-id="' . $row->inventory_ID . '"
                        title="Delete">
                    <i class="bi bi-trash3"></i>
                </button>
            ';
        })
        ->rawColumns(['action'])
        ->make(true);
}


    // ─────────────────────────────────────────────────────────────
    // GET PRODUCTS  —  AJAX dropdown for the modal form
    //
    // Returns products that belong to a given category,
    // with the total available batch_quantity summed across
    // all non-expired batches so the JS can show stock hints
    // and disable out-of-stock options.
    // ─────────────────────────────────────────────────────────────
public function getProducts(Request $request)
{
     dd(DB::select("
        SELECT p.id, COALESCE(SUM(b.batch_quantity),0) as batch_quantity
        FROM product p
        LEFT JOIN batch b ON b.product_id = p.id
        WHERE p.category_id = ?
        GROUP BY p.id
    ", [$request->category_id]));

    $request->validate([
        'category_id' => 'required|integer|exists:category,id',
    ]);

    $products = DB::table('product')
        ->leftJoin('batch', 'batch.product_id', '=', 'product.id')
        ->where('product.category_id', $request->category_id)
        ->select(
            'product.id           as product_ID',
            'product.product_name',
            DB::raw('COALESCE(SUM(batch.batch_quantity), 0) as batch_quantity'),
            DB::raw('(
                SELECT pi.unit_price
                FROM purchase_items pi
                INNER JOIN batch b ON b.purchase_item_id = pi.id
                WHERE b.product_id = product.id
                ORDER BY b.id DESC
                LIMIT 1
            ) as unit_cost')
        )
        ->groupBy('product.id', 'product.product_name')
        ->orderBy('product.product_name')
        ->get();

    return response()->json($products);
}


    // ─────────────────────────────────────────────────────────────
    // SAVE  —  register a new inventory entry
    // ─────────────────────────────────────────────────────────────
   public function save_inventory(Request $request)
{
    $request->validate([
        'category'      => 'required|exists:category,id',
        'product'       => 'required|exists:product,id',
        'cost_price'    => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
        'quantity'      => 'required|integer|min:1',
    ]);

    $qty = (int) $request->quantity;

    // Check if inventory already exists for this product
    $existing = DB::table('inventory')
        ->where('product_id', $request->product)
        ->first();

    if ($existing) {
        // Update existing — add new qty on top
        $newQty = $existing->inventory_newQty + $qty;
        $remaining = $existing->inventory_startingQty 
                   + $newQty 
                   - $existing->inventory_totalSold;

        DB::table('inventory')
            ->where('product_id', $request->product)
            ->update([
                'inventory_newQty'       => $newQty,
                'inventory_sellingPrice' => $request->selling_price,
                'status_id'              => $this->resolveStatusId($remaining),
                'updated_at'             => now(),
            ]);
    } else {
        // Fresh insert
        DB::table('inventory')->insert([
            'category_id'            => $request->category,
            'product_id'             => $request->product,
            'status_id'              => $this->resolveStatusId($qty),
            'inventory_startingQty'  => $qty,
            'inventory_newQty'       => 0,
            'inventory_remainingQty' => $remaining,
            'inventory_sellingPrice' => $request->selling_price,
            'inventory_totalSold'    => 0,
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);
    }

   // ── FIFO batch deduction ────────────────────────
    $toDeduct = $qty;

    $batches = DB::table('batch')
        ->where('product_id', $request->product)
        ->where('batch_quantity', '>', 0)
        ->orderBy('id', 'asc') // oldest batch first
        ->get();

    foreach ($batches as $batch) {
        if ($toDeduct <= 0) break;

        if ($batch->batch_quantity <= $toDeduct) {
            // Fully consume this batch
            $toDeduct -= $batch->batch_quantity;
            DB::table('batch')
                ->where('id', $batch->id)
                ->update([
                    'batch_quantity' => 0,
                    'updated_at'     => now(),
                ]);
        } else {
            // Partially consume this batch
            DB::table('batch')
                ->where('id', $batch->id)
                ->update([
                    'batch_quantity' => $batch->batch_quantity - $toDeduct,
                    'updated_at'     => now(),
                ]);
            $toDeduct = 0;
        }
    }



    return response()->json(['save' => 'Inventory registered successfully!']);
}




    // ─────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────

    /**
     * Determine the stock status ID from a quantity.
     *
     *  1 = In Stock   (qty > 10)
     *  2 = Low Stock  (qty 1–10)
     *  3 = Out of Stock (qty 0)
     */
    private function resolveStatusId(int $qty): int
    {
        if ($qty <= 0)  return 3;
        if ($qty <= 10) return 2;
        return 1;
    }
}