<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hollandaca çeviri kolonları (`_nl`).
 *
 * 2026_08_11_000001 (İngilizce) migration'ının aynısı; oradaki desen bilinçli
 * olarak kopyalandı çünkü migration'lar geçmişi anlatır — eskisini düzenleyip
 * yeni sonek eklemek, daha önce çalıştırılmış sunucularda hiçbir şey yapmaz.
 *
 * `attributes_nl` de burada eklenir (000002 migration'ı Locales::secondary()
 * üzerinden dönüyor ama o dosya çalışmış sayıldığı için tekrar koşmaz).
 */
return new class extends Migration
{
    /** tablo => [kolon => tip] */
    private array $map = [
        'categories'   => ['name' => 'string', 'description' => 'text'],
        'products'     => ['name' => 'string', 'short_desc' => 'text', 'description' => 'longText'],
        'services'     => ['title' => 'string', 'summary' => 'text', 'content' => 'longText'],
        'projects'     => ['title' => 'string', 'kind' => 'string', 'summary' => 'text', 'content' => 'longText'],
        'posts'        => ['title' => 'string', 'category' => 'string', 'summary' => 'text', 'content' => 'longText'],
        'testimonials' => ['title' => 'string', 'comment' => 'text'],
    ];

    public function up(): void
    {
        foreach ($this->map as $table => $columns) {
            Schema::table($table, function (Blueprint $t) use ($table, $columns) {
                foreach ($columns as $column => $type) {
                    $new = $column . '_nl';

                    if (Schema::hasColumn($table, $new)) {
                        continue;
                    }

                    // Ana dil kolonunun hemen ardına (panelde okunurluk için)
                    $t->{$type}($new)->nullable()->after($column);
                }
            });
        }

        Schema::table('products', function (Blueprint $t) {
            if (! Schema::hasColumn('products', 'attributes_nl')) {
                $t->json('attributes_nl')->nullable()->after('attributes');
            }
        });
    }

    public function down(): void
    {
        foreach ($this->map as $table => $columns) {
            Schema::table($table, function (Blueprint $t) use ($table, $columns) {
                $drop = [];

                foreach (array_keys($columns) as $column) {
                    if (Schema::hasColumn($table, $column . '_nl')) {
                        $drop[] = $column . '_nl';
                    }
                }

                if ($drop) {
                    $t->dropColumn($drop);
                }
            });
        }

        Schema::table('products', function (Blueprint $t) {
            if (Schema::hasColumn('products', 'attributes_nl')) {
                $t->dropColumn('attributes_nl');
            }
        });
    }
};
