<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\ProductTiktoks;
use Vanguard\Models\FlashDeals;
use Vanguard\Models\ProductFlashdeals;
use Vanguard\Jobs\addProductFlashdealjob;
use Vanguard\Jobs\RenewFlashDealJob;
use Vanguard\Models\Store\Store;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UpdateFlashDeals implements ShouldQueue
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
    public function uniqueId()
    {
        return $this->id;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::channel('autoflashdeals')->info("Start auto flashdeal");

        $producttiktoks = ProductTiktoks::select('id', 'store_id', 'skus', 'remote_id', 'discount', 'is_flashdeal')
            ->where('is_flashdeal', 0)
            // ->where('store_id', $store_id)
            ->whereNotNull('discount')
            ->limit(50)
            ->get();

        // Log all the IDs from the retrieved collection
        \Log::channel('autoflashdeals')->info('Retrieved ProductTiktoks IDs:', $producttiktoks->pluck('id')->toArray());

        // dd($producttiktoks);
        $message = [];
        foreach ($producttiktoks as $producttiktok) {
            $flashdealstores = FlashDeals::select('id', 'activity_id', 'store_id')->where('store_id', $producttiktok->store_id)
                ->whereIn('status_fld', ['ONGOING', 'NOT_START'])
                ->where('auto', 1)
                ->where('renew', 0)
                ->orderBy('id', 'ASC')
                ->get();
            $checkexistfld = 0;
            $flashdeal = null;
            foreach ($flashdealstores as $flashdealstore) {
                $total_sku = ProductFlashdeals::select('id', 'total_sku')
                    ->where('flashdeal_id', $flashdealstore->activity_id)
                    ->sum('total_sku');

                $activity_id = $flashdealstore->activity_id;
                $total_sku_producttiktok = count(json_decode($producttiktok->skus));
                if ($total_sku + $total_sku_producttiktok <= 10000) {
                    $checkexistfld = 1;
                    array_push($message, $producttiktok->id);

                    $productFlashdeals = ProductFlashdeals::updateOrCreate([
                        'flashdeal_id' => $activity_id,
                        'product_id' => (int) $producttiktok->remote_id,
                    ], [
                        'discount' => $producttiktok->discount,
                        'quantity_limit' => -1,
                        'quantity_per_user' => -1,
                        'total_sku' => $total_sku_producttiktok,
                    ]);
                    $producttiktok->is_flashdeal = 1;
                    $producttiktok->save();
                    addProductFlashdealjob::dispatch($producttiktok->store_id, (string) $activity_id, $producttiktok->remote_id, $producttiktok->discount, -1, -1)->onQueue('add-product-to-flashdeals');
                    // dd($total_sku, $total_sku_producttiktok, $activity_id, (int)$producttiktok->remote_id);
                }
                $flashdeal = $flashdealstore;
            }
            // dd($flashdealstore);
            if (!$checkexistfld) {
                $store = Store::select('create_flashdeal', 'id')
                    ->whereNotNull('name_flashdeal')
                    ->where('id', $producttiktok->store_id)
                    ->first();
                \Log::channel('autoflashdeals')->info('No have flashdeal create new flashdeal Store ID: '.$producttiktok->store_id);
                \Log::channel('autoflashdeals')->info('check: '.$store);
                if ($store != null) {
                    $store->create_flashdeal = 0;
                    $store->save();
                    RenewFlashDealJob::dispatch($producttiktok->store_id, 0)->onQueue('renew-flashdeal');
                }
            }
        }
    }
}
