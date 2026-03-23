<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\StudentController;


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

Route::get('/', [MainController::class, 'main'])->name('login');



Route::get('/logout', [MainController::class, 'logout'])->name('logout');
Route::post('/authenticate', [MainController::class, 'auth_user'])->name('auth_user');


Route::group(['prefix' => 'Admin', 'middleware' => ['role:Admin']], function () {
    // Place all admin routes here
    Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin_profile', [MainController::class, 'admin_profile'])->name('admin_profile');
    Route::get('/product', [MainController::class, 'product'])->name('product');
    Route::get('/inventory', [MainController::class, 'inventory'])->name('inventory');
    Route::get('/invoiceEncoder', [MainController::class, 'invoiceEncoder'])->name('invoiceEncoder');


    Route::post('/save_product', [MainController::class, 'save_product'])->name('save_product');
    

    
});

