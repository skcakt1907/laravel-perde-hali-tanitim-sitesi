@extends('admin.layout')
@section('title', 'Ayarlar')

@section('content')
@php $s = fn ($k, $d = '') => old($k, $settings[$k] ?? $d); @endphp

<div class="page-header">
    <div>
        <h1 class="page-title">Ayarlar</h1>
        <div class="page-subtitle">Site bilgileri, iletişim, anasayfa metinleri ve künye verileri</div>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf

    <div class="grid-2">
        <div>
            <div class="card mb-4">
                <div class="section-title"><i data-lucide="globe"></i> Site bilgileri</div>

                <div class="form-group">
                    <label class="form-label">Site adı</label>
                    <input name="site_adi" class="form-input" value="{{ $s('site_adi') }}">
                </div>

                <div class="lang-box">
                    <span class="lang-tag">DE — Almanca</span>
                    <div class="form-group mb-0">
                        <label class="form-label">Site açıklaması (meta description)</label>
                        <textarea name="site_aciklama" class="form-textarea" rows="3">{{ $s('site_aciklama') }}</textarea>
                    </div>
                </div>
                <div class="lang-box tr" style="margin-bottom:0">
                    <span class="lang-tag">TR — Türkçe</span>
                    <div class="form-group mb-0">
                        <label class="form-label">Site açıklaması</label>
                        <textarea name="site_aciklama_tr" class="form-textarea" rows="3">{{ $s('site_aciklama_tr') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="section-title"><i data-lucide="phone"></i> İletişim</div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Telefon</label>
                        <input name="telefon" class="form-input" value="{{ $s('telefon') }}" placeholder="+31 6 84 10 46 48">
                    </div>
                    <div class="form-group">
                        <label class="form-label">WhatsApp (sadece rakam)</label>
                        <input name="whatsapp" class="form-input" value="{{ $s('whatsapp') }}" placeholder="31684104648">
                    </div>
                    <div class="form-group full">
                        <label class="form-label">E-posta</label>
                        <input name="eposta" class="form-input" value="{{ $s('eposta') }}">
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Adres</label>
                        <textarea name="adres" class="form-textarea" rows="2">{{ $s('adres') }}</textarea>
                        <div class="form-help">Boş bırakılırsa adres blokları sitede hiç gösterilmez.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Instagram</label>
                        <input name="instagram" class="form-input" value="{{ $s('instagram') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Facebook</label>
                        <input name="facebook" class="form-input" value="{{ $s('facebook') }}">
                    </div>
                </div>

                <div class="lang-box">
                    <span class="lang-tag">DE — Almanca</span>
                    <div class="form-group mb-0">
                        <label class="form-label">Çalışma saatleri</label>
                        <textarea name="calisma_saatleri" class="form-textarea" rows="2">{{ $s('calisma_saatleri') }}</textarea>
                    </div>
                </div>
                <div class="lang-box tr">
                    <span class="lang-tag">TR — Türkçe</span>
                    <div class="form-group mb-0">
                        <label class="form-label">Çalışma saatleri</label>
                        <textarea name="calisma_saatleri_tr" class="form-textarea" rows="2">{{ $s('calisma_saatleri_tr') }}</textarea>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Google Maps embed kodu</label>
                    <textarea name="harita_embed" class="form-textarea" rows="3"
                              placeholder="&lt;iframe src=&quot;https://www.google.com/maps/embed?…&quot;&gt;&lt;/iframe&gt;">{{ $s('harita_embed') }}</textarea>
                    <div class="form-help">
                        Yalnızca Google Maps'ten aldığınız iframe kodunu yapıştırın — bu alan sayfaya HTML olarak basılır.
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card mb-4">
                <div class="section-title"><i data-lucide="panel-top"></i> Anasayfa üst bölüm (hero)</div>

                <div class="form-group">
                    <label class="form-label">Hero görseli (URL)</label>
                    <input name="hero_gorsel" class="form-input" value="{{ $s('hero_gorsel') }}"
                           placeholder="{{ asset('img/demo/hero.jpg') }}">
                </div>

                <div class="lang-box">
                    <span class="lang-tag">DE — Almanca</span>
                    <div class="form-group">
                        <label class="form-label">Başlık</label>
                        <textarea name="hero_baslik" class="form-textarea" rows="2">{{ $s('hero_baslik') }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Metin</label>
                        <textarea name="hero_metin" class="form-textarea" rows="3">{{ $s('hero_metin') }}</textarea>
                    </div>
                </div>
                <div class="lang-box tr" style="margin-bottom:0">
                    <span class="lang-tag">TR — Türkçe</span>
                    <div class="form-group">
                        <label class="form-label">Başlık</label>
                        <textarea name="hero_baslik_tr" class="form-textarea" rows="2">{{ $s('hero_baslik_tr') }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Metin</label>
                        <textarea name="hero_metin_tr" class="form-textarea" rows="3">{{ $s('hero_metin_tr') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="section-title"><i data-lucide="bar-chart-3"></i> Sayılar (anasayfa şeridi)</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Yıl deneyim</label>
                        <input name="istatistik_yil" class="form-input" value="{{ $s('istatistik_yil') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Monte edilen pencere</label>
                        <input name="istatistik_pencere" class="form-input" value="{{ $s('istatistik_pencere') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mutlu müşteri</label>
                        <input name="istatistik_musteri" class="form-input" value="{{ $s('istatistik_musteri') }}">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Hizmet yarıçapı (km)</label>
                        <input name="istatistik_bolge" class="form-input" value="{{ $s('istatistik_bolge') }}">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="section-title"><i data-lucide="users"></i> Hakkımızda sayfası</div>

                <div class="form-group">
                    <label class="form-label">Görsel (URL)</label>
                    <input name="hakkimizda_gorsel" class="form-input" value="{{ $s('hakkimizda_gorsel') }}">
                </div>

                <div class="lang-box">
                    <span class="lang-tag">DE — Almanca</span>
                    <div class="form-group">
                        <label class="form-label">Başlık</label>
                        <input name="hakkimizda_baslik" class="form-input" value="{{ $s('hakkimizda_baslik') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Metin</label>
                        <textarea name="hakkimizda_metin" class="form-textarea" rows="5">{{ $s('hakkimizda_metin') }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Maddeler (her satır bir madde)</label>
                        <textarea name="hakkimizda_maddeler" class="form-textarea" rows="4">{{ $s('hakkimizda_maddeler') }}</textarea>
                    </div>
                </div>
                <div class="lang-box tr" style="margin-bottom:0">
                    <span class="lang-tag">TR — Türkçe</span>
                    <div class="form-group">
                        <label class="form-label">Başlık</label>
                        <input name="hakkimizda_baslik_tr" class="form-input" value="{{ $s('hakkimizda_baslik_tr') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Metin</label>
                        <textarea name="hakkimizda_metin_tr" class="form-textarea" rows="5">{{ $s('hakkimizda_metin_tr') }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Maddeler</label>
                        <textarea name="hakkimizda_maddeler_tr" class="form-textarea" rows="4">{{ $s('hakkimizda_maddeler_tr') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="section-title"><i data-lucide="scale"></i> Yasal / künye bilgileri</div>
                <div class="alert alert-warning" style="margin-bottom:14px">
                    <i data-lucide="alert-triangle"></i>
                    <div>Bu alanlar Impressum ve yasal sayfalarda otomatik görünür. Boş kaldıkça o satırlar &ldquo;—&rdquo; gösterir.</div>
                </div>
                <div class="form-grid">
                    <div class="form-group full">
                        <label class="form-label">Resmî firma ünvanı</label>
                        <input name="firma_unvan" class="form-input" value="{{ $s('firma_unvan') }}">
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Yetkili kişi</label>
                        <input name="yetkili" class="form-input" value="{{ $s('yetkili') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">KvK (ticaret sicil) no</label>
                        <input name="kvk_no" class="form-input" value="{{ $s('kvk_no') }}">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">BTW (KDV) no</label>
                        <input name="btw_no" class="form-input" value="{{ $s('btw_no') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions-sticky">
        <span class="text-muted" style="font-size:12.5px;align-self:center">Değişiklikler kaydedildiğinde site anında güncellenir.</span>
        <button type="submit" class="btn btn-primary"><i data-lucide="check"></i> Tüm ayarları kaydet</button>
    </div>
</form>
@endsection
