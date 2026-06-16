<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;
use App\Helpers\BranchFilter;
use App\Services\InventoryService;
use Yajra\DataTables\Facades\DataTables;

class StockOutController extends Controller
{

    public function __construct(
    private ActivityLogger $activityLogger,
    private InventoryService $inventoryService
    ) {}
    

    public function get_products_stockOut(Request $request)
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
                DB::raw('(
                    SELECT pi.unit_price
                    FROM purchase_items pi
                    INNER JOIN batch b ON b.purchase_item_id = pi.id
                    WHERE b.product_id = inventory.product_id
                    ORDER BY b.id DESC
                    LIMIT 1
                ) as unit_price')            // ← add this
            )
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function save_stock_transfer(Request $request)
    {
        $request->validate([
            'transfer_date'   => 'required|date',
            'from_branch_id'  => 'required|exists:branches,id',
            'to_branch_id'    => 'required|exists:branches,id|different:from_branch_id',
            'inventory_id'    => 'required|array',
            'inventory_id.*'  => 'required|exists:inventory,id',
            'quantity'        => 'required|array',
            'quantity.*'      => 'required|integer|min:1',
            'amount'          => 'required|array',
            'amount.*'        => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->inventory_id as $index => $inventoryId) {
                $qty    = (int) $request->quantity[$index];
                $amount = $request->amount[$index];

                // 1. Get current inventory record
                $inventory = DB::table('inventory')
                    ->join('product', 'inventory.product_id', '=', 'product.id')
                    ->where('inventory.id', $inventoryId)
                    ->where('inventory.branch_id', session('branch_id'))
                    ->whereNull('inventory.deleted_at')
                    ->select('inventory.*', 'product.product_name')
                    ->first();

                if (!$inventory) {
                    DB::rollBack();
                    return response()->json([
                        'error' => 'Inventory record not found for one of the selected products.'
                    ], 404);
                }

                // 2. Prevent over-transferring
                if ($qty > $inventory->inventory_remainingQty) {
                    DB::rollBack();
                    return response()->json([
                        'error' => 'Quantity (' . $qty . ') exceeds remaining stock (' . $inventory->inventory_remainingQty . ') for product: ' . $inventory->product_name
                    ], 422);
                }

                $newRemainingQty = $inventory->inventory_remainingQty - $qty;

                // 3. Save to stock_transfer
                DB::table('stock_transfer')->insert([
                    'branch_id'      => session('branch_id'),
                    'from_branch_id' => $request->from_branch_id,
                    'to_branch_id'   => $request->to_branch_id,
                    'product_id'     => $inventory->product_id,
                    'transfer_date'  => $request->transfer_date,
                    'quantity'       => $qty,
                    'amount'         => $amount,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // 4. Deduct from inventory
                DB::table('inventory')
                    ->where('id', $inventoryId)
                    ->update([
                        'inventory_remainingQty' => $newRemainingQty,
                        'status_id'              => $this->inventoryService->resolveStatusId($newRemainingQty),
                        'updated_at'             => now(),
                    ]);
            }

            DB::commit();

            $userName = session('user_role');
            $this->activityLogger->log(
                'transferred',
                "Stock Transfer | Date: {$request->transfer_date} | From Branch: {$request->from_branch_id} | To Branch: {$request->to_branch_id} | Responsible: {$userName}"
            );

            return response()->json(['save' => 'Stock transferred successfully!']);

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'errorMessage' => 'An error occurred while saving. Please try again.'
            ], 500);
        }
    }

    public function stock_transfer()
    {
        $branches = DB::table('branches')
            ->where('id', '!=', session('branch_id'))  // ← exclude current branch
            ->get();

        return view('themes.Stock_Out.stockOut', compact('branches'));
    }

}
