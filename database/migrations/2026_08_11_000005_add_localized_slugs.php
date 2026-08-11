<?php

use App\Support\Locales;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Dile göre slug kolonları (`slug_de`, `slug_en`, `slug_tr`).
 *
 * Yol adları dile göre değiştiği için (`/producten` ↔ `/produkte`) içerik
 * slug'larının da değişmesi gerekti; yoksa adres yarı Hollandaca kalıyordu.
 *
 * Temel `slug` ana dile (Hollandaca) aittir — çeviri kolonlarındaki desenin aynısı.
 * Boş kalan çeviri slug'ı, kaydederken o dilin başlığından üretilir
 * (bkz. App\Support\HasLocalizedSlug).
 */
return new class extends Migration
{
    /** @var list<string> */
    private array $tablolar = ['categories', 'products', 'services', 'projects', 'posts'];

    public function up(): void
    {
        foreach ($this->tablolar as $tablo) {
            Schema::table($tablo, function (Blueprint $t) use ($tablo) {
                $sonra = 'slug';

                foreach (Locales::secondary() as $kod) {
                    $kolon = 'slug_' . $kod;

                    if (Schema::hasColumn($tablo, $kolon)) {
                        continue;
                    }

                    // Benzersiz: iki kayıt aynı dilde aynı adresi paylaşamaz.
                    // nullable + unique birlikte çalışır (MySQL birden çok NULL'a izin verir).
                    $t->string($kolon)->nullable()->unique()->after($sonra);
                    $sonra = $kolon;
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tablolar as $tablo) {
            Schema::table($tablo, function (Blueprint $t) use ($tablo) {
                foreach (Locales::secondary() as $kod) {
                    $kolon = 'slug_' . $kod;

                    if (Schema::hasColumn($tablo, $kolon)) {
                        $t->dropUnique([$kolon]);
                        $t->dropColumn($kolon);
                    }
                }
            });
        }
    }
};
