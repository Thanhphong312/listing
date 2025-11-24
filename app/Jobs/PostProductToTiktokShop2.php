<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\StoreProducts;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Vanguard\Product;
use Vanguard\Models\Store\Store;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Telegram\Bot\Laravel\Facades\Telegram;

class PostProductToTiktokShop2 implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $id;
    private $discount;
    public $imageattribute = [];
    public function __construct($id, $discount)
    {
        $this->id = $id;
        $this->discount = $discount;
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
        $storeproduct = StoreProducts::find($this->id);
        \Log::channel('export-product-tiktokshop')->info("Start post to tiktok");
        \Log::channel('export-product-tiktokshop')->info($storeproduct->id);

        if ($storeproduct) {
            $store = Store::find($storeproduct->store_id);
            $product = $storeproduct;
            \Log::channel('export-product-tiktokshop')->info("store");
            \Log::channel('export-product-tiktokshop')->info($store->id);
            \Log::channel('export-product-tiktokshop')->info("product");
            \Log::channel('export-product-tiktokshop')->info($product->data);

            if (!empty($storeproduct->remote_id)) {
                return;
            }
            try {
                $custom_data = json_decode($product->data);
                $clientAppPartner = (new ConnectAppPartnerService())->connectAppPartner($store);

                if (!isset($clientAppPartner['client'])) {
                    \Log::channel('export-product-tiktokshop')->error("{$store->name} - title: {$product->product_id} - store has no app");
                    return;
                }

                $clientAppPartner = $clientAppPartner['client'];
                $warehouse_id = $this->getDefaultWarehouseId($clientAppPartner);

                $dataJson = $custom_data;
                $description = $dataJson->description;
                if (str_contains($description, '<img') == true) {
                    $description = preg_replace_callback(
                        '/<img\s+[^>]*src=["\']([^"\']+)["\'][^>]*>/i',
                        function ($matches)  {
                            $originalSrc = $matches[1]; // Extract the original src URL
                            // $newSrcData = $this->uploadDescriptionImages($clientAppPartner, $originalSrc);
                            $newSrc = "";
                            if(str_contains($originalSrc, 'f194e6f8faf44b6197e5f3e9d1c4190d') == true){
                                $newSrc =  'https://p16-oec-ttp.tiktokcdn-us.com/tos-useast5-i-omjb5zjo8w-tx/f194e6f8faf44b6197e5f3e9d1c4190d~tplv-omjb5zjo8w-origin-jpeg.jpeg';
                            }else{
                                $newSrc = "https://p19-oec-ttp.tiktokcdn-us.com/tos-useast5-i-omjb5zjo8w-tx/ea6530172fd7443e8b88aabe10c662f0~tplv-omjb5zjo8w-origin-jpeg.jpeg"; // Get the new src URL from the upload function
                            }
        
                            return str_replace($originalSrc, $newSrc, $matches[0]);
                        },
                        $description
                    );
                }
                $dataJson->category_id = (string)$dataJson->category_id;
                $dataJson->description = $description;
                $title = $dataJson->title . " " . $store->keyword ?? "";
                
                $this->uploadAttributeImage($clientAppPartner, $dataJson);
                $dataJson->main_images = $this->uploadMainImages($clientAppPartner, $dataJson->main_images);
                \Log::channel('export-product-tiktokshop')->info("main_images");
                \Log::channel('export-product-tiktokshop')->info($dataJson->main_images);
                $this->addWarehourseSkus($dataJson->skus, $warehouse_id);

                $dataJson->size_chart->image =  $this->uploadSizeChart($clientAppPartner, $dataJson->size_chart->image);
                \Log::channel('export-product-tiktokshop')->info("dataJson");
                \Log::channel('export-product-tiktokshop')->info(json_encode($dataJson));
                
                $createProduct = $clientAppPartner->Product->createProduct($dataJson);
                $storeproduct->remote_id = $createProduct['product_id'];
                $storeproduct->message = "success";
                $storeproduct->save();
                syncProductTiktokPostJob::dispatch($storeproduct->store_id, $createProduct['product_id'], $this->discount)->onQueue('sync-product-post_tiktok');
                \Log::channel('export-product-tiktokshop')->info("{$store->name} - title: {$product->title} - post success. Remote ID: {$createProduct['product_id']}");

            } catch (\Exception $e) {
                \Log::channel('export-product-tiktokshop')->error("{$store->name} - title: {$product->title} - {$e->getMessage()}");
                // Check if the error message contains 'cURL error 56' or 'Unable'
                $storeproduct->message = $e->getMessage();
                $storeproduct->save();
                if (str_contains($e->getMessage(), 'fopen') == true || str_contains($e->getMessage(), 'Unable to parse response string as JSON') == true || str_contains($e->getMessage(), 'request is limited') == true || str_contains($e->getMessage(), 'System error') == true || str_contains($e->getMessage(), 'Internal system error') == true ) {
                    // Dispatch the job to the 'post-product-to-tiktok' queue, with a 2-second delay
                    rePostProductToTiktokShop::dispatch($this->id, $this->discount)
                        ->delay(now()->addSeconds(2))  // Using `now()` for better clarity and consistency
                        ->onQueue('re-post-product-to-tiktok');
                }
                try {
                    Telegram::sendMessage([
                        'chat_id' => $store->user->group_id,
                        'text' => 'Post Product to Tiktok Error ID: '.$storeproduct->id.' - '.$e->getMessage(),
                    ]);
                } catch (\Throwable $th) {
                    \Log::channel('telegram-wh')->info($th);
                }
            }
        }

    }
    private function addWarehourseSkus(&$skus, $warehouse_id){
        foreach($skus as $sku){
            $sku->inventory[0]->warehouse_id = (string) $warehouse_id;
        }
    }
    private function getDefaultWarehouseId($clientAppPartner)
    {
        $warehouses = $clientAppPartner->Logistic->getWarehouseList()['warehouses'];
        foreach ($warehouses as $warehouse) {
            if ($warehouse['is_default']) {
                return $warehouse['id'];
            }
        }
        return null; // Or handle this case as needed
    }

    private function getDefaultDescription()
    {
        $description = "<p><strong>Welcome to the store!</strong></p><p>_ Experience unparalleled comfort and style with our versatile collection of hoodies, sweatshirts, and t-shirts. Crafted with a passion for providing the perfect shopping experience, our products are designed to keep you warm and cozy throughout the winter.</p><p>_ Feel free to explore a wide range of soft, comfy hoodies that are perfect for the season. We take pride in offering customization options, allowing you to choose your preferred color or even request a custom design. Should you have any questions or specific concerns, our dedicated team is always ready to assist – just drop us a message.</p><p>_ The standout feature of our products lies in the captivating images printed on the fabric using cutting-edge digital printing technology. Unlike embroidered designs, these images are seamlessly integrated, ensuring they neither peel off nor fade over time. Our hoodies, made from a blend of 50% cotton and 50% polyester, provide a classic fit with a double-lined hood and color-matched drawcord.</p><p>_ For those seeking premium shirts, our collection of soft, high-quality shirts is a perfect fit. Immerse yourself in 100% cotton shirts, available in various colors and styles. The innovative digital printing technology ensures that the vibrant images on these shirts remain intact for the long haul.</p><p>_ Embrace the winter chill with our warm sweatshirts, designed with your comfort in mind. The images are intricately printed using advanced digital technology, creating a lasting impression. The sweatshirts, featuring a classic fit and 1x1 rib with spandex, guarantee enhanced stretch and recovery.</p><p>_ Elevate your winter wardrobe with our curated selection of cozy and stylish apparel. Your satisfaction is our priority, and we look forward to making your shopping experience truly exceptional.</p><p><strong>RETURNS OR EXCHANGES</strong></p><p>All of our shirts are custom printed so we do not accept returns or exchanges due to the sizing so please make sure you take all the steps to ensure you get the size you are wanting. However, if there are any issues with the shirt itself, please message us and we'd be happy to help correct the error.</p><p><strong>PRODUCTION AND SHIPPING</strong></p><p>Production: 1-3 days Standard Shipping : 3-6 business days after production time</p><p><strong>THANK YOU</strong></p>";
        return $description;
    }

    private function uploadDescriptionImages($clientAppPartner, $src)
    {
        $uploadProductImage = $clientAppPartner->Product->uploadProductImage($src, 'DESCRIPTION_IMAGE');

        return ["uri" => $uploadProductImage['uri']];
    }
    private function uploadMainImages($clientAppPartner, $images)
    {
        $main_images = [];
        foreach ($images as $key => $image) {
            if ($key < 9 && !isset($image->color)) {
                $uploadProductImage = $clientAppPartner->Product->uploadProductImage($image, 'MAIN_IMAGE');
                $main_images[] = ["uri" => $uploadProductImage['uri']];
            }
        }
        return $main_images;
    }
    private function uploadAttributeImage($clientAppPartner, &$json)
    {
        $attri_images = [];
        $skus = $json->skus;
        foreach ($skus as $sku){
            foreach($sku->sales_attributes as $data) {
                if (isset($data->sku_img) && !empty($data->sku_img)) {
                    $data->sku_img = $clientAppPartner->Product->uploadProductImage(str_replace(' ', '%20', $data->sku_img), 'ATTRIBUTE_IMAGE');
                    \Log::channel('export-product-tiktokshop')->info("dataProduct");
                    \Log::channel('export-product-tiktokshop')->info($data->sku_img);
                }
            }
        }
        return $attri_images;
    }
    private function uploadSizeChart($clientAppPartner, $src)
    {
        $uploadProductImage = $clientAppPartner->Product->uploadProductImage($src, 'SIZE_CHART_IMAGE');
        return ["uri" => $uploadProductImage['uri']];
    }
    public function findColor($variant){
        foreach ($variant as $value) {
            if($value->name=='color'){
                return $value->value;
            }
        }
        return "";
    }

    public function findImageId($color)
    {
        foreach ($this->imageattribute as $image) {
            if ($image['color'] === trim($color)) {
                return $image['url'];
            }
        }
        return null;
    }
}
