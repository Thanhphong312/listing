<?php

namespace Vanguard\Http\Controllers\Web;

use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\Order\Order;
use Vanguard\Models\Order\OrderItem;
use Vanguard\Product;
use Vanguard\ProductVariants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages;
use Vanguard\Models\TimeLine;
use Vanguard\Models\Tracking;
use Vanguard\Models\Transaction;
use Vanguard\User;
use Vanguard\Models\Store\Store;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;

class GetOrderTiktokController extends Controller
{
    /**
     * Displays the application dashboard.
     *
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $store = Store::find(218);

        // try{
        // //     $store->syncfld = 1;
        $storetiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
            // $storetiktok->useVersion(202406);
        $createTimeGe = strtotime('2025-09-01 00:00:00');

            // Tạo timestamp kết thúc đến 18/09 23:59:59
        $createTimeLt = strtotime('2025-09-17 23:59:59');
        $pageToken = "";
        if(isset($request->page_token)&&!empty($request->page_token)){
            $pageToken = $request->page_token;
        }
        $query = [
            "order_status"=>"AWAITING_COLLECTION",
            "page_size" => 100,
            "page_token" => $pageToken,
            "create_time_ge" => $createTimeGe,
            "create_time_lt" => $createTimeLt,
        ];
        $getOrders = $storetiktok->Order->getOrderList($query);
        $next_page_token = $getOrders['next_page_token'];
        $orders = $getOrders['orders'];
        $this->checkOrder($orders);
        $total_count = $getOrders['total_count'];
        return view('getorders.index',compact('orders','pageToken','next_page_token','total_count'));
    }
    public function checkOrder($orders)
    {
        $filePath       = '/home/runcloud/webapps/global_new/public/csv/order_miss_fteeck.json';
        $filePathfteeck = '/home/runcloud/webapps/global_new/public/csv/order_miss.json';

        // Đọc danh sách ID từ order_miss.json
        $existingIds = [];
        if (file_exists($filePathfteeck)) {
            $jsonContent = file_get_contents($filePathfteeck);
            $existingIds = json_decode($jsonContent, true) ?? [];
        }

        // Đọc file order_miss_fteeck.json nếu đã có
        $missedFileIds = [];
        if (file_exists($filePath)) {
            $jsonContent = file_get_contents($filePath);
            $missedFileIds = json_decode($jsonContent, true) ?? [];
        }

        foreach ($orders as $order) {
            $orderId = $order['id'];

            // Nếu orderId không có trong file gốc và cũng chưa có trong file missed
            if (!in_array($orderId, $existingIds) && !in_array($orderId, $missedFileIds)) {
                $missedFileIds[] = $orderId;
            }
        }

        // Ghi lại file, vẫn là JSON array, nhưng có thêm các ID mới
        file_put_contents($filePath, json_encode($missedFileIds, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }





}
