<?php

namespace App\Models;

use App\Support\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasTranslations;

    /** Çevrilebilir alanlar — `_<dil>` kolonları HasTranslations tarafından eklenir */
    protected array $translatable = ['name', 'short_desc', 'description'];

    protected $fillable = ['category_id', 'name', 'slug', 'sku', 'brand', 'cover', 'images',
        'short_desc', 'description', 'price', 'price_unit',
        'attributes', 'featured', 'sira', 'durum'];

    /** Özellik tablosu her dilde ayrı JSON kolonunda tutulur */
    protected array $translatableJson = ['attributes'];

    protected $casts = [
        'images'     => 'array',
        'attributes' => 'array',
        'attributes_en' => 'array',
        'attributes_tr' => 'array',
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

    /**
     * Geçerli dildeki özellik tablosu; o dilde girilmemişse ana dile düşer.
     *
     * @return array<string,string>
     */
    public function getAttributesForAttribute(): array
    {
        $locale = app()->getLocale();

        if ($locale !== \App\Support\Locales::primary()) {
            $translated = $this->getAttribute('attributes_' . $locale);

            if (filled($translated)) {
                return $translated;
            }
        }

        return $this->attributes_list;
    }

    /** Ana dildeki özellik tablosu (Eloquent'in `attributes` çakışmasını atlatır) */
    public function getAttributesListAttribute(): array
    {
        return $this->getAttribute('attributes') ?? [];
    }

    /** Kapak + galeri, tek dizide */
    public function getGalleryAttribute(): array
    {
        return array_values(array_filter(array_merge([$this->cover], $this->images ?? [])));
    }
}
