@extends('layouts.app')

@section('title', __('site.aufmass.title') . ' — ' . setting('site_adi'))
@section('meta', __('site.aufmass.lead'))

@section('content')

@include('partials.page-head', [
    'title'  => __('site.aufmass.title'),
    'lead'   => __('site.aufmass.lead'),
    'crumbs' => [__('site.aufmass.title') => null],
])

<section>
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('aufmass.store') }}" class="contact-form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="a-name">{{ __('site.form.name') }} *</label>
                            <input id="a-name" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="a-phone">{{ __('site.form.phone') }} *</label>
                            <input id="a-phone" name="phone" class="form-control" value="{{ old('phone') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="a-email">{{ __('site.form.email') }} <small class="text-muted">({{ __('site.form.optional') }})</small></label>
                            <input id="a-email" name="email" type="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="a-subject">{{ __('site.form.interest') }}</label>
                            @php $preselect = old('subject', request('produkt')); @endphp
                            <select id="a-subject" name="subject" class="form-select">
                                <option value="">{{ __('site.form.choose') }}</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->t('name') }}" @selected($preselect === $cat->t('name'))>{{ $cat->t('name') }}</option>
                                @endforeach
                                @if($preselect && ! $categories->contains(fn ($c) => $c->t('name') === $preselect))
                                    <option value="{{ $preselect }}" selected>{{ $preselect }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="a-zip">{{ __('site.form.zip') }}</label>
                            <input id="a-zip" name="zip" class="form-control" value="{{ old('zip') }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="a-city">{{ __('site.form.city') }}</label>
                            <input id="a-city" name="city" class="form-control" value="{{ old('city') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="a-address">{{ __('site.form.address') }} <small class="text-muted">({{ __('site.form.optional') }})</small></label>
                            <input id="a-address" name="address" class="form-control" value="{{ old('address') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="a-date">{{ __('site.form.date') }}</label>
                            <input id="a-date" name="date" type="date" class="form-control" value="{{ old('date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="a-time">{{ __('site.form.time') }}</label>
                            <input id="a-time" name="time" type="time" class="form-control" value="{{ old('time') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="a-note">{{ __('site.form.note') }}</label>
                            <textarea id="a-note" name="note" rows="5" class="form-control"
                                      placeholder="{{ __('site.aufmass.note_hint') }}">{{ old('note') }}</textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="privacy" value="1" id="a-privacy" required>
                                <label class="form-check-label" for="a-privacy">
                                    <a href="{{ route('legal', 'datenschutz') }}" target="_blank">{{ __('site.legal.datenschutz') }}</a>
                                    {{ app()->getLocale() === 'tr' ? 'metnini okudum ve kabul ediyorum.' : 'habe ich gelesen und akzeptiere sie.' }} *
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-orange"><i class="bi bi-rulers"></i> {{ __('site.cta.aufmass_short') }}</button>
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
                        <i class="bi bi-cash-coin"></i>
                        <div><strong>{{ __('site.home.usp_1_title') }}</strong><span>{{ __('site.home.usp_1_text') }}</span></div>
                    </li>
                    <li>
                        <i class="bi bi-palette"></i>
                        <div><strong>{{ __('site.home.step_2_title') }}</strong><span>{{ __('site.home.step_2_text') }}</span></div>
                    </li>
                    <li>
                        <i class="bi bi-file-earmark-text"></i>
                        <div><strong>{{ __('site.home.step_3_title') }}</strong><span>{{ __('site.home.step_3_text') }}</span></div>
                    </li>
                    <li>
                        <i class="bi bi-tools"></i>
                        <div><strong>{{ __('site.home.step_4_title') }}</strong><span>{{ __('site.home.step_4_text') }}</span></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

@endsection
