@extends('layouts.app')

@section('title', __('site.contact.title') . ' — ' . setting('site_adi'))
@section('meta', __('site.contact.lead'))

@section('content')

@include('partials.page-head', [
    'title'  => __('site.contact.title'),
    'lead'   => __('site.contact.lead'),
    'crumbs' => [__('site.contact.title') => null],
])

<section>
    <div class="container">
        <div class="row g-4 mb-5">
            @if($adres = setting('adres'))
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <i class="bi bi-geo-alt"></i>
                        <h5>{{ __('site.contact.address') }}</h5>
                        <p>{{ $adres }}</p>
                    </div>
                </div>
            @endif
            @if($tel = setting('telefon'))
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <i class="bi bi-telephone"></i>
                        <h5>{{ __('site.contact.phone') }}</h5>
                        <p><a href="tel:{{ preg_replace('/[^\d+]/', '', $tel) }}">{{ $tel }}</a></p>
                    </div>
                </div>
            @endif
            @if($mail = setting('eposta'))
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <i class="bi bi-envelope"></i>
                        <h5>{{ __('site.contact.email') }}</h5>
                        <p><a href="mailto:{{ $mail }}">{{ $mail }}</a></p>
                    </div>
                </div>
            @endif
            @if($hours = tsetting('calisma_saatleri'))
                <div class="col-lg-3 col-md-6">
                    <div class="contact-info-card">
                        <i class="bi bi-clock"></i>
                        <h5>{{ __('site.contact.hours') }}</h5>
                        <p>{{ $hours }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="row g-5">
            <div class="col-lg-7">
                <div class="section-head">
                    <h2>{{ __('site.contact.form') }}</h2>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="contact-form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="c-name">{{ __('site.form.name') }} *</label>
                            <input id="c-name" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="c-phone">{{ __('site.form.phone') }}</label>
                            <input id="c-phone" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="c-email">{{ __('site.form.email') }}</label>
                            <input id="c-email" name="email" type="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="c-subject">{{ __('site.form.subject') }}</label>
                            <input id="c-subject" name="subject" class="form-control" value="{{ old('subject') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="c-message">{{ __('site.form.message') }} *</label>
                            <textarea id="c-message" name="message" rows="6" class="form-control" required>{{ old('message') }}</textarea>
                        </div>
                        {{-- Honeypot: ekran okuyucudan ve gözden gizli; yalnızca botlar doldurur --}}
                        <div class="hp-field" aria-hidden="true">
                            <label for="c-website">Website</label>
                            <input type="text" id="c-website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="privacy" value="1" id="c-privacy" required>
                                <label class="form-check-label" for="c-privacy">
                                    {{ __('site.form.privacy_before') }}
                                    <a href="{{ route('legal', 'datenschutz') }}" target="_blank">{{ __('site.legal.datenschutz') }}</a>
                                    {{ __('site.form.privacy_after') }} *
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-orange"><i class="bi bi-send"></i> {{ __('site.cta.send') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-5">
                @if($tel = setting('telefon'))
                    <div class="quote-call">
                        <div class="quote-call-icon"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <small>{{ __('site.cta.call') }}</small>
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $tel) }}">{{ $tel }}</a>
                        </div>
                    </div>
                @endif

                <ul class="quote-perks">
                    <li>
                        <i class="bi bi-rulers"></i>
                        <div><strong>{{ __('site.home.usp_1_title') }}</strong><span>{{ __('site.home.usp_1_text') }}</span></div>
                    </li>
                    <li>
                        <i class="bi bi-scissors"></i>
                        <div><strong>{{ __('site.home.usp_2_title') }}</strong><span>{{ __('site.home.usp_2_text') }}</span></div>
                    </li>
                    <li>
                        <i class="bi bi-tools"></i>
                        <div><strong>{{ __('site.home.usp_3_title') }}</strong><span>{{ __('site.home.usp_3_text') }}</span></div>
                    </li>
                </ul>

                @if($map = setting('harita_embed'))
                    <div class="mt-4" style="border-radius:12px;overflow:hidden;border:1px solid var(--line)">
                        {!! $map !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
