<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/* ---------------- Dil kökü ---------------- */
Route::get('/', fn () => redirect('/' . config('app.fallback_locale')));

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

/* robots.txt rota olarak sunulur — Sitemap satırındaki MUTLAK adres alan adına göre
   kendiliğinden doğru olsun diye (statik dosyada elle güncellemek gerekiyordu). */
Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Disallow: /yonetim',
        'Disallow: /giris',
        'Allow: /',
        '',
        'Sitemap: ' . route('sitemap'),
    ];

    return response(implode("
", $lines) . "
")->header('Content-Type', 'text/plain');
})->name('robots');

/* ---------------- Vitrin (dil önekli) ----------------
 | Yol adları Almanca; dil yalnızca önekte değişir (/de/produkte, /tr/produkte).
 | SetLocale middleware'i URL::defaults ile {locale}'i doldurur, bu yüzden
 | view'lerde route('produkte') gibi parametresiz çağrı yeterlidir.
 */
Route::prefix('{locale}')
    ->whereIn('locale', App\Support\Locales::codes())
    ->middleware('setlocale')
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::get('/produkte', [CatalogController::class, 'index'])->name('catalog');
        Route::get('/produkte/{category}', [CatalogController::class, 'category'])->name('catalog.category');
        Route::get('/produkt/{product}', [CatalogController::class, 'show'])->name('product');

        Route::get('/leistungen', [PageController::class, 'services'])->name('services');
        Route::get('/leistungen/{service}', [PageController::class, 'serviceShow'])->name('service.show');

        Route::get('/galerie', [ProjectController::class, 'index'])->name('gallery');
        Route::get('/galerie/{project}', [ProjectController::class, 'show'])->name('gallery.show');

        Route::get('/ratgeber', [PageController::class, 'blog'])->name('blog');
        Route::get('/ratgeber/{post}', [PageController::class, 'blogShow'])->name('blog.show');

        Route::get('/ueber-uns', [PageController::class, 'about'])->name('about');

        Route::get('/kontakt', [PageController::class, 'contact'])->name('contact');
        Route::get('/aufmass', [PageController::class, 'aufmass'])->name('aufmass');

        // Herkese açık formlar: IP başına dakikada en fazla 5 gönderim (spam/bot freni).
        // Ek olarak formlarda honeypot alanı var (bkz. PageController::botMu).
        Route::middleware('throttle:5,1')->group(function () {
            Route::post('/kontakt', [PageController::class, 'contactStore'])->name('contact.store');
            Route::post('/aufmass', [PageController::class, 'aufmassStore'])->name('aufmass.store');
        });

        Route::get('/seite/{slug}', [LegalController::class, 'show'])->name('legal');
    });

/* ---------------- Yönetim girişi (Türkçe panel) ---------------- */
Route::middleware('guest')->group(function () {
    Route::get('/giris', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/giris', [AuthController::class, 'login']);
});
Route::post('/cikis', [AuthController::class, 'logout'])->name('logout');

/* ---------------- Admin ---------------- */
Route::middleware(['auth', 'admin'])->prefix('yonetim')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', Admin\ProductController::class)->except('show');
    Route::resource('categories', Admin\CategoryController::class)->except('show');
    Route::resource('projects', Admin\ProjectController::class)->except('show');
    Route::resource('services', Admin\ServiceController::class)->except('show');
    Route::resource('posts', Admin\PostController::class)->except('show');
    Route::resource('testimonials', Admin\TestimonialController::class)->except('show');

    // Ayarlar bölümlere ayrıldı (genel / iletisim / sosyal / anasayfa / hakkimizda / kunye)
    Route::get('/settings/{page?}', [Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings/{page}', [Admin\SettingController::class, 'update'])->name('settings.update');

    Route::get('/profile', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [Admin\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/appointments', [Admin\AppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}', [Admin\AppointmentController::class, 'update'])->name('appointments.update');

    Route::get('/messages', [Admin\ContactMessageController::class, 'index'])->name('messages.index');
    Route::patch('/messages/{message}', [Admin\ContactMessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [Admin\ContactMessageController::class, 'destroy'])->name('messages.destroy');
});
