<?php

namespace App\Support;

/**
 * YOL ADLARI SÖZLÜĞÜ — her sayfanın her dildeki adres parçası.
 *
 * Adreste dil kodu yoktur; **yolun kendisi dili söyler**:
 *   /producten → Hollandaca · /produkte → Almanca · /products → İngilizce · /urunler → Türkçe
 *
 * İki yerde kullanılır ve tek kaynak burasıdır:
 *   1. `routes/web.php` — her dil için ayrı rota kaydeder (gelen isteği eşlemek için)
 *   2. `AppServiceProvider` içindeki `URL::formatPathUsing` — üretilen adreslerin ilk
 *      parçasını geçerli dile çevirir, böylece view'lerdeki `route('catalog')` çağrılarına
 *      dokunmak gerekmez
 *
 * KURAL: Parçalar TÜM diller arasında benzersiz olmalı. Aynı parça iki dile denk gelirse
 * gelen istekte hangi dil olduğu belirsizleşir. Bu yüzden ör. İngilizce'de 'contact' değil
 * 'contact-us' (Hollandaca 'contact' aldı), 'product' değil 'model' kullanılır.
 * Yeni dil/sayfa eklerken `benzersizMi()` testi bunu korur.
 */
final class Yollar
{
    /** rota adı => [dil kodu => yol parçası] */
    public const SAYFALAR = [
        'catalog' => [
            'nl' => 'producten',      'de' => 'produkte',
            'en' => 'products',       'tr' => 'urunler',
        ],
        'product' => [
            'nl' => 'product',        'de' => 'produkt',
            'en' => 'model',          'tr' => 'urun',
        ],
        'services' => [
            'nl' => 'diensten',       'de' => 'leistungen',
            'en' => 'services',       'tr' => 'hizmetler',
        ],
        'gallery' => [
            'nl' => 'galerij',        'de' => 'galerie',
            'en' => 'gallery',        'tr' => 'galeri',
        ],
        'blog' => [
            'nl' => 'advies',         'de' => 'ratgeber',
            'en' => 'guide',          'tr' => 'rehber',
        ],
        'about' => [
            'nl' => 'over-ons',       'de' => 'ueber-uns',
            'en' => 'about-us',       'tr' => 'hakkimizda',
        ],
        'contact' => [
            'nl' => 'contact',        'de' => 'kontakt',
            'en' => 'contact-us',     'tr' => 'iletisim',
        ],
        'aufmass' => [
            'nl' => 'gratis-inmeten', 'de' => 'kostenloses-aufmass',
            'en' => 'free-measuring', 'tr' => 'ucretsiz-olcu',
        ],
        'legal' => [
            'nl' => 'pagina',         'de' => 'seite',
            'en' => 'page',           'tr' => 'sayfa',
        ],
        // Dil değiştirici (çerezi yazıp geri döner)
        'locale.switch' => [
            'nl' => 'taal',           'de' => 'sprache',
            'en' => 'language',       'tr' => 'dil',
        ],
    ];

    /** Bir sayfanın belirli dildeki parçası; tanımsızsa ana dile düşer. */
    public static function parca(string $sayfa, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return self::SAYFALAR[$sayfa][$locale]
            ?? self::SAYFALAR[$sayfa][Locales::primary()]
            ?? $sayfa;
    }

    /**
     * Gelen adresin ilk parçasından sayfayı ve dili bulur.
     *
     * @return array{0:string,1:string}|null [sayfa, dil]
     */
    public static function coz(string $parca): ?array
    {
        foreach (self::SAYFALAR as $sayfa => $diller) {
            $kod = array_search($parca, $diller, true);

            if ($kod !== false) {
                return [$sayfa, $kod];
            }
        }

        return null;
    }

    /** Ana dildeki parça => geçerli dildeki parça (URL üretiminde kullanılır) */
    public static function anaDildenCevir(string $parca, string $hedefDil): ?string
    {
        $cozum = self::coz($parca);

        if ($cozum === null || $cozum[1] !== Locales::primary()) {
            return null;
        }

        return self::parca($cozum[0], $hedefDil);
    }

    /**
     * Tüm dillerdeki parçalar benzersiz mi? (aynı parça iki dile denk gelmemeli)
     *
     * @return list<string> çakışan parçalar
     */
    public static function cakisanlar(): array
    {
        $sayim = [];

        foreach (self::SAYFALAR as $diller) {
            foreach ($diller as $parca) {
                $sayim[$parca] = ($sayim[$parca] ?? 0) + 1;
            }
        }

        return array_keys(array_filter($sayim, fn ($n) => $n > 1));
    }
}
