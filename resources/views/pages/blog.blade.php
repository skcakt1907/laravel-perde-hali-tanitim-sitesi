@extends('layouts.app')

@section('title', __('site.blog.title') . ' — ' . setting('site_adi'))
@section('meta', __('site.blog.lead'))

@section('content')

@include('partials.page-head', [
    'title'  => __('site.blog.title'),
    'lead'   => __('site.blog.lead'),
    'crumbs' => [__('site.blog.title') => null],
])

<section>
    <div class="container">
        @if($posts->isEmpty())
            <div class="empty-state">
                <i class="bi bi-journal-text"></i>
                <h3>{{ __('site.blog.empty') }}</h3>
            </div>
        @else
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
                                <p>{{ Str::limit($post->t('summary'), 120) }}</p>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <div class="mt-5">{{ $posts->links() }}</div>
        @endif
    </div>
</section>

@endsection
