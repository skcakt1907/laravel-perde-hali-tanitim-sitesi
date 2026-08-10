@extends('admin.layout')
@section('title', $service->exists ? 'Hizmet Düzenle' : 'Yeni Hizmet')

@section('content')
@php
$iconlar = [
    'bi-rulers'           => 'Ölçü alma',
    'bi-chat-dots'        => 'Danışmanlık',
    'bi-scissors'         => 'Ölçüye özel üretim',
    'bi-tools'            => 'Montaj',
    'bi-arrow-repeat'     => 'Tadilat / değişim',
    'bi-droplet'          => 'Temizlik / bakım',
    'bi-truck'            => 'Teslimat',
    'bi-brightness-high'  => 'Güneş koruma',
    'bi-moon-stars'       => 'Karartma',
    'bi-volume-down'      => 'Akustik',
    'bi-thermometer-half' => 'Isı yalıtımı',
    'bi-building'         => 'Ofis / proje işleri',
    'bi-grid-3x3'         => 'Halı hizmetleri',
    'bi-shield-check'     => 'Garanti',
    'bi-check2-circle'    => 'Genel',
];
$seciliIkon = old('icon', $service->icon);
if ($seciliIkon && ! isset($iconlar[$seciliIkon])) {
    $iconlar = [$seciliIkon => 'Mevcut (' . $seciliIkon . ')'] + $iconlar;
}
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">{{ $service->exists ? 'Hizmet Düzenle' : 'Yeni Hizmet' }}</h1>
        <div class="page-subtitle">{{ $service->exists ? $service->title : 'Yeni hizmet kartı ekleyin' }}</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.services.index') }}" class="btn btn-ghost"><i data-lucide="arrow-left"></i> Listeye dön</a>
    </div>
</div>

<form action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($service->exists)@method('PUT')@endif

    <div class="grid-8-4">
        <div class="card">
            @include('admin._partials.lang-fields', [
                'model'  => $service,
                'fields' => [
                    ['name' => 'title', 'label' => 'Başlık', 'required' => true,
                     'placeholder' => 'z. B. Kostenloses Aufmaß & Beratung',
                     'placeholder_en' => 'e.g. Free Measuring & Advice',
                     'placeholder_tr' => 'örn. Ücretsiz ölçü & danışmanlık'],
                    ['name' => 'summary', 'label' => 'Özet', 'type' => 'textarea', 'rows' => 3,
                     'help' => 'Hizmet kartlarında görünen kısa metin.'],
                    ['name' => 'content', 'label' => 'Detay metni', 'type' => 'textarea', 'rows' => 8],
                ],
            ])
        </div>

        <div>
            <div class="card mb-4">
                <div class="section-title"><i data-lucide="sliders-horizontal"></i> Görünüm</div>

                <div class="form-group">
                    <label class="form-label">İkon</label>
                    <div class="icon-picker">
                        <span class="preview" id="iconPrev"><i class="bi {{ $seciliIkon ?: 'bi-check2-circle' }}"></i></span>
                        <select name="icon" id="iconSel" class="form-select">
                            @foreach($iconlar as $cls => $ad)
                                <option value="{{ $cls }}" @selected($seciliIkon === $cls)>{{ $ad }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Sıra</label>
                    <input type="number" min="0" name="sira" class="form-input" value="{{ old('sira', $service->sira ?? 0) }}">
                </div>

                <label class="form-check mb-0">
                    <input type="checkbox" name="durum" value="1" @checked(old('durum', $service->durum ?? true))>
                    Yayında
                </label>
            </div>

            <div class="card">
                <div class="section-title"><i data-lucide="image"></i> Detay sayfası görseli</div>
                @if($service->image)
                    <img src="{{ $service->image }}" class="img-preview" alt="">
                @endif
                <div class="form-group">
                    <label class="form-label">Görsel URL</label>
                    <input name="image" class="form-input" value="{{ old('image', $service->image) }}" placeholder="https://…">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">veya dosya yükle</label>
                    <input type="file" name="image_file" accept="image/*" class="form-file">
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions-sticky">
        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Vazgeç</a>
        <button type="submit" class="btn btn-primary"><i data-lucide="check"></i> Kaydet</button>
    </div>
</form>

<script>
(function () {
    var sel = document.getElementById('iconSel'), prev = document.getElementById('iconPrev');
    if (sel && prev) sel.addEventListener('change', function () { prev.innerHTML = '<i class="bi ' + sel.value + '"></i>'; });
})();
</script>
@endsection
