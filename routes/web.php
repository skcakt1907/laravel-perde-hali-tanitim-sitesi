<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\DetectLocale;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SitemapController;
use App\Support\Locales;
use App\Support\Yollar;
use Illuminate\Support\Facades\Route;

/* ---------------- Dil ----------------
 | URL'de dil KODU yoktur; yol adının kendisi dili söyler:
 |   /producten (NL) · /produkte (DE) · /products (EN) · /urunler (TR)
 | Sözlük: App\Support\Yollar. Dil tespiti: App\Http\Middleware\DetectLocale.
 |
 | Değiştirici JS'siz çalışsın diye normal bağlantıdır: çerezi yazar ve
 | ziyaretçiyi aynı sayfanın o dildeki adresine gönderir (bkz. locale_url()).
 */
foreach (Locales::codes() as $kod) {
    Route::get('/' . Yollar::parca('locale.switch', $kod) . '/{locale}', function (string $locale) {
        abort_unless(Locales::supports($locale), 404);

        // `geri` parametresi hedef dildeki adresi taşır (bkz. locale_switch_url());
        // yoksa geldiği sayfaya döneriz.
        $geri = request()->query('geri') ?: url()->previous();

        // Açık yönlendirme açığına düşmemek için yalnızca kendi alan adımıza dönüyoruz
        if (! str_starts_with($geri, url('/'))) {
            $geri = url('/');
        }

        return redirect($geri)->withCookie(
            cookie()->forever(DetectLocale::COOKIE, $locale, sameSite: 'Lax')
        );
    })->whereIn('locale', Locales::codes())
      ->name($kod === Locales::primary() ? 'locale.switch' : $kod . '.locale.switch');
}

/* Eski dil önekli adresler (`/de/produkte` biçimi, bu yapıya geçmeden önce kullanılıyordu).
   Öneki atıp yolu HEDEF DİLİN adına çeviriyoruz: /tr/produkte → /urunler. Sadece önek
   atılsa Türkçe isteyen ziyaretçi Almanca sayfaya düşerdi. */
Route::get('/{locale}/{yol?}', function (string $locale, ?string $yol = null) {
    /* DİLİ ÖNCE KUR. `redirect()` de URL::formatPathUsing kancasından geçiyor;
       dil kurulmazsa hedef yol İKİNCİ KEZ çevriliyor ve /nl/produkte → /products
       gibi yanlış dile düşüyordu (yaşandı). Bu adreste dil kodu yolun kendisinde,
       DetectLocale onu tanımıyor — burada elle set etmek gerekiyor. */
    app()->setLocale($locale);

    if ($yol === null) {
        return redirect('/', 301)
            ->withCookie(cookie()->forever(DetectLocale::COOKIE, $locale, sameSite: 'Lax'));
    }

    $parcalar = explode('/', $yol);
    $cozum    = Yollar::coz($parcalar[0]);

    if ($cozum !== null) {
        $parcalar[0] = Yollar::parca($cozum[0], $locale);

        // Yasal sayfada slug da dile göre değişiyor: /tr/seite/impressum → /sayfa/kunye
        if ($cozum[0] === 'legal' && isset($parcalar[1])) {
            $anahtar = LegalController::anahtar($parcalar[1]);

            if ($anahtar !== null) {
                $parcalar[1] = LegalController::slug($anahtar, $locale);
            }
        }
    }

    return redirect('/' . implode('/', $parcalar), 301)
        ->withCookie(cookie()->forever(DetectLocale::COOKIE, $locale, sameSite: 'Lax'));
})->whereIn('locale', Locales::codes())->where('yol', '.*');

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

/* ---------------- Vitrin ----------------
 | Her sayfa HER DİL İÇİN ayrı kaydedilir; yol adı dile göre değişir
 | (`Yollar::SAYFALAR`). Ana dilin rotaları KANONİK adı taşır (`catalog`),
 | diğer diller `de.catalog` gibi önekli ad alır ve isimle hiç çağrılmaz —
 | yalnızca gelen isteği eşlemek için varlar.
 |
 | Adres ÜRETİMİ tek bir yerden çevrilir: AppServiceProvider'daki
 | `URL::formatPathUsing`, üretilen yolun ilk parçasını geçerli dile çevirir.
 | Bu yüzden view'lerde `route('catalog')` gibi çağrılar aynen kalabiliyor —
 | 60'tan fazla çağrıyı elle değiştirmek gerekmedi.
 |
 | Ana sayfa tek istisna: her dilde `/` (dili çerez/tarayıcı belirler).
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

foreach (Locales::codes() as $kod) {
    $anaDil = $kod === Locales::primary();
    $ad = fn (string $isim) => $anaDil ? $isim : $kod . '.' . $isim;
    $p = fn (string $sayfa) => '/' . Yollar::parca($sayfa, $kod);

    Route::get($p('catalog'), [CatalogController::class, 'index'])->name($ad('catalog'));
    Route::get($p('catalog') . '/{category}', [CatalogController::class, 'category'])->name($ad('catalog.category'));
    Route::get($p('product') . '/{product}', [CatalogController::class, 'show'])->name($ad('product'));

    Route::get($p('services'), [PageController::class, 'services'])->name($ad('services'));
    Route::get($p('services') . '/{service}', [PageController::class, 'serviceShow'])->name($ad('service.show'));

    Route::get($p('gallery'), [ProjectController::class, 'index'])->name($ad('gallery'));
    Route::get($p('gallery') . '/{project}', [ProjectController::class, 'show'])->name($ad('gallery.show'));

    Route::get($p('blog'), [PageController::class, 'blog'])->name($ad('blog'));
    Route::get($p('blog') . '/{post}', [PageController::class, 'blogShow'])->name($ad('blog.show'));

    Route::get($p('about'), [PageController::class, 'about'])->name($ad('about'));

    Route::get($p('contact'), [PageController::class, 'contact'])->name($ad('contact'));
    Route::get($p('aufmass'), [PageController::class, 'aufmass'])->name($ad('aufmass'));

    // Herkese açık formlar: IP başına dakikada en fazla 5 gönderim (spam/bot freni).
    // Ek olarak formlarda honeypot alanı var (bkz. PageController::botMu).
    Route::middleware('throttle:5,1')->group(function () use ($p, $ad) {
        Route::post($p('contact'), [PageController::class, 'contactStore'])->name($ad('contact.store'));
        Route::post($p('aufmass'), [PageController::class, 'aufmassStore'])->name($ad('aufmass.store'));
    });

    Route::get($p('legal') . '/{slug}', [LegalController::class, 'show'])->name($ad('legal'));
}

/* ---------------- Yönetim girişi (Türkçe panel) ---------------- */
Route::middleware('guest')->group(function () {
    Route::get('/giris', [AuthController::class, 'showLogin'])->name('login');

    // Kaba kuvvete karsi ASIL koruma AuthController'da: e-posta+IP basina
    // 5 BASARISIZ deneme -> 1 dakika kilit. Buradaki sinir sadece sel/bot freni;
    // dusuk tutmak ortak IP arkasindaki mesru kullaniciyi da cezalandirirdi.
    Route::post('/giris', [AuthController::class, 'login'])->middleware('throttle:30,1');
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
