@extends('admin.layout')
@section('title', 'İletişim Mesajları')

@section('content')
<table class="table-a">
    <thead><tr><th>Gönderen</th><th>Konu / Mesaj</th><th>Dil</th><th>Tarih</th><th>Durum</th><th></th></tr></thead>
    <tbody>
    @forelse($messages as $m)
        <tr style="{{ $m->read_at ? '' : 'background:#faf6e9' }}">
            <td>
                <strong>{{ $m->name }}</strong>
                @if($m->email)<br><small class="text-muted">{{ $m->email }}</small>@endif
                @if($m->phone)<br><small class="text-muted">{{ $m->phone }}</small>@endif
            </td>
            <td>
                @if($m->subject)<strong>{{ $m->subject }}</strong><br>@endif
                <small>{{ \Illuminate\Support\Str::limit($m->message, 140) }}</small>
            </td>
            <td><small class="text-muted">{{ strtoupper($m->locale) }}</small></td>
            <td><small class="text-muted">{{ $m->created_at->format('d.m.Y H:i') }}</small></td>
            <td>
                @if($m->read_at)
                    <span class="badge bg-secondary">Okundu</span>
                @else
                    <span class="badge bg-success">Yeni</span>
                @endif
            </td>
            <td class="d-flex gap-1">
                <form action="{{ route('admin.messages.update', $m) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm btn-outline-secondary" title="{{ $m->read_at ? 'Okunmadı işaretle' : 'Okundu işaretle' }}">
                        <i class="bi {{ $m->read_at ? 'bi-envelope' : 'bi-envelope-open' }}"></i>
                    </button>
                </form>
                <form action="{{ route('admin.messages.destroy', $m) }}" method="POST" onsubmit="return confirm('Mesaj silinsin mi?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted py-4">Henüz iletişim mesajı yok.</td></tr>
    @endforelse
    </tbody>
</table>
<div class="mt-3">{{ $messages->links() }}</div>
@endsection
