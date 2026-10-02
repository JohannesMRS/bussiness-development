<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\SellerDirectoryController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::middleware(SetLocale::class)->group(function (): void {
    Route::get('/', [CatalogController::class, 'home'])->name('home');
    Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/produk/{product:slug}', [CatalogController::class, 'show'])->name('products.show');
    Route::get('/penjual', [SellerDirectoryController::class, 'index'])->name('sellers.index');
    Route::get('/penjual/{id}', [SellerDirectoryController::class, 'show'])->whereNumber('id')->name('sellers.show');
    Route::get('/tentang-kami', AboutController::class)->name('about');
    Route::post('/locale', [LocaleController::class, 'update'])->name('locale.switch');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/index', [AdminDashboardController::class, 'index'])->name('index');
    Route::resource('product', AdminProductController::class);
    Route::post('product/{product}/approve', [AdminProductController::class, 'approve'])->name('product.approve');
    Route::post('product/{product}/reject', [AdminProductController::class, 'reject'])->name('product.reject');
    Route::patch('product/{product}/featured', [AdminProductController::class, 'toggleFeatured'])->name('product.featured');
    Route::get('product/{product}/payment-proof', [AdminProductController::class, 'paymentProof'])->name('product.payment-proof');

    Route::resource('seller', SellerController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    Route::patch('seller/{seller}/active', [SellerController::class, 'toggleActive'])->name('seller.active');
    Route::put('seller/{seller}/password', [SellerController::class, 'resetPassword'])->name('seller.password');
});

Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/index', [SellerDashboardController::class, 'index'])->name('index');
    Route::resource('product', SellerProductController::class);
    Route::get('product/{product}/payment-proof', [SellerProductController::class, 'paymentProof'])->name('product.payment-proof');
});
