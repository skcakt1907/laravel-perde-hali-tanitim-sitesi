@extends('admin.layout')
@section('title', $service->exists ? 'Hizmet Düzenle' : 'Yeni Hizmet')

@section('content')
@php
$iconlar = [
    'bi-rulers'         => 'Ölçü alma',
    'bi-chat-dots'      => 'Danışmanlık',
    'bi-scissors'       => 'Ölçüye özel üretim',
    'bi-tools'          => 'Montaj',
    'bi-arrow-repeat'   => 'Tadilat / değişim',
    'bi-droplet'        => 'Temizlik / bakım',
    'bi-truck'          => 'Teslimat',
    'bi-brightness-high'=> 'Güneş koruma',
    'bi-moon-stars'     => 'Karartma',
    'bi-volume-down'    => 'Akustik',
    'bi-thermometer-half' => 'Isı yalıtımı',
    'bi-building'       => 'Ofis / proje işleri',
    'bi-grid-3x3'       => 'Halı hizmetleri',
    'bi-shield-check'   => 'Garanti',
    'bi-check2-circle'  => 'Genel',
];
$seciliIkon = old('icon', $service->icon);
if ($seciliIkon && ! isset($iconlar[$seciliIkon])) {
    $iconlar = [$seciliIkon => 'Mevcut (' . $seciliIkon . ')'] + $iconlar;
}
@endphp
<form action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}"
      method="POST" enctype="multipart/form-data" class="form-a">
    @csrf
    @if($service->exists)@method('PUT')@endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-a">
                <div class="lang-box">
                    <span class="lang-tag">DE — Almanca</span>
                    <label>Başlık *</label>
                    <input name="title" value="{{ old('title', $service->title) }}" required placeholder="z. B. Kostenloses Aufmaß">

                    <label>Özet</label>
                    <textarea name="summary" rows="3">{{ old('summary', $service->summary) }}</textarea>

                    <label>Detay metni</label>
                    <textarea name="content" rows="8">{{ old('content', $service->content) }}</textarea>
                </div>

                <div class="lang-box tr">
                    <span class="lang-tag">TR — Türkçe</span>
                    <label>Başlık</label>
                    <input name="title_tr" value="{{ old('title_tr', $service->title_tr) }}" placeholder="örn. Ücretsiz ölçü">

                    <label>Özet</label>
                    <textarea name="summary_tr" rows="3">{{ old('summary_tr', $service->summary_tr) }}</textarea>

                    <label>Detay metni</label>
                    <textarea name="content_tr" rows="8">{{ old('content_tr', $service->content_tr) }}</textarea>
                    <div class="hint">Boş bırakılırsa sitede Almanca metin gösterilir.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-a">
                <label>İkon</label>
                <div style="display:flex;align-items:center;gap:.6rem">
                    <span id="iconPrev" style="width:44px;height:44px;border:1px solid var(--aline);border-radius:10px;display:inline-flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--ap-text);background:#f3ecd9;flex-shrink:0">
                        <i class="bi {{ $seciliIkon ?: 'bi-check2-circle' }}"></i>
                    </span>
                    <select name="icon" id="iconSel" style="flex:1">
                        @foreach($iconlar as $cls => $ad)
                            <option value="{{ $cls }}" @selected($seciliIkon === $cls)>{{ $ad }} — {{ $cls }}</option>
                        @endforeach
                    </select>
                </div>

                <label>Sıra</label>
                <input type="number" min="0" name="sira" value="{{ old('sira', $service->sira ?? 0) }}">

                <label class="mt-2"><input type="checkbox" name="durum" value="1" @checked(old('durum', $service->durum ?? true)) style="width:auto"> Yayında</label>
            </div>

            <div class="card-a mt-3">
                <label>Görsel (detay sayfası)</label>
                @if($service->image)
                    <img src="{{ $service->image }}" style="width:100%;border-radius:10px;margin-bottom:.6rem" alt="">
                @endif
                <label>Görsel URL</label>
                <input name="image" value="{{ old('image', $service->image) }}" placeholder="https://...">
                <label>veya dosya yükle</label>
                <input type="file" name="image_file" accept="image/*">
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn-a"><i class="bi bi-check-lg"></i> Kaydet</button>
        <a href="{{ route('admin.services.index') }}" class="btn-a sec">Vazgeç</a>
    </div>
</form>

<script>
(function () {
    var sel = document.getElementById('iconSel'), prev = document.getElementById('iconPrev');
    if (sel && prev) sel.addEventListener('change', function () { prev.innerHTML = '<i class="bi ' + sel.value + '"></i>'; });
})();
</script>
@endsection
