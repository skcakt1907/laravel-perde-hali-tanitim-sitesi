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

if (! function_exists('media')) {
    /**
     * Görsel adresi. Göreli yol verilirse asset()'e çevirir, mutlak URL'i
     * (http/https veya //) olduğu gibi bırakır.
     *
     * Neden: görselleri göreli saklıyoruz ki alan adı değişince (canlıya çıkış,
     * https, farklı port) hiçbir görsel kırılmasın.
     */
    function media(?string $path, ?string $default = null): ?string
    {
        $path = filled($path) ? $path : $default;

        if (blank($path)) {
            return null;
        }

        return preg_match('#^(https?:)?//#i', $path) ? $path : asset(ltrim($path, '/'));
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

if (! function_exists('locale_path_list')) {
    /**
     * Dil öneklerinin okunabilir listesi: "/de/, /nl/, /en/, /tr/"
     *
     * Çerez metninde diller elle yazılıydı; dil eklenince dört sayfada birden
     * yanlış kalıyordu. Bağlaç ("veya/oder/of") dile göre değiştiği için
     * kasıtlı olarak yalnızca virgüllü liste döner.
     */
    function locale_path_list(): string
    {
        return '/' . implode('/, /', Locales::codes()) . '/';
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
