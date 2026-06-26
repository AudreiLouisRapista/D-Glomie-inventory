<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\BranchFilter;

class InvoiceController extends Controller
{
    public function __construct(private ActivityLogger $activityLogger)
    {
    }

    public function invoiceEncoder()
    {
        $invoiceInfo = BranchFilter::apply(DB::table('purchase'), 'purchase')
            ->join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
            ->select('purchase.*', 'purchase.id as purchase_id', 'supplier.supplier_name')
            ->get();

        $purchase_items = BranchFilter::apply(DB::table('purchase_items'), 'purchase_items')
            ->join('product', 'purchase_items.product_id', '=', 'product.id')
            ->select(['purchase_items.*', 'product.product_name'])
            ->orderBy('purchase_items.purchase_id', 'desc')
            ->get();

        $products = DB::table('product')
            ->join('perishable', 'product.perishable_id', '=', 'perishable.id')
            ->select('product.*', 'perishable.perishable_type')
            ->orderBy('product.product_name', 'ASC')
            ->get();

        $supplier = DB::table('supplier')->get();

        return view('themes.invoiceEncoder.invoiceEncoder', compact('invoiceInfo', 'supplier', 'products', 'purchase_items'));
    }

    public function save_invoiceDetails(Request $request)
    {
        $request->validate([
            'supplierId' => 'required|integer|exists:supplier,id',
            'invoiceNumber' => 'required|string|max:100|unique:purchase,invoice_number',
            'invoiceDate' => 'required|date',
            'invoiceduoDate' => 'required|date|after_or_equal:invoiceDate',
            'gross_total_raw' => 'required|numeric|min:0',
            'vat_amount_raw' => 'required|numeric|min:0',
            'grand_total_raw' => 'required|numeric|min:0',
            'productId' => 'required|array|min:1',            
            'productId.*' => 'required|integer|exists:product,id', 
            'CSquantity' => 'required|array|min:1',
            'CSquantity.*' => 'required|integer|min:1',
            'Quantinumber' => 'required|array|min:1',
            'Quantinumber.*' => 'required|integer|min:0',
            'productSize' => 'required|array|min:1',
            'productSize.*' => 'required|numeric|min:0',
            'unitPrice' => 'required|array|min:1',
            'unitPrice.*' => 'required|numeric|min:0.01',
            'perishableType' => 'required|array|min:1',
            'perishableType.*' => 'required|string',
            'expdate' => 'nullable|array',
            'expdate.*' => 'nullable|date|after:today',
        ]);

        DB::beginTransaction();

        try {
            $invoiceId = DB::table('purchase')->insertGetId([
                'supplier_id' => $request->supplierId,
                'branch_id' => session('branch_id'),
                'invoice_number' => $request->invoiceNumber,
                'invoice_date' => $request->invoiceDate,
                'invoice_duo_date' => $request->invoiceduoDate,
                'invoice_grossAmount' => $request->gross_total_raw,
                'invoice_vatAmount' => $request->vat_amount_raw,
                'invoice_netAmount' => $request->grand_total_raw,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->productId as $key => $productId) {

                $purchaseItemId = DB::table('purchase_items')->insertGetId([
                    'purchase_id' => $invoiceId,
                    'branch_id' => session('branch_id'),
                    'product_id' => $productId,  
                    'supply_qty' => $request->CSquantity[$key],
                    'unit_price' => $request->unitPrice[$key],
                    'total_price' => $request->Quantinumber[$key] * $request->productSize[$key] * $request->unitPrice[$key],
                    'grand_total' => $request->CSquantity[$key] * (
                        $request->Quantinumber[$key] *
                        $request->productSize[$key] *
                        $request->unitPrice[$key]
                    ),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $batchQty = (int) (
                    $request->Quantinumber[$key] *
                    $request->productSize[$key] *
                    $request->CSquantity[$key]
                );

                $isPerishable = strtolower($request->perishableType[$key]) === 'perishable';

                DB::table('batch')->insert([
                    'purchase_item_id' => $purchaseItemId,
                    'branch_id' => session('branch_id'),
                    'product_id' => $productId,
                    'exp_date' => $isPerishable ? $request->expdate[$key] : null,
                    'batch_quantity' => $batchQty,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $userName = session('name');
            $this->activityLogger->log(
                'added',
                "New Item | Invoice No.: {$request->invoiceNumber} | Total Amount: {$request->grand_total_raw} | Responsible: {$userName}"
            );

            DB::commit();
            return redirect()->route('invoiceEncoder')
                ->with('save', 'Invoice saved! Go to Inventory to receive items.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('duplicate', 'Duplicate Invoice Number');
            }

            return back()->withInput()->with('errorMessage', 'Database Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('errorMessage', 'General Error: ' . $e->getMessage());
        }
    }
}
