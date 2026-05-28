<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct(private ActivityLogger $activityLogger)
    {
    }

    public function product()
    {
        $products = DB::table('product')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->join('perishable', 'product.perishable_id', '=', 'perishable.id')
            ->select('product.*', 'category.category_name', 'perishable.perishable_type')
            ->get();

        $categories = DB::table('category')->select('category.*')->get();
        $perishables = DB::table('perishable')->select('perishable.*')->get();

        return view('product', compact('products', 'categories', 'perishables'));
    }

    public function save_product(Request $request)
    {
        $request->validate([
            'productName' => 'required|string|max:255',
            'category' => 'required|integer',
            'perishableType' => 'required|integer',
            'quantity' => 'required|numeric|min:0',
            'packSize' => 'required|numeric|min:0',
        ]);

        $product = $request->productName;
        $category = $request->category;
        $perishable = $request->perishableType;
        $productQuantity = $request->quantity;
        $productSize = $request->packSize;

        $duplicate = DB::table('product')
            ->where('category_id', $category)
            ->where('product_name', $product)
            ->where('perishable_id', $perishable)
            ->where('product_quantity', $productQuantity)
            ->where('product_size', $productSize)
            ->whereNull('deleted_at')
            ->exists();

        if ($duplicate) {
            return redirect()->back([
                'duplicate' => "The product '$product' with these specific bundle details already exists.",
            ], 422);
        }

        try {
            DB::table('product')->insert([
                'product_name' => $product,
                'category_id' => $category,
                'perishable_id' => $perishable,
                'product_quantity' => $productQuantity,
                'product_size' => $productSize,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $userName = session('name');
            $this->activityLogger->log(
                'added',
                "Added Product | Name: {$request->product_name} | Responsible: {$userName} | Bundle Number: {$request->tie_number} | Bundle Size: {$request->tie_qty}"
            );

            session()->flash('save', 'Product Added successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back([
                'errorMessage' => 'An error occurred while saving the product. Please try again.',
            ], 500);
        }
    }

    public function get_products_by_category(Request $request)
    {
        $products = DB::table('product')
            ->where('product.category_id', $request->category_id)
            ->leftJoin('purchase_items', 'purchase_items.product_id', '=', 'product.id')
            ->select(
                'product.id as product_ID',
                'product.product_name',
                DB::raw('COALESCE(MAX(purchase_items.unit_price), 0) as unit_cost')
            )
            ->groupBy('product.id', 'product.product_name')
            ->get()
            ->map(function ($product) {
                $product->batch_quantity = DB::table('inventory')
                    ->where('product_id', $product->product_ID)
                    ->sum(DB::raw('inventory_startingQty + inventory_newQty - inventory_totalSold'));

                return $product;
            });

        return response()->json($products);
    }
}
