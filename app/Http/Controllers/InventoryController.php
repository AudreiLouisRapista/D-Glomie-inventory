<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InventoryController extends Controller
{
    public function inventory()
    {
        $categories = DB::table('category')->select('category.*')->get();
        $status = DB::table('status')->select('status.*')->get();
        $totalInventory = DB::table('inventory')->count();
        $totalAvailableStock = DB::table('inventory')->select('inventory_remainingQty')->sum('inventory_remainingQty');
        $totalLowStock = DB::table('inventory')->where('status_id', 2)->count();
        $totalOutOfStock = DB::table('inventory')->where('status_id', 3)->count();

        return view('themes.inventory.inventory', compact(
            'categories',
            'status',
            'totalInventory',
            'totalAvailableStock',
            'totalLowStock',
            'totalOutOfStock'
        ));
    }

    public function view_inventory(Request $request)
    {
        $query = DB::table('inventory')
            ->join('category', 'inventory.category_id', '=', 'category.id')
            ->join('product', 'inventory.product_id', '=', 'product.id')
            ->join('status', 'inventory.status_id', '=', 'status.id')
            ->select(
                'inventory.id as inventory_ID',
                'inventory.product_id as product_id',
                'inventory.category_id as category_id',
                'inventory.inventory_startingQty as invt_StartingQuantity',
                'inventory.inventory_newQty as invt_NewQuantity',
                'inventory.inventory_sellingPrice as invt_sellingPrice',
                'inventory.inventory_totalSold as invt_totalSold',
                'inventory.inventory_remainingQty as invt_remainingQty',
                'inventory.status_id as status_ID',
                'product.product_name as product_name',
                'category.category_name as category_name',
                DB::raw('(
                    SELECT pi.unit_price
                    FROM purchase_items pi
                    INNER JOIN batch b ON b.purchase_item_id = pi.id
                    WHERE b.product_id = inventory.product_id
                    ORDER BY b.id DESC
                    LIMIT 1
                ) as unit_price'),
               
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
                    <div class="d-flex justify-content-center align-items-center">
                    <button class="action-btn btn-edit mx-1" 
                            data-id="' . $row->inventory_ID . '"
                            data-product-id="' . $row->product_id . '"
                            data-product-name="' . $row->product_name . '"
                            data-category-name="' . $row->category_name . '"
                            data-category-id="' . $row->category_id . '"
                            data-selling_price="' . $row->invt_sellingPrice . '"
                            title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="action-btn btn-delete mx-1"
                            data-id="' . $row->inventory_ID . '"
                            title="Delete">
                        <i class="bi bi-trash3"></i>
                    </button>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getProducts(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:category,id',
        ]);

        $products = DB::table('product')
            ->leftJoin('batch', 'batch.product_id', '=', 'product.id')
            ->where('product.category_id', $request->category_id)
            ->select(
                'product.id as product_ID',
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

    public function save_inventory(Request $request)
    {
        $request->validate([
            'category' => 'required|exists:category,id',
            'product' => 'required|exists:product,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $qty = (int) $request->quantity;

        try {
            DB::beginTransaction();

            $existing = DB::table('inventory')
                ->where('product_id', $request->product)
                ->first();

            if ($existing) {
                $newQty = $existing->inventory_newQty + $qty;
                $remaining = $existing->inventory_startingQty + $newQty - $existing->inventory_totalSold;

                DB::table('inventory')
                    ->where('product_id', $request->product)
                    ->update([
                        'inventory_newQty' => $newQty,
                        'inventory_sellingPrice' => $request->selling_price,
                        'status_id' => $this->resolveStatusId($remaining),
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('inventory')->insert([
                    'category_id' => $request->category,
                    'product_id' => $request->product,
                    'status_id' => $this->resolveStatusId($qty),
                    'inventory_startingQty' => $qty,
                    'inventory_newQty' => 0,
                    'inventory_remainingQty' => $qty,
                    'inventory_sellingPrice' => $request->selling_price,
                    'inventory_totalSold' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $toDeduct = $qty;

            $batches = DB::table('batch')
                ->where('product_id', $request->product)
                ->where('batch_quantity', '>', 0)
                ->orderBy('id', 'asc')
                ->get();

            foreach ($batches as $batch) {
                if ($toDeduct <= 0) {
                    break;
                }

                if ($batch->batch_quantity <= $toDeduct) {
                    $toDeduct -= $batch->batch_quantity;

                    DB::table('batch')
                        ->where('id', $batch->id)
                        ->update([
                            'batch_quantity' => 0,
                            'updated_at' => now(),
                        ]);
                } else {
                    DB::table('batch')
                        ->where('id', $batch->id)
                        ->update([
                            'batch_quantity' => $batch->batch_quantity - $toDeduct,
                            'updated_at' => now(),
                        ]);

                    $toDeduct = 0;
                }
            }

            DB::commit();

            return response()->json(['save' => 'Inventory registered successfully!', 'total' => ['totalInventory' => DB::table('inventory')->count(), 
            'totalAvailableStock' => DB::table('inventory')->select('inventory_remainingQty')->sum('inventory_remainingQty'), 
            'totalLowStock' => DB::table('inventory')->where('status_id', 2)->count(), 
            'totalOutOfStock' => DB::table('inventory')->where('status_id', 3)->count()]]);


        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'errorMessage' => 'An error occurred while saving the inventory. Please try again.',
            ], 500);
        }
    }

        
    public function update_inventory(Request $request) {
    // dd($request->all());
        try {
            
            DB::beginTransaction();
            // 1. Get the current record from the database
            $inventory = DB::table('inventory')
                ->where('id', $request->id)
                ->first();

            if (!$inventory) {
                return response()->json(['error' => 'Record not found'], 404);
            }

            // 6. Update the Database
            $affected = DB::table('inventory')
                ->where('id', $request->id)
                ->update([
                    'product_id'            => $request->product_id,
                    'category_id'          => $request->category_id,
                    'inventory_sellingPrice'         => $request->selling_price,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);

            DB::commit();

            return response()->json([
                'save' => 'Product Updated',
                'debug' => [
                    'affected_rows' => $affected,
                    'inventory_id' => $request->id,
                    'product_id' => $request->product_id,
                    'category_id' => $request->category_id,
                    'selling_price' => $request->selling_price,
                ]
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'errorMessage' => 'An error occurred while updating the inventory. Please try again.',
            ], 500);
        }
    }

    private function resolveStatusId(int $qty): int
    {
        if ($qty <= 0) {
            return 3;
        }

        if ($qty <= 10) {
            return 2;
        }

        return 1;
    }
}
