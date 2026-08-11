@extends('admin.layout')
@section('title', $testimonial->exists ? 'Yorumu Düzenle' : 'Yeni Yorum')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $testimonial->exists ? 'Yorumu Düzenle' : 'Yeni Yorum' }}</h1>
        <div class="page-subtitle">{{ $testimonial->exists ? $testimonial->name : 'Gerçek bir müşteri yorumu ekleyin' }}</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-ghost"><i data-lucide="arrow-left"></i> Listeye dön</a>
    </div>
</div>

<form action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($testimonial->exists)@method('PUT')@endif

    <div class="grid-8-4">
        <div class="card">
            @include('admin._partials.lang-fields', [
                'model'  => $testimonial,
                'fields' => [
                    ['name' => 'title', 'label' => 'Alt bilgi (şehir / iş türü)',
                     'placeholder' => 'Amsterdam',
                     'help' => 'Müşteri adının altında küçük yazı olarak çıkar. Genelde şehir yazılır.'],
                    ['name' => 'comment', 'label' => 'Yorum metni', 'required' => true,
                     'type' => 'textarea', 'rows' => 5],
                ],
            ])
        </div>

        <div>
            <div class="card mb-4">
                <div class="section-title"><i data-lucide="user"></i> Müşteri</div>

                <div class="form-group">
                    <label class="form-label">Ad <span class="required">*</span></label>
                    <input name="name" class="form-input" value="{{ old('name', $testimonial->name) }}" required
                           placeholder="Familie V. / J. de Boer">
                    <div class="form-help">Tam ad yerine baş harf kullanmak KVKK/GDPR açısından daha güvenli.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Puan <span class="required">*</span></label>
                    <select name="stars" class="form-select">
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(old('stars', $testimonial->stars ?? 5) == $i)>
                                {{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }} ({{ $i }})
                            </option>
                        @endfor
                    </select>
                </div>

                <label class="form-check mb-0">
                    <input type="checkbox" name="durum" value="1" @checked(old('durum', $testimonial->durum ?? true))>
                    Yayında
                </label>
            </div>

            <div class="card">
                <div class="section-title"><i data-lucide="image"></i> Fotoğraf</div>
                @if($testimonial->photo)
                    <img src="{{ media($testimonial->photo) }}" class="img-preview" style="aspect-ratio:1/1" alt="">
                @endif
                <div class="form-group">
                    <label class="form-label">Görsel URL</label>
                    <input name="photo" class="form-input" value="{{ old('photo', $testimonial->photo) }}" placeholder="https://…">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">veya dosya yükle</label>
                    <input type="file" name="image_file" accept="image/*" class="form-file">
                    <div class="form-help">Boş bırakılırsa adın ilk harfi daire içinde gösterilir.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions-sticky">
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">Vazgeç</a>
        <button type="submit" class="btn btn-primary"><i data-lucide="check"></i> Kaydet</button>
    </div>
</form>
@endsection
