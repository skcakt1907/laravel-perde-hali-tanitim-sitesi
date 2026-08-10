@php
    $r = optional(request()->route())->getName() ?? '';

    $newAppt   = \App\Models\Appointment::where('status', 'yeni')->count();
    $unreadMsg = \App\Models\ContactMessage::unread()->count();

    /** Menü tanımı — header'daki arama da bu listeyi tarar. */
    $menu = [
        ['type' => 'link', 'label' => 'Panel', 'icon' => 'layout-dashboard',
         'url' => route('admin.dashboard'), 'active' => $r === 'admin.dashboard'],

        ['type' => 'section', 'label' => 'Katalog'],
        ['type' => 'link', 'label' => 'Ürünler', 'icon' => 'package',
         'url' => route('admin.products.index'), 'active' => str_starts_with($r, 'admin.products')],
        ['type' => 'link', 'label' => 'Kategoriler', 'icon' => 'tags',
         'url' => route('admin.categories.index'), 'active' => str_starts_with($r, 'admin.categories')],
        ['type' => 'link', 'label' => 'Hizmetler', 'icon' => 'list-checks',
         'url' => route('admin.services.index'), 'active' => str_starts_with($r, 'admin.services')],
        ['type' => 'link', 'label' => 'Yapılan İşler', 'icon' => 'images',
         'url' => route('admin.projects.index'), 'active' => str_starts_with($r, 'admin.projects')],

        ['type' => 'section', 'label' => 'Talepler'],
        ['type' => 'link', 'label' => 'Ölçü Talepleri', 'icon' => 'ruler',
         'url' => route('admin.appointments.index'), 'active' => str_starts_with($r, 'admin.appointments'),
         'count' => $newAppt],
        ['type' => 'link', 'label' => 'Mesajlar', 'icon' => 'mail',
         'url' => route('admin.messages.index'), 'active' => str_starts_with($r, 'admin.messages'),
         'count' => $unreadMsg],

        ['type' => 'section', 'label' => 'Sistem'],
        ['type' => 'link', 'label' => 'Ayarlar', 'icon' => 'settings',
         'url' => route('admin.settings.edit'), 'active' => str_starts_with($r, 'admin.settings')],
        ['type' => 'link', 'label' => 'Profil', 'icon' => 'user-cog',
         'url' => route('admin.profile.edit'), 'active' => str_starts_with($r, 'admin.profile')],
    ];
@endphp

<aside class="app-sidebar" id="appSidebar">
    <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
        <span class="logo-mark"><img src="{{ asset('img/logo-mark.png') }}" alt=""></span>
        <span class="logo-text">
            <strong>MC Gordijnen</strong>
            <small>Yönetim</small>
        </span>
    </a>

    <nav class="sidebar-nav" id="sidebarNav">
        @foreach($menu as $item)
            @if($item['type'] === 'section')
                <div class="sidebar-section">{{ $item['label'] }}</div>
            @else
                <a href="{{ $item['url'] }}" class="sidebar-link {{ $item['active'] ? 'active' : '' }}"
                   title="{{ $item['label'] }}">
                    <i data-lucide="{{ $item['icon'] }}"></i>
                    <span class="label">{{ $item['label'] }}</span>
                    @if(! empty($item['count']))
                        <span class="sidebar-count">{{ $item['count'] > 9 ? '9+' : $item['count'] }}</span>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>

    <div class="sidebar-foot">
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="sidebar-link">
            <i data-lucide="external-link"></i>
            <span class="label">Siteyi Gör</span>
        </a>
    </div>
</aside>

<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>
