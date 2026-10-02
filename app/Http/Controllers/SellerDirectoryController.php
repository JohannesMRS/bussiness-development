<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class SellerDirectoryController extends Controller
{
    public function index(): View
    {
        $publicProducts = fn (Builder $query) => $query->publiclyVisible();
        $sellers = User::sellers()
            ->active()
            ->whereHas('products', $publicProducts)
            ->withCount(['products as approved_products_count' => $publicProducts])
            ->orderBy('name')
            ->paginate(12);

        return view('public.sellers.index', compact('sellers'));
    }

    public function show(int $id): View
    {
        $publicProducts = fn (Builder $query) => $query->publiclyVisible();
        $seller = User::sellers()
            ->active()
            ->whereHas('products', $publicProducts)
            ->findOrFail($id);
        $products = Product::publiclyVisible()
            ->whereBelongsTo($seller, 'seller')
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('public.sellers.show', compact('seller', 'products'));
    }
}
