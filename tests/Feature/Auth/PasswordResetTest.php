<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_password_reset_screen_is_not_available(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertNotFound();
    }

    public function test_public_password_reset_link_cannot_be_requested(): void
    {
        $this->post('/forgot-password', ['email' => 'seller@example.com'])->assertNotFound();
    }

    public function test_public_password_reset_token_screen_is_not_available(): void
    {
        $this->get('/reset-password/fake-token')->assertNotFound();
    }

    public function test_public_password_reset_submission_is_not_available(): void
    {
        $this->post('/reset-password', [
            'token' => 'fake-token',
            'email' => 'seller@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertNotFound();
    }
}
