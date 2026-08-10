@extends('admin.ayarlar._base')

@section('fields')
@php $s = fn ($k, $d = '') => old($k, $settings[$k] ?? $d); @endphp

<div class="section">
    <div class="section-title"><i data-lucide="phone"></i><span>İletişim Bilgileri</span></div>

    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Telefon</label>
            <input type="text" name="telefon" class="form-input" value="{{ $s('telefon') }}"
                   placeholder="+31 6 84 10 46 48">
            <div class="form-help">Üst şeritte, hero'da ve tüm formların yanında görünür.</div>
        </div>

        <div class="form-group">
            <label class="form-label">WhatsApp numarası</label>
            <input type="text" name="whatsapp" class="form-input" value="{{ $s('whatsapp') }}"
                   placeholder="31684104648">
            <div class="form-help">Ülke kodu ile, <strong>sadece rakam</strong>. Boş bırakılırsa yüzen WhatsApp düğmesi görünmez.</div>
        </div>

        <div class="form-group full">
            <label class="form-label">E-posta</label>
            <input type="email" name="eposta" class="form-input" value="{{ $s('eposta') }}">
            <div class="form-help">Form bildirimleri bu adrese gönderilir.</div>
        </div>

        <div class="form-group full mb-0">
            <label class="form-label">Adres</label>
            <textarea name="adres" rows="2" class="form-textarea">{{ $s('adres') }}</textarea>
            <div class="form-help">Boş bırakılırsa adres blokları sitede hiç gösterilmez (kırık görünmez).</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title"><i data-lucide="clock"></i><span>Çalışma Saatleri</span></div>

    @include('admin.ayarlar._partials.lang-tabs', [
        'group'    => 'saat',
        'settings' => $settings,
        'fields'   => [
            ['name' => 'calisma_saatleri', 'label' => 'Çalışma saatleri', 'type' => 'textarea', 'rows' => 3,
             'placeholder' => "Mo–Fr 09:00–18:00\nSa 10:00–16:00 (nach Absprache)",
             'placeholder_en' => "Mon–Fri 09:00–18:00\nSat 10:00–16:00 (by appointment)",
             'placeholder_tr' => "Pzt–Cum 09:00–18:00\nCmt 10:00–16:00 (randevu ile)",
             'help' => 'Her satır alt alta gösterilir.'],
        ],
    ])
</div>

<div class="section">
    <div class="section-title"><i data-lucide="map-pin"></i><span>Harita</span></div>

    <div class="info-card is-warning">
        <i data-lucide="alert-triangle" class="ic"></i>
        <div class="body">
            Bu alan sayfaya <strong>HTML olarak</strong> basılır. Yalnızca Google Maps'ten
            &ldquo;Paylaş → Harita yerleştir&rdquo; ile aldığınız <code>&lt;iframe&gt;</code> kodunu yapıştırın.
        </div>
    </div>

    <div class="form-group mb-0">
        <label class="form-label">Google Maps embed kodu</label>
        <textarea name="harita_embed" rows="4" class="form-textarea"
                  placeholder="&lt;iframe src=&quot;https://www.google.com/maps/embed?…&quot; …&gt;&lt;/iframe&gt;">{{ $s('harita_embed') }}</textarea>
        <div class="form-help">Boş bırakılırsa iletişim sayfasında harita bölümü çıkmaz.</div>
    </div>
</div>
@endsection
