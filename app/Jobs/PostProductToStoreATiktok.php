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

class PostProductToStoreATiktok implements ShouldQueue
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
    private function addWatermarkToImages2(&$images, $wartermark)
    {
        if(is_array($images)){
            foreach ($images as $image) {
                $image = 'https://global24watermark.site/gen-water-mark?url=' . urlencode($image) . '&watermark=' . urlencode($wartermark);
            }  
        }else{
            $images = 'https://global24watermark.site/gen-water-mark?url=' . urlencode($images) . '&watermark=' . urlencode($wartermark);

        }
        
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->products as $product) {
            $product = Product::find($product);
            $store_name = Store::find($this->ids)->watermark;
            if($product->type==0){
                $data = json_decode($product->data);
                $productdata = $data->product;
                $this->addWatermarkToImages($productdata->images, $store_name);
            }else{
                $data = json_decode($product->data);
                $this->addWatermarkToImages2($data->main_images, $store_name);
            }
            // if(isset($productdata->imagevariants)){
            //     $this->addWatermarkToImages($productdata->imagevariants, $store_name);
            // }
            $storeproduct = StoreProducts::updateorCreate([
                'store_id' => $this->ids,
                'product_id' => $product->id,
            ], [
                'data' => json_encode($data),
                'message' => NULL
            ]);
            if($storeproduct){
                if($product->type==0){
                    PostProductToTiktokShop::dispatch($storeproduct->id, $product->discount)->delay(3)->onQueue('post-product-to-tiktok');
                }else{        
                    PostProductToTiktokShop2::dispatch($storeproduct->id, $product->discount)->delay(3)->onQueue('post-product-to-tiktok');
                }
            }
        }
    }
}
