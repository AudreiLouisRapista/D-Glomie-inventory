<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalProduct = DB::table('product')->count();

        return view('themes.dashboard.dashboard', compact('totalProduct'));
    }
}
