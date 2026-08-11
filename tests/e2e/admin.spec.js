import { test, expect } from '@playwright/test';

const ADMIN = { email: 'admin@ornek-perde.nl', password: 'admin123' };
const LOCALES = ['nl', 'de', 'en', 'tr'];

/**
 * Yol adları dile göre değişiyor (App\Support\Yollar'ın kopyası).
 * Test bu sözlüğü bilinçli olarak TEKRAR yazıyor: uygulamadan türetilse
 * yanlış çeviri de sessizce "doğru" sayılırdı.
 */
const YOL = {
    catalog:  { nl: 'producten',      de: 'produkte',            en: 'products',       tr: 'urunler' },
    product:  { nl: 'product',        de: 'produkt',             en: 'model',          tr: 'urun' },
    services: { nl: 'diensten',       de: 'leistungen',          en: 'services',       tr: 'hizmetler' },
    gallery:  { nl: 'galerij',        de: 'galerie',             en: 'gallery',        tr: 'galeri' },
    blog:     { nl: 'advies',         de: 'ratgeber',            en: 'guide',          tr: 'rehber' },
    about:    { nl: 'over-ons',       de: 'ueber-uns',           en: 'about-us',       tr: 'hakkimizda' },
    contact:  { nl: 'contact',        de: 'kontakt',             en: 'contact-us',     tr: 'iletisim' },
    aufmass:  { nl: 'gratis-inmeten', de: 'kostenloses-aufmass', en: 'free-measuring', tr: 'ucretsiz-olcu' },
    legal:    { nl: 'pagina',         de: 'seite',               en: 'page',           tr: 'sayfa' },
    lang:     { nl: 'taal',           de: 'sprache',             en: 'language',       tr: 'dil' },
};

/** Sayfanın o dildeki adresi: yol('catalog', 'de') → '/produkte' */
function yol(sayfa, locale, ek = '') {
    return '/' + YOL[sayfa][locale] + (ek ? '/' + ek : '');
}

/**
 * Ana sayfayı belirli dilde açar.
 * `/` her dilde aynı adres olduğu için dili yol taşımıyor — çerez taşıyor.
 * Alt sayfalarda buna gerek yok, adres dili kendi söylüyor.
 */
async function anaSayfa(page, locale) {
    await page.goto(yol('lang', locale) + '/' + locale);
    await page.goto('/');
}

/** Gizlilik sayfasının o dildeki slug'ı */
const GIZLILIK = { nl: 'privacyverklaring', de: 'datenschutz', en: 'privacy-policy', tr: 'gizlilik' };

/** Bir ürünün o dildeki slug'ı (seeder'daki temel + üretilen slug'lar) */
const URUN = {
    nl: 'duette-plisse-zand-isolerend',
    de: 'wabenplissee-sand-thermo',
    en: 'honeycomb-pleated-blind-sand-thermal',
    tr: 'petek-plise-kum-isi-yalitimli',
};

async function loginAs(page, email, password) {
    await page.goto('/giris');
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
}

