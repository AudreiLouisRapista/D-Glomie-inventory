<?php

namespace App\Http\Controllers;
use App\Helpers\BranchFilter;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $stats = BranchFilter::apply(DB::table('inventory'), 'inventory')
            ->whereNull('deleted_at')
            ->selectRaw("
                COUNT(*) as totalInventory,
                SUM(inventory_remainingQty) as totalAvailableStock,
                SUM(CASE WHEN status_id = 2 THEN 1 ELSE 0 END) as totalLowStock,
                SUM(CASE WHEN status_id = 3 THEN 1 ELSE 0 END) as totalOutOfStock
            ")
            ->first();

        return view('themes.dashboard.dashboard', [
            'totalInventory'      => $stats->totalInventory,
            'totalAvailableStock' => $stats->totalAvailableStock ?? 0,
            'totalLowStock'       => $stats->totalLowStock,
            'totalOutOfStock'     => $stats->totalOutOfStock,
        ]);
    }
}
