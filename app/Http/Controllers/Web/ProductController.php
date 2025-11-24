<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Resources\ProductResource;
use Vanguard\Jobs\addMockupProductJob;
use Vanguard\Jobs\addMockupProductJob2;
use Vanguard\Jobs\PostProductToStoreATiktok;
use Vanguard\Models\Categories;
use Vanguard\Models\MetaImages;
use Vanguard\Models\Store\Store;
use Vanguard\Models\StoreProducts;
use Vanguard\Product;
use Vanguard\Jobs\PostProductToStore;
use Illuminate\Support\Facades\Storage;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Vanguard\Models\Templetes;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        $user = Auth::user();
        $role = $user->role->name;
        if ($role == 'Seller' || $role == 'Staff') {
            $query->where('user_id', $user->id);
        }
        if (isset($request->id) && !empty($request->id)) {
            $query->where('id', $request->id);
        }
        if (isset($request->staff_id) && !empty($request->staff_id)) {
            $query->where('user_id', $request->staff_id);
        }
        if (isset($request->title) && !empty($request->title)) {
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.product.title')) = ?", [$request->title]);
        }

        if (isset($request->seller_id) && !empty($request->seller_id)) {
            $query->where('user_id', $request->seller_id);
        }
        // $query->where('type',0);

        if (isset($request->store_id) && !empty($request->store_id)) {
            $query->whereHas('storeproducts', function ($storerq) use ($request) {
                $storerq->where('store_id', $request->store_id);
            });
        }
        if (isset($request->datefrom) && !empty($request->datefrom)) {
            $arrDate = [$request->datefrom, $request->dateto];
            // dd($arrDate);
            if ($arrDate[1] != null) {
                $query->whereBetween('created_at', [
                    Carbon::parse($arrDate[0])->toDateTimeString(),
                    Carbon::parse($arrDate[1])->endOfDay()->toDateTimeString()
                ]);
            } else {
                // echo Carbon::parse($arrDate[0])->startOfDay()->toDateTimeString();
                // dd();
                $query->whereBetween('created_at', [
                    Carbon::parse($arrDate[0])->startOfDay()->toDateTimeString(),
                    Carbon::parse($arrDate[0])->addHour(23)->addSecond(59)->addMinute(59)->toDateTimeString()
                ]);
            }
        }
        $products = $query->orderBy('id', 'desc')->paginate(15);
        $query = Templetes::query();
        if ($role == 'Seller' || $role == 'Staff') {
            // $query->where('user_id', $user->id);
            $query->whereHas('stafftemplate', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
            $query->orWhere('user_id', $user->id);

        }
        $templates = $query->get();
        return view('products.index', compact('products', 'request', 'role', 'templates'));
    }

    public function ajax(Request $request, $id)
    {
        $product = Product::find($id);
        if ($product->type == 0) {
            return view('products.ajax.index', compact('product'));
        } else {
            return view('products.ajax.index2', compact('product'));
        }
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            // dd($request->json);
            $product = new Product();
            $product->user_id = Auth::user()->id;
            $product->data = $request->json;
            $product->discount = $request->discount;
            $rs = $product->save();
            // StoreProducts::create([
            //     'product_id' => $product->id,
            //     'store_id' => $request->store,
            //     'data'=> $request->json
            // ]);

            // $product = Product::find(6);
            $arrayimage = [];
            if ($request->images) {
                foreach ($request->images as $designimage) {
                    $filename = random_int(10000, 99999) . "_" . $designimage->getClientOriginalName();            // Store the uploaded file temporarily
                    $path = $designimage->store('products');  // Store the uploaded file in storage/designs
                    array_push($arrayimage, [
                        $filename,
                        $path
                    ]);
                    // Dispatch the job with the file path
                }
                addMockupProductJob::dispatch($arrayimage, $product->id, $request->product_id)->delay(5)->onQueue('upload-mockup-product');
            }


            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        $path = public_path('categories/categories.json'); // Đường dẫn đến file categories.json

        if (file_exists($path)) {
            $jsonData = file_get_contents($path);
            $categorietemps = (json_decode($jsonData));
        }
        $categories = Categories::select(['name'])->get();
        $query = MetaImages::query();
        $user = Auth::user();
        $role = $user->role->name;
        if ($role == 'Seller' || $role == 'Staff') {
            $query->where('user_id', $user->id);
        }
        $sizecharts = $query->get();

        return view('products.add.index', compact('categories', 'sizecharts', 'categorietemps'));
    }
    public function addtemplate(Request $request)
    {
        if ($request->isMethod('post')) {
            $template = Templetes::find($request->selectedtemplete);
            if ($template->type == 1) {
                $json = json_decode($template->data);
                $json->title = $request->title;
                $product = new Product();
                $product->user_id = Auth::user()->id;
                $product->data = json_encode($json);
                $product->templete_id = $request->selectedtemplete;
                $product->discount = $template->discount;
                $rs = $product->save();
            } else {
                $json = json_decode($template->data);
                $producttemp = $json->product;
                $producttemp->title = $request->title;
                $product = new Product();
                $product->user_id = Auth::user()->id;
                $product->data = json_encode($json);
                $product->discount = $request->discount;
                $product->templete_id = $request->selectedtemplete;
                $product->discount = $template->discount;

                $rs = $product->save();
            }
            // StoreProducts::create([
            //     'product_id' => $product->id,
            //     'store_id' => $request->store,
            //     'data'=> $request->json
            // ]);

            // $product = Product::find(6);
            $arrayimage = [];
            // dd($request->all());
            if ($request->images) {
                $arrayimage = [];
                foreach ($request->images as $designimage) {
                    // dd($designimage);
                    $filename = random_int(10000, 99999) . "_" . $designimage->getClientOriginalName();
                    $path = $designimage->storeAs('products', $filename, 'local'); // lưu có tên cụ thể
                    $arrayimage[] = [$filename, "products/".$filename];
                }
                \Log::info("arrayimage", $arrayimage);

                if ($template->type == 1) {
                    addMockupProductJob2::dispatch($arrayimage, $product->id, $request->product_id)->delay(5)->onQueue('upload-mockup-product');
                } else {
                    addMockupProductJob::dispatch($arrayimage, $product->id, $request->product_id)->delay(5)->onQueue('upload-mockup-product');

                }
            } else {
                \Log::error("No images uploaded in request");
            }



            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        $path = public_path('categories/categories.json'); // Đường dẫn đến file categories.json

        if (file_exists($path)) {
            $jsonData = file_get_contents($path);
            $categorietemps = (json_decode($jsonData));
        }
        $categories = Categories::select(['name'])->get();

        $query = MetaImages::query();
        $user = Auth::user();
        $role = $user->role->name;

        $query = Templetes::query();
        if ($role == 'Seller' || $role == 'Staff') {
            // $query->where('user_id', $user->id);
            $query->whereHas('stafftemplate', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
            $query->orWhere('user_id', $user->id);

        }
        $templates = $query->get();
        return view('products.add.tempindex', compact('categories', 'categorietemps', 'templates'));
    }
    public function edit(Request $request, $id)
    {
        $product = Product::find($id);
        if ($request->isMethod('post')) {
            // $product->user_id = Auth::user()->id;
            // dd($request->json);
            $product->data = $request->json;
            $product->discount = $request->discount;
            $rs = $product->save();

            // StoreProducts::create([
            //     'product_id' => $product->id,
            //     'store_id' => $request->store,
            //     'data'=> $request->json
            // ]);

            // $product = Product::find(6);
            $arrayimage = [];
            foreach ($request->images as $designimage) {
                \Log::info("designimage:");
                \Log::info($designimage->getClientOriginalName());

                $filename = "product_wd_" . random_int(1000000, 99999999) . ".jpg";            // Store the uploaded file temporarily
                $path = $designimage->store('products');  // Store the uploaded file in storage/designs
                array_push($arrayimage, [
                    $filename,
                    $path
                ]);
                // Dispatch the job with the file path
            }
            addMockupProductJob::dispatch($arrayimage, $product->id, $request->product_id)->delay(5)->onQueue('upload-mockup-product');

            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        $categories = Categories::select(['name'])->get();
        $query = MetaImages::query();
        $user = Auth::user();
        $role = $user->role->name;
        if ($role == 'Seller' || $role == 'Staff') {
            $query->where('user_id', $user->id);
        }
        $sizecharts = $query->get();

        $json = json_decode($product->data);
        $encodedJson = json_encode($json);

        // dd($json);
        $path = public_path('categories/categories.json'); // Đường dẫn đến file categories.json

        if (file_exists($path)) {
            $jsonData = file_get_contents($path);

            $categorietemps = (json_decode($jsonData));
            $category_id = $json->product->category_id ?? 0;
            $id = $category_id;
            $localNames = [];

            while ($id != 0) {
                // Sử dụng array_filter để tìm danh mục theo category_id
                $category = current(array_filter($categorietemps, function ($cat) use ($id) {
                    return $cat->id == (string) $id;
                }));

                // Nếu tìm thấy danh mục, thêm local_name vào mảng và cập nhật category_id
                if ($category) {
                    $localNames[] = $category->local_name;
                    $id = $category->parent_id;
                } else {
                    // Nếu không tìm thấy, thoát vòng lặp
                    break;
                }
            }

            // Đảo ngược và nối chuỗi local_name với dấu ">"
            $breadcrumb = implode(' > ', array_reverse($localNames));

        }
        return view('products.edit.index', compact('categorietemps', 'categories', 'sizecharts', 'encodedJson', 'product', 'breadcrumb', 'category_id'));
    }
    public function viewmockup(Request $request)
    {
        $product = Product::find($request->id);
        $json = json_decode($product->data ?? "[]")->product ?? "";
        $images = $json->images ?? [];
        return view('products.show.mockup', compact('images', 'product'));
    }
    public function viewstore(Request $request)
    {
        $query = Store::query();
        $user = Auth::user();
        $role = $user->role->name;
        if ($role == 'Seller') {
            $query->where('user_id', $user->id);
        }
        if ($role == 'Staff') {
            $query->where('staff_id', $user->id);
        }
        $query->where('status', 1);

        $stores = $query->get();
        return view('products.show.liststore', compact('stores'));
    }
    public function postToStore(Request $request)
    {
        $ids = $request->ids;
        $products = $request->products;
        foreach ($ids as $id) {
            PostProductToStore::dispatch($id, $products)->delay(2)->onQueue('post-product-to-store-nomal');
        }
    }
    public function postToStoreTiktok(Request $request)
    {
        $ids = $request->ids;
        $products = $request->products;
        foreach ($ids as $id) {
            PostProductToStoreATiktok::dispatch($id, $products)->delay(2)->onQueue('post-product-to-store');
        }
    }
    public function delete(Request $request, $id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('products.index');
    }
    public function deleteMulti(Request $request)
    {
        $ids = $request->valueproducts;
        foreach ($ids as $id) {
            $product = Product::find($id);
            $product->delete();
        }
        return redirect()->route('products.index');
    }
    public function duplicate(Request $request, $id)
    {
        // dd($request->all());
        if(isset($request->dup_template)){
            $template = Templetes::find($request->dup_template);
            if ($template->type == 1) {
                $json = json_decode($template->data);
                $json->title = $request->title;
                $product = new Product();
                $product->user_id = Auth::user()->id;
                $product->data = json_encode($json);
                $product->templete_id = $request->selectedtemplete;
                $product->discount = $template->discount;
                // $rs = $product->save();
            } else {
                $json = json_decode($template->data);
                $producttemp = $json->product;
                $producttemp->title = $request->title;
                $product = new Product();
                $product->user_id = Auth::user()->id;
                $product->data = json_encode($json);
                $product->discount = $request->discount;
                $product->templete_id = $request->selectedtemplete;
                $product->discount = $template->discount;

                // $rs = $product->save();
            }
            $originProduct = Product::find($id);
            $dataOrigin = json_decode($originProduct->data, true);
            $data = json_decode($product->data, true);
            if(isset($dataOrigin['product'])){
                $data['product']['title'] = $dataOrigin['product']['title'];
                $data['product']['images'] = $dataOrigin['product']['images'];
                $data['product']['imagevariants'] = $dataOrigin['product']['imagevariants'];
                $data['product']['image'] = $dataOrigin['product']['image'];
            }else{
                $data['title'] = $dataOrigin['title'];
                $data['images'] = $dataOrigin['images'];
                $data['imagevariants'] = $dataOrigin['imagevariants'];
                $data['image'] = $dataOrigin['image'];
            }
            $product->data = json_encode($data);
            $product->templete_id = $template->id;
            $product->save();
            // dd($product, $data, $dataOrigin);
        }else{
            
            $product = Product::find($id);
            $newProduct = $product->replicate(); 
            $newProduct->user_id = Auth::user()->id;
            $newProduct->save();
        }
        return redirect()->route('products.index');
    }
    public function multiDuplicate(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            if(isset($request->multi_dup_template)){
                $template = Templetes::find($request->multi_dup_template);
                if ($template->type == 1) {
                    $json = json_decode($template->data);
                    $json->title = $request->title;
                    $product = new Product();
                    $product->user_id = Auth::user()->id;
                    $product->data = json_encode($json);
                    $product->templete_id = $request->selectedtemplete;
                    $product->discount = $template->discount;
                    // $rs = $product->save();
                } else {
                    $json = json_decode($template->data);
                    $producttemp = $json->product;
                    $producttemp->title = $request->title;
                    $product = new Product();
                    $product->user_id = Auth::user()->id;
                    $product->data = json_encode($json);
                    $product->discount = $request->discount;
                    $product->templete_id = $request->selectedtemplete;
                    $product->discount = $template->discount;

                    // $rs = $product->save();
                }
                $originProduct = Product::find($id);
                $dataOrigin = json_decode($originProduct->data, true);
                $data = json_decode($product->data, true);
                if(isset($dataOrigin['product'])){
                    $data['product']['title'] = $dataOrigin['product']['title'];
                    $data['product']['images'] = $dataOrigin['product']['images'];
                    $data['product']['imagevariants'] = $dataOrigin['product']['imagevariants'];
                    $data['product']['image'] = $dataOrigin['product']['image'];
                }else{
                    $data['title'] = $dataOrigin['title'];
                    $data['images'] = $dataOrigin['images'];
                    $data['imagevariants'] = $dataOrigin['imagevariants'];
                    $data['image'] = $dataOrigin['image'];
                }
                $product->data = json_encode($data);
                $product->templete_id = $template->id;
                $product->save();
                // dd($product, $data, $dataOrigin);
            }else{
                
                $product = Product::find($id);
                $newProduct = $product->replicate(); 
                $newProduct->user_id = Auth::user()->id;
                $newProduct->save();
            }
        }
        
        return redirect()->route('products.index');
    }
    public function uploadimagecolor(Request $request)
    {
        $file = $request->image_color;
        // dd($file);
        $imgName = "image_color_" . random_int(1000000, 99999999) . ".jpg";
        Storage::disk('b2')->put($imgName, file_get_contents($file), 'public');

        // Generate a temporary URL for the image
        $url = Storage::disk('b2')->temporaryUrl($imgName, '', []);

        $urlDesign = substr($url, 0, strpos($url, '?X-Amz'));
        return response()->json(['url' => $urlDesign]);
    }
    public function getAttributes(Request $request)
    {
        try {
            $store = Store::find(1);
            $tiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
            $attributes = $tiktok->Product->getAttributes($request->idcategory, [
                'category_version' => 'v2'
            ]);
        
            return $attributes;
        } catch (\Throwable $th) {
            return response(json_encode(["message" => $th->getMessage(), "data" => []]), 400);
        }
    }
    public function updateImageOrder(Request $request)
    {
        $product = Product::find($request->id);
        $newOrder = $request->order;
        // dd($newOrder);
        // Update product data with new images order
        $json = json_decode($product->data ?? "[]");
        $json->product->images = $newOrder;
        $product->data = json_encode($json);
        $product->save();

        return response()->json(['status' => 'success']);
    }
    public function showstore(Request $request, $product_id)
    {
        $storeProducts = StoreProducts::select('id', 'product_id', 'store_id', 'created_at')->where('product_id', $product_id)->get();
        // dd($storeProducts);
        return view('products.ajax.storeproduct', compact('storeProducts'));
    }
    public function generateTitles(Request $request)
    {
        $title = $request->input('name');
        $content = "Help me title (without icon) as \"$title\" listing product tiktok good.";
        $apiKey = env('OPENAI_API_KEY');

        $postData = json_encode([
            "model" => "gpt-4o-mini",
            "store" => false,
            "messages" => [
                ["role" => "user", "content" => $content]
            ],
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return response()->json(['error' => $error], 500);
        }

        $data = json_decode($response, true);

        return response()->json([
            'titles' => $data['choices'][0]['message']['content'] ?? 'No response from OpenAI',
        ]);
    }
}
