<?php

namespace App\Support;

/**
 * Çok dilli metin alanları için çeviri katmanı.
 *
 * Temel kolon (name, title, summary…) BİRİNCİL dili tutar (Almanca).
 * `_<kod>` sonekli kolonlar diğer dilleri tutar; boşsa birincile düşülür.
 * Böylece admin panelinde tek formda tüm diller yönetilir, ekstra tablo gerekmez.
 *
 * Model, çevrilebilir alanlarını `$translatable` ile bildirir; sonekli kolonlar
 * `getFillable()` içinde otomatik eklenir — yeni dil eklenince model dosyalarına
 * dokunmak gerekmez.
 */
trait HasTranslations
{
    /** İstenen dildeki değer; boşsa birincil dile düşer. */
    public function t(string $field, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        if ($locale !== Locales::primary()) {
            $value = $this->getAttribute($field . '_' . $locale);

            if (filled($value)) {
                return $value;
            }
        }

        return $this->getAttribute($field);
    }

    /**
     * Bu modelin çevrilebilir alanları.
     *
     * `$translatable`     → metin alanları (t() ile okunur)
     * `$translatableJson` → JSON alanları (ör. ürün özellik tablosu; kendi accessor'ı vardır)
     *
     * İkisi de `_<dil>` sonekli kolonlara açılır ve `$fillable`'a katılır.
     */
    public function translatableFields(): array
    {
        return array_merge($this->translatable ?? [], $this->translatableJson ?? []);
    }

    /** Temel `$fillable` + tüm dillerin sonekli kolonları. */
    public function getFillable(): array
    {
        return array_values(array_unique(array_merge(
            parent::getFillable(),
            Locales::expand($this->translatableFields()),
        )));
    }

    /**
     * Admin doğrulama kuralları: verilen kural birincil alan için nasılsa,
     * sonekli alanlar için de aynısı (ama zorunluluk kaldırılmış) uygulanır.
     *
     * @param  array<string,string>  $rules  ['name' => 'required|string|max:200', …]
     * @return array<string,string>
     */
    public static function translationRules(array $rules): array
    {
        $out = [];

        foreach ($rules as $field => $rule) {
            $out[$field] = $rule;

            // Çeviriler hiçbir zaman zorunlu değildir — boş kalırsa birincil dil gösterilir.
            $optional = implode('|', array_filter(
                explode('|', $rule),
                fn ($r) => $r !== 'required',
            ));

            foreach (Locales::secondary() as $locale) {
                $out[$field . '_' . $locale] = 'nullable|' . $optional;
            }
        }

        return $out;
    }
}
