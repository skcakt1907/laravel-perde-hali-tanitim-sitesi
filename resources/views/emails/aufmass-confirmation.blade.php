@component('emails.layout', ['title' => __('mail.aufmass.subject')])
<h1 style="font-size:21px;margin:0 0 12px;">{{ __('mail.aufmass.greeting', ['name' => $appointment->name]) }}</h1>

<p style="font-size:15px;line-height:1.65;color:#374151;margin:0 0 20px;">
    {{ __('mail.aufmass.intro') }}
</p>

<p style="font-size:13px;font-weight:bold;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin:0 0 8px;">
    {{ __('mail.aufmass.summary') }}
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;margin:0 0 20px;">
    <tr><td style="padding:4px 0;color:#6b7280;width:170px;">{{ __('mail.fields.name') }}</td><td style="padding:4px 0;">{{ $appointment->name }}</td></tr>
    <tr><td style="padding:4px 0;color:#6b7280;">{{ __('mail.fields.phone') }}</td><td style="padding:4px 0;">{{ $appointment->phone }}</td></tr>
    @if($appointment->email)<tr><td style="padding:4px 0;color:#6b7280;">{{ __('mail.fields.email') }}</td><td style="padding:4px 0;">{{ $appointment->email }}</td></tr>@endif
    @if($appointment->subject)<tr><td style="padding:4px 0;color:#6b7280;">{{ __('mail.fields.subject') }}</td><td style="padding:4px 0;">{{ $appointment->subject }}</td></tr>@endif
    @if($appointment->zip || $appointment->city)<tr><td style="padding:4px 0;color:#6b7280;">{{ __('mail.fields.place') }}</td><td style="padding:4px 0;">{{ trim($appointment->zip . ' ' . $appointment->city) }}</td></tr>@endif
    @if($appointment->address)<tr><td style="padding:4px 0;color:#6b7280;">{{ __('mail.fields.address') }}</td><td style="padding:4px 0;">{{ $appointment->address }}</td></tr>@endif
    @if($appointment->date)<tr><td style="padding:4px 0;color:#6b7280;">{{ __('mail.fields.date') }}</td><td style="padding:4px 0;">{{ $appointment->date->format('d.m.Y') }} {{ $appointment->time }}</td></tr>@endif
</table>

@if($appointment->note)
    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:14px 16px;font-size:14px;line-height:1.7;color:#374151;white-space:pre-line;margin-bottom:20px;">{{ $appointment->note }}</div>
@endif

<div style="background:#faf6e9;border:1px solid #e8dfc0;border-left:4px solid #c9a227;border-radius:8px;padding:12px 14px;font-size:13.5px;color:#6b5a20;margin-bottom:20px;">
    {{ __('mail.aufmass.note') }}
</div>

<p style="font-size:14px;line-height:1.65;color:#374151;margin:0 0 18px;">
    {{ __('mail.aufmass.contact', ['phone' => setting('telefon')]) }}
</p>

<p style="font-size:14px;color:#374151;margin:0;">
    {{ __('mail.signature') }}<br>
    <strong>{{ setting('site_adi') }}</strong>
</p>
@endcomponent
