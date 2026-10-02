<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::whereBelongsTo($request->user(), 'seller')
            ->with('category')
            ->latest()
            ->paginate(10);
        $productCounts = Product::whereBelongsTo($request->user(), 'seller')
            ->selectRaw('status, COUNT(*) AS aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return view('seller.index', compact('products', 'productCounts'));
    }
}
