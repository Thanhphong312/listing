<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Vanguard\Models\Order\Order;
use Vanguard\Models\TimeLine;
use Vanguard\Services\Dropbox\DropboxService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class syncDesignCreateOrder implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $orderId;
    private $typeButton;
    protected DropboxService $dropboxService;

    public function __construct($orderId, $typeButton)
    {
        $this->orderId = $orderId;
        $this->typeButton = $typeButton;
        $this->dropboxService = new DropboxService(); // Initialize the DropboxService

    }
    public function uniqueId()
    {
        return $this->orderId;
    }

    public function uploadFileFromUrl($folder, $url, $filename): string
    {
        if ($folder === null || $filename === null) {
            \Log::error('Folder or filename is null!');
            return ""; // hoặc giá trị phù hợp với trường hợp của bạn
        }

        $destinationPath = "/$folder/$filename";
        $result = $this->dropboxService->uploadFileFromUrl($url, $destinationPath);

        $result = json_decode($result);
        // dd($result);
        // \Log::info('Result: ');
        // \Log::info('Result: ' . json_encode($result));
        if (isset($result->error)) {
            \Log::info('Error drop box: ');
            \Log::info(json_encode($result));
            $errorTag = $result->error->{'.tag'};
            if ($errorTag == "expired_access_token") {
                $result = $this->dropboxService->refresh_token_request();
                $newToken = $result['access_token'];
                $this->dropboxService->changeAccessToken($newToken);
                // dd($newToken);
                return 0;
            } else {
                return 0;
            }
            // dd($result->error[".tag"]);
        } else {
            // \Log::info('Result: ' . json_encode($result));
            return 1;
        }
        // if ($result) {
        //     \Log::info('File uploaded successfully!');
        //     return 1;
        // } else {
        //     \Log::info('File upload failed!');
        //     return 0;
        // }
        // dd($result);
    }

    /**
     * @return string
     */
    public function deleteFileByName($folder, $filename): string
    {
        if ($folder === null || $filename === null) {
            \Log::error('Folder or filename is null!');
            return ""; // hoặc giá trị phù hợp với trường hợp của bạn
        }

        $destinationPath = "/$folder/$filename";
        $result = $this->dropboxService->deleteFileByName($destinationPath);

        if ($result) {
            \Log::info('File deleted successfully!');
            return 1;
        } else {
            \Log::info('File delete failed!');
            return 0;
        }
    }

    /**
     * @return string
     */
    public function checkFileExist($folder, $filename): string
    {
        if ($folder === null || $filename === null) {
            \Log::error('Folder or filename is null!');
            return ""; // hoặc giá trị phù hợp với trường hợp của bạn
        }

        $destinationPath = "/$folder/$filename";
        $result = $this->dropboxService->checkFileExist($destinationPath);

        if ($result) {
            \Log::info('File exists!');
            return 1;
        } else {
            \Log::info('File does not exist!');
            return 0;
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // try {
        \Log::info('Sync dropbox all orderId: ' . $this->orderId . ' typeButton: ' . $this->typeButton);
        $checkSyncDesignOrder = 0;
        // foreach ($this->orders as $orderId) {
        $order = Order::find($this->orderId);
        \Log::info("order");
        \Log::info($order);
        if ($order->fulfill_status == 'cancelled') {
            return;
        }
        \Log::info("order");
        \Log::info($order);
        // dd($folderNameIdDesign);
        $checkSyncDesign = 0;
        $lineItems = $order->items;
        $total_item = 0;
        $total = 0;
        foreach ($lineItems as $countItem) {
            $total_item += 1 * $countItem->quantity;
            $orderMeta = $countItem->orderItemMetas->whereIn('meta_key',['front_design', 'back_design', 'sleeve_left_design', 'sleeve_right_design']);
            foreach ($orderMeta as $print_files) {
                $total += 1 * $countItem->quantity;
            }
        }

        if ($order->user->username == 'feline') {
            if ($order->fulfill_status == 'priority') {
                $folderNameDesign = 'seller/feline/priority_designs';
            } else if ($order->fulfill_status == 'oversize') {
                $folderNameDesign = 'seller/feline/oversize_designs';
            } else if ($order->fulfill_status == 'overprio') {
                $folderNameDesign = 'seller/feline/overprio_designs';
            } else if ($order->fulfill_status == 'fixed' || $order->fulfill_status == 'wrongsize') {
                $folderNameDesign = 'seller/feline/wrongsize_designs';
            } else if ($order->fulfill_status == 'reprint') {
                $folderNameDesign = 'seller/feline/reprint';
            } else {
                $folderNameDesign = 'seller/feline/designs';
            }
        } else {
            //sync label append
            if ($order->fulfill_status == 'priority') {
                $folderNameDesign = 'priority_designs';
            } else if ($order->fulfill_status == 'oversize') {
                $folderNameDesign = 'oversize_designs';
            } else if ($order->fulfill_status == 'overprio') {
                $folderNameDesign = 'overprio_designs';
            } else if ($order->fulfill_status == 'fixed' || $order->fulfill_status == 'wrongsize') {
                $folderNameDesign = 'wrongsize_designs';
            } else if ($order->fulfill_status == 'reprint') {
                $folderNameDesign = 'reprint';
            } else {
                if($total_item > 1){
                    $folderNameDesign = 'designs_multi';
                }else{
                    $folderNameDesign = 'designs_single';
                }
            }
        }
        // $folderNameDesign = 'phongtest';

        $orderItem = $order->items;
       
        \Log::info($orderItem);
        foreach ($orderItem as $item) {
            $orderItemMeta = $item->orderItemMetas;
            // \Log::info($orderItemMeta);
            // \Log::info('typeButton: '.$this->typeButton );
            $site = 0;
            foreach ($orderItemMeta as $itemMeta) {

                $designKeys = ['front_design', 'back_design', 'sleeve_left_design', 'sleeve_right_design'];
                if (in_array($itemMeta->meta_key, $designKeys)) {
                    $site++;
                }
            }
            $site_num = 1;
            foreach ($orderItemMeta as $itemMeta) {

                $designKeysQr = ['front_design_qr', 'back_design_qr', 'sleeve_left_design_qr', 'sleeve_right_design_qr'];

                //sync design

                    //sync design qr
                    if (in_array($itemMeta->meta_key, $designKeysQr)) {
                        $sitename = $site . '_' . $site_num;
                        if ($item->quantity >= 2) {
                            for ($i = 1; $i <= $item->quantity; $i++) {
                                $urlImage = $itemMeta->meta_value;

                                $type = str_replace('_design_qr', '', $itemMeta->meta_key);
                                $style = $item->product->style;
                                $size = $item->product->size;

                                $nameImage = "{$order->id}_{$item->id}_{$type}_{$size}_{$style}_{$i}_{$sitename}_item_{$total}.png";
                                \Log::info($item->id . ":sync qr design path: " . $nameImage);
                                // echo $nameImage."<br>";
                                \Log::info("name: " . $nameImage);

                                $checkFileDesign = $order->sync_design;
                                // \Log::info("checkFileDesign: " . $checkFileDesign);
                                if ($checkFileDesign) {
                                    $fileExist = $this->checkFileExist($folderNameDesign, $nameImage);
                                    if ($fileExist) {
                                        $delete = $this->deleteFileByName($folderNameDesign, $nameImage);
                                        if ($delete) {
                                            \Log::info($item->id . ' delete design success');
                                            $checkFile = $this->uploadFileFromUrl($folderNameDesign, $urlImage, $nameImage);
                                            if ($checkFile) {
                                                $checkSyncDesign = 1;
                                            }
                                        }
                                    } else {
                                        $checkFile = $this->uploadFileFromUrl($folderNameDesign, $urlImage, $nameImage);
                                        if ($checkFile) {
                                            $checkSyncDesign = 1;
                                        }
                                    }
                                } else {
                                    $fileExist = $this->checkFileExist($folderNameDesign, $nameImage);
                                    if ($fileExist) {
                                        $delete = $this->deleteFileByName($folderNameDesign, $nameImage);
                                        if ($delete) {
                                            \Log::info($item->id . ' delete design success');
                                            $checkFile = $this->uploadFileFromUrl($folderNameDesign, $urlImage, $nameImage);
                                            if ($checkFile) {
                                                $checkSyncDesign = 1;
                                            }
                                        }
                                    } else {
                                        $checkFile = $this->uploadFileFromUrl($folderNameDesign, $urlImage, $nameImage);
                                        if ($checkFile) {
                                            $checkSyncDesign = 1;
                                        }
                                    }
                                }
                            }
                            \Log::info($this->orderId . " Sync many");
                        } else {
                            $urlImage = $itemMeta->meta_value;
                            $type = str_replace('_design_qr', '', $itemMeta->meta_key);
                            $style = $item->product->style;
                            $size = $item->product->size;
                            $nameImage = "{$order->id}_{$item->id}_{$type}_{$size}_{$style}_{$sitename}_item_{$total}.png";
                            \Log::info($item->id . ":sync qr design path: " . $nameImage);

                            $checkFileDesign = $order->sync_design;
                            // \Log::info("checkFileDesign: " . $checkFileDesign);
                            if ($checkFileDesign) {
                                $fileExist = $this->checkFileExist($folderNameDesign, $nameImage);
                                if ($fileExist) {
                                    $delete = $this->deleteFileByName($folderNameDesign, $nameImage);
                                    if ($delete) {
                                        \Log::info($item->id . ' delete design success');
                                        $checkFile = $this->uploadFileFromUrl($folderNameDesign, $urlImage, $nameImage);
                                        if ($checkFile) {
                                            $checkSyncDesign = 1;
                                        }
                                    }
                                } else {
                                    $checkFile = $this->uploadFileFromUrl($folderNameDesign, $urlImage, $nameImage);
                                    if ($checkFile) {
                                        $checkSyncDesign = 1;
                                    }
                                }
                            } else {
                                $fileExist = $this->checkFileExist($folderNameDesign, $nameImage);
                                if ($fileExist) {
                                    $delete = $this->deleteFileByName($folderNameDesign, $nameImage);
                                    if ($delete) {
                                        \Log::info($item->id . ' delete design success');
                                        $checkFile = $this->uploadFileFromUrl($folderNameDesign, $urlImage, $nameImage);
                                        if ($checkFile) {
                                            $checkSyncDesign = 1;
                                        }
                                    }
                                } else {
                                    $checkFile = $this->uploadFileFromUrl($folderNameDesign, $urlImage, $nameImage);
                                    if ($checkFile) {
                                        $checkSyncDesign = 1;
                                    }
                                }
                            }
                            \Log::info($this->orderId . " Sync one");
                        }
                        if (is_null($item->sync_stock) || $item->sync_stock == 0) {
                            $item->sync_stock = 1;
                            $item->save();
                        }
                        $site_num++;
                        $itemMeta->overide_qr_design = 1;
                        $itemMeta->save();
                    }
            }
        }

        if ($checkSyncDesign == 1) {
            if ($order->fulfill_status == 'new_order') {
                // $order->fulfill_status = 'printed';
            }
            // $order->sync_design = 1;
            \Log::info('orderId: ' . $order->id . ' dropbox syncDesignStatus:' . $checkSyncDesign);
            TimeLine::create([
                'object' => 'order',
                'object_id' => $order->id,
                'owner_id' => Auth::user()->id ?? 1,
                'action' => 'sync design',
                'note' => Auth::user()->username ?? "Cron auto" . ' sync design to ' . $folderNameDesign . ', order ' . $order->id,
            ]);
            $reqsult = $order->save();
            \Log::info('save order: ' . $reqsult ? "True" : "False");
        }


        // }
        // } catch (\Exception $e) {
        //     \Log::info($this->orderId);
        //     \Log::info('orderId '. $this->orderId. ' syncDesignStatus '. $checkSyncDesignOrder. ' error ');
        //     \Log::info($e->getMessage());
        // }
    }
    public function getTotalOrder($order_id)
    {
        $url = 'https://f004.backblazeb2.com/file/pressifypod/data_json/' . $order_id . '.json';

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL request and get response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            throw new Exception('cURL error: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Decode JSON response
        $data = json_decode($response, true);
        $lineItems = $data['line_items'];
        $total_item = 0;
        $total = 0;
        foreach ($lineItems as $countItem) {
            $total_item += 1 * $countItem['quantity'];
            foreach ($countItem['print_files'] as $print_files) {
                $total += 1 * $countItem['quantity'];
            }
        }
        return ['total_item'=>$total,'item'=>$total_item];
    }
}
