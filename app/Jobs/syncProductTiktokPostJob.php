<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\ProductTiktoks;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Vanguard\Product;
use Vanguard\Models\Store\Store;

class syncProductTiktokPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $store_id;
    private $remote_id;
    private $discount;
    public function __construct($store_id, $remote_id, $discount)
    {
        $this->store_id = $store_id;
        $this->remote_id = $remote_id;
        $this->discount = $discount;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $store = Store::find($this->store_id);

        if (!$store) {
            \Log::error("Store not found with ID: {$this->store_id}");
            return;
        }

        // Initialize Tiktok connection
        $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
        $storetiktok->useVersion(202309);

        // Set the request parameters for product search
        // Fetch product data from Tiktok
        $product = $storetiktok->Product->getproduct($this->remote_id);

        ProductTiktoks::updateOrCreate(
            [
                'store_id' => $this->store_id,
                'remote_id' => $product['id'],
            ],
            [
                'title' => $product['title'],
                'status' => $product['status'],
                'skus' => json_encode(array_map(function ($sku) {
                    return [
                        'id' => $sku['id'],
                        'price' => $sku['price']['tax_exclusive_price']
                    ];
                }, $product['skus'])),
                'discount' => $this->discount ?? null,
            ]
        );
        if($this->discount!=null){
            
        }
       
    }
}
