<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\FlashDeals;
use Vanguard\Models\Store\Store;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Vanguard\Models\ProductFlashdeals;

class ReCreateFlashDeals implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $id;
    public function __construct($id)
    {
        $this->id = $id;
    }
    public function uniqueId() {
        return $this->id;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // \Log::channel('renew-flash-deal')->info("Start: ".$this->id);
        $now = Carbon::now();
        // $renewflashdeals = FlashDeals::find($this->id);
        
        try {            
            $store = Store::find($this->id);
            // \Log::channel('renew-flash-deal')->info("store: ".$store);
            // $productflashdeals = $renewflashdeals->productflashdeal;
            // dd($productflashdeals);
            $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
            $storetiktok->useVersion(202406);
            $promotion = $storetiktok->Promotion;
            $promotion->useVersion(202309);

            $title = $store->name_flashdeal."|Renew ".Carbon::now()->format('d-m-y H:i');
            // $title = "3|Renew 24-11-2433 17:02";
            $type = 'FLASHDEAL';
            $begin_time = $now->addSecond();
            // Add the calculated duration to the new begin_time to get the new end_time
            $end_time = $begin_time->copy()->addSeconds(2)->timestamp;
            
            $product_level = 'VARIATION';
            // dd($title, $type, $begin_time->timestamp, $end_time, $product_level);
            // \Log::channel('renew-flash-deal')->info($title." ".$type." ". $begin_time->timestamp." ". $end_time." ". $product_level);

            $createFlashdeal = $promotion->createActivity($title, $type, $begin_time->timestamp, $end_time, $product_level);
        
            $activity = $promotion->getActivity($createFlashdeal['activity_id']);
            
            $rs = FlashDeals::updateorCreate([
                    'activity_id' => $activity['activity_id']
                ],[
                    'store_id' => $store->id,
                    'promotion_name' => $activity['title'],
                    'activity_type' => $activity['activity_type'],
                    'product_level' => $activity['product_level'],
                    'status_fld' => $activity['status'],
                    'begin_time' => $activity['begin_time'],
                    'end_time' => $activity['end_time'],
                    'auto' => 1,
                    'status' => 1,
                    'total_sku' => 0
                ]);
            // if($rs){
            //     $renewflashdeals->status_fld = 'EXPIRED';
            //     $renewflashdeals->message = " success | ".$now;
            //     $renewflashdeals->renew = 1;
            //     $renewflashdeals->auto = 0;
            //     $renewflashdeals->save();
            // }
            // $rs = FlashDeals::find(170);
            // dd($rs);
            $store_id = $rs->store_id; 
            $activity_id = $rs->activity_id; // 7426262189666928427
            
            // dd($store_id, $activity_id, $remote_ids, $discount, $quantity_limit, $quantity_per_user);
            
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'cURL') == true || str_contains($e->getMessage(), 'request is limited') == true || str_contains($e->getMessage(), 'System error') == true || str_contains($e->getMessage(), 'Internal system error') == true ) {
                CreateFlashDeals::dispatch($this->id)->onQueue('create-flashdeal');
            }
        }
    }
}
