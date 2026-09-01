@extends('admin.ayarlar._base')

@section('fields')
@php $s = fn ($k, $d = '') => old($k, $settings[$k] ?? $d); @endphp

<div class="section">
    <div class="section-title"><i data-lucide="globe"></i><span>Site Bilgileri</span></div>

    <div class="form-grid">
        <div class="form-group full mb-0">
            <label class="form-label">Site adı</label>
            <input type="text" name="site_adi" class="form-input" value="{{ $s('site_adi') }}"
                   placeholder="MC Gordijnen">
            <div class="form-help">Sekme başlığında, alt bilgide ve e-postalarda görünür — tüm dillerde aynıdır.</div>
        </div>

        <div class="form-group full mb-0">
            <label class="form-label">Site adresi</label>
            <input type="url" name="site_adresi" class="form-input" value="{{ $s('site_adresi') }}"
                   placeholder="https://ornekperde.com" inputmode="url">
            <div class="form-help">
                Panelin sağ üstündeki <strong>“Siteyi yeni sekmede aç”</strong> butonu buraya gider.
                Boş bırakılırsa panele hangi adresten girdiysen buton da oraya gider —
                geçici/önizleme bir adresten girildiğinde yanlış yere gitmesinin sebebi budur.
                Buraya sitenin <strong>gerçek adresini</strong> yazmanı öneririm.
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title"><i data-lucide="search"></i><span>Arama Motoru Açıklaması</span></div>

    <div class="info-card">
        <i data-lucide="info" class="ic"></i>
        <div class="body">
            Google'da site adının altında görünen metindir; <strong>160 karakteri</strong> geçmemeli.
            Ana dil (Almanca) zorunludur; diğer diller boş kalırsa o dillerde de Almanca metin kullanılır.
        </div>
    </div>

    @include('admin.ayarlar._partials.lang-tabs', [
        'group'    => 'aciklama',
        'settings' => $settings,
        'fields'   => [
            ['name' => 'site_aciklama', 'label' => 'Site açıklaması', 'type' => 'textarea',
             'rows' => 3, 'counter' => 160],
        ],
    ])
</div>
@endsection
