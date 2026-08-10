<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>Yönetim Girişi — {{ setting('site_adi') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body{font-family:'Inter',Arial,sans-serif;background:#141414;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;margin:0;padding:2rem 1rem}
        .login-box{width:100%;max-width:420px;background:#1e1c19;border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:2.4rem;border-bottom:3px solid #c9a227}
        .login-box img{max-height:64px;display:block;margin:0 auto 1.6rem}
        .login-box h1{font-size:1.15rem;text-align:center;margin-bottom:1.8rem;color:rgba(255,255,255,.72);font-weight:600}
        .form-label{font-size:.85rem;font-weight:600;color:rgba(255,255,255,.8)}
        .form-control{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);color:#fff;padding:.8rem 1rem;border-radius:6px}
        .form-control:focus{background:rgba(255,255,255,.1);border-color:#c9a227;color:#fff;box-shadow:0 0 0 .2rem rgba(201,162,39,.2)}
        .btn-gold{background:#c9a227;color:#141414;border:none;border-radius:6px;padding:.85rem;font-weight:700;width:100%}
        .btn-gold:hover{background:#e0bf4f;color:#141414}
        .form-check-label{font-size:.88rem;color:rgba(255,255,255,.7)}
        .back{display:block;text-align:center;margin-top:1.4rem;font-size:.86rem;color:rgba(255,255,255,.5);text-decoration:none}
        .back:hover{color:#e0bf4f}
    </style>
</head>
<body>
<div class="login-box">
    <img src="{{ asset('img/logo-light.png') }}" alt="{{ setting('site_adi') }}">
    <h1>Yönetim Paneli Girişi</h1>

    @if($errors->any())
        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="email">E-posta</label>
            <input id="email" type="email" name="email" class="form-control" required value="{{ old('email') }}" autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Şifre</label>
            <input id="password" type="password" name="password" class="form-control" required>
        </div>
        <div class="form-check mb-4">
            <input type="checkbox" name="remember" class="form-check-input" id="rmb">
            <label class="form-check-label" for="rmb">Beni hatırla</label>
        </div>
        <button type="submit" class="btn-gold"><i class="bi bi-box-arrow-in-right"></i> Giriş Yap</button>
    </form>

    <a class="back" href="{{ route('home') }}"><i class="bi bi-arrow-left"></i> Siteye dön</a>
</div>
</body>
</html>
