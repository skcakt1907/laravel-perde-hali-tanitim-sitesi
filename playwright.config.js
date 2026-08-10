import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
    testDir: './tests/e2e',
    timeout: 30000,
    fullyParallel: false,
    reporter: [['list']],
    use: {
        // Testlerdeki yollar kök ('/de/...') olduğu için baseURL alt klasör İÇERMEMELİ.
        // Çalıştırmadan önce: php artisan serve
        baseURL: process.env.BASE_URL || 'http://127.0.0.1:8000',
        screenshot: 'only-on-failure',
        trace: 'retain-on-failure',
    },
    projects: [
        { name: 'chromium', use: { ...devices['Desktop Chrome'] } },
    ],
});
