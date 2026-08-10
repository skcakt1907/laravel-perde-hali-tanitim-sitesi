@extends('layouts.app')

@section('title', $post->t('title') . ' — ' . setting('site_adi'))
@section('meta', Str::limit($post->t('summary'), 155))
@section('og_image', $post->image_url)

@section('content')

@include('partials.page-head', [
    'title'  => $post->t('title'),
    'crumbs' => [__('site.blog.title') => route('blog'), Str::limit($post->t('title'), 40) => null],
])

<section>
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <img src="{{ $post->image_url }}" alt="{{ $post->t('title') }}"
                     style="width:100%;border-radius:12px;margin-bottom:2rem">
                <div class="blog-meta mb-3">
                    <i class="bi bi-calendar3"></i>{{ optional($post->tarih)->format('d.m.Y') }}
                    @if($cat = $post->t('category')) · {{ $cat }}@endif
                </div>
                <div class="prose">
                    @if($s = $post->t('summary'))<p class="fs-5">{{ $s }}</p>@endif
                    @if($c = $post->t('content')){!! nl2br(e($c)) !!}@endif
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
                        <h4>{{ __('site.blog.others') }}</h4>
                        <div class="side-list media">
                            @foreach($others as $other)
                                <a href="{{ route('blog.show', $other) }}">
                                    <img src="{{ $other->image_url }}" alt="{{ $other->t('title') }}" loading="lazy">
                                    <span>
                                        <strong>{{ Str::limit($other->t('title'), 40) }}</strong>
                                        <small>{{ optional($other->tarih)->format('d.m.Y') }}</small>
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
