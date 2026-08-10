<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Katalog tabloları. Metin alanları iki dilli: temel kolon = Almanca (birincil),
 * `_tr` sonekli kolon = Türkçe. Boş bırakılırsa Almanca'ya düşer (HasTranslations).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');                       // Gardinen, Plissee, Teppiche...
            $table->string('name_tr')->nullable();
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->text('description_tr')->nullable();
            $table->unsignedInteger('sira')->default(0);
            $table->boolean('durum')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('name_tr')->nullable();
            $table->string('slug')->unique();
            $table->string('sku')->nullable();
            $table->string('brand')->nullable();
            $table->string('cover')->nullable();
            $table->json('images')->nullable();
            $table->text('short_desc')->nullable();
            $table->text('short_desc_tr')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_tr')->nullable();
            // Tanıtım sitesi: fiyat "ab X €" başlangıç fiyatıdır, 0 ise "Preis auf Anfrage"
            $table->decimal('price', 10, 2)->default(0);
            $table->string('price_unit')->nullable();      // m², Stück, lfd. Meter
            $table->json('attributes')->nullable();        // Material, Lichtdurchlässigkeit, Montage...
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('sira')->default(0);
            $table->boolean('durum')->default(true);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('anahtar')->unique();
            $table->text('deger')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('settings');
    }
};
