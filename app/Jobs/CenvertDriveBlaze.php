<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Services\Blaze\BlazeService;
use Vanguard\Models\DesignItems;
use Illuminate\Support\Facades\Storage;
use Vanguard\Services\ImageService;
use Vanguard\Models\DesignMetas;
use Vanguard\Models\Designs;
use Telegram\Bot\Laravel\Facades\Telegram;

class CenvertDriveBlaze implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $url;
    private $fileName;
    private $designId;
    private $key;

    public function __construct($url, $fileName, $designId, $key)
    {
        $this->url = $url;
        $this->fileName = $fileName;
        $this->designId = $designId;
        $this->key = $key;
    }

    public function handle(): void
    {
        $design = Designs::find($this->designId);

        try {
            \Log::info("Processing URL: {$this->url}, FileName: {$this->fileName}, Design ID: {$this->designId}, Key: {$this->key}");

            $fileId = $this->getFileId($this->url);
            if (!$fileId) {
                \Log::error("Invalid Google Drive URL: {$this->url}");
                return;
            }

            $urlvalue = "https://drive.google.com/uc?export=view&id={$fileId}";
            $blazeService = new BlazeService();
            $bucketId = env('B2_BUCKET_ID');

            $result = $blazeService->uploadFile($urlvalue, $this->fileName . ".png", $bucketId);
            if (!$result || !isset($result['fileName'])) {
                \Log::error("Failed to upload file to Blaze: {$this->fileName}");
                return;
            }

            $fileUrl = 'https://windycloud.s3.us-west-004.backblazeb2.com/' . $result['fileName'];
            $imageresize = new ImageService();
            $thumbnailurl = $imageresize->resizeImage($fileUrl, $this->fileName . "_thumbnail_" . random_int(100000, 999999) . ".png", 600, 600);

            if ($design->thumbnail == null) {
                \Log::info("thumbnail");
                $design->thumbnail = $thumbnailurl;
                // \Log::info($thumbnailurl);
                $design->save();
            }
            DesignMetas::updateOrCreate(
                ['design_id' => $this->designId, 'key' => $this->key],
                ['value' => $fileUrl, 'thumbnail' => $thumbnailurl]
            );

            \Log::info("Successfully saved to DesignMetas: Design ID {$this->designId}, Key {$this->key}, File URL {$fileUrl}");

        } catch (\Throwable $th) {
            try {
                Telegram::sendMessage([
                    'chat_id' => $design->user->group_id,
                    'text' => 'Create Design Image Error ID: '.$this->designId.' - '.$th->getMessage(),
                ]);
            } catch (\Throwable $th) {
                Log::channel('telegram-wh')->info($th);
            }
            
        }

    }

    private function getFileId($link)
    {
        preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $link, $matches);
        return $matches[1] ?? null;
    }
}
