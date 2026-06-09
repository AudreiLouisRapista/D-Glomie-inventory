<?php

namespace App\Http\Controllers;
use App\Helpers\BranchFilter;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $query = BranchFilter::apply(DB::table('inventory'), 'inventory');
        $totalInventory = $query->count();

        return view('themes.dashboard.dashboard', compact('totalInventory'));
    }
}
