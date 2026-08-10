@extends('admin.ayarlar._base')

@section('fields')
@php $s = fn ($k, $d = '') => old($k, $settings[$k] ?? $d); @endphp

<div class="section">
    <div class="section-title"><i data-lucide="globe"></i><span>Site Bilgileri</span></div>

    <div class="form-grid">
        <div class="form-group full">
            <label class="form-label">Site adı</label>
            <input type="text" name="site_adi" class="form-input" value="{{ $s('site_adi') }}"
                   placeholder="MC Gordijnen">
            <div class="form-help">Sekme başlığında, alt bilgide ve e-postalarda görünür.</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title"><i data-lucide="search"></i><span>Arama Motoru Açıklaması</span></div>

    <div class="info-card">
        <i data-lucide="info" class="ic"></i>
        <div class="body">
            Google'da site adının altında görünen metindir. <strong>160 karakteri</strong> geçmemeli.
            Almanca alan zorunludur; Türkçe boş kalırsa Türkçe sayfalarda da Almanca metin kullanılır.
        </div>
    </div>

    <div class="lang-tabs">
        <button type="button" class="lang-tab active" data-lang-tab="aciklama" data-locale="de"
                onclick="langTab('aciklama', 'de')">
            <span class="flag">DE</span> Almanca
        </button>
        <button type="button" class="lang-tab" data-lang-tab="aciklama" data-locale="tr"
                onclick="langTab('aciklama', 'tr')">
            <span class="flag">TR</span> Türkçe
        </button>
    </div>

    <div class="lang-panel" data-lang-panel="aciklama" data-locale="de">
        <div class="form-group mb-0">
            <label class="form-label">Site açıklaması (Almanca)</label>
            <textarea name="site_aciklama" rows="3" class="form-textarea"
                      data-counter="descDe" data-counter-max="160"
                      onkeyup="updateCharCounter(this, 'descDe', 160)">{{ $s('site_aciklama') }}</textarea>
            <div class="char-counter" id="descDe">0 / 160</div>
        </div>
    </div>

    <div class="lang-panel" data-lang-panel="aciklama" data-locale="tr" hidden>
        <div class="form-group mb-0">
            <label class="form-label">Site açıklaması (Türkçe)</label>
            <textarea name="site_aciklama_tr" rows="3" class="form-textarea"
                      data-counter="descTr" data-counter-max="160"
                      onkeyup="updateCharCounter(this, 'descTr', 160)">{{ $s('site_aciklama_tr') }}</textarea>
            <div class="char-counter" id="descTr">0 / 160</div>
        </div>
    </div>
</div>
@endsection
