<?php

use App\Models\Setting;
use App\Support\Locales;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('tsetting')) {
    /**
     * Dile göre ayar: önce `anahtar_tr` denenir, yoksa Almanca temel anahtara düşer.
     */
    function tsetting(string $key, $default = null)
    {
        if (app()->getLocale() !== Locales::primary()) {
            $value = Setting::get($key . '_' . app()->getLocale());

            if (filled($value)) {
                return $value;
            }
        }

        return Setting::get($key, $default);
    }
}

if (! function_exists('money')) {
    function money($amount): string
    {
        return number_format((float) $amount, 2, ',', '.') . ' €';
    }
}

if (! function_exists('locales')) {
    /** @return array<string,string> */
    function locales(): array
    {
        return Locales::labels();
    }
}

if (! function_exists('locale_url')) {
    /**
     * Bulunulan sayfanın başka dildeki karşılığı (yol aynı, yalnızca dil öneki değişir).
     */
    function locale_url(string $locale): string
    {
        // Yolun ilk parçasını değiştiriyoruz. (Rota parametresi üzerinden gitmiyoruz:
        // SetLocale middleware'i {locale}'i rotadan düşürüyor.)
        $segments = request()->segments();

        if (isset($segments[0]) && array_key_exists($segments[0], locales())) {
            $segments[0] = $locale;
        } else {
            array_unshift($segments, $locale);
        }

        $url   = url(implode('/', $segments));
        $query = request()->getQueryString();

        return $query ? $url . '?' . $query : $url;
    }
}
