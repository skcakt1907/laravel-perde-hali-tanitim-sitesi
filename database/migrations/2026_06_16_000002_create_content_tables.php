<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_tr')->nullable();
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->text('summary')->nullable();
            $table->text('summary_tr')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_tr')->nullable();
            $table->unsignedInteger('sira')->default(0);
            $table->boolean('durum')->default(true);
            $table->timestamps();
        });

        // Yapılan işler / Referenz-Projekte — galeri
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_tr')->nullable();
            $table->string('slug')->unique();
            $table->string('location')->nullable();        // Amsterdam, Utrecht...
            $table->string('kind')->nullable();            // Plissee, Rollo, Teppich... (filtre)
            $table->string('kind_tr')->nullable();
            $table->string('cover')->nullable();
            $table->json('images')->nullable();
            $table->text('summary')->nullable();
            $table->text('summary_tr')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_tr')->nullable();
            $table->date('tarih')->nullable();
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('sira')->default(0);
            $table->boolean('durum')->default(true);
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_tr')->nullable();
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->string('category_tr')->nullable();
            $table->string('image')->nullable();
            $table->text('summary')->nullable();
            $table->text('summary_tr')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_tr')->nullable();
            $table->date('tarih')->nullable();
            $table->boolean('durum')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('title_tr')->nullable();
            $table->text('comment');
            $table->text('comment_tr')->nullable();
            $table->unsignedTinyInteger('stars')->default(5);
            $table->string('photo')->nullable();
            $table->boolean('durum')->default(true);
            $table->timestamps();
        });

        // Kostenloses Aufmaß & Beratung — ücretsiz ölçü ve danışmanlık talebi
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('subject')->nullable();     // ilgilenilen ürün grubu
            $table->string('zip')->nullable();         // Postleitzahl
            $table->string('city')->nullable();        // Ort
            $table->text('address')->nullable();
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->text('note')->nullable();
            $table->string('locale', 5)->default('de'); // talebin geldiği dil
            $table->string('status')->default('yeni');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('appointments');
    }
};
