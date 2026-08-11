@extends('admin.layout')
@section('title', 'Rehber Yazıları')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Rehber Yazıları</h1>
        <div class="page-subtitle">Sitedeki Ratgeber / Guide / Rehber bölümü — {{ $posts->total() }} yazı</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('blog') }}" target="_blank" rel="noopener" class="btn btn-secondary">
            <i data-lucide="external-link"></i> Bölümü gör
        </a>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary"><i data-lucide="plus"></i> Yeni yazı</a>
    </div>
</div>

<div class="table-wrap">
    <div class="table-scroll">
        <table class="data-table">
            <thead>
            <tr><th style="width:64px"></th><th>Başlık (ana dil)</th><th>Çeviriler</th><th>Kategori</th><th>Tarih</th><th>Durum</th><th>İşlem</th></tr>
            </thead>
            <tbody>
            @forelse($posts as $p)
                <tr>
                    <td><img src="{{ $p->image_url }}" class="thumb" alt=""></td>
                    <td>
                        <div class="cell-strong">{{ $p->title }}</div>
                        <div class="cell-sub"><code>{{ $p->slug }}</code></div>
                    </td>
                    <td>@include('admin._partials.lang-status', ['model' => $p, 'field' => 'title'])</td>
                    <td>@if($p->category)<span class="badge badge-brand">{{ $p->category }}</span>@else — @endif</td>
                    <td>{{ optional($p->tarih)->format('d.m.Y') ?: '—' }}</td>
                    <td>
                        <span class="badge {{ $p->durum ? 'badge-success' : 'badge-danger' }}">
                            {{ $p->durum ? 'Yayında' : 'Pasif' }}
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('blog.show', $p) }}" target="_blank" rel="noopener" class="table-action" title="Sitede gör">
                                <i data-lucide="eye"></i>
                            </a>
                            <a href="{{ route('admin.posts.edit', $p) }}" class="table-action" title="Düzenle">
                                <i data-lucide="pencil"></i>
                            </a>
                            <form action="{{ route('admin.posts.destroy', $p) }}" method="POST"
                                  onsubmit="return confirm('Yazı silinsin mi?')" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="table-action danger" title="Sil"><i data-lucide="trash-2"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7"><div class="table-empty"><i data-lucide="newspaper"></i>Henüz yazı eklenmedi.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($posts->hasPages())
        <div>{{ $posts->links() }}</div>
    @endif
</div>
@endsection
