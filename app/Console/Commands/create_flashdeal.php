<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Vanguard\Jobs\RenewFlashDealJob;
use Vanguard\Models\FlashDeals;
use Vanguard\Models\Store;
use Vanguard\Jobs\GetOrderPageJob2;
class create_flashdeal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create_flashdeal';

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
        // $store = Store::where('cron',1)->get();
        //     foreach($store as $store){
        //         GetOrderPageJob2::dispatch($store->id, null)->onQueue('order-page');        
        //     }
    }
}
