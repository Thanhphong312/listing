<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Vanguard\Product;
use Vanguard\Services\ImageService;
class addMockupProductJob2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $arrayimage;
    private $id;
    private $product_id;
    public function __construct($arrayimage, $id, $product_id)
    {
        $this->arrayimage = $arrayimage;
        $this->id = $id;
        $this->product_id = $product_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $images = [];
        foreach ($this->arrayimage as $arr) {
            // Retrieve the file using the path stored earlier
            $imagePath = Storage::path($arr[1]);  // Get the full local path of the file
            $imgName = 'designs/' . $arr[0];
            \Log::info("add product image");
            \Log::info($imagePath);
            // Create an Imagick object
            $imageservice = new ImageService();

            $urlDesign = $imageservice->resizeImage($imagePath, $imgName, 1200, 1200);
            // Delete the original image from local storage
            Storage::delete($arr[1]);
        
            // Add the image information to the array
            array_push($images, $urlDesign);
        
            // Clear Imagick object to free memory
           
        }
        
        
        \Log::info("images:");
        \Log::info($images);
        
        $product = Product::find($this->id);
        $json = json_decode($product->data);
        $json->main_images = $images;
        $product->data = json_encode($json, JSON_UNESCAPED_UNICODE);
        $product->type = 1;
        $product->save();
    }
    function randomId() {
        $randomNumber = mt_rand(0, 99999999999999); // Generate a random number up to 14 digits
        return str_pad($randomNumber, 14, '0', STR_PAD_LEFT); // Pad the number with leading zeros if necessary
    }
}
