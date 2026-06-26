<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use App\Helpers\BranchFilter;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function __construct(private ActivityLogger $activityLogger)
    {
    }

    // ==========================================
    // PAGE LOADER
    // ==========================================
    public function finance(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year  ?? now()->year;

        $summary = BranchFilter::apply(DB::table('daily_sales_report'), 'daily_sales_report')
            ->whereMonth('report_date', $month)
            ->whereYear('report_date', $year)
            ->whereNull('deleted_at')
            ->selectRaw('
                COALESCE(SUM(net_sales), 0)     as total_net_amount,
                COALESCE(SUM(gross_sales), 0)   as total_revenue,
                COALESCE(SUM(net_income), 0)    as total_profit,
                COALESCE(SUM(total_expenses), 0) as total_expenses
            ')
            ->first();

        // AJAX request for refreshing summary cards only (on filter change)
        if ($request->boolean('ajax_summary')) {
            return response()->json(['summary' => $summary]);
        }

        return view('themes.finance.finance', compact('summary', 'month', 'year'));
    }

    // ==========================================
    // DATATABLE — DAILY TRANSACTIONS
    // ==========================================
    public function view_finance(Request $request)
    {
        $query = BranchFilter::apply(DB::table('daily_sales_report'), 'daily_sales_report')
            ->whereNull('deleted_at');

        if ($request->filled('month')) {
            $query->whereMonth('report_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('report_date', $request->year);
        }

        $query->select(
            'id as report_ID',
            'report_date',
            'gross_sales',
            'total_expenses',
            'net_income'
        )   ->orderBy('daily_sales_report.id','desc')
            ->get();


        return DataTables::of($query)
            ->editColumn('report_date', function ($row) {
                return \Carbon\Carbon::parse($row->report_date)->format('M d, Y');
            })
            ->editColumn('gross_sales', function ($row) {
                return '₱' . number_format($row->gross_sales, 2);
            })
            ->editColumn('total_expenses', function ($row) {
                return '₱' . number_format($row->total_expenses, 2);
            })
            ->editColumn('net_income', function ($row) {
                $color = $row->net_income >= 0 ? 'text-success' : 'text-danger';
                return '<span class="' . $color . ' font-weight-bold">₱' . number_format($row->net_income, 2) . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button class="action-btn btn-view mx-1" data-id="' . $row->report_ID . '" title="View">
                        <i class="bi bi-eye"></i>
                    </button>
                ';
            })
            ->rawColumns(['net_income', 'action'])
            ->make(true);
    }

    // ==========================================
    // GRAPH DATA — LINE (Net Income) & BAR (Purchases vs Sales)
    // ==========================================
    public function get_graph_data(Request $request)
    {
        $month  = $request->month ?? now()->month;
        $year   = $request->year  ?? now()->year;
        $filter = $request->filter ?? 'day'; // 'day' or 'week'

        $query = BranchFilter::apply(DB::table('daily_sales_report'), 'daily_sales_report')
            ->whereMonth('report_date', $month)
            ->whereYear('report_date', $year)
            ->whereNull('deleted_at');

        if ($filter === 'week') {
            $data = $query->selectRaw('
                    WEEK(report_date) as period,
                    MIN(report_date) as period_start,
                    SUM(net_income) as net_income,
                    SUM(gross_sales) as gross_sales,
                    SUM(total_purchases) as total_purchases
                ')
                ->groupByRaw('WEEK(report_date)')
                ->orderByRaw('WEEK(report_date)')
                ->get();

            // Re-label weeks as Week 1, Week 2, etc. within the month
            $labels = $data->values()->map(function ($row, $index) {
                return 'Week ' . ($index + 1);
            });
        } else {
            $data = $query->selectRaw('
                    DAY(report_date) as period,
                    SUM(net_income) as net_income,
                    SUM(gross_sales) as gross_sales,
                    SUM(total_purchases) as total_purchases
                ')
                ->groupByRaw('DAY(report_date)')
                ->orderByRaw('DAY(report_date)')
                ->get();

            $labels = $data->pluck('period')->map(function ($day) {
                return 'Day ' . $day;
            });
        }

        return response()->json([
            'labels'           => $labels,
            'net_income'       => $data->pluck('net_income'),
            'gross_sales'      => $data->pluck('gross_sales'),
            'total_purchases'  => $data->pluck('total_purchases'),
        ]);
    }

    // ==========================================
    // VIEW SINGLE REPORT DETAILS (modal)
    // ==========================================
    public function view_report_details($id)
    {
        $report = BranchFilter::apply(DB::table('daily_sales_report'), 'daily_sales_report')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$report) {
            return response()->json(['error' => 'Report not found.'], 404);
        }

        $expenses = DB::table('expenses')
            ->where('report_id', $id)
            ->whereNull('deleted_at')
            ->get();

        $denom = DB::table('denom')
            ->where('report_id', $id)
            ->first();

        return response()->json([
            'report'   => $report,
            'expenses' => $expenses,
            'denom'    => $denom,
        ]);
    }
}