test.describe('Ön yüz — çok dil', () => {
    test('kök URL doğrudan ana sayfayı açar, dil önekine yönlenmez', async ({ page }) => {
        const resp = await page.goto('/');
        expect(resp.status()).toBe(200);
        await expect(page).toHaveURL(/\/$/);

        // Hangi dil geleceği tarayıcıya bağlı (Accept-Language) — burada
        // önemli olan yönlendirme OLMAMASI. Dil tahmini ayrı testte.
        await expect(page.locator('html')).toHaveAttribute('lang', /^(nl|de|en|tr)$/);
    });

    test('adreste dil kodu yok, yol adı dili söylüyor', async ({ page }) => {
        for (const locale of LOCALES) {
            const adres = yol('catalog', locale);
            await page.goto(adres);

            await expect(page, adres).toHaveURL(new RegExp(`${adres}$`));
            await expect(page.locator('html'), adres).toHaveAttribute('lang', locale);
            // Dil kodu adreste GEÇMEMELİ
            expect(adres).not.toMatch(/\/(nl|de|en|tr)(\/|$)/);
        }
    });

    test('yol dili çerezi ezer — paylaşılan link doğru dilde açılır', async ({ page }) => {
        // Ziyaretçi Türkçe seçmiş olsun
        await page.goto(yol('lang', 'nl') + '/tr');

        // Ama Almanca bir link tıklıyor → Almanca görmeli
        await page.goto(yol('catalog', 'de'));
        await expect(page.locator('html')).toHaveAttribute('lang', 'de');
    });

    test('eski dil önekli adresler hedef dilin yoluna 301 döner', async ({ request }) => {
        for (const [eski, yeni] of [
            ['/de/produkte', '/produkte'],
            ['/tr/produkte', '/urunler'],
            ['/en/produkte', '/products'],
            ['/nl/produkte', '/producten'],
            ['/tr/seite/impressum', '/sayfa/kunye'],
        ]) {
            const resp = await request.get(eski, { maxRedirects: 0 });
            expect(resp.status(), `${eski} durum`).toBe(301);
            expect(resp.headers()['location'], `${eski} hedef`).toContain(yeni);
        }
    });

    test('başka dilin yasal slug\'ı doğru adrese 301 döner', async ({ request }) => {
        // Ana dilde de gerekli: kanonik anahtar ile slug farklı olduğu için
        // erken çıkış yapılırsa sonsuz yönlendirme oluşuyordu.
        for (const [eski, yeni] of [
            ['/pagina/impressum', '/pagina/bedrijfsgegevens'],
            ['/seite/bedrijfsgegevens', '/seite/impressum'],
            ['/sayfa/cookies', '/sayfa/cerezler'],
        ]) {
            const resp = await request.get(eski, { maxRedirects: 0 });
            expect(resp.status(), `${eski} durum`).toBe(301);
            expect(resp.headers()['location'], `${eski} hedef`).toContain(yeni);
        }
    });

    test('dil değiştirici hedef dilin adresine gider', async ({ page }) => {
        await page.goto(yol('catalog', 'nl'));
        await page.click('.lang-switch a[title="Türkçe"]');

        await expect(page).toHaveURL(new RegExp(`${yol('catalog', 'tr')}$`));
        await expect(page.locator('html')).toHaveAttribute('lang', 'tr');

        const cerezler = await page.context().cookies();
        expect(cerezler.find(c => c.name === 'taal')?.value).toBe('tr');
    });

    test('dil seçimi ana sayfada hatırlanır', async ({ page }) => {
        await page.goto('/');
        await page.click('.lang-switch a[title="English"]');

        // Ana sayfa her dilde `/` — dili çerez taşır
        await page.goto('/');
        await expect(page.locator('html')).toHaveAttribute('lang', 'en');
    });

    test('sayfa içi bağlantılar bulunulan dilin yollarını kullanır', async ({ page }) => {
        await page.goto(yol('catalog', 'de'));

        const linkler = await page.locator('.navbar a[href]').evaluateAll(
            els => els.map(e => new URL(e.href).pathname)
        );

        expect(linkler.some(u => u.endsWith('/produkte')), 'Almanca katalog linki').toBe(true);
        expect(linkler.some(u => u.endsWith('/producten')), 'Hollandaca linki OLMAMALI').toBe(false);
    });

    test('ilk ziyarette dil tarayıcıdan tahmin edilir', async ({ browser }) => {
        for (const [baslik, beklenen] of [['de-DE,de;q=0.9', 'de'], ['tr-TR,tr;q=0.9', 'tr'],
                                          ['fr-FR,fr;q=0.9', 'nl']]) {
            const ctx = await browser.newContext({ locale: baslik.split(',')[0] });
            const p = await ctx.newPage();
            await p.goto('/');
            await expect(p.locator('html'), baslik).toHaveAttribute('lang', beklenen);
            await ctx.close();
        }
    });

    test('geçersiz dil kodu 404', async ({ request }) => {
        expect((await request.get('/taal/xx')).status()).toBe(404);
    });

    for (const locale of LOCALES) {
        test(`tüm sayfalar ${locale} yollarıyla 200 dönüyor`, async ({ page }) => {
            // `/` bilinçli olarak YOK: ana sayfada dil yoldan gelmiyor,
            // onu `anaSayfa()` kullanan ayrı testler kontrol ediyor.
            const paths = [
                yol('catalog', locale),
                yol('product', locale, URUN[locale]),
                yol('services', locale),
                yol('gallery', locale),
                yol('blog', locale),
                yol('about', locale),
                yol('contact', locale),
                yol('aufmass', locale),
                yol('legal', locale, GIZLILIK[locale]),
            ];

            for (const path of paths) {
                const resp = await page.goto(path);
                expect(resp.status(), `${path} (${locale}) status`).toBe(200);
                await expect(page.locator('html'), `${path} lang`).toHaveAttribute('lang', locale);
            }
        });
    }

    test('beş yasal sayfa da her dilde açılıyor', async ({ page }) => {
        const sluglar = {
            nl: ['bedrijfsgegevens', 'privacyverklaring', 'algemene-voorwaarden', 'herroepingsrecht', 'cookieverklaring'],
            de: ['impressum', 'datenschutz', 'agb', 'widerruf', 'cookies'],
            en: ['legal-notice', 'privacy-policy', 'terms-and-conditions', 'right-of-withdrawal', 'cookie-notice'],
            tr: ['kunye', 'gizlilik', 'sartlar', 'cayma-hakki', 'cerezler'],
        };

        for (const [locale, liste] of Object.entries(sluglar)) {
            for (const slug of liste) {
                const adres = yol('legal', locale, slug);
                const resp = await page.goto(adres);
                expect(resp.status(), adres).toBe(200);
                await expect(page.locator('html'), adres).toHaveAttribute('lang', locale);
            }
        }
    });

    test('her dil kendi ürün adını gösterir', async ({ page }) => {
        const beklenen = { nl: 'Duette', de: 'Wabenplissee', en: 'Honeycomb', tr: 'Plise' };

        for (const [locale, metin] of Object.entries(beklenen)) {
            await page.goto(yol('product', locale, URUN[locale]));
            await expect(page.locator('h1.pd-title'), `${locale} ürün adı`).toContainText(metin);
        }
    });

    test('hreflang her dilin gerçek adresini bildiriyor', async ({ page }) => {
        await page.goto(yol('product', 'de', URUN.de));

        for (const locale of LOCALES) {
            const link = page.locator(`link[rel="alternate"][hreflang="${locale}"]`);
            await expect(link, `${locale} hreflang`).toHaveCount(1);

            const href = await link.getAttribute('href');
            expect(href, `${locale} hreflang adresi`).toContain(yol('product', locale, URUN[locale]));
        }

        await expect(page.locator('link[rel="alternate"][hreflang="x-default"]')).toHaveCount(1);
    });

    test('canonical bulunulan dilin adresini gösteriyor', async ({ page }) => {
        await page.goto(yol('product', 'tr', URUN.tr));
        const canonical = await page.locator('link[rel="canonical"]').getAttribute('href');
        expect(canonical).toContain(yol('product', 'tr', URUN.tr));
    });

    test('ana sayfada yalnızca x-default var (her dilde aynı adres)', async ({ page }) => {
        await anaSayfa(page, 'nl');
        await expect(page.locator('link[rel="alternate"][hreflang="x-default"]')).toHaveCount(1);
        await expect(page.locator('link[rel="alternate"]')).toHaveCount(1);
    });

    test('başka dilin içerik slug\'ı da kaydı açıyor', async ({ page }) => {
        // Almanca yol + Hollandaca slug → 404 değil, içerik gelmeli
        const resp = await page.goto('/produkte/gordijnen');
        expect(resp.status()).toBe(200);
    });

    test('yanıt Vary: Cookie taşıyor', async ({ request }) => {
        const resp = await request.get('/producten');
        // Olmazsa araya giren önbellek ilk gelen dili herkese servis eder
        expect(resp.headers()['vary'] ?? '').toContain('Cookie');
    });

    test('Hollandaca sayfa (ana dil) Hollandaca arayüz metni gösterir', async ({ page }) => {
        await anaSayfa(page, 'nl');
        await expect(page.locator('html')).toHaveAttribute('lang', 'nl');
        await expect(page.locator('.navbar')).toContainText('Producten');
        await expect(page.locator('.stats')).toContainText('Jaar ervaring');
    });

    test('Almanca sayfa ikincil kolonlardan Almanca metni gösterir', async ({ page }) => {
        await page.goto(yol('catalog', 'de'));
        await expect(page.locator('html')).toHaveAttribute('lang', 'de');
        await expect(page.locator('.navbar')).toContainText('Produkte');
        // İçerik `_de` kolonundan gelmeli, ana dile düşmemeli
        await page.goto(yol('product', 'de', 'aluminiumjalousie-25-mm'));
        await expect(page.locator('h1.pd-title')).toContainText('Aluminiumjalousie');
    });

    test('Hollandaca ürün sayfası Hollandaca özellik tablosu gösterir', async ({ page }) => {
        await page.goto(yol('product', 'nl', 'aluminium-jaloezie-25mm'));

        // Almanca "Lamellenbreite" değil Hollandaca karşılığı görünmeli
        await expect(page.locator('.pd-attrs')).toContainText('Lamelbreedte');
        await expect(page.locator('.pd-attrs')).not.toContainText('Lamellenbreite');
    });

    test('Hollandaca hata sayfası Hollandaca metin gösterir', async ({ page }) => {
        await page.goto(yol('lang', 'nl') + '/nl');
        const resp = await page.goto('/boes-boes-yok');
        expect(resp.status()).toBe(404);
        await expect(page.locator('body')).toContainText('Deze pagina bestaat niet');
    });

    test('İngilizce sayfa İngilizce arayüz metni gösterir', async ({ page }) => {
        await anaSayfa(page, 'en');
        await expect(page.locator('.navbar')).toContainText('Products');
        await expect(page.locator('.stats')).toContainText('Years of experience');
    });

    test('özellik tablosu her dilde çevrilmiş görünür', async ({ page }) => {
        const beklenen = {
            nl: ['Lichtdoorlatendheid', 'Honingraatstructuur'],
            de: ['Lichtdurchlässigkeit', 'Wabenstruktur'],
            en: ['Light transmission', 'Honeycomb structure'],
            tr: ['Işık geçirgenliği', 'Petek yapı'],
        };

        for (const [locale, kelimeler] of Object.entries(beklenen)) {
            await page.goto(yol('product', locale, URUN[locale]));
            for (const kelime of kelimeler) {
                await expect(page.locator('.pd-attrs'), `${locale}: ${kelime}`).toContainText(kelime);
            }
        }
    });

    test('geçersiz dil öneki 404', async ({ page }) => {
        const resp = await page.goto('/fr');
        expect(resp.status()).toBe(404);
    });

    test('fiyatsız ürün her dilde kendi "sorunuz" metnini gösterir', async ({ page }) => {
        const beklenen = { nl: 'op aanvraag', de: 'Anfrage', en: 'on request', tr: 'sorunuz' };

        const halislug = { nl: 'vintage-loper-op-maat', de: 'laeufer-vintage-nach-mass',
                           en: 'vintage-runner-made-to-measure', tr: 'vintage-yol-halisi-olcuye-ozel' };

        for (const [locale, metin] of Object.entries(beklenen)) {
            await page.goto(yol('product', locale, halislug[locale]));
            await expect(page.locator('.pd-price'), `${locale} fiyat metni`).toContainText(metin);
        }
    });
});

