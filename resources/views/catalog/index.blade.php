@extends('layouts.app')

@section('title', ($category ? $category->t('name') : __('site.catalog.title')) . ' — ' . setting('site_adi'))
@section('meta', $category ? Str::limit($category->t('description'), 155) : __('site.home.categories_sub'))

@section('content')

@include('partials.page-head', [
    'title'  => $category ? $category->t('name') : __('site.catalog.title'),
    'lead'   => $category ? $category->t('description') : __('site.home.categories_sub'),
    'crumbs' => $category
        ? [__('site.catalog.title') => route('catalog'), $category->t('name') => null]
        : [__('site.catalog.title') => null],
])

<section>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <aside class="shop-filter">
                    <h5>{{ __('site.nav.products') }}</h5>
                    <ul class="f-list">
                        <li>
                            <a href="{{ route('catalog') }}" class="{{ $category ? '' : 'active' }}">
                                {{ __('site.catalog.all') }}
                            </a>
                        </li>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('catalog.category', $cat) }}"
                                   class="{{ $category && $category->id === $cat->id ? 'active' : '' }}">
                                    {{ $cat->t('name') }}
                                    <span>{{ $cat->products()->where('durum', true)->count() }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="side-cta">
                        <h5>{{ __('site.aufmass.title') }}</h5>
                        <p>{{ __('site.aufmass.free_note') }}</p>
                        <a href="{{ route('aufmass') }}" class="btn">{{ __('site.cta.aufmass_short') }}</a>
                    </div>
                </aside>
            </div>

            <div class="col-lg-9">
                <div class="shop-toolbar">
                    <span class="count">
                        {{ $products->total() }}
                        {{ __('site.catalog.count') }}
                    </span>
                </div>

                @if($products->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h3>{{ __('site.catalog.empty') }}</h3>
                        <a href="{{ route('aufmass') }}" class="btn-orange">{{ __('site.cta.aufmass') }}</a>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($products as $product)
                            <div class="col-lg-4 col-md-6">
                                @include('partials.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5">{{ $products->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</section>

@include('partials.cta-band')

@endsection
