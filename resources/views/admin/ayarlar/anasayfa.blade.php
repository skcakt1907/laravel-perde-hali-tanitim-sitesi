@extends('admin.ayarlar._base')

@section('fields')
@php
    $s = fn ($k, $d = '') => old($k, $settings[$k] ?? $d);
    $hero = $s('hero_gorsel');
@endphp

<div class="section">
    <div class="section-title"><i data-lucide="image"></i><span>Hero Görseli</span></div>

    <div class="image-field">
        <div class="thumb-box {{ $hero ? '' : 'empty' }}">
            @if($hero)
                <img src="{{ media($hero) }}" alt="" id="heroPreview">
            @else
                <span>Görsel yok</span>
            @endif
        </div>
        <div>
            <div class="form-group">
                <label class="form-label">Görsel adresi (URL)</label>
                <input type="text" name="hero_gorsel" class="form-input" value="{{ $hero }}"
                       placeholder="{{ asset('img/demo/hero.jpg') }}"
                       oninput="var p=document.getElementById('heroPreview'); if(p) p.src=this.value">
                <div class="form-help">
                    Şu an <strong>yer tutucu</strong> bir görsel kullanılıyor. Müşterinin gerçek fotoğrafı
                    hazır olduğunda <em>Ürünler</em> ekranından yükleyip adresini buraya yapıştırabilirsiniz.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title"><i data-lucide="type"></i><span>Hero Başlık ve Metni</span></div>

    <div class="info-card">
        <i data-lucide="info" class="ic"></i>
        <div class="body">
            Başlıkta <strong>satır sonu</strong> kullanabilirsiniz — sitede de alt satıra iner.
            Boş bırakılan alanlar için dil dosyasındaki varsayılan metin kullanılır.
        </div>
    </div>

    @include('admin.ayarlar._partials.lang-tabs', [
        'group'    => 'hero',
        'settings' => $settings,
        'fields'   => [
            ['name' => 'hero_baslik', 'label' => 'Başlık', 'type' => 'textarea', 'rows' => 2],
            ['name' => 'hero_metin',  'label' => 'Metin',  'type' => 'textarea', 'rows' => 3],
        ],
    ])
</div>

<div class="section">
    <div class="section-title"><i data-lucide="bar-chart-3"></i><span>Sayı Şeridi</span></div>

    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Yıl deneyim</label>
            <input type="text" name="istatistik_yil" class="form-input" value="{{ $s('istatistik_yil') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Monte edilen pencere</label>
            <input type="text" name="istatistik_pencere" class="form-input" value="{{ $s('istatistik_pencere') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Mutlu müşteri</label>
            <input type="text" name="istatistik_musteri" class="form-input" value="{{ $s('istatistik_musteri') }}">
        </div>
        <div class="form-group mb-0">
            <label class="form-label">Hizmet yarıçapı (km)</label>
            <input type="text" name="istatistik_bolge" class="form-input" value="{{ $s('istatistik_bolge') }}">
        </div>
        <div class="form-group full mb-0">
            <div class="form-help" style="margin-top:0">
                Sayılar olduğu gibi basılır — &ldquo;12.000&rdquo; gibi noktalı yazabilirsiniz.
                Etiketler (Jahre Erfahrung / Yıl deneyim) dile göre otomatik gelir.
            </div>
        </div>
    </div>
</div>
@endsection
