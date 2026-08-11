<?php

namespace App\Models;

use App\Support\HasLocalizedSlug;
use App\Support\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasLocalizedSlug, HasTranslations;

    /** Çeviri slug'ları bu alandan üretilir (bkz. HasLocalizedSlug) */
    protected string $slugKaynagi = 'title';

    /** Çevrilebilir alanlar — `_<dil>` kolonları HasTranslations tarafından eklenir */
    protected array $translatable = ['title', 'summary', 'content'];

    protected $fillable = ['title', 'slug', 'icon', 'image', 'summary', 'content', 'sira', 'durum'];

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
