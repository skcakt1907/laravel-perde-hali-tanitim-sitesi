{{--
Liste ekranlarında çeviri durumu rozetleri: her ikincil dil için
girildi / boş gösterir. Diller App\Support\Locales'ten gelir.

Kullanım: @include('admin._partials.lang-status', ['model' => $p, 'field' => 'name'])
--}}
<div class="flex gap-2" style="flex-wrap:wrap">
    @foreach(\App\Support\Locales::secondary() as $code)
        @php $dolu = filled($model->{$field . '_' . $code}); @endphp
        <span class="badge {{ $dolu ? 'badge-success' : 'badge-warning' }}"
              title="{{ \App\Support\Locales::label($code) }}: {{ $dolu ? 'girildi' : 'boş — ana dil gösterilir' }}">
            {{ strtoupper($code) }}
        </span>
    @endforeach
</div>
