<?php

namespace App\Providers;

use App\Http\Controllers\LegalController;
use App\Models\Category;
use App\Support\Locales;
use App\Support\Yollar;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /* Üretilen adresin ilk parçasını geçerli dile çevirir:
           route('catalog') ana dil rotasından `/producten` üretir, burada
           Almanca istekte `/produkte` olur. Böylece view'lerdeki 60'tan fazla
           `route(...)` çağrısına dokunmak gerekmedi.

           `asset()` bu kancadan GEÇMEZ (denendi) — /css, /fonts gibi yollar
           güvende. Yine de yalnızca sözlükte karşılığı olan parçaya dokunuyoruz;
           tanımadığı bir ilk parçayı (ör. `yonetim`, `giris`) aynen bırakır.

           Yasal sayfalarda ikinci parça da çevrilir: view'ler `route('legal',
           'datenschutz')` gibi KANONİK anahtarla çağırıyor, adres ise o dilin
           slug'ını taşımalı (`/pagina/privacyverklaring`). */
        URL::formatPathUsing(function (string $path) {
            if ($path === '' || $path === '/') {
                return $path;
            }

            $dil      = app()->getLocale();
            $parcalar = explode('/', ltrim($path, '/'));
            $cozum    = Yollar::coz($parcalar[0]);

            // Yalnızca ANA DİLİN parçasını çeviriyoruz; zaten çevrilmiş bir yolu
            // tekrar işlemek çift çeviriye yol açardı.
            if ($cozum === null || $cozum[1] !== Locales::primary()) {
                return $path;
            }

            [$sayfa] = $cozum;
            $parcalar[0] = Yollar::parca($sayfa, $dil);

            /* Yasal sayfada ikinci parça da çevrilir. DİKKAT: bu, ana dilde de
               gerekli — view'ler `route('legal', 'datenschutz')` ile KANONİK
               anahtarı geçiyor, Hollandaca slug ise 'privacyverklaring'.
               Ana dili erken `return` ile atlamak, LegalController'ın kanonik
               anahtara geri yönlendirip aynı adresi tekrar üretmesine, yani
               sonsuz 301 döngüsüne yol açıyordu (yaşandı). */
            if ($sayfa === 'legal' && isset($parcalar[1])) {
                $parcalar[1] = LegalController::slug($parcalar[1], $dil);
            }

            return '/' . implode('/', $parcalar);
        });

        // Sayfalama Bootstrap 5 işaretlemesiyle basılsın — ön yüz Bootstrap kullanıyor,
        // yönetim panelinin admin-theme.css'i de .pagination/.page-item/.page-link'i stillendiriyor.
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            $view->with('navCategories', Category::active()->whereNull('parent_id')->orderBy('sira')->get());
        });
    }
}
