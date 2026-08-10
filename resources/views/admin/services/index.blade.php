@extends('admin.layout')
@section('title', 'Hizmetler')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Hizmetler</h1>
        <div class="page-subtitle">Anasayfa ve Leistungen sayfasındaki hizmet kartları</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary"><i data-lucide="plus"></i> Yeni hizmet</a>
    </div>
</div>

<div class="table-wrap">
    <div class="table-scroll">
        <table class="data-table">
            <thead>
            <tr><th style="width:64px">Sıra</th><th style="width:56px">İkon</th><th>Başlık (ana dil)</th><th>Çeviriler</th><th>Durum</th><th>İşlem</th></tr>
            </thead>
            <tbody>
            @forelse($services as $s)
                <tr>
                    <td>{{ $s->sira }}</td>
                    <td><i class="bi {{ $s->icon ?: 'bi-check2-circle' }}" style="font-size:20px;color:var(--brand)"></i></td>
                    <td>
                        <div class="cell-strong">{{ $s->title }}</div>
                        <div class="cell-sub"><code>{{ $s->slug }}</code></div>
                    </td>
                    <td>@include('admin._partials.lang-status', ['model' => $s, 'field' => 'title'])</td>
                    <td>
                        <span class="badge {{ $s->durum ? 'badge-success' : 'badge-danger' }}">
                            {{ $s->durum ? 'Yayında' : 'Pasif' }}
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('service.show', $s) }}" target="_blank" rel="noopener" class="table-action" title="Sitede gör">
                                <i data-lucide="eye"></i>
                            </a>
                            <a href="{{ route('admin.services.edit', $s) }}" class="table-action" title="Düzenle">
                                <i data-lucide="pencil"></i>
                            </a>
                            <form action="{{ route('admin.services.destroy', $s) }}" method="POST"
                                  onsubmit="return confirm('{{ $s->title }} silinsin mi?')" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="table-action danger" title="Sil"><i data-lucide="trash-2"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="table-empty"><i data-lucide="list-checks"></i>Hizmet yok.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
