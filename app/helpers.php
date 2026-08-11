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

if (! function_exists('locale_code_list')) {
    /**
     * Dil kodlarının okunabilir listesi: "nl, de, en, tr"
     *
     * Çerez aydınlatma metninde diller elle yazılıydı; dil eklenince dört
     * sayfada birden yanlış kalıyordu. Bağlaç ("veya/oder/of") dile göre
     * değiştiği için kasıtlı olarak yalnızca virgüllü liste döner.
     */
    function locale_code_list(): string
    {
        return implode(', ', Locales::codes());
    }
}

if (! function_exists('locale_url')) {
    /**
     * Bulunulan sayfanın başka dildeki adresi.
     *
     * Yol adları ve içerik slug'ları dile göre değiştiği için gerçek bir
     * karşılık üretilebiliyor: `/produkte/gardinen` ↔ `/producten/gordijnen`.
     * Hem dil değiştiricide hem `hreflang` etiketlerinde kullanılır.
     *
     * Rota adları ikincil dillerde `de.catalog` gibi önekli; kanonik ada
     * indirip hedef dilde tekrar üretiyoruz. Parametreler ÇÖZÜLMÜŞ model
     * nesneleri olduğu için `getRouteKey()` hedef dilin slug'ını döndürür.
     */
    function locale_url(string $locale): string
    {
        $rota = request()->route();
        $ad   = $rota?->getName();

        if ($ad === null) {
            return url('/');
        }

        // 'de.catalog.category' → 'catalog.category'
        foreach (Locales::codes() as $kod) {
            if (str_starts_with($ad, $kod . '.')) {
                $ad = substr($ad, strlen($kod) + 1);
                break;
            }
        }

        $oncekiDil = app()->getLocale();
        app()->setLocale($locale);

        try {
            $url = route($ad, $rota->parameters());
        } catch (\Throwable) {
            // Adı çözülemeyen bir rota (ör. kapanış rotası) → ana sayfa
            $url = url('/');
        } finally {
            app()->setLocale($oncekiDil);
        }

        $query = request()->getQueryString();

        return $query ? $url . '?' . $query : $url;
    }
}

if (! function_exists('locale_switch_url')) {
    /**
     * Dil değiştirici bağlantısı: hedef dildeki adrese gider VE tercihi
     * çereze yazar (ana sayfa `/` her dilde aynı adres olduğu için gerekli).
     */
    function locale_switch_url(string $locale): string
    {
        return route('locale.switch', $locale) . '?geri=' . urlencode(locale_url($locale));
    }
}
