<?php
namespace Vanguard\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie\CookieJar;
use Vanguard\Models\Templetes;
use Illuminate\Support\Facades\Auth;
use Vanguard\Product;
use Vanguard\Services\ImageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TiktokCrawlController extends Controller
{
    public function __construct(private readonly ImageService $imageService) {}

    public function index(Request $request)
    {
        $query = Templetes::query();
        $user = Auth::user();
        $role = $user->role->name;
        if ($role == 'Seller' || $role == 'Staff') {
            $query->whereHas('stafftemplate', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
            $query->orWhere('user_id', $user->id);
        }
        $templates = $query->get();

        $importedProduct = null;
        if ($token = $request->query('import')) {
            $importedProduct = Cache::pull('tiktok_import_' . $token);
        }

        return view('craw.tiktok', compact('templates', 'importedProduct'));
    }

    public function getProduct(Request $request)
    {
        $url   = $request->input('url');
        $proxy = $request->input('proxy');
        $debug = $request->boolean('debug');

        // Thử Playwright service trước
        try {
            $pwResponse = Http::timeout(35)->post('http://localhost:3333/tiktok-product', [
                'url'   => $url,
                'proxy' => $proxy,
            ]);
            if ($pwResponse->successful()) {
                $data = $pwResponse->json();
                if (!empty($data['title']) || !empty($data['debug'])) {
                    return response()->json($data);
                }
            }
        } catch (\Exception $e) {
            // Playwright service chưa chạy, fallback xuống HTTP
        }

        $proxy = $this->normalizeProxy($proxy);

        preg_match('/product\/(\d+)/', $url, $matches);
        if (!isset($matches[1])) {
            return response()->json(['error' => 'Product ID not found in URL']);
        }
        $productId = $matches[1];

        $headers = [
            'User-Agent'               => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept'                   => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
            'Accept-Language'          => 'en-US,en;q=0.9',
            'Accept-Encoding'          => 'gzip, deflate, br',
            'Cache-Control'            => 'max-age=0',
            'sec-ch-ua'                => '"Chromium";v="124", "Google Chrome";v="124", "Not-A.Brand";v="99"',
            'sec-ch-ua-mobile'         => '?0',
            'sec-ch-ua-platform'       => '"Windows"',
            'sec-fetch-dest'           => 'document',
            'sec-fetch-mode'           => 'navigate',
            'sec-fetch-site'           => 'none',
            'sec-fetch-user'           => '?1',
            'upgrade-insecure-requests'=> '1',
        ];

        $guzzleOptions = [
            'headers'         => $headers,
            'cookies'         => new CookieJar(),
            'allow_redirects' => true,
            'verify'          => false,
            'timeout'         => 20,
        ];
        if ($proxy) $guzzleOptions['proxy'] = $proxy;

        $guzzle = new GuzzleClient($guzzleOptions);

        // Warm-up: lấy cookies thật từ TikTok trước
        try { $guzzle->get('https://www.tiktok.com/'); } catch (\Exception $e) {}

        $urlsToTry = array_unique(array_filter([
            $url,
            "https://www.tiktok.com/view/product/{$productId}",
        ]));

        foreach ($urlsToTry as $tryUrl) {
            try {
                $response = $guzzle->get($tryUrl);
                $html     = (string) $response->getBody();

                if ($debug) {
                    return $this->debugResponse($html, $productId);
                }

                $product = $this->extractFromNextData($html)
                    ?? $this->extractFromScripts($html);

                if ($product) {
                    return $this->formatProductData($product, $productId);
                }
            } catch (\Exception $e) {
                if ($debug) {
                    return response()->json(['exception' => $e->getMessage()]);
                }
            }
        }

        // Fallback: thử SOCKS5 nếu proxy HTTP fail
        if ($proxy && str_starts_with($proxy, 'http://')) {
            $socks5Proxy = 'socks5' . substr($proxy, 4);
            $guzzleOptions['proxy'] = $socks5Proxy;
            $guzzle2 = new GuzzleClient($guzzleOptions);
            try { $guzzle2->get('https://www.tiktok.com/'); } catch (\Exception $e) {}

            try {
                $response = $guzzle2->get("https://www.tiktok.com/view/product/{$productId}");
                $html     = (string) $response->getBody();

                $product = $this->extractFromNextData($html)
                    ?? $this->extractFromScripts($html);

                if ($product) return $this->formatProductData($product, $productId);
                if ($debug)   return $this->debugResponse($html, $productId);
            } catch (\Exception $e) {}
        }

        return response()->json([
            'error'      => 'TikTok is blocking the request. TikTok dùng TLS fingerprinting — PHP/cURL bị detect. Thử bật debug=1 để xem HTML trả về.',
            'product_id' => $productId,
        ]);
    }

    public function importFromBrowser(Request $request)
    {
        $data = [
            'product_id'  => $request->input('product_id'),
            'title'       => $request->input('title'),
            'price'       => $request->input('price'),
            'description' => $request->input('description'),
            'images'      => $request->input('images', []),
        ];

        $token = Str::random(32);
        Cache::put('tiktok_import_' . $token, $data, now()->addMinutes(30));

        return response()->json([
            'ok'       => true,
            'redirect' => url('/tiktok-crawl') . '?import=' . $token,
        ])->header('Access-Control-Allow-Origin', '*');
    }

    private function debugResponse(string $html, string $productId): \Illuminate\Http\JsonResponse
    {
        $hasNextData = str_contains($html, '__NEXT_DATA__');
        $nextDataKeys = [];

        if ($hasNextData && preg_match('/<script id="__NEXT_DATA__" type="application\/json">(.*?)<\/script>/s', $html, $m)) {
            $nextData = json_decode($m[1], true);
            $nextDataKeys = array_keys($nextData['props']['pageProps'] ?? []);
        }

        return response()->json([
            'product_id'     => $productId,
            'html_length'    => strlen($html),
            'has_next_data'  => $hasNextData,
            'page_props_keys'=> $nextDataKeys,
            'html_preview'   => substr(strip_tags($html), 0, 300),
        ]);
    }

    private function extractFromNextData(string $html): ?array
    {
        if (!preg_match('/<script id="__NEXT_DATA__" type="application\/json">(.*?)<\/script>/s', $html, $m)) {
            return null;
        }
        $nextData = json_decode($m[1], true);
        if (!$nextData) return null;

        $pageProps = $nextData['props']['pageProps'] ?? [];
        return $pageProps['productDetail']
            ?? $pageProps['product']
            ?? $pageProps['initialData']['product']
            ?? $pageProps['data']['product']
            ?? null;
    }

    private function extractFromScripts(string $html): ?array
    {
        // Try window.__STORE__, window.__data__, SIGI_STATE, etc.
        $patterns = [
            '/window\.__STORE__\s*=\s*({.+?});\s*(?:<\/script>|window\.)/s',
            '/window\.__data__\s*=\s*({.+?});\s*<\/script>/s',
            '/"product"\s*:\s*(\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\})/s',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $m)) {
                $data = json_decode($m[1], true);
                if ($data) {
                    // nếu là wrapper object, tìm key product bên trong
                    return $data['product'] ?? $data['productDetail'] ?? $data ?? null;
                }
            }
        }
        return null;
    }

    private function normalizeProxy(?string $proxy): ?string
    {
        if (!$proxy) return null;

        // Already in correct format: http://user:pass@host:port
        if (preg_match('/^https?:\/\/.+@.+:\d+$/', $proxy)) {
            return $proxy;
        }

        // Format: host:port:user:pass → http://user:pass@host:port
        $parts = explode(':', $proxy);
        if (count($parts) === 4) {
            [$host, $port, $user, $pass] = $parts;
            return "http://{$user}:{$pass}@{$host}:{$port}";
        }

        // Format: host:port (no auth)
        if (count($parts) === 2) {
            return "http://{$proxy}";
        }

        return $proxy;
    }

    private function formatProductData(array $product, string $productId)
    {
        $title = $product['title'] ?? $product['name'] ?? $product['product_name'] ?? 'No title';

        $price = null;
        if (isset($product['price'])) {
            $price = $product['price'];
        } elseif (isset($product['skus'][0]['price']['original_price'])) {
            $price = $product['skus'][0]['price']['original_price'] / 100;
        } elseif (isset($product['min_price'])) {
            $price = $product['min_price'];
        }

        $images = [];
        if (isset($product['images'])) {
            foreach ($product['images'] as $img) {
                $images[] = $img['url'] ?? $img['src'] ?? (is_string($img) ? $img : null);
            }
        } elseif (isset($product['main_images'])) {
            foreach ($product['main_images'] as $img) {
                $images[] = $img['url'] ?? $img;
            }
        } elseif (isset($product['image_urls'])) {
            $images = $product['image_urls'];
        }

        $images = array_values(array_filter($images));

        return response()->json([
            'product_id'  => $productId,
            'title'       => $title,
            'price'       => $price,
            'description' => $product['description'] ?? $product['desc'] ?? null,
            'images'      => $images,
        ]);
    }

    private function randomId(): string
    {
        return str_pad(mt_rand(0, 99999999999999), 14, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'     => 'required|string',
            'title'          => 'required|string',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric',
            'image_files'    => 'nullable|array',
            'images'         => 'nullable',
            'selectedtemplete' => 'required|numeric',
        ]);

        $templetes = Templetes::find($data['selectedtemplete']);
        if ($templetes) {
            $templeteData = json_decode($templetes->data, true);
            $product = $templeteData['product'];
            $product['title'] = $data['title'];

            $getimages = json_decode($request->input('images'), true) ?? [];
            $images = [];
            foreach ($getimages as $key => $image) {
                $urlDesign = $this->imageService->uploadImage($image['url'], "{$this->randomId()}_{$key}.png");
                $images[] = [
                    "id"          => "45616820869642",
                    "product_id"  => null,
                    "position"    => 1,
                    "created_at"  => "2019-05-30T04:33:43+07:00",
                    "updated_at"  => "2019-10-22T09:03:30+07:00",
                    "width"       => 1155,
                    "height"      => 1155,
                    "src"         => $urlDesign,
                    "variant_ids" => [],
                ];
            }

            foreach (($data['image_files'] ?? []) as $file) {
                $imgName = "image_" . random_int(1000000, 99999999) . ".jpg";
                Storage::disk('b2')->put($imgName, file_get_contents($file), 'public');
                $url = Storage::disk('b2')->temporaryUrl($imgName, '', []);
                $urlDesign = substr($url, 0, strpos($url, '?X-Amz'));
                $images[] = [
                    "id"          => "45616820869642",
                    "product_id"  => null,
                    "position"    => 1,
                    "created_at"  => "2019-05-30T04:33:43+07:00",
                    "updated_at"  => "2019-10-22T09:03:30+07:00",
                    "width"       => 1155,
                    "height"      => 1155,
                    "src"         => $urlDesign,
                    "variant_ids" => [],
                ];
            }

            $product['images'] = $images;
            $product['image'] = $images[0] ?? null;

            $createProduct = new Product();
            $createProduct->data = json_encode(['product' => $product]);
            $createProduct->templete_id = $templetes->id;
            $createProduct->user_id = Auth::user()->id;
            $createProduct->save();
        }

        return response()->json(['message' => 'Product saved successfully']);
    }
}
