import { chromium } from '@playwright/test';

const base = process.env.TEST_BASE_URL ?? 'http://127.0.0.1:8005';
const trackingCode = process.env.TEST_TRACKING_CODE ?? '';

const browser = await chromium.launch({ headless: true });
const page = await browser.newPage();
const results = [];

async function runCheck(name, fn) {
    try {
        await fn();
        results.push(`OK|${name}`);
    } catch (error) {
        results.push(`FAIL|${name}|${String(error.message).slice(0, 240)}`);
    }
}

try {
    await page.goto(base, { waitUntil: 'networkidle' });

    await runCheck('hero_pesan_sekarang', async () => {
        await page.locator('a[href="#kategori"]').first().click();
        await page.waitForTimeout(350);
        const scrollY = await page.evaluate(() => window.scrollY);
        if (scrollY < 200) {
            throw new Error(`scroll kecil: ${scrollY}`);
        }
    });

    await page.goto(base, { waitUntil: 'networkidle' });

    await runCheck('hero_lihat_koleksi', async () => {
        await page.locator('a[href="#galeri"]').first().click();
        await page.waitForTimeout(350);
        const hash = await page.evaluate(() => window.location.hash);
        if (hash !== '#galeri') {
            throw new Error(`hash salah: ${hash}`);
        }
    });

    await page.goto(base, { waitUntil: 'networkidle' });

    await runCheck('quicklink_papan', async () => {
        await page.locator('a[href="#kategori-papan-ucapan"]').first().click();
        await page.waitForTimeout(350);
        const hash = await page.evaluate(() => window.location.hash);
        if (hash !== '#kategori-papan-ucapan') {
            throw new Error(`hash salah: ${hash}`);
        }
    });

    await page.goto(base, { waitUntil: 'networkidle' });

    await runCheck('category_card_papan', async () => {
        await page.locator('a.category-card').first().click();
        await page.waitForTimeout(350);
        const hash = await page.evaluate(() => window.location.hash);
        if (hash !== '#kategori-papan-ucapan') {
            throw new Error(`hash salah: ${hash}`);
        }
    });

    await page.goto(base, { waitUntil: 'networkidle' });

    await runCheck('lihat_semua_link', async () => {
        const button = page.locator('[data-scroll-products]').first();
        const scroller = page.locator('.product-scroll-container').first();
        const before = await scroller.evaluate((element) => element.scrollLeft);
        await button.click();
        await page.waitForTimeout(350);
        const after = await scroller.evaluate((element) => element.scrollLeft);
        if (after <= before) {
            throw new Error(`scroller tidak bergeser: ${before} -> ${after}`);
        }
    });

    await page.goto(base, { waitUntil: 'networkidle' });

    await runCheck('product_pesan_redirect', async () => {
        const button = page.locator('[data-product-card] button[data-order-url]').first();
        await button.scrollIntoViewIfNeeded();
        await button.evaluate((element) => element.click());
        await page.waitForLoadState('networkidle');
        if (!page.url().includes('/order')) {
            throw new Error(`tidak redirect ke order: ${page.url()}`);
        }
    });

    await page.goto(base, { waitUntil: 'networkidle' });

    await runCheck('gallery_filter_dekorasi', async () => {
        await page.getByRole('button', { name: 'Dekorasi' }).click();
        await page.waitForTimeout(350);
        const activeText = await page.locator('.filter-btn.active').textContent();
        if (!activeText?.includes('Dekorasi')) {
            throw new Error(`filter aktif salah: ${activeText}`);
        }
    });

    await page.goto(base, { waitUntil: 'networkidle' });

    await runCheck('tracking_button_valid_code', async () => {
        if (!trackingCode) {
            throw new Error('tracking code kosong');
        }
        await page.locator('#trackingInput').fill(trackingCode);
        await page.locator('#trackingBtn').click();
        await page.waitForTimeout(1200);
        const resultVisible = await page.locator('#trackingResult').isVisible();
        if (!resultVisible) {
            throw new Error('hasil tracking tidak muncul');
        }
    });

    await page.goto(base, { waitUntil: 'networkidle' });

    await runCheck('contact_whatsapp', async () => {
        const [popup] = await Promise.all([
            page.waitForEvent('popup'),
            page.locator('a[href^="https://wa.me/"]').first().click(),
        ]);
        const popupUrl = popup.url();
        await popup.close();
        if (!popupUrl.includes('wa.me') && !popupUrl.includes('api.whatsapp.com')) {
            throw new Error(`popup bukan whatsapp: ${popupUrl}`);
        }
    });

    console.log(results.join('\n'));
} finally {
    await browser.close();
}
