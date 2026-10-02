<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    public const ROLE_ADMIN = 'admin';

    public const ROLE_SELLER = 'seller';

    public const MAJORS = [
        'Manajemen Informatika',
        'Teknik Komputer',
        'Teknik Mesin',
        'Administrasi Bisnis',
    ];

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'whatsapp_number',
        'major',
        'bussiness_name',
        'bussiness_description',
        'avatar_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public static function normalizeWhatsappNumber(?string $number): ?string
    {
        if ($number === null) {
            return null;
        }

        if (! preg_match('/^\+?(?:62|0)[0-9\s().-]*$/', trim($number))) {
            return $number;
        }

        $digits = preg_replace('/\D+/', '', $number) ?? '';

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        return $digits;
    }

    public function scopeSellers(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_SELLER);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        $number = self::normalizeWhatsappNumber($this->whatsapp_number);

        if ($number === null || $number === '') {
            return null;
        }

        $message = sprintf('Halo, saya ingin mengetahui informasi tentang %s.', $this->bussiness_name ?? $this->name);

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }
}
