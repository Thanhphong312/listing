<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\FlashDeals;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Vanguard\Models\Store\Store;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class syncFlashDealJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $store_id;
    public function __construct($store_id)
    {
        $this->store_id = $store_id;
    }
    public function uniqueId() {
        return $this->store_id;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $store = Store::find($this->store_id);

        try{
            $store->syncfld = 1;
            $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
            $storetiktok->useVersion(202406);
            $promotion = $storetiktok->Promotion;
            $promotion->useVersion(202309);
            $listpromotion = $promotion->searchActivities([
                'page_size'=>100,  
                // 'status' => 'ONGOING',
            ]);
            $store->save();
            $activities = ($listpromotion['activities']);
            foreach($activities as $activity){
                //'activity_id','store_id', 'promotion_name', 'activity_type', 'product_level', 'status_fld', 'begin_time', 'end_time', 'auto', 'status'
                if($activity['status']=='ONGOING'){
                    $rs = FlashDeals::updateorCreate([
                        'activity_id' => $activity['id']
                    ],[
                        'store_id' => $this->store_id, 
                        'promotion_name' => $activity['title'], 
                        'activity_type' => $activity['activity_type'], 
                        'product_level' => $activity['product_level'], 
                        'status_fld' => $activity['status'], 
                        'begin_time' => $activity['begin_time'], 
                        'end_time' => $activity['end_time'], 
                        'status' => 1
                    ]);  
                    syncFlashDealProductJob::dispatch($this->store_id, $activity['id'], $activity['status'])->onQueue('sync-product-flashdeal');      
                }else{
                    $rs = FlashDeals::updateorCreate([
                        'activity_id' => $activity['id']
                    ],[
                        'status_fld' => $activity['status'],  
                    ]);  
                }
            } 
            $store->syncfld = 0;
            $store->message = "success | ".Carbon::now();
            $store->save();

        } catch (\Throwable $th) {
            $store->syncfld = 0;
            $store->message = $th->getMessage()." | ".Carbon::now();
            $store->save();
        }
        
    }
}
