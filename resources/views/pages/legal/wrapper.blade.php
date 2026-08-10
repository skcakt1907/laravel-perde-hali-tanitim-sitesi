@extends('layouts.app')

@section('title', $pageTitle . ' — ' . setting('site_adi'))
@section('meta', $pageTitle . ' — ' . setting('site_adi'))

@section('content')

@include('partials.page-head', [
    'title'  => $pageTitle,
    'crumbs' => [$pageTitle => null],
])

<section>
    <div class="container">
        <div class="prose">
            @include($bodyView)
        </div>
    </div>
</section>

@endsection
