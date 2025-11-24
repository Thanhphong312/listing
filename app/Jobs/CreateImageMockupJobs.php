<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Services\ImageService;
use Vanguard\Product;
use Vanguard\Models\Designs;

class CreateImageMockupJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $user_id;
    private int $product_id;
    private $discount;
    private $design_id;
    private object $json;
    private ImageService $imageservice;

    /**
     * Create a new job instance.
     */
    public function __construct($user_id ,$product_id ,$discount, $json, $design_id)
    {
        $this->user_id = $user_id;
        $this->discount = $discount;
        $this->product_id = $product_id;
        $this->json = $json;
        $this->design_id = $design_id;
        $this->imageservice = new ImageService();
    }

    /**
     * Execute the job.
     */
        
        public function handle(): void
        {
            $images = $this->json->images ?? null;
            $imagevariants = $this->json->imagevariants ?? null;
    
            if (!is_array($images)) {
                \Log::error("No images found in the JSON data.");
                return;
            }
            
            // Process main images
            foreach ($images as &$designimage) {
                // if (isset($designimage['src'], $designimage['id'])&&str_contains($designimage['src'],'windymockup')) {
                    $url = str_replace(' ', '%20', $designimage['src']); // Thay thế khoảng trắng

                    $newUrl = $this->updateBlaze($url, $designimage['id']);
                    $designimage['src'] = $newUrl;  // Update the src with the new URL
                    \Log::info("Updated main image source", $designimage);
                // }
            }
    
            // Process image variants
            if (is_array($imagevariants)) {
                foreach ($imagevariants as &$designimagevariants) {
                    // if (isset($designimagevariants['src'])) {
                        $url = str_replace(' ', '%20', $designimagevariants['src']); // Thay thế khoảng trắng

                        $newUrl = $this->updateBlaze($url, $designimagevariants['color']);
                        $designimagevariants['src'] = $newUrl;  // Update the src with the new URL
                        \Log::info("Updated image variant source", $designimagevariants);
                    // }
                }
            } else {
                \Log::warning("No image variants found in the JSON data.");
            }
            $this->json->image = $images[0];
            // Save the updated images back to the JSON object
            $this->json->images = $images;
            $this->json->imagevariants = $imagevariants;
            $product = new Product();
            $product->user_id = $this->user_id;
            $product->discount = $this->discount;
            $product->data = json_encode([
                "product" => $this->json
            ]);
            $rs = $product->save();
            if(isset($this->design_id)&&!empty($this->design_id)){
                $design = Designs::find($this->design_id);
                $design->product_listing = $product->id;
                $design->save();
            }
            \Log::info("Updated JSON data.", (array) $this->json);
        }

    private function updateBlaze($url, $id): string
    {
        // return $this->imageservice->resizeImage($url, "{$this->product_id}_{$id}.png", 1155, 1155);
        return $this->imageservice->uploadImage($url, "{$this->randomId()}_{$id}.png");
    }
    function randomId()
    {
        $randomNumber = mt_rand(0, 99999999999999); // Generate a random number up to 14 digits
        return str_pad($randomNumber, 14, '0', STR_PAD_LEFT); // Pad the number with leading zeros if necessary
    }
}
