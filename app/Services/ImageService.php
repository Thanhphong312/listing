<?php

namespace Vanguard\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Imagick;
use Vanguard\Services\PushFileJsonToBackblaze\PushFileJsonToBackblazeService;

class ImageService extends ModelService
{
    /**
     * Resize an image and upload it to the B2 storage disk.
     *
     * @param string $url The URL of the source image.
     * @param string $imgName The name of the resized image file.
     * @param int $width The desired width of the image.
     * @param int $height The desired height of the image.
     * @return string The URL of the resized image.
     * @throws Exception
     */
    public function resizeImage($url, $imgName, $width, $height)
    {
        $imagick = new Imagick($url);
        
        $newWidth = $width;
        $newHeight = $height;

        $imagick->thumbnailImage($newWidth, $newHeight, true);

        $tempFilePath = storage_path('app/tmp/' . $imgName);

        // Ensure the directory exists
        $tempDir = dirname($tempFilePath);
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        file_put_contents($tempFilePath, $imagick->getImageBlob());
        
        $pushFile = new PushFileJsonToBackblazeService();
        $bucketName = 'Windycloud';

        try {
            $response = $pushFile->pushFileToBlaze(new \Illuminate\Http\File($tempFilePath), $imgName, $bucketName);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        $image_link = "https://s3.us-west-004.backblazeb2.com/Windycloud/" . $imgName;
        // Storage::disk('b2')->putFileAs('', new \Illuminate\Http\File($tempFilePath), $imgName, 'public');

        $imagick->clear();
        $imagick->destroy();

        unlink($tempFilePath);

        // $tempUrl = Storage::disk('b2')->temporaryUrl($imgName, now()->addMinutes(60));

        // $urlDesign = strtok($tempUrl, '?');
        return $image_link;
    }

    /**
     * Upload an image to the B2 storage disk.
     *
     * @param string $url The URL of the source image.
     * @param string $imgName The name of the image file.
     * @return string The URL of the uploaded image.
     * @throws Exception
     */
    public function uploadImage($url, $imgName)
    {
        // Read the image content directly from the provided URL
        $imageContent = file_get_contents($url);

        if ($imageContent === false) {
            throw new Exception("Unable to read image from the URL.");
        }

        // Define a temporary file path in the storage directory
        $tempFilePath = storage_path('app/tmp/' . $imgName);

        // Ensure the directory exists
        $tempDir = dirname($tempFilePath);
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Save the image content to the temporary file
        file_put_contents($tempFilePath, $imageContent);

        $pushFile = new PushFileJsonToBackblazeService();
        $bucketName = 'Windycloud';

        try {
            $response = $pushFile->pushFileToBlaze(new \Illuminate\Http\File($tempFilePath), $imgName, $bucketName);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        $image_link = "https://s3.us-west-004.backblazeb2.com/Windycloud/" . $imgName;

        // Store the image to the B2 disk using putFileAs
        // Storage::disk('b2')->putFileAs('', new \Illuminate\Http\File($tempFilePath), $imgName, 'public');

        // Delete the temporary file after upload
        unlink($tempFilePath);

        // Generate a temporary URL for the uploaded image
        // $tempUrl = Storage::disk('b2')->temporaryUrl($imgName, now()->addMinutes(60));

        // Strip the query parameters to return a clean URL
        // $urlDesign = strtok($tempUrl, '?');
        return $image_link;
    }
}
