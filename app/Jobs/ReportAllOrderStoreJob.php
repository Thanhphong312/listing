<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Vanguard\Models\Store;
use Vanguard\Models\Teams;

class ReportAllOrderStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $shop_code;
    private $start_date;
    private $end_date;
    private $url;
    private $team;
    public function __construct($shop_code, $start_date, $end_date, $url, $team)
    {
        $this->shop_code = $shop_code;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->url = $url;
        $this->team = $team;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $tokenResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post($this->url.'/api/account/getToken', [
                    'username' => 'admin',
                    'password' => $this->team==1?'Hungngt123@':'Hungngt123#'
                ]);

        $rawBody = $tokenResponse->body();

        if (!$tokenResponse->successful()) {
            return;
        }

        $token = $rawBody;

        $requestData = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'shop_code' => $this->shop_code
        ];

        $initialResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->withBody(
                json_encode($requestData),
                'application/json'
            )->get($this->url .'/api/orders/getList?page=1');

        if (!$initialResponse->successful()) {
            return;
        }

        $responseData = $initialResponse->json();

        $total = $responseData['total'] ?? 0;
        $pages = ceil($total / 20);
        \Log::channel('report-order')->info('Total: ', ['value' => $total]);

        $store = Store::select('id', 'user_id', 'staff_id')->where('shop_code', $this->shop_code)->first();
        $user_id = null;
        $store_id = null;
        if ($store) {
            $user_id = $store->user_id ?? $store->staff_id;
            $store_id = $store->id;
        }
        // dd($store, $user_id, $store_id);
        // add queue
        for ($page = 1; $page <= $pages; $page++) {
            GetOrderPageJob::dispatch($user_id, $store_id, $token, $page, $requestData)->onQueue('order-page');
        }

    }
}
