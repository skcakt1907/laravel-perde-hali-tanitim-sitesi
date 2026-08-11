<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Support\Locales;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        /* Her dilin kendi adresi var (yol adı + slug dile göre değişiyor), o yüzden
           sitemap dil başına tekrarlanıyor — arama motoru dört sürümü de görsün.
           Ana sayfa istisna: her dilde `/`, bir kez listelenir.
           Model NESNESİ geçiriyoruz (slug string'i değil) ki `getRouteKey()` o dilin
           slug'ını üretsin. */
        $urls[] = ['loc' => route('home'), 'lastmod' => null];

        $oncekiDil = app()->getLocale();

        try {
            foreach (Locales::codes() as $locale) {
                app()->setLocale($locale);

                foreach (['catalog', 'services', 'gallery', 'blog', 'about', 'contact', 'aufmass'] as $name) {
                    $urls[] = ['loc' => route($name), 'lastmod' => null];
                }

                foreach (array_keys(LegalController::PAGES) as $anahtar) {
                    $urls[] = ['loc' => route('legal', $anahtar), 'lastmod' => null];
                }

                foreach (Category::where('durum', true)->get() as $c) {
                    $urls[] = ['loc' => route('catalog.category', $c), 'lastmod' => null];
                }
                foreach (Product::where('durum', true)->get() as $p) {
                    $urls[] = ['loc' => route('product', $p), 'lastmod' => optional($p->updated_at)->toAtomString()];
                }
                foreach (Project::where('durum', true)->get() as $p) {
                    $urls[] = ['loc' => route('gallery.show', $p), 'lastmod' => optional($p->updated_at)->toAtomString()];
                }
                foreach (Service::where('durum', true)->get() as $s) {
                    $urls[] = ['loc' => route('service.show', $s), 'lastmod' => null];
                }
                foreach (Post::where('durum', true)->get() as $p) {
                    $urls[] = ['loc' => route('blog.show', $p), 'lastmod' => optional($p->updated_at)->toAtomString()];
                }
            }
        } finally {
            app()->setLocale($oncekiDil);
        }

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
