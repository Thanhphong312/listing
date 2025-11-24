<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\Payout;
use Carbon\Carbon;
use Vanguard\Models\Store;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;

class ReportPayoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $page_token;
    private $shop_code;
    private $create_time_ge;
    private $create_time_lt;
    public function __construct($shop_code, $create_time_ge, $create_time_lt, $page_token)
    {
        $this->shop_code = $shop_code;
        $this->create_time_ge = $create_time_ge;
        $this->create_time_lt = $create_time_lt;
        $this->page_token = $page_token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $create_time_ge = Carbon::parse($this->create_time_ge)->timestamp;
        $create_time_lt = Carbon::parse($this->create_time_lt)->timestamp;
        $store = Store::where('shop_code', $this->shop_code)->first();
        $storetiktok = (new ConnectAppPartnerService())->connectAppPartnerPostProduct($store)['client'];
        $finances = $storetiktok->Finance->getPayments([
            'sort_field' => 'create_time',
            'create_time_ge' => $create_time_ge,
            'create_time_lt' => $create_time_lt,
            'page_token' => $this->page_token,
            'page_size' => 100
        ]);
        $payments = $finances['payments'];
        foreach ($payments as $payment) {
            Payout::updateOrCreate([
                    'payout_id' => $payment['id'], 
                ],[
                    'store_id' => $store->id,
                    'user_id' => $store->user_id??$store->staff_id,
                    'payout_amout' => $payment['amount']['value'], 
                    'settlement_amount' => $payment['settlement_amount']['value'], 
                    'amount_before_exchange' => $payment['payment_amount_before_exchange']['value'], 
                    'reserve_amount' => $payment['reserve_amount']['value'], 
                    'date' => Carbon::parse($payment['create_time']), 
                    'date_complete' => Carbon::parse($payment['paid_time']),
                    'status' => $payment['status'],
                    'bank_account' => $payment['bank_account']
                ]);
        }
        if($finances['next_page_token']!=""){
            ReportPayoutJob::dispatch($this->shop_code, $this->create_time_ge, $this->create_time_lt, $finances['next_page_token'])->onQueue('report-all-order-payout');
        }
    }
}
