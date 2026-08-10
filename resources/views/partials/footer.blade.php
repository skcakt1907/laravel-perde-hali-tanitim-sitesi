<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="brand">
                    <img src="{{ asset('img/logo-light.png') }}" alt="{{ setting('site_adi') }}">
                </div>
                <p>{{ tsetting('site_aciklama') }}</p>
                <div class="social">
                    @if($ig = setting('instagram'))
                        <a href="{{ $ig }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if($fb = setting('facebook'))
                        <a href="{{ $fb }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if($wa = setting('whatsapp'))
                        <a href="https://wa.me/{{ preg_replace('/\D+/', '', $wa) }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    @endif
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6">
                <h5>{{ __('site.footer.products') }}</h5>
                @foreach($navCategories as $cat)
                    <a href="{{ route('catalog.category', $cat) }}">{{ $cat->t('name') }}</a>
                @endforeach
            </div>
            <div class="col-lg-2 col-md-6 col-6">
                <h5>{{ __('site.footer.company') }}</h5>
                <a href="{{ route('about') }}">{{ __('site.nav.about') }}</a>
                <a href="{{ route('services') }}">{{ __('site.nav.services') }}</a>
                <a href="{{ route('gallery') }}">{{ __('site.nav.gallery') }}</a>
                <a href="{{ route('blog') }}">{{ __('site.nav.blog') }}</a>
                <a href="{{ route('contact') }}">{{ __('site.nav.contact') }}</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5>{{ __('site.footer.contact') }}</h5>
                @if($adres = setting('adres'))
                    <div class="contact-li"><i class="bi bi-geo-alt"></i><span>{{ $adres }}</span></div>
                @endif
                @if($tel = setting('telefon'))
                    <div class="contact-li"><i class="bi bi-telephone"></i><a href="tel:{{ preg_replace('/[^\d+]/', '', $tel) }}">{{ $tel }}</a></div>
                @endif
                @if($mail = setting('eposta'))
                    <div class="contact-li"><i class="bi bi-envelope"></i><a href="mailto:{{ $mail }}">{{ $mail }}</a></div>
                @endif
                @if($hours = tsetting('calisma_saatleri'))
                    <div class="contact-li"><i class="bi bi-clock"></i><span>{{ $hours }}</span></div>
                @endif
            </div>
        </div>
        <div class="footer-legal">
            @foreach(App\Http\Controllers\LegalController::PAGES as $slug => $page)
                <a href="{{ route('legal', $slug) }}">{{ __($page[0]) }}</a>
            @endforeach
        </div>
        <div class="footer-bottom">
            © {{ date('Y') }} {{ setting('site_adi') }}. {{ __('site.footer.rights') }}
        </div>
    </div>
</footer>
