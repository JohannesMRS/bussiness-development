<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::whereBelongsTo($request->user(), 'seller')
            ->with('category')
            ->latest()
            ->paginate(15);

        return view('seller.product.index', compact('products'));
    }

    public function create(): View
    {
        return view('seller.product.create', [
            'categories' => Category::query()->orderBy('name')->get(),
            'bankDetails' => [
                'name' => config('bizdev.bank_name'),
                'account_name' => config('bizdev.bank_account_name'),
                'account_number' => config('bizdev.bank_account_number'),
            ],
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $seller = $request->user();
        $imagePath = $request->file('image')->store('products', 'public');
        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'local');
        $product = $seller->products()->make([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'short_description' => $validated['short_description'],
            'full_description' => $validated['full_description'],
            'price' => $validated['price'],
            'image_url' => $imagePath,
        ]);
        $product->slug = Product::makeUniqueSlug($validated['title']);
        $product->status = Product::STATUS_PENDING;
        $product->is_featured = false;
        $product->rejection_reason = null;
        $product->payment_proof = $proofPath;
        $product->views_count = 0;
        $product->fee_amount = Product::calculateFee((int) $validated['price']);
        $product->save();

        return redirect()->route('seller.product.index')->with('success', 'Produk berhasil diajukan untuk ditinjau.');
    }

    public function show(Request $request, Product $product): View
    {
        $this->ensureOwnership($request, $product);
        $product->load('category');

        return view('seller.product.show', compact('product'));
    }

    public function edit(Request $request, Product $product): View
    {
        $this->ensureOwnership($request, $product);

        return view('seller.product.edit', [
            'product' => $product,
            'categories' => Category::query()->orderBy('name')->get(),
            'bankDetails' => [
                'name' => config('bizdev.bank_name'),
                'account_name' => config('bizdev.bank_account_name'),
                'account_number' => config('bizdev.bank_account_number'),
            ],
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->ensureOwnership($request, $product);
        $validated = $request->validated();
        $oldImagePath = $product->image_url;
        $oldProofPath = $product->payment_proof;
        $priceChanged = (int) $validated['price'] !== (int) $product->price;
        $imagePath = $request->file('image')?->store('products', 'public');
        $proofPath = $request->file('payment_proof')?->store('payment-proofs', 'local');

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

        if ($proofPath !== null) {
            $product->payment_proof = $proofPath;
        }

        if ($priceChanged) {
            $product->fee_amount = Product::calculateFee((int) $product->price);
        }

        $coreDataChanged = $product->isDirty([
            'category_id',
            'title',
            'short_description',
            'full_description',
            'price',
            'image_url',
        ]);
        $productDataChanged = $coreDataChanged || $product->isDirty('payment_proof');

        if (($product->status === Product::STATUS_APPROVED && $coreDataChanged)
            || ($product->status === Product::STATUS_REJECTED && $productDataChanged)) {
            $product->status = Product::STATUS_PENDING;
            $product->rejection_reason = null;
        }

        $product->save();

        if ($imagePath !== null && $oldImagePath !== $imagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        if ($proofPath !== null && $oldProofPath !== $proofPath) {
            Storage::disk('local')->delete($oldProofPath);
        }

        return redirect()->route('seller.product.edit', $product)->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->ensureOwnership($request, $product);
        $this->deleteProductFiles($product);
        $product->delete();

        return redirect()->route('seller.product.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function paymentProof(Request $request, Product $product): BinaryFileResponse
    {
        $this->ensureOwnership($request, $product);
        abort_unless(
            $product->payment_proof !== null && Storage::disk('local')->exists($product->payment_proof),
            404,
        );

        return response()->download(
            Storage::disk('local')->path($product->payment_proof),
            basename($product->payment_proof),
        );
    }

    private function ensureOwnership(Request $request, Product $product): void
    {
        abort_unless($product->user_id === $request->user()->id, 404);
    }

    private function deleteProductFiles(Product $product): void
    {
        Storage::disk('public')->delete($product->image_url);

        if ($product->payment_proof !== null) {
            Storage::disk('local')->delete($product->payment_proof);
        }
    }
}
