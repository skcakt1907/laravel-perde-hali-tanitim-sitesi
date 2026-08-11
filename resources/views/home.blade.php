@extends('layouts.app')

@section('title', setting('site_adi') . ' — ' . __('site.home.hero_eyebrow'))
@section('meta', __('site.home.hero_text'))

@section('content')

{{-- ===================== HERO ===================== --}}
<section class="hero">
    <img class="hero-bg" src="{{ media(setting('hero_gorsel'), 'img/demo/hero.jpg') }}" alt="">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <span class="hero-badge"><i class="bi bi-circle-fill"></i> {{ __('site.home.hero_eyebrow') }}</span>
                <h1>{!! nl2br(e(tsetting('hero_baslik', __('site.home.hero_title')))) !!}</h1>
                <p>{{ tsetting('hero_metin', __('site.home.hero_text')) }}</p>
                <div class="hero-cta">
                    <a href="{{ route('aufmass') }}" class="btn-orange">
                        <i class="bi bi-rulers"></i> {{ __('site.cta.aufmass') }}
                    </a>
                    @if($tel = setting('telefon'))
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $tel) }}" class="btn-ghost-light">
                            <i class="bi bi-telephone"></i> {{ $tel }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-3 hero-meta">
            @foreach([
                ['bi-rulers', __('site.home.usp_1_title'), __('site.home.usp_1_text')],
                ['bi-scissors', __('site.home.usp_2_title'), __('site.home.usp_2_text')],
                ['bi-tools', __('site.home.usp_3_title'), __('site.home.usp_3_text')],
                ['bi-award', __('site.home.usp_4_title'), __('site.home.usp_4_text')],
            ] as [$icon, $t, $d])
                <div class="col-lg-3 col-sm-6">
                    <div class="meta-item">
                        <i class="bi {{ $icon }}"></i>
                        <span>
                            <strong>{{ $t }}</strong>
                            <small>{{ $d }}</small>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== KATEGORİLER ===================== --}}
@if($categories->isNotEmpty())
    <section>
        <div class="container">
            <div class="section-head center">
                <span class="mini">{{ __('site.nav.products') }}</span>
                <h2>{{ __('site.home.categories') }}</h2>
                <p class="desc">{{ __('site.home.categories_sub') }}</p>
            </div>
            <div class="row g-4">
                @foreach($categories as $cat)
                    <div class="col-lg-4 col-md-6">
                        <a class="proj" href="{{ route('catalog.category', $cat) }}">
                            <img src="{{ $cat->image_url }}" alt="{{ $cat->t('name') }}" loading="lazy">
                            <div class="proj-info">
                                <h5>{{ $cat->t('name') }}</h5>
                                @if($d = $cat->t('description'))
                                    <small>{{ Str::limit($d, 70) }}</small>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('catalog') }}" class="btn-line">{{ __('site.cta.all_products') }} <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>
@endif

{{-- ===================== HİZMETLER ===================== --}}
@if($services->isNotEmpty())
    <section class="services-grid">
        <div class="container">
            <div class="section-head center">
                <span class="mini">{{ __('site.nav.services') }}</span>
                <h2>{{ __('site.home.services') }}</h2>
                <p class="desc">{{ __('site.home.services_sub') }}</p>
            </div>
            <div class="row g-4">
                @foreach($services as $service)
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="icon"><i class="bi {{ $service->icon ?: 'bi-check2-circle' }}"></i></div>
                            <h4>{{ $service->t('title') }}</h4>
                            <p>{{ $service->t('summary') }}</p>
                            <a href="{{ route('service.show', $service) }}">
                                {{ __('site.cta.read_more') }} <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===================== ÖNE ÇIKAN ÜRÜNLER ===================== --}}
@if($featured->isNotEmpty())
    <section>
        <div class="container">
            <div class="section-head center">
                <span class="mini">{{ __('site.home.featured_eyebrow') }}</span>
                <h2>{{ __('site.home.featured') }}</h2>
                <p class="desc">{{ __('site.home.featured_sub') }}</p>
            </div>
            <div class="row g-4">
                @foreach($featured as $product)
                    <div class="col-lg-3 col-md-6">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===================== SÜREÇ ===================== --}}
