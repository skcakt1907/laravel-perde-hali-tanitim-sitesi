<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? setting('site_adi') }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f5f7;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f5f7;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#141414;padding:22px 28px;">
                            <span style="color:#c9a227;font-size:20px;font-weight:bold;letter-spacing:.3px;">{{ setting('site_adi') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            {{ $slot }}
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f9fafb;padding:18px 28px;border-top:1px solid #e5e7eb;color:#6b7280;font-size:12px;line-height:1.6;">
                            {{ setting('site_adi') }} · {{ setting('adres') }}<br>
                            Tel: {{ setting('telefon') }} · {{ setting('eposta') }}<br>
                            © {{ date('Y') }} {{ setting('site_adi') }}. Tüm hakları saklıdır.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
