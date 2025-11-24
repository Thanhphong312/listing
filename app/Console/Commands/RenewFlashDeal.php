<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Vanguard\Jobs\RenewFlashDealJob;
use Carbon\Carbon;
use Vanguard\Models\FlashDeals;

class RenewFlashDeal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:renew-flash-deal';

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
        \Log::channel('renew-flash-deal')->info("renew start----------------------------------------");

        $now = Carbon::now()->timestamp; // Get current timestamp
        $renewflashdeals = FlashDeals::select('id')
            ->whereIn('status_fld', ['ONGOING','NOT_START','EXPIRED'])
            ->where('auto', 1)
            ->where('end_time', '<=', $now) // Compare as timestamp
            ->where('renew', 0)
            ->pluck('id')
            ->toArray();
        foreach ($renewflashdeals as $renewflashdeal) {
            // dd($renewflashdeal);
            RenewFlashDealJob::dispatch($renewflashdeal)->onQueue('renew-flashdeal');
        }
        \Log::channel('renew-flash-deal')->info("renew flashdeal");
        \Log::channel('renew-flash-deal')->info($renewflashdeals);
        \Log::channel('renew-flash-deal')->info("renew end ----------------------------------------");
        // $stores = Store::select('id')->where('create_flashdeal', 1)->get();
        // if (count($stores) > 0) {
        //     foreach ($stores as $store) {
        //         $firstFlashDeal = FlashDeals::select('id')->where('store_id', $store->id)->orderBy('id', 'DESC')->first();
        //         RenewFlashDealJob::dispatch($firstFlashDeal->id, 1)->onQueue('renew-flashdeal');
        //     }
        // }
    }
}
