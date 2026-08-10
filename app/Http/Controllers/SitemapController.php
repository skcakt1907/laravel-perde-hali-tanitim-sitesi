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

        // Her dil için ayrı URL kümesi — arama motorları iki sürümü de görsün
        foreach (Locales::codes() as $locale) {
            $l = ['locale' => $locale];

            foreach (['home', 'catalog', 'services', 'gallery', 'blog', 'about', 'contact', 'aufmass'] as $name) {
                $urls[] = ['loc' => route($name, $l), 'lastmod' => null];
            }

            foreach (array_keys(LegalController::PAGES) as $slug) {
                $urls[] = ['loc' => route('legal', $l + ['slug' => $slug]), 'lastmod' => null];
            }

            foreach (Category::where('durum', true)->get() as $c) {
                $urls[] = ['loc' => route('catalog.category', $l + ['category' => $c->slug]), 'lastmod' => null];
            }
            foreach (Product::where('durum', true)->get() as $p) {
                $urls[] = ['loc' => route('product', $l + ['product' => $p->slug]), 'lastmod' => optional($p->updated_at)->toAtomString()];
            }
            foreach (Project::where('durum', true)->get() as $p) {
                $urls[] = ['loc' => route('gallery.show', $l + ['project' => $p->slug]), 'lastmod' => optional($p->updated_at)->toAtomString()];
            }
            foreach (Service::where('durum', true)->get() as $s) {
                $urls[] = ['loc' => route('service.show', $l + ['service' => $s->slug]), 'lastmod' => null];
            }
            foreach (Post::where('durum', true)->get() as $p) {
                $urls[] = ['loc' => route('blog.show', $l + ['post' => $p->slug]), 'lastmod' => optional($p->updated_at)->toAtomString()];
            }
        }

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
