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

    <div class="lang-tabs">
        <button type="button" class="lang-tab active" data-lang-tab="about" data-locale="de"
                onclick="langTab('about', 'de')"><span class="flag">DE</span> Almanca</button>
        <button type="button" class="lang-tab" data-lang-tab="about" data-locale="tr"
                onclick="langTab('about', 'tr')"><span class="flag">TR</span> Türkçe</button>
    </div>

    <div class="lang-panel" data-lang-panel="about" data-locale="de">
        <div class="form-group">
            <label class="form-label">Başlık (Almanca)</label>
            <input type="text" name="hakkimizda_baslik" class="form-input" value="{{ $s('hakkimizda_baslik') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Metin (Almanca)</label>
            <textarea name="hakkimizda_metin" rows="7" class="form-textarea">{{ $s('hakkimizda_metin') }}</textarea>
        </div>
        <div class="form-group mb-0">
            <label class="form-label">Maddeler (Almanca) — her satır bir madde</label>
            <textarea name="hakkimizda_maddeler" rows="6" class="form-textarea">{{ $s('hakkimizda_maddeler') }}</textarea>
        </div>
    </div>

    <div class="lang-panel" data-lang-panel="about" data-locale="tr" hidden>
        <div class="form-group">
            <label class="form-label">Başlık (Türkçe)</label>
            <input type="text" name="hakkimizda_baslik_tr" class="form-input" value="{{ $s('hakkimizda_baslik_tr') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Metin (Türkçe)</label>
            <textarea name="hakkimizda_metin_tr" rows="7" class="form-textarea">{{ $s('hakkimizda_metin_tr') }}</textarea>
        </div>
        <div class="form-group mb-0">
            <label class="form-label">Maddeler (Türkçe)</label>
            <textarea name="hakkimizda_maddeler_tr" rows="6" class="form-textarea">{{ $s('hakkimizda_maddeler_tr') }}</textarea>
        </div>
    </div>
</div>
@endsection
