{{-- ŞABLON METİN — GDPR/AVG uyumu için avukat onayı gerek. --}}
<p class="alert-soft">
    <strong>Not:</strong> Şablon metindir. Yayına almadan önce fiilen kullanılan servislere
    (barındırma, analiz, yazı tipleri, harita) göre uyarlanmalı ve hukuken kontrol edilmelidir.
</p>

<h2>1. Veri sorumlusu</h2>
<p>
    Bu web sitesindeki veri işlemeden sorumlu taraf
    {{ setting('firma_unvan', setting('site_adi')) }}, {{ setting('adres') }},
    e-posta: {{ setting('eposta') }}, telefon: {{ setting('telefon') }}.
</p>

<h2>2. Hangi verileri işliyoruz</h2>
<ul>
    <li>
        <strong>İletişim formu:</strong> Ad, telefon, e-posta, konu ve mesaj metni —
        talebinizi yanıtlamak için.
    </li>
    <li>
        <strong>&ldquo;Ücretsiz ölçü&rdquo; talebi:</strong> Ek olarak posta kodu, şehir,
        adres ve tercih edilen tarih — ölçü randevusunu planlamak ve gerçekleştirmek için.
    </li>
    <li>
        <strong>Sunucu kayıtları:</strong> IP adresi, erişim zamanı, görüntülenen sayfa,
        tarayıcı türü — işletim ve güvenlik için teknik olarak gereklidir.
    </li>
</ul>

<h2>3. Hukuki dayanak</h2>
<p>
    Form verilerinizin işlenmesi sözleşmenin kurulması ve ifası (GDPR md. 6/1-b) ya da
    açık rızanız (GDPR md. 6/1-a) kapsamındadır. Sunucu kayıtlarının işlenmesi, sitenin
    güvenli çalışmasına ilişkin meşru menfaatimize dayanır (GDPR md. 6/1-f).
</p>

<h2>4. Saklama süresi</h2>
<p>
    Verilerinizi yalnızca talebinizin işlenmesi için gerekli olduğu sürece ve yasal saklama
    süreleri çerçevesinde saklıyoruz. Siparişe dönüşmeyen talepleri en geç on iki ay sonra
    siliyoruz.
</p>

<h2>5. Üçüncü taraflara aktarım</h2>
<p>
    Verileriniz yalnızca sözleşmenin ifası için gerekli olduğu ölçüde (örn. ölçüye özel
    üretim için üreticiye ya da montaj ekibine) veya yasal bir yükümlülük bulunduğunda
    aktarılır. Verilerinizin satışı yapılmaz.
</p>

<h2>6. Çerezler ve dış servisler</h2>
<p>
    Bu site teknik olarak gerekli çerezleri kullanır (oturum ve formların kötüye kullanıma
    karşı korunması için). Yazı tipleri ve tasarım öğeleri dış sağlayıcılardan (Google Fonts,
    jsDelivr CDN) yüklenir; bu sırada IP adresiniz ilgili sağlayıcıya iletilir. Ayrıntılar
    <a href="{{ route('legal', 'cookies') }}">Çerez Bilgilendirmesi</a> sayfasındadır.
</p>

<h2>7. Haklarınız</h2>
<p>
    Bilgi talep etme, düzeltme, silme, işlemenin kısıtlanması, veri taşınabilirliği ve itiraz
    haklarına sahipsiniz. Verdiğiniz rızayı her zaman geri alabilirsiniz. Bunun için
    {{ setting('eposta') }} adresine yazabilirsiniz.
</p>

<h2>8. Şikâyet hakkı</h2>
<p>
    Bir veri koruma denetim kurumuna şikâyette bulunabilirsiniz — Hollanda'da Autoriteit
    Persoonsgegevens, Almanya'da ikametinize bağlı eyalet veri koruma kurumu.
</p>
