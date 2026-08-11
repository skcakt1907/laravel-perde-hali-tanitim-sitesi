<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * ANA DİL ALMANCA → HOLLANDACA (müşteri isteği).
 *
 * Desen: sonek almayan temel kolon = ana dil. Ana dil değişince veri de taşınmalı,
 * yoksa `t()` Hollandaca isterken Almanca metni döner.
 *
 * Yapılan:
 *   1. `<alan>_de` kolonlarını ekle
 *   2. temel kolon (Almanca) → `<alan>_de`
 *   3. `<alan>_nl` (Hollandaca) → temel kolon
 *   4. `<alan>_nl` kolonlarını düşür (artık ana dil sonek almaz)
 *
 * Aynısı `settings` tablosunda anahtar bazında yapılır (`x` → `x_de`, `x_nl` → `x`).
 *
 * NOT: `migrate:fresh` senaryosunda tablolar boş olduğu için 2–3 adımı bir şey yapmaz;
 * içeriği seeder doğrudan yeni düzende yazar. Eski veriyle çalışan bir kurulumda
 * (ör. önceki SQL yedeği import edilmişse) taşıma burada gerçekleşir.
 */
return new class extends Migration
{
    /** tablo => [kolon => tip] */
    private array $map = [
        'categories'   => ['name' => 'string', 'description' => 'text'],
        'products'     => ['name' => 'string', 'short_desc' => 'text', 'description' => 'longText',
                           'attributes' => 'json'],
        'services'     => ['title' => 'string', 'summary' => 'text', 'content' => 'longText'],
        'projects'     => ['title' => 'string', 'kind' => 'string', 'summary' => 'text', 'content' => 'longText'],
        'posts'        => ['title' => 'string', 'category' => 'string', 'summary' => 'text', 'content' => 'longText'],
        'testimonials' => ['title' => 'string', 'comment' => 'text'],
    ];

    public function up(): void
    {
        $this->takasEt('nl', 'de');
    }

    public function down(): void
    {
        $this->takasEt('de', 'nl');
    }

    /**
     * `$yeni` dilini temel kolona taşır, eski ana dili `_$eski` soneğine indirir.
     */
    private function takasEt(string $yeni, string $eski): void
    {
        foreach ($this->map as $table => $columns) {
            // 1) eski ana dil için sonekli kolonları aç
            Schema::table($table, function (Blueprint $t) use ($table, $columns, $eski) {
                foreach ($columns as $column => $type) {
                    if (! Schema::hasColumn($table, $column . '_' . $eski)) {
                        $t->{$type}($column . '_' . $eski)->nullable()->after($column);
                    }
                }
            });

            // 2+3) veriyi tek UPDATE ile takasla (satır satır dönmeye gerek yok)
            foreach ($columns as $column => $type) {
                $yeniKolon = $column . '_' . $yeni;

                if (! Schema::hasColumn($table, $yeniKolon)) {
                    continue;
                }

                // JSON kolonunda NULLIF(...,'') MySQL 8'de tip hatası verir — sadece NULL kontrolü
                $yeniDeger = $type === 'json'
                    ? "COALESCE({$yeniKolon}, {$column})"
                    : "COALESCE(NULLIF({$yeniKolon}, ''), {$column})";

                DB::table($table)->update([
                    $column . '_' . $eski => DB::raw($column),
                    // Yeni ana dil boşsa temel kolonu bozmayalım: eski metin kalsın
                    $column => DB::raw($yeniDeger),
                ]);
            }

            // 4) yeni ana dilin sonekli kolonlarını düşür
            Schema::table($table, function (Blueprint $t) use ($table, $columns, $yeni) {
                $drop = [];

                foreach (array_keys($columns) as $column) {
                    if (Schema::hasColumn($table, $column . '_' . $yeni)) {
                        $drop[] = $column . '_' . $yeni;
                    }
                }

                if ($drop) {
                    $t->dropColumn($drop);
                }
            });
        }

        $this->ayarlariTakasla($yeni, $eski);
    }

    /**
     * settings tablosu key/value olduğu için kolon değil SATIR taşınır.
     */
    private function ayarlariTakasla(string $yeni, string $eski): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $sonek = '\_' . $yeni;

        $yeniSatirlar = DB::table('settings')
            ->where('anahtar', 'like', '%' . $sonek)
            ->get();

        foreach ($yeniSatirlar as $satir) {
            $temel = substr($satir->anahtar, 0, -strlen('_' . $yeni));

            $eskiDeger = DB::table('settings')->where('anahtar', $temel)->value('deger');

            // eski ana dilin metnini sonekli anahtara indir
            if ($eskiDeger !== null) {
                DB::table('settings')->updateOrInsert(
                    ['anahtar' => $temel . '_' . $eski],
                    ['deger' => $eskiDeger]
                );
            }

            // yeni ana dilin metnini soneksiz anahtara çıkar
            if (filled($satir->deger)) {
                DB::table('settings')->updateOrInsert(
                    ['anahtar' => $temel],
                    ['deger' => $satir->deger]
                );
            }

            DB::table('settings')->where('anahtar', $satir->anahtar)->delete();
        }
    }
};
