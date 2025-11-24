<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\ProductFlashdeals;
use Vanguard\Models\ProductTiktoks;

class mapJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $producttiktok = ProductTiktoks::select('id','remote_id','discount')
            ->where('id', $this->id)
            ->first();
        $productfld = ProductFlashdeals::select('discount')->where('product_id', $producttiktok->remote_id)->first();
        $producttiktok->discount = $productfld->discount;
        $producttiktok->save();
    }
}
