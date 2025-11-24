<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Vanguard\Models\Order\Order;
use Vanguard\Models\TimeLine;
use Vanguard\Product;
use Vanguard\ProductVariants;
use Vanguard\Services\GoogleDriver\GoogleDriverServices;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SyncDesignDriver implements ShouldQueue, ShouldBeUnique 
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
    private $orderId;
    private $typeButton;
    public function __construct($orderId, $typeButton)
    {
        $this->orderId = $orderId;
        $this->typeButton = $typeButton;
    }
    // public function uniqueId() {
    //     return $this->orderId;
    // }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            \Log::info('Sync all orderId: '.$this->orderId. ' typeButton: '. $this->typeButton);
            $checkSyncDesignOrder = 0;
            // foreach ($this->orders as $orderId) {
                $order = Order::find($this->orderId);
                if($order->fulfill_status == 'onhold'){
                    return;
                }
                $googleDriver = new GoogleDriverServices();
                //sync design
                $checkSyncDesign = 0;
                // if($order->priority==1){
                //     $folderNameIdDesign = $googleDriver->getFolderId('priority_designs');
                // }else{
                    $folderNameIdDesign = $googleDriver->getFolderId('designs');
                // }
                // dd($folderNameIdDesign);
                


                //sync label append
                $checkSyncLabel = 0;
                // if($order->priority==1){
                //     $folderNameIdLabel = $googleDriver->getFolderId('priority_labels');
                // }else{
                    $folderNameIdLabel = $googleDriver->getFolderId('labels');
                // }

                if ($this->typeButton == 'sync_label_driver' || $this->typeButton == 'sync_all_driver') {
                    $urlImage = $order->convert_label;
                    $nameImage = $order->id . '_label_convert' . '.jpg';

                    $checklabel = $order->sync_label;
                    //\Log::info($order->id.' sync label status: '.$checklabel);
                    $checkFile = 1;
                    if ($checklabel) {
                        // \Log::info($order->id.' delete label was sync');
                        // $auth = $googleDriver->getAuthurFile($nameImage, $folderNameIdLabel);
                        // \Log::info($order->id.' auth: '.$auth);
                        // $delete = $this->deleteWithAuth($auth, $nameImage, $folderNameIdLabel);
                        // if($delete){
                        //     \Log::info($order->id.' delete label success');
                        //     $checkFile = $googleDriver->uploadFileFromUrl($urlImage, $nameImage, $folderNameIdLabel);
                        // }else{
                        //     $checkFile = $googleDriver->uploadFileFromUrl($urlImage, $nameImage, $folderNameIdLabel);
                        // }
                    }else{
                        // \Log::info($order->id." delete label won't sync");
                        $checkFile = $googleDriver->uploadFileFromUrl($urlImage, $nameImage, $folderNameIdLabel);
                        if ($checkFile) {
                            $checkSyncLabel = 1;
                            $order->sync_label = $checkSyncLabel;
                            \Log::info($order->id.' sync label success');
                        }
                       
                    }
                   
                    
                    // if ($checkSyncLabel == 1) {
                    //     $order->label_printed = 'printed';
                    // }
                    $order->save();
                }
                
                $orderItem = $order->items;
                // \Log::info($orderItem);
                foreach ($orderItem as $item) {
                    $orderItemMeta = $item->orderItemMetas;
                    // \Log::info($orderItemMeta);
                    \Log::info('sync typeButton: '.$this->typeButton );
                    $site = 0;
                    foreach ($orderItemMeta as $itemMeta) {

                        $designKeysQr = ['front_design_qr', 'back_design_qr', 'sleeve_left_design_qr', 'sleeve_right_design_qr'];
                        if(in_array($itemMeta->meta_key, $designKeysQr)){
                            $site++;
                        }
                    }
                    $site_num = 1;
                    foreach ($orderItemMeta as $itemMeta) {
                        // $designKeys = ['front_design', 'back_design', 'sleeve_left_design', 'sleeve_right_design'];
                        $designKeysPrint = ['front_design_printed', 'back_design_printed', 'sleeve_left_design_printed', 'sleeve_right_design_printed'];

                        $designKeysQr = ['front_design_qr', 'back_design_qr', 'sleeve_left_design_qr', 'sleeve_right_design_qr'];
                        
                        //sync design
                        if ($this->typeButton == 'sync_design_driver' || $this->typeButton == 'sync_all_driver') {
                            
                            if($order->sync_design == 0){
                                if (in_array($itemMeta->meta_key, $designKeysPrint)) {
                                    $itemMeta->meta_value = 1;
                                    $itemMeta->save();
                                }
                            }
                            

                            //sync design qr
                            if (in_array($itemMeta->meta_key, $designKeysQr)) {
                                \Log::info('sync check in array ok');

                                $sitename = $site.'_'.$site_num;
                                if ($item->quantity >= 2) {
                                    for ($i = 1; $i <= $item->quantity; $i++) {
                                        $urlImage = $itemMeta->meta_value;
                                        
                                        $type = str_replace('_design_qr','', $itemMeta->meta_key);
                                        $style = $item->product->style;
                                        $size = $item->product->size;

                                        $nameImage = "{$order->id}_{$item->id }_{$type}_{$size}_{$style}_{$i}_{$sitename}.jpg";
                                        //\Log::info($item->id.":sync qr design path: ".$nameImage);
                                        // echo $nameImage."<br>";
                                        //\Log::info("name: " . $nameImage);
                                        // $checkFileDesign = $googleDriver->getFileInFolder($nameImage, $folderNameIdDesign);
                                        // \Log::info("checkFileDesign: " . $checkFileDesign);
                                        // if (!$checkFileDesign) {
                                        //     $checkFileDesign = $googleDriver->uploadFileFromUrl($urlImage, $nameImage, $folderNameIdDesign);
                                        // }
                                        // if ($checkFileDesign) {
                                        //     $checkSyncDesign = 1;
                                        // }
                                        $checkFileDesign = $order->sync_design;
                                        // \Log::info("checkFileDesign: " . $checkFileDesign);
                                        if ($checkFileDesign) {
                                            \Log::info('sync check was sync array not ');

                                            // $auth = $googleDriver->getAuthurFile($nameImage, $folderNameIdDesign);
                                            // $delete = $this->deleteWithAuth($auth, $nameImage, $folderNameIdDesign);
                                            // if($delete){
                                            //     $checkFileDesign = $googleDriver->uploadFileFromUrl($urlImage, $nameImage, $folderNameIdDesign);
                                            //     $checkSyncDesign = 1;
                                            // }else{
                                                $checkSyncDesign = $googleDriver->uploadFileFromUrl($urlImage, $nameImage, $folderNameIdDesign);
                                                // $checkSyncDesign = 1;
                                                \Log::info("sync check:".$checkSyncDesign);

                                            // }
                                        }else{
                                            \Log::info('sync check was sync array ok');

                                            $checkSyncDesign = $googleDriver->uploadFileFromUrl($urlImage, $nameImage, $folderNameIdDesign);
                                            // $checkSyncDesign = 1;
                                            \Log::info("sync check:".$checkSyncDesign);

                                        }
                                    }
                                    \Log::info($this->orderId." Sync many");
                                } else {
                                    $urlImage = $itemMeta->meta_value;
                                    $type = str_replace('_design_qr','', $itemMeta->meta_key);
                                    $style = $item->product->style;
                                    $size = $item->product->size;
                                    $nameImage = "{$order->id}_{$item->id }_{$type}_{$size}_{$style}_{$sitename}.jpg";
                                    //\Log::info($item->id.":sync qr design path: ".$nameImage);
                                    // echo $nameImage."<br>";

                                    // $checkFileDesign = $googleDriver->getFileInFolder($nameImage, $folderNameIdDesign);
                                    // if (!$checkFileDesign) {
                                    //     $checkFileDesign = $googleDriver->uploadFileFromUrl($urlImage, $nameImage, $folderNameIdDesign);
                                    // }
                                    // if ($checkFileDesign) {
                                    //     $checkSyncDesign = 1;
                                    // }
                                    $checkFileDesign = $order->sync_design;
                                        // \Log::info("checkFileDesign: " . $checkFileDesign);
                                    if ($checkFileDesign) {
                                        \Log::info('sync check was sync array not ');

                                        // $auth = $googleDriver->getAuthurFile($nameImage, $folderNameIdDesign);
                                        // $delete = $this->deleteWithAuth($auth, $nameImage, $folderNameIdDesign);
                                        // if($delete){
                                        //     $checkFileDesign = $googleDriver->uploadFileFromUrl($urlImage, $nameImage, $folderNameIdDesign);
                                        //     $checkSyncDesign = 1;
                                        // }else{
                                            $checkSyncDesign = $googleDriver->uploadFileFromUrl($urlImage, $nameImage, $folderNameIdDesign);
                                            // $checkSyncDesign = 1;
                                            \Log::info("sync check:".$checkSyncDesign);

                                        // }
                                    }else{
                                        \Log::info('sync check was sync array ok');

                                        $checkSyncDesign = $googleDriver->uploadFileFromUrl($urlImage, $nameImage, $folderNameIdDesign);
                                        \Log::info("sync check:".$checkSyncDesign);
                                        // $checkSyncDesign = 1;
                                    }
                                    \Log::info($this->orderId." Sync one");

                                }
                                if(is_null($item->sync_stock)||$item->sync_stock==0){
                                    $item->sync_stock = 1;
                                    $item->save();
                                }
                                $site_num++ ;
                            }
                        }
                    }
                }

                if($checkSyncDesign == 1){
                    if($order->fulfill_status != 'test_order'&&$order->fulfill_status != 'onhold'&&$order->fulfill_status != 'reprint'){
                        $order->fulfill_status = 'printed';
                    }
                    $order->sync_design = 1;
                    \Log::info('orderId: '.$order->id. ' syncDesignStatus:'. $checkSyncDesign);
                    TimeLine::create([
                        'object' => 'order',
                        'object_id' => $order->id,
                        'owner_id' => Auth::user()->id??1,
                        'action' => 'sync design',
                        'note' => Auth::user()->username??"Cron auto" . ' sync design order ' . $order->id,
                    ]);
                }
                
                $reqsult = $order->save();
                //\Log::info('save order: '.$reqsult?"True":"False");
                $checkSyncDesignOrder = $checkSyncDesign;
            // }
        } catch (\Exception $e) {
            //\Log::info($this->orderId);
            //\Log::info('orderId '. $this->orderId. ' syncDesignStatus '. $checkSyncDesignOrder. ' error ');
            //\Log::info($e->getMessage());
        }
    }
    public function deleteWithAuth($numCredentials, $nameImage, $folderNameIdLabel){
        $googleDriver = new GoogleDriverServices($numCredentials);
        return $googleDriver->deleteFileInFolder($nameImage, $folderNameIdLabel);
    }
}
