@component('emails.layout', ['title' => 'Yeni ölçü talebi'])
<h1 style="font-size:22px;margin:0 0 6px;">Yeni Ücretsiz Ölçü Talebi</h1>
<p style="font-size:15px;line-height:1.6;color:#374151;margin:0 0 18px;">
    Web sitesindeki <strong>Kostenloses Aufmaß</strong> formundan yeni bir talep geldi.
    Talebin geldiği dil: <strong>{{ strtoupper($appointment->locale) }}</strong>
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;margin:0 0 18px;">
    <tr><td style="padding:4px 0;color:#6b7280;width:150px;">Ad Soyad</td><td style="padding:4px 0;">{{ $appointment->name }}</td></tr>
    <tr><td style="padding:4px 0;color:#6b7280;">Telefon</td><td style="padding:4px 0;">{{ $appointment->phone }}</td></tr>
    @if($appointment->email)<tr><td style="padding:4px 0;color:#6b7280;">E-posta</td><td style="padding:4px 0;">{{ $appointment->email }}</td></tr>@endif
    @if($appointment->subject)<tr><td style="padding:4px 0;color:#6b7280;">İlgilendiği ürün</td><td style="padding:4px 0;">{{ $appointment->subject }}</td></tr>@endif
    @if($appointment->zip || $appointment->city)<tr><td style="padding:4px 0;color:#6b7280;">Yer</td><td style="padding:4px 0;">{{ trim($appointment->zip . ' ' . $appointment->city) }}</td></tr>@endif
    @if($appointment->address)<tr><td style="padding:4px 0;color:#6b7280;">Adres</td><td style="padding:4px 0;">{{ $appointment->address }}</td></tr>@endif
    @if($appointment->date)<tr><td style="padding:4px 0;color:#6b7280;">Tercih ettiği tarih</td><td style="padding:4px 0;">{{ $appointment->date->format('d.m.Y') }} {{ $appointment->time }}</td></tr>@endif
</table>

@if($appointment->note)
    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px 18px;font-size:14px;line-height:1.7;color:#374151;white-space:pre-line;">{{ $appointment->note }}</div>
@endif
@endcomponent
