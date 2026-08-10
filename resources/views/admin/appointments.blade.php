@extends('admin.layout')
@section('title', 'Ölçü Talepleri')

@section('content')
<div class="page-title-row">
    <div class="d-flex gap-1 flex-wrap">
        <a href="{{ route('admin.appointments.index') }}" class="btn-a {{ $status ? 'sec' : '' }} sm">Tümü</a>
        @foreach(\App\Models\Appointment::DURUMLAR as $k => $v)
            <a href="{{ route('admin.appointments.index', ['durum' => $k]) }}"
               class="btn-a {{ $status === $k ? '' : 'sec' }} sm">{{ $v }}</a>
        @endforeach
    </div>
</div>

<table class="table-a">
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
                <strong>{{ $a->name }}</strong><br>
                <small class="text-muted">{{ strtoupper($a->locale) }}</small>
            </td>
            <td>
                <a href="tel:{{ preg_replace('/[^\d+]/', '', $a->phone) }}">{{ $a->phone }}</a>
                @if($a->email)<br><small class="text-muted">{{ $a->email }}</small>@endif
            </td>
            <td>{{ $a->subject ?: '—' }}</td>
            <td>
                {{ trim($a->zip . ' ' . $a->city) ?: '—' }}
                @if($a->address)<br><small class="text-muted">{{ $a->address }}</small>@endif
            </td>
            <td>{{ optional($a->date)->format('d.m.Y') }} {{ $a->time }}</td>
            <td><small>{{ \Illuminate\Support\Str::limit($a->note, 70) }}</small></td>
            <td>
                <form action="{{ route('admin.appointments.update', $a) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="status" onchange="this.form.submit()"
                            style="padding:.3rem .5rem;border:1px solid var(--aline);border-radius:7px;font-size:.82rem">
                        @foreach(\App\Models\Appointment::DURUMLAR as $k => $v)
                            <option value="{{ $k }}" @selected($a->status === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </form>
            </td>
            <td><small class="text-muted">{{ $a->created_at->format('d.m.Y H:i') }}</small></td>
        </tr>
    @empty
        <tr><td colspan="8" class="text-center text-muted py-4">Ölçü talebi yok.</td></tr>
    @endforelse
    </tbody>
</table>
<div class="mt-3">{{ $appointments->links() }}</div>
@endsection
