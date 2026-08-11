<div class="topbar">
    <div class="container">
        <div class="tb-left">
            @if($tel = setting('telefon'))
                <a href="tel:{{ preg_replace('/[^\d+]/', '', $tel) }}"><i class="bi bi-telephone-fill"></i>{{ $tel }}</a>
            @endif
            <span class="d-none d-md-inline-flex"><i class="bi bi-rulers"></i>{{ __('site.aufmass.free_note') }}</span>
        </div>
        <div class="lang-switch">
            @foreach(locales() as $code => $label)
                {{-- Hedef dildeki gerçek adrese gider, ayrıca tercihi çereze yazar --}}
                <a href="{{ locale_switch_url($code) }}" class="{{ app()->getLocale() === $code ? 'active' : '' }}"
                   rel="nofollow" hreflang="{{ $code }}" title="{{ $label }}">{{ strtoupper($code) }}</a>
            @endforeach
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('img/logo.png') }}" alt="{{ setting('site_adi') }}">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-label="Menü">
            <i class="bi bi-list fs-2"></i>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('site.nav.home') }}</a>
                </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link {{ request()->routeIs('catalog*') || request()->routeIs('product') ? 'active' : '' }}" href="{{ route('catalog') }}">
                        {{ __('site.nav.products') }} <i class="bi bi-chevron-down" style="font-size:.7rem"></i>
                    </a>
                    <div class="dropdown-panel">
                        @foreach($navCategories as $cat)
                            <a href="{{ route('catalog.category', $cat) }}">
                                <i class="bi {{ $cat->icon ?: 'bi-columns-gap' }}"></i> {{ $cat->t('name') }}
                            </a>
                        @endforeach
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('services*') || request()->routeIs('service.show') ? 'active' : '' }}" href="{{ route('services') }}">{{ __('site.nav.services') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('gallery*') ? 'active' : '' }}" href="{{ route('gallery') }}">{{ __('site.nav.gallery') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">{{ __('site.nav.about') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('site.nav.contact') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-cta" href="{{ route('aufmass') }}">
                        <i class="bi bi-rulers me-1"></i>{{ __('site.nav.aufmass') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
