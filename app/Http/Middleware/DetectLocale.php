<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use App\Support\Yollar;
use Closure;
use Illuminate\Http\Request;

/**
 * Dili belirler. URL'de dil KODU yoktur; **yol adının kendisi dili söyler**:
 *   /producten → nl · /produkte → de · /products → en · /urunler → tr
 *
 * Sıra:
 *   1. Yolun ilk parçası (`Yollar::coz`) — en güçlü sinyal, paylaşılan link
 *      karşı tarafta da doğru dilde açılsın diye çerezi EZER
 *   2. `taal` çerezi (ziyaretçinin son seçimi) — ana sayfa `/` için geçerli
 *   3. Tarayıcının Accept-Language başlığı (ilk ziyaret)
 *   4. Ana dil (Hollandaca)
 *
 * Neden global ve `prepend`: rota grubuna bağlı bir middleware yalnızca eşleşen
 * rota bulununca çalışır; 404 ve istisna sayfaları ana dilde kalıyordu. Ayrıca
 * Blade `@section('title', __('...'))` ifadeleri ana şablondan ÖNCE değerlendiği
 * için dili görünümün içinde ayarlamak yetmiyor.
 *
 * Çerez adı Hollandaca (`taal`) — ziyaretçiye gösterilen çerez aydınlatma
 * metninde bu adla geçiyor. Düz metindir (şifrelenmez): `bootstrap/app.php`
 * içindeki `encryptCookies(except: [...])` listesinde. Sadece dil kodu tutuyor,
 * gizli bilgi değil; şifrelenirse aynı istekte yazılıp okunması zorlaşır.
 */
class DetectLocale
{
    public const COOKIE = 'taal';

    public function handle(Request $request, Closure $next)
    {
        $locale = $this->tespitEt($request);

        app()->setLocale($locale);

        $response = $next($request);

        /* Ana sayfa (`/`) tek adres ve dile göre farklı içerik döndürüyor. Vary
           olmadan araya giren bir önbellek (CDN, hosting sayfa önbelleği, şirket
           vekil sunucusu) ilk gelen dili herkese servis eder. Alt sayfaların
           adresi zaten dile özgü, onlarda risk yok — ama başlığı ayırmak
           karmaşıklık katıyor, hepsine veriyoruz. */
        $response->headers->set('Vary', 'Cookie, Accept-Language');

        return $response;
    }

    private function tespitEt(Request $request): string
    {
        /* Yönetim paneli ve giriş her zaman ANA DİLDE çalışır.
           Panel arayüzü Türkçe (çeviri dosyasından gelmiyor), ama app locale
           model `getRouteKey()`'ini etkiliyor: panelde dil ziyaretçiye göre
           değişirse aynı ürünün düzenleme adresi bir gün /yonetim/products/
           aluminium-jaloezie-25mm, bir gün .../aluminyum-jaluzi-25-mm oluyordu.
           Sabitlemek yer imlerini ve testleri öngörülebilir kılıyor. */
        if ($request->is('yonetim', 'yonetim/*', 'giris', 'cikis')) {
            return Locales::primary();
        }

        // 1. Yol dili söylüyorsa o kazanır
        $cozum = Yollar::coz((string) $request->segment(1));

        if ($cozum !== null) {
            return $cozum[1];
        }

        // 2. Ziyaretçinin son seçimi
        $cerez = $request->cookie(self::COOKIE);

        if (Locales::supports($cerez)) {
            return $cerez;
        }

        // 3. Tarayıcı dili
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
