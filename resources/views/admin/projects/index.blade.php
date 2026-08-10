@extends('admin.layout')
@section('title', 'Yapılan İşler')

@section('content')
<div class="page-title-row">
    <h3 style="margin:0;font-size:1.1rem">Yapılan işler (galeri)</h3>
    <a href="{{ route('admin.projects.create') }}" class="btn-a"><i class="bi bi-plus-lg"></i> Yeni iş</a>
</div>

<table class="table-a">
    <thead><tr><th></th><th>Başlık (DE)</th><th>Başlık (TR)</th><th>Tür</th><th>Yer</th><th>Tarih</th><th>Durum</th><th></th></tr></thead>
    <tbody>
    @forelse($projects as $p)
        <tr>
            <td><img src="{{ $p->image_url }}" class="thumb" alt=""></td>
            <td>
                <strong>{{ $p->title }}</strong>
                @if($p->featured)<br><small class="text-muted">⭐ öne çıkan</small>@endif
            </td>
            <td>{{ $p->title_tr ?: '—' }}</td>
            <td>{{ $p->kind ?: '—' }}</td>
            <td>{{ $p->location ?: '—' }}</td>
            <td>{{ optional($p->tarih)->format('m.Y') ?: '—' }}</td>
            <td><span class="pill {{ $p->durum ? 'kazanildi' : 'iptal' }}">{{ $p->durum ? 'Yayında' : 'Pasif' }}</span></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="{{ route('admin.projects.edit', $p) }}" class="btn-a sec sm"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.projects.destroy', $p) }}" method="POST" onsubmit="return confirm('Silinsin mi?')">
                        @csrf @method('DELETE')
                        <button class="btn-a danger sm"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="8" class="text-center text-muted py-4">Henüz iş eklenmedi.</td></tr>
    @endforelse
    </tbody>
</table>
<div class="mt-3">{{ $projects->links() }}</div>
@endsection
