{{--
═══════════════════════════════════════════════════════════
ÇOK DİLLİ ALAN BLOKLARI
Her dil için bir `.lang-box` üretir; birincil dil temel kolonu,
diğerleri `_<kod>` sonekli kolonu kullanır. Diller App\Support\Locales'ten
gelir — yeni dil eklenince bu dosyaya da form dosyalarına da dokunmak gerekmez.

Kullanım:
@include('admin._partials.lang-fields', [
    'model'  => $product,
    'fields' => [
        ['name' => 'name',        'label' => 'Ürün adı', 'required' => true, 'placeholder' => 'z. B. …'],
        ['name' => 'description', 'label' => 'Açıklama', 'type' => 'textarea', 'rows' => 6],
    ],
])
═══════════════════════════════════════════════════════════
--}}
@php
    $primary = \App\Support\Locales::primary();
@endphp

@foreach(\App\Support\Locales::labels() as $code => $label)
    @php $isPrimary = $code === $primary; @endphp

    <div class="lang-box {{ $isPrimary ? '' : 'secondary' }}">
        <span class="lang-tag">
            {{ strtoupper($code) }} — {{ $label }}{{ $isPrimary ? ' (ana dil)' : '' }}
        </span>

        @foreach($fields as $field)
            @php
                $attr        = $isPrimary ? $field['name'] : $field['name'] . '_' . $code;
                $type        = $field['type'] ?? 'input';
                $required    = $isPrimary && ($field['required'] ?? false);
                $placeholder = $isPrimary ? ($field['placeholder'] ?? '') : ($field['placeholder_' . $code] ?? '');
                $value       = old($attr, $model->{$attr});
                $last        = $loop->last;
            @endphp

            <div class="form-group {{ $last ? 'mb-0' : '' }}">
                <label class="form-label">
                    {{ $field['label'] }}
                    @if($required)<span class="required">*</span>@endif
                </label>

                @if($type === 'textarea')
                    <textarea name="{{ $attr }}" class="form-textarea" rows="{{ $field['rows'] ?? 4 }}"
                              @if($placeholder) placeholder="{{ $placeholder }}" @endif
                              @if($required) required @endif>{{ $value }}</textarea>
                @else
                    <input type="text" name="{{ $attr }}" class="form-input" value="{{ $value }}"
                           @if($placeholder) placeholder="{{ $placeholder }}" @endif
                           @if($required) required @endif>
                @endif

                @if($last && ! $isPrimary)
                    <div class="form-help">
                        Boş bırakılan alanlar sitede <strong>{{ \App\Support\Locales::label($primary) }}</strong>
                        metinle gösterilir.
                    </div>
                @elseif(! empty($field['help']) && $isPrimary)
                    <div class="form-help">{!! $field['help'] !!}</div>
                @endif
            </div>
        @endforeach
    </div>
@endforeach
