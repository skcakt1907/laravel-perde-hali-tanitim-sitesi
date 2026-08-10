{{-- ŞABLON METİN — müşteri/avukat onayı ile kesinleştirilmeli. --}}
<p class="alert-soft">
    <strong>Not:</strong> Bu bilgiler şablondur; yayına almadan önce gerçek firma verileriyle
    (KvK numarası, KDV/BTW numarası, yetkili kişi) tamamlanmalı ve hukuken kontrol edilmelidir.
</p>

<h2>Firma bilgileri</h2>
<table>
    <tr><td>Firma</td><td>{{ setting('firma_unvan', setting('site_adi')) }}</td></tr>
    <tr><td>Yetkili</td><td>{{ setting('yetkili', '—') }}</td></tr>
    <tr><td>Adres</td><td>{{ setting('adres', '—') }}</td></tr>
    <tr><td>Telefon</td><td>{{ setting('telefon', '—') }}</td></tr>
    <tr><td>E-posta</td><td>{{ setting('eposta', '—') }}</td></tr>
    <tr><td>Ticaret sicil (KvK)</td><td>{{ setting('kvk_no', '—') }}</td></tr>
    <tr><td>KDV no (BTW)</td><td>{{ setting('btw_no', '—') }}</td></tr>
</table>

<h2>İçerik sorumlusu</h2>
<p>{{ setting('yetkili', setting('site_adi')) }} — adres yukarıdaki gibidir.</p>

<h2>İçerik sorumluluğu</h2>
<p>
    Bu sayfalardaki içerikler büyük bir özenle hazırlanmıştır. Yine de içeriğin doğruluğu,
    eksiksizliği ve güncelliği konusunda garanti veremeyiz. Tüm fiyatlar başlangıç
    (&ldquo;itibaren&rdquo;) fiyatlarıdır ve bağlayıcı teklif niteliği taşımaz; bağlayıcı
    fiyat, ölçü alındıktan sonra verilen kişiye özel teklifle belirlenir.
</p>

<h2>Bağlantı sorumluluğu</h2>
<p>
    Sitemiz, içeriği üzerinde etkimiz olmayan üçüncü taraf sitelere bağlantılar içerir.
    Bağlantı verilen sayfaların içeriğinden her zaman ilgili sağlayıcı sorumludur.
</p>

<h2>Telif hakkı</h2>
<p>
    Bu web sitesinde yayınlanan içerik, metin ve görseller telif hakkı ile korunmaktadır.
    Telif hakkı sınırları dışındaki çoğaltma veya kullanım yazılı izin gerektirir.
</p>
