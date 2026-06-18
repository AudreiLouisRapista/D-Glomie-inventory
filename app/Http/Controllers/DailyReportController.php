<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;
use App\Helpers\BranchFilter;

class DailyReportController extends Controller
{
    public function __construct(
        private ActivityLogger $activityLogger
    ) {}

    // ==========================================
    // PAGE LOADER
    // ==========================================
    public function dailyReport(){
        return view('themes.finance.DailySalesReport', []);

    }

    // ==========================================
    // LOAD PURCHASES BY DATE
    // ==========================================
    public function get_purchases_by_date(Request $request)
    {
        $request->validate(['date' => 'required|date']);

        $purchases = DB::table('purchase')
            ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
            ->where('purchase.branch_id', session('branch_id'))
            ->whereDate('purchase.invoice_date', $request->date)
            ->select(
                'purchase.id as purchase_id',
                'purchase.invoice_number',
                'purchase.invoice_netAmount as amount',
                'supplier.supplier_name'
            )
            ->get();

        return response()->json($purchases);
    }

    // ==========================================
    // LOAD DAILY SALES BY DATE
    // ==========================================
    public function get_daily_sales_by_date(Request $request)
    {
        $request->validate(['date' => 'required|date']);

        $sales = BranchFilter::apply(DB::table('daily_sales'), 'daily_sales')
            ->join('inventory', 'daily_sales.inventory_id', '=', 'inventory.id')
            ->join('product', 'inventory.product_id', '=', 'product.id')
            ->whereDate('daily_sales.sale_date', $request->date)
            ->whereNull('daily_sales.deleted_at')
            ->select(
                'daily_sales.id as sale_id',
                'product.product_name',
                'daily_sales.quantity_sold',
                'daily_sales.total_amount'
            )
            ->get();

        return response()->json($sales);
    }

    // ==========================================
    // LOAD STOCK OUT BY DATE
    // ==========================================
    public function get_stock_out_by_date(Request $request)
    {
        $request->validate(['date' => 'required|date']);

        $stockOut = BranchFilter::apply(DB::table('stock_transfer'), 'stock_transfer')
            ->join('product', 'stock_transfer.product_id', '=', 'product.id')
            ->join('branches', 'stock_transfer.to_branch_id', '=', 'branches.id')
            ->whereDate('stock_transfer.transfer_date', $request->date)
            ->whereNull('stock_transfer.deleted_at')
            ->select(
                'stock_transfer.id as transfer_id',
                'product.product_name',
                'stock_transfer.quantity',
                'stock_transfer.amount',
                'branches.branch_name as destination'
            )
            ->get();

        return response()->json($stockOut);
    }

    // ==========================================
    // SAVE DAILY REPORT
    // ==========================================
    public function save_daily_report(Request $request)
    {
        $request->validate([
            'report_date'        => 'required|date',
            'gross_sales'        => 'required|numeric',
            'less_expenses_stock'=> 'required|numeric',
            'net_sales'          => 'required|numeric',
            'total_purchases'    => 'required|numeric',
            'total_stockout'     => 'required|numeric',
            'total_expenses'     => 'required|numeric',
            'total_cash_sales'   => 'required|numeric',
            'gcash_init_deposit' => 'required|numeric',
            'total_sales'        => 'required|numeric',
            'difference'         => 'required|numeric',
            'net_income'         => 'required|numeric',
        ]);

        // Check for duplicate report
        $existing = DB::table('daily_sales_report')
            ->where('branch_id', session('branch_id'))
            ->whereDate('report_date', $request->report_date)
            ->exists();

        if ($existing) {
            return response()->json([
                'errorMessage' => 'A report for this date already exists for your branch.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // 1. Save main report
            $reportId = DB::table('daily_sales_report')->insertGetId([
                'branch_id'           => session('branch_id'),
                'user_id'             => session('id'),
                'report_date'         => $request->report_date,
                'total_purchases'     => $request->total_purchases,
                'gross_sales'         => $request->gross_sales,
                'less_expenses_stock' => $request->less_expenses_stock,
                'net_sales'           => $request->net_sales,
                'total_stockout'      => $request->total_stockout,
                'total_expenses'      => $request->total_expenses,
                'total_cash_sales'    => $request->total_cash_sales,
                'gcash'               => $request->gcash_init_deposit,
                'initial_deposit'     => $request->gcash_init_deposit,
                'total_sales'         => $request->total_sales,
                'difference'          => $request->difference,
                'net_income'          => $request->net_income,
                'remarks'             => $request->remarks,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            // 2. Save pivot — report_purchases
            if ($request->has('purchase_ids')) {
                foreach ($request->purchase_ids as $purchaseId) {
                    DB::table('report_purchases')->insert([
                        'report_id'   => $reportId,
                        'purchase_id' => $purchaseId,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }
            }

            // 3. Save pivot — report_daily_sales
            if ($request->has('sale_ids')) {
                foreach ($request->sale_ids as $saleId) {
                    DB::table('report_daily_sales')->insert([
                        'report_id'     => $reportId,
                        'daily_sale_id' => $saleId,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }

            // 4. Save pivot — report_stock_transfers
            if ($request->has('transfer_ids')) {
                foreach ($request->transfer_ids as $transferId) {
                    DB::table('report_stock_transfers')->insert([
                        'report_id'          => $reportId,
                        'stock_transfer_id'  => $transferId,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);
                }
            }

            // 5. Save expenses
            if ($request->has('expense_label')) {
                foreach ($request->expense_label as $index => $label) {
                    if (!empty($label) && isset($request->expense_amount[$index])) {
                        DB::table('expenses')->insert([
                            'branch_id'    => session('branch_id'),
                            'report_id'    => $reportId,
                            'expense_date' => $request->report_date,
                            'label'        => $label,
                            'amount'       => $request->expense_amount[$index],
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ]);
                    }
                }
            }

            // 6. Save denom
            DB::table('denom')->insert([
                'branch_id'       => session('branch_id'),
                'report_id'       => $reportId,
                'denom_date'      => $request->report_date,
                'bill_1000'       => $request->denom_1000_count ?? 0,
                'bill_500'        => $request->denom_500_count ?? 0,
                'bill_200'        => $request->denom_200_count ?? 0,
                'bill_100'        => $request->denom_100_count ?? 0,
                'bill_50'         => $request->denom_50_count ?? 0,
                'bill_20'         => $request->denom_20_count ?? 0,
                'coins'           => $request->coins_amount ?? 0,
                'total_cash'      => $request->total_cash_sales,
                'gcash'           => $request->gcash_init_deposit,
                'initial_deposit' => $request->gcash_init_deposit,
                'total_sales'     => $request->total_sales,
                'difference'      => $request->difference,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            DB::commit();

            $userName = session('name');
            $this->activityLogger->log(
                'submitted',
                "Daily Report submitted | Date: {$request->report_date} | Branch ID: " . session('branch_id') . " | Responsible: {$userName}"
            );

            return response()->json(['save' => 'Daily report saved successfully!']);

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'errorMessage' => 'An error occurred while saving. Please try again.'
            ], 500);
        }
    }
}