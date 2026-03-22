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
        'user_role' => 'admin',
    ]);

    return redirect()->route('admin.dashboard');
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

    $admins = DB::table('admin')->get();

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
    ->Join('category', 'product.id', '=', 'category.id' )
    ->Join('perishable', 'product.id', '=', 'perishable.id' )
    ->select('product.*',
    'category.category_name',
    'perishable.perishable_type')
    ->get();


    $categories = DB::table('category')->select('category.*')->get();
    return view('product', compact ('products', 'categories'));
}


public function save_product(Request $request)
{
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

return view('invoiceEncoder');
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
