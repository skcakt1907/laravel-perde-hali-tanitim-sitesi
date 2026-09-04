<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Support\Locales;
use App\Support\Yollar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * KAPATILAN DİLLERİN ESKİ ADRESLERİNİ YENİSİNE YÖNLENDİRİR (301).
 *
 * Almanca ve Türkçe müşteri isteğiyle kapatıldı. O dillerdeki 96 adres
 * Google'da kayıtlı; hiçbir şey yapılmazsa hepsi 404 verir ve site
 * sıralamada kaybeder. Bu sınıf onları ana dildeki (Hollandaca)
 * karşılığına kalıcı olarak yönlendirir.
 *
 * NEDEN STATİK LİSTE DEĞİL: 96 adresi elle yazmak, yeni ürün eklendiğinde
 * veya slug değiştiğinde bayatlar. Burada eşleştirme kayıttan yapılıyor,
 * kendini günceller.
 *
 * NASIL BULUYOR: Kapatılan dilin slug kolonları (`slug_de`, `slug_tr`)
 * veritabanında DURUYOR — yalnızca `Locales::ALL` içinden çıkarıldılar.
 * Kayıt bu kolonların hepsine bakılarak bulunur.
 *
 * Diller geri açılırsa bu sınıf kendiliğinden devre dışı kalır:
 * `kapatilanDiller()` boş döner, hiçbir rota kaydedilmez.
 */
class EskiDilYonlendirmeController extends Controller
{
    /** Yol sözlüğünde tanımlı ama artık aktif olmayan diller */
    public static function kapatilanDiller(): array
    {
        $tanimli = array_keys(Yollar::SAYFALAR['catalog'] ?? []);

        return array_values(array_diff($tanimli, Locales::codes()));
    }

    /**
     * Hedef adresi üretmeden ÖNCE dili ana dile sabitler.
     *
     * ZORUNLU: DetectLocale, adresteki yol kelimesinden dili çıkarıyor
     * (/produkte → de). Kapatılmış olsa bile geçerli dil 'de' kalıyor ve
     * AppServiceProvider'daki URL::formatPathUsing ürettiğimiz Hollandaca
     * adresi tekrar Almancaya çeviriyordu — yönlendirme kendi kendine
     * dönüyordu (/produkte → /produkte).
     */
    private function anaDileGec(): void
    {
        app()->setLocale(Locales::primary());
    }

    /** Liste sayfaları: /produkte → /producten */
    public function liste(string $sayfa)
    {
        $this->anaDileGec();

        return redirect()->to('/' . Yollar::parca($this->listeSayfasi($sayfa), Locales::primary()), 301);
    }

    /** Detay sayfaları: /urun/<türkçe-slug> → /product/<hollandaca-slug> */
    public function detay(string $sayfa, string $slug)
    {
        $this->anaDileGec();

        $model = match ($sayfa) {
            'catalog'  => Category::class,
            'product'  => Product::class,
            'services' => Service::class,
            'gallery'  => Project::class,
            'blog'     => Post::class,
            default    => null,
        };

        $kayit = $model ? $this->slugIleBul($model, $slug) : null;

        // Kayıt yoksa VEYA pasife alınmışsa doğrudan listeye gönder.
        //
        // Pasif kontrolü şart: halı içeriği silinmedi, `durum=0` yapıldı.
        // Kayıt bulunduğu için eskiden ana dildeki detay adresine
        // yönlendiriliyor, o sayfa da pasif olduğu için 404 veriyordu —
        // ziyaretçi iki atlama sonunda yine boş sayfaya düşüyordu.
        if (! $kayit || ! ($kayit->durum ?? true)) {
            return redirect()->to('/' . Yollar::parca($this->listeSayfasi($sayfa), Locales::primary()), 301);
        }

        $yol = $sayfa === 'catalog' ? 'catalog' : $sayfa;

        return redirect()->to(
            '/' . Yollar::parca($yol, Locales::primary()) . '/' . $kayit->slugFor(Locales::primary()),
            301
        );
    }

    /**
     * Bir içerik türünün LİSTE sayfası.
     *
     * 'product' bir detay yolu ('/product/{slug}'); tek başına '/product'
     * diye bir sayfa YOK. Ürünlerin listesi 'catalog' ('/producten').
     * Bu ayrım atlandığında kayıt bulunamayan ürünler '/product' adresine
     * gönderiliyor ve orada 404 alıyordu.
     */
    private function listeSayfasi(string $sayfa): string
    {
        return $sayfa === 'product' ? 'catalog' : $sayfa;
    }

    /** Yasal sayfalar: /seite/widerruf → /pagina/herroepingsrecht */
    public function yasal(string $slug)
    {
        $this->anaDileGec();

        $anahtar = \App\Http\Controllers\LegalController::anahtar($slug);
        $hedef   = $anahtar
            ? \App\Http\Controllers\LegalController::slug($anahtar, Locales::primary())
            : null;

        return redirect()->to(
            '/' . Yollar::parca('legal', Locales::primary()) . ($hedef ? '/' . $hedef : ''),
            301
        );
    }

    /**
     * Kaydı TÜM slug kolonlarına bakarak bulur — kapatılmış dillerinki dahil.
     *
     * Model::resolveRouteBinding() yalnızca AKTİF dillerin kolonlarına bakar,
     * bu yüzden burada kullanılamaz; eski Türkçe slug'ı bulamazdı.
     */
    private function slugIleBul(string $model, string $slug): ?Model
    {
        $ornek  = new $model;
        $tablo  = $ornek->getTable();
        $kolonlar = ['slug'];

        foreach (array_keys(Yollar::SAYFALAR['catalog'] ?? []) as $dil) {
            $k = 'slug_' . $dil;
            if (Schema::hasColumn($tablo, $k)) {
                $kolonlar[] = $k;
            }
        }

        return $model::where(function ($q) use ($kolonlar, $slug) {
            foreach ($kolonlar as $k) {
                $q->orWhere($k, $slug);
            }
        })->first();
    }
}
