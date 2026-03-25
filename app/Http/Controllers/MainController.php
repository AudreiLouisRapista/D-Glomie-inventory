<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // For direct database queries
use Illuminate\Validation\Rule;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session; // For session usage
use Exception;
use DateTime;
use Illuminate\Support\Facades\File;
class MainController extends Controller
{
    /**
     * Show the main welcome page.
     */
    public function main(){
        return view('welcome');
    }

    /**
     * Show the registration form view.
     */


    /**
     * Handle an incoming registration request and save the new user.
     */

    /**
     * Handle user authentication (login).
     */
   
public function auth_user(Request $request)
{

    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|min:6',
    ]);

    // Step 2: Find the user by email
    $user = DB::table('users')->where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->with('errorMessage', 'Invalid email or password.');
    }

    if ($user->role_id != 1) {
        return back()->with('errorMessage', 'Unauthorized access.');
    }

    // Step 5: Regenerate session to prevent session fixation attacks
    // This gives the user a brand new session ID after login
    $request->session()->regenerate();

    Session::put([
        'id'        => $user->id,
        'name'      => $user->usr_name,
        'email'     => $user->email,
        'role_id'   => $user->role_id,
        'user_role' => 'Admin',
    ]);

    return redirect()->route('dashboard');
}
private function logActivity($action, $description)
{
    ActivityLog::create([
        'admin_id' => Session::get('id'), // or Auth::id() if using Auth
        'action' => $action,
        'description' => $description,
    ]);
}

public function admin_profile()

{
    $logs = ActivityLog::latest()->take(10)->get();

    $admins = DB::table('users')->get();

    return view('admin_profile', compact('admins','logs'));
}

 public function adminProfile(Request $request, $id) { 
    // 1. Get the current admin record to find the old image path
    $admin = DB::table('admin')->where('id', $id)->first();
    
    $updateData = [
        'email'  => $request->email,
        'name'   => $request->name,
        'gender' => $request->gender,
        'phone'  => $request->phone,
    ];

    if ($request->filled('password')) {
        $updateData['password'] = Hash::make($request->password);
    }
    // 2. Handle Profile Image Update
  if ($request->hasFile('profile_image')) {
        $image = $request->file('profile_image');
        $filename = time() . '_' . $image->getClientOriginalName();
        
        // Save to public/images
        $image->move(public_path('images'), $filename);
        $new_path = 'images/' . $filename;

        // Delete old file if it's not the default avatar
        if ($admin->profile && $admin->profile !== 'dist/img/avatar.png') {
            $old_file_path = public_path($admin->profile);
            if (File::exists($old_file_path)) {
                File::delete($old_file_path);
            }
        }

        $updateData['profile'] = $new_path;
        
        // Update session immediately for UI refresh
        session(['profile' => $new_path]);
    }

    // 3. Update Database
    DB::table('admin')->where('id', $id)->update($updateData);

    session(['name' => $request->name]);
    $this->logActivity('updated', 'Updated Admin Profile: ' . $request->name);

    session()->flash('save', 'Admin Info updated successfully.');
    return redirect()->back();
}




    /**s
     * Show the dashboard view.
     */
   public function dashboard()
{

 
    $totalProduct = DB::table('product')->count();
    return view('dashboard', compact('totalProduct'));
    
}




// PRODUCT SDIE
public function product() {
    $products = DB::table('product')
    ->Join('category', 'product.category_id', '=', 'category.id' )
    ->Join('perishable', 'product.perishable_id', '=', 'perishable.id' )
    ->select('product.*',
    'category.category_name',
    'perishable.perishable_type')
    ->get();


    $categories = DB::table('category')->select('category.*')->get();
    $perishables = DB::table('perishable')->select('perishable.*')->get();
    return view('product', compact ('products', 'categories', 'perishables'));
}


