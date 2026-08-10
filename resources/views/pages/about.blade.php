@extends('layouts.app')

@section('title', __('site.about.title') . ' — ' . setting('site_adi'))
@section('meta', Str::limit(tsetting('hakkimizda_metin', __('site.about.lead')), 155))

@section('content')

@include('partials.page-head', [
    'title'  => __('site.about.title'),
    'lead'   => __('site.about.lead'),
    'crumbs' => [__('site.about.title') => null],
])

<section>
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="about-img-wrap">
                    <img src="{{ setting('hakkimizda_gorsel', asset('img/demo/about.jpg')) }}" alt="{{ setting('site_adi') }}">
                    <div class="exp-badge">
                        <span class="num">{{ setting('istatistik_yil', '15') }}+</span>
                        <span class="lbl">{{ __('site.stats.years_short') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-head">
                    <span class="mini">{{ setting('site_adi') }}</span>
                    <h2>{{ tsetting('hakkimizda_baslik', __('site.about.lead')) }}</h2>
                </div>
                <div class="prose">
                    {!! nl2br(e(tsetting('hakkimizda_metin'))) !!}
                </div>
                <ul class="about-features">
                    @foreach(array_filter(preg_split('/\r?\n/', (string) tsetting('hakkimizda_maddeler'))) as $item)
                        <li><i class="bi bi-check-lg"></i> {{ trim($item) }}</li>
                    @endforeach
                </ul>
                <div class="mt-4 d-flex gap-2 flex-wrap">
                    <a href="{{ route('aufmass') }}" class="btn-orange"><i class="bi bi-rulers"></i> {{ __('site.cta.aufmass') }}</a>
                    <a href="{{ route('gallery') }}" class="btn-line">{{ __('site.cta.all_projects') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

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
                            <a href="{{ route('service.show', $service) }}">{{ __('site.cta.read_more') }} <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@if($testimonials->isNotEmpty())
    <section>
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
                                <div class="avatar-fallback">{{ Str::upper(Str::substr($t->name, 0, 1)) }}</div>
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

@include('partials.cta-band')

@endsection
