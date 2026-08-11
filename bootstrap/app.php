<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Dili yönlendirmeden ÖNCE belirler (çerez → Accept-Language → ana dil).
        // URL'de dil öneki yok, bu yüzden dili rota grubu değil bu middleware kurar.
        $middleware->prepend(\App\Http\Middleware\DetectLocale::class);

        // Güvenlik başlıkları (CSP, nosniff, HSTS…) — sunucudan bağımsız çalışsın diye
        // .htaccess'te değil burada. Hata sayfalarına da uygulanması için global.
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);

        // Şifrelenmeyen çerezler:
        // - panel tercihleri tarayıcıda JS ile (düz metin) yazılıyor; şifrelemeye
        //   dahil edilirse sunucu tarafında çözülemez ve null okunur → tercih kaybolur
        // - `dil` çerezi yalnızca dil kodu tutar; şifrelemek gereksiz maliyet ve
        //   yanıtla birlikte yazıldığı için aynı istekte okunması gerekiyor
        $middleware->encryptCookies(except: [
            'admin_theme',
            'admin_sidebar',
            \App\Http\Middleware\DetectLocale::COOKIE,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
