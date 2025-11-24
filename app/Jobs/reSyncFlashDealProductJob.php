<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\ProductFlashdeals;
use Vanguard\Models\Store\Store;
use Illuminate\Support\Facades\Auth;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;

class reSyncFlashDealProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $store_id;
    private $flashdeal_id;
    private $status;
    public function __construct($store_id, $flashdeal_id, $status)
    {
        $this->store_id = $store_id;
        $this->flashdeal_id = $flashdeal_id;
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $store = Store::find($this->store_id);
        try {
            $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
            $storetiktok->useVersion(202312);
            $promotion = $storetiktok->Promotion;
            $promotion->useVersion(202309);
            $flashdealproducts = $promotion->getActivity($this->flashdeal_id)['products'];
            foreach ($flashdealproducts as $flashdealproduct) {
                // \Log::info($flashdealproduct);
                // `flashdeal_id`, `product_id`, `quantity_limit`, `quantity_per_user`, `sku`,
                // if($this->status=='ACTIVATE'){
                    $skus = $flashdealproduct['skus'];
                    $totalAmount = 0;
    
                    foreach ($skus as $sku) {
                        $totalAmount += (float) $sku['activity_price']['amount'];
                    }
                    if ($totalAmount == 0) {
                        $discount = 0;
                    } else {
                        $discount = calPercentProduct($totalAmount, (int) $flashdealproduct['id']);
                    }
                    if ($discount > 0) {
                        ProductFlashdeals::updateOrCreate([
                            'product_id' => $flashdealproduct['id']
                        ], [
                            'flashdeal_id' => $this->flashdeal_id,
                            'discount' => $discount,
                            'quantity_limit' => $flashdealproduct['quantity_limit'],
                            'quantity_per_user' => $flashdealproduct['quantity_per_user'],
                            'total_sku' => count($skus),
                            'message' => 'success'
                        ]);
                    }
                // }else{
                //     array_push($listProduct,$flashdealproduct['id']);
                // }
            }
            // if(count($listProduct)){
            //     ProductFlashdealMeta::updateOrCreate(
            //             [
            //                 'product_flashdeal_id' => $this->flashdeal_id
            //             ],
            //             [
            //                 'meta_key'=> 'product_flashdeal', 
            //                 'meta_value'=> implode(",",$listProduct)
            //             ]
            //         );
            // }
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'Too many requests') == true ||str_contains($e->getMessage(), 'request is limited') == true || str_contains($e->getMessage(), 'System error') == true || str_contains($e->getMessage(), 'Internal system error') == true ) {
                syncFlashDealProductJob::dispatch($this->store_id, $this->flashdeal_id, $this->status)->onQueue('sync-product-flashdeal');
            }
        }

    }
}
