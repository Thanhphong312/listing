<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Vanguard\Jobs\addProductFlashdealPriorityJob;
use Vanguard\Jobs\AutoFlashdealJob;
use Vanguard\Jobs\UpdateFlashDeals;
use Vanguard\Models\ProductTiktoks;
use Vanguard\Models\FlashDeals;
use Vanguard\Models\ProductFlashdeals;
use Vanguard\Jobs\addProductFlashdealjob;
use Vanguard\Jobs\RenewFlashDealJob;
use Vanguard\Models\Store\Store;
class autoFlashdeal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-flashdeal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $repostflashdeals = ProductFlashdeals::select('id','flashdeal_id','product_id','discount','quantity_limit','quantity_per_user')->where('priority', 1)
            ->limit(50)
            ->get();
        // dd($repostflashdeals);
        foreach($repostflashdeals as $renewflashdeal){   
            $store_id = $renewflashdeal->flashdeal->store_id;
            $activity_id = $renewflashdeal->flashdeal_id;
            $remote_id = $renewflashdeal->product_id; 
            $discount = $renewflashdeal->discount;
            $quantity_limit = $renewflashdeal->quantity_limit;
            $quantity_per_user = $renewflashdeal->quantity_per_user;
            addProductFlashdealPriorityJob::dispatch($store_id, $activity_id, (string)$remote_id, $discount, $quantity_limit, $quantity_per_user)->onQueue('add-product-to-flashdeals-priority');
        }
        // try {//
        // $productTiktoks = ProductTiktoks::selectRaw('id, store_id, JSON_LENGTH(skus) as total_sku, remote_id, discount, is_flashdeal')
        //     ->where('store_id','<',284)
        //     ->where('store_id','>',21)
        //     ->where('is_flashdeal', 0)
        //     ->whereNotNull('discount')
        //     ->whereDoesntHave('flashdealproduct')
        //     ->get();
        // // dd($productTiktoks);
        // $message = [];
        // foreach ($productTiktoks as $productTiktok) {
        //     // dd($productTiktok);
        //     $message[] = $productTiktok->store_id;
        //     AutoFlashdealJob::dispatch($productTiktok->id, $productTiktok->store_id, $productTiktok->total_sku, $productTiktok->remote_id, $productTiktok->discount)->onQueue('auto-flashdeal');
        // }
        // \Log::channel('autoflashdeals')->info(json_encode(array_unique($message)));
    }
}
