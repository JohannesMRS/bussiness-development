<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicLocaleTest extends TestCase
{
    public function test_supported_public_locale_is_saved_in_the_session(): void
    {
        $response = $this->from(route('home', absolute: false))
            ->post(route('locale.switch'), ['locale' => 'en']);

        $response->assertRedirect(route('home', absolute: false))
            ->assertSessionHas('locale', 'en');
    }

    public function test_unsupported_public_locale_is_rejected(): void
    {
        $response = $this->from(route('home', absolute: false))
            ->post(route('locale.switch'), ['locale' => 'fr']);

        $response->assertSessionHasErrors('locale')
            ->assertSessionMissing('locale');
    }
}
