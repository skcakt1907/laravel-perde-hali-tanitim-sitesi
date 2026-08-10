<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    /** slug => [dil anahtarı, görünüm adı] — görünüm dile göre seçilir */
    public const PAGES = [
        'impressum'   => ['site.legal.impressum',   'impressum'],
        'datenschutz' => ['site.legal.datenschutz', 'datenschutz'],
        'agb'         => ['site.legal.agb',         'agb'],
        'widerruf'    => ['site.legal.widerruf',    'widerruf'],
        'cookies'     => ['site.legal.cookies',     'cookies'],
    ];

    public function show(string $slug)
    {
        abort_unless(isset(self::PAGES[$slug]), 404);

        [$titleKey, $name] = self::PAGES[$slug];

        // Metinler dil başına ayrı blade; eksikse Almanca'ya düşer.
        $view = 'pages.legal.' . app()->getLocale() . '.' . $name;

        if (! view()->exists($view)) {
            $view = 'pages.legal.' . config('app.fallback_locale') . '.' . $name;
        }

        return view('pages.legal.wrapper', [
            'pageTitle' => __($titleKey),
            'bodyView'  => $view,
        ]);
    }
}
