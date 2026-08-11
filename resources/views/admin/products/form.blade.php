@extends('admin.layout')
@section('title', $product->exists ? 'Ürün Düzenle' : 'Yeni Ürün')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $product->exists ? 'Ürün Düzenle' : 'Yeni Ürün' }}</h1>
        <div class="page-subtitle">
            {{ $product->exists ? $product->name : 'Ana dil (Almanca) alanları zorunlu; diğer diller boş kalırsa site ana dili gösterir.' }}
        </div>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.products.index') }}" class="btn btn-ghost"><i data-lucide="arrow-left"></i> Listeye dön</a>
    </div>
</div>

<form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($product->exists)@method('PUT')@endif

    <div class="grid-8-4">
        <div>
            <div class="card mb-4">
                @include('admin._partials.lang-fields', [
                    'model'  => $product,
                    'fields' => [
                        ['name' => 'name', 'label' => 'Ürün adı', 'required' => true,
                         'placeholder' => 'z. B. Wabenplissee Sand Thermo',
                         'placeholder_nl' => 'bijv. Duette-plissé zand, isolerend',
                         'placeholder_en' => 'e.g. Honeycomb Pleated Blind, Sand',
                         'placeholder_tr' => 'örn. Petek Plise — Kum'],
                        ['name' => 'short_desc', 'label' => 'Kısa açıklama',
                         'help' => 'Ürün kartlarında ve detay sayfasının başında görünür.'],
                        ['name' => 'description', 'label' => 'Detaylı açıklama', 'type' => 'textarea', 'rows' => 6],
                    ],
                ])
            </div>

            <div class="card">
                <div class="section-title"><i data-lucide="list"></i> Özellikler</div>
                <div class="form-help" style="margin-top:0;margin-bottom:14px">
                    Her satır <code>anahtar: değer</code>. Her dil ayrı yazılır; bir dil boş
                    bırakılırsa o dilde ana dilin tablosu gösterilir.
                </div>

                @foreach(\App\Support\Locales::labels() as $code => $label)
                    @php
                        $isPrimary = $code === \App\Support\Locales::primary();
                        $field     = $isPrimary ? 'attributes_raw' : 'attributes_raw_' . $code;
                        $current   = $isPrimary
                            ? $product->attributes_list
                            : ($product->{'attributes_' . $code} ?? []);
                        $satirlar  = collect($current)->map(fn ($v, $k) => "$k: $v")->implode("\n");
                        $ipucu     = [
                            'nl' => "Materiaal: 100% polyester\nLichtdoorlatendheid: halftransparant\nMontage: wand of plafond",
                            'de' => "Material: 100% Polyester\nLichtdurchlässigkeit: halbtransparent\nMontage: Wand oder Decke",
                            'en' => "Material: 100% polyester\nLight transmission: semi-transparent\nFitting: wall or ceiling",
                            'tr' => "Malzeme: %100 polyester\nIşık geçirgenliği: yarı şeffaf\nMontaj: duvar veya tavan",
                        ][$code] ?? '';
                    @endphp

                    <div class="lang-box {{ $isPrimary ? '' : 'secondary' }}">
                        <span class="lang-tag">{{ strtoupper($code) }} — {{ $label }}{{ $isPrimary ? ' (ana dil)' : '' }}</span>
                        <div class="form-group mb-0">
                            <textarea name="{{ $field }}" class="form-textarea" rows="6"
                                      placeholder="{{ $ipucu }}">{{ old($field, $satirlar) }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <div class="card mb-4">
                <div class="section-title"><i data-lucide="sliders-horizontal"></i> Yayın bilgileri</div>

                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select">
                        <option value="">— Seçiniz —</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Marka / koleksiyon</label>
                        <input name="brand" class="form-input" value="{{ old('brand', $product->brand) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ürün kodu</label>
                        <input name="sku" class="form-input" value="{{ old('sku', $product->sku) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Başlangıç fiyatı (€)</label>
                        <input type="number" step="0.01" min="0" name="price" class="form-input"
                               value="{{ old('price', $product->price) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Birim</label>
                        <input name="price_unit" class="form-input" value="{{ old('price_unit', $product->price_unit) }}"
                               placeholder="m² / Stück">
                    </div>
                    <div class="form-group full">
                        <div class="form-help" style="margin-top:0">
                            Fiyatı <strong>0</strong> bırakırsanız sitede &ldquo;Preis auf Anfrage&rdquo; / &ldquo;Fiyat için sorunuz&rdquo; yazar.
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sıra</label>
                        <input type="number" min="0" name="sira" class="form-input" value="{{ old('sira', $product->sira) }}">
                    </div>
                </div>

                <label class="form-check">
                    <input type="checkbox" name="featured" value="1" @checked(old('featured', $product->featured))>
                    Anasayfada öne çıkar
                </label>
                <label class="form-check mb-0">
                    <input type="checkbox" name="durum" value="1" @checked(old('durum', $product->durum ?? true))>
                    Yayında
                </label>
            </div>

            <div class="card">
                <div class="section-title"><i data-lucide="image"></i> Görseller</div>

                @if($product->cover)
                    <img src="{{ media($product->cover) }}" class="img-preview" alt="">
                @endif

                <div class="form-group">
                    <label class="form-label">Kapak görseli (URL)</label>
                    <input name="cover" class="form-input" value="{{ old('cover', $product->cover) }}" placeholder="https://…">
                </div>
                <div class="form-group">
                    <label class="form-label">veya dosya yükle</label>
                    <input type="file" name="image_file" accept="image/*" class="form-file">
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Galeri</label>
                    @if($product->images)
                        <div class="gallery-keep">
                            @foreach($product->images as $img)
                                <div class="gallery-keep-item">
                                    <img src="{{ media($img) }}" alt="">
                                    <label><input type="checkbox" name="keep_images[]" value="{{ $img }}" checked> Kalsın</label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <input type="file" name="gallery_files[]" accept="image/*" multiple class="form-file">
                    <div class="form-help">Birden fazla dosya seçebilirsiniz.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions-sticky">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Vazgeç</a>
        <button type="submit" class="btn btn-primary"><i data-lucide="check"></i> Kaydet</button>
    </div>
</form>
@endsection
