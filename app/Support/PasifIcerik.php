<?php

namespace App\Support;

use Illuminate\Http\RedirectResponse;

/**
 * PASİFE ALINMIŞ İÇERİK — 404 yerine ilgili liste sayfası.
 *
 * NEDEN: Halı hizmeti kaldırılınca ilgili kayıtlar SİLİNMEDİ, `durum=0`
 * yapıldı (geri açılabilsin diye). Ama detay sayfaları `abort(404)`
 * verdiği için Google'da kayıtlı 20 adres ölü bağlantıya dönüştü ve
 * ziyaretçi boş sayfayla karşılaştı.
 *
 * Artık ziyaretçi ilgili listeye gönderiliyor: halı ürününü arayan
 * kişi ürünler sayfasına düşüyor, hiçliğe değil.
 *
 * NEDEN 302 (geçici), 301 değil:
 * Kayıtlar bilerek silinmedi; panelden tekrar aktif edilebilirler.
 * 301 "bu adres kalıcı olarak taşındı" demektir ve arama motoru eski
 * adresi düşürür — içerik geri geldiğinde yeniden indekslenmesi
 * aylar sürebilir. 302 ile adres kayıtlı kalır, tekrar aktif
 * edildiğinde kaldığı yerden devam eder.
 */
final class PasifIcerik
{
    /** @param  string  $liste  Yollar::SAYFALAR anahtarı (ör. 'catalog') */
    public static function listeyeGonder(string $liste): RedirectResponse
    {
        return redirect()->to('/' . Yollar::parca($liste, app()->getLocale()), 302);
    }
}
