@extends('admin.layout')
@section('title', 'Panel')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Panel</h1>
        <div class="page-subtitle">Ölçü talepleri, mesajlar ve katalog özeti</div>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.products.create') }}" class="btn btn-secondary">
            <i data-lucide="plus"></i> Yeni ürün
        </a>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-primary">
            <i data-lucide="ruler"></i> Ölçü talepleri
        </a>
    </div>
</div>

<div class="stat-grid">
    @foreach([
        ['ruler',    '',           $apptNew,      'Yeni ölçü talebi'],
        ['inbox',    'is-purple',  $apptTotal,    'Toplam talep'],
        ['trophy',   'is-success', $apptWon,      'İşe dönüşen'],
        ['mail',     'is-warning', $messageNew,   'Okunmamış mesaj'],
        ['package',  '',           $productCount, 'Ürün'],
        ['images',   'is-purple',  $projectCount, 'Yapılan iş'],
    ] as [$icon, $tone, $value, $label])
        <div class="stat-card">
            <div class="stat-card-icon {{ $tone }}"><i data-lucide="{{ $icon }}"></i></div>
            <div class="stat-card-body">
                <div class="stat-card-label">{{ $label }}</div>
                <div class="stat-card-value">{{ $value }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="table-wrap mb-6">
    <div class="card-header" style="padding:16px 20px;margin:0">
        <div class="card-title"><i data-lucide="ruler"></i> Son ölçü talepleri</div>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-ghost btn-sm">
            Tümü <i data-lucide="arrow-right"></i>
        </a>
    </div>
    <div class="table-scroll">
        <table class="data-table">
            <thead>
            <tr><th>Müşteri</th><th>Telefon</th><th>İlgilendiği</th><th>Yer</th><th>Durum</th><th>Geldi</th></tr>
            </thead>
            <tbody>
            @forelse($recentAppts as $a)
                <tr>
                    <td>
                        <div class="cell-strong">{{ $a->name }}</div>
                        <div class="cell-sub">
                            <span class="lang-pill">{{ strtoupper($a->locale) }}</span>
                            @if($a->email) {{ $a->email }} @endif
                        </div>
                    </td>
                    <td>{{ $a->phone }}</td>
                    <td>{{ $a->subject ?: '—' }}</td>
                    <td>{{ trim($a->zip . ' ' . $a->city) ?: '—' }}</td>
                    <td><span class="badge badge-{{ $a->status }}">{{ \App\Models\Appointment::DURUMLAR[$a->status] ?? $a->status }}</span></td>
                    <td>{{ $a->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="table-empty"><i data-lucide="inbox"></i>Henüz ölçü talebi yok.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="table-wrap">
    <div class="card-header" style="padding:16px 20px;margin:0">
        <div class="card-title"><i data-lucide="mail"></i> Son mesajlar</div>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-ghost btn-sm">
            Tümü <i data-lucide="arrow-right"></i>
        </a>
    </div>
    <div class="table-scroll">
        <table class="data-table">
            <thead>
            <tr><th>Gönderen</th><th>Konu</th><th>Dil</th><th>Durum</th><th>Tarih</th></tr>
            </thead>
            <tbody>
            @forelse($recentMsgs as $m)
                <tr class="{{ $m->read_at ? '' : 'unread' }}">
                    <td>
                        <div class="cell-strong">{{ $m->name }}</div>
                        <div class="cell-sub">{{ $m->email ?: $m->phone }}</div>
                    </td>
                    <td>{{ $m->subject ?: '—' }}</td>
                    <td><span class="lang-pill">{{ strtoupper($m->locale) }}</span></td>
                    <td>
                        <span class="badge {{ $m->read_at ? 'badge-neutral' : 'badge-brand' }}">
                            {{ $m->read_at ? 'Okundu' : 'Yeni' }}
                        </span>
                    </td>
                    <td>{{ $m->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="table-empty"><i data-lucide="mail"></i>Henüz mesaj yok.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
