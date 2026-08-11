<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Güvenlik başlıkları — .htaccess'e DEĞİL koda konuldu ki sunucu değişse
 * (nginx, farklı hosting, mod_headers kapalı) koruma kaybolmasın.
 *
 * CSP notu: sitede satır içi (`inline`) stil, script ve `onclick` kullanılıyor;
 * bu yüzden script/style için 'unsafe-inline' veriliyor. Buna rağmen CSP asıl
 * işini yapıyor: DIŞ kaynaktan script/stil/font yüklenmesini engelliyor —
 * yani "bu site üçüncü taraf içerik yüklemez" sözünü tarayıcı seviyesinde
 * zorunlu kılıyor ve olası bir XSS'te dışarıya veri sızdırmayı zorlaştırıyor.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        // PHP bu başlığı SAPI seviyesinde ekler; Symfony yanıtından silmek yetmez.
        // (.htaccess'teki `Header unset` de mod_headers kapalıysa çalışmaz.)
        if (! headers_sent()) {
            header_remove('X-Powered-By');
        }

        $response = $next($request);

        // Google Maps yerleştirmesi ayarlarda opsiyonel — iframe'e izin ver, gerisini kapat
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline'",
            "font-src 'self' data:",
            // Ayarlarda dış bir görsel adresi girilebilir (ör. CDN'deki ürün fotoğrafı)
            "img-src 'self' data: https:",
            "connect-src 'self'",
            "frame-src 'self' https://www.google.com https://maps.google.com",
            "media-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ];

        $headers = [
            'Content-Security-Policy' => implode('; ', $csp),
            'X-Content-Type-Options'  => 'nosniff',
            'X-Frame-Options'         => 'SAMEORIGIN',
            'Referrer-Policy'         => 'strict-origin-when-cross-origin',
            'Permissions-Policy'      => 'geolocation=(), microphone=(), camera=(), payment=(), usb=()',
            'Cross-Origin-Opener-Policy' => 'same-origin',
            // Sunucu/PHP sürümünü sızdırmayalım
            'X-Powered-By' => '',
        ];

        // HSTS yalnızca gerçekten HTTPS'te — yoksa yerel http geliştirme kilitlenir
        if ($request->secure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
        }

        foreach ($headers as $ad => $deger) {
            if ($deger === '') {
                $response->headers->remove($ad);
                continue;
            }

            // Zaten elle set edilmişse ezmeyelim
            if (! $response->headers->has($ad)) {
                $response->headers->set($ad, $deger);
            }
        }

        return $response;
    }
}
