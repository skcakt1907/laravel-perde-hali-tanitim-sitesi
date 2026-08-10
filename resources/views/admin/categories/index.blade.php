@extends('admin.layout')
@section('title', 'Kategoriler')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Kategoriler</h1>
        <div class="page-subtitle">Menüdeki ürün grupları — sıralama menüde de geçerlidir</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i data-lucide="plus"></i> Yeni kategori</a>
    </div>
</div>

<div class="table-wrap">
    <div class="table-scroll">
        <table class="data-table">
            <thead>
            <tr><th style="width:64px">Sıra</th><th style="width:56px">İkon</th><th>Ad (DE)</th><th>Ad (TR)</th><th>Slug</th><th>Ürün</th><th>Durum</th><th>İşlem</th></tr>
            </thead>
            <tbody>
            @forelse($categories as $c)
                <tr>
                    <td>{{ $c->sira }}</td>
                    <td><i class="bi {{ $c->icon ?: 'bi-tag' }}" style="font-size:20px;color:var(--brand)"></i></td>
                    <td><span class="cell-strong">{{ $c->name }}</span></td>
                    <td>{{ $c->name_tr ?: '—' }}</td>
                    <td><code>{{ $c->slug }}</code></td>
                    <td>{{ $c->products_count }}</td>
                    <td>
                        <span class="badge {{ $c->durum ? 'badge-success' : 'badge-danger' }}">
                            {{ $c->durum ? 'Yayında' : 'Pasif' }}
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('catalog.category', $c) }}" target="_blank" rel="noopener" class="table-action" title="Sitede gör">
                                <i data-lucide="eye"></i>
                            </a>
                            <a href="{{ route('admin.categories.edit', $c) }}" class="table-action" title="Düzenle">
                                <i data-lucide="pencil"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $c) }}" method="POST"
                                  onsubmit="return confirm('{{ $c->name }} silinsin mi? Ürünleri kategorisiz kalır.')" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="table-action danger" title="Sil"><i data-lucide="trash-2"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8"><div class="table-empty"><i data-lucide="tags"></i>Kategori yok.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
