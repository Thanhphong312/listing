<?php

namespace Vanguard\Http\Controllers\Api;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use EcomPHP\TiktokShop\Auth;
use EcomPHP\TiktokShop\Client;
use Vanguard\Jobs\addProductFlashdealjob;
use Vanguard\Jobs\RenewFlashDealJob;
use Vanguard\Jobs\ReportOrderStoreJob;
use Vanguard\Models\FlashDeals;
use Vanguard\Models\PartnerApp;
use Vanguard\Models\ProductFlashdeals;
use Vanguard\Models\ProductTiktoks;
use Vanguard\Models\StoreProducts;
use Vanguard\Models\Order\Order;
use Vanguard\Models\Order\OrderItem;
use EcomPHP\TiktokShop\Client as TiktokApiClient;
use Vanguard\Models\Meta;
use Vanguard\Models\Store\Store;
use Vanguard\Services\ImageService;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Imagick;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Vanguard\Product;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    public function index(Request $request, $id)
    {
        try {
            \Log::channel('webhook')->info("start get webhook data -------------------");
            \Log::channel('webhook')->info($id);
            \Log::channel('webhook')->info($request->all());
            $data = $request->all();
            if (isset($data['type']) && $data['type'] == 1) {
                $rs = $data['data'];
                $store = Store::where('shop_id', $data['shop_id'])->first();
                $user = $store->user;
                $url = $user?->team?->link_page??'https://pthung.fteeck.com';

                // $storetiktok = (new ConnectAppPartnerService())->connectAppPartnerPostProductTMP($store)['client'];
                // $order = $storetiktok->Order->getOrderDetail($rs['order_id']);

                $tokenResponse = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])->post($url.'/api/account/getToken', [
                            'username' => 'admin',
                            'password' => $user?->team->id==1?'Hungngt123@':'Hungngt123#'
                        ]);

                $rawBody = $tokenResponse->body();

                if (!$tokenResponse->successful()) {
                    return;
                }

                $token = $rawBody;
                // dd($store->shop_code);
                $tiktok_order_id = $rs['order_id'];
                $requestData = [
                    'start_date' => Carbon::now()->startOfMonth(),
                    'end_date' => Carbon::now()->endOfMonth(),
                    'tiktok_order_id' => $tiktok_order_id
                ];

                $reportResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])->withBody(
                        json_encode($requestData),
                        'application/json'
                    )->get($url.'/api/orders/getList');

                if (!$reportResponse->successful()) {
                    return;
                }

                $responseData = $reportResponse->json();

                // $total = $responseData['total'] ?? 0;
                // $pages = ceil($total / 20);
                // \Log::info('Total: ', ['value' => $total]);

                $user_id = null;
                $store_id = null;
                if ($store) {
                    $user_id = $store->user_id ?? $store->staff_id;
                    $store_id = $store->id;
                }
                // dd($store, $user_id, $store_id);

                try {
                    foreach ($responseData['orders'] as $orderData) {
                        if ($orderData['tiktok_order_id'] == $tiktok_order_id) {
                            $order = Order::updateOrCreate(
                                ['tiktok_order_id' => $orderData['tiktok_order_id']],
                                [
                                    'user_id' => $user_id,
                                    'store_id' => $store_id,
                                    'tracking_number' => $orderData['tracking_number'] ?? null,
                                    'original_shipping_fee' => $orderData['original_shipping_fee'] ?? null,
                                    'original_total_product_price' => $orderData['original_total_product_price'] ?? null,
                                    'seller_discount' => $orderData['seller_discount'] ?? null,
                                    'shipping_fee' => $orderData['shipping_fee'] ?? null,
                                    'total_amount' => $orderData['total_amount'] ?? null,
                                    'order_status' => $orderData['order_status'] ?? null,
                                    'tiktok_create_date' => $orderData['tiktok_create_date'] ?? null,
                                    'net_revenue' => $orderData['net_revenue'] ?? null,
                                    'base_cost' => $orderData['base_cost'] ?? null,
                                    'net_profits' => $orderData['net_profits'] ?? null,
                                    'design_fee' => (float) $orderData['design_fee'] ?? null,
                                    'created_at' => $orderData['created_date'],
                                    'updated_at' => now(),
                                ]
                            );

                            $items = $orderData['items'];

                            foreach ($items as $item) {
                                OrderItem::updateOrCreate(
                                    [
                                        'order_id' => $order->id,
                                        'fteeck_item_id' => $item['id']
                                    ],
                                    [
                                        'product_id' => $item['product_id'],
                                        'product_name' => $item['product_name'],
                                        'sku_id' => $item['sku_id'],
                                        'quantity' => $item['quantity'],
                                        'sku_image' => $item['sku_image'],
                                        'sku_name' => $item['sku_name'],
                                    ]
                                );
                            }
                        }
                    }
                    \Log::info('Get order by tiktok_order_id: Data processed successfully');
                } catch (\Exception $e) {
                    \Log::error('Get order by tiktok_order_id: Exception occurred', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                    throw $e;
                }
                // dd($rs['order_id']);
            } else {
                if(isset($data['code'])){
                    $appPartner = PartnerApp::where('app_secret', $id)->first();
                    \Log::channel('webhook')->info('appPartner');
                    \Log::channel('webhook')->info($appPartner);
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
                        $auth = new Auth($client);
                        $result = $auth->getToken($data['code']);
                        $shopCode = "";
                        try {
                            $client->setAccessToken($result['access_token']);
                            $authorizedShopList = $client->Authorization->getAuthorizedShop();
                            \Log::channel('webhook')->info("authorizedShopList");
                            \Log::channel('webhook')->info($authorizedShopList);
                            $shopCode = $authorizedShopList['shops'][0]['code'];
                        } catch (\Throwable $th) {
                            \Log::channel('webhook')->info("loi");
                            \Log::channel('webhook')->info($th);
                        }
                        \Log::channel('webhook')->info($result);
                        \Log::channel('webhook')->info($result['open_id']);
                        \Log::channel('webhook')->info($result['seller_name']);
                        \Log::channel('webhook')->info($result['access_token']);
                        \Log::channel('webhook')->info($result['refresh_token']);
    
                        $store = Store::where('shop_code', $shopCode)->first();
                        if (!$store) {
                            \Log::channel('webhook')->info("Create new store");
                            $store = new Store();
                            $store->partner_id = $appPartner->id;
                            $store->open_id = $result['open_id'];
                            $store->user_id = $appPartner->seller_id;
                            $store->staff_id = $appPartner->staff_id;
                            $store->name = $result['seller_name'];
                            $store->shop_code = $shopCode;
                            $store->type = 1;
                            $store->status = 1;
                            $store->save();
    
                            Meta::updateOrCreate([
                                'store_id' => $store->id,
                                'key' => 'access_token',
                            ], [
                                'value' => $result['access_token'],
                            ]);
                            Meta::updateOrCreate([
                                'store_id' => $store->id,
                                'key' => 'refresh_token',
                            ], [
                                'value' => $result['refresh_token'],
                            ]);
    
                            Meta::updateOrCreate([
                                'store_id' => $store->id,
                                'key' => 'refresh_token_expire',
                            ], [
                                'value' => $result['refresh_token_expire_in'],
                            ]);
                            Meta::updateOrCreate([
                                'store_id' => $store->id,
                                'key' => 'access_token_expire',
                            ], [
                                'value' => $result['access_token_expire_in'],
                            ]);
                        } else {
                            \Log::channel('webhook')->info("Update store :".$store->name);
                            \Log::channel('webhook')->info("AppPartner Update");
                            \Log::channel('webhook')->info($appPartner);
    
                            $store->update([
                                'partner_id' => $appPartner->id,
                                'open_id' => $result['open_id'],
                                'user_id' => $appPartner->seller_id,
                                'staff_id' => $appPartner->staff_id,
                                'name' => $result['seller_name'],
                                'shop_code' => $shopCode,
                                'type' => 1,
                                'status' => 1,
                            ]);
    
                            Meta::updateOrCreate([
                                'store_id' => $store->id,
                                'key' => 'access_token',
                            ], [
                                'value' => $result['access_token'],
                            ]);
                            Meta::updateOrCreate([
                                'store_id' => $store->id,
                                'key' => 'refresh_token',
                            ], [
                                'value' => $result['refresh_token'],
                            ]);
    
                            Meta::updateOrCreate([
                                'store_id' => $store->id,
                                'key' => 'refresh_token_expire',
                            ], [
                                'value' => $result['refresh_token_expire_in'],
                            ]);
                            Meta::updateOrCreate([
                                'store_id' => $store->id,
                                'key' => 'access_token_expire',
                            ], [
                                'value' => $result['access_token_expire_in'],
                            ]);
                        }
    
                        \Log::channel('webhook')->info($result);
                        \Log::channel('webhook')->info("end get webhook data -------------------");
                        echo "- sync done";
                    } else {
                        echo "ahihi";
                    }
                }
            }
        } catch (\Throwable $th) {
            \Log::channel('webhook')->info("ERROR SHOP AUTH");
            \Log::channel('webhook')->info($th);
            echo "Error! Please contact support global.us.";
        }

    }
    public function testwebhook(Request $request, $id)
    {
        \Log::channel('webhook')->info("start get webhook data -------------------");
        \Log::channel('webhook')->info($id);
        \Log::channel('webhook')->info($request->all());
        $data = $request->all();
        if (isset($data['type']) && $data['type'] == 1) {
            $rs = $data['data'];
            $store = Store::where('shop_id', $data['shop_id'])->first();
            $storetiktok = (new ConnectAppPartnerService())->connectAppPartnerPostProductTMP($store)['client'];
            // $order = $storetiktok->Order->getOrderDetail($rs['order_id']);

            $tokenResponse = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post('https://pthung.fteeck.com/api/account/getToken', [
                        'username' => 'admin',
                        'password' => 'Hungngt123@'
                    ]);

            $rawBody = $tokenResponse->body();

            if (!$tokenResponse->successful()) {
                return;
            }

            $token = $rawBody;
            // dd($store->shop_code);
            $requestData = [
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->endOfMonth(),
                'shop_code' => $store->shop_code
            ];

            $initialResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->withBody(
                    json_encode($requestData),
                    'application/json'
                )->get('https://pthung.fteeck.com/api/orders/getList?page=1');

            if (!$initialResponse->successful()) {
                return;
            }

            $responseData = $initialResponse->json();

            $total = $responseData['total'] ?? 0;
            $pages = ceil($total / 20);
            \Log::info('Total: ', ['value' => $total]);

            $user_id = null;
            $store_id = null;
            if ($store) {
                $user_id = $store->user_id ?? $store->staff_id;
                $store_id = $store->id;
            }
            // dd($store, $user_id, $store_id);
            // add queue
            for ($page = 1; $page <= $pages; $page++) {
                ReportOrderStoreJob::dispatch($user_id, $store_id, $token, $page, $requestData, $rs['order_id'])->onQueue('order-store-page');
            }
            dd($rs['order_id']);
        } else {
            $appPartner = PartnerApp::where('app_secret', $id)->first();
            \Log::channel('webhook')->info('appPartner');
            \Log::channel('webhook')->info($appPartner);
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
                $auth = new Auth($client);
                $result = $auth->getToken($data['code']);
                \Log::channel('webhook')->info($result);
                \Log::channel('webhook')->info($result['open_id']);
                \Log::channel('webhook')->info($result['seller_name']);
                \Log::channel('webhook')->info($result['access_token']);
                \Log::channel('webhook')->info($result['refresh_token']);

                $store = Store::where('partner_id', $appPartner->id)
                    ->where('open_id', $result['open_id'])
                    ->where('user_id', $appPartner->seller_id)->first();
                if (!$store) {
                    $store = new Store();
                    $store->partner_id = $appPartner->id;
                    $store->open_id = $result['open_id'];
                    $store->user_id = $appPartner->seller_id;
                    $store->staff_id = $appPartner->staff_id;
                    $store->name = $result['seller_name'];
                    // $store->shop_code = $result['code'];
                    $store->type = 1;
                    $store->status = 1;
                    $store->save();

                    Meta::updateOrCreate([
                        'store_id' => $store->id,
                        'key' => 'access_token',
                    ], [
                        'value' => $result['access_token'],
                    ]);
                    Meta::updateOrCreate([
                        'store_id' => $store->id,
                        'key' => 'refresh_token',
                    ], [
                        'value' => $result['refresh_token'],
                    ]);

                    Meta::updateOrCreate([
                        'store_id' => $store->id,
                        'key' => 'refresh_token_expire',
                    ], [
                        'value' => $result['refresh_token_expire_in'],
                    ]);
                    Meta::updateOrCreate([
                        'store_id' => $store->id,
                        'key' => 'access_token_expire',
                    ], [
                        'value' => $result['access_token_expire_in'],
                    ]);
                }

                \Log::channel('webhook')->info($result);
                \Log::channel('webhook')->info("end get webhook data -------------------");
                echo "- sync done";
            } else {
                echo "ahihi";
            }
        }
    }
}
