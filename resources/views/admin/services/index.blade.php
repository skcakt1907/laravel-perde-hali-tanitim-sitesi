@extends('admin.layout')
@section('title', 'Hizmetler')

@section('content')
<div class="page-title-row">
    <h3 style="margin:0;font-size:1.1rem">Hizmetler</h3>
    <a href="{{ route('admin.services.create') }}" class="btn-a"><i class="bi bi-plus-lg"></i> Yeni hizmet</a>
</div>

<table class="table-a">
    <thead><tr><th>Sıra</th><th>İkon</th><th>Başlık (DE)</th><th>Başlık (TR)</th><th>Durum</th><th></th></tr></thead>
    <tbody>
    @forelse($services as $s)
        <tr>
            <td>{{ $s->sira }}</td>
            <td><i class="bi {{ $s->icon ?: 'bi-check2-circle' }}" style="font-size:1.3rem;color:var(--ap)"></i></td>
            <td><strong>{{ $s->title }}</strong><br><small class="text-muted">{{ $s->slug }}</small></td>
            <td>{{ $s->title_tr ?: '—' }}</td>
            <td><span class="pill {{ $s->durum ? 'kazanildi' : 'iptal' }}">{{ $s->durum ? 'Yayında' : 'Pasif' }}</span></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="{{ route('admin.services.edit', $s) }}" class="btn-a sec sm"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.services.destroy', $s) }}" method="POST" onsubmit="return confirm('Silinsin mi?')">
                        @csrf @method('DELETE')
                        <button class="btn-a danger sm"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted py-4">Hizmet yok.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
