@extends('admin.layout')
@section('title', $category->exists ? 'Kategori Düzenle' : 'Yeni Kategori')

@section('content')
@php
$iconlar = [
    'bi-columns-gap'         => 'Perde / panel (genel)',
    'bi-window'              => 'Pencere',
    'bi-window-sidebar'      => 'Stor / rulo',
    'bi-list'                => 'Jaluzi (yatay lamel)',
    'bi-distribute-vertical' => 'Dikey lamel',
    'bi-layers'              => 'Plise / katmanlı',
    'bi-brightness-high'     => 'Güneşlik',
    'bi-moon-stars'          => 'Karartma',
    'bi-grid-3x3'            => 'Halı / kilim',
    'bi-palette'             => 'Kumaş & renk',
    'bi-scissors'            => 'Ölçüye özel',
    'bi-house-door'          => 'Ev tekstili',
    'bi-building'            => 'Ofis / proje',
    'bi-stars'               => 'Premium',
    'bi-gem'                 => 'Lüks koleksiyon',
    'bi-tags'                => 'Genel',
];
$seciliIkon = old('icon', $category->icon);
// Listede olmayan mevcut bir ikon varsa kaybolmasın
if ($seciliIkon && ! isset($iconlar[$seciliIkon])) {
    $iconlar = [$seciliIkon => 'Mevcut (' . $seciliIkon . ')'] + $iconlar;
}
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">{{ $category->exists ? 'Kategori Düzenle' : 'Yeni Kategori' }}</h1>
        <div class="page-subtitle">{{ $category->exists ? $category->name : 'Ürün grubu ekleyin' }}</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost"><i data-lucide="arrow-left"></i> Listeye dön</a>
    </div>
</div>

<form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($category->exists)@method('PUT')@endif

    <div class="grid-8-4">
        <div class="card">
            @include('admin._partials.lang-fields', [
                'model'  => $category,
                'fields' => [
                    ['name' => 'name', 'label' => 'Kategori adı', 'required' => true,
                     'placeholder' => 'z. B. Plissees',
                     'placeholder_nl' => 'bijv. Plisségordijnen',
                     'placeholder_en' => 'e.g. Pleated Blinds',
                     'placeholder_tr' => 'örn. Plise Perde'],
                    ['name' => 'description', 'label' => 'Açıklama', 'type' => 'textarea', 'rows' => 3,
                     'help' => 'Kategori sayfasının başlığı altında ve anasayfa kartında görünür.'],
                ],
            ])
        </div>

        <div>
            <div class="card mb-4">
                <div class="section-title"><i data-lucide="sliders-horizontal"></i> Görünüm</div>

                <div class="form-group">
                    <label class="form-label">Menü ikonu</label>
                    <div class="icon-picker">
                        <span class="preview" id="iconPrev"><i class="bi {{ $seciliIkon ?: 'bi-columns-gap' }}"></i></span>
                        <select name="icon" id="iconSel" class="form-select">
                            @foreach($iconlar as $cls => $ad)
                                <option value="{{ $cls }}" @selected($seciliIkon === $cls)>{{ $ad }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Sıra</label>
                    <input type="number" min="0" name="sira" class="form-input" value="{{ old('sira', $category->sira ?? 0) }}">
                </div>

                <label class="form-check mb-0">
                    <input type="checkbox" name="durum" value="1" @checked(old('durum', $category->durum ?? true))>
                    Yayında
                </label>
            </div>

            <div class="card">
                <div class="section-title"><i data-lucide="image"></i> Kategori görseli</div>
                @if($category->image)
                    <img src="{{ media($category->image) }}" class="img-preview" alt="">
                @endif
                <div class="form-group">
                    <label class="form-label">Görsel URL</label>
                    <input name="image" class="form-input" value="{{ old('image', $category->image) }}" placeholder="https://…">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">veya dosya yükle</label>
                    <input type="file" name="image_file" accept="image/*" class="form-file">
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions-sticky">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Vazgeç</a>
        <button type="submit" class="btn btn-primary"><i data-lucide="check"></i> Kaydet</button>
    </div>
</form>

<script>
(function () {
    var sel = document.getElementById('iconSel'), prev = document.getElementById('iconPrev');
    if (sel && prev) {
        sel.addEventListener('change', function () {
            prev.innerHTML = '<i class="bi ' + sel.value + '"></i>';
        });
    }
})();
</script>
@endsection
