<?php

namespace App\Providers;

use App\Models\Category;
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
        // Dil önekli rotalar admin/giriş sayfalarından da çağrılabilsin diye
        // {locale} için genel bir varsayılan; SetLocale middleware'i bunu ezer.
        URL::defaults(['locale' => config('app.fallback_locale')]);

        // Sayfalama Bootstrap 5 işaretlemesiyle basılsın — ön yüz Bootstrap kullanıyor,
        // yönetim panelinin admin-theme.css'i de .pagination/.page-item/.page-link'i stillendiriyor.
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            $view->with('navCategories', Category::active()->whereNull('parent_id')->orderBy('sira')->get());
        });
    }
}
