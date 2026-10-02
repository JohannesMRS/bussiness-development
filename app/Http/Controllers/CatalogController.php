<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function home(): View
    {
        $newProducts = Product::publiclyVisible()
            ->with(['seller', 'category'])
            ->latest()
            ->take(8)
            ->get();
        $featuredProducts = Product::publiclyVisible()
            ->featured()
            ->with(['seller', 'category'])
            ->latest()
            ->take(8)
            ->get();
        $categories = Category::query()->orderBy('name')->get();
        $adminNumber = User::normalizeWhatsappNumber(config('bizdev.admin_whatsapp'));
        $adminWhatsappUrl = $adminNumber === null || $adminNumber === ''
            ? null
            : 'https://wa.me/'.$adminNumber;

        $isDemoContact = config('bizdev.is_demo_contact');

        return view('public.home', compact('newProducts', 'featuredProducts', 'categories', 'adminWhatsappUrl', 'isDemoContact'));
    }

    public function index(Request $request): View
    {
        $search = trim($request->string('q')->toString());
        $categorySlug = $request->string('category')->toString();
        $major = $request->string('major')->toString();

        $products = Product::publiclyVisible()
            ->with(['seller', 'category'])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $term = '%'.$search.'%';
                $query->where(function (Builder $searchQuery) use ($term): void {
                    $searchQuery
                        ->where('title', 'ILIKE', $term)
                        ->orWhereHas('seller', function (Builder $sellerQuery) use ($term): void {
                            $sellerQuery->where('bussiness_name', 'ILIKE', $term);
                        });
                });
            })
            ->when($categorySlug !== '', function (Builder $query) use ($categorySlug): void {
                $query->whereHas('category', function (Builder $categoryQuery) use ($categorySlug): void {
                    $categoryQuery->where('slug', $categorySlug);
                });
            })
            ->when(in_array($major, User::MAJORS, true), function (Builder $query) use ($major): void {
                $query->whereHas('seller', function (Builder $sellerQuery) use ($major): void {
                    $sellerQuery->where('major', $major);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('public.catalog.index', [
            'products' => $products,
            'categories' => Category::query()->orderBy('name')->get(),
            'majors' => User::MAJORS,
            'search' => $search,
            'categorySlug' => $categorySlug,
            'major' => $major,
        ]);
    }

    public function show(Product $product): View
    {
        $product = Product::publiclyVisible()
            ->with(['seller', 'category'])
            ->whereKey($product->id)
            ->firstOrFail();

        $viewedProducts = session()->get('viewed_products', []);

        if (! in_array($product->id, $viewedProducts, true)) {
            $product->increment('views_count');
            session()->put('viewed_products', [...$viewedProducts, $product->id]);
        }

        $relatedProducts = Product::publiclyVisible()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['seller', 'category'])
            ->latest()
            ->take(4)
            ->get();

        return view('public.catalog.show', compact('product', 'relatedProducts'));
    }
}
