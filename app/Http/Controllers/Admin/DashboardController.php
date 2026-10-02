<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $productCounts = Product::query()
            ->selectRaw('status, COUNT(*) AS aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');
        $sellerCount = User::sellers()->count();
        $activeSellerCount = User::sellers()->active()->count();
        $recentProducts = Product::with('seller')->latest()->take(5)->get();

        return view('admin.index', compact('productCounts', 'sellerCount', 'activeSellerCount', 'recentProducts'));
    }
}
