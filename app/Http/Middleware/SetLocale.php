<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * URL'in ilk parçasından dili belirler (/de/…, /en/…, /tr/…) ve
 * route() çağrılarının {locale} parametresini otomatik doldurması için
 * URL varsayılanı olarak kaydeder — böylece view'lerde locale taşımak gerekmez.
 *
 * Desteklenen diller `App\Support\Locales` içinde tanımlıdır.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->route('locale');

        if (! Locales::supports($locale)) {
            $locale = Locales::primary();
        }

        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);

        // Laravel denetleyici argümanlarını rota parametrelerinin SIRASINA göre geçer;
        // {locale} listede kalırsa ilk argümana (ör. Category $category) o düşer.
        // Dili aldıktan sonra parametreyi düşürüyoruz — URL üretimi URL::defaults'tan besleniyor.
        $request->route()?->forgetParameter('locale');

        return $next($request);
    }
}