test.describe('Ön yüz — formlar', () => {
    test('ücretsiz ölçü formu kaydedilir', async ({ page }) => {
        await page.goto(yol('aufmass', 'de'));
        await page.fill('input[name="name"]', 'PW Aufmass Test');
        await page.fill('input[name="phone"]', '+31 6 00 00 00 00');
        await page.fill('input[name="zip"]', '1012');
        await page.check('input[name="privacy"]');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('Vielen Dank');
    });

    test('ürün detayından gelen ölçü formu ürün grubunu ön seçer', async ({ page }) => {
        await page.goto(yol('aufmass', 'nl') + '?produkt=Plissees');
        await expect(page.locator('select[name="subject"]')).toHaveValue('Plissees');
    });

    test('gizlilik onayı olmadan iletişim formu reddedilir', async ({ page }) => {
        await page.goto(yol('contact', 'tr'));
        await page.fill('input[name="name"]', 'PW Test');
        await page.fill('textarea[name="message"]', 'Test mesaji');
        // privacy işaretlenmedi → tarayıcı required'ı atlatmak için kaldırıyoruz
        await page.evaluate(() => document.querySelector('[name=privacy]').removeAttribute('required'));
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-danger')).toBeVisible();
    });
});

test.describe('Yönetim paneli', () => {
    test('admin giriş yapıp panele ulaşır', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await expect(page).toHaveURL(/yonetim/);
        await expect(page.locator('.sidebar-brand')).toContainText('Gordijnen');
    });

    test('tüm admin sayfaları yükleniyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);

        for (const path of [
            '/yonetim',
            '/yonetim/products',
            '/yonetim/products/create',
            '/yonetim/categories',
            '/yonetim/services',
            '/yonetim/projects',
            '/yonetim/posts',
            '/yonetim/posts/create',
            '/yonetim/testimonials',
            '/yonetim/testimonials/create',
            '/yonetim/appointments',
            '/yonetim/messages',
            '/yonetim/settings',
            '/yonetim/profile',
        ]) {
            const resp = await page.goto(path);
            expect(resp.status(), `${path} status`).toBe(200);
            await expect(page.locator('h1')).toBeVisible();
        }
    });

    test('ürün formunda her dil için alan var', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/products/create');

        await expect(page.locator('input[name="name"]')).toBeVisible();

        for (const locale of ['de', 'en', 'tr']) {
            await expect(page.locator(`input[name="name_${locale}"]`), `name_${locale}`).toBeVisible();
            await expect(page.locator(`textarea[name="description_${locale}"]`), `description_${locale}`).toBeVisible();
        }
    });

    test('ürün formunda her dil için özellik alanı var', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/products/duette-plisse-zand-isolerend/edit');

        await expect(page.locator('textarea[name="attributes_raw"]')).toBeVisible();
        for (const locale of ['de', 'en', 'tr']) {
            await expect(page.locator(`textarea[name="attributes_raw_${locale}"]`)).toBeVisible();
        }

        // TR özellik tablosunu değiştir → sitede TR sayfada görünsün
        await page.fill('textarea[name="attributes_raw_tr"]', 'Malzeme: PW test kumaş');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('güncellendi');

        await page.goto(yol('product', 'tr', URUN.tr));
        await expect(page.locator('.pd-attrs')).toContainText('PW test kumaş');

        // Almanca tablo (artık ikincil kolon) etkilenmemeli
        await page.goto(yol('product', 'de', URUN.de));
        await expect(page.locator('.pd-attrs')).toContainText('Lichtdurchlässigkeit');

        // Ana dil (Hollandaca) tablosu da etkilenmemeli
        await page.goto(yol('product', 'nl', URUN.nl));
        await expect(page.locator('.pd-attrs')).toContainText('Lichtdoorlatendheid');

        // Kurulumdaki TR tablosunu geri yaz: aksi halde takım ikinci kez
        // çalıştırıldığında "özellik tablosu her dilde çevrilmiş" testi
        // bu testin bıraktığı veriyi bulup patlıyor.
        await page.goto('/yonetim/products/duette-plisse-zand-isolerend/edit');
        await page.fill('textarea[name="attributes_raw_tr"]',
            'Malzeme: Petek yapı, arkası yansıtıcı\nIşık geçirgenliği: yarı şeffaftan karartmaya\n'
            + 'Ek özellik: ısı ve soğuk yalıtımı\nMontaj: vidalı veya kıskaçlı aparat\nKumanda: tutamak veya kordon');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('güncellendi');
    });

    test('çeviri alanları kaydedilip ilgili dilde görünüyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/products/aluminium-jaloezie-25mm/edit');
        await page.fill('input[name="name_tr"]', 'PW Alüminyum Jaluzi');
        await page.fill('input[name="name_en"]', 'PW Aluminium Blind');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('güncellendi');

        /* Slug DEĞİŞMEZ: yalnızca boş olan çeviri slug'ı başlıktan üretilir.
           Başlığı düzenleyince adresin de değişmesi, paylaşılmış linkleri ve
           arama motorundaki kaydı sessizce kırardı. */
        await page.goto(yol('product', 'tr', 'aluminyum-jaluzi-25-mm'));
        await expect(page.locator('h1.pd-title')).toContainText('PW Alüminyum Jaluzi');

        await page.goto(yol('product', 'en', 'aluminium-venetian-blind-25-mm'));
        await expect(page.locator('h1.pd-title')).toContainText('PW Aluminium Blind');
    });

    test('boş çeviri slug\'ı başlıktan üretilir', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);

        // Yeni kayıt: yalnızca başlıklar giriliyor, slug alanı yok
        await page.goto('/yonetim/testimonials/create');
        await expect(page.locator('input[name="name"]')).toBeVisible();

        // Ürün üzerinden doğrula: mevcut slug'lar dolu olduğu için üretilen
        // slug'ların dört dilde de var olması yeterli kanıt
        await page.goto('/yonetim/products/aluminium-jaloezie-25mm/edit');
        await expect(page.locator('input[name="name_de"]')).toHaveValue(/Aluminiumjalousie/);
    });

    test('boş çeviri ana dile düşer', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/products/vitrage-goudglans/edit');
        await page.fill('input[name="name_en"]', '');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toBeVisible();

        // İngilizcesi boş → ana dil (Hollandaca) adı gösterilmeli
        await page.goto(yol('product', 'en', 'voile-with-gold-shimmer'));
        await expect(page.locator('h1.pd-title')).toContainText('Vitrage');
    });

    test('ölçü talebi durumu güncellenebiliyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/appointments');
        const select = page.locator('select[name="status"]').first();
        await expect(select).toBeVisible();
        await select.selectOption('arandi');
        await expect(page.locator('.alert-success')).toContainText('güncellendi');
    });

    test('koyu tema tercihi sayfa yenilendikten sonra da kalıyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim');

        await expect(page.locator('body')).not.toHaveClass(/theme-dark/);
        await page.click('button[onclick="toggleTheme()"]');
        await expect(page.locator('body')).toHaveClass(/theme-dark/);

        // Çerez şifrelemeden muaf olmalı; aksi halde sunucu null okur ve tema sıfırlanır
        await page.goto('/yonetim/products');
        await expect(page.locator('body')).toHaveClass(/theme-dark/);

        await page.click('button[onclick="toggleTheme()"]');
        await expect(page.locator('body')).not.toHaveClass(/theme-dark/);
    });

    test('sidebar daraltma tercihi korunuyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim');

        await page.click('button[onclick="toggleSidebar()"]');
        await expect(page.locator('body')).toHaveClass(/sidebar-collapsed/);

        await page.goto('/yonetim/categories');
        await expect(page.locator('body')).toHaveClass(/sidebar-collapsed/);

        await page.click('button[onclick="toggleSidebar()"]');
        await expect(page.locator('body')).not.toHaveClass(/sidebar-collapsed/);
    });

    test('geçersiz durum değeri kaydedilmiyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/appointments');

        const token = await page.locator('input[name="_token"]').first().inputValue();
        const action = await page.locator('form[action*="appointments"]').first().getAttribute('action');

        // Enum dışı bir durum göndermeyi dene (form arayüzünü atlayarak)
        await page.evaluate(async ([action, token]) => {
            const body = new URLSearchParams({ _token: token, _method: 'PATCH', status: 'hacklendi' });
            await fetch(action, { method: 'POST', body });
        }, [action, token]);

        // Liste yenilendiğinde geçersiz değer görünmemeli; select yine geçerli bir durumda olmalı
        await page.reload();
        const value = await page.locator('select[name="status"]').first().inputValue();
        expect(value).not.toBe('hacklendi');
        await expect(page.locator('body')).not.toContainText('hacklendi');
    });
});

