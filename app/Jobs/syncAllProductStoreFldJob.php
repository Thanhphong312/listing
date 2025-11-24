<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\ProductTiktoks;
use Vanguard\Models\Store\Store;
use Illuminate\Support\Facades\Auth;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;

class syncAllProductStoreFldJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $store_id;
    private $page_token;

    /**
     * Create a new job instance.
     *
     * @param int $store
     * @param string $page_token
     */
    public function __construct($store, $page_token = '')
    {
        $this->store_id = $store;
        $this->page_token = $page_token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Fetch the store from the database
            $store = Store::find($this->store_id);

            if (!$store) {
                Log::error("Store not found with ID: {$this->store_id}");
                return;
            }

            // Initialize Tiktok connection
            $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
            $storetiktok->useVersion(202312);

            // Set the request parameters for product search
            $params = ['page_size' => 50];
            if ($this->page_token) {
                $params['page_token'] = $this->page_token;
            }

            // Fetch product data from Tiktok
            $data = $storetiktok->Product->searchProducts($params);

            // Process the product data if available
            if (isset($data['products']) && is_array($data['products'])) {
                foreach ($data['products'] as $product) {
                    if($product['status']=='ACTIVATE'){
                        // Update or create the product records in the local database
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
                            ]
                        );
                    }else{
                        ProductTiktoks::where('store_id', $this->store_id)
                        ->where('remote_id', $product['id'])
                        ->delete();
                    }
                }
            }

            // Check if there are more pages to sync
            if (isset($data['next_page_token']) && $data['next_page_token']) {
                // Dispatch a new job to process the next page
                SyncAllProductStoreFldJob::dispatch($this->store_id, $data['next_page_token'])
                    ->onQueue('sync-all-product-store');
            }

        } catch (Exception $e) {
            // Log any errors that occur during the job execution
            Log::error("Error in SyncAllProductStoreFldJob: " . $e->getMessage());
        }
    }
}
