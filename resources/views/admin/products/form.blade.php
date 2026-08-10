@extends('admin.layout')
@section('title', $product->exists ? 'Ürün Düzenle' : 'Yeni Ürün')

@section('content')
<form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
      method="POST" enctype="multipart/form-data" class="form-a">
    @csrf
    @if($product->exists)@method('PUT')@endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-a">
                <div class="lang-box">
                    <span class="lang-tag">DE — Almanca (sitenin ana dili)</span>
                    <label>Ürün adı *</label>
                    <input name="name" value="{{ old('name', $product->name) }}" required>

                    <label>Kısa açıklama</label>
                    <input name="short_desc" value="{{ old('short_desc', $product->short_desc) }}">

                    <label>Detaylı açıklama</label>
                    <textarea name="description" rows="6">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="lang-box tr">
                    <span class="lang-tag">TR — Türkçe</span>
                    <label>Ürün adı</label>
                    <input name="name_tr" value="{{ old('name_tr', $product->name_tr) }}">

                    <label>Kısa açıklama</label>
                    <input name="short_desc_tr" value="{{ old('short_desc_tr', $product->short_desc_tr) }}">

                    <label>Detaylı açıklama</label>
                    <textarea name="description_tr" rows="6">{{ old('description_tr', $product->description_tr) }}</textarea>
                    <div class="hint">Boş bırakılan Türkçe alanlar sitede Almanca metinle gösterilir.</div>
                </div>

                <label>Özellikler (her satır <code>anahtar: değer</code>)</label>
                <textarea name="attributes_raw" rows="6"
                          placeholder="Material: 100% Polyester&#10;Lichtdurchlässigkeit: halbtransparent&#10;Montage: Wand oder Decke&#10;Pflege: 30° Feinwäsche">{{ old('attributes_raw', $product->attributes ? collect($product->attributes)->map(fn ($v, $k) => "$k: $v")->implode("\n") : '') }}</textarea>
                <div class="hint">Almanca yazın — ürün detayında olduğu gibi görünür.</div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-a">
                <label>Kategori</label>
                <select name="category_id">
                    <option value="">— Seçiniz —</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>

                <label>Marka / Koleksiyon</label>
                <input name="brand" value="{{ old('brand', $product->brand) }}">

                <label>Ürün kodu</label>
                <input name="sku" value="{{ old('sku', $product->sku) }}">

                <div class="row">
                    <div class="col-7">
                        <label>Başlangıç fiyatı (€)</label>
                        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}">
                    </div>
                    <div class="col-5">
                        <label>Birim</label>
                        <input name="price_unit" value="{{ old('price_unit', $product->price_unit) }}" placeholder="m²">
                    </div>
                </div>
                <div class="hint">Fiyatı 0 bırakırsanız sitede &ldquo;Preis auf Anfrage&rdquo; yazar.</div>

                <label>Sıra</label>
                <input type="number" min="0" name="sira" value="{{ old('sira', $product->sira) }}">

                <label class="mt-3"><input type="checkbox" name="featured" value="1" @checked(old('featured', $product->featured)) style="width:auto"> Anasayfada öne çıkar</label>
                <label><input type="checkbox" name="durum" value="1" @checked(old('durum', $product->durum ?? true)) style="width:auto"> Yayında</label>
            </div>

            <div class="card-a mt-3">
                <label>Kapak görseli</label>
                @if($product->cover)
                    <img src="{{ $product->cover }}" style="width:100%;border-radius:10px;margin-bottom:.6rem" alt="">
                @endif
                <label>Görsel URL</label>
                <input name="cover" value="{{ old('cover', $product->cover) }}" placeholder="https://...">
                <label>veya dosya yükle</label>
                <input type="file" name="image_file" accept="image/*">

                <label class="mt-3">Galeri</label>
                @foreach($product->images ?? [] as $img)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <img src="{{ $img }}" class="thumb" alt="">
                        <label class="m-0" style="font-weight:500;font-size:.85rem">
                            <input type="checkbox" name="keep_images[]" value="{{ $img }}" checked style="width:auto"> Kalsın
                        </label>
                    </div>
                @endforeach
                <input type="file" name="gallery_files[]" accept="image/*" multiple>
                <div class="hint">Birden fazla dosya seçebilirsiniz.</div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn-a"><i class="bi bi-check-lg"></i> Kaydet</button>
        <a href="{{ route('admin.products.index') }}" class="btn-a sec">Vazgeç</a>
    </div>
</form>
@endsection
