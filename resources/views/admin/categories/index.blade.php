@extends('admin.layout')
@section('title', 'Kategoriler')

@section('content')
<div class="page-title-row">
    <h3 style="margin:0;font-size:1.1rem">Kategoriler</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn-a"><i class="bi bi-plus-lg"></i> Yeni Kategori</a>
</div>

<table class="table-a">
    <thead><tr><th>Sıra</th><th>İkon</th><th>Ad (DE)</th><th>Ad (TR)</th><th>Slug</th><th>Ürün</th><th></th></tr></thead>
    <tbody>
    @forelse($categories as $c)
        <tr>
            <td>{{ $c->sira }}</td>
            <td><i class="bi {{ $c->icon ?: 'bi-tag' }}" style="font-size:1.3rem;color:var(--ap)"></i></td>
            <td><strong>{{ $c->name }}</strong></td>
            <td>{{ $c->name_tr ?: '—' }}</td>
            <td><small class="text-muted">{{ $c->slug }}</small></td>
            <td>{{ $c->products_count }}</td>
            <td>
                <div class="d-flex gap-1">
                    <a href="{{ route('admin.categories.edit', $c) }}" class="btn-a sec sm"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" onsubmit="return confirm('Silinsin mi?')">@csrf @method('DELETE')<button class="btn-a danger sm"><i class="bi bi-trash"></i></button></form>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted py-4">Kategori yok.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
