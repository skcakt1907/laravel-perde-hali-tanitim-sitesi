<?php

namespace App\Models;

use App\Support\HasTranslations;
use Illuminate\Database\Eloquent\Model;

/** Yapılan işler / Referenz-Projekte — galeri kalemi */
class Project extends Model
{
    use HasTranslations;

    /** Çevrilebilir alanlar — `_<dil>` kolonları HasTranslations tarafından eklenir */
    protected array $translatable = ['title', 'kind', 'summary', 'content'];

    protected $fillable = ['title', 'slug', 'location', 'kind', 'cover', 'images',
        'summary', 'content', 'tarih', 'featured', 'sira', 'durum'];

    protected $casts = [
        'images'   => 'array',
        'featured' => 'boolean',
        'durum'    => 'boolean',
        'tarih'    => 'date',
    ];

    public function scopeActive($q)
    {
        return $q->where('durum', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getImageUrlAttribute(): string
    {
        return $this->cover ?: 'https://placehold.co/900x700/141414/c9a227?text=MC+Gordijnen';
    }

    public function getGalleryAttribute(): array
    {
        return array_values(array_filter(array_merge([$this->cover], $this->images ?? [])));
    }
}
