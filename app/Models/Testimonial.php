<?php

namespace App\Models;

use App\Support\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name', 'title', 'title_tr', 'comment', 'comment_tr', 'stars', 'photo', 'durum',
    ];

    protected $casts = ['durum' => 'boolean'];

    public function scopeActive($q)
    {
        return $q->where('durum', true);
    }
}
