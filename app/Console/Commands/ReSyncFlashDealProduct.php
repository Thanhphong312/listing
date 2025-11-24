<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Vanguard\Jobs\addProductFlashdealjob;
use Vanguard\Models\ProductFlashdeals;

class ReSyncFlashDealProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:re-sync-flash-deal-product';

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
        \Log::channel('reup-product-flash-deal')->info("Reup start --------------------------------------------");

        $repostflashdeals = ProductFlashdeals::with('flashdeal')
            ->where(function($query) {
                $query->where('success', 0);
            })
            ->whereHas('flashdeal', function($query) {
                $query->whereIn('status_fld', ['ONGOING','NOT_START'])
                    ->where('renew', 0)
                    ->where('auto', 1);
            })
            ->get();
        foreach($repostflashdeals as $renewflashdeal){
            
            $store_id = $renewflashdeal->flashdeal->store_id;
            $activity_id = $renewflashdeal->flashdeal_id;
            $remote_id = $renewflashdeal->product_id; 
            $discount = $renewflashdeal->discount;
            $quantity_limit = $renewflashdeal->quantity_limit;
            $quantity_per_user = $renewflashdeal->quantity_per_user;
            addProductFlashdealjob::dispatch($store_id, $activity_id, (string)$remote_id, $discount, $quantity_limit, $quantity_per_user)->onQueue('add-product-to-flashdeals');
        }
        \Log::channel('reup-product-flash-deal')->info("Reup");
        \Log::channel('reup-product-flash-deal')->info($repostflashdeals);
        \Log::channel('reup-product-flash-deal')->info("Reup end --------------------------------------------");


    }
}
