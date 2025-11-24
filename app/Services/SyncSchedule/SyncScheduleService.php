<?php

namespace Vanguard\Services\SyncSchedule;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Vanguard\Jobs\deleteProductFlashdealJob;
use Vanguard\Models\FlashDeals;
use Vanguard\Models\Meta;
use Vanguard\Models\ProductTiktoks;
use Carbon\Carbon;

class SyncScheduleService
{
    protected $apiEndpoint = 'http://flashdeal.6sccb7llke-ewx3lnxrk4zq.p.temp-site.link/api';
    protected $batchSize = 1000;
  
    
    /**
     * Đồng bộ FlashDeals theo schedule
     * Mỗi lần lấy 1000 bản ghi và lưu lại vị trí cuối cùng
     */
    public function syncScheduleFlashDeals()
    {
        try {
            // Lấy thông tin lần đồng bộ trước
            $lastSyncMeta = Meta::where([
                'key' => 'post_flashdeal',
            ])->latest('id')->first();
            
            $lastId = 0;
            if ($lastSyncMeta) {
                $metaValue = json_decode($lastSyncMeta->value, true);
                $lastId = $metaValue['end_id'] ?? 0;
            }
            $thismonth = Carbon::now()->startOfMonth();
            // Query lấy 1000 bản ghi tiếp theo
            $query = FlashDeals::where('id', '>', $lastId)
                                ->where('created_at','>=',$thismonth)
                                ->orderBy('id', 'asc')
                                ->limit($this->batchSize);
            
            $flashDeals = $query->get();
        
            
            // Lấy ID đầu và ID cuối của batch hiện tại
            $startId = $flashDeals->first()->id;
            $endId = $flashDeals->last()->id;
            dd($startId, $endId);
            $flashDealsData = [];
            foreach ($flashDeals as $flashDeal) {
                $flashDealsData[] = $flashDeal->only([
                    'id', 'activity_id', 'store_id', 'promotion_name', 
                    'activity_type', 'product_level', 'status_fld', 
                    'begin_time', 'end_time', 'auto', 'renew', 
                    'status', 'message', 'create_new', 
                    'created_at', 'updated_at'
                ]);
            }
            
            // Chia thành các chunk nhỏ hơn (100 bản ghi) để gửi đi
            $chunks = array_chunk($flashDealsData, 100);
            
            foreach ($chunks as $chunk) {
                $response = Http::post($this->apiEndpoint . '/syncFlashDeals', [
                    'flashdeals' => $chunk
                ]);
                
                if (!$response->successful()) {
                    Log::channel('convert-flashdeal')->error('Đồng bộ chunk thất bại: ' . $response->body());
                    // Có thể thêm logic retry hoặc xử lý lỗi ở đây
                }
            }
            
            // Lưu lại thông tin lần đồng bộ hiện tại
            Meta::create([
                'user_id' => null,
                'store_id' => null,
                'key' => 'post_flashdeal',
                'value' => json_encode([
                    'start_id' => $startId,
                    'end_id' => $endId,
                    'count' => count($flashDealsData),
                    'sync_time' => now()->toDateTimeString()
                ])
            ]);
            
            Log::channel('convert-flashdeal')->info('Đồng bộ thành công ' . count($flashDealsData) . ' flashdeals từ ID ' . $startId . ' đến ' . $endId);
            return true;
            
        } catch (\Exception $e) {
            Log::channel('convert-flashdeal')->error('Lỗi đồng bộ FlashDeals schedule: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Đồng bộ ProductTiktoks theo schedule
     * Mỗi lần lấy 1000 bản ghi và lưu lại vị trí cuối cùng
     */
    public function syncScheduleProductTiktoks()
    {
        try {
            // Lấy thông tin lần đồng bộ trước
            $lastSyncMeta = Meta::where([
                'key' => 'post_producttiktok',
            ])->latest('id')->first();
            
            $lastId = 0;
            if ($lastSyncMeta) {
                $metaValue = json_decode($lastSyncMeta->value, true);
                $lastId = $metaValue['end_id'] ?? 0;
            }
            
            // Query lấy 1000 bản ghi tiếp theo
            $query = ProductTiktoks::where('id', '>', $lastId)
                                  ->orderBy('id', 'asc')
                                  ->limit($this->batchSize);
            
            
            $products = $query->get();
            
            if ($products->isEmpty()) {
                Log::info('Không có ProductTiktoks mới để đồng bộ');
                return true;
            }
            
            // Lấy ID đầu và ID cuối của batch hiện tại
            $startId = $products->first()->id;
            $endId = $products->last()->id;
            
            $productsData = [];
            foreach ($products as $product) {
                $productsData[] = $product->only([
                    'id', 'store_id', 'remote_id', 'created_at', 
                    'updated_at', 'title', 'status', 'skus', 
                    'is_flashdeal', 'discount'
                ]);
            }
            
            // Chia thành các chunk nhỏ hơn (100 bản ghi) để gửi đi
            $chunks = array_chunk($productsData, 100);
            
            foreach ($chunks as $chunk) {
                $response = Http::post($this->apiEndpoint . '/syncProductFlashDeals', [
                    'products' => $chunk
                ]);
                
                if (!$response->successful()) {
                    Log::error('Đồng bộ chunk thất bại: ' . $response->body());
                    // Có thể thêm logic retry hoặc xử lý lỗi ở đây
                }
            }
            
            // Lưu lại thông tin lần đồng bộ hiện tại
            Meta::create([
                'user_id' => null,
                'store_id' => null,
                'key' => 'post_producttiktok',
                'value' => json_encode([
                    'start_id' => $startId,
                    'end_id' => $endId,
                    'count' => count($productsData),
                    'sync_time' => now()->toDateTimeString()
                ])
            ]);
            
            Log::info('Đồng bộ thành công ' . count($productsData) . ' products từ ID ' . $startId . ' đến ' . $endId);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Lỗi đồng bộ ProductTiktoks schedule: ' . $e->getMessage());
            return false;
        }
    }
    public function deleleProductFlashdeal(){
        try {
            $lastSyncMeta = Meta::where([
                'key' => 'deleteProductFlashdealJob',
            ])->latest('id')->first();
            
            $lastId = 0;
            if ($lastSyncMeta) {
                $metaValue = json_decode($lastSyncMeta->value, true);
                $lastId = $metaValue['end_id'] ?? 0;
            }


            $flashDeals = FlashDeals::where('id', '>', $lastId)
                ->limit(60)
                ->get();

            foreach($flashDeals as $flashDeal){
                $flashdeal_id = $flashDeal->activity_id;
                $dateCreate = $flashDeal->created_at;
                deleteProductFlashdealJob::dispatch($flashdeal_id, $dateCreate)->delay(1)->onQueue('deleteProductFlashdealJob');
            }

            $startId = $flashDeals->first()->id;
            $endId = $flashDeals->last()->id;
            Meta::create([
                'user_id' => null,
                'store_id' => null,
                'key' => 'deleteProductFlashdealJob',
                'value' => json_encode([
                    'start_id' => $startId,
                    'end_id' => $endId,
                    'count' => count($flashDeals),
                    'sync_time' => now()->toDateTimeString()
                ])
            ]);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}