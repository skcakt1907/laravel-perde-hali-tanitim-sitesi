{{--
═══════════════════════════════════════════════════════════
AYARLARDA ÇOK DİLLİ ALAN GRUBU (dil sekmeli)
Diller App\Support\Locales'ten gelir; yeni dil eklenince bu dosyaya
ve ayar sayfalarına dokunmak gerekmez.

Kullanım:
@include('admin.ayarlar._partials.lang-tabs', [
    'group'    => 'hero',
    'settings' => $settings,
    'fields'   => [
        ['name' => 'hero_baslik', 'label' => 'Başlık', 'type' => 'textarea', 'rows' => 2],
        ['name' => 'hero_metin',  'label' => 'Metin',  'type' => 'textarea', 'rows' => 3],
    ],
])
═══════════════════════════════════════════════════════════
--}}
@php
    $primary = \App\Support\Locales::primary();
    $locales = \App\Support\Locales::labels();
@endphp

<div class="lang-tabs">
    @foreach($locales as $code => $label)
        <button type="button"
                class="lang-tab {{ $code === $primary ? 'active' : '' }}"
                data-lang-tab="{{ $group }}" data-locale="{{ $code }}"
                onclick="langTab('{{ $group }}', '{{ $code }}')">
            <span class="flag">{{ strtoupper($code) }}</span> {{ $label }}
        </button>
    @endforeach
</div>

@foreach($locales as $code => $label)
    <div class="lang-panel" data-lang-panel="{{ $group }}" data-locale="{{ $code }}"
         @if($code !== $primary) hidden @endif>
        @foreach($fields as $field)
            @php
                $key         = $code === $primary ? $field['name'] : $field['name'] . '_' . $code;
                $type        = $field['type'] ?? 'input';
                $value       = old($key, $settings[$key] ?? '');
                $placeholder = $field['placeholder_' . $code] ?? ($code === $primary ? ($field['placeholder'] ?? '') : '');
                $counter     = ! empty($field['counter']) ? $field['name'] . '-' . $code : null;
                $max         = $field['counter'] ?? 160;
            @endphp

            <div class="form-group {{ $loop->last ? 'mb-0' : '' }}">
                <label class="form-label">{{ $field['label'] }} ({{ $label }})</label>

                @if($type === 'textarea')
                    <textarea name="{{ $key }}" rows="{{ $field['rows'] ?? 3 }}" class="form-textarea"
                              @if($placeholder) placeholder="{{ $placeholder }}" @endif
                              @if($counter)
                                  data-counter="{{ $counter }}" data-counter-max="{{ $max }}"
                                  onkeyup="updateCharCounter(this, '{{ $counter }}', {{ $max }})"
                              @endif>{{ $value }}</textarea>
                @else
                    <input type="text" name="{{ $key }}" class="form-input" value="{{ $value }}"
                           @if($placeholder) placeholder="{{ $placeholder }}" @endif>
                @endif

                @if($counter)
                    <div class="char-counter" id="{{ $counter }}">0 / {{ $max }}</div>
                @endif

                @if(! empty($field['help']))
                    <div class="form-help">{!! $field['help'] !!}</div>
                @endif
            </div>
        @endforeach
    </div>
@endforeach
