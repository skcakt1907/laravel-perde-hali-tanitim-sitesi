@extends('layouts.app')

@section('title', __('site.services.title') . ' — ' . setting('site_adi'))
@section('meta', __('site.services.lead'))

@section('content')

@include('partials.page-head', [
    'title'  => __('site.services.title'),
    'lead'   => __('site.services.lead'),
    'crumbs' => [__('site.services.title') => null],
])

<section>
    <div class="container">
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

@include('partials.cta-band')

@endsection
