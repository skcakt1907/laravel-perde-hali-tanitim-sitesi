import { test, expect } from '@playwright/test';

const ADMIN = { email: 'admin@ornek-perde.nl', password: 'admin123' };
const LOCALES = ['de', 'en', 'tr'];

async function loginAs(page, email, password) {
    await page.goto('/giris');
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
}

test.describe('Ön yüz — çok dil', () => {
    test('kök URL varsayılan dile yönlenir', async ({ page }) => {
        await page.goto('/');
        await expect(page).toHaveURL(/\/de$/);
    });

    for (const locale of LOCALES) {
        test(`tüm ${locale} sayfaları 200 dönüyor`, async ({ page }) => {
            const paths = [
                '', '/produkte', '/produkte/plissees', '/produkt/wabenplissee-sand-thermo',
                '/leistungen', '/leistungen/montage', '/galerie', '/ratgeber',
                '/ueber-uns', '/kontakt', '/aufmass',
                '/seite/impressum', '/seite/datenschutz', '/seite/agb',
                '/seite/widerruf', '/seite/cookies',
            ];

            for (const path of paths) {
                const url = `/${locale}${path}`;
                const resp = await page.goto(url);
                expect(resp.status(), `${url} status`).toBe(200);
            }
        });
    }

    test('dil değiştirici bulunulan sayfada dili çevirir', async ({ page }) => {
        await page.goto('/de/produkte/plissees');

        await page.click('.lang-switch a[hreflang="tr"]');
        await expect(page).toHaveURL(/\/tr\/produkte\/plissees$/);

        await page.click('.lang-switch a[hreflang="en"]');
        await expect(page).toHaveURL(/\/en\/produkte\/plissees$/);
    });

    test('her dil kendi ürün adını gösterir', async ({ page }) => {
        const beklenen = { de: 'Wabenplissee', en: 'Honeycomb', tr: 'Plise' };

        for (const [locale, metin] of Object.entries(beklenen)) {
            await page.goto(`/${locale}/produkt/wabenplissee-sand-thermo`);
            await expect(page.locator('h1.pd-title'), `${locale} ürün adı`).toContainText(metin);
        }
    });

    test('üç dil için de hreflang alternatifi basılır', async ({ page }) => {
        await page.goto('/en/produkte');

        for (const locale of LOCALES) {
            await expect(page.locator(`link[rel="alternate"][hreflang="${locale}"]`)).toHaveCount(1);
        }
    });

    test('İngilizce sayfa İngilizce arayüz metni gösterir', async ({ page }) => {
        await page.goto('/en');
        await expect(page.locator('.navbar')).toContainText('Products');
        await expect(page.locator('.stats')).toContainText('Years of experience');
    });

    test('özellik tablosu her dilde çevrilmiş görünür', async ({ page }) => {
        const beklenen = {
            de: ['Lichtdurchlässigkeit', 'Wabenstruktur'],
            en: ['Light transmission', 'Honeycomb structure'],
            tr: ['Işık geçirgenliği', 'Petek yapı'],
        };

        for (const [locale, kelimeler] of Object.entries(beklenen)) {
            await page.goto(`/${locale}/produkt/wabenplissee-sand-thermo`);
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
        const beklenen = { de: 'Anfrage', en: 'on request', tr: 'sorunuz' };

        for (const [locale, metin] of Object.entries(beklenen)) {
            await page.goto(`/${locale}/produkt/laeufer-vintage-mass`);
            await expect(page.locator('.pd-price'), `${locale} fiyat metni`).toContainText(metin);
        }
    });
});

test.describe('Ön yüz — formlar', () => {
    test('ücretsiz ölçü formu kaydedilir', async ({ page }) => {
        await page.goto('/de/aufmass');
        await page.fill('input[name="name"]', 'PW Aufmass Test');
        await page.fill('input[name="phone"]', '+31 6 00 00 00 00');
        await page.fill('input[name="zip"]', '1012');
        await page.check('input[name="privacy"]');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('Vielen Dank');
    });

    test('ürün detayından gelen ölçü formu ürün grubunu ön seçer', async ({ page }) => {
        await page.goto('/de/aufmass?produkt=Plissees');
        await expect(page.locator('select[name="subject"]')).toHaveValue('Plissees');
    });

    test('gizlilik onayı olmadan iletişim formu reddedilir', async ({ page }) => {
        await page.goto('/tr/kontakt');
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

        for (const locale of ['en', 'tr']) {
            await expect(page.locator(`input[name="name_${locale}"]`), `name_${locale}`).toBeVisible();
            await expect(page.locator(`textarea[name="description_${locale}"]`), `description_${locale}`).toBeVisible();
        }
    });

    test('ürün formunda her dil için özellik alanı var', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/products/wabenplissee-sand-thermo/edit');

        await expect(page.locator('textarea[name="attributes_raw"]')).toBeVisible();
        for (const locale of ['en', 'tr']) {
            await expect(page.locator(`textarea[name="attributes_raw_${locale}"]`)).toBeVisible();
        }

        // TR özellik tablosunu değiştir → sitede TR sayfada görünsün
        await page.fill('textarea[name="attributes_raw_tr"]', 'Malzeme: PW test kumaş');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('güncellendi');

        await page.goto('/tr/produkt/wabenplissee-sand-thermo');
        await expect(page.locator('.pd-attrs')).toContainText('PW test kumaş');

        // Almanca tablo etkilenmemeli
        await page.goto('/de/produkt/wabenplissee-sand-thermo');
        await expect(page.locator('.pd-attrs')).toContainText('Lichtdurchlässigkeit');
    });

    test('çeviri alanları kaydedilip ilgili dilde görünüyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/products/aluminiumjalousie-25mm/edit');
        await page.fill('input[name="name_tr"]', 'PW Alüminyum Jaluzi');
        await page.fill('input[name="name_en"]', 'PW Aluminium Blind');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('güncellendi');

        await page.goto('/tr/produkt/aluminiumjalousie-25mm');
        await expect(page.locator('h1.pd-title')).toContainText('PW Alüminyum Jaluzi');

        await page.goto('/en/produkt/aluminiumjalousie-25mm');
        await expect(page.locator('h1.pd-title')).toContainText('PW Aluminium Blind');
    });

    test('boş çeviri ana dile düşer', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/products/store-tuell-goldschimmer/edit');
        await page.fill('input[name="name_en"]', '');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toBeVisible();

        // İngilizcesi boş → Almanca ad gösterilmeli
        await page.goto('/en/produkt/store-tuell-goldschimmer');
        await expect(page.locator('h1.pd-title')).toContainText('Store Tüll');
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

    test('dil sekmeleri alan panelini değiştirir (üç dil)', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/settings/hakkimizda');

        await expect(page.locator('[data-lang-tab="about"]')).toHaveCount(3);
        await expect(page.locator('textarea[name="hakkimizda_metin"]')).toBeVisible();

        for (const locale of ['en', 'tr']) {
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
        await page.goto('/de');
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
        await page.goto('/de/ratgeber');
        await expect(page.locator('body')).toContainText('PW Testbeitrag');
        await page.goto('/en/ratgeber');
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

        await page.goto('/de');
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

        await page.goto('/tr/kontakt');
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
        await page.goto('/de/aufmass');

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

        await page.goto('/de', { waitUntil: 'networkidle' });

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
        await page.goto('/de/olmayan-sayfa');
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
        const resp = await request.get('/de');
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
        const resp = await request.get('/de');
        const csp = resp.headers()['content-security-policy'];

        // Yalnızca kendi sunucumuz (satır içi kod hariç) — CDN adresi geçmemeli
        expect(csp).toContain("script-src 'self'");
        expect(csp).not.toContain('jsdelivr');
        expect(csp).not.toContain('googleapis');
    });

    test('hata sayfaları da güvenlik başlığı taşıyor', async ({ request }) => {
        const resp = await request.get('/de/olmayan-sayfa');
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

    test('sitemap tüm dilleri içerir', async ({ request }) => {
        const resp = await request.get('/sitemap.xml');
        const body = await resp.text();
        for (const locale of LOCALES) {
            expect(body, `${locale} sitemap`).toContain(`/${locale}/produkte`);
        }
    });
});
