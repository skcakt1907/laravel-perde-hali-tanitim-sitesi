@extends('admin.layout')
@section('title', 'Panel')

@section('content')
<div class="stat-grid">
    <div class="stat-a"><div class="ic"><i class="bi bi-rulers"></i></div><div><div class="v">{{ $apptNew }}</div><div class="l">Yeni ölçü talebi</div></div></div>
    <div class="stat-a"><div class="ic"><i class="bi bi-inboxes"></i></div><div><div class="v">{{ $apptTotal }}</div><div class="l">Toplam talep</div></div></div>
    <div class="stat-a"><div class="ic"><i class="bi bi-trophy"></i></div><div><div class="v">{{ $apptWon }}</div><div class="l">İşe dönüşen</div></div></div>
    <div class="stat-a"><div class="ic"><i class="bi bi-envelope"></i></div><div><div class="v">{{ $messageNew }}</div><div class="l">Okunmamış mesaj</div></div></div>
    <div class="stat-a"><div class="ic"><i class="bi bi-box-seam"></i></div><div><div class="v">{{ $productCount }}</div><div class="l">Ürün</div></div></div>
    <div class="stat-a"><div class="ic"><i class="bi bi-images"></i></div><div><div class="v">{{ $projectCount }}</div><div class="l">Yapılan iş</div></div></div>
</div>

<div class="card-a mb-4">
    <div class="page-title-row">
        <h3 style="margin:0;font-size:1.1rem">Son ölçü talepleri</h3>
        <a href="{{ route('admin.appointments.index') }}" class="btn-a sec sm">Tümü</a>
    </div>
    <table class="table-a">
        <thead><tr><th>Ad Soyad</th><th>Telefon</th><th>İlgi</th><th>Yer</th><th>Durum</th><th>Geldi</th></tr></thead>
        <tbody>
        @forelse($recentAppts as $a)
            <tr>
                <td><strong>{{ $a->name }}</strong>@if($a->email)<br><small class="text-muted">{{ $a->email }}</small>@endif</td>
                <td>{{ $a->phone }}</td>
                <td>{{ $a->subject ?: '—' }}</td>
                <td>{{ trim($a->zip . ' ' . $a->city) ?: '—' }}</td>
                <td><span class="pill {{ $a->status }}">{{ \App\Models\Appointment::DURUMLAR[$a->status] ?? $a->status }}</span></td>
                <td><small class="text-muted">{{ $a->created_at->format('d.m.Y H:i') }}</small></td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Henüz ölçü talebi yok.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card-a">
    <div class="page-title-row">
        <h3 style="margin:0;font-size:1.1rem">Son mesajlar</h3>
        <a href="{{ route('admin.messages.index') }}" class="btn-a sec sm">Tümü</a>
    </div>
    <table class="table-a">
        <thead><tr><th>Gönderen</th><th>Konu</th><th>Dil</th><th>Durum</th><th>Tarih</th></tr></thead>
        <tbody>
        @forelse($recentMsgs as $m)
            <tr>
                <td><strong>{{ $m->name }}</strong><br><small class="text-muted">{{ $m->email ?: $m->phone }}</small></td>
                <td>{{ $m->subject ?: '—' }}</td>
                <td><small class="text-muted">{{ strtoupper($m->locale) }}</small></td>
                <td><span class="pill {{ $m->read_at ? 'kazanildi' : 'yeni' }}">{{ $m->read_at ? 'Okundu' : 'Yeni' }}</span></td>
                <td><small class="text-muted">{{ $m->created_at->format('d.m.Y H:i') }}</small></td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Henüz mesaj yok.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
