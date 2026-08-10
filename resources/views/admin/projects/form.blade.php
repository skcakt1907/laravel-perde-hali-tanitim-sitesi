@extends('admin.layout')
@section('title', $project->exists ? 'İşi Düzenle' : 'Yeni İş')

@section('content')
<form action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
      method="POST" enctype="multipart/form-data" class="form-a">
    @csrf
    @if($project->exists)@method('PUT')@endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-a">
                <div class="lang-box">
                    <span class="lang-tag">DE — Almanca</span>
                    <label>Başlık *</label>
                    <input name="title" value="{{ old('title', $project->title) }}" required placeholder="z. B. Plissees für ein Reihenhaus">

                    <label>İşin türü (galeri filtresi)</label>
                    <input name="kind" value="{{ old('kind', $project->kind) }}" placeholder="Plissee / Rollo / Teppich">

                    <label>Özet</label>
                    <textarea name="summary" rows="3">{{ old('summary', $project->summary) }}</textarea>

                    <label>Detay metni</label>
                    <textarea name="content" rows="7">{{ old('content', $project->content) }}</textarea>
                </div>

                <div class="lang-box tr">
                    <span class="lang-tag">TR — Türkçe</span>
                    <label>Başlık</label>
                    <input name="title_tr" value="{{ old('title_tr', $project->title_tr) }}">

                    <label>İşin türü</label>
                    <input name="kind_tr" value="{{ old('kind_tr', $project->kind_tr) }}" placeholder="Plise / Stor / Halı">

                    <label>Özet</label>
                    <textarea name="summary_tr" rows="3">{{ old('summary_tr', $project->summary_tr) }}</textarea>

                    <label>Detay metni</label>
                    <textarea name="content_tr" rows="7">{{ old('content_tr', $project->content_tr) }}</textarea>
                    <div class="hint">Boş bırakılırsa sitede Almanca metin gösterilir.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-a">
                <label>Yer (şehir / semt)</label>
                <input name="location" value="{{ old('location', $project->location) }}" placeholder="Amsterdam">

                <label>Tarih</label>
                <input type="date" name="tarih" value="{{ old('tarih', optional($project->tarih)->format('Y-m-d')) }}">

                <label>Sıra</label>
                <input type="number" min="0" name="sira" value="{{ old('sira', $project->sira ?? 0) }}">

                <label class="mt-3"><input type="checkbox" name="featured" value="1" @checked(old('featured', $project->featured)) style="width:auto"> Anasayfada öne çıkar</label>
                <label><input type="checkbox" name="durum" value="1" @checked(old('durum', $project->durum ?? true)) style="width:auto"> Yayında</label>
            </div>

            <div class="card-a mt-3">
                <label>Kapak görseli</label>
                @if($project->cover)
                    <img src="{{ $project->cover }}" style="width:100%;border-radius:10px;margin-bottom:.6rem" alt="">
                @endif
                <label>Görsel URL</label>
                <input name="cover" value="{{ old('cover', $project->cover) }}" placeholder="https://...">
                <label>veya dosya yükle</label>
                <input type="file" name="image_file" accept="image/*">

                <label class="mt-3">Galeri</label>
                @foreach($project->images ?? [] as $img)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <img src="{{ $img }}" class="thumb" alt="">
                        <label class="m-0" style="font-weight:500;font-size:.85rem">
                            <input type="checkbox" name="keep_images[]" value="{{ $img }}" checked style="width:auto"> Kalsın
                        </label>
                    </div>
                @endforeach
                <input type="file" name="gallery_files[]" accept="image/*" multiple>
                <div class="hint">Birden fazla dosya seçebilirsiniz.</div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn-a"><i class="bi bi-check-lg"></i> Kaydet</button>
        <a href="{{ route('admin.projects.index') }}" class="btn-a sec">Vazgeç</a>
    </div>
</form>
@endsection
