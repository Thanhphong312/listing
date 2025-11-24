<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\Order\Order;
use Vanguard\Services\Blaze\BlazeService;
use Illuminate\Contracts\Queue\ShouldBeUnique;


class CloneDataJsonJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $order_id;
    private $data_json;
    public function __construct($order_id, $data_json)
    {
        $this->order_id = $order_id;
        $this->data_json = $data_json;
    }
    public function uniqueId()
    {
        return $this->order_id;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $blaze = new BlazeService();
        $blaze->pushOrderJsonToCloud($this->data_json,$this->order_id.".json" );   
        \Log::info('Pushed order json to cloud order id'.$this->order_id);
    }
}
