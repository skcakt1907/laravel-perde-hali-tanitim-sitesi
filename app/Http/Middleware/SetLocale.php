<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * URL'in ilk parçasından dili belirler (/de/..., /tr/...) ve
 * route() çağrılarının {locale} parametresini otomatik doldurması için
 * URL varsayılanı olarak kaydeder — böylece view'lerde locale taşımak gerekmez.
 */
class SetLocale
{
    public const SUPPORTED = ['de' => 'Deutsch', 'tr' => 'Türkçe'];

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->route('locale');

        if (! array_key_exists($locale, self::SUPPORTED)) {
            $locale = config('app.fallback_locale');
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
