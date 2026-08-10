@extends('layouts.app')

@section('title', __('site.gallery.title') . ' — ' . setting('site_adi'))
@section('meta', __('site.gallery.lead'))

@section('content')

@include('partials.page-head', [
    'title'  => __('site.gallery.title'),
    'lead'   => __('site.gallery.lead'),
    'crumbs' => [__('site.gallery.title') => null],
])

<section>
    <div class="container">
        @if($kinds->isNotEmpty())
            <div class="text-center mb-5">
                <a href="{{ route('gallery') }}" class="filter-btn {{ $kind ? '' : 'active' }}">{{ __('site.gallery.filter') }}</a>
                @foreach($kinds as $k)
                    <a href="{{ route('gallery', ['art' => $k]) }}" class="filter-btn {{ $kind === $k ? 'active' : '' }}">{{ $k }}</a>
                @endforeach
            </div>
        @endif

        @if($projects->isEmpty())
            <div class="empty-state">
                <i class="bi bi-images"></i>
                <h3>{{ __('site.gallery.empty') }}</h3>
            </div>
        @else
            <div class="row g-4">
                @foreach($projects as $project)
                    <div class="col-lg-4 col-md-6">
                        <a class="proj" href="{{ route('gallery.show', $project) }}">
                            <img src="{{ $project->image_url }}" alt="{{ $project->t('title') }}" loading="lazy">
                            <div class="proj-info">
                                @if($k = $project->t('kind'))<span class="cat">{{ $k }}</span>@endif
                                <h5>{{ $project->t('title') }}</h5>
                                @if($project->location)<small><i class="bi bi-geo-alt"></i> {{ $project->location }}</small>@endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-5">{{ $projects->links() }}</div>
        @endif
    </div>
</section>

@include('partials.cta-band')

@endsection
