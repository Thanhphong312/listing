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

class ReportOrderStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $user_id;
    private $store_id;
    private $token;
    private $page;
    private $requestData;
    private $tiktok_order_id;
    public $timeout = 120; // 2 minutes

    /**
     * Create a new job instance.
     */
    public function __construct($user_id,$store_id, $token, $page, $requestData, $tiktok_order_id)
    {
        $this->user_id = $user_id;
        $this->store_id = $store_id;
        $this->token = $token;
        $this->page = $page;
        $this->requestData = $requestData;
        $this->tiktok_order_id = $tiktok_order_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Log::info('GetOrderPageJob started', ['page' => $this->page]);
            $user = User::find($this->user_id);
            $url = $user?->team?->link_page??'https://pthung.fteeck.com';
            $reportResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->withBody(
                json_encode($this->requestData),
                'application/json'
            )->get($url.'/api/orders/getList/' . $this->page);

            if ($reportResponse->successful()) {
                // Log::info('GetOrderPageJob: API request successful', ['page' => $this->page]);

                $responseData = $reportResponse->json();

                foreach ($responseData['orders'] as $orderData) {
                    if($orderData['tiktok_order_id']==$this->tiktok_order_id){
                        $order = Order::updateOrCreate(
                            ['tiktok_order_id' => $orderData['tiktok_order_id']], 
                            [
                                'user_id' => $this->user_id,
                                'store_id' => $this->store_id,
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
                                'design_fee' => (double)$orderData['design_fee'] ?? null,
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

                // Log::info('GetOrderPageJob: Data processed successfully', ['page' => $this->page]);
            } else {
                Log::channel('report-order')->error('GetOrderPageJob: API request failed', [
                    'page' => $this->page,
                    'response' => $reportResponse->body(),
                    'status' => $reportResponse->status(),
                ]);
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
        Log::channel('report-order')->critical('GetOrderPageJob failed', [
            'page' => $this->page,
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
