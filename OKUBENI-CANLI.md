# Canlıya Alma ve Güvenlik Kontrol Listesi

Sırayla uygula. **Kalın** yazılanlar atlanırsa güvenlik açığı oluşur.

---

## 1. Dosyaları yükle

Tüm proje köküyle birlikte yüklenir (`app/`, `bootstrap/`, `config/`, `database/`,
`lang/`, `public/`, `resources/`, `routes/`, `storage/`, `vendor/`).

`node_modules/`, `tests/`, `.git/`, `test-results/` yüklenmesi gerekmez.

### Alan adı hangi klasöre baksın?
- **Tercih edilen:** docroot → `public/`. En güvenli, hiçbir uygulama dosyası web'den görünmez.
- **Yapılamıyorsa:** docroot proje kökü kalır; kökteki `.htaccess` istekleri `public/`e taşır
  ve `app/`, `config/`, `storage/`, `vendor/`, `.env` gibi yolları **reddeder**.
  Bu yedek çözümdür; mümkünse ilkini kullan.

---

## 2. `.env` hazırla

```bash
cp .env.canli.ornek .env
# <> içindeki yerleri doldur (alan adı, veritabanı, SMTP)
php artisan key:generate      # komut satırı varsa; yoksa şablondaki hazır APP_KEY kullanılır
```

**Kontrol et:**
- [ ] `APP_ENV=production`
- [ ] **`APP_DEBUG=false`** — `true` kalırsa hata ekranında kod yolları, SQL ve `.env` değerleri görünür
- [ ] `APP_URL` https ile, sondaki eğik çizgi olmadan
- [ ] `APP_KEY` dolu
- [ ] **`SESSION_SECURE_COOKIE=true`** (SSL kurulduktan sonra)
- [ ] `MAIL_MAILER=smtp` — `log` kalırsa **hiçbir e-posta gitmez**, ölçü talepleri kaybolur

---

## 3. Veritabanı

```bash
php artisan migrate --force
php artisan db:seed --force      # SADECE ilk kurulumda
```

`migrate:fresh` **çalıştırma** — tüm veriyi siler.

---

## 4. İzinler

```bash
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/uploads
```

`.env` dosyası mümkünse `600`.

---

## 5. Önbellek

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Sonradan `.env` ya da rota değiştirirsen `php artisan optimize:clear` çalıştırıp tekrar cache'le.

---

## 6. HTTPS

SSL sertifikası kurulduktan sonra `public/.htaccess` içindeki HTTPS yönlendirme
bloğunun **başındaki `#` işaretlerini kaldır** (dosyada `HTTPS zorlaması` başlığı altında).
SSL yokken açarsan site açılmaz, o yüzden kapalı geliyor.

---

## 7. **Yönetici şifresini değiştir**

Kurulumdan gelen şifre `admin123` — herkese açık depoda ve dokümanda yazıyor.

1. `/giris` → **admin@ornek-perde.nl / admin123**
2. **Profil** ekranından e-postayı müşterinin gerçek adresine çevir
3. Şifreyi değiştir (en az 10 karakter, harf + rakam zorunlu)

---

## 8. Panelden doldurulacaklar

- **Ayarlar → Künye/Yasal:** firma ünvanı, yetkili, KvK, BTW → boş kalırsa Impressum eksik olur
- **Ayarlar → İletişim:** adres, telefon, e-posta, çalışma saatleri
- **Müşteri Yorumları:** kurulumla gelen **3 yorum örnek metindir**, gerçek yorumlarla değiştir
- **Dört dil:** her içerik kaydında DE / NL / EN / TR kutusu var. Boş bırakılan alan sitede
  **Almanca** görünür — yani eksik çeviri siteyi kırmaz, sadece o satır Almanca kalır.
  Sitenin varsayılan dili Almanca (`/` → `/de`); Hollandaca açılması istenirse söyle, tek satır
- Görseller: yer tutucu dokular yerine müşterinin fotoğrafları

---

## Uygulanmış güvenlik önlemleri (bilgi)

Bunlar kodda hazır, ek işlem gerekmez:

| Önlem | Nerede |
|---|---|
| CSP, nosniff, X-Frame-Options, Referrer-Policy, Permissions-Policy, HSTS | `SecurityHeaders` middleware (sunucudan bağımsız) |
| Kök dizinde `.env`, `storage`, `vendor`, gizli dosya erişimi reddi | kök `.htaccess` |
| Dizin listeleme kapalı, hassas uzantılar reddi | `public/.htaccess` |
| Yüklenen dosyada PHP çalıştırma engeli (çoklu handler) | `public/uploads/.htaccess` |
| Yükleme uzantısı istemciden DEĞİL dosya içeriğinden türetilir + mime beyaz listesi | `HandlesUploads` |
| Girişte kaba kuvvet freni: e-posta+IP başına 5 hatalı deneme → 1 dk kilit | `AuthController` |
| Girişte hesap varlığını sızdırmayan tek tip hata mesajı | `AuthController` |
| Oturum sabitleme önlemi (`session()->regenerate()`) | `AuthController` |
| Formlarda CSRF + honeypot + IP başına dakikada 5 gönderim | `routes/web.php`, `PageController` |
| Mass-assignment koruması (`role`, `status`, `read_at` yazılamaz) | modellerde `$fillable` |
| Ayarlarda bölüm bazlı beyaz liste (rastgele anahtar yazılamaz) | `SettingController::pages()` |
| Panel `noindex,nofollow` + robots.txt'de `Disallow` | admin layout, robots rotası |
| Dışarıya sıfır istek (font/Bootstrap/ikon yerel) | `public/fonts`, `public/vendor` |
| Şifre kuralı: en az 10 karakter, harf + rakam | `Admin\ProfileController` |

### Sunucu yapılandırmasında yapılması gerekenler (.htaccess'ten yapılamaz)
- **`ServerTokens Prod`** — yanıtlarda `Server: Apache/2.4.65 … PHP/8.3.28` yazıyor,
  yani sürüm numaraları görünüyor. Apache ana yapılandırmasında kısılır; paylaşımlı
  hostingde hosting firmasından istenir. Kritik değil ama bilinen sürüme yönelik
  taramaları kolaylaştırır.
- **`expose_php = Off`** — `X-Powered-By` kod tarafında kaldırıldı, bu ayar ikinci hattır.

### Bilinçli olarak yapılmayanlar
- **Çerez onay bandı yok** — üçüncü taraf içerik ve izleme çerezi olmadığı için gerekmiyor.
  Analytics veya Meta Pixel eklenirse **banner zorunlu hâle gelir** ve yasal metinler güncellenmelidir.
- **CSP'de `unsafe-inline` var** — sitede satır içi stil/script kullanılıyor. Dış kaynak
  yükleme yine engelli; tam katılık için satır içi kodların nonce'a taşınması gerekir.
- `MAIL_MAILER=log` geliştirmede bilinçli — canlıda `smtp` yapılmalı.
