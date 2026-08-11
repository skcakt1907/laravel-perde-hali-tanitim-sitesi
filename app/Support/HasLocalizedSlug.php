<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Dile göre slug — `/producten/gordijnen` (NL) ile `/produkte/gardinen` (DE)
 * aynı kaydı açar.
 *
 * Desen çeviri kolonlarıyla aynı: temel `slug` ana dile (Hollandaca) aittir,
 * diğer diller `slug_<kod>` kolonunda durur.
 *
 * Slug'lar **kaydederken o dilin başlığından otomatik üretilir** (boşsa).
 * Panelde dil başına slug alanı yok; müşterinin dört dilde slug yazmasını
 * beklemek hataya davetiye — başlığı yazması yeterli.
 *
 * Kullanan model şunu tanımlar:
 *   protected string $slugKaynagi = 'name';   // ya da 'title'
 */
trait HasLocalizedSlug
{
    public static function bootHasLocalizedSlug(): void
    {
        static::saving(function (Model $model) {
            /** @var static $model */
            $model->cevrilmisSluglariDoldur();
        });
    }

    /** URL üretiminde kullanılan anahtar — geçerli dilin slug'ı */
    public function getRouteKey(): mixed
    {
        return $this->slugFor(app()->getLocale());
    }

    /**
     * Gelen adresteki slug'ı çözer.
     *
     * Önce geçerli dilin kolonuna, bulunamazsa diğer dillere bakar: böylece
     * eski/yabancı bir dilin adresi de kaydı açar (404 yerine içerik gelir).
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        if ($field !== null) {
            return $this->where($field, $value)->first();
        }

        $locale = app()->getLocale();
        $kolonlar = [$this->slugKolonu($locale)];

        foreach (Locales::codes() as $kod) {
            $kolon = $this->slugKolonu($kod);

            if (! in_array($kolon, $kolonlar, true)) {
                $kolonlar[] = $kolon;
            }
        }

        return $this->where(function ($q) use ($kolonlar, $value) {
            foreach ($kolonlar as $kolon) {
                $q->orWhere($kolon, $value);
            }
        })->first();
    }

    /** O dildeki slug; boşsa ana dilin slug'ına düşer */
    public function slugFor(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $deger = $this->getAttribute($this->slugKolonu($locale));

        return filled($deger) ? $deger : (string) $this->getAttribute('slug');
    }

    private function slugKolonu(string $locale): string
    {
        return $locale === Locales::primary() ? 'slug' : 'slug_' . $locale;
    }

    /** Boş olan çeviri slug'larını o dilin başlığından üretir */
    private function cevrilmisSluglariDoldur(): void
    {
        $kaynak = $this->slugKaynagi ?? 'name';

        foreach (Locales::secondary() as $kod) {
            $kolon = 'slug_' . $kod;

            if (filled($this->getAttribute($kolon))) {
                continue;
            }

            // O dildeki başlık girilmemişse ana dilin slug'ı yeterli (geri düşme)
            $baslik = $this->getAttribute($kaynak . '_' . $kod);

            if (blank($baslik)) {
                continue;
            }

            $this->setAttribute($kolon, $this->benzersizSlug($this->slugla($baslik, $kod), $kolon));
        }
    }

    /**
     * Başlıktan slug üretir.
     *
     * İki cila:
     *  - **Almanca umlaut** ae/oe/ue/ss olarak yazılır. `Str::slug` varsayılan
     *    olarak ä→a yapıyor ve "Vorhänge" → "vorhange" gibi YANLIŞ YAZIM üretiyordu;
     *    Almanca'da doğru karşılık "vorhaenge".
     *  - Uzunluk **60 karakterle** sınırlanır, kelime ortasından kesilmez. Rehber
     *    yazılarının başlıkları uzun; slug'a olduğu gibi konursa adres okunmaz oluyor.
     */
    private function slugla(string $baslik, string $locale): string
    {
        if ($locale === 'de') {
            $baslik = str_replace(
                ['ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü', 'ß'],
                ['ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss'],
                $baslik
            );
        }

        $slug = Str::slug($baslik);

        if (strlen($slug) <= 60) {
            return $slug;
        }

        $kisa = substr($slug, 0, 60);
        $son  = strrpos($kisa, '-');

        return $son !== false && $son > 20 ? substr($kisa, 0, $son) : $kisa;
    }

    /** Aynı kolonda çakışma olursa sonuna sayı ekler */
    private function benzersizSlug(string $slug, string $kolon): string
    {
        $temel = $slug !== '' ? $slug : 'sayfa';
        $deneme = $temel;
        $i = 2;

        while (
            static::query()
                ->where($kolon, $deneme)
                ->when($this->exists, fn ($q) => $q->whereKeyNot($this->getKey()))
                ->exists()
        ) {
            $deneme = $temel . '-' . $i++;
        }

        return $deneme;
    }
}
