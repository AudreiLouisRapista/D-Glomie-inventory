<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesTransaction extends Controller
{
    public function sales_transaction(){

    return view ('themes.Add_Sales.salesTransaction', []);

    }
}
