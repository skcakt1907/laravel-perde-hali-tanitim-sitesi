@extends('admin.layout')
@section('title', $post->exists ? 'Yazıyı Düzenle' : 'Yeni Yazı')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $post->exists ? 'Yazıyı Düzenle' : 'Yeni Yazı' }}</h1>
        <div class="page-subtitle">{{ $post->exists ? $post->title : 'Rehber bölümüne yeni yazı ekleyin' }}</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-ghost"><i data-lucide="arrow-left"></i> Listeye dön</a>
    </div>
</div>

<form action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($post->exists)@method('PUT')@endif

    <div class="grid-8-4">
        <div class="card">
            @include('admin._partials.lang-fields', [
                'model'  => $post,
                'fields' => [
                    ['name' => 'title', 'label' => 'Başlık', 'required' => true,
                     'placeholder' => 'z. B. Vorhänge richtig ausmessen',
                     'placeholder_nl' => 'bijv. Gordijnen goed opmeten',
                     'placeholder_en' => 'e.g. Measuring curtains correctly',
                     'placeholder_tr' => 'örn. Fon perdeyi doğru ölçmek'],
                    ['name' => 'category', 'label' => 'Kategori etiketi',
                     'placeholder' => 'Ratgeber / Materialkunde / Teppiche',
                     'placeholder_nl' => 'Advies / Materiaalkennis / Tapijten',
                     'placeholder_en' => 'Guide / Materials / Rugs',
                     'placeholder_tr' => 'Rehber / Malzeme Bilgisi / Halı',
                     'help' => 'Görsel üstünde küçük etiket olarak çıkar.'],
                    ['name' => 'summary', 'label' => 'Özet', 'type' => 'textarea', 'rows' => 3,
                     'help' => 'Liste kartlarında ve yazının başında görünür.'],
                    ['name' => 'content', 'label' => 'Yazı metni', 'type' => 'textarea', 'rows' => 14],
                ],
            ])
        </div>

        <div>
            <div class="card mb-4">
                <div class="section-title"><i data-lucide="sliders-horizontal"></i> Yayın</div>

                <div class="form-group">
                    <label class="form-label">Yayın tarihi</label>
                    <input type="date" name="tarih" class="form-input"
                           value="{{ old('tarih', optional($post->tarih)->format('Y-m-d')) }}">
                    <div class="form-help">Liste en yeni tarihten eskiye sıralanır.</div>
                </div>

                <label class="form-check mb-0">
                    <input type="checkbox" name="durum" value="1" @checked(old('durum', $post->durum ?? true))>
                    Yayında
                </label>
            </div>

            <div class="card">
                <div class="section-title"><i data-lucide="image"></i> Kapak görseli</div>
                @if($post->image)
                    <img src="{{ media($post->image) }}" class="img-preview" alt="">
                @endif
                <div class="form-group">
                    <label class="form-label">Görsel URL</label>
                    <input name="image" class="form-input" value="{{ old('image', $post->image) }}" placeholder="https://…">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">veya dosya yükle</label>
                    <input type="file" name="image_file" accept="image/*" class="form-file">
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions-sticky">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Vazgeç</a>
        <button type="submit" class="btn btn-primary"><i data-lucide="check"></i> Kaydet</button>
    </div>
</form>
@endsection
