<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\ProductFlashdeals;
use Vanguard\Models\ProductTiktoks;
use Vanguard\Models\Store\Store;
use Vanguard\Models\StoreProducts;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;

class DeleteProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $store_id;
    private $remote_id;
    public function __construct($store_id, $remote_id)
    {
        $this->store_id = $store_id;
        $this->remote_id = $remote_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $store = Store::find($this->store_id);
        $productStores = StoreProducts::select('id', 'remote_id')->where('remote_id', $this->remote_id)->get();
        $tiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
        foreach ($productStores as $productStore) {
            if ($productStore->remote_id) {

                $deleteproduct = $tiktok->Product->deleteProducts([$productStore->remote_id]);
                if (!isset($deleteproduct[0])) {
                    $productStore->delete();
                    $productTiktoks = ProductTiktoks::where('remote_id', $this->remote_id)->get();
                    foreach ($productTiktoks as $productTiktok) {
                        $productTiktok->delete();
                    }

                    $productFlashdeals = ProductFlashdeals::where('product_id', $this->remote_id)->get();
                    foreach ($productFlashdeals as $productFlashdeal) {
                        $productFlashdeal->delete();
                    }
                } else {
                }
            } else {
                foreach ($productStores as $productStore) {
                    $productStore->delete();
                }
            }
        }

    }
}
