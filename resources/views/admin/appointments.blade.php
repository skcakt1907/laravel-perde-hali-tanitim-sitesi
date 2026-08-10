@extends('admin.layout')
@section('title', 'Ölçü Talepleri')

@section('content')
@php $durumlar = \App\Models\Appointment::DURUMLAR; @endphp

<div class="page-header">
    <div>
        <h1 class="page-title">Ölçü Talepleri</h1>
        <div class="page-subtitle">Kostenloses Aufmaß formundan gelen talepler — {{ $appointments->total() }} kayıt</div>
    </div>
</div>

<div class="chip-row mb-4">
    <a href="{{ route('admin.appointments.index') }}" class="chip {{ $status ? '' : 'active' }}">Tümü</a>
    @foreach($durumlar as $k => $v)
        <a href="{{ route('admin.appointments.index', ['durum' => $k]) }}"
           class="chip {{ $status === $k ? 'active' : '' }}">{{ $v }}</a>
    @endforeach
</div>

<div class="table-wrap">
    <div class="table-scroll">
        <table class="data-table" style="min-width:980px">
            <thead>
            <tr>
                <th>Müşteri</th><th>İletişim</th><th>İlgilendiği</th><th>Yer</th>
                <th>Tercih ettiği tarih</th><th>Not</th><th>Durum</th><th>Geldi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($appointments as $a)
                <tr>
                    <td>
                        <div class="cell-strong">{{ $a->name }}</div>
                        <div class="cell-sub"><span class="lang-pill">{{ strtoupper($a->locale) }}</span></div>
                    </td>
                    <td>
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $a->phone) }}" class="cell-strong">{{ $a->phone }}</a>
                        @if($a->email)<div class="cell-sub">{{ $a->email }}</div>@endif
                    </td>
                    <td>{{ $a->subject ?: '—' }}</td>
                    <td>
                        {{ trim($a->zip . ' ' . $a->city) ?: '—' }}
                        @if($a->address)<div class="cell-sub">{{ $a->address }}</div>@endif
                    </td>
                    <td>{{ optional($a->date)->format('d.m.Y') }} {{ $a->time }}</td>
                    <td style="max-width:240px;white-space:normal">
                        <span class="text-secondary">{{ \Illuminate\Support\Str::limit($a->note, 90) }}</span>
                    </td>
                    <td>
                        <form action="{{ route('admin.appointments.update', $a) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="status" class="table-select" onchange="this.form.submit()">
                                @foreach($durumlar as $k => $v)
                                    <option value="{{ $k }}" @selected($a->status === $k)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>{{ $a->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="8"><div class="table-empty"><i data-lucide="ruler"></i>Ölçü talebi yok.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($appointments->hasPages())
        <div>{{ $appointments->links() }}</div>
    @endif
</div>
@endsection
