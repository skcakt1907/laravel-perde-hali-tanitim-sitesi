{{--
═══════════════════════════════════════════════════════════
MC GORDIJNEN — YÖNETİM PANELİ ANA ŞABLONU
Yapı: İş Ortağım panel mimarisi · Palet: mavi/beyaz
═══════════════════════════════════════════════════════════
--}}
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex,nofollow">
    <meta name="theme-color" content="#2563eb">
    <title>@yield('title', 'Yönetim') — {{ setting('site_adi') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('img/logo-mark.png') }}">

    <link rel="stylesheet"
          href="{{ asset('css/admin-theme.css') }}?v={{ @filemtime(public_path('css/admin-theme.css')) ?: time() }}">

    {{-- Bootstrap Icons: ürün/kategori ikon seçicileri bu setten seçiyor --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Lucide (yerel — CDN engellemelerine takılmasın) --}}
    <script src="{{ asset('vendor/lucide/lucide.min.js') }}" defer></script>

    @stack('head')
</head>
<body class="{{ request()->cookie('admin_theme') === 'dark' ? 'theme-dark' : '' }}{{ request()->cookie('admin_sidebar') === 'collapsed' ? ' sidebar-collapsed' : '' }}">

<div class="app-shell">

    @include('admin._partials.sidebar')

    <div class="app-main">

        <header class="app-header">
            <div class="header-left">
                <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Menüyü aç/kapat">
                    <i data-lucide="menu"></i>
                </button>
            </div>

            <div class="header-search">
                <i data-lucide="search" class="search-ic"></i>
                <input type="text" id="navSearch" autocomplete="off" placeholder="Menüde ara…"
                       onkeyup="navSearchHandler(event)" onfocus="navSearchHandler(event)">
                <div class="header-search-results" id="navSearchResults"></div>
            </div>

            <div class="header-right">
                @php
                    $hdrAppt = \App\Models\Appointment::where('status', 'yeni')->count();
                    $hdrMsg  = \App\Models\ContactMessage::unread()->count();
                    $hdrTotal = $hdrAppt + $hdrMsg;
                @endphp

                <a href="{{ route('admin.appointments.index') }}" class="icon-btn" title="Yeni ölçü talepleri">
                    <i data-lucide="bell"></i>
                    @if($hdrTotal > 0)
                        <span class="badge-dot">{{ $hdrTotal > 9 ? '9+' : $hdrTotal }}</span>
                    @endif
                </a>

                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="icon-btn"
                   title="Siteyi yeni sekmede aç">
                    <i data-lucide="external-link"></i>
                </a>

                <button class="icon-btn" onclick="toggleTheme()" title="Tema değiştir">
                    <i data-lucide="moon" id="themeIconMoon"></i>
                    <i data-lucide="sun" id="themeIconSun" style="display:none"></i>
                </button>

                <div class="user-menu">
                    <button class="user-trigger" onclick="toggleUserMenu(event)">
                        <span class="user-avatar">{{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="user-meta">
                            <strong>{{ auth()->user()->name }}</strong>
                            <small>Yönetici</small>
                        </span>
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="{{ route('admin.profile.edit') }}">
                            <i data-lucide="user"></i><span>Profilim</span>
                        </a>
                        <a href="{{ route('admin.settings.edit') }}">
                            <i data-lucide="settings"></i><span>Ayarlar</span>
                        </a>
                        <div class="divider"></div>
                        <button type="button" onclick="document.getElementById('logoutForm').submit()"
                                style="color:var(--danger)">
                            <i data-lucide="log-out"></i><span>Çıkış Yap</span>
                        </button>
                    </div>
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                </div>
            </div>
        </header>

        <main class="app-content">
            @if(session('success'))
                <div class="alert alert-success"><i data-lucide="check-circle"></i><div>{{ session('success') }}</div></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger"><i data-lucide="alert-circle"></i><div>{{ session('error') }}</div></div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <i data-lucide="alert-triangle"></i>
                    <div>
                        Formda düzeltilmesi gereken alanlar var:
                        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
/* ─── Sidebar ─── */
function toggleSidebar() {
    if (window.innerWidth <= 1024) {
        document.getElementById('appSidebar').classList.toggle('open');
        document.getElementById('sidebarBackdrop').classList.toggle('show');
        return;
    }
    document.body.classList.toggle('sidebar-collapsed');
    setCookie('admin_sidebar', document.body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'open');
}

/* ─── Tema ─── */
function toggleTheme() {
    var dark = document.body.classList.toggle('theme-dark');
    setCookie('admin_theme', dark ? 'dark' : 'light');
    syncThemeIcon();
}

function syncThemeIcon() {
    var dark = document.body.classList.contains('theme-dark');
    var moon = document.getElementById('themeIconMoon');
    var sun = document.getElementById('themeIconSun');
    if (moon) moon.style.display = dark ? 'none' : '';
    if (sun) sun.style.display = dark ? '' : 'none';
}

function setCookie(name, value) {
    document.cookie = name + '=' + value + ';path=/;max-age=31536000;samesite=lax';
}

/* ─── Kullanıcı menüsü ─── */
function toggleUserMenu(e) {
    e.stopPropagation();
    document.getElementById('userDropdown').classList.toggle('show');
}

document.addEventListener('click', function () {
    document.getElementById('userDropdown')?.classList.remove('show');
    document.getElementById('navSearchResults')?.classList.remove('show');
});

/* ─── Menüde arama ─── */
function navSearchHandler(e) {
    var input = document.getElementById('navSearch');
    var box = document.getElementById('navSearchResults');
    var q = input.value.trim().toLocaleLowerCase('tr');

    if (e.key === 'Escape') { box.classList.remove('show'); input.blur(); return; }

    var links = Array.prototype.slice.call(document.querySelectorAll('#sidebarNav .sidebar-link'));
    var hits = links.filter(function (a) {
        var label = (a.querySelector('.label')?.textContent || '').toLocaleLowerCase('tr');
        return q === '' ? true : label.indexOf(q) !== -1;
    }).slice(0, 8);

    if (e.key === 'Enter' && hits.length) { window.location.href = hits[0].getAttribute('href'); return; }

    box.innerHTML = hits.length
        ? hits.map(function (a) {
              return '<a href="' + a.getAttribute('href') + '">' +
                     (a.querySelector('.label')?.textContent || '') + '</a>';
          }).join('')
        : '<div class="no-result">Sonuç yok</div>';

    box.classList.add('show');
    e.stopPropagation();
}

document.getElementById('navSearchResults')?.addEventListener('click', function (e) { e.stopPropagation(); });

/* ─── İkonları çiz ─── */
function drawIcons() { if (window.lucide) window.lucide.createIcons(); }
window.addEventListener('DOMContentLoaded', function () { drawIcons(); syncThemeIcon(); });
window.addEventListener('load', drawIcons);
</script>

@stack('scripts')
</body>
</html>
