<div class="product-card">
    <a class="pc-img" href="{{ route('product', $product) }}">
        <img src="{{ $product->image_url }}" alt="{{ $product->t('name') }}" loading="lazy">
        @if($product->featured)
            <span class="pc-badge">{{ app()->getLocale() === 'tr' ? 'Öne çıkan' : 'Beliebt' }}</span>
        @endif
    </a>
    <div class="pc-body">
        @if($product->category)
            <div class="pc-brand">{{ $product->category->t('name') }}</div>
        @endif
        <h3 class="pc-title"><a href="{{ route('product', $product) }}">{{ $product->t('name') }}</a></h3>
        @if($short = $product->t('short_desc'))
            <p class="pc-desc">{{ Str::limit($short, 80) }}</p>
        @endif
        <div class="pc-price">
            @if($product->has_price)
                <span>{{ __('site.catalog.from') }}</span>
                <span class="now">{{ money($product->price) }}</span>
                @if($product->price_unit)<span>/ {{ $product->price_unit }}</span>@endif
            @else
                <span class="req">{{ __('site.catalog.on_request') }}</span>
            @endif
        </div>
        <a class="pc-add" href="{{ route('product', $product) }}">
            {{ __('site.cta.details') }} <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</div>
