<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Support\Facades\Auth;
use EcomPHP\TiktokShop\Resource;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use EcomPHP\TiktokShop\Client;
use Vanguard\Jobs\addProductFlashdealjob;
use Vanguard\Jobs\addProductFlashdealPriorityJob;
use Vanguard\Jobs\AutoFlashdealJob;
use Vanguard\Jobs\CreateFlashDeals;
use Vanguard\Jobs\deleteProductFlashdealJob;
use Vanguard\Jobs\mapJob;
use Vanguard\Jobs\RenewFlashDealJob;
use Vanguard\Jobs\syncAllProductStoreFldJob;
use Vanguard\Jobs\syncFlashDealJob;
use Vanguard\Jobs\syncFlashDealProductJob;
use Vanguard\Jobs\UpdateFlashDeals;
use Vanguard\Models\FlashDeals;
use Vanguard\Models\Customers;
use Vanguard\Models\PartnerApp;
use Vanguard\Models\ProductFlashdealMeta;
use Vanguard\Models\ProductFlashdeals;
use Vanguard\Models\ProductSkus;
use Vanguard\Models\ProductTiktoks;
use Vanguard\Models\Store;
use Vanguard\Models\StoreProducts;
use EcomPHP\TiktokShop\Client as TiktokApiClient;
use Vanguard\Models\Meta;
use Vanguard\Services\ImageService;
use Vanguard\Services\Redis\RedisService;
use Vanguard\Services\SyncSchedule\SyncScheduleService;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Imagick;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Vanguard\Product;
use Vanguard\Models\Categories;
use Vanguard\Models\MetaImages;
use Vanguard\Models\Templetes;
use Vanguard\Models\Colors;
use Vanguard\User;
use Illuminate\Support\Facades\Http;
use Vanguard\Models\Order\Order;
use Vanguard\Models\Order\OrderItem;
use Vanguard\Jobs\PostProductToStoreATiktok;
use Vanguard\Jobs\PostProductToTiktokShop;
use Vanguard\Jobs\GetOrderPageJob2;

class TiktokController extends Controller
{
    public $imageattribute = [];
    public function connectAppPartnerPostProduct($store)
    {
        $storeMetas = Meta::where('store_id', $store->id)->get();
        foreach ($storeMetas as $storeMeta) {

            if ($storeMeta->key == 'access_token') {
                $access_token = $storeMeta->value;
            }
            if ($storeMeta->key == 'refresh_token') {
                $refresh_token = $storeMeta->value;
            }

        }
        $appPartner = PartnerApp::find($store->partner_id);
        $dataConnectAppPartner = [];
        // dd($access_token);

        if (!empty($appPartner)) {
            $app_key = $appPartner->app_key;
            $app_secret = $appPartner->app_secret;
            $proxy = $appPartner->proxy;
            if (!empty($proxy)) {
                $proxyParts = explode(':', $proxy);
                $proxyAddress = $proxyParts[0];
                $proxyPort = $proxyParts[1];
                $proxyUsername = $proxyParts[2];
                $proxyPassword = $proxyParts[3];
            } else {
            }

            $client = new TiktokApiClient($app_key, $app_secret, ['proxy' => 'http://' . $proxyUsername . ':' . $proxyPassword . '@' . $proxyAddress . ':' . $proxyPort]);
            $auth = $client->auth();

            $dataNewToken = $auth->refreshNewToken($refresh_token);
            $access_token = $dataNewToken['access_token'];
            $refresh_token = $dataNewToken['refresh_token'];
            $client->setAccessToken($access_token);
            $authorizedShopList = $client->Authorization->getAuthorizedShop();
            $shop_cipher = $authorizedShopList['shops'][0]['cipher'];
            $client->setShopCipher($shop_cipher);

            $dataConnectAppPartner += [
                'client' => $client,
            ];
        } else {
        }

        return $dataConnectAppPartner;
    }
    private function addWatermarkToImages(&$images, $wartermark)
    {
        if (is_array($images)) {
            foreach ($images as $image) {
                $image->src = "http://windiez.cloud/gen-water-mark?url=" . $image->src . "&watermark=" . $wartermark;
            }
        } else {
            $images = "http://windiez.cloud/gen-water-mark?url=" . $images . "&watermark=" . $wartermark;
        }

    }
    // public $imageservice = new ImageService();

