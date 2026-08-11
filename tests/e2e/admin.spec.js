import { test, expect } from '@playwright/test';

const ADMIN = { email: 'admin@ornek-perde.nl', password: 'admin123' };
const LOCALES = ['nl', 'de', 'en', 'tr'];

/**
 * Dili değiştirir. URL'de önek olmadığı için dil `/dil/{kod}` rotasıyla
 * çereze yazılır — gerçek ziyaretçi akışının aynısı.
 */
async function dilSec(page, locale) {
    await page.goto(`/dil/${locale}`);
}

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

    test('adreste dil öneki yok', async ({ page }) => {
        await dilSec(page, 'tr');
        await page.goto('/produkte');

        await expect(page).toHaveURL(/\/produkte$/);
        await expect(page.locator('html')).toHaveAttribute('lang', 'tr');
    });

    test('eski dil önekli adresler öneksiz karşılığına 301 döner', async ({ request }) => {
        for (const [eski, yeni] of [
            ['/de', '/'],
            ['/de/produkte', '/produkte'],
            ['/tr/produkt/aluminiumjalousie-25mm', '/produkt/aluminiumjalousie-25mm'],
        ]) {
            const resp = await request.get(eski, { maxRedirects: 0 });
            expect(resp.status(), `${eski} durum`).toBe(301);
            expect(resp.headers()['location'], `${eski} hedef`).toContain(yeni);
        }
    });

    test('dil değiştirici çerezi yazıp aynı sayfaya döner', async ({ page }) => {
        await page.goto('/produkte');
        await page.click('.lang-switch a[title="Türkçe"]');

        await expect(page).toHaveURL(/\/produkte$/);
        await expect(page.locator('html')).toHaveAttribute('lang', 'tr');

        const cerezler = await page.context().cookies();
        expect(cerezler.find(c => c.name === 'dil')?.value).toBe('tr');
    });

    test('dil seçimi sonraki sayfalarda korunur', async ({ page }) => {
        await page.goto('/');
        await page.click('.lang-switch a[title="English"]');

        for (const yol of ['/produkte', '/galerie', '/kontakt']) {
            await page.goto(yol);
            await expect(page.locator('html'), yol).toHaveAttribute('lang', 'en');
        }
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
        expect((await request.get('/dil/xx')).status()).toBe(404);
    });

    for (const locale of LOCALES) {
        test(`tüm sayfalar ${locale} dilinde 200 dönüyor`, async ({ page }) => {
            const paths = [
                '/', '/produkte', '/produkte/plissees', '/produkt/wabenplissee-sand-thermo',
                '/leistungen', '/leistungen/montage', '/galerie', '/ratgeber',
                '/ueber-uns', '/kontakt', '/aufmass',
                '/seite/impressum', '/seite/datenschutz', '/seite/agb',
                '/seite/widerruf', '/seite/cookies',
            ];

            await dilSec(page, locale);

            for (const path of paths) {
                const resp = await page.goto(path);
                expect(resp.status(), `${path} (${locale}) status`).toBe(200);
                await expect(page.locator('html'), `${path} lang`).toHaveAttribute('lang', locale);
            }
        });
    }

    test('her dil kendi ürün adını gösterir', async ({ page }) => {
        const beklenen = { nl: 'Duette', de: 'Wabenplissee', en: 'Honeycomb', tr: 'Plise' };

        for (const [locale, metin] of Object.entries(beklenen)) {
            await dilSec(page, locale);
            await page.goto('/produkt/wabenplissee-sand-thermo');
            await expect(page.locator('h1.pd-title'), `${locale} ürün adı`).toContainText(metin);
        }
    });

    test('hreflang basılmıyor — her sayfanın tek adresi var', async ({ page }) => {
        await page.goto('/produkte');

        // Dil URL'de olmadığı için bildirilecek alternatif adres yok; hreflang
        // basmak arama motoruna var olmayan adresleri bildirmek olurdu.
        await expect(page.locator('link[rel="alternate"]')).toHaveCount(0);
        await expect(page.locator('link[rel="canonical"]')).toHaveCount(1);
    });

    test('yanıt Vary: Cookie taşıyor', async ({ request }) => {
        const resp = await request.get('/produkte');
        // Olmazsa araya giren önbellek ilk gelen dili herkese servis eder
        expect(resp.headers()['vary'] ?? '').toContain('Cookie');
    });

    test('Hollandaca sayfa (ana dil) Hollandaca arayüz metni gösterir', async ({ page }) => {
        await dilSec(page, 'nl');
        await page.goto('/');
        await expect(page.locator('html')).toHaveAttribute('lang', 'nl');
        await expect(page.locator('.navbar')).toContainText('Producten');
        await expect(page.locator('.stats')).toContainText('Jaar ervaring');
    });

    test('Almanca sayfa ikincil kolonlardan Almanca metni gösterir', async ({ page }) => {
        await dilSec(page, 'de');
        await page.goto('/');
        await expect(page.locator('html')).toHaveAttribute('lang', 'de');
        await expect(page.locator('.navbar')).toContainText('Produkte');
        // İçerik `_de` kolonundan gelmeli, ana dile düşmemeli
        await page.goto('/produkt/aluminiumjalousie-25mm');
        await expect(page.locator('h1.pd-title')).toContainText('Aluminiumjalousie');
    });

    test('Hollandaca ürün sayfası Hollandaca özellik tablosu gösterir', async ({ page }) => {
        await dilSec(page, 'nl');
        await page.goto('/produkt/aluminiumjalousie-25mm');

        // Almanca "Lamellenbreite" değil Hollandaca karşılığı görünmeli
        await expect(page.locator('.pd-attrs')).toContainText('Lamelbreedte');
        await expect(page.locator('.pd-attrs')).not.toContainText('Lamellenbreite');
    });

    test('Hollandaca hata sayfası Hollandaca metin gösterir', async ({ page }) => {
        await dilSec(page, 'nl');
        const resp = await page.goto('/boes-boes-yok');
        expect(resp.status()).toBe(404);
        await expect(page.locator('body')).toContainText('Deze pagina bestaat niet');
    });

    test('İngilizce sayfa İngilizce arayüz metni gösterir', async ({ page }) => {
        await dilSec(page, 'en');
        await page.goto('/');
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
            await dilSec(page, locale);
            await page.goto('/produkt/wabenplissee-sand-thermo');
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

        for (const [locale, metin] of Object.entries(beklenen)) {
            await dilSec(page, locale);
            await page.goto('/produkt/laeufer-vintage-mass');
            await expect(page.locator('.pd-price'), `${locale} fiyat metni`).toContainText(metin);
        }
    });
});

