# Perde ve Hali Tanitim Sitesi

Hollanda merkezli bir perde/hali firmasi icin dort dilli tanitim sitesi.

## Ozellikler

- Dort dilli icerik (NL / DE / EN / TR) ve dile gore ayri URL yapisi
- Urun ve hizmet katalogu, galeri
- Iletisim ve teklif formlari
- Yonetim paneli ile icerik ve ayar yonetimi

## Kullanilan teknolojiler

Laravel 13 - PHP 8.3 - MySQL - Blade

## Bu depo hakkinda

Gercek bir musteri projesinin **portfolyo icin yayinlanmis** surumudur.
Yayina hazirlanirken canli alan adlari, gercek iletisim bilgileri, musteri
kayitlari ve uygulama anahtarlari ornek degerlerle degistirilmistir.
Kod ve mimari oldugu gibidir; veri gercek degildir.

## Kurulum

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
