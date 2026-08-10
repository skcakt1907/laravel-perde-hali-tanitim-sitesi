@extends('layouts.app')

@section('title', $service->t('title') . ' — ' . setting('site_adi'))
@section('meta', Str::limit($service->t('summary'), 155))

@section('content')

@include('partials.page-head', [
    'title'  => $service->t('title'),
    'crumbs' => [__('site.services.title') => route('services'), $service->t('title') => null],
])

<section>
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                @if($service->image)
                    <img src="{{ $service->image }}" alt="{{ $service->t('title') }}"
                         style="width:100%;border-radius:12px;margin-bottom:2rem">
                @endif
                <div class="prose">
                    @if($s = $service->t('summary'))<p class="fs-5">{{ $s }}</p>@endif
                    @if($c = $service->t('content')){!! nl2br(e($c)) !!}@endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="side-cta mb-4">
                    <h5>{{ __('site.aufmass.title') }}</h5>
                    <p>{{ __('site.aufmass.free_note') }}</p>
                    <a href="{{ route('aufmass') }}" class="btn">{{ __('site.cta.aufmass_short') }}</a>
                </div>

                @if($others->isNotEmpty())
                    <div class="side-card">
                        <h4>{{ __('site.services.others') }}</h4>
                        <div class="side-list">
                            @foreach($others as $other)
                                <a href="{{ route('service.show', $other) }}">
                                    <i class="bi {{ $other->icon ?: 'bi-check2-circle' }}"></i>
                                    {{ $other->t('title') }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
