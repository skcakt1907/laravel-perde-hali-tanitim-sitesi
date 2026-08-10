@component('emails.layout', ['title' => 'İletişim Mesajı'])
<h1 style="font-size:22px;margin:0 0 6px;">Yeni İletişim Mesajı</h1>
<p style="font-size:15px;line-height:1.6;color:#374151;margin:0 0 18px;">Web sitesi iletişim formundan yeni bir mesaj alındı.</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;margin:0 0 18px;">
    <tr><td style="padding:4px 0;color:#6b7280;width:120px;">Ad Soyad</td><td style="padding:4px 0;">{{ $contactMessage->name }}</td></tr>
    @if($contactMessage->email)<tr><td style="padding:4px 0;color:#6b7280;">E-Posta</td><td style="padding:4px 0;">{{ $contactMessage->email }}</td></tr>@endif
    @if($contactMessage->phone)<tr><td style="padding:4px 0;color:#6b7280;">Telefon</td><td style="padding:4px 0;">{{ $contactMessage->phone }}</td></tr>@endif
    @if($contactMessage->subject)<tr><td style="padding:4px 0;color:#6b7280;">Konu</td><td style="padding:4px 0;">{{ $contactMessage->subject }}</td></tr>@endif
</table>

<div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px 18px;font-size:14px;line-height:1.7;color:#374151;white-space:pre-line;">{{ $contactMessage->message }}</div>
@endcomponent
