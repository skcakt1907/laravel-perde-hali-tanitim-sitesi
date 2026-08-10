<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * İngilizce çeviri kolonları (`_en`).
 *
 * Desen: temel kolon = birincil dil (Almanca), `_<kod>` sonekli kolon = o dil.
 * Yeni bir dil eklenirse bu migration'ın aynısı yeni sonekle yazılır;
 * kod tarafında App\Support\Locales'e kod eklemek yeterlidir.
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
                    $new = $column . '_en';

                    if (Schema::hasColumn($table, $new)) {
                        continue;
                    }

                    // Türkçe kolonun hemen ardına ekle (panelde okunurluk için)
                    $t->{$type}($new)->nullable()->after($column . '_tr');
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->map as $table => $columns) {
            Schema::table($table, function (Blueprint $t) use ($table, $columns) {
                $drop = [];

                foreach (array_keys($columns) as $column) {
                    if (Schema::hasColumn($table, $column . '_en')) {
                        $drop[] = $column . '_en';
                    }
                }

                if ($drop) {
                    $t->dropColumn($drop);
                }
            });
        }
    }
};
