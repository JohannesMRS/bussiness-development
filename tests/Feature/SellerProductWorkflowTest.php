<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SellerProductWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_submission_is_pending_and_stores_fee_and_private_proof(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        $seller = User::factory()->create();
        $category = Category::create(['name' => 'Kuliner', 'slug' => 'kuliner']);

        $response = $this->actingAs($seller)->post(route('seller.product.store'), [
            'category_id' => $category->id,
            'title' => 'Produk Demo',
            'short_description' => 'Ringkasan produk demo.',
            'full_description' => 'Deskripsi produk demo lengkap.',
            'price' => 5000,
            'image' => UploadedFile::fake()->image('produk.jpg'),
            'payment_proof' => UploadedFile::fake()->image('bukti.png'),
        ]);

        $response->assertRedirect(route('seller.product.index'))->assertSessionHasNoErrors();

        $product = Product::query()->where('title', 'Produk Demo')->firstOrFail();
        $this->assertSame(Product::STATUS_PENDING, $product->status);
        $this->assertSame(50, $product->fee_amount);
        $this->assertSame($seller->id, $product->user_id);
        $this->assertTrue(Storage::disk('public')->exists($product->image_url));
        $this->assertTrue(Storage::disk('local')->exists($product->payment_proof));
    }

    public function test_price_change_requires_a_new_payment_proof(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        $seller = User::factory()->create();
        $category = Category::create(['name' => 'Jasa', 'slug' => 'jasa']);
        $product = $this->createProduct($seller, $category, [
            'status' => Product::STATUS_APPROVED,
            'price' => 10000,
            'fee_amount' => 100,
            'payment_proof' => 'payment-proofs/old-proof.png',
        ]);
        Storage::disk('local')->put($product->payment_proof, 'old proof');

        $this->actingAs($seller)
            ->put(route('seller.product.update', $product), $this->updatePayload($product, ['price' => 15000]))
            ->assertSessionHasErrors('payment_proof');

        $response = $this->actingAs($seller)->put(
            route('seller.product.update', $product),
            $this->updatePayload($product, [
                'price' => 15000,
                'payment_proof' => UploadedFile::fake()->image('bukti-baru.png'),
            ]),
        );

        $response->assertRedirect(route('seller.product.edit', $product->fresh()))->assertSessionHasNoErrors();
        $product->refresh();

        $this->assertSame(Product::STATUS_PENDING, $product->status);
        $this->assertSame(150, $product->fee_amount);
        $this->assertNotSame('payment-proofs/old-proof.png', $product->payment_proof);
        $this->assertFalse(Storage::disk('local')->exists('payment-proofs/old-proof.png'));
        $this->assertTrue(Storage::disk('local')->exists($product->payment_proof));
    }

    public function test_editing_rejected_product_returns_it_to_pending_and_clears_reason(): void
    {
        $seller = User::factory()->create();
        $category = Category::create(['name' => 'Teknologi', 'slug' => 'teknologi']);
        $product = $this->createProduct($seller, $category, [
            'status' => Product::STATUS_REJECTED,
            'rejection_reason' => 'Perlu diperbaiki.',
        ]);

        $response = $this->actingAs($seller)->put(
            route('seller.product.update', $product),
            $this->updatePayload($product, ['title' => 'Judul sudah diperbaiki']),
        );

        $response->assertRedirect(route('seller.product.edit', $product->fresh()))->assertSessionHasNoErrors();
        $this->assertSame(Product::STATUS_PENDING, $product->fresh()->status);
        $this->assertNull($product->fresh()->rejection_reason);
    }

    public function test_editing_only_the_payment_proof_of_a_rejected_product_resubmits_it(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $seller = User::factory()->create();
        $category = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
        $product = $this->createProduct($seller, $category, [
            'status' => Product::STATUS_REJECTED,
            'rejection_reason' => 'Bukti lama tidak jelas.',
        ]);
        Storage::disk('local')->put($product->payment_proof, 'old proof');

        $payload = $this->updatePayload($product, [
            'payment_proof' => UploadedFile::fake()->image('proof-new.png'),
        ]);
        $this->actingAs($seller)
            ->put(route('seller.product.update', $product), $payload)
            ->assertRedirect(route('seller.product.edit', $product->fresh()))
            ->assertSessionHasNoErrors();

        $this->assertSame(Product::STATUS_PENDING, $product->fresh()->status);
        $this->assertNull($product->fresh()->rejection_reason);
    }

    /** @param array<string, mixed> $overrides */
    private function createProduct(User $seller, Category $category, array $overrides = []): Product
    {
        $product = $seller->products()->make([
            'category_id' => $category->id,
            'title' => 'Produk Uji',
            'short_description' => 'Ringkasan uji.',
            'full_description' => 'Deskripsi uji.',
            'price' => 10000,
            'image_url' => 'products/existing.jpg',
        ]);
        $product->slug = 'produk-uji';
        $product->status = Product::STATUS_APPROVED;
        $product->fee_amount = 100;
        $product->payment_proof = 'payment-proofs/existing.png';

        foreach ($overrides as $attribute => $value) {
            $product->{$attribute} = $value;
        }

        $product->save();

        return $product;
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function updatePayload(Product $product, array $overrides = []): array
    {
        return array_merge([
            'category_id' => $product->category_id,
            'title' => $product->title,
            'short_description' => $product->short_description,
            'full_description' => $product->full_description,
            'price' => $product->price,
        ], $overrides);
    }
}