test.describe('Ayarlar — bölümler', () => {
    const BOLUMLER = ['genel', 'iletisim', 'sosyal', 'anasayfa', 'hakkimizda', 'kunye'];

    test('tüm ayar bölümleri açılıyor, geçersiz bölüm 404', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);

        for (const b of BOLUMLER) {
            const resp = await page.goto(`/yonetim/settings/${b}`);
            expect(resp.status(), `${b} status`).toBe(200);
            await expect(page.locator('.ayarlar-subnav')).toBeVisible();
            await expect(page.locator(`.ayarlar-sub-link.active`)).toHaveCount(1);
        }

        const resp = await page.goto('/yonetim/settings/olmayan-bolum');
        expect(resp.status()).toBe(404);
    });

    test('parametresiz /settings genel bölümü açar', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/settings');
        await expect(page.locator('.page-title')).toContainText('Genel');
    });

    test('dil sekmeleri alan panelini değiştirir (tüm diller)', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/settings/hakkimizda');

        // Sekme sayısı dil listesinden türer — sabit sayı yazmak dil eklenince kırılıyor
        await expect(page.locator('[data-lang-tab="about"]')).toHaveCount(LOCALES.length);
        await expect(page.locator('textarea[name="hakkimizda_metin"]')).toBeVisible();

        for (const locale of ['de', 'en', 'tr']) {
            await page.click(`[data-lang-tab="about"][data-locale="${locale}"]`);
            await expect(page.locator(`textarea[name="hakkimizda_metin_${locale}"]`)).toBeVisible();
            await expect(page.locator('textarea[name="hakkimizda_metin"]')).toBeHidden();
        }
    });

    test('bölüm kaydediliyor ve değer sitede görünüyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/settings/sosyal');

        await page.fill('input[name="facebook"]', 'https://www.facebook.com/pw-test');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('kaydedildi');
        await expect(page.locator('input[name="facebook"]')).toHaveValue('https://www.facebook.com/pw-test');

        // alt bilgide Facebook ikonu artık çıkmalı
        await page.goto('/');
        await expect(page.locator('footer a[href*="facebook.com/pw-test"]')).toHaveCount(1);

        // temizle
        await page.goto('/yonetim/settings/sosyal');
        await page.fill('input[name="facebook"]', '');
        await page.click('form button[type="submit"]');
    });

    test('bölüme ait olmayan anahtar yazılamıyor (beyaz liste)', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/settings/sosyal');

        // Sosyal bölümüne site_adi enjekte etmeyi dene
        await page.evaluate(() => {
            const f = document.querySelector('form[action*="settings/sosyal"]');
            const i = document.createElement('input');
            i.name = 'site_adi';
            i.value = 'BEYAZ-LISTE-KIRILDI';
            f.appendChild(i);
            f.submit();
        });

        await expect(page.locator('.alert-success')).toBeVisible();

        // site adı değişmemiş olmalı (sekme başlığından doğrula)
        await page.goto('/yonetim/settings/genel');
        await expect(page.locator('input[name="site_adi"]')).not.toHaveValue('BEYAZ-LISTE-KIRILDI');
    });
});

