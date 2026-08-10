<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Admin formlarında görsel yükleme + tekil slug üretimi.
 *
 * Güvenlik notu: uzantı istemciden DEĞİL doğrulanmış dosya içeriğinden
 * (guessExtension) türetilir; whitelist dışı her şey jpg'ye düşer ve
 * public/uploads/.htaccess script çalıştırmayı engeller.
 */
trait HandlesUploads
{
    protected const IMAGE_EXT = ['jpg' => 'jpg', 'jpeg' => 'jpg', 'png' => 'png', 'webp' => 'webp', 'gif' => 'gif'];

    /** Tek görsel: yüklenmişse kaydeder, yoksa formdaki URL'i / mevcut değeri korur. */
    protected function resolveImage(Request $request, string $field, ?string $current, string $dir = 'products'): ?string
    {
        if ($request->hasFile($field)) {
            return $this->storeImage($request->file($field), $dir);
        }

        return $current ?: null;
    }

    /**
     * Galeri: mevcut URL listesine yeni yüklenen dosyaları ekler.
     *
     * @param  array<int,string>  $current
     * @return array<int,string>|null
     */
    protected function resolveGallery(Request $request, string $field, array $current, string $dir = 'products'): ?array
    {
        $images = array_values(array_filter($current));

        foreach ((array) $request->file($field, []) as $file) {
            if ($file instanceof UploadedFile) {
                $images[] = $this->storeImage($file, $dir);
            }
        }

        return $images ?: null;
    }

    protected function storeImage(UploadedFile $file, string $dir): string
    {
        $ext  = self::IMAGE_EXT[strtolower($file->guessExtension() ?: '')] ?? 'jpg';
        $name = Str::random(20) . '.' . $ext;
        $path = public_path('uploads/' . $dir);

        if (! is_dir($path)) {
            @mkdir($path, 0775, true);
        }

        $file->move($path, $name);

        return asset('uploads/' . $dir . '/' . $name);
    }

    /** Satır satır "Anahtar: Değer" metnini diziye çevirir. */
    protected function parseAttributes(?string $raw): ?array
    {
        if (! $raw) {
            return null;
        }

        $attrs = [];

        foreach (preg_split('/\r?\n/', $raw) as $line) {
            if (! str_contains($line, ':')) {
                continue;
            }

            [$k, $v] = array_map('trim', explode(':', $line, 2));

            if ($k !== '' && $v !== '') {
                $attrs[$k] = $v;
            }
        }

        return $attrs ?: null;
    }

    /** @param  class-string<Model>  $model */
    protected function uniqueSlug(string $model, string $text, ?int $ignore = null): string
    {
        $base = Str::slug($text) ?: Str::random(8);
        $slug = $base;
        $i    = 1;

        while ($model::where('slug', $slug)->when($ignore, fn ($q) => $q->where('id', '<>', $ignore))->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }
}
