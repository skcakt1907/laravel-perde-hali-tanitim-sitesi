<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <meta name="theme-color" content="#2563eb">
    <title>Yönetim Girişi — {{ setting('site_adi') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link href="{{ asset('css/fonts.css') }}?v={{ @filemtime(public_path('css/fonts.css')) ?: time() }}" rel="stylesheet">
    <link rel="stylesheet"
          href="{{ asset('css/admin-theme.css') }}?v={{ @filemtime(public_path('css/admin-theme.css')) ?: time() }}">
    <script src="{{ asset('vendor/lucide/lucide.min.js') }}" defer></script>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background:
                radial-gradient(900px 500px at 12% -10%, rgba(37, 99, 235, 0.14), transparent 60%),
                radial-gradient(700px 420px at 100% 100%, rgba(37, 99, 235, 0.10), transparent 60%),
                var(--bg);
        }
        .login-wrap { width: 100%; max-width: 412px; }
        .login-head { text-align: center; margin-bottom: 22px; }
        .login-head .mark {
            width: 62px; height: 62px;
            border-radius: 18px;
            overflow: hidden;
            margin: 0 auto 14px;
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.25);
            border: 1px solid var(--border);
            background: var(--surface);
        }
        .login-head .mark img { width: 100%; height: 100%; object-fit: cover; }
        .login-head h1 { font-size: 19px; margin-bottom: 4px; }
        .login-head p { font-size: 13px; color: var(--text-muted); }
        .login-card { padding: 26px; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); }
        .login-card .btn { width: 100%; }
        .login-foot { text-align: center; margin-top: 18px; font-size: 12.5px; }
        .login-foot a { color: var(--text-muted); display: inline-flex; align-items: center; gap: 6px; }
        .login-foot a:hover { color: var(--brand); }
    </style>
</head>
<body>

<div class="login-wrap">
    <div class="login-head">
        <div class="mark"><img src="{{ asset('img/logo-mark.png') }}" alt="{{ setting('site_adi') }}"></div>
        <h1>{{ setting('site_adi') }} · Yönetim</h1>
        <p>Panele girmek için hesap bilgilerinizi girin</p>
    </div>

    <div class="card login-card">
        @if($errors->any())
            <div class="alert alert-danger">
                <i data-lucide="alert-circle"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">E-posta</label>
                <input id="email" type="email" name="email" class="form-input" required
                       value="{{ old('email') }}" autocomplete="username" autofocus>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Şifre</label>
                <input id="password" type="password" name="password" class="form-input" required
                       autocomplete="current-password">
            </div>

            <label class="form-check" style="margin-bottom:18px">
                <input type="checkbox" name="remember" value="1">
                Beni hatırla
            </label>

            <button type="submit" class="btn btn-primary btn-lg">
                <i data-lucide="log-in"></i> Giriş Yap
            </button>
        </form>
    </div>

    <div class="login-foot">
        <a href="{{ route('home') }}"><i data-lucide="arrow-left"></i> Siteye dön</a>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', function () { if (window.lucide) window.lucide.createIcons(); });
    window.addEventListener('load', function () { if (window.lucide) window.lucide.createIcons(); });
</script>
</body>
</html>
