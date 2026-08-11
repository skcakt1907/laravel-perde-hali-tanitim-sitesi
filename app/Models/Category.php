<?php

namespace App\Models;

use App\Support\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasTranslations;

    /** Çevrilebilir alanlar — `_<dil>` kolonları HasTranslations tarafından eklenir */
    protected array $translatable = ['name', 'description'];

    protected $fillable = ['parent_id', 'name', 'slug', 'icon', 'image', 'description', 'sira', 'durum'];

    protected $casts = [
        'durum' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sira');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

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
        return media($this->image) ?: 'https://placehold.co/800x600/141414/c9a227?text=MC+Gordijnen';
    }
}
