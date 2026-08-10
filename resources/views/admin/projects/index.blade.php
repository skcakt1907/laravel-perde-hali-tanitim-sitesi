@extends('admin.layout')
@section('title', 'Yapılan İşler')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Yapılan İşler</h1>
        <div class="page-subtitle">Galeri (Referenzen) — {{ $projects->total() }} kayıt</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('gallery') }}" target="_blank" rel="noopener" class="btn btn-secondary">
            <i data-lucide="external-link"></i> Galeriyi gör
        </a>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary"><i data-lucide="plus"></i> Yeni iş</a>
    </div>
</div>

<div class="table-wrap">
    <div class="table-scroll">
        <table class="data-table">
            <thead>
            <tr><th style="width:64px"></th><th>Başlık (ana dil)</th><th>Çeviriler</th><th>Tür</th><th>Yer</th><th>Tarih</th><th>Durum</th><th>İşlem</th></tr>
            </thead>
            <tbody>
            @forelse($projects as $p)
                <tr>
                    <td><img src="{{ $p->image_url }}" class="thumb" alt=""></td>
                    <td>
                        <div class="cell-strong">{{ $p->title }}</div>
                        @if($p->featured)
                            <div class="cell-sub"><i data-lucide="star" style="width:11px;height:11px"></i> öne çıkan</div>
                        @endif
                    </td>
                    <td>@include('admin._partials.lang-status', ['model' => $p, 'field' => 'title'])</td>
                    <td>@if($p->kind)<span class="badge badge-brand">{{ $p->kind }}</span>@else — @endif</td>
                    <td>{{ $p->location ?: '—' }}</td>
                    <td>{{ optional($p->tarih)->format('m.Y') ?: '—' }}</td>
                    <td>
                        <span class="badge {{ $p->durum ? 'badge-success' : 'badge-danger' }}">
                            {{ $p->durum ? 'Yayında' : 'Pasif' }}
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('gallery.show', $p) }}" target="_blank" rel="noopener" class="table-action" title="Sitede gör">
                                <i data-lucide="eye"></i>
                            </a>
                            <a href="{{ route('admin.projects.edit', $p) }}" class="table-action" title="Düzenle">
                                <i data-lucide="pencil"></i>
                            </a>
                            <form action="{{ route('admin.projects.destroy', $p) }}" method="POST"
                                  onsubmit="return confirm('{{ $p->title }} silinsin mi?')" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="table-action danger" title="Sil"><i data-lucide="trash-2"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8"><div class="table-empty"><i data-lucide="images"></i>Henüz iş eklenmedi.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($projects->hasPages())
        <div>{{ $projects->links() }}</div>
    @endif
</div>
@endsection
