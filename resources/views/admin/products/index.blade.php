@extends('admin.layout')
@section('title', 'Ürünler')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Ürünler</h1>
        <div class="page-subtitle">{{ $products->total() }} model · fiyatı 0 olanlar sitede &ldquo;Preis auf Anfrage&rdquo; gösterir</div>
    </div>
    <div class="page-actions">
        <form method="GET" class="flex gap-2">
            <input name="q" value="{{ request('q') }}" class="form-input" style="width:190px" placeholder="Ürün ara…">
            <select name="kategori" class="form-select" style="width:auto" onchange="this.form.submit()">
                <option value="">Tüm kategoriler</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected(request('kategori') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-secondary"><i data-lucide="search"></i></button>
        </form>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i data-lucide="plus"></i> Yeni ürün</a>
    </div>
</div>

<div class="table-wrap">
    <div class="table-scroll">
        <table class="data-table">
            <thead>
            <tr><th style="width:64px"></th><th>Ürün</th><th>Kategori</th><th>Başlangıç fiyatı</th><th>Çeviriler</th><th>Durum</th><th>İşlem</th></tr>
            </thead>
            <tbody>
            @forelse($products as $p)
                <tr>
                    <td><img src="{{ $p->image_url }}" class="thumb" alt=""></td>
                    <td>
                        <div class="cell-strong">{{ $p->name }}</div>
                        <div class="cell-sub">
                            {{ $p->sku ?: $p->slug }}
                            @if($p->featured) · <i data-lucide="star" style="width:11px;height:11px"></i> öne çıkan @endif
                        </div>
                    </td>
                    <td>{{ $p->category?->name ?? '—' }}</td>
                    <td>
                        @if($p->has_price)
                            <span class="cell-strong">{{ money($p->price) }}</span>
                            @if($p->price_unit)<span class="text-muted"> / {{ $p->price_unit }}</span>@endif
                        @else
                            <span class="badge badge-neutral">Sorunuz</span>
                        @endif
                    </td>
                    <td>@include('admin._partials.lang-status', ['model' => $p, 'field' => 'name'])</td>
                    <td>
                        <span class="badge {{ $p->durum ? 'badge-success' : 'badge-danger' }}">
                            {{ $p->durum ? 'Yayında' : 'Pasif' }}
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('product', $p) }}" target="_blank" rel="noopener" class="table-action" title="Sitede gör">
                                <i data-lucide="eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $p) }}" class="table-action" title="Düzenle">
                                <i data-lucide="pencil"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $p) }}" method="POST"
                                  onsubmit="return confirm('{{ $p->name }} silinsin mi?')" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="table-action danger" title="Sil"><i data-lucide="trash-2"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7"><div class="table-empty"><i data-lucide="package"></i>Ürün bulunamadı.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div>{{ $products->links() }}</div>
    @endif
</div>
@endsection
