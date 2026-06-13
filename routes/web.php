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
use App\Http\Controllers\FinanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/index', function () {
    return view('themes.welcome.welcome');
});

Route::get('/', [AuthController::class, 'main'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/authenticate', [AuthController::class, 'auth_user'])->name('auth_user');

Route::group(['prefix' => 'Admin', 'middleware' => ['role:Admin', 'branch']], function () {
    // Dashboard & Profiles
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin_profile', [AdminProfileController::class, 'admin_profile'])->name('admin_profile');
    
    // Product Management Routes
    Route::get('/product', [ProductController::class, 'product'])->name('product');
    Route::get('/product-data', [ProductController::class, 'view_product'])->name('view_product');
    Route::get('/get-products-by-category', [ProductController::class, 'get_products_by_category'])->name('get_products_by_category');
    Route::get('/product-archives', [ProductController::class, 'product_archive'])->name('product_archive');
    Route::get('/view-product-archives', [ProductController::class, 'view_product_archive'])->name('view_product_archive');

    Route::post('/update_product', [ProductController::class, 'update_product'])->name('update_product');
    Route::post('/save_product', [ProductController::class, 'save_product'])->name('save_product');
    Route::post('/update_product', [ProductController::class, 'update_product'])->name('update_product');
    Route::post('/soft-delete-product/{id}', [ProductController::class, 'soft_delete_product'])->name('soft_delete_product');
    Route::post('/restore-product/{id}', [ProductController::class, 'restore_product'])->name('restore_product');
    Route::delete('/force-delete-product/{id}', [ProductController::class, 'force_delete_product'])->name('force_delete_product');

    // Inventory Management Routes
    Route::get('/inventory', [InventoryController::class, 'inventory'])->name('inventory');
    Route::get('/inventory-data', [InventoryController::class, 'view_inventory'])->name('view_inventory');
    Route::get('/inventory-archives', [InventoryController::class, 'inventory_archive'])->name('inventory_archive');
    Route::get('/get-inventory-by-product/{productId}', [InventoryController::class, 'get_inventory_by_product'])->name('get_inventory_by_product');
    Route::get('/view-inventory-archives', [InventoryController::class, 'view_inventory_archive'])->name('view_inventory_archive');
    Route::get('/inventory-sales-history', [InventoryController::class, 'inventory_sales_history'])->name('inventory_sales_history');
    Route::get('/view-sales-history', [InventoryController::class, 'view_sales_history'])->name('view_sales_history');

    Route::post('/soft-delete-inventory/{id}', [InventoryController::class, 'soft_delete_inventory'])->name('soft_delete_inventory');
    Route::post('/save_inventory', [InventoryController::class, 'save_inventory'])->name('save_inventory');
    Route::post('/update_inventory', [InventoryController::class, 'update_inventory'])->name('update_inventory');
    Route::post('/restore-inventory/{id}', [InventoryController::class, 'restore_inventory'])->name('restore_inventory');
    Route::post('/add-sale', [InventoryController::class, 'add_sale_record'])->name('add_sale_record');
    Route::delete('/force-delete-inventory/{id}', [InventoryController::class, 'force_delete_inventory'])->name('force_delete_inventory');
    
    // Invoices, Suppliers & Payments
    Route::get('/invoiceEncoder', [InvoiceController::class, 'invoiceEncoder'])->name('invoiceEncoder');
    Route::post('/save_invoiceDetails', [InvoiceController::class, 'save_invoiceDetails'])->name('save_invoiceDetails');
    
    Route::get('/supplierList', [SupplierController::class, 'supplierList'])->name('supplierList');
    Route::post('/save_supplier', [SupplierController::class, 'save_supplier'])->name('save_supplier');
    
    Route::get('/paymentTracker', [PaymentController::class, 'paymentTracker'])->name('paymentTracker');
    Route::get('/getPaymentHistory/{id}', [PaymentController::class, 'getPaymentHistory'])->name('getPaymentHistory');
    Route::post('/save_payment', [PaymentController::class, 'save_payment'])->name('save_payment');

    // Finance 
    Route::get('/finance', [FinanceController::class, 'finance'])->name('finance');
    Route::get('/daily-transaction', [FinanceController::class, 'DailyTransction'])->name('DailyTransction');




});