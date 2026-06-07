<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct(private ActivityLogger $activityLogger)
    {
    }

    public function product()
    {
        $products = DB::table('product')->get();
        $categories = DB::table('category')->select('category.*')->get();
        $perishables = DB::table('perishable')->select('perishable.*')->get();

        return view('product', compact('products', 'categories', 'perishables'));
    }


    public function view_product(Request $request)
    {
        $query = DB::table('product')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->join('perishable', 'product.perishable_id', '=', 'perishable.id')
            ->select(
                'product.id as product_ID',
                'product.product_name',
                'product.bundle_quantity',
                'product.bundle_size',
                'product.category_id',
                'product.perishable_id',
                'category.category_name',
                'perishable.perishable_type'
            );

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '
                    <div class="d-flex justify-content-center align-items-center">
                    <button class="action-btn btn-edit mx-1"
                            data-id="' . $row->product_ID . '"
                            data-product-name="' . $row->product_name . '"
                            data-category-name="' . $row->category_name . '"
                            data-category-id="' . $row->category_id . '"
                            data-perishable-type="' . $row->perishable_type . '"
                            data-perishable-id="' . $row->perishable_id . '"
                            data-bundle-quantity="' . $row->bundle_quantity . '"
                            data-bundle-size="' . $row->bundle_size . '"
                            title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="action-btn btn-delete mx-1"
                            data-id="' . $row->product_ID . '"
                            title="Delete">
                        <i class="bi bi-trash3"></i>
                    </button>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
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
            ->where('bundle_quantity', $productQuantity)
            ->where('bundle_size', $productSize)
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
                'bundle_quantity' => $productQuantity,
                'bundle_size' => $productSize,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $userName = session('name');
            $this->activityLogger->log(
                'added',
                "Added Product | Name: {$request->product_name} | Responsible: {$userName} | Bundle Number: {$request->bundle_quantity} | Bundle Size: {$request->bundle_size}"
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
        $request->validate([
            'category_id' => 'required|integer|exists:category,id',
        ]);

        $products = DB::table('product')
            ->leftJoin('batch', 'batch.product_id', '=', 'product.id')
            ->where('product.category_id', $request->category_id)
            ->select(
                'product.id as product_ID',
                'product.product_name',
                DB::raw('COALESCE(SUM(batch.batch_quantity), 0) as batch_quantity'),
                DB::raw('(
                    SELECT pi.unit_price
                    FROM purchase_items pi
                    INNER JOIN batch b ON b.purchase_item_id = pi.id
                    WHERE b.product_id = product.id
                    ORDER BY b.id DESC
                    LIMIT 1
                ) as unit_cost')
            )
            ->groupBy('product.id', 'product.product_name')
            ->orderBy('product.product_name')
            ->get();

        return response()->json($products);
    }
}
