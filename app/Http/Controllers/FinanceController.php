<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function __construct(private ActivityLogger $activityLogger)
    {
    }

    public function finance()
    {

        return view('themes.finance.finance', []);
    }

    public function DailyTransction(){
        return view('themes.finance.DailyTransaction', []);

    }
}