<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;


/*
|--------------------------------------------------------------------------
| Web Routes
|---------------------------------------------*-
-----------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/index', function () {
    return view('welcome');
});

Route::get('/', [AuthController::class, 'main'])->name('login');



Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/authenticate', [AuthController::class, 'auth_user'])->name('auth_user');


Route::group(['prefix' => 'Admin', 'middleware' => ['role:Admin']], function () {
    // Place all admin routes here
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin_profile', [AdminProfileController::class, 'admin_profile'])->name('admin_profile');
    Route::get('/product', [ProductController::class, 'product'])->name('product');
    Route::get('/inventory', [InventoryController::class, 'inventory'])->name('inventory');
    Route::get('/inventory-data', [InventoryController::class, 'view_inventory'])->name('view_inventory');
    Route::get('/invoiceEncoder', [InvoiceController::class, 'invoiceEncoder'])->name('invoiceEncoder');
    Route::get('/supplierList', [SupplierController::class, 'supplierList'])->name('supplierList');
    Route::get('/paymentTracker', [PaymentController::class, 'paymentTracker'])->name('paymentTracker');
    Route::get('/getPaymentHistory/{id}', [PaymentController::class, 'getPaymentHistory'])->name('getPaymentHistory');
    Route::get('/get-products-by-category',[ProductController::class, 'get_products_by_category'])->name('get_products_by_category');
    




    Route::post('/save_product', [ProductController::class, 'save_product'])->name('save_product');
    Route::post('/save_invoiceDetails', [InvoiceController::class, 'save_invoiceDetails'])->name('save_invoiceDetails');
    Route::post('/save_supplier', [SupplierController::class, 'save_supplier'])->name('save_supplier');
    Route::post('/save_payment', [PaymentController::class, 'save_payment'])->name('save_payment');
    Route::post('/save_inventory', [InventoryController::class, 'save_inventory'])->name('save_inventory');
    Route::post('/update_inventory', [InventoryController::class, 'update_inventory'])->name('update_inventory');




    

    
});
