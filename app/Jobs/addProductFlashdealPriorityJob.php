<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\ProductFlashdeals;
use Vanguard\Models\Store\Store;
use Illuminate\Support\Facades\Auth;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Vanguard\Models\ProductTiktoks;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Vanguard\Models\FlashDeals;

class addProductFlashdealPriorityJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $store_id;
    private $activity_id;
    private $remote_id;
    private $discount;
    private $quantity_limit;
    private $quantity_per_user;
    public function __construct($store_id, $activity_id, $remote_id, $discount, $quantity_limit, $quantity_per_user)
    {
        $this->store_id = $store_id;
        $this->activity_id = $activity_id;
        $this->remote_id = $remote_id;
        $this->discount = $discount;
        $this->quantity_limit = $quantity_limit;
        $this->quantity_per_user = $quantity_per_user;
    }
    public function uniqueId()
    {
        return implode('-', [
            $this->store_id,
            $this->activity_id,
            $this->remote_id,
        ]);
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::channel('product-to-flashdeal')->info("start add product fld ---------------------------------");
        $productFlashdeals = ProductFlashdeals::where('flashdeal_id', $this->activity_id)
            ->where('product_id', $this->remote_id)->first();

        if (!$productFlashdeals || ($productFlashdeals && $productFlashdeals->message != 'success')) {
            $store = Store::find($this->store_id);

            $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];

            try {
                $remote_id = $this->remote_id;
                $discount = $this->discount;
                $quantity_limit = (int) $this->quantity_limit;
                $quantity_per_user = (int) $this->quantity_per_user;
                \Log::channel('product-to-flashdeal')->info("store_id : " . $this->store_id);
                \Log::channel('product-to-flashdeal')->info("activity_id : " . $this->activity_id);
                \Log::channel('product-to-flashdeal')->info("remote_id : " . $this->remote_id);
                \Log::channel('product-to-flashdeal')->info("discount : " . $this->discount);
                \Log::channel('product-to-flashdeal')->info("quantity_limit : " . $this->quantity_limit);
                \Log::channel('product-to-flashdeal')->info("quantity_per_user : " . $this->quantity_per_user);

              
                $storetiktok->useVersion(202309);
                $promotion = $storetiktok->Promotion;
                $producttiktok = ProductTiktoks::where('remote_id', $remote_id)->first();
                $skus = json_decode($producttiktok->skus);
                $result = [];
                foreach ($skus as $product) {
                    $tax_exclusive_price = (float) $product->price;

                    // Tính giá sau khi áp dụng discount
                    $activity_price_amount = $tax_exclusive_price - ($tax_exclusive_price * $discount / 100);

                    // Tạo phần tử mới cho mảng result
                    $result[] = [
                        "activity_price_amount" => (string) round($activity_price_amount, 2),
                        "id" => $product->id,
                        "quantity_limit" => $quantity_limit,
                        "quantity_per_user" => $quantity_per_user
                    ];
                }

                // dd($skus[0], $result[0]);
                $product = [
                    [
                        "id" => (string)$remote_id,
                        "quantity_limit" => $quantity_limit,
                        "quantity_per_user" => $quantity_per_user,
                        "skus" => $result
                    ]
                ];
                // dd($product);
                $updateactivity = $promotion->updateActivityProduct((string)$this->activity_id, $product);

                // $productFlashdeals = ProductFlashdeals::updateOrCreate([
                //     'flashdeal_id' => $this->activity_id,
                //     'product_id' => $this->remote_id,
                // ], [
                //     'discount' => $this->discount,
                //     'quantity_limit' => $this->quantity_limit,
                //     'quantity_per_user' => $this->quantity_per_user,
                //     'skus' => null,
                // ]);
                if(isset($updateactivity['activity_id'])){
                    $productFlashdeals->total_sku = count($skus);
                    $productFlashdeals->message = 'success';
                    $productFlashdeals->success = 1;
                    $productFlashdeals->save();

                    $producttiktok->is_flashdeal = 1;
                    $producttiktok->save();
                }

                // $flashdeal = FlashDeals::where('activity_id',$this->activity_id)->first();
                // $flashdeal->total_sku = $flashdeal->total_sku + count($skus);
                // $flashdeal->save();
                // $flashdealproducts = $promotion->getActivity($this->activity_id)['products'];
                // $totalsku = 0;
                // foreach ($flashdealproducts as $flashdealproduct) {
                //     // `flashdeal_id`, `product_id`, `quantity_limit`, `quantity_per_user`, `sku`,
                //     if ($flashdealproduct['id'] == $this->remote_id) {
                //         ProductFlashdeals::updateOrCreate([
                //             'flashdeal_id' => $this->activity_id,
                //             'product_id' => $flashdealproduct['id'],
                //         ], [
                //             'discount' => $this->discount,
                //             'quantity_limit' => $flashdealproduct['quantity_limit'],
                //             'quantity_per_user' => $flashdealproduct['quantity_per_user'],
                            // 'total_sku' => count($flashdealproduct['skus']),
                //             'message' => 'success'
                //         ]);
                //         $totalsku+=count($flashdealproduct['skus']);
                //     }

                // }
                \Log::channel('product-to-flashdeal')->info("success");

            } catch (\Throwable $th) {
                $product = $storetiktok->Product->getProduct($this->remote_id);

                $productFlashdeals = ProductFlashdeals::updateOrCreate([
                    'flashdeal_id' => $this->activity_id,
                    'product_id' => $this->remote_id,
                ], [
                    'discount' => $this->discount,
                    'quantity_limit' => $this->quantity_limit,
                    'quantity_per_user' => $this->quantity_per_user,
                    'message' => "product status ".$product['status']." | " . $th->getMessage() . " | " . Carbon::now() 
                ]);
                if(str_contains($th->getMessage(), 'SKU cannot be in two') == true){
                    $productFlashdeals = ProductFlashdeals::where('flashdeal_id', $this->activity_id)
                        ->where('product_id',$this->remote_id)->first();
                    $productFlashdeals->delete();
                }
                \Log::channel('product-to-flashdeal')->info("store_id : " . $this->store_id);
                \Log::channel('product-to-flashdeal')->info("activity_id : " . $this->activity_id);
                \Log::channel('product-to-flashdeal')->info("remote_id : " . $this->remote_id);
                \Log::channel('product-to-flashdeal')->info("discount : " . $this->discount);
                \Log::channel('product-to-flashdeal')->info("quantity_limit : " . $this->quantity_limit);
                \Log::channel('product-to-flashdeal')->info("quantity_per_user : " . $this->quantity_per_user);
                \Log::channel('product-to-flashdeal')->info("error");

            }
            \Log::channel('product-to-flashdeal')->info("end add product fld  ---------------------------------");
        }


    }
}