    // public function updateBlaze(){
    //     return $this->imageservice->resizeImage(, "test.png", 1155, 1155);
    // }
    private function addWarehourseSkus(&$skus, $warehouse_id)
    {
        foreach ($skus as $sku) {
            $sku->inventory[0]->warehouse_id = (string) $warehouse_id;
        }
    }
    private function uploadAttributeImage($clientAppPartner, $json)
    {
        $attri_images = [];
        foreach ($json as $key => $image) {
            if (isset($image->color) && !empty($image->color)) {
                $uploadProductImage = $clientAppPartner->Product->uploadProductImage(str_replace(' ', '%20', $image->src), 'ATTRIBUTE_IMAGE');
                $attri_images[$key]['color'] = $image->color;
                $attri_images[$key]['url'] = ["uri" => $uploadProductImage['uri']];
                \Log::channel('export-product-tiktokshop')->info("dataProduct");
                \Log::channel('export-product-tiktokshop')->info($image->color);
                \Log::channel('export-product-tiktokshop')->info(["uri" => $uploadProductImage['uri']]);
            }
        }
        return $attri_images;
    }
    public function test(Request $request, $id)
    {
        $store = Store::find(1);

        $store->syncfld = 1;
            $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
            $product = $storetiktok->Product->getProduct(1731815324732067903);
            dd($product);
        // $storeproducts = StoreProducts::WHERENULL('remote_id')->WhereNotNull('message')->get();
        // foreach($storeproducts as $storeproduct){
        //     $id = $storeproduct->id;
        //     $product = Product::find($storeproduct->product_id);
        //     // dd($id, $product);
        //     PostProductToTiktokShop::dispatch($storeproduct->id, $product->discount)->delay(3)->onQueue('post-product-to-tiktok');

        //     // PostProductToStoreATiktok::dispatch($id, $products)->delay(2)->onQueue('post-product-to-store');
        //     echo "ok ".$storeproduct->id ." - ".$product->discount."<br>";

        // }
        // dd($productTiktoks);
        // $url = 'https://pthung.fteeck.com';

        // $filePath = '/home/runcloud/webapps/global_new/public/csv/order_miss.json';

        // // Nếu file chưa tồn tại thì khởi tạo mảng rỗng
        // if (!file_exists($filePath)) {
        //     file_put_contents($filePath, json_encode([]));
        // }

        // // Đọc dữ liệu cũ trong file
        // $existingData = json_decode(file_get_contents($filePath), true) ?? [];
        // dd($existingData);
        // $totalOrders = 2316;
        // $perPage = 20;
        // $totalPages = ceil($totalOrders / $perPage);

        // for ($page = 1; $page <= $totalPages; $page++) {
        //     $reportResponse = Http::withHeaders([
        //         'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6ImFkbWluIiwicGFzc3dvcmQiOiJIdW5nbmd0MTIzQCIsImV4cCI6MTc2MDgwNTQ1M30.tIuhsgVBwFV5IZPTueFt0eb52Ukjvmgo4pKkzthuONY',
        //         'Accept'        => 'application/json',
        //         'Content-Type'  => 'application/json',
        //     ])->withBody(
        //         json_encode([
        //             "start_date" => "2025-09-01",
        //             "end_date"   => "2025-09-18",
        //             "shop_code"  => "USLC4FEEH4"
        //         ]),
        //         'application/json'
        //     )->get($url . '/api/orders/getList/' . $page);


        //     if ($reportResponse->successful()) {
        //         $responseData = $reportResponse->json();
        //         foreach ($responseData['orders'] as $orderData) {
        //             $id = $orderData['id'];

        //             // Nếu id chưa có thì thêm vào
        //             if (!in_array($id, $existingData)) {
        //                 $existingData[] = $id;
        //             }
        //         }
        //     } else {
        //         // log nếu lỗi
        //         \Log::error("Lỗi khi gọi API trang {$page}");
        //     }
        // }
        // // Ghi lại vào file theo format JSON
        // file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        // $storeproduct = StoreProducts::find(192270);
        // // dd($storeproduct);
        // \Log::channel('export-product-tiktokshop')->info("Start post to tiktok");
        // \Log::channel('export-product-tiktokshop')->info($storeproduct->id);

        // if ($storeproduct) {

        //     $product = $storeproduct;
        //     \Log::channel('export-product-tiktokshop')->info("store");
        //     \Log::channel('export-product-tiktokshop')->info($store->id);
        //     \Log::channel('export-product-tiktokshop')->info("product");
        //     \Log::channel('export-product-tiktokshop')->info($product->product_id);

        //     if (!empty($storeproduct->remote_id)) {
        //         return;
        //     }
        //     // try {
        //         $custom_data = json_decode($product->data);

        // dd($getOrders);
        // foreach($getOrders['orders'] as $orderData){
        //     // dd($orderData);
        //     $original_shipping_fee = $orderData['payment']['original_shipping_fee'] ?? 0;
        //     $original_total_product_price = $orderData['payment']['original_total_product_price'] ?? 0;
        //     $seller_discount = $orderData['payment']['seller_discount'] ?? 0;
        //     $shipping_fee = $orderData['payment']['shipping_fee'] ?? 0;
        //     $total_amount = $orderData['payment']['total_amount'] ?? 0;
        //     $shipping_fee = $orderData['payment']['shipping_fee'] ?? 0;
        //     $order = Order::updateOrCreate(
        //             ['tiktok_order_id' => $orderData['id']], 
        //             [
        //                 'user_id' => $store->user_id??$store->staff_id,
        //                 'store_id' => $store->id,
        //                 'tracking_number' => $orderData['tracking_number'] ?? null,
        //                 'original_shipping_fee' => $original_shipping_fee,
        //                 'original_total_product_price' => $original_total_product_price,
        //                 'seller_discount' => $seller_discount ?? null,
        //                 'shipping_fee' => $shipping_fee ?? null,
        //                 'total_amount' => $total_amount ?? null,
        //                 'order_status' => $orderData['status'] ?? null,
        //                 'tiktok_create_date' => Carbon::createFromTimestamp(1760800237)->toDateTimeString(),
        //                 'net_revenue' => 0,
        //                 'base_cost' => 0,
        //                 'net_profits' => 0,
        //                 'design_fee' => 0,
        //                 'created_at' => $orderData['create_time'],
        //                 'updated_at' => now(),
        //             ]
        //         );
        //         // dd($order);
        //             $district_info = $orderData['recipient_address']['district_info'] ?? [];

        //             $country = $state = $county = $city = null;

        //             foreach ($district_info as $info) {
        //                 switch ($info['address_level_name']) {
        //                     case 'Country':
        //                         $country = $info['address_name'];
        //                         break;
        //                     case 'State':
        //                         $state = $info['address_name'];
        //                         break;
        //                     case 'County':
        //                         $county = $info['address_name'];
        //                         break;
        //                     case 'City':
        //                         $city = $info['address_name'];
        //                         break;
        //                 }
        //             }                    
        //         $Customers = Customers::updateOrCreate(
        //             [
        //                 'order_id' => $order->id
        //             ],
        //             [
        //                 'first_name' => $orderData['recipient_address']['first_name'] ?? null,
        //                 'last_name'  => $orderData['recipient_address']['last_name'] ?? null,
        //                 'phone'      => $orderData['recipient_address']['phone_number'] ?? null,
        //                 'address_1'  => $orderData['recipient_address']['address_line1'] ?? null,
        //                 'address_2'  => $orderData['recipient_address']['address_line2'] ?? null,
        //                 'city'       => $city,
        //                 'state'      => $state,
        //                 'county'     => $county, // nếu bạn cần
        //                 'postcode'   => $orderData['recipient_address']['postal_code'] ?? null,
        //                 'country'    => $country,
        //             ]
        //         );
        //         $items = $orderData['line_items'];

        //         foreach ($items as $item) {
        //             foreach ($items as $item) {
        //                 OrderItem::updateOrCreate(
        //                     [
        //                         'order_id' => $order->id,
        //                         'fteeck_item_id' => null
        //                     ],
        //                     [
        //                         'product_id' => $item['product_id'], 
        //                         'product_name' => $item['product_name'],
        //                         'sku_id' => $item['sku_id'],
        //                         'quantity' => $item['quantity']??1,
        //                         'sku_image' => $item['sku_image'],
        //                         'sku_name' => $item['sku_name'],
        //                     ]
        //                 );
        //             }
        //         }
        // }
        // dd($orders);
        // //         if (!isset($clientAppPartner['client'])) {
        // //             \Log::channel('export-product-tiktokshop')->error("{$store->name} - title: {$product->product_id} - store has no app");
        // //             return;
        // //         }

        //         $clientAppPartner = $clientAppPartner['client'];
        //         $templates = $clientAppPartner->Promotion->getActivity('7558011264790906638');
        //         dd($templates);
        // // dd($clientAppPartner);
        // $warehouse_id = $this->getDefaultWarehouseId($clientAppPartner);

        // $dataJson = $custom_data->product;
        // $description = $dataJson->description;
        // if(isset($dataJson->variant_keys)){
        //     $this->variant_keys = $dataJson->variant_keys;
        // }
        // if (str_contains($description, '<img') == true) {
        //     $description = preg_replace_callback(
        //         '/<img\s+[^>]*src=["\']([^"\']+)["\'][^>]*>/i',
        //         function ($matches)  {
        //             $originalSrc = $matches[1]; // Extract the original src URL
        //             // $newSrcData = $this->uploadDescriptionImages($clientAppPartner, $originalSrc);
        //             $newSrc = "";
        //             if(str_contains($originalSrc, 'f194e6f8faf44b6197e5f3e9d1c4190d') == true){
        //                 $newSrc =  'https://p16-oec-ttp.tiktokcdn-us.com/tos-useast5-i-omjb5zjo8w-tx/f194e6f8faf44b6197e5f3e9d1c4190d~tplv-omjb5zjo8w-origin-jpeg.jpeg';
        //             }else{
        //                 $newSrc = "https://p19-oec-ttp.tiktokcdn-us.com/tos-useast5-i-omjb5zjo8w-tx/ea6530172fd7443e8b88aabe10c662f0~tplv-omjb5zjo8w-origin-jpeg.jpeg"; // Get the new src URL from the upload function
        //             }

        //             return str_replace($originalSrc, $newSrc, $matches[0]);
        //         },
        //         $description
        //     );
        // }
        // $dataJson->description = $description;
        // // dd($dataJson->description);
        // $title = $dataJson->title . " " . $store->keyword ?? "";
        // $product_type = $dataJson->category;
        // // $product_attributes = $this->getProductAttributes($product_type, $dataJson->set);
        // $product_attributes = $dataJson->selectedAttributes ?? $this->getProductAttributes($product_type, $dataJson->set);

        // if (property_exists($dataJson, 'aerosols')) {
        //     array_push(
        //         $product_attributes,
        //         [
        //             "id" => "101571",
        //             "name" => "Aerosols",
        //             "values" => [
        //                 [
        //                     "id" => "1000059",
        //                     "name" => "no"
        //                 ]
        //             ]
        //         ]
        //     );
        // }
        // if (property_exists($dataJson, 'flammable_liquid')) {
        //     array_push(
        //         $product_attributes,
        //         [
        //             "id" => "101574",
        //             "name" => "Flammable Liquid",
        //             "values" => [
        //                 [
        //                     "id" => "1000059",
        //                     "name" => "no"
        //                 ]
        //             ]
        //         ]
        //     );
        // }
        // if (property_exists($dataJson, 'contains_batteries_or_cells')) {
        //     array_push(
        //         $product_attributes,
        //         [
        //             "id" => "101610",
        //             "name" => "Contains Batteries or Cells?",
        //             "values" => [
        //                 [
        //                     "id" => "1000325",
        //                     "name" => "None"
        //                 ]
        //             ]
        //         ]
        //     );
        // }
        // if (property_exists($dataJson, 'other_dangerous_goods_or_hazardous_materials')) {

        //     array_push(
        //         $product_attributes,
        //         [
        //             "id" => "101619",
        //             "name" => "Other Dangerous Goods or Hazardous Materials",
        //             "values" => [
        //                 [
        //                     "id" => "1000059",
        //                     "name" => "no"
        //                 ]
        //             ]
        //         ]
        //     );
        // }


        // array_push(
        //     $product_attributes,
        //     [
        //         "id" => "101400",
        //         "name" => "CA Prop 65: Carcinogens",
        //         "values" => [
        //             [
        //                 "id" => $dataJson->ca_prop_65_carcinogens ? ($dataJson->ca_prop_65_carcinogens == "no" ? "1000059" : "1000058") : "1000059",
        //                 "name" => $dataJson->ca_prop_65_carcinogens ?? "no"
        //             ]
        //         ]
        //     ]
        // );
        // array_push(
        //     $product_attributes,
        //     [
        //         "id" => "101395",
        //         "name" => "CA Prop 65: Repro. Chems",
        //         "values" => [
        //             [
        //                 "id" => $dataJson->ca_prop_65_repro_chems ? ($dataJson->ca_prop_65_repro_chems == "no" ? "1000059" : "1000058") : "1000059",
        //                 "name" => $dataJson->ca_prop_65_repro_chems ?? "no"
        //             ]
        //         ]
        //     ]
        // );
        // \Log::channel('export-product-tiktokshop')->info("product_attributes");
        // \Log::channel('export-product-tiktokshop')->info($product_attributes);

        // $category_id = (string) $dataJson->category_id;
        // // $category_id = $this->getCategoryId($product_type, $dataJson->set);
        // \Log::channel('export-product-tiktokshop')->info("category_id");
        // \Log::channel('export-product-tiktokshop')->info($category_id);
        // $main_images = $this->uploadMainImages($clientAppPartner, $dataJson->images);
        // \Log::channel('export-product-tiktokshop')->info("main_images");
        // \Log::channel('export-product-tiktokshop')->info($main_images);
        // if (isset($dataJson->imagevariants)) {
        //     $attribute_images = $this->uploadAttributeImage($clientAppPartner, $dataJson->imagevariants);
        // } else {
        //     $attribute_images = [];
        // }
        // \Log::channel('export-product-tiktokshop')->info("attribute_images");
        // \Log::channel('export-product-tiktokshop')->info($attribute_images);
        // $this->imageattribute = $attribute_images;
        // $size_chart = (isset($dataJson->imagesizechart)&&filter_var($dataJson->imagesizechart, FILTER_VALIDATE_URL))? $this->uploadSizeChart($clientAppPartner, $dataJson->imagesizechart) : null;
        // if (property_exists($dataJson, 'aerosols')) {
        //     $skus[] = [
        //         "price" => [
        //             "currency" => "USD",
        //             "amount" => $dataJson->variants->only_price
        //         ],
        //         "sales_attributes" => [],
        //         "seller_sku" => "",
        //         "inventory" => [
        //             [
        //                 "quantity" => (int) $dataJson->variants->only_quantity,
        //                 "warehouse_id" => $warehouse_id
        //             ]
        //         ]
        //     ];

        // }else{
        //     $skus = $this->createSkus($clientAppPartner, $dataJson->variants, $dataJson->images, $warehouse_id);
        // }
        // \Log::channel('export-product-tiktokshop')->info("package_weight");
        // \Log::channel('export-product-tiktokshop')->info($dataJson->weight);
        // \Log::channel('export-product-tiktokshop')->info("package_dimensions");
        // \Log::channel('export-product-tiktokshop')->info($dataJson->height);
        // \Log::channel('export-product-tiktokshop')->info("package_dimensions");
        // \Log::channel('export-product-tiktokshop')->info($dataJson->length);
        // \Log::channel('export-product-tiktokshop')->info("package_dimensions");
        // \Log::channel('export-product-tiktokshop')->info($dataJson->width);

        // $dataProduct = [
        //     "description" => $dataJson->description??$this->getDefaultDescription(),
        //     "title" => $title,
        //     "is_cod_open" => true,
        //     "category_id" => $category_id,
        //     "category_version" => "v2",
        //     "main_images" => $main_images,
        //     "skus" => $skus,
        //     "package_weight" => [
        //         "value" => (string) $dataJson->weight ?? "0.3",
        //         "unit" => "KILOGRAM"
        //     ],
        //     "package_dimensions" => [
        //         "height" => (string) $dataJson->height ?? "5",
        //         "length" => (string) $dataJson->length ?? "15",
        //         "unit" => "CENTIMETER",
        //         "width" => (string) $dataJson->width ?? "15",
        //     ],
        //     "product_attributes" => $product_attributes,
        // ];

        // if ($size_chart) {
        //     $dataProduct["size_chart"] = ['image' => $size_chart];
        // }
        // // dd($dataProduct);
        // \Log::channel('export-product-tiktokshop')->info("dataProduct");
        // \Log::channel('export-product-tiktokshop')->info($dataProduct);
        // $createProduct = $clientAppPartner->Product->createProduct($dataProduct);
        // $storeproduct->remote_id = $createProduct['product_id'];
        // $storeproduct->message = "success";
        // $storeproduct->save();
        // syncProductTiktokPostJob::dispatch($storeproduct->store_id, $createProduct['product_id'], $this->discount)->onQueue('sync-product-post_tiktok');
        // \Log::channel('export-product-tiktokshop')->info("{$store->name} - title: {$product->title} - post success. Remote ID: {$createProduct['product_id']}");

        // } catch (\Exception $e) {
        //     \Log::channel('export-product-tiktokshop')->error("{$store->name} - title: {$product->title} - {$e->getMessage()}");
        //     // Check if the error message contains 'cURL error 56' or 'Unable'
        //     $storeproduct->message = $e->getMessage();
        //     $storeproduct->save();
        //     if (str_contains($e->getMessage(), 'request is limited') == true || str_contains($e->getMessage(), 'System error') == true || str_contains($e->getMessage(), 'Internal system error') == true ) {
        //         // Dispatch the job to the 'post-product-to-tiktok' queue, with a 2-second delay
        //         rePostProductToTiktokShop::dispatch($this->id, $this->discount)
        //             ->delay(now()->addSeconds(2))  // Using `now()` for better clarity and consistency
        //             ->onQueue('re-post-product-to-tiktok');
        //     }
        //     try {
        //         Telegram::sendMessage([
        //             'chat_id' => $store->user->group_id,
        //             'text' => 'Post Product to Tiktok Error ID: '.$storeproduct->id.' - '.$e->getMessage(),
        //         ]);
        //     } catch (\Throwable $th) {
        //         \Log::channel('telegram-wh')->info($th);
        //     }
        // }
        // }   
        // $requestData = [
        //             'start_date' => Carbon::now()->startOfMonth(),
        //             'end_date' => Carbon::now()->endOfMonth(),
        //             'tiktok_order_id' => '577006292658459365'
        //         ];

        //         $reportResponse = Http::withHeaders([
        //             'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6ImFkbWluIiwicGFzc3dvcmQiOiJIdW5nbmd0MTIzQCIsImV4cCI6MTc1MjMyNjc2NH0.xjwtRr82N1rDr6pLGReFR-D_r3O9-fKONNBizauRiPA',
        //             'Accept' => 'application/json',
        //             'Content-Type' => 'application/json'
        //         ])->withBody(
        //                 json_encode($requestData),
        //                 'application/json'
        //             )->get('https://pthung.fteeck.com/api/orders/getList');

        //         if (!$reportResponse->successful()) {
        //             return;
        //         }

        //         $responseData = $reportResponse->json();

        //         // $total = $responseData['total'] ?? 0;
        //         // $pages = ceil($total / 20);
        //         // \Log::info('Total: ', ['value' => $total]);

        //         $user_id = null;
        //         $store_id = null;

        //         // dd($store, $user_id, $store_id);
        //         // dd($responseData);
        //         try {
        //             foreach ($responseData['orders'] as $orderData) {
        //                     $order = Order::updateOrCreate(
        //                         ['tiktok_order_id' => $orderData['tiktok_order_id']],
        //                         [
        //                             'user_id' => 118,
        //                             'store_id' => 218,
        //                             'tracking_number' => $orderData['tracking_number'] ?? null,
        //                             'original_shipping_fee' => $orderData['original_shipping_fee'] ?? null,
        //                             'original_total_product_price' => $orderData['original_total_product_price'] ?? null,
        //                             'seller_discount' => $orderData['seller_discount'] ?? null,
        //                             'shipping_fee' => $orderData['shipping_fee'] ?? null,
        //                             'total_amount' => $orderData['total_amount'] ?? null,
        //                             'order_status' => $orderData['order_status'] ?? null,
        //                             'tiktok_create_date' => $orderData['tiktok_create_date'] ?? null,
        //                             'net_revenue' => $orderData['net_revenue'] ?? null,
        //                             'base_cost' => $orderData['base_cost'] ?? null,
        //                             'net_profits' => $orderData['net_profits'] ?? null,
        //                             'design_fee' => (float) $orderData['design_fee'] ?? null,
        //                             'created_at' => $orderData['created_date'],
        //                             'updated_at' => now(),
        //                         ]
        //                     );

        //                     $items = $orderData['items'];

        //                     foreach ($items as $item) {
        //                         OrderItem::updateOrCreate(
        //                             [
        //                                 'order_id' => $order->id,
        //                                 'fteeck_item_id' => $item['id']
        //                             ],
        //                             [
        //                                 'product_id' => $item['product_id'],
        //                                 'product_name' => $item['product_name'],
        //                                 'sku_id' => $item['sku_id'],
        //                                 'quantity' => $item['quantity'],
        //                                 'sku_image' => $item['sku_image'],
        //                                 'sku_name' => $item['sku_name'],
        //                             ]
        //                         );
        //                     }

        //             }
        //             \Log::info('Get order by tiktok_order_id: Data processed successfully');
        //         } catch (\Exception $e) {
        //             \Log::error('Get order by tiktok_order_id: Exception occurred', [
        //                 'message' => $e->getMessage(),
        //                 'trace' => $e->getTraceAsString(),
        //             ]);

        //             throw $e;
        //         }
        // $store = Store::find(218);

        // // try{
        // // //     $store->syncfld = 1;
        //     $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
        //     // $storetiktok->useVersion(202406);
        //     $create_time_ge = strtotime('2025-09-01 00:00:00');

        //     // Tạo timestamp kết thúc đến 18/09 23:59:59
        //     $create_time_lt = strtotime('2025-09-18 23:59:59');

        //     $query = [
        //         "order_status"=>"AWAITING_COLLECTION",
        //         // "page_token" => "aDV5MHFtZVdaRXBOVDJGTjl5bUlidHJWOU1jeHFZMWNmRDUwRG1NRnpCRzBTUT09",
        //         "create_time_ge" => $create_time_ge,
        //         "create_time_lt" => $create_time_lt,
        //     ];
        //     $orders = $storetiktok->Order->getOrderList($query);
        //     dd($orders);
        //     return ($product);
        // //     $promotion = $storetiktok->Promotion;
        // //     $promotion->useVersion(202309);
        // //     $listpromotion = $promotion->searchActivities([
        // //         'page_size'=>100,
        // //         'product_id' => '1730895572697846462',
        // //         'status' => 'ONGOING',
        // //     ]);
        // //     $store->save();
        // //     $activities = ($listpromotion['activities']);
        // //     // dd($activities);
        // //     foreach($activities as $activity){
        // //         //'activity_id','store_id', 'promotion_name', 'activity_type', 'product_level', 'status_fld', 'begin_time', 'end_time', 'auto', 'status'
        // //         $rs = FlashDeals::updateorCreate([
        // //             'activity_id' => $activity['id']
        // //         ],[
        // //             'store_id' => 444, 
        // //             'promotion_name' => $activity['title'], 
        // //             'activity_type' => $activity['activity_type'], 
        // //             'product_level' => $activity['product_level'], 
        // //             'status_fld' => $activity['status'], 
        // //             'begin_time' => $activity['begin_time'], 
        // //             'end_time' => $activity['end_time'], 
        // //             'status' => 1
        // //         ]);  
        // //         // $flashdealproducts = $promotion->getActivity($activity['id'])['products'];
        // //         // echo $activity['id'].' - '.count($flashdealproducts).'<br/>';
        // //         // foreach ($flashdealproducts as $flashdealproduct) {
        // //         //         $skus = $flashdealproduct['skus'];
        // //         //         $totalAmount = 0;

        // //         //         foreach ($skus as $sku) {
        // //         //             $totalAmount += (float) $sku['activity_price']['amount'];
        // //         //         }
        // //         //         if ($totalAmount == 0) {
        // //         //             $discount = 0;
        // //         //         } else {
        // //         //             $discount = calPercentProduct($totalAmount, (int) $flashdealproduct['id']);
        // //         //         }
        // //         //         if ($discount > 0) {
        // //         //             ProductFlashdeals::updateOrCreate([
        // //         //                 'flashdeal_id' => $this->flashdeal_id,
        // //         //             ], [
        // //         //                 'product_id' => $flashdealproduct['id'],
        // //         //                 'discount' => $discount,
        // //         //                 'quantity_limit' => $flashdealproduct['quantity_limit'],
        // //         //                 'quantity_per_user' => $flashdealproduct['quantity_per_user'],
        // //         //                 'total_sku' => count($skus),
        // //         //                 'message' => 'success'
        // //         //             ]);
        // //         //         }
        // //         // }

        // //         // dd($activity, $flashdealproducts);
        // //         syncFlashDealProductJob::dispatch(5, $activity['id'], $activity['status'])->onQueue('sync-product-flashdeal')->delay(2);      
        // //     } 
        // //     $store->syncfld = 0;
        // //     $store->message = "success | ".Carbon::now();
        // //     $store->save();

        // } catch (\Throwable $th) {
        //     dd($th);
        //     // $store->syncfld = 0;
        //     // $store->message = $th->getMessage()." | ".Carbon::now();
        //     // $store->save();
        // }
        // $repostflashdeals = ProductFlashdeals::with('flashdeal')
        //     ->where(function($query) {
        //         $query->where('success', 0);
        //     })
        //     ->whereHas('flashdeal', function($query) {
        //         $query->whereIn('status_fld', ['ONGOING','NOT_START'])
        //             ->where('renew', 0)
        //             ->where('auto', 1);
        //     })
        //     // ->where('product_id', '1729964871503483184')
        //     // ->limit(400)
        //     ->orderBy('id','asc')
        //     ->get();
        // // dd($repostflashdeals);
        // foreach($repostflashdeals as $renewflashdeal){

        //     $store_id = $renewflashdeal->flashdeal->store_id;
        //     $activity_id = $renewflashdeal->flashdeal_id;
        //     $remote_id = $renewflashdeal->product_id; 
        //     $discount = $renewflashdeal->discount;
        //     $quantity_limit = $renewflashdeal->quantity_limit;
        //     $quantity_per_user = $renewflashdeal->quantity_per_user;
        //     addProductFlashdealjob::dispatch($store_id, (string)$activity_id, (string)$remote_id, $discount, $quantity_limit, $quantity_per_user)->onQueue('add-product-to-flashdeals');
        // }
        // $store = Store::find(218);

        // $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];

        // try {
        //     $remote_id = '1730457868265951536';
        //     $discount = 50;
        //     $quantity_limit = (int) -1;
        //     $quantity_per_user = (int) -1;
        //     // \Log::channel('product-to-flashdeal')->info("store_id : " . $this->store_id);
        //     // \Log::channel('product-to-flashdeal')->info("activity_id : " . $this->activity_id);
        //     // \Log::channel('product-to-flashdeal')->info("remote_id : " . $this->remote_id);
        //     // \Log::channel('product-to-flashdeal')->info("discount : " . $this->discount);
        //     // \Log::channel('product-to-flashdeal')->info("quantity_limit : " . $this->quantity_limit);
        //     // \Log::channel('product-to-flashdeal')->info("quantity_per_user : " . $this->quantity_per_user);


        //     $storetiktok->useVersion(202309);
        //     $promotion = $storetiktok->Promotion;
        //     $producttiktok = ProductTiktoks::where('remote_id', $remote_id)->first();
        //     $skus = json_decode($producttiktok->skus);
        //     $result = [];
        //     foreach ($skus as $product) {
        //         $tax_exclusive_price = (float) $product->price;

        //         // Tính giá sau khi áp dụng discount
        //         $activity_price_amount = $tax_exclusive_price - ($tax_exclusive_price * $discount / 100);

        //         // Tạo phần tử mới cho mảng result
        //         $result[] = [
        //             "activity_price_amount" => (string) round($activity_price_amount, 2),
        //             "id" => $product->id,
        //             "quantity_limit" => $quantity_limit,
        //             "quantity_per_user" => $quantity_per_user
        //         ];
        //     }

        //     // dd($skus[0], $result[0]);
        //     $product = [
        //         [
        //             "id" => (string)$remote_id,
        //             "quantity_limit" => $quantity_limit,
        //             "quantity_per_user" => $quantity_per_user,
        //             "skus" => $result
        //         ]
        //     ];
        //     $updateactivity = $promotion->updateActivityProduct((string)'7500908910002325294', $product);
        //     if(isset($updateactivity['activity_id'])){
        //         $productFlashdeals->total_sku = count($skus);
        //         $productFlashdeals->message = 'success';
        //         $productFlashdeals->success = 1;
        //         $productFlashdeals->save();

        //         $producttiktok->is_flashdeal = 1;
        //         $producttiktok->save();
        //     }
        //     dd($updateactivity);
        // } catch (\Throwable $th) {
        //     dd($th);
        // }

        // $repostflashdeals = ProductFlashdeals::with('flashdeal')
        //     ->where(function($query) {
        //         $query->where('success', 0);
        //     })
        //     ->whereHas('flashdeal', function($query) {
        //         $query->whereIn('status_fld', ['ONGOING','NOT_START'])
        //             ->where('renew', 0)
        //             ->where('auto', 1);
        //     })
        //     ->get();
        // // dd($repostflashdeals);
        // foreach($repostflashdeals as $renewflashdeal){

        //     $store_id = $renewflashdeal->flashdeal->store_id;
        //     $activity_id = $renewflashdeal->flashdeal_id;
        //     $remote_id = $renewflashdeal->product_id; 
        //     $discount = $renewflashdeal->discount;
        //     $quantity_limit = $renewflashdeal->quantity_limit;
        //     $quantity_per_user = $renewflashdeal->quantity_per_user;
        //     addProductFlashdealjob::dispatch($store_id, $activity_id, (string)$remote_id, $discount, $quantity_limit, $quantity_per_user)->onQueue('add-product-to-flashdeals');
        // }
        // $store = Store::find(5);

        // try{
        //     $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
        //     $storetiktok->useVersion(202312);
        //     $promotion = $storetiktok->Promotion;
        //     $promotion->useVersion(202309);
        //     $flashdealproducts = $promotion->getActivity(7502021011237095211)['products'];
        //     dd($flashdealproducts);
        // } catch (\Throwable $e) {
        //    dd($e);
        // }
        // try {
        //     $lastSyncMeta = Meta::where([
        //         'key' => 'deleteProductFlashdealJob',
        //     ])->latest('id')->first();

        //     $lastId = 0;
        //     if ($lastSyncMeta) {
        //         $metaValue = json_decode($lastSyncMeta->value, true);
        //         $lastId = $metaValue['end_id'] ?? 0;
        //     }

        //     $sevenday = Carbon::now()->subDays(7);

        //     $flashDeals = FlashDeals::where('id', '>', $lastId)
        //         ->limit(50)
        //         ->get();

        //     foreach($flashDeals as $flashDeal){
        //         $flashdeal_id = $flashDeal->activity_id;
        //         $dateCreate = $flashDeal->created_at;
        //         deleteProductFlashdealJob::dispatch($flashdeal_id, $dateCreate)->delay(1)->onQueue('deleteProductFlashdealJob');
        //     }

        //     $startId = $flashDeals->first()->id;
        //     $endId = $flashDeals->last()->id;
        //     Meta::create([
        //         'user_id' => null,
        //         'store_id' => null,
        //         'key' => 'deleteProductFlashdealJob',
        //         'value' => json_encode([
        //             'start_id' => $startId,
        //             'end_id' => $endId,
        //             'count' => count($flashDeals),
        //             'sync_time' => now()->toDateTimeString()
        //         ])
        //     ]);
        // } catch (\Throwable $th) {
        //     dd($th);
        // }

        // $service = new SyncScheduleService();

        // $result = $service->syncScheduleFlashDeals();
        // $user = User::find(101);
        //     $url = $user?->team?->link_page;
        // dd($url, $user);
        // // $store = Store::where('id',2)->first();
        // // $clientAppPartner = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
        // // $product = $clientAppPartner->Product->getProduct('1730824494285689434');
        // // return ($product['description']);

        // $product = Templetes::find(127);
        // $json = json_decode($product->data);
        // $description = $json->product->description;
        // // dd($description);
        // if (str_contains($description, '<img') == true) {
        //     $description = preg_replace_callback(
        //         '/<img\s+[^>]*src=["\']([^"\']+)["\'][^>]*>/i',
        //         function ($matches)  {
        //             $originalSrc = $matches[1]; // Extract the original src URL
        //             // $newSrcData = $this->uploadDescriptionImages($clientAppPartner, $originalSrc);
        //             $newSrc = "";
        //             if(str_contains($originalSrc, 'http') == true){
        //                 $newSrc =  'https://p16-oec-ttp.tiktokcdn-us.com/tos-useast5-i-omjb5zjo8w-tx/f194e6f8faf44b6197e5f3e9d1c4190d~tplv-omjb5zjo8w-origin-jpeg.jpeg';
        //             }else{
        //                 $newSrc = "https://p19-oec-ttp.tiktokcdn-us.com/tos-useast5-i-omjb5zjo8w-tx/ea6530172fd7443e8b88aabe10c662f0~tplv-omjb5zjo8w-origin-jpeg.jpeg"; // Get the new src URL from the upload function

        //             }

        //             return str_replace($originalSrc, $newSrc, $matches[0]);
        //         },
        //         $description
        //     );
        // }
        // dd($description);
    }
    function getDefaultWarehouseId($clientAppPartner)
    {
        $warehouses = $clientAppPartner->Logistic->getWarehouseList()['warehouses'];
        foreach ($warehouses as $warehouse) {
            if ($warehouse['is_default']) {
                return $warehouse['id'];
            }
        }
        return null; // Or handle this case as needed
    }

