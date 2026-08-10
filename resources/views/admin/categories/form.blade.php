@extends('admin.layout')
@section('title', $category->exists ? 'Kategori Düzenle' : 'Yeni Kategori')

@section('content')
@php
$iconlar = [
    'bi-columns-gap'      => 'Perde / panel (genel)',
    'bi-window'           => 'Pencere',
    'bi-window-sidebar'   => 'Stor / rulo',
    'bi-list'             => 'Jaluzi (yatay lamel)',
    'bi-distribute-vertical' => 'Dikey lamel',
    'bi-layers'           => 'Plise / katmanlı',
    'bi-brightness-high'  => 'Güneşlik',
    'bi-moon-stars'       => 'Karartma',
    'bi-grid-3x3'         => 'Halı / kilim',
    'bi-palette'          => 'Kumaş & renk',
    'bi-scissors'         => 'Ölçüye özel',
    'bi-house-door'       => 'Ev tekstili',
    'bi-building'         => 'Ofis / proje',
    'bi-stars'            => 'Premium',
    'bi-gem'              => 'Lüks koleksiyon',
    'bi-tags'             => 'Genel',
];
$seciliIkon = old('icon', $category->icon);
// Listede olmayan mevcut bir ikon varsa kaybolmasın
if ($seciliIkon && ! isset($iconlar[$seciliIkon])) {
    $iconlar = [$seciliIkon => 'Mevcut (' . $seciliIkon . ')'] + $iconlar;
}
@endphp
<form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      method="POST" enctype="multipart/form-data" class="form-a">
    @csrf
    @if($category->exists)@method('PUT')@endif
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card-a">
                <div class="lang-box">
                    <span class="lang-tag">DE — Almanca</span>
                    <label>Kategori adı *</label>
                    <input name="name" value="{{ old('name', $category->name) }}" required placeholder="z. B. Plissees">

                    <label>Açıklama</label>
                    <textarea name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="lang-box tr">
                    <span class="lang-tag">TR — Türkçe</span>
                    <label>Kategori adı</label>
                    <input name="name_tr" value="{{ old('name_tr', $category->name_tr) }}" placeholder="örn. Plise Perde">

                    <label>Açıklama</label>
                    <textarea name="description_tr" rows="3">{{ old('description_tr', $category->description_tr) }}</textarea>
                    <div class="hint">Boş bırakılırsa sitede Almanca metin gösterilir.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card-a">
                <label>İkon</label>
                <div style="display:flex;align-items:center;gap:.6rem">
                    <span id="iconPrev" style="width:44px;height:44px;border:1px solid var(--aline);border-radius:10px;display:inline-flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--ap-text);background:#f3ecd9;flex-shrink:0">
                        <i class="bi {{ $seciliIkon ?: 'bi-columns-gap' }}"></i>
                    </span>
                    <select name="icon" id="iconSel" style="flex:1">
                        @foreach($iconlar as $cls => $ad)
                            <option value="{{ $cls }}" @selected($seciliIkon === $cls)>{{ $ad }} — {{ $cls }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="hint">Menüdeki açılır listede görünür.</div>

                <label>Sıra</label>
                <input type="number" min="0" name="sira" value="{{ old('sira', $category->sira ?? 0) }}">

                <label class="mt-2"><input type="checkbox" name="durum" value="1" @checked(old('durum', $category->durum ?? true)) style="width:auto"> Yayında</label>
            </div>

            <div class="card-a mt-3">
                <label>Kategori görseli</label>
                @if($category->image)
                    <img src="{{ $category->image }}" style="width:100%;border-radius:10px;margin-bottom:.6rem" alt="">
                @endif
                <label>Görsel URL</label>
                <input name="image" value="{{ old('image', $category->image) }}" placeholder="https://...">
                <label>veya dosya yükle</label>
                <input type="file" name="image_file" accept="image/*">
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn-a"><i class="bi bi-check-lg"></i> Kaydet</button>
        <a href="{{ route('admin.categories.index') }}" class="btn-a sec">Vazgeç</a>
    </div>
</form>

<script>
(function () {
    var sel = document.getElementById('iconSel'),
        prev = document.getElementById('iconPrev');
    if (sel && prev) {
        sel.addEventListener('change', function () {
            prev.innerHTML = '<i class="bi ' + sel.value + '"></i>';
        });
    }
})();
</script>
@endsection
