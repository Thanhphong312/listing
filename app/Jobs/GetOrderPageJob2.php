<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Vanguard\Models\Order\Order;
use Vanguard\Models\Order\OrderItem;
use Vanguard\User;
use Vanguard\Models\Customers;
use Vanguard\Models\Store;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Carbon\Carbon;

class GetOrderPageJob2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user_id;
    private $store_id;
    private $token;
    private $page;
    private $requestData;
    public $timeout = 120; // 2 minutes

    /**
     * Create a new job instance.
     */
    public function __construct($store_id, $token)
    {
        $this->store_id = $store_id;
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $store = Store::find($this->store_id);
            $clientAppPartner = (new ConnectAppPartnerService())->connectAppPartner($store);
            $getOrders = $clientAppPartner['client']->Order->getOrderList([
                "page_size" => 100,
                "next_page_token" => $this->token??null,
            ]);
            \Log::channel('report-order')->info('GetOrderPageJob: Fetched orders', [
                'store_id' => $store->id,
                'response' => $getOrders,
            ]);
            if(empty($getOrders['orders'])) {
                \Log::channel('report-order')->info('GetOrderPageJob: No orders found', [
                    'store_id' => $store->id,
                ]);
                return;
            }
            foreach($getOrders['orders'] as $orderData){
                // dd($orderData);
                if(Order::where('tiktok_order_id',$orderData['id'])->exists()){
                    continue;
                }
                $original_shipping_fee = $orderData['payment']['original_shipping_fee'] ?? 0;
                $original_total_product_price = $orderData['payment']['original_total_product_price'] ?? 0;
                $seller_discount = $orderData['payment']['seller_discount'] ?? 0;
                $shipping_fee = $orderData['payment']['shipping_fee'] ?? 0;
                $total_amount = $orderData['payment']['total_amount'] ?? 0;
                $shipping_fee = $orderData['payment']['shipping_fee'] ?? 0;
                $order = Order::updateOrCreate(
                        ['tiktok_order_id' => $orderData['id']], 
                        [
                            'user_id' => $store->user_id??$store->staff_id,
                            'store_id' => $store->id,
                            'tracking_number' => $orderData['tracking_number'] ?? null,
                            'original_shipping_fee' => $original_shipping_fee,
                            'original_total_product_price' => $original_total_product_price,
                            'seller_discount' => $seller_discount ?? null,
                            'shipping_fee' => $shipping_fee ?? null,
                            'total_amount' => $total_amount ?? null,
                            'order_status' => $orderData['status'] ?? null,
                            'tiktok_create_date' => Carbon::createFromTimestamp(1760800237)->toDateTimeString(),
                            'net_revenue' => 0,
                            'base_cost' => 0,
                            'net_profits' => 0,
                            'design_fee' => 0,
                            'created_at' => $orderData['create_time'],
                            'updated_at' => now(),
                        ]
                    );
                    // dd($order);
                        $district_info = $orderData['recipient_address']['district_info'] ?? [];

                        $country = $state = $county = $city = null;

                        foreach ($district_info as $info) {
                            switch ($info['address_level_name']) {
                                case 'Country':
                                    $country = $info['address_name'];
                                    break;
                                case 'State':
                                    $state = $info['address_name'];
                                    break;
                                case 'County':
                                    $county = $info['address_name'];
                                    break;
                                case 'City':
                                    $city = $info['address_name'];
                                    break;
                            }
                        }                    
                    $Customers = Customers::updateOrCreate(
                        [
                            'order_id' => $order->id
                        ],
                        [
                            'first_name' => $orderData['recipient_address']['first_name'] ?? null,
                            'last_name'  => $orderData['recipient_address']['last_name'] ?? null,
                            'phone'      => $orderData['recipient_address']['phone_number'] ?? null,
                            'address_1'  => $orderData['recipient_address']['address_line1'] ?? null,
                            'address_2'  => $orderData['recipient_address']['address_line2'] ?? null,
                            'city'       => $city,
                            'state'      => $state,
                            'county'     => $county, // nếu bạn cần
                            'postcode'   => $orderData['recipient_address']['postal_code'] ?? null,
                            'country'    => $country,
                        ]
                    );
                    $items = $orderData['line_items'];
                    
                    foreach ($items as $item) {
                        foreach ($items as $item) {
                            OrderItem::updateOrCreate(
                                [
                                    'order_id' => $order->id,
                                    'fteeck_item_id' => null
                                ],
                                [
                                    'product_id' => $item['product_id'], 
                                    'product_name' => $item['product_name'],
                                    'sku_id' => $item['sku_id'],
                                    'quantity' => $item['quantity']??1,
                                    'sku_image' => $item['sku_image'],
                                    'sku_name' => $item['sku_name'],
                                ]
                            );
                        }
                    }
            }
            if(isset($getOrders['next_page_token'])){
                GetOrderPageJob2::dispatch($store->id, $getOrders['next_page_token'])->onQueue('order-page');
            }
        } catch (\Exception $e) {
            Log::channel('report-order')->error('GetOrderPageJob: Exception occurred', [
                'page' => $this->page,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function failed(\Exception $exception): void
    {
        Log::critical('GetOrderPageJob failed', [
            'page' => $this->page,
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
