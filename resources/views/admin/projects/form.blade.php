@extends('admin.layout')
@section('title', $project->exists ? 'İşi Düzenle' : 'Yeni İş')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $project->exists ? 'İşi Düzenle' : 'Yeni İş' }}</h1>
        <div class="page-subtitle">{{ $project->exists ? $project->title : 'Galeriye yeni referans işi ekleyin' }}</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.projects.index') }}" class="btn btn-ghost"><i data-lucide="arrow-left"></i> Listeye dön</a>
    </div>
</div>

<form action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($project->exists)@method('PUT')@endif

    <div class="grid-8-4">
        <div class="card">
            @include('admin._partials.lang-fields', [
                'model'  => $project,
                'fields' => [
                    ['name' => 'title', 'label' => 'Başlık', 'required' => true,
                     'placeholder' => 'z. B. Plissees für ein Reihenhaus',
                     'placeholder_nl' => 'bijv. Plissés voor een rijtjeshuis',
                     'placeholder_en' => 'e.g. Pleated blinds for a terraced house',
                     'placeholder_tr' => 'örn. Sıra evde plise perde'],
                    ['name' => 'kind', 'label' => 'İşin türü (galeri filtresi)',
                     'placeholder' => 'Plissees / Rollos / Teppiche',
                     'placeholder_nl' => 'Plisségordijnen / Rolgordijnen / Tapijten',
                     'placeholder_en' => 'Pleated Blinds / Roller Blinds / Rugs',
                     'placeholder_tr' => 'Plise / Stor / Halı',
                     'help' => 'Galeri sayfasındaki filtre çipleri bu değerden üretilir — mevcutlarla aynı yazın.'],
                    ['name' => 'summary', 'label' => 'Özet', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'content', 'label' => 'Detay metni', 'type' => 'textarea', 'rows' => 7],
                ],
            ])
        </div>

        <div>
            <div class="card mb-4">
                <div class="section-title"><i data-lucide="map-pin"></i> İş bilgileri</div>

                <div class="form-group">
                    <label class="form-label">Yer (şehir / semt)</label>
                    <input name="location" class="form-input" value="{{ old('location', $project->location) }}" placeholder="Amsterdam">
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Tarih</label>
                        <input type="date" name="tarih" class="form-input"
                               value="{{ old('tarih', optional($project->tarih)->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sıra</label>
                        <input type="number" min="0" name="sira" class="form-input" value="{{ old('sira', $project->sira ?? 0) }}">
                    </div>
                </div>

                <label class="form-check">
                    <input type="checkbox" name="featured" value="1" @checked(old('featured', $project->featured))>
                    Anasayfada öne çıkar
                </label>
                <label class="form-check mb-0">
                    <input type="checkbox" name="durum" value="1" @checked(old('durum', $project->durum ?? true))>
                    Yayında
                </label>
            </div>

            <div class="card">
                <div class="section-title"><i data-lucide="image"></i> Görseller</div>

                @if($project->cover)
                    <img src="{{ media($project->cover) }}" class="img-preview" alt="">
                @endif

                <div class="form-group">
                    <label class="form-label">Kapak görseli (URL)</label>
                    <input name="cover" class="form-input" value="{{ old('cover', $project->cover) }}" placeholder="https://…">
                </div>
                <div class="form-group">
                    <label class="form-label">veya dosya yükle</label>
                    <input type="file" name="image_file" accept="image/*" class="form-file">
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Galeri</label>
                    @if($project->images)
                        <div class="gallery-keep">
                            @foreach($project->images as $img)
                                <div class="gallery-keep-item">
                                    <img src="{{ media($img) }}" alt="">
                                    <label><input type="checkbox" name="keep_images[]" value="{{ $img }}" checked> Kalsın</label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <input type="file" name="gallery_files[]" accept="image/*" multiple class="form-file">
                    <div class="form-help">Birden fazla dosya seçebilirsiniz.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions-sticky">
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Vazgeç</a>
        <button type="submit" class="btn btn-primary"><i data-lucide="check"></i> Kaydet</button>
    </div>
</form>
@endsection
