<?php

namespace App\Models;

use App\Support\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasTranslations;

    /** Çevrilebilir alanlar — `_<dil>` kolonları HasTranslations tarafından eklenir */
    protected array $translatable = ['title', 'category', 'summary', 'content'];

    protected $fillable = ['title', 'slug', 'category', 'image', 'summary', 'content', 'tarih', 'durum'];

    protected $casts = ['durum' => 'boolean', 'tarih' => 'date'];

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
        return $this->image ?: 'https://placehold.co/900x600/141414/c9a227?text=MC+Gordijnen';
    }
}
