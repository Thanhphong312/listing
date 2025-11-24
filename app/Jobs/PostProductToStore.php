<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\StoreProducts;
use Vanguard\Product;
use Vanguard\Models\Store\Store;

class PostProductToStore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $ids;
    private $products;
    public function __construct($ids, $products)
    {
        $this->ids = $ids;
        $this->products = $products;
    }

    /**
     * Execute the job.
     */
    private function addWatermarkToImages(&$images, $wartermark)
    {
        if(is_array($images)){
            foreach ($images as $image) {
                $image->src = 'https://global24watermark.site/gen-water-mark?url=' . urlencode($image->src) . '&watermark=' . urlencode($wartermark);
            }  
        }else{
            $images = 'https://global24watermark.site/gen-water-mark?url=' . urlencode($images) . '&watermark=' . urlencode($wartermark);

        }
    }
    public function handle(): void
    {
        foreach ($this->products as $product) {
            $product = Product::find($product);
            $store_name = Store::find($this->ids)->watermark;
            $data = json_decode($product->data);
            $productdata = $data->product;
            $this->addWatermarkToImages($productdata->images, $store_name);
            // if(isset($productdata->imagevariants)){
            //     $this->addWatermarkToImages($productdata->imagevariants, $store_name);
            // }
            $storeproduct = StoreProducts::updateorCreate([
                'store_id' => $this->ids,
                'product_id' => $product->id,
            ], [
                'data' => json_encode($data)
            ]);
        }

    }
    private function uploadMainImages($clientAppPartner, $images)
    {
        $main_images = [];
        foreach ($images as $key => $image) {
            if ($key < 9 && !isset($image->color)) {
                $uploadProductImage = $clientAppPartner->Product->uploadProductImage($image->src, 'MAIN_IMAGE');
                $main_images[] = ["uri" => $uploadProductImage['uri']];
            }
        }
        return $main_images;
    }
}
