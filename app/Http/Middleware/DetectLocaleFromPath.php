<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * Dili URL'in ilk parçasından belirler — YÖNLENDİRMEDEN ÖNCE.
 *
 * Neden global middleware: rota grubundaki `setlocale` yalnızca eşleşen bir rota
 * bulunduğunda çalışır. Eşleşme yoksa (404) ya da bir istisna atıldığında hata
 * sayfası ana dilde render ediliyordu — `/en/olmayan-sayfa` Almanca 404 veriyordu.
 * Ayrıca Blade `@section('title', __('...'))` ifadeleri ana şablondan ÖNCE
 * değerlendiği için dili hata görünümünün içinde ayarlamak da yetmiyor.
 *
 * Rota eşleşirse SetLocale zaten aynı değeri tekrar yazar; çakışma olmaz.
 */
class DetectLocaleFromPath
{
    public function handle(Request $request, Closure $next)
    {
        $segment = $request->segment(1);

        if (Locales::supports($segment)) {
            app()->setLocale($segment);
            URL::defaults(['locale' => $segment]);
        }

        return $next($request);
    }
}
