<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;
use Yajra\DataTables\Facades\DataTables;

class InventoryController extends Controller
{
    public function __construct(private ActivityLogger $activityLogger)
    {
    }

    public function inventory()
    {
        $categories = DB::table('category')->select('category.*')->get();
        $status = DB::table('status')->select('status.*')->get();
        $totalInventory = DB::table('inventory')->whereNull('deleted_at')->count();
        $totalAvailableStock = DB::table('inventory')->whereNull('deleted_at')->select('inventory_remainingQty')->sum('inventory_remainingQty');
        $totalLowStock = DB::table('inventory')->where('status_id', 2)->whereNull('deleted_at')->count();
        $totalOutOfStock = DB::table('inventory')->where('status_id', 3)->whereNull('deleted_at')->count();

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
            ->whereNull('inventory.deleted_at')
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

            $userName = session('name');
            $inventoryAction = $existing ? 'updated' : 'added';
            $this->activityLogger->log(
                $inventoryAction,
                ucfirst($inventoryAction) . " Inventory | Product ID: {$request->product} | Category ID: {$request->category} | Quantity: {$qty} | Responsible: {$userName}"
            );

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

    public function soft_delete_inventory($id)
    {
        $inventory = DB::table('inventory')->where('id', $id)->first();

        if (!$inventory) {
            return response()->json(['error' => 'Record not found.'], 404);
        }

        DB::table('inventory')
            ->where('id', $id)
            ->update(['deleted_at' => now()]);

        $userName = session('name');
        $this->activityLogger->log(
            'archived',
            "Archived Inventory | Inventory ID: {$id} | Responsible: {$userName}"
        );

        return response()->json(['save' => 'Inventory record archived successfully.']);
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

            $userName = session('name');
            $this->activityLogger->log(
                'updated',
                "Updated Inventory | Inventory ID: {$request->id} | Product ID: {$request->product_id} | Responsible: {$userName}"
            );

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


    // Page loader
    public function inventory_archive()
    {
        return view('themes.inventory.inventoryArchive');
    }

    // DataTable data
    public function view_inventory_archive(Request $request)
    {
        $query = DB::table('inventory')
            ->join('category', 'inventory.category_id', '=', 'category.id')
            ->join('product', 'inventory.product_id', '=', 'product.id')
            ->whereNotNull('inventory.deleted_at')
            ->select(
                'inventory.id as inventory_ID',
                'inventory.deleted_at as deleted_at',
                'inventory.inventory_sellingPrice as invt_sellingPrice',
                'inventory.inventory_startingQty as invt_startingQuantity',
                'inventory.inventory_newQty as invt_newQuantity',
                'inventory.inventory_remainingQty as remaining_stock',
                'inventory.inventory_totalSold as total_sold',
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


        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '
                    <button class="action-btn btn-restore mx-1" data-id="' . $row->inventory_ID . '" title="Restore">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                    <button class="action-btn btn-force-delete mx-1" data-id="' . $row->inventory_ID . '" title="Permanently Delete">
                          <i class="bi bi-trash3"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Restore
    public function restore_inventory($id)
    {
        DB::table('inventory')
            ->where('id', $id)
            ->update(['deleted_at' => null]);

        $userName = session('name');
        $this->activityLogger->log(
            'restored',
            "Restored Inventory | Inventory ID: {$id} | Responsible: {$userName}"
        );

        return response()->json(['save' => 'Inventory restored successfully.']);
    }

    // Permanently delete
    public function force_delete_inventory($id)
    {
        DB::table('inventory')
            ->where('id', $id)
            ->delete();

        $userName = session('name');
        $this->activityLogger->log(
            'deleted',
            "Permanently deleted Inventory | Inventory ID: {$id} | Responsible: {$userName}"
        );

        return response()->json(['save' => 'Inventory permanently deleted.']);
    }

    // Add sales record
    public function add_sale_record(Request $request)
    {
        $request->validate([
            'category_id'    => 'required|exists:category,id',
            'product_id'     => 'required|exists:product,id',
            'total_amount'   => 'required|numeric|min:0',
        ]);

        $qty = (int) $request->total_quantity;

        try {
            DB::beginTransaction();

            $inventory = DB::table('inventory')
                ->where('product_id', $request->product_id)
                ->whereNull('deleted_at')
                ->first();

            if (!$inventory) {
                return response()->json(['error' => 'No inventory record found for this product.'], 404);
            }

            // Prevent overselling
            if ($qty > $inventory->inventory_remainingQty) {
                return response()->json([
                    'error' => 'Quantity exceeds remaining stock of ' . $inventory->inventory_remainingQty
                ], 422);
            }

            $newTotalSold    = $inventory->inventory_totalSold + $qty;
            $newRemainingQty = $inventory->inventory_remainingQty - $qty;

            // 1. Save to inventorySales
            DB::table('inventorySales')->insert([
                'inventory_id'  => $inventory->id,
                'total_amount'  => $request->total_amount,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // 2. Update inventory
            DB::table('inventory')
                ->where('id', $inventory->id)
                ->update([
                    'inventory_totalSold'    => $newTotalSold,
                    'inventory_remainingQty' => $newRemainingQty,
                    'status_id'              => $this->resolveStatusId($newRemainingQty),
                    'updated_at'             => now(),
                ]);

            DB::commit();

            $userName = session('name');
            $this->activityLogger->log(
                'sales recorded',
                "Sales recorded | Inventory ID: {$inventory->id} | Responsible: {$userName}"
            );

            return response()->json(['save' => 'Sales recorded successfully!']);

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json(['errorMessage' => 'An error occurred. Please try again.'], 500);
        }
    }

    public function get_inventory_by_product($productId)
    {
        $inventory = DB::table('inventory')
            ->where('product_id', $productId)
            ->whereNull('deleted_at')
            ->select(
                'id as inventory_id',
                'inventory_remainingQty as remaining_qty',
                'inventory_totalSold as total_sold',
                'inventory_sellingPrice as selling_price'
            )
            ->first();

        if (!$inventory) {
            return response()->json(['error' => 'No inventory record found for this product.'], 404);
        }

        return response()->json($inventory);
    }

    public function inventory_sales_history()
    {
        return view('themes.inventory.inventorySalesHistory');
    }

    public function view_sales_history(Request $request)
    {
        $query = DB::table('inventorySales')
            ->join('inventory', 'inventorySales.inventory_id', '=', 'inventory.id')
            ->join('product', 'inventory.product_id', '=', 'product.id')
            ->join('category', 'inventory.category_id', '=', 'category.id')
            ->select(
                'inventorySales.id as sale_ID',
                'inventorySales.inventory_id',
                'inventorySales.total_amount',
                'inventorySales.created_at as sale_date',
                'inventory.inventory_sellingPrice as selling_price',
                'inventory.inventory_totalSold as quantity_sold',
                'product.product_name',
                'category.category_name'
            )
            ->orderBy('inventorySales.created_at', 'desc');

        return DataTables::of($query)
            ->addColumn('sale_ID', function ($row) {
                return 'SALE-' . $row->sale_ID;
            })
            ->rawColumns(['sale_ID'])
            ->make(true);
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
