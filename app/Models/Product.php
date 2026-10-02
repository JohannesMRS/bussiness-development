<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'category_id',
        'title',
        'short_description',
        'full_description',
        'price',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_featured' => 'boolean',
            'views_count' => 'integer',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query->approved()->whereHas('seller', function (Builder $sellerQuery): void {
            $sellerQuery
                ->where('role', User::ROLE_SELLER)
                ->where('is_active', true);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: 'product';
        $slug = $baseSlug;
        $suffix = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId !== null, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    /** Link WhatsApp sesuai format di PRD, memakai nomor seller. */
    public function getWhatsappUrlAttribute(): ?string
    {
        $seller = $this->seller;
        $number = User::normalizeWhatsappNumber($seller?->whatsapp_number);

        if ($seller === null || $number === null || $number === '') {
            return null;
        }

        $text = sprintf(
            'Halo %s, saya tertarik dengan produk %s di BizDev HMPS MI.',
            $seller->name,
            $this->title
        );

        return 'https://wa.me/'.$number.'?text='.rawurlencode($text);
    }

    public static function calculateFee(int $price): int
    {
        return intdiv($price, 100) + (($price % 100) >= 50 ? 1 : 0);
    }
}
