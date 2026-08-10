{{-- Ücretsiz ölçü çağrı şeridi — birçok sayfanın altında kullanılır --}}
<div class="container" style="padding-bottom:70px">
    <div class="cta-strip">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h3>{{ __('site.home.cta_band_title') }}</h3>
                    <p>{{ __('site.home.cta_band_text') }}</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('aufmass') }}" class="btn">
                        <i class="bi bi-rulers"></i> {{ __('site.cta.aufmass') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
