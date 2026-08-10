<?php

namespace App\Support;

/**
 * Sitenin dilleri TEK yerden tanımlanır.
 *
 * Yeni bir dil eklemek için:
 *   1) buraya kodu + etiketi ekle
 *   2) `lang/<kod>/site.php` ve `lang/<kod>/validation.php` dosyalarını oluştur
 *   3) çevrilebilir tablolara `<alan>_<kod>` kolonlarını ekleyen bir migration yaz
 *      (bkz. 2026_08_11_000001_add_english_translation_columns)
 *   4) `resources/views/pages/legal/<kod>/` altına yasal metinleri koy (yoksa Almanca'ya düşer)
 *
 * Rotalar, dil değiştirici, admin form sekmeleri, ayar anahtarları, sitemap ve
 * model `$fillable`'ları bu listeden türer — başka yerde elle iş yoktur.
 */
final class Locales
{
    /** kod => [etiket, html lang, og:locale] */
    public const ALL = [
        'de' => ['Deutsch', 'de', 'de_DE'],
        'en' => ['English', 'en', 'en_US'],
        'tr' => ['Türkçe',  'tr', 'tr_TR'],
    ];

    /** @return array<string,string> kod => etiket */
    public static function labels(): array
    {
        return array_map(fn ($v) => $v[0], self::ALL);
    }

    /** @return list<string> */
    public static function codes(): array
    {
        return array_keys(self::ALL);
    }

    /** Birincil (yedek) dil — DB'de sonek almayan kolonlar bu dile aittir. */
    public static function primary(): string
    {
        return config('app.fallback_locale', 'de');
    }

    /**
     * Birincil dışındaki diller — DB'de `_<kod>` sonekli kolonları olanlar.
     *
     * @return list<string>
     */
    public static function secondary(): array
    {
        return array_values(array_diff(self::codes(), [self::primary()]));
    }

    public static function supports(?string $locale): bool
    {
        return $locale !== null && isset(self::ALL[$locale]);
    }

    public static function label(string $locale): string
    {
        return self::ALL[$locale][0] ?? strtoupper($locale);
    }

    public static function ogLocale(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return self::ALL[$locale][2] ?? 'de_DE';
    }

    /**
     * Bir alan adını tüm dillerdeki kolon/anahtar adlarına açar.
     * `site_aciklama` → ['site_aciklama', 'site_aciklama_en', 'site_aciklama_tr']
     *
     * @param  list<string>  $fields
     * @return list<string>
     */
    public static function expand(array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            $out[] = $field;

            foreach (self::secondary() as $locale) {
                $out[] = $field . '_' . $locale;
            }
        }

        return $out;
    }
}
