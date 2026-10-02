<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $products = Product::with(['seller', 'category'])
            ->when(in_array($status, [Product::STATUS_PENDING, Product::STATUS_APPROVED, Product::STATUS_REJECTED], true), function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.product.index', compact('products', 'status'));
    }

    public function create(): View
    {
        return view('admin.product.create', [
            'categories' => Category::query()->orderBy('name')->get(),
            'sellers' => User::sellers()->active()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $seller = User::sellers()->active()->findOrFail($validated['seller_id']);
        $imagePath = $request->file('image')->store('products', 'public');
        $product = $seller->products()->make([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'short_description' => $validated['short_description'],
            'full_description' => $validated['full_description'],
            'price' => $validated['price'],
            'image_url' => $imagePath,
        ]);
        $product->slug = Product::makeUniqueSlug($validated['title']);
        $product->status = Product::STATUS_APPROVED;
        $product->rejection_reason = null;
        $product->is_featured = false;
        $product->views_count = 0;
        $product->fee_amount = 0;
        $product->payment_proof = null;
        $product->save();

        return redirect()->route('admin.product.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product): View
    {
        return view('admin.product.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('admin.product.edit', [
            'product' => $product,
            'categories' => Category::query()->orderBy('name')->get(),
            'sellers' => User::sellers()->active()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();
        $oldImagePath = $product->image_url;
        $imagePath = $request->file('image')?->store('products', 'public');
        $priceChanged = (int) $validated['price'] !== (int) $product->price;

        $product->category_id = (int) $validated['category_id'];
        $product->title = $validated['title'];
        $product->short_description = $validated['short_description'];
        $product->full_description = $validated['full_description'];
        $product->price = (int) $validated['price'];

        if ($product->isDirty('title')) {
            $product->slug = Product::makeUniqueSlug($product->title, $product->id);
        }

        if ($imagePath !== null) {
            $product->image_url = $imagePath;
        }

        if ($priceChanged && $product->seller?->role === User::ROLE_SELLER) {
            $product->fee_amount = Product::calculateFee((int) $product->price);
        }

        $product->save();

        if ($imagePath !== null && $oldImagePath !== $imagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()->route('admin.product.edit', $product)->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteProductFiles($product);
        $product->delete();

        return redirect()->route('admin.product.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function approve(Product $product): RedirectResponse
    {
        if ($product->seller?->role === User::ROLE_SELLER
            && ($product->payment_proof === null || ! Storage::disk('local')->exists($product->payment_proof))) {
            return back()->withErrors([
                'payment_proof' => 'Bukti transfer seller tidak tersedia. Minta seller mengunggah bukti yang valid.',
            ]);
        }

        $product->status = Product::STATUS_APPROVED;
        $product->rejection_reason = null;
        $product->save();

        return back()->with('success', 'Produk disetujui.');
    }

    public function reject(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:5000'],
        ]);

        $product->status = Product::STATUS_REJECTED;
        $product->rejection_reason = $validated['rejection_reason'];
        $product->save();

        return back()->with('success', 'Produk ditolak.');
    }

    public function toggleFeatured(Product $product): RedirectResponse
    {
        $product->is_featured = ! $product->is_featured;
        $product->save();

        return back()->with('success', 'Status rekomendasi produk diperbarui.');
    }

    public function paymentProof(Product $product): BinaryFileResponse
    {
        abort_unless(
            $product->payment_proof !== null && Storage::disk('local')->exists($product->payment_proof),
            404,
        );

        return response()->download(
            Storage::disk('local')->path($product->payment_proof),
            basename($product->payment_proof),
        );
    }

    private function deleteProductFiles(Product $product): void
    {
        Storage::disk('public')->delete($product->image_url);

        if ($product->payment_proof !== null) {
            Storage::disk('local')->delete($product->payment_proof);
        }
    }
}
