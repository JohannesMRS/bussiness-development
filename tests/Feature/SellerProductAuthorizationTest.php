<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SellerProductAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_product_index_only_displays_its_own_products(): void
    {
        $seller = User::factory()->create();
        $otherSeller = User::factory()->create();
        $category = Category::create(['name' => 'Jasa', 'slug' => 'jasa']);
        $ownProduct = $this->createProduct($seller, $category, 'Produk Milik Saya');
        $otherProduct = $this->createProduct($otherSeller, $category, 'Produk Seller Lain');

        $response = $this->actingAs($seller)->get(route('seller.product.index'));

        $response->assertOk()->assertSee($ownProduct->title)->assertDontSee($otherProduct->title);
    }

    public function test_seller_cannot_view_another_sellers_product(): void
    {
        $seller = User::factory()->create();
        $otherSeller = User::factory()->create();
        $category = Category::create(['name' => 'Kuliner', 'slug' => 'kuliner']);
        $product = $this->createProduct($otherSeller, $category, 'Produk Rahasia Seller');

        $this->actingAs($seller)
            ->get(route('seller.product.show', $product))
            ->assertNotFound();
    }

    public function test_seller_cannot_delete_another_sellers_product(): void
    {
        $seller = User::factory()->create();
        $otherSeller = User::factory()->create();
        $category = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
        $product = $this->createProduct($otherSeller, $category, 'Produk Tidak Boleh Dihapus');

        $this->actingAs($seller)
            ->delete(route('seller.product.destroy', $product))
            ->assertNotFound();

        $this->assertModelExists($product);
    }

    private function createProduct(User $seller, Category $category, string $title): Product
    {
        $product = $seller->products()->make([
            'category_id' => $category->id,
            'title' => $title,
            'short_description' => 'Deskripsi singkat',
            'full_description' => 'Deskripsi lengkap',
            'price' => 10000,
            'image_url' => 'products/example.jpg',
        ]);
        $product->slug = Str::slug($title);
        $product->save();

        return $product;
    }
}
