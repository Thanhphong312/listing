<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\FlashDeals;
use Vanguard\Models\ProductFlashdealMeta;
use Vanguard\Models\Store\Store;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Vanguard\Models\ProductFlashdeals;

class RenewFlashDealJob implements ShouldQueue, ShouldBeUnique
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
        \Log::channel('renew-flash-deal')->info("Start: ".$this->id);
        $now = Carbon::now();
        $renewflashdeals = FlashDeals::find($this->id);
        if($renewflashdeals->renew==1){
            return;
        }
        try {            
            $store = Store::find($renewflashdeals->store_id);
            \Log::channel('renew-flash-deal')->info("store: ".$store);
            $productflashdeals = $renewflashdeals->productflashdeal;
            // dd($productflashdeals);
            $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
            $storetiktok->useVersion(202406);
            $promotion = $storetiktok->Promotion;
            $promotion->useVersion(202309);

            $title = $store->name_flashdeal."|Renew ".Carbon::now()->format('d-m-y H:i:s');
            $type = $renewflashdeals->activity_type;
            $begin_time = $now->addSecond();

            // Calculate new end time based on the request inputs and flash deal's timing
            // Assuming the $request->begin_time and $request->renewflashdeals contain timestamps
            $end_time_duration = Carbon::createFromTimestamp($renewflashdeals->begin_time)
                            ->diffInSeconds(Carbon::createFromTimestamp($renewflashdeals->end_time));
        
            // Add the calculated duration to the new begin_time to get the new end_time
            $end_time = $begin_time->copy()->addSeconds($end_time_duration)->timestamp;
            
            $product_level = $renewflashdeals->product_level;
            // dd($title, $type, $begin_time->timestamp, $end_time, $product_level);
            \Log::channel('renew-flash-deal')->info($title." ".$type." ". $begin_time->timestamp." ". $end_time." ". $product_level);

            $createFlashdeal = $promotion->createActivity($title, $type, $begin_time->timestamp, $end_time, $product_level);
        
            $activity = $promotion->getActivity($createFlashdeal['activity_id']);
            
            $totalSku = $productflashdeals->sum('total_sku');

            $rs = FlashDeals::updateorCreate([
                    'activity_id' => $activity['activity_id']
                ],[
                    'store_id' => $renewflashdeals->store_id,
                    'promotion_name' => $activity['title'],
                    'activity_type' => $activity['activity_type'],
                    'product_level' => $activity['product_level'],
                    'status_fld' => $activity['status'],
                    'begin_time' => $activity['begin_time'],
                    'end_time' => $activity['end_time'],
                    'auto' => 1,
                    'status' => 1,
                    'create_new' => 1,
                    'total_sku' => $totalSku
                ]);
            if($rs){
                $renewflashdeals->status_fld = 'EXPIRED';
                $renewflashdeals->message = " success | ".$now;
                $renewflashdeals->renew = 1;
                $renewflashdeals->auto = 0;
                $renewflashdeals->create_new = 1;                
                $renewflashdeals->save();
            }
            // $rs = FlashDeals::find(170);
            // dd($rs);
            $store_id = $rs->store_id; 
            $activity_id = $rs->activity_id; // 7426262189666928427
            
            // // dd($store_id, $activity_id, $remote_ids, $discount, $quantity_limit, $quantity_per_user);
            $listProduct = [];
            foreach($productflashdeals as $productflashdeal){
                // $skus = json_decode($productflashdeal->skus)??[];
                // $totalAmount = 0;

                // foreach ($skus as $sku) {
                //     $totalAmount += (float) $sku->activity_price->amount;
                // }
                // if($totalAmount==0){
                //     $discount = 0;
                // }else{
                //     $discount = calPercentProduct($totalAmount, $productflashdeal->product_id);            
                // }
                $discount = $productflashdeal->discount;
                $quantity_limit= $productflashdeal->quantity_limit;
                $quantity_per_user= $productflashdeal->quantity_per_user;
                \Log::channel('renew-flash-deal')->info("Renew : ".$rs->promotion_name);
                \Log::channel('renew-flash-deal')->info($store_id." ".$activity_id." ".$productflashdeal->product_id." ".$discount." ".(int)$quantity_limit." ".(int)$quantity_per_user);
                $productFlashdeals = ProductFlashdeals::updateOrCreate([
                    'product_id' => $productflashdeal->product_id,
                ], [
                    'flashdeal_id' => (string)$activity_id,
                    'discount' => $discount,
                    'quantity_limit' => $quantity_limit,
                    'quantity_per_user' => $quantity_per_user,
                    'total_sku' => $productflashdeal->total_sku,
                    'message' => '',
                    'success' => 0,
                ]);
                // dd($store_id, $activity_id, $productflashdeal->product_id , $discount, (int)$quantity_limit, (int)$quantity_per_user);
                addProductFlashdealjob::dispatch($store_id, (string)$activity_id, $productflashdeal->product_id , $discount, (int)$quantity_limit, (int)$quantity_per_user)->onQueue('add-product-to-flashdeals');
                array_push($listProduct,$productflashdeal->product_id);
            }
            if(count($listProduct)){
                ProductFlashdealMeta::updateOrCreate(
                        [
                            'product_flashdeal_id' => $renewflashdeals->activity_id 
                        ],
                        [
                            'meta_key'=> 'product_flashdeal', 
                            'meta_value'=> implode(",",$listProduct)
                        ]
                    );
            }
        } catch (\Throwable $th) {
            $renewflashdeals->message = $th->getMessage()." | ".$now;
            $renewflashdeals->save();
        }
    }
}
