<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'category', 'brand', 'gender', 'frame_shape', 'description',
        'price', 'sale_price', 'stock', 'image', 'is_featured', 'is_active',
    ];

    protected $appends = ['image_url', 'final_price', 'category_label', 'in_stock'];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'sale_price' => 'integer',
            'stock' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Always keep a unique, URL-friendly slug.
        static::saving(function (Product $product) {
            if (blank($product->slug) || $product->isDirty('name')) {
                $product->slug = static::uniqueSlug($product->name, $product->id);
            }
        });

        // Remove the image file when a product is deleted.
        static::deleted(function (Product $product) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /* ---------- Scopes ---------- */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['category'] ?? null, fn ($q, $c) => $q->where('category', $c))
            ->when($filters['search'] ?? null, function ($q, $s) {
                $q->where(fn ($q) => $q
                    ->where('name', 'like', "%{$s}%")
                    ->orWhere('brand', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%"));
            })
            ->when($filters['sort'] ?? 'newest', function ($q, $sort) {
                match ($sort) {
                    'price_asc' => $q->orderByRaw('COALESCE(sale_price, price) asc'),
                    'price_desc' => $q->orderByRaw('COALESCE(sale_price, price) desc'),
                    'name' => $q->orderBy('name'),
                    default => $q->latest(),
                };
            });
    }

    /* ---------- Accessors ---------- */

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    public function getFinalPriceAttribute(): int
    {
        return $this->sale_price && $this->sale_price < $this->price ? $this->sale_price : $this->price;
    }

    public function getCategoryLabelAttribute(): string
    {
        return config("khalil.product_categories.{$this->category}", Str::headline($this->category));
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }
}
