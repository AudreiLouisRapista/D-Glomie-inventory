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
|--------------------------------------------------------------------------
*/

Route::get('/index', function () {
    return view('welcome');
});

Route::get('/', [AuthController::class, 'main'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/authenticate', [AuthController::class, 'auth_user'])->name('auth_user');

Route::group(['prefix' => 'Admin', 'middleware' => ['role:Admin']], function () {
    // Dashboard & Profiles
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin_profile', [AdminProfileController::class, 'admin_profile'])->name('admin_profile');
    
    // Product Management Routes
    Route::get('/product', [ProductController::class, 'product'])->name('product');
    Route::get('/product-data', [ProductController::class, 'view_product'])->name('view_product');
    Route::get('/get-products-by-category', [ProductController::class, 'get_products_by_category'])->name('get_products_by_category');
    Route::post('/update_product', [ProductController::class, 'update_product'])->name('update_product');
    Route::post('/save_product', [ProductController::class, 'save_product'])->name('save_product');
    
    // Inventory Management Routes
    Route::get('/inventory', [InventoryController::class, 'inventory'])->name('inventory');
    Route::get('/inventory-data', [InventoryController::class, 'view_inventory'])->name('view_inventory');
    Route::post('/save_inventory', [InventoryController::class, 'save_inventory'])->name('save_inventory');
    Route::post('/update_inventory', [InventoryController::class, 'update_inventory'])->name('update_inventory');
    
    // Invoices, Suppliers & Payments
    Route::get('/invoiceEncoder', [InvoiceController::class, 'invoiceEncoder'])->name('invoiceEncoder');
    Route::post('/save_invoiceDetails', [InvoiceController::class, 'save_invoiceDetails'])->name('save_invoiceDetails');
    
    Route::get('/supplierList', [SupplierController::class, 'supplierList'])->name('supplierList');
    Route::post('/save_supplier', [SupplierController::class, 'save_supplier'])->name('save_supplier');
    
    Route::get('/paymentTracker', [PaymentController::class, 'paymentTracker'])->name('paymentTracker');
    Route::get('/getPaymentHistory/{id}', [PaymentController::class, 'getPaymentHistory'])->name('getPaymentHistory');
    Route::post('/save_payment', [PaymentController::class, 'save_payment'])->name('save_payment');
});