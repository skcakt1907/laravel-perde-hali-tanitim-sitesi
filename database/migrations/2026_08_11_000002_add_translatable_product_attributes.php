<?php

use App\Support\Locales;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ürün özellikleri (Material, Lichtdurchlässigkeit…) tek bir JSON alanındaydı ve
 * ana dilde kalıyordu — İngilizce/Türkçe sayfada Almanca görünüyordu.
 * Her dil için ayrı JSON kolonu ekliyoruz; boş kalırsa ana dile düşülür
 * (bkz. Product::getAttributesFor()).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $t) {
            $after = 'attributes';

            foreach (Locales::secondary() as $locale) {
                $column = 'attributes_' . $locale;

                if (! Schema::hasColumn('products', $column)) {
                    $t->json($column)->nullable()->after($after);
                    $after = $column;
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $t) {
            $drop = [];

            foreach (Locales::secondary() as $locale) {
                if (Schema::hasColumn('products', 'attributes_' . $locale)) {
                    $drop[] = 'attributes_' . $locale;
                }
            }

            if ($drop) {
                $t->dropColumn($drop);
            }
        });
    }
};
