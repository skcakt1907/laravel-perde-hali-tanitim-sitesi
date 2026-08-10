@extends('layouts.app')

@section('title', $project->t('title') . ' — ' . setting('site_adi'))
@section('meta', Str::limit($project->t('summary'), 155))
@section('og_image', $project->image_url)

@section('content')

@include('partials.page-head', [
    'title'  => $project->t('title'),
    'crumbs' => [__('site.gallery.title') => route('gallery'), $project->t('title') => null],
])

<section>
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="pd-gallery mb-3">
                    <img src="{{ $project->image_url }}" alt="{{ $project->t('title') }}">
                </div>

                @if(count($project->gallery) > 1)
                    <div class="row g-3">
                        @foreach(array_slice($project->gallery, 1) as $img)
                            <div class="col-md-6">
                                <img src="{{ $img }}" alt="{{ $project->t('title') }}"
                                     style="border-radius:12px;width:100%;aspect-ratio:4/3;object-fit:cover" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="prose mt-4">
                    @if($s = $project->t('summary'))<p class="fs-5">{{ $s }}</p>@endif
                    @if($c = $project->t('content')){!! nl2br(e($c)) !!}@endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="side-card mb-4">
                    <h4>{{ __('site.gallery.title') }}</h4>
                    <ul class="pd-attrs mb-0">
                        @if($k = $project->t('kind'))
                            <li><span>{{ __('site.gallery.kind') }}</span><span>{{ $k }}</span></li>
                        @endif
                        @if($project->location)
                            <li><span>{{ __('site.gallery.place') }}</span><span>{{ $project->location }}</span></li>
                        @endif
                        @if($project->tarih)
                            <li><span>{{ __('site.gallery.date') }}</span><span>{{ $project->tarih->format('m.Y') }}</span></li>
                        @endif
                    </ul>
                </div>

                <div class="side-cta mb-4">
                    <h5>{{ __('site.aufmass.title') }}</h5>
                    <p>{{ __('site.aufmass.free_note') }}</p>
                    <a href="{{ route('aufmass') }}" class="btn">{{ __('site.cta.aufmass_short') }}</a>
                </div>

                @if($others->isNotEmpty())
                    <div class="side-card">
                        <h4>{{ __('site.gallery.others') }}</h4>
                        <div class="side-list media">
                            @foreach($others as $other)
                                <a href="{{ route('gallery.show', $other) }}">
                                    <img src="{{ $other->image_url }}" alt="{{ $other->t('title') }}" loading="lazy">
                                    <span>
                                        <strong>{{ Str::limit($other->t('title'), 40) }}</strong>
                                        <small>{{ $other->location }}</small>
                                    </span>
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
