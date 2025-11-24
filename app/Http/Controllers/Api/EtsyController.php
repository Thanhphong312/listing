<?php
namespace Vanguard\Http\Controllers\Api;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DOMDocument;
use DOMXPath;
use Vanguard\Models\ProductCrawl;
use Vanguard\Models\DesignCrawl;
class EtsyController extends Controller
{

public function getProduct(Request $request)
{
    $url = $request->input('url');
    $proxy = $request->input('proxy');
    // Lấy listing_id từ URL
    preg_match('/listing\/(\d+)/', $url, $matches);
    if (!isset($matches[1])) {
        return response()->json(['error' => 'Listing ID not found in URL']);
    }

    $listingId = $matches[1];
    $apiUrl = "https://www.etsy.com/api/v3/ajax/public/listings/{$listingId}";

    // Gọi API với proxy US
    $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept' => 'application/json',
        ])
        ->withOptions([
            // Proxy US 
            'proxy' => $proxy,
        ])
        ->get($apiUrl);

    if ($response->successful()) {
        $data = $response->json();
        return response()->json([
            'title' => $data['title'] ?? 'No title found',
            'listing_id' => $listingId,
            'price' => $data['price'] ?? null, // nếu có field price
            'currency_code' => $data['currency_code'] ?? null,
            'images' => $data['images'] ?? null,
            'description' => $data['description'] ?? null
        ]);
    }

    return response()->json(['error' => 'Failed to fetch listing data']);
}
public function index()
{
    return view('craw.index');
}

public function store(Request $request)
    {
        $data = $request->validate([
            'listing_id'   => 'required|numeric',
            'title'        => 'required|string',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric',
            'images'       => 'nullable|array',   
            'images.*.url' => 'required_with:images|string',
            'images.*.type'=> 'required_with:images|in:1,2',
        ]);
        

        // Tìm hoặc tạo mới product
        $product = ProductCrawl::updateOrCreate(
            ['listing_id' => $data['listing_id']],
            [
                'title'       => $data['title'],
                'description' => $data['description'],
                'price'       => $data['price']
            ]
        );

        // Xóa các ảnh cũ trước khi lưu mới
        $product->designs()->delete();

        foreach ($data['images'] as $img) {
            $product->designs()->create([
                'url'  => $img['url'],
                'type' => $img['type']
            ]);
        }

        return response()->json([
            'message' => 'Product saved successfully',
            'product' => $product->load('designs')
        ]);
    }



    public function listProducts(Request $request)
{
    $query = ProductCrawl::with('designs');

    if ($request->filled('listing_id')) {
        $query->where('listing_id', 'like', "%{$request->listing_id}%");
    }

    if ($request->filled('title')) {
        $query->where('title', 'like', "%{$request->title}%");
    }

    $products = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('craw.list', compact('products'));
}

    
    
public function getProductImages($id)
    {
        $product = \Vanguard\Models\ProductCrawl::with('designs')->findOrFail($id);
        return response()->json($product->designs);
    }
    
    
}
