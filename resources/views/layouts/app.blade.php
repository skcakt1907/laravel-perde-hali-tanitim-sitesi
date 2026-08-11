<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', setting('site_adi', 'MC Gordijnen'))</title>
    <meta name="description" content="@yield('meta', tsetting('site_aciklama'))">
    <link rel="canonical" href="{{ url()->current() }}">
    {{-- hreflang YOK: dil URL'de değil çerezde tutuluyor (müşteri isteği), yani her
         sayfanın tek adresi var. Var olmayan dil adreslerini hreflang ile bildirmek
         arama motoruna yanlış bilgi vermek olur. --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('img/logo-mark.png') }}">
    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ setting('site_adi') }}">
    <meta property="og:title" content="@yield('title', setting('site_adi'))">
    <meta property="og:description" content="@yield('meta', tsetting('site_aciklama'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('img/og-image.jpg'))">
    <meta property="og:locale" content="{{ \App\Support\Locales::ogLocale() }}">
    <meta name="twitter:card" content="summary_large_image">
{{-- Tüm varlıklar YEREL: dışarıya (Google Fonts / CDN) hiç istek gitmez.
         Böylece ziyaretçinin IP'si üçüncü taraflara aktarılmaz — çerez bandına gerek kalmaz. --}}
    <link href="{{ asset('css/fonts.css') }}?v={{ filemtime(public_path('css/fonts.css')) }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}" rel="stylesheet">
    <link href="{{ asset('css/catalog.css') }}?v={{ filemtime(public_path('css/catalog.css')) }}" rel="stylesheet">
</head>
<body>
@include('partials.header')

<main>
    @if(session('success'))
        <div class="container mt-3"><div class="alert alert-success">{{ session('success') }}</div></div>
    @endif
    @if(session('error'))
        <div class="container mt-3"><div class="alert alert-danger">{{ session('error') }}</div></div>
    @endif

    @yield('content')
</main>

@include('partials.footer')

@if($wa = setting('whatsapp'))
    <a href="https://wa.me/{{ preg_replace('/\D+/', '', $wa) }}" class="wa-float" target="_blank" rel="noopener"
       title="WhatsApp" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
@endif

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
    // navbar scroll efekti
    const nav = document.querySelector('.navbar');
    window.addEventListener('scroll', () => nav?.classList.toggle('scrolled', window.scrollY > 20));

    // ürün detayında küçük görsele tıklayınca ana görseli değiştir
    document.querySelectorAll('.pd-thumbs img').forEach(thumb => {
        thumb.addEventListener('click', () => {
            const main = document.querySelector('.pd-gallery img');
            if (!main) return;
            main.src = thumb.src;
            document.querySelectorAll('.pd-thumbs img').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        });
    });
</script>
@stack('scripts')
</body>
</html>
