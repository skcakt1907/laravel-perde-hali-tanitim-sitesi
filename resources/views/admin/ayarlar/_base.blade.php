{{--
═══════════════════════════════════════════════════════════
AYARLAR SAYFA İSKELETİ
Alt sayfalar: @extends('admin.ayarlar._base') + @section('fields')
Değişkenler denetleyiciden gelir: $page, $meta, $settings
═══════════════════════════════════════════════════════════
--}}
@extends('admin.layout')

@section('title', $meta['title'])

@push('head')
    @include('admin.ayarlar._partials.styles')
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Panel</a>
    <span class="sep">/</span>
    <a href="{{ route('admin.settings.edit') }}">Ayarlar</a>
    <span class="sep">/</span>
    <span class="current">{{ $meta['title'] }}</span>
</div>

<div class="page-header">
    <div>
        <h1 class="page-title">
            <i data-lucide="{{ $meta['icon'] }}"></i>
            {{ $meta['title'] }}
        </h1>
        <div class="page-subtitle">{{ $meta['subtitle'] }}</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-secondary">
            <i data-lucide="external-link"></i> Siteyi gör
        </a>
    </div>
</div>

<div class="ayarlar-layout">
    @include('admin.ayarlar._partials.nav', ['active' => $page])

    <div class="ayarlar-content">
        <form action="{{ route('admin.settings.update', $page) }}" method="POST">
            @csrf

            @yield('fields')

            <div class="sticky-save">
                <span class="hint">@yield('save_hint', 'Kaydedince site anında güncellenir.')</span>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="check"></i> Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
