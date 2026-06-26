<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;
use App\Helpers\BranchFilter;

class StockInHistroy extends Controller
{
    public function __construct(private ActivityLogger $activityLogger)
    {
    }
    public function stockIn_history(Request $request)
    {
        $query = BranchFilter::apply(DB::table('purchase'), 'purchase')
            ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
            ->select([
                'purchase.id as purchase_id',
                'purchase.*',
                'supplier.supplier_name',
            ]);

        if ($request->supplier_id) {
            $query->where('purchase.supplier_id', $request->supplier_id);
        }

        $purchases = $query->orderBy('purchase.id', 'desc')->get();

        $purchase_items = BranchFilter::apply(DB::table('purchase_items'), 'purchase_items')
            ->join('product', 'purchase_items.product_id', '=', 'product.id')
            ->select([
                'purchase_items.*',
                'product.product_name',
                'product.bundle_size',
                'product.bundle_quantity',
                DB::raw('product.bundle_size * product.bundle_quantity as tie_total'),
            ])
            ->orderBy('purchase_items.purchase_id', 'desc')
            ->get()
            ->groupBy('purchase_id');

        $suppliers = DB::table('supplier')->orderBy('supplier_name', 'ASC')->get();
        $products = DB::table('product')->orderBy('product_name', 'ASC')->get();

        return view('themes.StockIn.stockInHistory', compact('suppliers', 'purchases', 'products', 'purchase_items'));
    }


}