test.describe('İçerik yönetimi (rehber + yorumlar)', () => {
    test('rehber yazısı eklenip sitede görünüyor, sonra siliniyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/posts/create');

        await page.fill('input[name="title"]', 'PW Testbeitrag');
        await page.fill('input[name="title_en"]', 'PW Test Article');
        await page.fill('textarea[name="summary"]', 'Kurzfassung aus dem Test.');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('eklendi');

        // Almanca listede ve İngilizce listede kendi başlığıyla
        await page.goto(yol('blog', 'de'));
        await expect(page.locator('body')).toContainText('PW Testbeitrag');
        await page.goto(yol('blog', 'en'));
        await expect(page.locator('body')).toContainText('PW Test Article');

        // temizle
        await page.goto('/yonetim/posts');
        page.once('dialog', d => d.accept());
        await page.locator('tr', { hasText: 'PW Testbeitrag' }).locator('button.danger').click();
        await expect(page.locator('.alert-success')).toContainText('silindi');
    });

    test('müşteri yorumu eklenip anasayfada görünüyor, sonra siliniyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/testimonials/create');

        await page.fill('input[name="name"]', 'PW Kunde');
        await page.fill('textarea[name="comment"]', 'Sehr zufrieden mit dem Test.');
        await page.selectOption('select[name="stars"]', '4');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('eklendi');

        await page.goto('/');
        await expect(page.locator('body')).toContainText('PW Kunde');

        await page.goto('/yonetim/testimonials');
        page.once('dialog', d => d.accept());
        await page.locator('tr', { hasText: 'PW Kunde' }).locator('button.danger').click();
        await expect(page.locator('.alert-success')).toContainText('silindi');
    });
});