public function save_product(Request $request)
{
    // dd($request->all());

    $request->validate([
        'productName'  => 'required|string|max:255',
        'category'   => 'required|integer',
        'perishableType' => 'required|integer',
        'quantity'    => 'required|numeric|min:0',
        'packSize'       => 'required|numeric|min:0',
    ]);

    $product    = $request->productName;
    $category   = $request->category;
    $perishable = $request->perishableType;
    $productQuantity = $request->quantity;
    $productSize    = $request->packSize;


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
            'duplicate' => "The product '$product' with these specific bundle details already exists."
        ], 422);
    }


    try {
            DB::table('product')->insert([
                'product_name'  => $product,
                'category_id'   => $category,
                'perishable_id' => $perishable,
                'product_quantity' => $productQuantity,
                'product_size'     => $productSize,
                'created_at'    => now(),
                'updated_at'    => now()
            ]);


            // Create Activity Log
             $userName = session('name');
            $this->logActivity('added', 
            "Added Product | Name: {$request->product_name} | Responsible: {$userName} | Bundle Number: {$request->tie_number} | Bundle Size: {$request->tie_qty}" );
    
       session()->flash('save', 'Product Added successfully.');
        return redirect()->back();

    } catch (\Exception $e) {
        return redirect()->back([
            'errorMessage' => 'An error occurred while saving the product. Please try again.'
        ], 500);
    }
}



// INVENTORY PART
public function inventory() {
    return view('inventory');
}



// INVOICE ENCODER

public function invoiceEncoder(){

    $invoiceInfo = DB::table('purchase')
    ->Join('supplier', 'purchase.supplier_id', '=', 'supplier.id')
    ->select('purchase.*',
    'supplier.supplier_name')
    ->get();

      $products = DB::table('product')
        ->Join('perishable', 'product.perishable_id', '=', 'perishable.id') 
        ->select(
            'product.*',
            'perishable.perishable_type'
        ) 
        ->orderBy('product.product_name', 'ASC')
        ->get();


    $supplier = DB::table('supplier')->get();


return view('invoiceEncoder', compact('invoiceInfo','supplier','products'));
}


