{{--
═══════════════════════════════════════════════════════════
HATA SAYFASI İSKELETİ
Dil, DetectLocale global middleware'inde yönlendirmeden ÖNCE ayarlanır —
404 sayfası da ziyaretçinin dilinde gelir.
Dili burada @php ile ayarlamak YETMEZ: alt şablonun
@section('title', __('…')) ifadeleri bu dosyadan önce değerlendirilir.
═══════════════════════════════════════════════════════════
--}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>{{ $code }} — {{ setting('site_adi', 'MC Gordijnen') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}?v={{ @filemtime(public_path('css/style.css')) ?: time() }}" rel="stylesheet">
    <style>
        body { background: var(--dark); color: #fff; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1.25rem; }
        .err-wrap { max-width: 620px; width: 100%; text-align: center; }
        .err-wrap img { max-height: 72px; margin: 0 auto 2.2rem; }
        .err-code { font-family: 'Playfair Display', Georgia, serif; font-size: clamp(4rem, 14vw, 7rem); line-height: 1; color: var(--gold-on-dark); margin-bottom: .6rem; }
        .err-wrap h1 { color: #fff; font-size: clamp(1.4rem, 4vw, 2rem); margin-bottom: .9rem; }
        .err-wrap p { color: rgba(255, 255, 255, .72); font-size: 1.02rem; margin-bottom: 2rem; }
        .err-actions { display: flex; gap: .7rem; justify-content: center; flex-wrap: wrap; }
        .err-links { margin-top: 2.4rem; padding-top: 1.6rem; border-top: 1px solid rgba(255, 255, 255, .12); display: flex; gap: 6px 20px; justify-content: center; flex-wrap: wrap; font-size: .9rem; }
        .err-links a { color: rgba(255, 255, 255, .68); }
        .err-links a:hover { color: var(--gold-on-dark); }
    </style>
</head>
<body>
<div class="err-wrap">
    <a href="{{ route('home') }}"><img src="{{ asset('img/logo-light.png') }}" alt="{{ setting('site_adi') }}"></a>

    <div class="err-code">{{ $code }}</div>
    <h1>@yield('title')</h1>
    <p>@yield('message')</p>

    <div class="err-actions">
        <a href="{{ route('home') }}" class="btn-orange">
            <i class="bi bi-house-door"></i> {{ __('site.errors.home') }}
        </a>
        @if($tel = setting('telefon'))
            <a href="tel:{{ preg_replace('/[^\d+]/', '', $tel) }}" class="btn-ghost-light">
                <i class="bi bi-telephone"></i> {{ $tel }}
            </a>
        @endif
    </div>

    <div class="err-links">
        <a href="{{ route('catalog') }}">{{ __('site.nav.products') }}</a>
        <a href="{{ route('services') }}">{{ __('site.nav.services') }}</a>
        <a href="{{ route('gallery') }}">{{ __('site.nav.gallery') }}</a>
        <a href="{{ route('aufmass') }}">{{ __('site.nav.aufmass') }}</a>
        <a href="{{ route('contact') }}">{{ __('site.nav.contact') }}</a>
    </div>
</div>
</body>
</html>
