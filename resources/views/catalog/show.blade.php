@extends('layouts.app')

@section('title', $product->t('name') . ' — ' . setting('site_adi'))
@section('meta', Str::limit($product->t('short_desc') ?: $product->t('description'), 155))
@section('og_image', $product->image_url)

@section('content')

@include('partials.page-head', [
    'title'  => $product->t('name'),
    'crumbs' => array_filter([
        __('site.catalog.title') => route('catalog'),
        ($product->category?->t('name') ?? '') => $product->category ? route('catalog.category', $product->category) : null,
        $product->t('name') => null,
    ], fn ($k) => $k !== '', ARRAY_FILTER_USE_KEY),
])

<section>
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="pd-gallery">
                    <img src="{{ $product->image_url }}" alt="{{ $product->t('name') }}">
                </div>
                @if(count($product->gallery) > 1)
                    <div class="pd-thumbs">
                        @foreach($product->gallery as $i => $img)
                            <img src="{{ $img }}" alt="{{ $product->t('name') }} {{ $i + 1 }}"
                                 class="{{ $i === 0 ? 'active' : '' }}" loading="lazy">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-6">
                @if($product->category)
                    <div class="pd-brand">{{ $product->category->t('name') }}</div>
                @endif
                <h1 class="pd-title">{{ $product->t('name') }}</h1>

                @if($short = $product->t('short_desc'))
                    <p>{{ $short }}</p>
                @endif

                <div class="pd-price">
                    @if($product->has_price)
                        <span>{{ __('site.catalog.from') }}</span>
                        <span class="now">{{ money($product->price) }}</span>
                        @if($product->price_unit)<span>/ {{ $product->price_unit }}</span>@endif
                    @else
                        <span class="req">{{ __('site.catalog.on_request') }}</span>
                    @endif
                </div>

                @if($attrs = $product->attributes)
                    <h5 class="mt-4">{{ __('site.catalog.properties') }}</h5>
                    <ul class="pd-attrs">
                        @foreach($attrs as $key => $value)
                            <li><span>{{ $key }}</span><span>{{ $value }}</span></li>
                        @endforeach
                    </ul>
                @endif

                <div class="pd-actions">
                    <a href="{{ route('aufmass', ['produkt' => $product->slug]) }}" class="btn-orange">
                        <i class="bi bi-rulers"></i> {{ __('site.catalog.ask_product') }}
                    </a>
                    @if($tel = setting('telefon'))
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $tel) }}" class="btn-line">
                            <i class="bi bi-telephone"></i> {{ __('site.cta.call') }}
                        </a>
                    @endif
                </div>

                <div class="alert-soft mt-4">
                    <i class="bi bi-info-circle me-1"></i> {{ __('site.aufmass.free_note') }}
                </div>
            </div>
        </div>

        @if($desc = $product->t('description'))
            <div class="prose mt-5">
                {!! nl2br(e($desc)) !!}
            </div>
        @endif
    </div>
</section>

@if($related->isNotEmpty())
    <section class="services-grid">
        <div class="container">
            <div class="section-head center">
                <h2>{{ __('site.catalog.related') }}</h2>
            </div>
            <div class="row g-4">
                @foreach($related as $item)
                    <div class="col-lg-3 col-md-6">
                        @include('partials.product-card', ['product' => $item])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@include('partials.cta-band')

@endsection
