@extends('admin.ayarlar._base')

@section('fields')
@php $s = fn ($k, $d = '') => old($k, $settings[$k] ?? $d); @endphp

<div class="section">
    <div class="section-title"><i data-lucide="share-2"></i><span>Sosyal Medya Hesapları</span></div>

    <div class="info-card">
        <i data-lucide="info" class="ic"></i>
        <div class="body">
            Tam adres yazın (<code>https://…</code>). Boş bıraktığınız hesabın ikonu alt bilgide
            <strong>hiç görünmez</strong> — kırık bağlantı oluşmaz.
        </div>
    </div>

    <div class="form-grid">
        <div class="form-group full">
            <label class="form-label">
                <i data-lucide="instagram" style="width:14px;height:14px;display:inline;vertical-align:-0.15em"></i>
                Instagram
            </label>
            <input type="url" name="instagram" class="form-input" value="{{ $s('instagram') }}"
                   placeholder="https://www.instagram.com/mc_gordijnen/">
        </div>

        <div class="form-group full">
            <label class="form-label">
                <i data-lucide="facebook" style="width:14px;height:14px;display:inline;vertical-align:-0.15em"></i>
                Facebook
            </label>
            <input type="url" name="facebook" class="form-input" value="{{ $s('facebook') }}"
                   placeholder="https://www.facebook.com/…">
        </div>
    </div>
</div>
@endsection