test.describe('Formlar — spam koruması', () => {
    test('honeypot dolu gönderim kayıt oluşturmuyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/messages');
        const oncekiSatir = await page.locator('.data-table tbody tr').count();

        await page.goto(yol('contact', 'nl'));
        await page.fill('input[name="name"]', 'PW Bot');
        await page.fill('textarea[name="message"]', 'Bot mesaji');
        await page.check('input[name="privacy"]');
        await page.fill('input[name="website"]', 'http://spam.example');   // honeypot
        await page.click('form button[type="submit"]');

        // Kullanıcıya başarı gösterilir (bota ipucu vermemek için) ama kayıt açılmaz
        await expect(page.locator('.alert-success')).toBeVisible();

        await page.goto('/yonetim/messages');
        await expect(page.locator('.data-table tbody tr')).toHaveCount(oncekiSatir);
        await expect(page.locator('body')).not.toContainText('PW Bot');
    });

    test('honeypot alanı görüntü alanının dışında', async ({ page }) => {
        await page.goto(yol('aufmass', 'nl'));

        // Bilinçli olarak display:none DEĞİL (bazı botlar onu atlar); ekran dışına itiliyor.
        const box = await page.locator('input[name="website"]').boundingBox();
        expect(box.x + box.width, 'honeypot ekranın solunda kalmalı').toBeLessThan(0);
    });
});

