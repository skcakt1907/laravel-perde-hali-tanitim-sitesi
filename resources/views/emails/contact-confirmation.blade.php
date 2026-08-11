@component('emails.layout', ['title' => __('mail.contact.subject')])
<h1 style="font-size:21px;margin:0 0 12px;">{{ __('mail.contact.greeting', ['name' => $contactMessage->name]) }}</h1>

<p style="font-size:15px;line-height:1.65;color:#374151;margin:0 0 20px;">
    {{ __('mail.contact.intro') }}
</p>

<p style="font-size:13px;font-weight:bold;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin:0 0 8px;">
    {{ __('mail.contact.summary') }}
</p>

@if($contactMessage->subject)
    <p style="font-size:14px;margin:0 0 8px;color:#374151;">
        <strong>{{ $contactMessage->subject }}</strong>
    </p>
@endif

<div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:14px 16px;font-size:14px;line-height:1.7;color:#374151;white-space:pre-line;margin-bottom:20px;">{{ $contactMessage->message }}</div>

<p style="font-size:14px;line-height:1.65;color:#374151;margin:0 0 18px;">
    {{ __('mail.contact.contact', ['phone' => setting('telefon')]) }}
</p>

<p style="font-size:14px;color:#374151;margin:0;">
    {{ __('mail.signature') }}<br>
    <strong>{{ setting('site_adi') }}</strong>
</p>
@endcomponent
