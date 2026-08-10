@extends('admin.layout')
@section('title', 'Ürünler')

@section('content')
<div class="page-title-row">
    <form method="GET" class="d-flex gap-2">
        <input name="q" value="{{ request('q') }}" placeholder="Ürün ara..." style="padding:.5rem .8rem;border:1px solid var(--aline);border-radius:9px">
        <select name="kategori" onchange="this.form.submit()" style="padding:.5rem .8rem;border:1px solid var(--aline);border-radius:9px">
            <option value="">Tüm kategoriler</option>
            @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('kategori') == $c->id)>{{ $c->name }}</option>@endforeach
        </select>
        <button class="btn-a sec"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn-a"><i class="bi bi-plus-lg"></i> Yeni ürün</a>
</div>

<table class="table-a">
    <thead><tr><th></th><th>Ürün</th><th>Kategori</th><th>Başlangıç fiyatı</th><th>TR</th><th>Durum</th><th></th></tr></thead>
    <tbody>
    @forelse($products as $p)
        <tr>
            <td><img src="{{ $p->image_url }}" class="thumb" alt=""></td>
            <td>
                <strong>{{ $p->name }}</strong><br>
                <small class="text-muted">{{ $p->sku }} @if($p->featured)· ⭐ öne çıkan @endif</small>
            </td>
            <td>{{ $p->category?->name ?? '—' }}</td>
            <td>
                @if($p->has_price)
                    {{ money($p->price) }}@if($p->price_unit) / {{ $p->price_unit }}@endif
                @else
                    <span class="text-muted">Sorunuz</span>
                @endif
            </td>
            <td>
                @if(filled($p->name_tr))
                    <i class="bi bi-check-circle-fill" style="color:#15803d" title="Türkçe girildi"></i>
                @else
                    <i class="bi bi-dash-circle" style="color:#b45309" title="Türkçe boş — Almanca gösterilir"></i>
                @endif
            </td>
            <td><span class="pill {{ $p->durum ? 'kazanildi' : 'iptal' }}">{{ $p->durum ? 'Yayında' : 'Pasif' }}</span></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="{{ route('admin.products.edit', $p) }}" class="btn-a sec sm"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST" onsubmit="return confirm('Silinsin mi?')">
                        @csrf @method('DELETE')
                        <button class="btn-a danger sm"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted py-4">Ürün bulunamadı.</td></tr>
    @endforelse
    </tbody>
</table>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
