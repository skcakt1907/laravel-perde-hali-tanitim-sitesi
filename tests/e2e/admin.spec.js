import { test, expect } from '@playwright/test';

const ADMIN = { email: 'admin@ornek-perde.nl', password: 'admin123' };
const LOCALES = ['de', 'tr'];

async function loginAs(page, email, password) {
    await page.goto('/giris');
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.click('button[type="submit"]');
    await page.waitForLoadState('networkidle');
}

test.describe('Ön yüz — iki dil', () => {
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

    test('dil değiştirici aynı sayfada dili çevirir', async ({ page }) => {
        await page.goto('/de/produkte/plissees');
        await page.click('.lang-switch a:not(.active)');
        await expect(page).toHaveURL(/\/tr\/produkte\/plissees$/);
    });

    test('Türkçe sayfa Türkçe ürün adını gösterir', async ({ page }) => {
        await page.goto('/tr/produkt/wabenplissee-sand-thermo');
        await expect(page.locator('h1.pd-title')).toContainText('Plise');
    });

    test('geçersiz dil öneki 404', async ({ page }) => {
        const resp = await page.goto('/fr');
        expect(resp.status()).toBe(404);
    });

    test('fiyatsız ürün "Preis auf Anfrage" gösterir', async ({ page }) => {
        await page.goto('/de/produkt/laeufer-vintage-mass');
        await expect(page.locator('.pd-price')).toContainText('Anfrage');
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

    test('ürün formunda iki dilli alanlar var', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/products/create');
        await expect(page.locator('input[name="name"]')).toBeVisible();
        await expect(page.locator('input[name="name_tr"]')).toBeVisible();
        await expect(page.locator('textarea[name="description_tr"]')).toBeVisible();
    });

    test('Türkçe alan kaydedilip TR sayfada görünüyor', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/products/aluminiumjalousie-25mm/edit');
        await page.fill('input[name="name_tr"]', 'PW Alüminyum Jaluzi');
        await page.click('form button[type="submit"]');
        await expect(page.locator('.alert-success')).toContainText('güncellendi');

        await page.goto('/tr/produkt/aluminiumjalousie-25mm');
        await expect(page.locator('h1.pd-title')).toContainText('PW Alüminyum Jaluzi');
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

    test('DE / TR sekmesi alan panelini değiştirir', async ({ page }) => {
        await loginAs(page, ADMIN.email, ADMIN.password);
        await page.goto('/yonetim/settings/hakkimizda');

        await expect(page.locator('textarea[name="hakkimizda_metin"]')).toBeVisible();
        await expect(page.locator('textarea[name="hakkimizda_metin_tr"]')).toBeHidden();

        await page.click('[data-lang-tab="about"][data-locale="tr"]');
        await expect(page.locator('textarea[name="hakkimizda_metin_tr"]')).toBeVisible();
        await expect(page.locator('textarea[name="hakkimizda_metin"]')).toBeHidden();
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

    test('sitemap iki dili de içerir', async ({ request }) => {
        const resp = await request.get('/sitemap.xml');
        const body = await resp.text();
        expect(body).toContain('/de/produkte');
        expect(body).toContain('/tr/produkte');
    });
});