<section class="services-grid">
    <div class="container">
        <div class="section-head center">
            <span class="mini">{{ __('site.home.steps_eyebrow') }}</span>
            <h2>{{ __('site.home.steps') }}</h2>
        </div>
        <div class="row g-4 steps">
            @foreach([
                [__('site.home.step_1_title'), __('site.home.step_1_text')],
                [__('site.home.step_2_title'), __('site.home.step_2_text')],
                [__('site.home.step_3_title'), __('site.home.step_3_text')],
                [__('site.home.step_4_title'), __('site.home.step_4_text')],
            ] as [$t, $d])
                <div class="col-lg-3 col-md-6">
                    <div class="step">
                        <h5>{{ $t }}</h5>
                        <p>{{ $d }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== İSTATİSTİK ===================== --}}
<div class="stats">
    <div class="container">
        <div class="row g-4">
            @foreach([
                ['bi-calendar-check', setting('istatistik_yil', '15'), __('site.stats.years')],
                ['bi-window', setting('istatistik_pencere', '12.000'), __('site.stats.windows')],
                ['bi-emoji-smile', setting('istatistik_musteri', '3.400'), __('site.stats.customers')],
                ['bi-truck', setting('istatistik_bolge', '100'), __('site.stats.radius')],
            ] as [$icon, $num, $lbl])
                <div class="col-lg-3 col-6">
                    <div class="stat">
                        <i class="bi {{ $icon }}"></i>
                        <h3>{{ $num }}</h3>
                        <p>{{ $lbl }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ===================== GALERİ ===================== --}}
@if($projects->isNotEmpty())
    <section>
        <div class="container">
            <div class="section-head center">
                <span class="mini">{{ __('site.nav.gallery') }}</span>
                <h2>{{ __('site.home.gallery') }}</h2>
                <p class="desc">{{ __('site.home.gallery_sub') }}</p>
            </div>
            <div class="row g-4">
                @foreach($projects as $project)
                    <div class="col-lg-4 col-md-6">
                        <a class="proj" href="{{ route('gallery.show', $project) }}">
                            <img src="{{ $project->image_url }}" alt="{{ $project->t('title') }}" loading="lazy">
                            <div class="proj-info">
                                @if($kind = $project->t('kind'))<span class="cat">{{ $kind }}</span>@endif
                                <h5>{{ $project->t('title') }}</h5>
                                @if($project->location)<small><i class="bi bi-geo-alt"></i> {{ $project->location }}</small>@endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('gallery') }}" class="btn-line">{{ __('site.cta.all_projects') }} <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>
@endif

{{-- ===================== YORUMLAR ===================== --}}
@if($testimonials->isNotEmpty())
    <section class="services-grid">
        <div class="container">
            <div class="section-head center">
                <span class="mini">{{ __('site.home.testimonials_eyebrow') }}</span>
                <h2>{{ __('site.home.testimonials') }}</h2>
            </div>
            <div class="row g-4">
                @foreach($testimonials as $t)
                    <div class="col-lg-4 col-md-6">
                        <div class="testi">
                            <div class="stars">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="bi bi-star{{ $i < $t->stars ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            <p>{{ $t->t('comment') }}</p>
                            <div class="testi-user">
                                @if($t->photo)
                                    <img src="{{ media($t->photo) }}" alt="{{ $t->name }}" loading="lazy">
                                @else
                                    <div class="avatar-fallback">{{ Str::upper(Str::substr($t->name, 0, 1)) }}</div>
                                @endif
                                <div>
                                    <h6>{{ $t->name }}</h6>
                                    <span>{{ $t->t('title') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===================== BLOG ===================== --}}
@if($posts->isNotEmpty())
    <section>
        <div class="container">
            <div class="section-head center">
                <span class="mini">{{ __('site.nav.blog') }}</span>
                <h2>{{ __('site.home.blog') }}</h2>
                <p class="desc">{{ __('site.home.blog_sub') }}</p>
            </div>
            <div class="row g-4">
                @foreach($posts as $post)
                    <div class="col-lg-4 col-md-6">
                        <article class="blog-card">
                            <div class="img">
                                <img src="{{ $post->image_url }}" alt="{{ $post->t('title') }}" loading="lazy">
                                @if($cat = $post->t('category'))<span class="cat">{{ $cat }}</span>@endif
                            </div>
                            <div class="blog-body">
                                <div class="meta"><i class="bi bi-calendar3"></i>{{ optional($post->tarih)->format('d.m.Y') }}</div>
                                <h5><a href="{{ route('blog.show', $post) }}">{{ $post->t('title') }}</a></h5>
                                <p>{{ Str::limit($post->t('summary'), 110) }}</p>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@include('partials.cta-band')

@endsection