public function save_invoiceDetails(Request $request)
{   
    // dd($request->all());
    $request->validate([
      
        'supplierId'      => 'required|integer|exists:supplier,id',
        'invoiceNumber'   => 'required|string|max:100|unique:purchase,invoice_number',
        'invoiceDate'     => 'required|date',
        'invoiceduoDate'  => 'required|date|after_or_equal:invoiceDate',
        'gross_total_raw' => 'required|numeric|min:0',
        'vat_amount_raw'  => 'required|numeric|min:0',
        'grand_total_raw' => 'required|numeric|min:0',
        'productName'     => 'required|array|min:1',
        'productName.*'   => 'required|string|max:255',
        'CSquantity'      => 'required|array|min:1',
        'CSquantity.*'    => 'required|integer|min:1',
        'Quantinumber'    => 'required|array|min:1',
        'Quantinumber.*'  => 'required|integer|min:0',
        'productSize'     => 'required|array|min:1',
        'productSize.*'   => 'required|numeric|min:0',
        'unitPrice'       => 'required|array|min:1',
        'unitPrice.*'     => 'required|numeric|min:0.01',
        'perishableType'  => 'required|array|min:1',
        'perishableType.*'=> 'required|string',
        'expdate'         => 'nullable|array',
        'expdate.*'       => 'nullable|date|after:today',
    ]);

    //  dd('validation passed');
    DB::beginTransaction();

    try {
        
        $invoiceId = DB::table('purchase')->insertGetId([
            'supplier_id'         => $request->supplierId,
            'invoice_number'      => $request->invoiceNumber,
            'invoice_date'        => $request->invoiceDate,
            'invoice_duo_date'    => $request->invoiceduoDate,
            'invoice_grossAmount' => $request->gross_total_raw,
            'invoice_vatAmount'   => $request->vat_amount_raw,
            'invoice_netAmount'   => $request->grand_total_raw,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        //  dd('invoice saved, id: ' . $invoiceId);

        foreach ($request->productName as $key => $name) {
 
            // Check if product exists
                $product = DB::table('product')->where('product_name', $name)->first();

                // If NOT found — stop everything, reject the save
                if (!$product) {
                    DB::rollback();
                    return back()->withInput()->with('errorMessage', 
                        "Product '{$name}' does not exist. Please add it in Product Management first."
                    );
                }

                $productId = $product->id;

                // purchase_items table
                $purchaseItemID = DB::table('purchase_items')->insertGetId([
                    'purchase_id'  => $invoiceId,   
                    'product_id'   => $productId,   
                    'supply_qty'   => $request->CSquantity[$key],
                    'unit_price'   => $request->unitPrice[$key],
                    'total_price'  => $request->Quantinumber[$key] * 
                                    $request->productSize[$key] * 
                                    $request->unitPrice[$key],
                    'grand_total'  => $request->CSquantity[$key] * (
                                        $request->Quantinumber[$key] * 
                                        $request->productSize[$key] * 
                                        $request->unitPrice[$key]
                                    ),
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);


            $batchQty = (int)(
                $request->Quantinumber[$key] * 
                $request->productSize[$key] * 
                $request->CSquantity[$key]
            );

            $isPerishable = strtolower($request->perishableType[$key]) === 'perishable';

            $batchId = DB::table('batch')->insert([
            'purchase_item_id' => $purchaseItemID,  
            'product_id'       => $productId,       
            'exp_date'         => $isPerishable ? $request->expdate[$key] : null,
            'batch_quantity'   => $batchQty,      
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

   
            // DB::table('stock_movements')->insert([
            //     'product_ID'       => $productId,
            //     'purchase_item_id' => $purchaseItemID,
            //     'purchase_id'      => $invoiceId,
            //     'batch_ID'         => $batchId,
            //     'MovementType'     => 'IN',
            //     'quantity'         => $request->CSquantity[$key],
            //     'created_at'       => now(),
            //     'updated_at'       => now(),
            // ]);
        }

        $userName = session('name');
        $this->logActivity(
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

//Supllier

public function save_supplier(Request $request){
    // dd($request->all());

    $request->validate([
        
        'supplierName'  => 'required|string|max:255',
        'supplierAddress'   => 'required|string|max:255',
        'supplierPhone' => 'required|digits:11',
       
    ]);

    $supplierName    = $request->supplierName;
    $supplierAddress   = $request->supplierAddress;
    $supplierPhone = $request->supplierPhone;
  


    $duplicate = DB::table('supplier')
        ->where('supplier_name', $supplierName)
        ->where('address', $supplierAddress)
        ->where('contact_number', $supplierPhone)
        ->exists();

    if ($duplicate) {
        return redirect()->back()->with([
            'duplicate' => "The Supplier $supplierName details already exists."
        ], 422);
    }


    try {
            DB::table('supplier')->insert([
                'supplier_name'  => $supplierName,
                'address'        => $supplierAddress,
                'contact_number' => $supplierPhone,
                'created_at'     => now(),
                'updated_at'     => now()
            ]);


            // Create Activity Log
             $userName = session('name');
            $this->logActivity('added', 
            "Added Supplier | Name: {$request->supplierName} | Responsible: {$userName} | Address: {$request->supplierAddress} | Phone Number: {$request->supplierPhone}" );
    
       session()->flash('save', ' Supplier save successfully.');
        return redirect()->back();

    } catch (\Exception $e) {
        return redirect()->back()->with(
            'errorMessage', $e->getMessage() // show actual error
        );
    }
}

public function supplierList(){

    $supplier = DB::table('supplier')->get();

    return view('supplierList', compact('supplier'));
}

   

        // LOG OUT

public function logout(Request $request)
{
    // 1. Tell Laravel's Auth system to log the current user out.
    Auth::logout();

    // 2. Invalidate the current session and remove all session data.
    // This is the core action that destroys the 'user_role' key and all other data.
    $request->session()->invalidate(); 

    // 3. Regenerate the session's CSRF token for security.
    $request->session()->regenerateToken();

    // 4. Redirect the user.
    return redirect('/');
}

}
