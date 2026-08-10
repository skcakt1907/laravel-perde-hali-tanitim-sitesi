<div class="page-head">
    <div class="container">
        <h1>{{ $title }}</h1>
        @isset($lead)
            <p class="lead">{{ $lead }}</p>
        @endisset
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a></li>
                @foreach($crumbs ?? [] as $label => $url)
                    @if($url)
                        <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                    @endif
                @endforeach
            </ol>
        </nav>
    </div>
</div>
