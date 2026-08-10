<?php

namespace App\Support;

/**
 * İki dilli metin alanları için basit çeviri katmanı.
 *
 * Temel kolon (name, title, summary...) Almanca'yı tutar — site birincil dili.
 * `_tr` sonekli kolon Türkçe'yi tutar; boşsa Almanca'ya düşülür.
 * Böylece admin panelinde tek formda iki dil yönetilir, ekstra tablo gerekmez.
 */
trait HasTranslations
{
    public function t(string $field, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        if ($locale !== config('app.fallback_locale')) {
            $value = $this->getAttribute($field . '_' . $locale);

            if (filled($value)) {
                return $value;
            }
        }

        return $this->getAttribute($field);
    }
}
