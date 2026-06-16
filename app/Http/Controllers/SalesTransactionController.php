<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;
use App\Helpers\BranchFilter;
use App\Services\InventoryService;
use Yajra\DataTables\Facades\DataTables;

class SalesTransactionController extends Controller
{
    public function __construct(
    private ActivityLogger $activityLogger,
    private InventoryService $inventoryService
    ) {}

    public function sales_transaction(){

     $query = BranchFilter::apply(DB::table('daily_sales'), 'daily_sales')
     ->where('daily_sales.branch_id', session('branch_id'))
     ->select('daily_sales.sale_date as sale_date', 
     'daily_sales.total_amount as total_amount')
        ->whereNull('deleted_at')
        ->orderBy('daily_sales.id','desc')
        ->get();

        return view ('themes.Add_Sales.salesTransaction', compact('query'));
    }

    public function get_products_sales(Request $request)
    {
        $products = DB::table('inventory')
            ->join('product', 'inventory.product_id', '=', 'product.id')
            ->where('product.product_name', 'LIKE', '%' . $request->search . '%')
            ->whereNull('inventory.deleted_at')
            ->where('inventory.branch_id', session('branch_id'))
            ->select(
                'inventory.id as inventory_id',
                'product.product_name',
                'inventory.inventory_remainingQty',
                'inventory.inventory_sellingPrice'

            )
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function save_daily_sales(Request $request)
    {
        $request->validate([
            'sale_date'       => 'required|date',
            'inventory_id'    => 'required|array',
            'inventory_id.*'  => 'required|exists:inventory,id',
            'quantity_sold'   => 'required|array',
            'quantity_sold.*' => 'required|integer|min:1',
            'total_amount'    => 'required|array',
            'total_amount.*'  => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->inventory_id as $index => $inventoryId) {
                $qtySold    = (int) $request->quantity_sold[$index];
                $totalAmount = $request->total_amount[$index];

                // 1. Get current inventory record
                    $inventory = DB::table('inventory')
                        ->join('product', 'inventory.product_id', '=', 'product.id')
                        ->where('inventory.id', $inventoryId)
                        ->where('inventory.branch_id', session('branch_id'))
                        ->whereNull('inventory.deleted_at')
                        ->select(
                            'inventory.*',
                            'product.product_name'
                        )
                        ->first();

                if (!$inventory) {
                    DB::rollBack();
                    return response()->json([
                        'error' => 'Inventory record not found for one of the selected products.'
                    ], 404);
                }

                // 2. Prevent overselling
                if ($qtySold > $inventory->inventory_remainingQty) {
                    DB::rollBack();
                    return response()->json([
                        'error' => 'Quantity sold (' . $qtySold . ') exceeds remaining stock (' . $inventory->inventory_remainingQty . ') for product: ' . $inventory->product_name ?? ''
                    ], 422);
                }

                $newTotalSold    = $inventory->inventory_totalSold + $qtySold;
                $newRemainingQty = $inventory->inventory_remainingQty - $qtySold;

                // 3. Save to daily_sales
                DB::table('daily_sales')->insert([
                    'branch_id'    => session('branch_id'),
                    'inventory_id' => $inventoryId,
                    'sale_date'    => $request->sale_date,
                    'quantity_sold' => $qtySold,
                    'total_amount'  => $totalAmount,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                // 4. Update inventory in real time
                DB::table('inventory')
                    ->where('id', $inventoryId)
                    ->update([
                        'inventory_totalSold'    => $newTotalSold,
                        'inventory_remainingQty' => $newRemainingQty,
                        'status_id' => $this->inventoryService->resolveStatusId($newRemainingQty),
                        'updated_at'             => now(),
                    ]);
            }

            DB::commit();

            $userName = session('name');
            $this->activityLogger->log(
                'recorded',
                "Daily Sales recorded | Date: {$request->sale_date} | Branch ID: " . session('branch_id') . " | Responsible: {$userName}"
            );

            return response()->json(['save' => 'Daily sales recorded successfully!']);

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'errorMessage' => 'An error occurred while saving. Please try again.'
            ], 500);
        }
    }
}
