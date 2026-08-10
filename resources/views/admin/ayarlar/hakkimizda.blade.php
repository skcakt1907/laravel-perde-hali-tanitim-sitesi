@extends('admin.ayarlar._base')

@section('fields')
@php
    $s = fn ($k, $d = '') => old($k, $settings[$k] ?? $d);
    $gorsel = $s('hakkimizda_gorsel');
@endphp

<div class="section">
    <div class="section-title"><i data-lucide="image"></i><span>Sayfa Görseli</span></div>

    <div class="image-field">
        <div class="thumb-box {{ $gorsel ? '' : 'empty' }}">
            @if($gorsel)
                <img src="{{ $gorsel }}" alt="" id="aboutPreview">
            @else
                <span>Görsel yok</span>
            @endif
        </div>
        <div class="form-group mb-0">
            <label class="form-label">Görsel adresi (URL)</label>
            <input type="text" name="hakkimizda_gorsel" class="form-input" value="{{ $gorsel }}"
                   placeholder="{{ asset('img/demo/about.jpg') }}"
                   oninput="var p=document.getElementById('aboutPreview'); if(p) p.src=this.value">
            <div class="form-help">Dikey (portre) görseller bu bölümde daha iyi durur.</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title"><i data-lucide="file-text"></i><span>Başlık, Metin ve Maddeler</span></div>

    <div class="info-card">
        <i data-lucide="info" class="ic"></i>
        <div class="body">
            <strong>Maddeler</strong> alanında her satır bir madde olur ve yanına onay işareti gelir
            (Ücretsiz ölçü, Kendi montaj ekibimiz…). Metinde boş satır bırakırsanız sitede paragraf olur.
        </div>
    </div>

    @include('admin.ayarlar._partials.lang-tabs', [
        'group'    => 'about',
        'settings' => $settings,
        'fields'   => [
            ['name' => 'hakkimizda_baslik',   'label' => 'Başlık'],
            ['name' => 'hakkimizda_metin',    'label' => 'Metin', 'type' => 'textarea', 'rows' => 7],
            ['name' => 'hakkimizda_maddeler', 'label' => 'Maddeler (her satır bir madde)',
             'type' => 'textarea', 'rows' => 6],
        ],
    ])
</div>
@endsection
