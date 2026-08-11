<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;

/**
 * Dili belirler. URL'de dil öneki YOKTUR (müşteri isteği) — sıra:
 *
 *   1. `dil` çerezi (ziyaretçi dil değiştiriciyi kullandıysa)
 *   2. tarayıcının Accept-Language başlığı (ilk ziyaret)
 *   3. ana dil (Hollandaca)
 *
 * Neden global middleware ve neden `prepend`: rota grubuna bağlı bir middleware
 * yalnızca eşleşen rota bulununca çalışır; 404 ve istisna sayfaları ana dilde
 * kalıyordu. Ayrıca Blade `@section('title', __('...'))` ifadeleri ana şablondan
 * ÖNCE değerlendiği için dili görünümün içinde ayarlamak yetmiyor.
 *
 * Çerez düz metindir (şifrelenmez) — `bootstrap/app.php` içindeki
 * `encryptCookies(except: [...])` listesinde. Sadece dil kodu tutuyor,
 * gizli bilgi değil; şifrelenirse JavaScript ya da yönlendirme yanıtı okuyamaz.
 */
class DetectLocale
{
    /** Çerez adı — dil değiştirici ve çerez aydınlatma metni aynı adı kullanır */
    public const COOKIE = 'dil';

    public function handle(Request $request, Closure $next)
    {
        app()->setLocale($this->tespitEt($request));

        $response = $next($request);

        /* Aynı adres ziyaretçiye göre farklı dilde dönüyor. Vary olmadan araya giren
           bir önbellek (CDN, hosting'in sayfa önbelleği, şirket vekil sunucusu) ilk
           gelen dili herkese servis eder — Türk ziyaretçi Almanca sayfa görür.
           Dil URL'de olsaydı bu başlığa hiç gerek olmayacaktı. */
        $response->headers->set('Vary', 'Cookie, Accept-Language');

        return $response;
    }

    private function tespitEt(Request $request): string
    {
        $cerez = $request->cookie(self::COOKIE);

        if (Locales::supports($cerez)) {
            return $cerez;
        }

        // Accept-Language: "nl-NL,nl;q=0.9,en;q=0.8" → sırayla dene
        foreach ($this->tarayiciDilleri($request) as $kod) {
            if (Locales::supports($kod)) {
                return $kod;
            }
        }

        return Locales::primary();
    }

    /** @return list<string> */
    private function tarayiciDilleri(Request $request): array
    {
        $baslik = (string) $request->header('Accept-Language');

        if ($baslik === '') {
            return [];
        }

        $diller = [];

        foreach (explode(',', $baslik) as $parca) {
            // "nl-NL;q=0.9" → "nl"
            $kod = strtolower(trim(explode(';', $parca)[0]));
            $kod = explode('-', $kod)[0];

            if ($kod !== '' && ! in_array($kod, $diller, true)) {
                $diller[] = $kod;
            }
        }

        return $diller;
    }
}
