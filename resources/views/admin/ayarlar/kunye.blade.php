@extends('admin.ayarlar._base')

@section('save_hint', 'Bu bilgiler Impressum, Datenschutz, AGB ve Widerruf sayfalarında otomatik görünür.')

@section('fields')
@php
    $s = fn ($k, $d = '') => old($k, $settings[$k] ?? $d);
    $eksik = collect(['firma_unvan', 'yetkili', 'kvk_no', 'btw_no'])
        ->filter(fn ($k) => blank($settings[$k] ?? null))->count();
@endphp

@if($eksik > 0)
    <div class="info-card is-warning">
        <i data-lucide="alert-triangle" class="ic"></i>
        <div class="body">
            <strong>{{ $eksik }} alan boş.</strong> Bu bilgiler yasal sayfalarda &ldquo;—&rdquo; olarak
            görünür; Almanya/Hollanda mevzuatında künye (Impressum) zorunludur.
            <strong>Site yayına alınmadan önce doldurulmalı</strong> ve yasal metinler avukata onaylatılmalı.
        </div>
    </div>
@endif

<div class="section">
    <div class="section-title"><i data-lucide="building-2"></i><span>Firma Bilgileri</span></div>

    <div class="form-grid">
        <div class="form-group full">
            <label class="form-label">Resmî firma ünvanı</label>
            <input type="text" name="firma_unvan" class="form-input" value="{{ $s('firma_unvan') }}"
                   placeholder="MC Gordijnen">
            <div class="form-help">Ticaret sicilinde kayıtlı tam ad.</div>
        </div>

        <div class="form-group full">
            <label class="form-label">Yetkili kişi</label>
            <input type="text" name="yetkili" class="form-input" value="{{ $s('yetkili') }}">
            <div class="form-help">Impressum'da &ldquo;Inhaber&rdquo; ve içerik sorumlusu olarak geçer.</div>
        </div>

        <div class="form-group">
            <label class="form-label">KvK numarası</label>
            <input type="text" name="kvk_no" class="form-input" value="{{ $s('kvk_no') }}">
            <div class="form-help">Hollanda ticaret sicil numarası.</div>
        </div>

        <div class="form-group">
            <label class="form-label">BTW (KDV) numarası</label>
            <input type="text" name="btw_no" class="form-input" value="{{ $s('btw_no') }}">
            <div class="form-help">Almanca sayfalarda &ldquo;USt-IdNr.&rdquo; olarak görünür.</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title"><i data-lucide="file-check"></i><span>Yasal Sayfalar</span></div>
    <div class="form-help" style="margin-top:0;margin-bottom:12px">
        Metinler şablon olarak hazır ve iki dilde yayında. İçerikleri değiştirmek için
        <code>resources/views/pages/legal/</code> altındaki dosyalar düzenlenir.
    </div>
    <div class="chip-row">
        @foreach(\App\Http\Controllers\LegalController::PAGES as $slug => $p)
            <a href="{{ route('legal', ['locale' => 'de', 'slug' => $slug]) }}" target="_blank"
               rel="noopener" class="chip">
                <i data-lucide="external-link" style="width:13px;height:13px"></i>
                {{ __($p[0], [], 'de') }}
            </a>
        @endforeach
    </div>
</div>
@endsection
