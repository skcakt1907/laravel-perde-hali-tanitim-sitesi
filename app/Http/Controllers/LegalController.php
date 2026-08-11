<?php

namespace App\Http\Controllers;

use App\Support\Locales;

class LegalController extends Controller
{
    /**
     * kanonik anahtar => [dil anahtarı, görünüm adı]
     *
     * Görünüm DOSYA adları Almanca kaldı (`impressum.blade.php` …) — 20 dosyayı
     * yeniden adlandırmak yerine eşleme burada. Dosyayı ararken bu tabloya bak.
     * `route('legal', 'datenschutz')` çağrıları da bu kanonik anahtarı kullanır;
     * adresteki dile özgü slug'a çeviri URL üretiminde yapılır
     * (AppServiceProvider → URL::formatPathUsing).
     */
    public const PAGES = [
        'impressum'   => ['site.legal.impressum',   'impressum'],
        'datenschutz' => ['site.legal.datenschutz', 'datenschutz'],
        'agb'         => ['site.legal.agb',         'agb'],
        'widerruf'    => ['site.legal.widerruf',    'widerruf'],
        'cookies'     => ['site.legal.cookies',     'cookies'],
    ];

    /**
     * kanonik anahtar => [dil => adreste görünen slug]
     *
     * Almanca sütunu kanonik anahtarla aynı: site Almanca tek dille kurulmuştu,
     * böylece eski `/seite/impressum` adresleri kendiliğinden çalışmaya devam ediyor.
     */
    public const SLUGLAR = [
        'impressum' => [
            'nl' => 'bedrijfsgegevens', 'de' => 'impressum',
            'en' => 'legal-notice',     'tr' => 'kunye',
        ],
        'datenschutz' => [
            'nl' => 'privacyverklaring', 'de' => 'datenschutz',
            'en' => 'privacy-policy',    'tr' => 'gizlilik',
        ],
        'agb' => [
            'nl' => 'algemene-voorwaarden', 'de' => 'agb',
            'en' => 'terms-and-conditions', 'tr' => 'sartlar',
        ],
        'widerruf' => [
            'nl' => 'herroepingsrecht', 'de' => 'widerruf',
            'en' => 'right-of-withdrawal', 'tr' => 'cayma-hakki',
        ],
        'cookies' => [
            'nl' => 'cookieverklaring', 'de' => 'cookies',
            'en' => 'cookie-notice',    'tr' => 'cerezler',
        ],
    ];

    /** Kanonik anahtarın o dildeki slug'ı */
    public static function slug(string $anahtar, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return self::SLUGLAR[$anahtar][$locale] ?? $anahtar;
    }

    /** Adresten gelen slug'ı kanonik anahtara çevirir (hangi dilde olursa olsun) */
    public static function anahtar(string $slug): ?string
    {
        if (isset(self::PAGES[$slug])) {
            return $slug;
        }

        foreach (self::SLUGLAR as $anahtar => $diller) {
            if (in_array($slug, $diller, true)) {
                return $anahtar;
            }
        }

        return null;
    }

    public function show(string $slug)
    {
        $anahtar = self::anahtar($slug);

        abort_if($anahtar === null, 404);

        // Başka dilin slug'ıyla gelindiyse doğru adrese taşı (çift içerik olmasın)
        $dogru = self::slug($anahtar);

        if ($slug !== $dogru) {
            return redirect(route('legal', $anahtar), 301);
        }

        [$titleKey, $name] = self::PAGES[$anahtar];

        // Metinler dil başına ayrı blade; eksikse ana dile düşer.
        $view = 'pages.legal.' . app()->getLocale() . '.' . $name;

        if (! view()->exists($view)) {
            $view = 'pages.legal.' . Locales::primary() . '.' . $name;
        }

        return view('pages.legal.wrapper', [
            'pageTitle' => __($titleKey),
            'bodyView'  => $view,
        ]);
    }
}
