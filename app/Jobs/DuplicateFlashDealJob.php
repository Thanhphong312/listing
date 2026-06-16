<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\FlashDeals;
use Vanguard\Models\ProductFlashdeals;
use Vanguard\Models\Store\Store;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Carbon\Carbon;

class DuplicateFlashDealJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $flashdeal_id;

    public function __construct($flashdeal_id)
    {
        $this->flashdeal_id = $flashdeal_id;
    }

    public function handle(): void
    {
        \Log::channel('renew-flash-deal')->info("DuplicateFLD start: " . $this->flashdeal_id);

        $original = FlashDeals::find($this->flashdeal_id);
        if (!$original) {
            \Log::channel('renew-flash-deal')->error("DuplicateFLD: flashdeal not found: " . $this->flashdeal_id);
            return;
        }

        $store = Store::find($original->store_id);

        try {
            $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
            $storetiktok->useVersion(202406);
            $promotion = $storetiktok->Promotion;
            $promotion->useVersion(202309);

            $now = Carbon::now();
            $title = $original->promotion_name . ' | Copy ' . $now->format('d-m-y H:i:s');

            $duration = Carbon::createFromTimestamp($original->begin_time)
                ->diffInSeconds(Carbon::createFromTimestamp($original->end_time));
            $begin_time = $now->addSecond()->timestamp;
            $end_time = $now->copy()->addSeconds($duration)->timestamp;

            $created = $promotion->createActivity(
                $title,
                $original->activity_type,
                $begin_time,
                $end_time,
                $original->product_level
            );

            $activity = $promotion->getActivity($created['activity_id']);

            $newFld = FlashDeals::updateOrCreate(
                ['activity_id' => $activity['activity_id']],
                [
                    'store_id'       => $original->store_id,
                    'promotion_name' => $activity['title'],
                    'activity_type'  => $activity['activity_type'],
                    'product_level'  => $activity['product_level'],
                    'status_fld'     => $activity['status'],
                    'begin_time'     => $activity['begin_time'],
                    'end_time'       => $activity['end_time'],
                    'auto'           => $original->auto,
                    'status'         => 1,
                    'create_new'     => 1,
                ]
            );

            $products = ProductFlashdeals::where('flashdeal_id', $original->activity_id)->get();

            foreach ($products as $pf) {
                ProductFlashdeals::updateOrCreate(
                    [
                        'flashdeal_id' => (string) $newFld->activity_id,
                        'product_id'   => $pf->product_id,
                    ],
                    [
                        'discount'          => $pf->discount,
                        'quantity_limit'    => $pf->quantity_limit,
                        'quantity_per_user' => $pf->quantity_per_user,
                        'total_sku'         => $pf->total_sku,
                        'message'           => '',
                        'success'           => 0,
                    ]
                );

                addProductFlashdealjob::dispatch(
                    $original->store_id,
                    (string) $newFld->activity_id,
                    $pf->product_id,
                    $pf->discount,
                    (int) $pf->quantity_limit,
                    (int) $pf->quantity_per_user
                )->onQueue('add-product-to-flashdeals');
            }

            \Log::channel('renew-flash-deal')->info("DuplicateFLD done: new activity_id=" . $newFld->activity_id . " products=" . $products->count());

        } catch (\Throwable $th) {
            \Log::channel('renew-flash-deal')->error("DuplicateFLD error: " . $th->getMessage());
        }
    }
}
