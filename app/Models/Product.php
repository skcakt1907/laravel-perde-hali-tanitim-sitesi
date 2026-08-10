<?php

namespace App\Models;

use App\Support\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasTranslations;

    protected $fillable = [
        'category_id', 'name', 'name_tr', 'slug', 'sku', 'brand', 'cover', 'images',
        'short_desc', 'short_desc_tr', 'description', 'description_tr',
        'price', 'price_unit', 'attributes', 'featured', 'sira', 'durum',
    ];

    protected $casts = [
        'images'     => 'array',
        'attributes' => 'array',
        'featured'   => 'boolean',
        'durum'      => 'boolean',
        'price'      => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($q)
    {
        return $q->where('durum', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Fiyat girilmediyse "Preis auf Anfrage" gösterilir */
    public function getHasPriceAttribute(): bool
    {
        return (float) $this->price > 0;
    }

    public function getImageUrlAttribute(): string
    {
        return $this->cover ?: 'https://placehold.co/800x800/141414/c9a227?text=MC+Gordijnen';
    }

    /** Kapak + galeri, tek dizide */
    public function getGalleryAttribute(): array
    {
        return array_values(array_filter(array_merge([$this->cover], $this->images ?? [])));
    }
}