    public function getDefaultDescription()
    {
        $description = "<p><strong>Welcome to the store!</strong></p><p>_ Experience unparalleled comfort and style with our versatile collection of hoodies, sweatshirts, and t-shirts. Crafted with a passion for providing the perfect shopping experience, our products are designed to keep you warm and cozy throughout the winter.</p><p>_ Feel free to explore a wide range of soft, comfy hoodies that are perfect for the season. We take pride in offering customization options, allowing you to choose your preferred color or even request a custom design. Should you have any questions or specific concerns, our dedicated team is always ready to assist – just drop us a message.</p><p>_ The standout feature of our products lies in the captivating images printed on the fabric using cutting-edge digital printing technology. Unlike embroidered designs, these images are seamlessly integrated, ensuring they neither peel off nor fade over time. Our hoodies, made from a blend of 50% cotton and 50% polyester, provide a classic fit with a double-lined hood and color-matched drawcord.</p><p>_ For those seeking premium shirts, our collection of soft, high-quality shirts is a perfect fit. Immerse yourself in 100% cotton shirts, available in various colors and styles. The innovative digital printing technology ensures that the vibrant images on these shirts remain intact for the long haul.</p><p>_ Embrace the winter chill with our warm sweatshirts, designed with your comfort in mind. The images are intricately printed using advanced digital technology, creating a lasting impression. The sweatshirts, featuring a classic fit and 1x1 rib with spandex, guarantee enhanced stretch and recovery.</p><p>_ Elevate your winter wardrobe with our curated selection of cozy and stylish apparel. Your satisfaction is our priority, and we look forward to making your shopping experience truly exceptional.</p><p><strong>RETURNS OR EXCHANGES</strong></p><p>All of our shirts are custom printed so we do not accept returns or exchanges due to the sizing so please make sure you take all the steps to ensure you get the size you are wanting. However, if there are any issues with the shirt itself, please message us and we'd be happy to help correct the error.</p><p><strong>PRODUCTION AND SHIPPING</strong></p><p>Production: 1-3 days Standard Shipping : 3-6 business days after production time</p><p><strong>THANK YOU</strong></p>";
        return $description;
    }

