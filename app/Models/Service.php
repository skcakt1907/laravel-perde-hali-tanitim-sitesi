<?php

namespace App\Models;

use App\Support\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'title_tr', 'slug', 'icon', 'image',
        'summary', 'summary_tr', 'content', 'content_tr', 'sira', 'durum',
    ];

    protected $casts = ['durum' => 'boolean'];

    public function scopeActive($q)
    {
        return $q->where('durum', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
