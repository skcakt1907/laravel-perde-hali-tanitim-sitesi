{{--
═══════════════════════════════════════════════════════════
AYARLAR SOL ALT-MENÜSÜ — tüm ayarlar sayfalarında kullanılır
Kullanım: @include('admin.ayarlar._partials.nav', ['active' => 'genel'])
Menü, SettingController::PAGES'ten türer — yeni bölüm eklemek için
sadece o diziye ekleme yapmak yeterli.
═══════════════════════════════════════════════════════════
--}}
@php
    $active = $active ?? 'genel';
    $pages  = \App\Http\Controllers\Admin\SettingController::pages();

    // Grup başlıklarına göre sırala
    $gruplar = [];
    foreach ($pages as $key => $p) {
        $gruplar[$p['group']][$key] = $p;
    }

    $aktifLabel = $pages[$active]['title'] ?? 'Bölüm';
@endphp

<button type="button" class="ayarlar-nav-toggle" id="ayarlarNavToggle" onclick="toggleAyarlarNav()">
    <span class="nt-left">
        <i data-lucide="menu"></i>
        <span>Bölüm: <strong>{{ $aktifLabel }}</strong></span>
    </span>
    <i data-lucide="chevron-down" class="nt-chevron"></i>
</button>

<aside class="ayarlar-subnav" id="ayarlarSubnav">
    @foreach($gruplar as $baslik => $items)
        <div class="ayarlar-group">
            <div class="ayarlar-group-title">{{ $baslik }}</div>
            @foreach($items as $key => $item)
                <a href="{{ route('admin.settings.edit', $key) }}"
                   class="ayarlar-sub-link {{ $active === $key ? 'active' : '' }}">
                    <i data-lucide="{{ $item['icon'] }}"></i>
                    <span>{{ $item['title'] }}</span>
                </a>
            @endforeach
        </div>
    @endforeach
</aside>

<script>
function toggleAyarlarNav() {
    var sub = document.getElementById('ayarlarSubnav');
    var btn = document.getElementById('ayarlarNavToggle');
    var acik = sub && sub.classList.toggle('open');
    if (btn) btn.classList.toggle('open');
    document.body.classList.toggle('ayarlar-nav-acik', !!acik);
    if (window.lucide) window.lucide.createIcons();
}
</script>