    private function getCategoryId($product_type, $set)
    {
        if ($set == 1) {
            return $product_type === 'Hoodie' ? '601295' : ($product_type === 'Sweatshirt' ? '601295' : '601302');
        }
        return $product_type === 'Hoodie' ? '601213' : ($product_type === 'Sweatshirt' ? '601213' : '1165840');
    }
    private function uploadDescriptionImages($clientAppPartner, $src)
    {
        $uploadProductImage = $clientAppPartner->Product->uploadProductImage($src, 'DESCRIPTION_IMAGE');

        return ["uri" => $uploadProductImage['uri']];
    }
    private function uploadMainImages($clientAppPartner, $images)
    {
        $main_images = [];
        foreach ($images as $key => $image) {
            if ($key < 9 && !isset($image->color)) {
                $uploadProductImage = $clientAppPartner->Product->uploadProductImage($image->src, 'MAIN_IMAGE');
                $main_images[] = ["uri" => $uploadProductImage['uri']];
            }
        }
        return $main_images;
    }

    private function uploadSizeChart($clientAppPartner, $src)
    {
        $uploadProductImage = $clientAppPartner->Product->uploadProductImage($src, 'SIZE_CHART_IMAGE');
        return ["uri" => $uploadProductImage['uri']];
    }

    private function createSkus($clientAppPartner, $variants, $images, $warehouse_id)
    {
        $skus = [];
        foreach ($variants as $variant) {
            $sales_attributes = [
                [
                    "id" => "100000",
                    "name" => "Color",
                    "value_name" => $variant->option2 ?? $variant->option2
                ],
                [
                    "id" => "100007",
                    "name" => "Size",
                    "value_name" => $variant->option3 ?? $variant->option3
                ]
            ];

            $imgurl = $this->findImageId($variant->option2);
            if ($imgurl) {
                // $sku_img = $clientAppPartner->Product->uploadProductImage($imgurl, 'ATTRIBUTE_IMAGE');
                $sales_attributes[0]["sku_img"] = $imgurl;
            }

            if (!empty($variant->option1)) {
                $sales_attributes[] = [
                    "name" => "Type",
                    "value_name" => $variant->option1
                ];
            }

            $variantConvert = [
                "price" => [
                    "currency" => "USD",
                    "amount" => $variant->price
                ],
                "sales_attributes" => $sales_attributes,
                "seller_sku" => "",
                "inventory" => [
                    [
                        "quantity" => (int) ($variant->quantity ?? 999),
                        "warehouse_id" => $warehouse_id
                    ]
                ]
            ];

            if (!in_array($variantConvert, $skus)) {
                $skus[] = $variantConvert;
            }
        }
        return $skus;
    }

    public function findImageId($color)
    {
        foreach ($this->imageattribute as $image) {
            if ($image['color'] === trim($color)) {
                return $image['url'];
            }
        }
        return null;
    }
    // public function  
}
