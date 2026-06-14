const express = require('express');
const { chromium } = require('playwright-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
chromium.use(StealthPlugin());

const app = express();
app.use(express.json());

const PORT = 3333;

app.get('/health', (req, res) => res.json({ status: 'ok' }));

app.post('/tiktok-product', async (req, res) => {
    const { url, proxy } = req.body;

    if (!url) return res.status(400).json({ error: 'url is required' });

    const productIdMatch = url.match(/product\/(\d+)/);
    if (!productIdMatch) return res.status(400).json({ error: 'Product ID not found in URL' });
    const productId = productIdMatch[1];

    const launchOptions = {
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-blink-features=AutomationControlled',
        ],
    };

    if (proxy) {
        const normalized = normalizeProxy(proxy);
        if (normalized) launchOptions.proxy = { server: normalized };
    }

    let browser;
    try {
        browser = await chromium.launch(launchOptions);
        const context = await browser.newContext({
            userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            locale: 'en-US',
            viewport: { width: 1280, height: 800 },
            extraHTTPHeaders: {
                'Accept-Language': 'en-US,en;q=0.9',
            },
        });

        // Ẩn dấu hiệu automation
        await context.addInitScript(() => {
            Object.defineProperty(navigator, 'webdriver', { get: () => undefined });
            window.chrome = { runtime: {} };
        });

        const page = await context.newPage();

        // Intercept và bỏ qua assets không cần thiết (nhanh hơn)
        await page.route('**/*.{png,jpg,jpeg,gif,svg,woff,woff2,mp4,webm}', route => route.abort());

        await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 30000 });

        // Nếu gặp security check, chờ nó tự resolve (TikTok thường auto-redirect sau vài giây)
        const maxWait = 15000;
        const interval = 1000;
        let waited = 0;
        while (waited < maxWait) {
            const title = await page.title();
            if (!title.toLowerCase().includes('security') && title !== '') break;
            await page.waitForTimeout(interval);
            waited += interval;
        }

        // Thử lấy __NEXT_DATA__
        const nextData = await page.evaluate(() => {
            const el = document.getElementById('__NEXT_DATA__');
            return el ? JSON.parse(el.textContent) : null;
        });

        if (nextData) {
            const pageProps = nextData?.props?.pageProps ?? {};
            const product = pageProps.productDetail
                ?? pageProps.product
                ?? pageProps.initialData?.product
                ?? pageProps.data?.product
                ?? null;

            if (product) {
                return res.json(formatProduct(product, productId));
            }

            // Trả về keys để debug
            return res.json({
                debug: true,
                product_id: productId,
                page_props_keys: Object.keys(pageProps),
                next_data_keys: Object.keys(nextData?.props ?? {}),
            });
        }

        // Thử lấy từ page title và meta nếu không có __NEXT_DATA__
        const metaData = await page.evaluate(() => ({
            title: document.title,
            description: document.querySelector('meta[name="description"]')?.content,
            ogTitle: document.querySelector('meta[property="og:title"]')?.content,
            ogImage: document.querySelector('meta[property="og:image"]')?.content,
            ogPrice: document.querySelector('meta[property="product:price:amount"]')?.content,
            bodyText: document.body?.innerText?.slice(0, 200),
        }));

        if (metaData.ogTitle || metaData.title) {
            return res.json({
                product_id: productId,
                title: metaData.ogTitle ?? metaData.title,
                price: metaData.ogPrice ?? null,
                description: metaData.description ?? null,
                images: metaData.ogImage ? [metaData.ogImage] : [],
            });
        }

        return res.json({
            error: 'Could not extract product data',
            product_id: productId,
            page_title: metaData.title,
            body_preview: metaData.bodyText,
        });

    } catch (err) {
        return res.status(500).json({ error: err.message });
    } finally {
        if (browser) await browser.close();
    }
});

function formatProduct(product, productId) {
    const title = product.title ?? product.name ?? product.product_name ?? 'No title';

    let price = null;
    if (product.price) price = product.price;
    else if (product.skus?.[0]?.price?.original_price) price = product.skus[0].price.original_price / 100;
    else if (product.min_price) price = product.min_price;

    let images = [];
    if (product.images) {
        images = product.images.map(img => img.url ?? img.src ?? (typeof img === 'string' ? img : null)).filter(Boolean);
    } else if (product.main_images) {
        images = product.main_images.map(img => img.url ?? img).filter(Boolean);
    } else if (product.image_urls) {
        images = product.image_urls;
    }

    return {
        product_id: productId,
        title,
        price,
        description: product.description ?? product.desc ?? null,
        images,
    };
}

function normalizeProxy(proxy) {
    if (!proxy) return null;
    if (/^https?:\/\/.+@.+:\d+$/.test(proxy)) return proxy;
    const parts = proxy.split(':');
    if (parts.length === 4) {
        const [host, port, user, pass] = parts;
        return `http://${user}:${pass}@${host}:${port}`;
    }
    if (parts.length === 2) return `http://${proxy}`;
    return proxy;
}

app.listen(PORT, () => {
    console.log(`Playwright service running on http://localhost:${PORT}`);
});