test.describe('Ön yüz — formlar', () => {
    test('ücretsiz ölçü formu kaydedilir', async ({ page }) => {
        await dilSec(page, 'de');
        await page.goto('/aufmass');
        await page.fill('input[name="name"]', 'PW Aufmass Test');
        await page.fill('input[name="phone"]', '+31 6 00 00 00 00');
        await page.fill('input[name="zip"]', '1012');
        await page.check('input[name="privacy"]');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('Vielen Dank');
    });

    test('ürün detayından gelen ölçü formu ürün grubunu ön seçer', async ({ page }) => {
        await page.goto('/aufmass?produkt=Plissees');
        await expect(page.locator('select[name="subject"]')).toHaveValue('Plissees');
    });

    test('gizlilik onayı olmadan iletişim formu reddedilir', async ({ page }) => {
        await page.goto('/kontakt');
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
        await page.goto('/yonetim/products/wabenplissee-sand-thermo/edit');

        await expect(page.locator('textarea[name="attributes_raw"]')).toBeVisible();
        for (const locale of ['de', 'en', 'tr']) {
            await expect(page.locator(`textarea[name="attributes_raw_${locale}"]`)).toBeVisible();
        }

        // TR özellik tablosunu değiştir → sitede TR sayfada görünsün
        await page.fill('textarea[name="attributes_raw_tr"]', 'Malzeme: PW test kumaş');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('güncellendi');

        await dilSec(page, 'tr');
        await page.goto('/produkt/wabenplissee-sand-thermo');
        await expect(page.locator('.pd-attrs')).toContainText('PW test kumaş');

        // Almanca tablo (artık ikincil kolon) etkilenmemeli
        await dilSec(page, 'de');
        await page.goto('/produkt/wabenplissee-sand-thermo');
        await expect(page.locator('.pd-attrs')).toContainText('Lichtdurchlässigkeit');

        // Ana dil (Hollandaca) tablosu da etkilenmemeli
        await dilSec(page, 'nl');
        await page.goto('/produkt/wabenplissee-sand-thermo');
        await expect(page.locator('.pd-attrs')).toContainText('Lichtdoorlatendheid');

        // Kurulumdaki TR tablosunu geri yaz: aksi halde takım ikinci kez
        // çalıştırıldığında "özellik tablosu her dilde çevrilmiş" testi
        // bu testin bıraktığı veriyi bulup patlıyor.
        await page.goto('/yonetim/products/wabenplissee-sand-thermo/edit');
        await page.fill('textarea[name="attributes_raw_tr"]',
            'Malzeme: Petek yapı, arkası yansıtıcı\nIşık geçirgenliği: yarı şeffaftan karartmaya\n'
            + 'Ek özellik: ısı ve soğuk yalıtımı\nMontaj: vidalı veya kıskaçlı aparat\nKumanda: tutamak veya kordon');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('güncellendi');
    });

    test('çeviri alanları kaydedilip ilgili dilde görünüyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/products/aluminiumjalousie-25mm/edit');
        await page.fill('input[name="name_tr"]', 'PW Alüminyum Jaluzi');
        await page.fill('input[name="name_en"]', 'PW Aluminium Blind');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('güncellendi');

        await dilSec(page, 'tr');
        await page.goto('/produkt/aluminiumjalousie-25mm');
        await expect(page.locator('h1.pd-title')).toContainText('PW Alüminyum Jaluzi');

        await dilSec(page, 'en');
        await page.goto('/produkt/aluminiumjalousie-25mm');
        await expect(page.locator('h1.pd-title')).toContainText('PW Aluminium Blind');
    });

    test('boş çeviri ana dile düşer', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/products/store-tuell-goldschimmer/edit');
        await page.fill('input[name="name_en"]', '');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toBeVisible();

        // İngilizcesi boş → ana dil (Hollandaca) adı gösterilmeli
        await dilSec(page, 'en');
        await page.goto('/produkt/store-tuell-goldschimmer');
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
        await dilSec(page, 'de');
        await page.goto('/ratgeber');
        await expect(page.locator('body')).toContainText('PW Testbeitrag');
        await dilSec(page, 'en');
        await page.goto('/ratgeber');
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

        await page.goto('/kontakt');
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
        await page.goto('/aufmass');

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
        await dilSec(page, 'de');
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

    test('sitemap her sayfayı bir kez içerir, dil öneki yok', async ({ request }) => {
        const body = await (await request.get('/sitemap.xml')).text();
        const adresler = [...body.matchAll(/<loc>([^<]+)<\/loc>/g)].map(m => m[1]);

        expect(adresler.length).toBeGreaterThan(20);
        expect(adresler.some(u => u.endsWith('/produkte')), '/produkte listelenmeli').toBe(true);

        // Dil URL'de olmadığı için aynı adres dil başına tekrarlanMAMALI
        expect(new Set(adresler).size, 'sitemap tekrar içermemeli').toBe(adresler.length);

        for (const locale of LOCALES) {
            expect(body, `${locale} öneki bulunmamalı`).not.toContain(`/${locale}/`);
        }
    });
});
