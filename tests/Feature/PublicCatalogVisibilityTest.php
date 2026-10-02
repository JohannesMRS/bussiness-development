<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicCatalogVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_displays_only_approved_products_from_active_sellers(): void
    {
        $category = Category::create(['name' => 'Kuliner', 'slug' => 'kuliner']);
        $activeSeller = User::factory()->create();
        $inactiveSeller = User::factory()->create();
        $inactiveSeller->is_active = false;
        $inactiveSeller->save();

        $this->createProduct($activeSeller, $category, 'Produk approved terlihat', Product::STATUS_APPROVED);
        $this->createProduct($activeSeller, $category, 'Produk pending tersembunyi', Product::STATUS_PENDING);
        $this->createProduct($inactiveSeller, $category, 'Produk seller nonaktif tersembunyi', Product::STATUS_APPROVED);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Produk approved terlihat')
            ->assertDontSee('Produk pending tersembunyi')
            ->assertDontSee('Produk seller nonaktif tersembunyi');
    }

    public function test_seller_directory_requires_an_active_seller_with_an_approved_product(): void
    {
        $category = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
        $activeSeller = User::factory()->create(['name' => 'Seller Aktif']);
        $pendingSeller = User::factory()->create(['name' => 'Seller Pending']);
        $inactiveSeller = User::factory()->create(['name' => 'Seller Nonaktif']);
        $inactiveSeller->is_active = false;
        $inactiveSeller->save();

        $this->createProduct($activeSeller, $category, 'Produk aktif disetujui', Product::STATUS_APPROVED);
        $this->createProduct($pendingSeller, $category, 'Produk masih pending', Product::STATUS_PENDING);
        $this->createProduct($inactiveSeller, $category, 'Produk owner nonaktif', Product::STATUS_APPROVED);

        $this->get(route('sellers.index'))
            ->assertOk()
            ->assertSee('Seller Aktif')
            ->assertDontSee('Seller Pending')
            ->assertDontSee('Seller Nonaktif');
    }

    private function createProduct(User $seller, Category $category, string $title, string $status): Product
    {
        $product = $seller->products()->make([
            'category_id' => $category->id,
            'title' => $title,
            'short_description' => 'Ringkasan katalog.',
            'full_description' => 'Deskripsi produk katalog.',
            'price' => 10000,
            'image_url' => 'images/products/placeholder.svg',
        ]);
        $product->slug = Str::slug($title);
        $product->status = $status;
        $product->fee_amount = 100;
        $product->save();

        return $product;
    }
}
