<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function paymentTracker(Request $request)
    {
        $query = DB::table('purchase')
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

        $purchase_items = DB::table('purchase_items')
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

        return view('paymentTracker', compact('suppliers', 'purchases', 'products', 'purchase_items'));
    }

    public function save_payment(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required',
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {
                DB::table('payment')->insert([
                    'purchase_id' => $request->purchase_id,
                    'amount_paid' => $request->amount_paid,
                    'payment_date' => $request->payment_date,
                    'payment_method' => $request->payment_method,
                    'old_remaining_balance' => $request->old_remaining_balance,
                    'payment_reference' => $request->reference_number,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $purchase = DB::table('purchase')->where('id', $request->purchase_id)->first();
                $totalPaid = ($purchase->invoice_totalPaid ?? 0) + $request->amount_paid;
                $status = ($totalPaid >= $purchase->invoice_netAmount) ? 'Paid' : 'Partial';

                DB::table('purchase')->where('id', $request->purchase_id)->update([
                    'invoice_totalPaid' => $totalPaid,
                    'invoice_status' => $status,
                ]);
            });

            return redirect()->back()->with('save', 'Payment recorded successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return redirect()->back()->with('errorMessage', 'Duplicate Entry: This reference number already exists.');
            }

            return redirect()->back()->with('errorMessage', 'An unexpected database error occurred.');
        }
    }

    public function getPaymentHistory($id)
    {
        $payments = DB::table('payment')
            ->where('purchase_id', $id)
            ->orderBy('payment_date', 'desc')
            ->get();

        return response()->json($payments);
    }
}
