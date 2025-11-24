<?php
namespace Vanguard\Http\Controllers\Web;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DOMDocument;
use DOMXPath;
use Vanguard\Models\ProductCrawl;
use Vanguard\Models\DesignCrawl;
use Vanguard\Models\Templetes;
use Illuminate\Support\Facades\Auth;
use Vanguard\Product;
use Vanguard\Services\ImageService;
use Illuminate\Support\Facades\Storage;

class EtsyController extends Controller
{
    public function __construct(private readonly ImageService $imageService)
    {

    }

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
        $query = Templetes::query();
        $user = Auth::user();
        $role = $user->role->name;
        if ($role == 'Seller' || $role == 'Staff') {
            // $query->where('user_id', $user->id);
            $query->whereHas('stafftemplate', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
            $query->orWhere('user_id', $user->id);

        }
        $templates = $query->get();
        return view('craw.index', compact('templates'));
    }
    private function updateBlaze($url, $id): string
    {
        // return $this->imageservice->resizeImage($url, "{$this->product_id}_{$id}.png", 1155, 1155);
        return $this->imageservice->uploadImage($url, "{$this->randomId()}_{$id}.png");
    }
        function randomId()
    {
        $randomNumber = mt_rand(0, 99999999999999); // Generate a random number up to 14 digits
        return str_pad($randomNumber, 14, '0', STR_PAD_LEFT); // Pad the number with leading zeros if necessary
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'listing_id' => 'required|numeric',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image_files' => 'nullable|array',
            'images' => 'nullable',
            'selectedtemplete' => 'required|numeric'
        ]);

        // dd($data);
        $templetes = Templetes::find($data['selectedtemplete']);
        if ($templetes) {
            $templeteData = json_decode($templetes->data, true);
            $product = $templeteData['product'];
            $product['title'] = $data['title'];

            
            $getimages = json_decode($request->input('images'), true);
            $images = [];
            foreach ($getimages as $key => $image) {
                $urlDesign = $this->imageService->uploadImage($image['url'], "{$this->randomId()}_{$key}.png");
                $images[] = [
                    "id" => "45616820869642",
                    "product_id" => null,
                    "position" => 1,
                    "created_at" => "2019-05-30T04:33:43+07:00",
                    "updated_at" => "2019-10-22T09:03:30+07:00",
                    "width" => 1155,
                    "height" => 1155,
                    "src" => $urlDesign,
                    "variant_ids" => []
                ];
            }

            foreach ($data['image_files'] as $file) {
                $imgName = "image_".random_int(1000000,99999999).".jpg";
                Storage::disk('b2')->put($imgName, file_get_contents($file), 'public');
                
                // Generate a temporary URL for the image
                $url = Storage::disk('b2')->temporaryUrl($imgName, '', []);

                $urlDesign = substr($url, 0, strpos($url, '?X-Amz'));

                $images[] = [
                    "id" => "45616820869642",
                    "product_id" => null,
                    "position" => 1,
                    "created_at" => "2019-05-30T04:33:43+07:00",
                    "updated_at" => "2019-10-22T09:03:30+07:00",
                    "width" => 1155,
                    "height" => 1155,
                    "src" => $urlDesign,
                    "variant_ids" => []
                ];
            }
            $product['images'] = $images;
            $product['image'] = $images[0];
            // dd($product);
            $createProduct = new Product();
            $createProduct->data = json_encode([
                'product' => $product,
            ]);

            $createProduct->templete_id = $templetes->id;
            $createProduct->user_id = Auth::user()->id;
            $createProduct->save();

        }
        // Tìm hoặc tạo mới product
        // $product = ProductCrawl::updateOrCreate(
        //     ['listing_id' => $data['listing_id']],
        //     [
        //         'title' => $data['title'],
        //         'description' => $data['description'],
        //         'price' => $data['price']
        //     ]
        // );

        // // Xóa các ảnh cũ trước khi lưu mới
        // $product->designs()->delete();

        // foreach ($data['images'] as $img) {
        //     $product->designs()->create([
        //         'url' => $img['url'],
        //         'type' => $img['type']
        //     ]);
        // }

        return response()->json([
            'message' => 'Product saved successfully',
            // 'product' => $product->load('designs')
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
