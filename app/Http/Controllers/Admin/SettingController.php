<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Locales;
use Illuminate\Http\Request;

/**
 * Ayarlar — İş Ortağım panelindeki gibi sol alt-menülü, bölümlere ayrılmış sayfalar.
 *
 * Her sayfa yalnızca kendi anahtarlarını gönderir; `update()` de yalnızca o sayfanın
 * BEYAZ LİSTESİNDEKİ anahtarları yazar. Böylece formdan gelen rastgele bir alan
 * ayarlar tablosuna sızamaz ve başka sayfanın ayarı yanlışlıkla ezilmez.
 *
 * `t_keys` çok dilli ayarlardır: `Locales` listesine göre `_en`, `_tr` … soneklerine
 * açılır. Yeni dil eklenince burada değişiklik gerekmez.
 */
class SettingController extends Controller
{
    /**
     * sayfa => [başlık, alt başlık, ikon, grup, tek dilli anahtarlar, çok dilli anahtarlar]
     *
     * @return array<string,array<string,mixed>>
     */
    public static function pages(): array
    {
        $pages = [
            'genel' => [
                'title'    => 'Genel Ayarlar',
                'subtitle' => 'Site adı ve arama motorlarında görünen açıklama',
                'icon'     => 'settings',
                'group'    => 'SİTE',
                'keys'     => ['site_adi'],
                't_keys'   => ['site_aciklama'],
            ],
            'iletisim' => [
                'title'    => 'İletişim',
                'subtitle' => 'Telefon, e-posta, adres, çalışma saatleri ve harita',
                'icon'     => 'phone',
                'group'    => 'SİTE',
                'keys'     => ['telefon', 'whatsapp', 'eposta', 'adres', 'harita_embed'],
                't_keys'   => ['calisma_saatleri'],
            ],
            'sosyal' => [
                'title'    => 'Sosyal Medya',
                'subtitle' => 'Alt bilgide ve iletişim bölümünde görünen hesaplar',
                'icon'     => 'share-2',
                'group'    => 'SİTE',
                'keys'     => ['instagram', 'facebook'],
                't_keys'   => [],
            ],
            'anasayfa' => [
                'title'    => 'Anasayfa',
                'subtitle' => 'Üst bölüm (hero) görseli, başlık, metin ve sayı şeridi',
                'icon'     => 'panel-top',
                'group'    => 'İÇERİK',
                'keys'     => [
                    'hero_gorsel',
                    'istatistik_yil', 'istatistik_pencere', 'istatistik_musteri', 'istatistik_bolge',
                ],
                't_keys' => ['hero_baslik', 'hero_metin'],
            ],
            'hakkimizda' => [
                'title'    => 'Hakkımızda',
                'subtitle' => 'Hakkımızda sayfasının görseli, metni ve maddeleri',
                'icon'     => 'users',
                'group'    => 'İÇERİK',
                'keys'     => ['hakkimizda_gorsel'],
                't_keys'   => ['hakkimizda_baslik', 'hakkimizda_metin', 'hakkimizda_maddeler'],
            ],
            'kunye' => [
                'title'    => 'Künye / Yasal',
                'subtitle' => 'Impressum ve yasal sayfalarda görünen firma bilgileri',
                'icon'     => 'scale',
                'group'    => 'YASAL',
                'keys'     => ['firma_unvan', 'yetkili', 'kvk_no', 'btw_no'],
                't_keys'   => [],
            ],
        ];

        // Çok dilli anahtarları dil soneklerine aç ve beyaz listeye kat
        foreach ($pages as $key => $page) {
            $pages[$key]['allowed'] = array_merge($page['keys'], Locales::expand($page['t_keys']));
        }

        return $pages;
    }

    public function edit(string $page = 'genel')
    {
        $pages = self::pages();
        abort_unless(isset($pages[$page]), 404);

        return view('admin.ayarlar.' . $page, [
            'page'     => $page,
            'meta'     => $pages[$page],
            'settings' => Setting::pluck('deger', 'anahtar')->toArray(),
        ]);
    }

    public function update(Request $request, string $page)
    {
        $pages = self::pages();
        abort_unless(isset($pages[$page]), 404);

        // Yalnızca bu sayfaya ait anahtarlar yazılır (beyaz liste).
        foreach ($pages[$page]['allowed'] as $key) {
            if ($request->has($key)) {
                Setting::put($key, $request->input($key));
            }
        }

        Setting::flush();

        return redirect()
            ->route('admin.settings.edit', $page)
            ->with('success', $pages[$page]['title'] . ' kaydedildi.');
    }
}
