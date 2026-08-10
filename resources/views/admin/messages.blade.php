@extends('admin.layout')
@section('title', 'Mesajlar')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Mesajlar</h1>
        <div class="page-subtitle">İletişim formundan gelen mesajlar — {{ $messages->total() }} kayıt</div>
    </div>
</div>

<div class="table-wrap">
    <div class="table-scroll">
        <table class="data-table" style="min-width:860px">
            <thead>
            <tr><th>Gönderen</th><th>Konu / Mesaj</th><th>Dil</th><th>Tarih</th><th>Durum</th><th>İşlem</th></tr>
            </thead>
            <tbody>
            @forelse($messages as $m)
                <tr class="{{ $m->read_at ? '' : 'unread' }}">
                    <td>
                        <div class="cell-strong">{{ $m->name }}</div>
                        @if($m->email)<div class="cell-sub">{{ $m->email }}</div>@endif
                        @if($m->phone)<div class="cell-sub">{{ $m->phone }}</div>@endif
                    </td>
                    <td style="max-width:420px;white-space:normal">
                        @if($m->subject)<div class="cell-strong">{{ $m->subject }}</div>@endif
                        <span class="text-secondary">{{ \Illuminate\Support\Str::limit($m->message, 180) }}</span>
                    </td>
                    <td><span class="lang-pill">{{ strtoupper($m->locale) }}</span></td>
                    <td>{{ $m->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <span class="badge {{ $m->read_at ? 'badge-neutral' : 'badge-brand' }}">
                            {{ $m->read_at ? 'Okundu' : 'Yeni' }}
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            @if($m->email)
                                <a href="mailto:{{ $m->email }}" class="table-action" title="E-posta ile yanıtla">
                                    <i data-lucide="reply"></i>
                                </a>
                            @endif
                            <form action="{{ route('admin.messages.update', $m) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="table-action" title="{{ $m->read_at ? 'Okunmadı işaretle' : 'Okundu işaretle' }}">
                                    <i data-lucide="{{ $m->read_at ? 'mail' : 'mail-open' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.messages.destroy', $m) }}" method="POST"
                                  onsubmit="return confirm('Mesaj silinsin mi?')" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="table-action danger" title="Sil"><i data-lucide="trash-2"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="table-empty"><i data-lucide="mail"></i>Henüz mesaj yok.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($messages->hasPages())
        <div>{{ $messages->links() }}</div>
    @endif
</div>
@endsection
