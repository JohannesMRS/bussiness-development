<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'id',
        'user_id',
        'category_id',
        'title',
        'slug',
        'short_description',
        'full_description',
        'price',
        'image_url',
        'status',
        'rejection_reason',
        'is_featured',
        'payment_proof',
        'views_count',
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

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Link WhatsApp sesuai format di PRD, memakai nomor seller. */
    public function getWhatsappUrlAttribute(): string
    {
        $text = sprintf(
            'Halo %s, saya tertarik dengan produk %s di BizDev HMPS MI.',
            $this->seller->name,
            $this->title
        );

        return 'https://wa.me/' . $this->seller->whatsapp_number . '?text=' . rawurlencode($text);
    }
}