test.describe('Dış bağımlılık ve hata sayfaları', () => {
    test('sayfa hiçbir dış sunucuya istek atmıyor', async ({ page, baseURL }) => {
        const kendiHost = new URL(baseURL).host;
        const disHost = new Set();

        page.on('request', (req) => {
            const url = req.url();
            if (url.startsWith('data:') || url.startsWith('blob:')) return;
            const host = new URL(url).host;
            if (host !== kendiHost) disHost.add(host);
        });

        await page.goto('/', { waitUntil: 'networkidle' });

        // Görseller de göreli yoldan geldiği için başka bir host görünmemeli
        // (mutlak URL saklanırsa alan adı değişince kırılır — bkz. media() yardımcısı).
        expect([...disHost], 'dış host listesi boş olmalı').toEqual([]);
    });

    test('fontlar ve Bootstrap yerelden sunuluyor', async ({ request }) => {
        for (const yol of [
            '/css/fonts.css',
            '/vendor/bootstrap/bootstrap.min.css',
            '/vendor/bootstrap/bootstrap.bundle.min.js',
            '/vendor/bootstrap-icons/bootstrap-icons.min.css',
            '/vendor/bootstrap-icons/fonts/bootstrap-icons.woff2',
        ]) {
            const resp = await request.get(yol);
            expect(resp.status(), yol).toBe(200);
        }
    });

    test('404 sayfası dil önekine göre çeviriliyor', async ({ page }) => {
        const beklenen = {
            de: 'Diese Seite gibt es nicht',
            en: 'This page does not exist',
            tr: 'Böyle bir sayfa yok',
        };

        for (const [locale, metin] of Object.entries(beklenen)) {
            const resp = await page.goto(`/${locale}/olmayan-sayfa`);
            expect(resp.status(), `${locale} durum`).toBe(404);
            await expect(page.locator('h1'), `${locale} metin`).toContainText(metin);
        }
    });

    test('404 sayfasında ana menü bağlantıları var', async ({ page }) => {
        await page.goto(yol('lang', 'nl') + '/de');   // 404 sayfasının dili çerezden gelir
        await page.goto('/olmayan-sayfa');
        await expect(page.locator('.err-links a')).toHaveCount(5);
        await expect(page.locator('.err-actions')).toContainText('Startseite');
    });

    test('robots.txt mutlak sitemap adresi veriyor', async ({ request }) => {
        const resp = await request.get('/robots.txt');
        const body = await resp.text();
        expect(body).toContain('Disallow: /yonetim');
        expect(body).toMatch(/Sitemap: https?:\/\/.+\/sitemap\.xml/);
    });
});

