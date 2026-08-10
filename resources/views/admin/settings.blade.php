@extends('admin.layout')
@section('title', 'Ayarlar')

@section('content')
@php $s = fn ($k, $d = '') => old($k, $settings[$k] ?? $d); @endphp
<form action="{{ route('admin.settings.update') }}" method="POST" class="form-a">
    @csrf
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card-a">
                <h3 style="font-size:1rem;margin-top:0">Site bilgileri</h3>
                <label>Site adı</label><input name="site_adi" value="{{ $s('site_adi') }}">

                <div class="lang-box">
                    <span class="lang-tag">DE — Almanca</span>
                    <label>Site açıklaması (meta description)</label>
                    <textarea name="site_aciklama" rows="3">{{ $s('site_aciklama') }}</textarea>
                </div>
                <div class="lang-box tr">
                    <span class="lang-tag">TR — Türkçe</span>
                    <label>Site açıklaması</label>
                    <textarea name="site_aciklama_tr" rows="3">{{ $s('site_aciklama_tr') }}</textarea>
                </div>
            </div>

            <div class="card-a mt-4">
                <h3 style="font-size:1rem;margin-top:0">İletişim</h3>
                <label>Telefon</label><input name="telefon" value="{{ $s('telefon') }}" placeholder="+31 6 84 10 46 48">
                <label>WhatsApp (ülke kodu ile, sadece rakam)</label><input name="whatsapp" value="{{ $s('whatsapp') }}" placeholder="31684104648">
                <label>E-posta</label><input name="eposta" value="{{ $s('eposta') }}">
                <label>Adres</label><textarea name="adres" rows="2">{{ $s('adres') }}</textarea>
                <label>Instagram</label><input name="instagram" value="{{ $s('instagram') }}">
                <label>Facebook</label><input name="facebook" value="{{ $s('facebook') }}">

                <div class="lang-box">
                    <span class="lang-tag">DE — Almanca</span>
                    <label>Çalışma saatleri</label>
                    <textarea name="calisma_saatleri" rows="2">{{ $s('calisma_saatleri') }}</textarea>
                </div>
                <div class="lang-box tr">
                    <span class="lang-tag">TR — Türkçe</span>
                    <label>Çalışma saatleri</label>
                    <textarea name="calisma_saatleri_tr" rows="2">{{ $s('calisma_saatleri_tr') }}</textarea>
                </div>

                <label>Google Maps embed kodu</label>
                <textarea name="harita_embed" rows="3" placeholder="&lt;iframe src=&quot;https://www.google.com/maps/embed?...&quot; ...&gt;&lt;/iframe&gt;">{{ $s('harita_embed') }}</textarea>
                <div class="hint">Yalnızca Google Maps'ten aldığınız iframe kodunu yapıştırın; bu alan HTML olarak sayfaya basılır.</div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-a">
                <h3 style="font-size:1rem;margin-top:0">Anasayfa üst bölüm (hero)</h3>
                <label>Hero görseli (URL)</label>
                <input name="hero_gorsel" value="{{ $s('hero_gorsel') }}" placeholder="{{ asset('img/demo/hero.jpg') }}">

                <div class="lang-box">
                    <span class="lang-tag">DE — Almanca</span>
                    <label>Başlık</label><textarea name="hero_baslik" rows="2">{{ $s('hero_baslik') }}</textarea>
                    <label>Metin</label><textarea name="hero_metin" rows="3">{{ $s('hero_metin') }}</textarea>
                </div>
                <div class="lang-box tr">
                    <span class="lang-tag">TR — Türkçe</span>
                    <label>Başlık</label><textarea name="hero_baslik_tr" rows="2">{{ $s('hero_baslik_tr') }}</textarea>
                    <label>Metin</label><textarea name="hero_metin_tr" rows="3">{{ $s('hero_metin_tr') }}</textarea>
                </div>
            </div>

            <div class="card-a mt-4">
                <h3 style="font-size:1rem;margin-top:0">Sayılar (anasayfa şeridi)</h3>
                <div class="row">
                    <div class="col-6"><label>Yıl deneyim</label><input name="istatistik_yil" value="{{ $s('istatistik_yil') }}"></div>
                    <div class="col-6"><label>Monte edilen pencere</label><input name="istatistik_pencere" value="{{ $s('istatistik_pencere') }}"></div>
                    <div class="col-6"><label>Mutlu müşteri</label><input name="istatistik_musteri" value="{{ $s('istatistik_musteri') }}"></div>
                    <div class="col-6"><label>Hizmet yarıçapı (km)</label><input name="istatistik_bolge" value="{{ $s('istatistik_bolge') }}"></div>
                </div>
            </div>

            <div class="card-a mt-4">
                <h3 style="font-size:1rem;margin-top:0">Hakkımızda sayfası</h3>
                <label>Görsel (URL)</label>
                <input name="hakkimizda_gorsel" value="{{ $s('hakkimizda_gorsel') }}">

                <div class="lang-box">
                    <span class="lang-tag">DE — Almanca</span>
                    <label>Başlık</label><input name="hakkimizda_baslik" value="{{ $s('hakkimizda_baslik') }}">
                    <label>Metin</label><textarea name="hakkimizda_metin" rows="5">{{ $s('hakkimizda_metin') }}</textarea>
                    <label>Maddeler (her satır bir madde)</label>
                    <textarea name="hakkimizda_maddeler" rows="4">{{ $s('hakkimizda_maddeler') }}</textarea>
                </div>
                <div class="lang-box tr">
                    <span class="lang-tag">TR — Türkçe</span>
                    <label>Başlık</label><input name="hakkimizda_baslik_tr" value="{{ $s('hakkimizda_baslik_tr') }}">
                    <label>Metin</label><textarea name="hakkimizda_metin_tr" rows="5">{{ $s('hakkimizda_metin_tr') }}</textarea>
                    <label>Maddeler</label>
                    <textarea name="hakkimizda_maddeler_tr" rows="4">{{ $s('hakkimizda_maddeler_tr') }}</textarea>
                </div>
            </div>

            <div class="card-a mt-4">
                <h3 style="font-size:1rem;margin-top:0">Yasal / künye bilgileri</h3>
                <label>Resmî firma ünvanı</label><input name="firma_unvan" value="{{ $s('firma_unvan') }}">
                <label>Yetkili kişi</label><input name="yetkili" value="{{ $s('yetkili') }}">
                <label>KvK (ticaret sicil) no</label><input name="kvk_no" value="{{ $s('kvk_no') }}">
                <label>BTW (KDV) no</label><input name="btw_no" value="{{ $s('btw_no') }}">
                <div class="hint">Bu alanlar Impressum ve yasal sayfalarda otomatik görünür.</div>
            </div>
        </div>
    </div>

    <div class="mt-4"><button class="btn-a"><i class="bi bi-check-lg"></i> Tüm ayarları kaydet</button></div>
</form>
@endsection
