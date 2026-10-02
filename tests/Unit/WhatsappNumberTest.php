<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class WhatsappNumberTest extends TestCase
{
    public function test_indonesian_whatsapp_number_is_normalized_to_country_code(): void
    {
        $this->assertSame('6281234567890', User::normalizeWhatsappNumber('0812 3456-7890'));
        $this->assertSame('6281234567890', User::normalizeWhatsappNumber('+62 812 3456 7890'));
        $this->assertSame('6281234567890', User::normalizeWhatsappNumber('6281234567890'));
    }
}
