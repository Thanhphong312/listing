<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Vanguard\Models\ProductFlashdeals;

class deleteProductFlashdealJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $flashdeal_id;
    private $dateCreate;
    public function __construct($flashdeal_id, $dateCreate)
    {
        $this->flashdeal_id = $flashdeal_id; 
        $this->dateCreate = $dateCreate; 
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $sevenDays = Carbon::now()->subDays(7)->startOfDay();
        $productFlashdeals = ProductFlashdeals::where('flashdeal_id', $this->flashdeal_id)
            ->get();
        $product_id = [];
        \Log::channel('convert-flashdeal')->info('Run : '.$this->flashdeal_id.' - '.$this->dateCreate.' - '.$productFlashdeals->count());
        foreach($productFlashdeals as $productFlashdeal){
            if($this->dateCreate >= $sevenDays){
                array_push($product_id, $productFlashdeal->product_id);
                \Log::channel('convert-flashdeal-save')->info('save meta - flashdeal_id: '.$productFlashdeal->flashdeal_id.'- product_id:'.$productFlashdeal->product_id);
            }
            $rs = $productFlashdeal->delete();
            if($rs){
                \Log::channel('convert-flashdeal')->info('delete ProductFlashdeals success: '.$productFlashdeal->flashdeal_id);
            }
        }
    }
}
