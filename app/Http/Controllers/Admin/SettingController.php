<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * Ayarlar — İş Ortağım panelindeki gibi sol alt-menülü, bölümlere ayrılmış sayfalar.
 *
 * Her sayfa yalnızca kendi anahtarlarını gönderir; `update()` de yalnızca o sayfanın
 * BEYAZ LİSTESİNDEKİ anahtarları yazar. Böylece formdan gelen rastgele bir alan
 * ayarlar tablosuna sızamaz ve başka sayfanın ayarı yanlışlıkla ezilmez.
 */
class SettingController extends Controller
{
    /**
     * sayfa => [başlık, alt başlık, ikon, izinli ayar anahtarları]
     */
    public const PAGES = [
        'genel' => [
            'title' => 'Genel Ayarlar',
            'subtitle' => 'Site adı ve arama motorlarında görünen açıklama',
            'icon' => 'settings',
            'group' => 'SİTE',
            'keys' => ['site_adi', 'site_aciklama', 'site_aciklama_tr'],
        ],
        'iletisim' => [
            'title' => 'İletişim',
            'subtitle' => 'Telefon, e-posta, adres, çalışma saatleri ve harita',
            'icon' => 'phone',
            'group' => 'SİTE',
            'keys' => [
                'telefon', 'whatsapp', 'eposta', 'adres',
                'calisma_saatleri', 'calisma_saatleri_tr', 'harita_embed',
            ],
        ],
        'sosyal' => [
            'title' => 'Sosyal Medya',
            'subtitle' => 'Alt bilgide ve iletişim bölümünde görünen hesaplar',
            'icon' => 'share-2',
            'group' => 'SİTE',
            'keys' => ['instagram', 'facebook'],
        ],
        'anasayfa' => [
            'title' => 'Anasayfa',
            'subtitle' => 'Üst bölüm (hero) görseli, başlık, metin ve sayı şeridi',
            'icon' => 'panel-top',
            'group' => 'İÇERİK',
            'keys' => [
                'hero_gorsel', 'hero_baslik', 'hero_metin', 'hero_baslik_tr', 'hero_metin_tr',
                'istatistik_yil', 'istatistik_pencere', 'istatistik_musteri', 'istatistik_bolge',
            ],
        ],
        'hakkimizda' => [
            'title' => 'Hakkımızda',
            'subtitle' => 'Hakkımızda sayfasının görseli, metni ve maddeleri',
            'icon' => 'users',
            'group' => 'İÇERİK',
            'keys' => [
                'hakkimizda_gorsel',
                'hakkimizda_baslik', 'hakkimizda_metin', 'hakkimizda_maddeler',
                'hakkimizda_baslik_tr', 'hakkimizda_metin_tr', 'hakkimizda_maddeler_tr',
            ],
        ],
        'kunye' => [
            'title' => 'Künye / Yasal',
            'subtitle' => 'Impressum ve yasal sayfalarda görünen firma bilgileri',
            'icon' => 'scale',
            'group' => 'YASAL',
            'keys' => ['firma_unvan', 'yetkili', 'kvk_no', 'btw_no'],
        ],
    ];

    public function edit(string $page = 'genel')
    {
        abort_unless(isset(self::PAGES[$page]), 404);

        return view('admin.ayarlar.' . $page, [
            'page'     => $page,
            'meta'     => self::PAGES[$page],
            'settings' => Setting::pluck('deger', 'anahtar')->toArray(),
        ]);
    }

    public function update(Request $request, string $page)
    {
        abort_unless(isset(self::PAGES[$page]), 404);

        // Yalnızca bu sayfaya ait anahtarlar yazılır (beyaz liste).
        foreach (self::PAGES[$page]['keys'] as $key) {
            if ($request->has($key)) {
                Setting::put($key, $request->input($key));
            }
        }

        Setting::flush();

        return redirect()
            ->route('admin.settings.edit', $page)
            ->with('success', self::PAGES[$page]['title'] . ' kaydedildi.');
    }
}
