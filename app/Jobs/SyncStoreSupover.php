<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\Meta;
use Vanguard\Models\Store\Store;

class SyncStoreSupover implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $store_id;
    public function __construct($store_id)
    {
        $this->store_id = $store_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $store = Store::find($this->store_id);
        $sup_store_id = $store->shop_code;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://ai.supover.com/api/store/info?shop_code='.$sup_store_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Optional: Set headers if needed
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        
        $response = curl_exec($ch);
        $data = json_decode($response);

        $meta = Meta::updateOrCreate([
            'key' => 'access_token',
            'store_id' => $store->id
        ],[
            'value' => $data->access_token
        ]);

        $meta = Meta::updateOrCreate([
            'key' => 'refresh_token',
            'store_id' => $store->id
        ],[
            'value' => $data->refresh_token,
        ]);
        
        $meta = Meta::updateOrCreate([
            'key' => 'access_token_expire',
            'store_id' => $store->id
        ],[
            'value' => $data->access_token_expire,
        ]);

        $meta = Meta::updateOrCreate([
            'key' => 'refresh_token_expire',
            'store_id' => $store->id
        ],[
            'value' => $data->refresh_token_expire
        ]);
    
    }
}
