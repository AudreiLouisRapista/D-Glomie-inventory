<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function __construct(private ActivityLogger $activityLogger)
    {
    }

    public function supplierList()
    {
        $supplier = DB::table('supplier')->get();

        return view('supplierList', compact('supplier'));
    }

    public function save_supplier(Request $request)
    {
        $request->validate([
            'supplierName' => 'required|string|max:255',
            'supplierAddress' => 'required|string|max:255',
            'supplierPhone' => 'required|digits:11',
        ]);

        $supplierName = $request->supplierName;
        $supplierAddress = $request->supplierAddress;
        $supplierPhone = $request->supplierPhone;

        $duplicate = DB::table('supplier')
            ->where('supplier_name', $supplierName)
            ->where('address', $supplierAddress)
            ->where('contact_number', $supplierPhone)
            ->exists();

        if ($duplicate) {
            return redirect()->back()->with([
                'duplicate' => "The Supplier $supplierName details already exists.",
            ], 422);
        }

        try {
            DB::table('supplier')->insert([
                'supplier_name' => $supplierName,
                'address' => $supplierAddress,
                'contact_number' => $supplierPhone,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $userName = session('name');
            $this->activityLogger->log(
                'added',
                "Added Supplier | Name: {$request->supplierName} | Responsible: {$userName} | Address: {$request->supplierAddress} | Phone Number: {$request->supplierPhone}"
            );

            session()->flash('save', ' Supplier save successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('errorMessage', $e->getMessage());
        }
    }
}
