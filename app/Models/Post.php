<?php

namespace App\Models;

use App\Support\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'title_tr', 'slug', 'category', 'category_tr', 'image',
        'summary', 'summary_tr', 'content', 'content_tr', 'tarih', 'durum',
    ];

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
