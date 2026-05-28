<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InventoryController extends Controller
{
    public function inventory()
    {
        $categories = DB::table('category')->select('category.*')->get();
        $status = DB::table('status')->select('status.*')->get();

        return view('inventory', compact('categories', 'status'));
    }

    public function view_inventory(Request $request)
    {
        $query = DB::table('inventory')
            ->join('category', 'inventory.category_id', '=', 'category.id')
            ->join('product', 'inventory.product_id', '=', 'product.id')
            ->join('status', 'inventory.status_id', '=', 'status.id')
            ->leftJoin('purchase_items', 'purchase_items.product_id', '=', 'inventory.product_id')
            ->select(
                'inventory.id as inventory_ID',
                'inventory.inventory_startingQty as invt_StartingQuantity',
                'inventory.inventory_newQty as invt_NewQuantity',
                'inventory.inventory_sellingPrice as invt_sellingPrice',
                'inventory.inventory_totalSold as invt_totalSold',
                'inventory.status_id as status_ID',
                'product.product_name',
                'purchase_items.unit_price',
                'category.category_name as name',
                DB::raw('(inventory.inventory_startingQty + inventory.inventory_newQty - inventory.inventory_totalSold) as invt_remainingStock')
            );

        if ($request->filled('category_id_table') && $request->category_id_table !== 'all') {
            $query->where('inventory.category_id', $request->category_id_table);
        }

        if ($request->filled('product_id_table') && $request->product_id_table !== 'all') {
            $query->where('inventory.product_id', $request->product_id_table);
        }

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-warning btn-edit" data-id="' . $row->inventory_ID . '">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->inventory_ID . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function save_inventory(Request $request)
    {
        $request->validate([
            'category' => 'required|exists:category,id',
            'product' => 'required|exists:products,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $qty = $request->quantity;
        if ($qty <= 0) {
            $statusId = 3;
        } elseif ($qty <= 10) {
            $statusId = 2;
        } else {
            $statusId = 1;
        }

        DB::table('inventory')->insert([
            'category_id' => $request->category,
            'product_id' => $request->product,
            'status_id' => $statusId,
            'inventory_startingQty' => $qty,
            'inventory_newQty' => 0,
            'inventory_sellingPrice' => $request->selling_price,
            'inventory_totalSold' => 0,
            'created_at' => now(),
        ]);

        return response()->json(['save' => 'Inventory registered successfully!']);
    }
}