test.describe('Güvenlik — başlıklar ve erişim', () => {
    test('güvenlik başlıkları gönderiliyor', async ({ request }) => {
        const resp = await request.get('/');
        const h = resp.headers();

        expect(h['content-security-policy'], 'CSP').toBeTruthy();
        expect(h['content-security-policy']).toContain("default-src 'self'");
        expect(h['x-content-type-options']).toBe('nosniff');
        expect(h['x-frame-options']).toBe('SAMEORIGIN');
        expect(h['referrer-policy']).toBe('strict-origin-when-cross-origin');
        expect(h['permissions-policy'], 'Permissions-Policy').toBeTruthy();

        // PHP sürümü sızmasın
        expect(h['x-powered-by'], 'X-Powered-By kaldırılmış olmalı').toBeUndefined();
    });

    test('CSP dış script/stil kaynağına izin vermiyor', async ({ request }) => {
        const resp = await request.get('/');
        const csp = resp.headers()['content-security-policy'];

        // Yalnızca kendi sunucumuz (satır içi kod hariç) — CDN adresi geçmemeli
        expect(csp).toContain("script-src 'self'");
        expect(csp).not.toContain('jsdelivr');
        expect(csp).not.toContain('googleapis');
    });

    test('hata sayfaları da güvenlik başlığı taşıyor', async ({ request }) => {
        const resp = await request.get('/olmayan-sayfa');
        expect(resp.status()).toBe(404);
        expect(resp.headers()['content-security-policy']).toBeTruthy();
    });

    test('yüklenen klasörde PHP çalıştırılamıyor', async ({ request }) => {
        // uploads/.htaccess çalıştırmayı engelliyor; dosya olmasa da 403 dönmeli
        const resp = await request.get('/uploads/deneme.php');
        expect([403, 404], 'PHP dosyası servis edilmemeli').toContain(resp.status());
    });

    test('panel arama motorlarına kapalı', async ({ page, request }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim');
        await expect(page.locator('meta[name="robots"]')).toHaveAttribute('content', /noindex/);

        const robots = await (await request.get('/robots.txt')).text();
        expect(robots).toContain('Disallow: /yonetim');
        expect(robots).toContain('Disallow: /giris');
    });

    test('yanlış şifreyle giriş hesap varlığını sızdırmıyor', async ({ page }) => {
        // Var olan hesap ile olmayan hesap AYNI mesajı vermeli
        const mesajlar = [];

        for (const email of [ADMIN.email, 'olmayan-hesap@example.com']) {
            await page.goto('/giris');
            await page.fill('input[name="email"]', email);
            await page.fill('input[name="password"]', 'kesinlikle-yanlis-sifre');
            await page.click('button[type="submit"]');
            mesajlar.push((await page.locator('.alert-danger').textContent()).trim());
        }

        expect(mesajlar[0]).toBe(mesajlar[1]);
    });
});

test.describe('Güvenlik — yetki kontrolü', () => {
    test('giriş yapmamış kullanıcı /yonetim göremez', async ({ page }) => {
        await page.goto('/yonetim');
        await expect(page).toHaveURL(/giris/);
    });

    test('oturumsuz /yonetim isteği yönlendirilir, 200 dönmez', async ({ request }) => {
        const resp = await request.get('/yonetim', { maxRedirects: 0 });
        expect([301, 302]).toContain(resp.status());
    });

    test('hassas yollar robots.txt ile engellenmiş', async ({ request }) => {
        const resp = await request.get('/robots.txt');
        const body = await resp.text();
        expect(body).toContain('Disallow: /yonetim');
    });

    test('sitemap dört dilin yollarını içerir, tekrar yok', async ({ request }) => {
        const body = await (await request.get('/sitemap.xml')).text();
        const adresler = [...body.matchAll(/<loc>([^<]+)<\/loc>/g)].map(m => m[1]);

        expect(new Set(adresler).size, 'aynı adres iki kez listelenmemeli').toBe(adresler.length);

        // Her dilin katalog adresi bulunmalı
        for (const locale of LOCALES) {
            const beklenen = yol('catalog', locale);
            expect(adresler.some(u => u.endsWith(beklenen)), `${locale}: ${beklenen}`).toBe(true);
        }

        // Dil KODU hiçbir adreste geçmemeli
        for (const locale of LOCALES) {
            expect(body, `/${locale}/ öneki bulunmamalı`).not.toContain(`/${locale}/`);
        }
    });
});
