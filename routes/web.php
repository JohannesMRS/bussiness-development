<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin
Route::middleware(['auth', 'verified', 'role:admin'])
->prefix('admin')->group(function(){
    Route::get('/admin/index', [AdminDashboardController::class, 'index'])->name('admin.index');
    Route::get('/admin/product', [AdminProductController::class, 'index'])->name('admin.product.index');
    Route::post('/admin/product/store', [AdminProductController::class, 'store'])->name('admin.product.store');
    Route::get('/admin/product/{id}', [AdminProductController::class, 'show'])->name('admin.product.show');
    Route::get('/admin/product/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.product.edit');
    Route::put('/admin/product/{id}', [AdminProductController::class, 'update'])->name('admin.product.update'); 
});



// Seller
Route::middleware(['auth', 'verified', 'role:seller'])
->group(function(){
    Route::get('/seller/index', [SellerDashboardController::class, 'index'])->name('seller.index');
    Route::get('/seller/product', [SellerProductController::class, 'index'])->name('seller.product.index');
    Route::post('/seller/product/store', [SellerProductController::class, 'store'])->name('seller.product.store');
    Route::get('/seller/product/{id}', [SellerProductController::class, 'show'])->name('seller.product.show');
    Route::get('/seller/product/{id}/edit', [SellerProductController::class, 'edit'])->name('seller.product.edit');
    Route::put('/seller/product/{id}', [SellerProductController::class, 'update'])->name('seller.product.update');
});