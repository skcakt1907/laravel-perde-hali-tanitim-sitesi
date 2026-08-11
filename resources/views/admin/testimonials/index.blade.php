@extends('admin.layout')
@section('title', 'Müşteri Yorumları')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Müşteri Yorumları</h1>
        <div class="page-subtitle">Anasayfa ve Hakkımızda sayfasında görünür — {{ $testimonials->total() }} yorum</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary"><i data-lucide="plus"></i> Yeni yorum</a>
    </div>
</div>

<div class="alert alert-warning">
    <i data-lucide="alert-triangle"></i>
    <div>
        Kurulumla gelen 3 yorum <strong>örnek metindir</strong>. Site yayına alınmadan önce
        gerçek müşteri yorumlarıyla değiştirilmeli — uydurma referans yayınlanmamalı.
    </div>
</div>

<div class="table-wrap">
    <div class="table-scroll">
        <table class="data-table">
            <thead>
            <tr><th>Müşteri</th><th>Yorum (ana dil)</th><th>Çeviriler</th><th>Puan</th><th>Durum</th><th>İşlem</th></tr>
            </thead>
            <tbody>
            @forelse($testimonials as $t)
                <tr>
                    <td>
                        <div class="cell-strong">{{ $t->name }}</div>
                        @if($t->title)<div class="cell-sub">{{ $t->title }}</div>@endif
                    </td>
                    <td style="max-width:420px;white-space:normal">
                        <span class="text-secondary">{{ \Illuminate\Support\Str::limit($t->comment, 150) }}</span>
                    </td>
                    <td>@include('admin._partials.lang-status', ['model' => $t, 'field' => 'comment'])</td>
                    <td style="white-space:nowrap;color:var(--brand)">
                        @for($i = 0; $i < 5; $i++){{ $i < $t->stars ? '★' : '☆' }}@endfor
                    </td>
                    <td>
                        <span class="badge {{ $t->durum ? 'badge-success' : 'badge-danger' }}">
                            {{ $t->durum ? 'Yayında' : 'Pasif' }}
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('admin.testimonials.edit', $t) }}" class="table-action" title="Düzenle">
                                <i data-lucide="pencil"></i>
                            </a>
                            <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST"
                                  onsubmit="return confirm('Yorum silinsin mi?')" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="table-action danger" title="Sil"><i data-lucide="trash-2"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="table-empty"><i data-lucide="message-square-quote"></i>Henüz yorum eklenmedi.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($testimonials->hasPages())
        <div>{{ $testimonials->links() }}</div>
    @endif
</div>
@endsection
