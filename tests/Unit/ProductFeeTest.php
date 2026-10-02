<?php

namespace Tests\Unit;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductFeeTest extends TestCase
{
    public function test_fee_is_one_percent_rounded_half_up_to_whole_rupiah(): void
    {
        $this->assertSame(50, Product::calculateFee(5000));
        $this->assertSame(2, Product::calculateFee(150));
        $this->assertSame(1, Product::calculateFee(149));
        $this->assertSame(0, Product::calculateFee(49));
    }

    public function test_mass_assignment_cannot_set_product_owner_or_approval_flags(): void
    {
        $product = new Product;
        $product->fill([
            'user_id' => 999,
            'status' => Product::STATUS_APPROVED,
            'is_featured' => true,
            'views_count' => 50,
            'fee_amount' => 500,
        ]);

        $this->assertSame([], $product->getAttributes());
    }
}
