<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');


// Seller
Route::get('/seller/dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');

Route::get('/seller/product/', [ProductController::class, 'index'])->name('seller.product.index');
Route::get('/seller/product/{id}', [ProductController::class, 'show'])->name('seller.product.show');
Route::post('/seller/product/store', [ProductController::class, 'store'])->name('seller.product.store');