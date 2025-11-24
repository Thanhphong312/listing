<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\FlashDeals;
use Vanguard\Models\ProductFlashdeals;
use Vanguard\Models\ProductTiktoks;
use Vanguard\Jobs\addProductFlashdealjob;
use Vanguard\Models\Store\Store;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class AutoFlashdealJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $producttiktok_id;
    private $store_id;
    private $total_sku;
    private $remote_id;
    private $discount;
    public function __construct($producttiktok_id, $store_id, $total_sku, $remote_id, $discount)
    {
        $this->producttiktok_id = $producttiktok_id;
        $this->store_id = $store_id;
        $this->total_sku = $total_sku;
        $this->remote_id = $remote_id;
        $this->discount = $discount;
    }
    public function uniqueId()
    {
        return $this->producttiktok_id;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::channel('autoflashdeals')->info($this->producttiktok_id." _ ".$this->store_id." _ ".$this->total_sku." _ ".$this->remote_id." _ ".$this->discount);
        $flashdealStores = FlashDeals::select('id', 'activity_id', 'store_id')
            ->where('store_id', $this->store_id)
            ->where('status_fld', ['ONGOING','NOT_START'])
            ->where('auto', 1)
            ->where('renew', 0)
            ->orderBy('id', 'DESC')
            ->limit(15)
            ->get();
        $productTiktok = ProductTiktoks::select('is_flashdeal','store_id','remote_id','discount')->where('id', $this->producttiktok_id)->first();

        \Log::channel('autoflashdeals')->info("Product tiktok");
        \Log::channel('autoflashdeals')->info($productTiktok);
        \Log::channel('autoflashdeals')->info("List flashdealStores");
        \Log::channel('autoflashdeals')->info($flashdealStores);

        $isFlashdealAdded = false;

        foreach ($flashdealStores as $flashdealStore) {
            $totalSku = ProductFlashdeals::where('flashdeal_id', $flashdealStore->activity_id)->sum('total_sku');
            $activityId = $flashdealStore->activity_id;
        
            $totalSkuProductTiktok = $this->total_sku;
        
            \Log::channel('autoflashdeals')->info("Checking total SKU: totalSku = $totalSku, totalSkuProductTiktok = $totalSkuProductTiktok, sum = " . ($totalSku + $totalSkuProductTiktok));
        
            if ($totalSku + $totalSkuProductTiktok <= 10000) {
                \Log::channel('autoflashdeals')->info("Adding to flash deal, activity ID: $activityId");
        
                $isFlashdealAdded = true;
                $productFlashdeal = ProductFlashdeals::where([
                    'product_id' => $this->remote_id,
                ])->first();
                \Log::channel('autoflashdeals')->info("Breaking loop for productFlashdeal: $productFlashdeal ");

                if ($productFlashdeal) {
                    $productFlashdeal->flashdeal_id = $activityId;
                    $productFlashdeal->discount = $this->discount;
                    $productFlashdeal->quantity_limit = -1;
                    $productFlashdeal->quantity_per_user = -1;
                    $productFlashdeal->total_sku = $totalSkuProductTiktok;
                    $productFlashdeal->save();

                } else {
                    ProductFlashdeals::create([
                        'flashdeal_id' => $activityId,
                        'product_id' => (int) $this->remote_id,
                        'discount' => $this->discount,
                        'quantity_limit' => -1,
                        'quantity_per_user' => -1,
                        'total_sku' => $totalSkuProductTiktok,
                    ]);
                }
        
                $productTiktok->is_flashdeal = 1;
                $productTiktok->save();
        
                addProductFlashdealjob::dispatch(
                    $productTiktok->store_id,
                    (string) $activityId,
                    $productTiktok->remote_id,
                    $productTiktok->discount,
                    -1,
                    -1
                )->onQueue('add-product-to-flashdeals');
        
                \Log::channel('autoflashdeals')->info("Breaking loop for activity ID: $activityId");
                break; // Ensure break works as intended
            }
        }
        
        $store = Store::select('id', 'create_flashdeal')->where('id', $productTiktok->store_id)->first();
        if (!$isFlashdealAdded && !$store->create_flashdeal) {
            \Log::channel('autoflashdeals')->info("No flash deal added. Updating store: $store->id");
            $store->update([
                'create_flashdeal' => 1
            ]);
        }
        
    }
}
