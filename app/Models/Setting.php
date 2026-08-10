<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['anahtar', 'deger'];
    public $timestamps = true;

    public static function get(string $key, $default = null)
    {
        $all = Cache::rememberForever('settings_all', function () {
            return static::pluck('deger', 'anahtar')->toArray();
        });

        return $all[$key] ?? $default;
    }

    public static function put(string $key, $value): void
    {
        static::updateOrCreate(['anahtar' => $key], ['deger' => $value]);
        Cache::forget('settings_all');
    }

    public static function flush(): void
    {
        Cache::forget('settings_all');
    }
}
