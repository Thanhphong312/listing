<?php

namespace Vanguard\Http\Controllers\Web;

use Akaunting\Setting\Support\Arr;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Vanguard\Console\Commands\UpdateSettingOrder;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Jobs\CloneDataJsonJob;
use Vanguard\Jobs\DeleteDropboxMeta;
use Vanguard\Jobs\DesignJob;
use Vanguard\Jobs\SyncDropBox;
use Vanguard\Jobs\SyncOverdueLabel;
use Vanguard\Jobs\UpdateProductJsonJobs;
use Vanguard\Jobs\UpdateSettingJobs1;
use Vanguard\Jobs\UpdateSettingJobs2;
use Vanguard\Models\MoneyExchange;
use Vanguard\Notifications\TelegramMessage;
use Vanguard\Product;
use Vanguard\Models\Order\Order;
use setasign\Fpdi\Fpdi;
use Vanguard\Jobs\LabelJob;
use Vanguard\Jobs\QrDesignJobs;
use Vanguard\Models\Order\OrderItem;
use Vanguard\Models\Order\OrderItemMeta;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\PdfParser\PdfParser;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfReader\PdfReader;
// use setasign\Fpdi\Tfpdf\Fpdi;
use Spatie\PdfToImage\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Imagick;
use Vanguard\Models\TimeLine;
use Vanguard\Models\Tracking;
use Vanguard\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Vanguard\Models\Supports;
use Vanguard\ProductVariants;
use Vanguard\Services\GoogleDriver\GoogleDriverServices;
use Vanguard\Jobs\PayOrderJob;
use Vanguard\Models\Transaction;
use Shippo;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\text;
use Telegram\Bot\Laravel\Facades\Telegram;
use Vanguard\Jobs\SyncDesignDriver;
use Illuminate\Support\Facades\Cookie;
use Vanguard\Jobs\syncLabel as syncLabelJob;
use Setting;
use Vanguard\Console\Commands\CloneDataJson;
use Vanguard\Jobs\syncDropboxMeta;
use Vanguard\Jobs\UpdateSettingJobs;
use Vanguard\Services\Blaze\BlazeService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Vanguard\Jobs\changePrintedJob;
use Vanguard\Jobs\MoveDropBoxMeta;
use Vanguard\Jobs\syncDropboxMetaNew;

class TestController extends Controller
{
    public function pushTracking()
    {
        $ordersWithoutTracking = Order::where('fulfill_status', 'printed')
            ->leftJoin('tracking', 'orders.id', '=', 'tracking.order_id')
            ->where('orders.created_at', '<=', Carbon::now()->subDays(2)->subHours(12))
            ->whereNull('tracking.order_id')
            ->whereNotNull('orders.tracking_id')
            ->select('orders.id', 'orders.tracking_id as tracking_order', 'tracking.tracking_id as tracking')
            ->limit(40)
            ->get();
        dd($ordersWithoutTracking);
        $message = [];
        foreach ($ordersWithoutTracking as $order) {
            $tracking = new Tracking();
            $tracking->order_id = $order->id;
            $tracking->tracking_id = $order->tracking_order;
            $tracking->status = 'Pending';
            $tracking->save();
            array_push($message, $order->id);
        }
        return response()->json($message);

    }
    public function trackingInfoReceive()
    {
        $trackings = Tracking::with('order')
            ->whereHas('order', function ($query) {
                $query->where('created_at', '<=', Carbon::now()->subDays(2))
                    ->whereNotIn('fulfill_status', ['cancelled', 'test_order']);
            })
            ->where(function ($query) {
                $query->where('status', 'Pending')
                    ->orWhere('status', 'Info received');
                $query->whereNull('push_17track');
            })
            ->limit(40)
            ->get();
        // dd($trackings);
        $message = [];
        foreach ($trackings as $tracking) {
            $responsepost = $this->track17Post($tracking->tracking_id, "note", $tracking->order->ref_id);
            $rs = json_decode($responsepost, true);
            \Log::info("post cron 17track");
            \Log::info($rs);
            // \Log::info($rs['data']['rejected'][0]['error']['code']==-18019901);
            if (isset($rs['data']['accepted']) && sizeof($rs['data']['accepted']) == 0) {
                $tracking->push_17track = 1;
                $tracking->save();
            }
            if (isset($rs['data']['rejected'][0]['error']['code']) && $rs['data']['rejected'][0]['error']['code'] == -18019901) {
                $tracking->push_17track = 1;
                $tracking->save();
            }

            array_push($message, $tracking->tracking_id);
        }
        return response()->json($message);
    }
    public function getSskInfoTracking()
    {
        $tracking = Tracking::where('tracking_id', 'like', "9400109106029637064959")->first();

        if ($tracking->ssk == null) {
            $rs = $this->getTrackingInfo($tracking->tracking_id);
            // dd($rs);
            $data = json_decode($rs, true);
            // dd($data);
            if (isset($data['data']['accepted'][0]['track_info']['tracking']['providers'][0]['events'])) {
                $event = $data['data']['accepted'][0]['track_info']['tracking']['providers'][0]['events'];
                // dd($event);
                foreach ($event as $item) {
                    if (isset($item['description'])) {
                        $des = $item['description'];
                        if (strpos($des, 'SSK') !== false) {
                            // $tracking->ssk = 1;
                            // $tracking->save();
                            echo "ok";
                        }
                    }
                }
            }
        }
    }
    public function countTrackingNotActive()
    {
        $trackings = Tracking::with('order')
            ->whereHas('order', function ($query) {
                $query->where('created_at', '<=', Carbon::now()->subDays(2))
                    ->whereNotIn('fulfill_status', ['cancelled', 'test_order']);
            })
            ->where(function ($query) {
                $query->where('status', 'Pending')
                    ->orWhere('status', 'Info received');
                $query->whereNull('push_17track');
            })
            // ->limit(40)
            ->get();
        dd($trackings);
    }
    public function countTrackingOrderPrintedOver2h()
    {
        $ordersWithoutTracking = Order::where('fulfill_status', 'printed')
            ->leftJoin('tracking', 'orders.id', '=', 'tracking.order_id')
            ->where('orders.created_at', '<=', Carbon::now()->subDays(2))
            ->whereNull('tracking.order_id')
            ->whereNotNull('orders.tracking_id')
            ->select('orders.id', 'orders.tracking_id as tracking_order', 'tracking.tracking_id as tracking')
            // ->limit(20)
            ->get();
        dd($ordersWithoutTracking);
    }
    public $id;
    public $type;
    public function getTotalItemQc($orders)
    {
        $url = 'https://jobs.pressify.us/api/reportQC';

        // Initialize cURL
        $ch = curl_init();

        // Convert $orders to JSON
        $postData = json_encode($orders);
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($postData)
        ));

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
        return $data;
    }
    public function test(Request $request)
    {
        $repostflashdeals = ProductFlashdeals::with('flashdeal')
            ->where(function($query) {
                $query->where('success', 0);
            })
            ->whereHas('flashdeal', function($query) {
                $query->whereIn('status_fld', ['ONGOING','NOT_START'])
                    ->where('renew', 0)
                    ->where('auto', 1);
            })
            ->get();
        foreach($repostflashdeals as $renewflashdeal){
            
            $store_id = $renewflashdeal->flashdeal->store_id;
            $activity_id = $renewflashdeal->flashdeal_id;
            $remote_id = $renewflashdeal->product_id; 
            $discount = $renewflashdeal->discount;
            $quantity_limit = $renewflashdeal->quantity_limit;
            $quantity_per_user = $renewflashdeal->quantity_per_user;
            addProductFlashdealjob::dispatch($store_id, $activity_id, (string)$remote_id, $discount, $quantity_limit, $quantity_per_user)->onQueue('add-product-to-flashdeals');
        }
    //  phpinfo();   

        // dd($header);
        // $orders = Order::select('id','created_at','fulfill_status','payment_status','payment_status')
        //     ->where('created_at','<=', Carbon::now()->subHours(1))
        //     ->where('fulfill_status','new_order')
        //     ->where('payment_status','paid')
        //     ->limit(600)
        //     ->get();
        // foreach($orders as $order){
        //     changePrintedJob::dispatch($order->id)->delay(now()->addSeconds(5))->onQueue('change_printed_order');
        // }

        // $orders = Order::join('users', 'users.id', '=', 'orders.seller_id')
        //     ->select('orders.id')
        //     ->where('orders.total_cost', '>', 0)
        //     ->whereColumn('users.wallet_balance', '>=', 'orders.total_cost')
        //     ->where(function($query) {
        //         $query->where('orders.payment_status', 'pending')
        //               ->orWhere('orders.priority', 1);
        //     })
        //     ->where('orders.fulfill_status', '!=', 'cancelled')
        //     ->where('orders.fulfill_status', '!=', 'test_order')
        //     ->where('orders.fulfill_status', '!=', 'fulfill_partner')
        //     ->where('orders.created_at', '<', now()->subHours(1))
        //     ->where('orders.created_at', '>=', now()->subDay()->startOfDay())
        //     ->orderBy('orders.created_at', 'ASC')
        //     ->limit(400)->get();
        // \Log::info('pay orders: '. $orders->count());
        // $message = [];
        // foreach ($orders as $order) {
        //     PayOrderJob::dispatch($order->id)->delay(now()->addSeconds(5))->onQueue('paid_order');
        //     $message[] = $order->id;
        // }
        // return response()->json($message);
        // $listUserQc = [];
        // $userQcs = User::where('role_id', 5)->get();
        // foreach ($userQcs as $userQc) {
        //     $timlines = TimeLine::select('owner_id', 'action', 'created_at', 'object_id')
        //         ->where('owner_id', $userQc->id)
        //         ->where('action', 'complete order')
        //         ->whereBetween('timeline.created_at', [Carbon::now()->subDay()->startOfDay(), Carbon::now()->subDay()->endOfDay()])
        //         ->pluck('object_id')->toArray();
                
        //     \Log::info('report qc');
        //     $orderString = implode(', ', $timlines);

        //     \Log::info($orderString);
        //     if (count($timlines) > 0) {
        //         $listUserQc[] = [
        //             "user_id" => $userQc->id,
        //             "total" => count($timlines),
        //             // "total" => $this->getTotalItemQc($timlines),
        //         ];
        //     }
        // }
        // // return $listUserQc[6]['total'];
        // dd($listUserQc);
        // dd($this->getTotalItemQc($listUserQc[6]['total']));
        // $completetodays = DB::table('timeline')
        //     ->join('users', 'timeline.owner_id', '=', 'users.id')
        //     ->select('users.username', DB::raw('count(timeline.id) as total'))
        //     ->where('users.role_id', 5)
        //     ->where('timeline.action', 'complete order')
        // ->whereBetween('timeline.created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
        //     ->groupBy('users.username')
        //     ->get();

        // $totalOrder = $this->getTotalOrder(362588);
        // dd($totalOrder);
        // $items = [
        //     'so5731_576689015517778222', 'so5657_576687677187068382', 'so6089_576687143713804581', 'so5731_576687831904719390', 
        //     'so7968_576687544534274834', 'so6022_576687206237704485', 'so6022_576687252104319857', 'so5796_576687291543229357', 
        //     'so8241_576687076188590086', 'so7856_576686004642878426', 'so8136_576686511551713488', 'so6046_576686101040238809', 
        //     'so6223_576686176096457224', 'so8436_576685741379719866', 'so6366_576685589633012084', 'so5344_576685897638580709', 
        //     'so5488_576685031635325510', 'so5662_576683460046131631', 'so5796_576682873993138291', 'so8417_576681526200144621', 
        //     'so5344_576682158053691414', 'so6566_576679146903736902a', 'so6023_576681760904680019', 'so6023_576681357403001826', 
        //     'so5731_576680725794034615', 'so5731_576680725515178131', 'so5731_576680724846645415', 'so5731_576680726845821947', 
        //     'so5731_576680726849818953', 'so5731_576680726993866978', 'so5731_576680724830392863', 'so5731_576680726467089082', 
        //     'so5427_576680600980132440', 'so8439_576680288024367263', 'so6698_576680263918784735', 'so5740_576679520239915814a', 
        //     'so5606_576680055493006151', 'so5041_576679888764571948', 'so6099_576679907191787941', 'so7631_576679687246221809', 
        //     'so5545_576679312028373199', 'so5740_576679520239915814', 'so6100_576678927624081434', 'so5731_576678846781100390b', 
        //     'so5927_576678901074006488', 'so8215_576678843405406338', 'so5731_576678846781100390', 'so5490_576678874074616625', 
        //     'so5956_576678764978475569', 'so6023_576678423855206751', 'so6023_576677484967989499', 'so5684_576677307714802428', 
        //     'so5684_576677440948900099', 'so5684_576677312771298162', 'so5684_576677361914450742', 'so5684_576677365687226605', 
        //     'so6023_576677513076511086', 'so6023_576677014905787048', 'so6023_576677025702253521', 'so6107_576677342368207165', 
        //     'so5949_576677065294058036', 'so5570_576677222332141915', 'so5344_576676707751399855', 'so6046_576675420635632356', 
        //     'so5220_576675864808559037', 'so5400_576675835653100471', 'so5545_576675682727792721', 'so6100_576675578653676322', 
        //     'so6046_576674421629358552', 'so5344_576674741313114203', 'so5220_576674464696406463', 'so6046_576674289096627099', 
        //     'so5344_576673762457587967', 'so4949_576673669543924605', 'so6461_576673280447910892', 'so5965_576673144552461146', 
        //     'so4924_576673184507203784', 'so5344_576671788856414395', 'so5927_576671511900820369', 'so5273_576670846510403703', 
        //     'so6161_576670958480036387', 'so5694_576669746356654582', 'so5344_576669748627870085', 'so5220_576669199514964412', 
        //     'so5842_576659421704590197A', 'so5912_576668599553135470', 'so6437_576667102154953488', 'so6113_576666893723472540', 
        //     'so5400_576667066993840365', 'so5220_576666696483377985'
        // ];

        // // Count unique items
        // $uniqueItemsCount = count(array_unique($items));

        // echo "Number of unique items: $uniqueItemsCount";
        // echo count([so5731_576689015517778222,so5657_576687677187068382,so6089_576687143713804581,so5731_576687831904719390,so7968_576687544534274834,so6022_576687206237704485,so6022_576687252104319857,so5796_576687291543229357,so8241_576687076188590086,so7856_576686004642878426,so8136_576686511551713488,so6046_576686101040238809,so6223_576686176096457224,so8436_576685741379719866,so6366_576685589633012084,so5344_576685897638580709,so5488_576685031635325510,so5662_576683460046131631,so5796_576682873993138291,so8417_576681526200144621,so5344_576682158053691414,so6566_576679146903736902a,so6023_576681760904680019,so6023_576681357403001826,so5731_576680725794034615,so5731_576680725515178131,so5731_576680724846645415,so5731_576680726845821947,so5731_576680726849818953,so5731_576680726993866978,so5731_576680724830392863,so5731_576680726467089082,so5427_576680600980132440,so8439_576680288024367263,so6698_576680263918784735,so5740_576679520239915814a,so5606_576680055493006151,so5041_576679888764571948,so6099_576679907191787941,so7631_576679687246221809,so5545_576679312028373199,so5740_576679520239915814,so6100_576678927624081434,so5731_576678846781100390b,so5927_576678901074006488,so8215_576678843405406338,so5731_576678846781100390,so5490_576678874074616625,so5956_576678764978475569,so6023_576678423855206751,so6023_576677484967989499,so5684_576677307714802428,so5684_576677440948900099,so5684_576677312771298162,so5684_576677361914450742,so5684_576677365687226605,so6023_576677513076511086,so6023_576677014905787048,so6023_576677025702253521,so6107_576677342368207165,so5949_576677065294058036,so5570_576677222332141915,so5344_576676707751399855,so6046_576675420635632356,so5220_576675864808559037,so5400_576675835653100471,so5545_576675682727792721,so6100_576675578653676322,so6046_576674421629358552,so5344_576674741313114203,so5220_576674464696406463,so6046_576674289096627099,so5344_576673762457587967,so4949_576673669543924605,so6461_576673280447910892,so5965_576673144552461146,so4924_576673184507203784,so5344_576671788856414395,so5927_576671511900820369,so5273_576670846510403703,so6161_576670958480036387,so5694_576669746356654582,so5344_576669748627870085,so5220_576669199514964412,so5842_576659421704590197A,so5912_576668599553135470,so6437_576667102154953488,so6113_576666893723472540,so5400_576667066993840365,so5220_576666696483377985])
        // $numbers = [2, 3, 2, 2, 2, 2, 3, 3, 3, 4, 4, 2, 2, 2, 6, 4, 6, 3, 2, 2, 2, 2, 3, 2, 4, 3, 4, 4, 3];
// $sum = array_sum($numbers);
// echo "Tổng các số là: " . $sum;
        
        // \Log::info('Start update setting jobs 1');

        // // Define the start and end times for today and yesterday
        // $todayStart = now()->startOfDay();
        // $todayEnd = now()->endOfDay();
        // $yesterdayStart = now()->subDay()->startOfDay();
        // $yesterdayEnd = now()->subDay()->endOfDay();

        // // Define the common query conditions
        // $recentOrderItems = OrderItem::select('order_id', 'created_at') // Thêm các trường cần thiết vào đây
        // ->with('order')
        // ->where('created_at', '>=', now()->subDays(3)->startOfDay());

        // // Orders created today with new_order status
        // $orderNews = Order::where('created_at', '>=', now()->subDays(1)->startOfDay())
        //     ->where('fulfill_status', 'new_order')
        //     ->count();

        // // Orders in production (printed or pressed)
        // $orderProduction = $recentOrderItems->clone()
        //     ->whereHas('order', function ($query) {
        //         $query->whereIn('fulfill_status', ['printed', 'pressed']);
        //     })
        //     ->count();

        // // // Orders shipped today (items)
        // $ordershippedtodays = $recentOrderItems->clone()
        //     ->whereHas('order', function ($query) use ($todayStart, $todayEnd) {
        //         $query->where('fulfill_status', 'shipped')
        //             ->whereBetween('updated_at', [$todayStart, $todayEnd]);
        //     })
        //     ->count();

        // // Orders shipped yesterday (items)
        // $ordershippedyesterdays = $recentOrderItems->clone()
        //     ->whereHas('order', function ($query) use ($yesterdayStart, $yesterdayEnd) {
        //         $query->where('fulfill_status', 'shipped')
        //             ->whereBetween('updated_at', [$yesterdayStart, $yesterdayEnd]);
        //     })
        //     ->count();

        // \Log::info('orderNews: ' . $orderNews);
        // \Log::info('orderProduction: ' . $orderProduction);
        // \Log::info('ordershippedtodays: ' . $ordershippedtodays);
        // \Log::info('ordershippedyesterdays: ' . $ordershippedyesterdays);

        // // dd($orderNews, $orderProduction, $ordershippedtodays, $ordershippedyesterdays);
        // // // // Set the values in settings
        // Setting::set('new_order', $orderNews);
        // Setting::set('production', $orderProduction);
        // Setting::set('order_shipped_today', $ordershippedtodays);
        // Setting::set('order_shipped_yesterday', $ordershippedyesterdays);
        // Setting::save();

        // \Log::info('End update setting jobs 1');
        // $orders = Order::join('users', 'users.id', '=', 'orders.seller_id')
        //     ->select('orders.id')
        //     ->where('orders.total_cost', '>', 0)
        //     ->whereColumn('users.wallet_balance', '>=', 'orders.total_cost')
        //     ->where(function($query) {
        //         $query->where('orders.payment_status', 'pending')
        //               ->orWhere('orders.priority', 1);
        //     })
        //     ->where('orders.fulfill_status', '!=', 'cancelled')
        //     ->where('orders.fulfill_status', '!=', 'test_order')
        //     ->where('orders.fulfill_status', '!=', 'fulfill_partner')
        //     ->where('orders.created_at', '<', now()->subHours(1))
        //     ->where('orders.created_at', '>=', now()->subDay()->startOfDay())
        //     ->orderBy('orders.created_at', 'ASC')
        //     ->limit(520)->get();
        // \Log::info('orders: '. $orders->count());
        // $message = [];
        // foreach ($orders as $order) {
        //     array_push($message, $order->id);
        //     PayOrderJob::dispatch($order->id)->delay(now()->addSeconds(2));;
        // }
        // return response()->json($message);
        // DeleteDropboxMeta::dispatch(327982);

        ///pay order
        // $orders = Order::join('users', 'users.id', '=', 'orders.seller_id')
        // ->select('orders.id')
        // ->whereColumn('users.wallet_balance', '>=', 'orders.total_cost')
        // ->where(function($query) {
        //     $query->where('orders.payment_status', 'pending')
        //           ->orWhere('orders.priority', 1);
        // })
        // ->where('orders.fulfill_status', '!=', 'cancelled')
        // ->where('orders.fulfill_status', '!=', 'test_order')
        // ->where('orders.fulfill_status', '!=', 'fulfill_partner')
        // // ->where('orders.created_at', '<', now()->subHours(1))
        // ->where('orders.created_at', '>=', now()->subDay()->startOfDay())
        // ->orderBy('orders.created_at', 'ASC')
        // ->limit(1500)->get();
        // \Log::info('orders: '. $orders->count());
        // $message = [];
        // foreach ($orders as $order) {

        //     PayOrderJob::dispatch($order->id);
        //     $message[] = $order->id;
        // }
        // return response()->json($message);

        // sync dropbox
        // $order_item_metas = DB::table('order_item_metas')
        //     ->where('order_item_metas.created_at','>=',Carbon::now()->subDays(1)->toDateTimeString())
        //     ->select('order_item_metas.id as order_item_meta_id', 'orders.id as order_id')
        //     ->join('order_items', 'order_item_metas.order_item_id', '=', 'order_items.id')
        //     ->join('orders', 'order_items.order_id', '=', 'orders.id')
        //     ->where('orders.payment_status', '=', 'paid')
        //     ->whereNotIn('orders.fulfill_status', ['cancelled', 'test_order','fulfill_partner','shipped','onhold','wrongsize'])
        //     ->whereIn('meta_key', ['front_design_qr', 'back_design_qr', 'sleeve_left_design_qr', 'sleeve_right_design_qr'])
        //     ->where('append_qr_design', 1)
        //     ->where('overide_qr_design', 0)
        //     ->orderBy('orders.id', 'asc')
        //     ->limit(200)
        //     ->get();
        // $message = [];

        // foreach ($order_item_metas as $order_item_meta) {
        //     syncDropboxMeta::dispatch($order_item_meta->order_item_meta_id);
        //     $message[] = $order_item_meta->order_id;
        // }
        // \Log::info('Sync dropbox meta: ', $message);
        // return response()->json($message);

        // \Log::info('Start update setting jobs 1');

        // // Define the start and end times for today and yesterday
        // $todayStart = now()->startOfDay();
        // $todayEnd = now()->endOfDay();
        // $yesterdayStart = now()->subDay()->startOfDay();
        // $yesterdayEnd = now()->subDay()->endOfDay();

        // // Define the common query conditions
        // $recentOrderItems = OrderItem::with('order')
        //     ->where('created_at', '>=', now()->subDays(3)->startOfDay());

        // // Orders created today with new_order status
        // $orderNews = Order::where('created_at', '>=', now()->subDays(1)->startOfDay())
        //     ->where('fulfill_status', 'new_order')
        //     ->count();

        // // Orders in production (printed or pressed)
        // $orderProduction = $recentOrderItems->clone()
        //     ->whereHas('order', function ($query) {
        //         $query->whereIn('fulfill_status', ['printed', 'pressed']);
        //     })
        //     ->count();

        // // // Orders shipped today (items)
        // $ordershippedtodays = $recentOrderItems->clone()
        //     ->whereHas('order', function ($query) use ($todayStart, $todayEnd) {
        //         $query->where('fulfill_status', 'shipped')
        //             ->whereBetween('updated_at', [$todayStart, $todayEnd]);
        //     })
        //     ->count();

        // // Orders shipped yesterday (items)
        // $ordershippedyesterdays = $recentOrderItems->clone()
        //     ->whereHas('order', function ($query) use ($yesterdayStart, $yesterdayEnd) {
        //         $query->where('fulfill_status', 'shipped')
        //             ->whereBetween('updated_at', [$yesterdayStart, $yesterdayEnd]);
        //     })
        //     ->count();

        // \Log::info('orderNews: ' . $orderNews);
        // \Log::info('orderProduction: ' . $orderProduction);
        // \Log::info('ordershippedtodays: ' . $ordershippedtodays);
        // \Log::info('ordershippedyesterdays: ' . $ordershippedyesterdays);

        // // dd($orderNews, $orderProduction, $ordershippedtodays, $ordershippedyesterdays);
        // // // Set the values in settings
        // Setting::set('new_order', $orderNews);
        // Setting::set('production', $orderProduction);
        // Setting::set('order_shipped_today', $ordershippedtodays);
        // Setting::set('order_shipped_yesterday', $ordershippedyesterdays);
        // Setting::save();

        // \Log::info('End update setting jobs 1');

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
        return ['total_item' => $total, 'item' => $total_item];
    }
    function isUrlContentReadable($url)
    {
        // Use file_get_contents to fetch the content from the URL
        // Suppress error messages with @ to prevent PHP warnings
        $content = @file_get_contents($url);

        // Check if content was successfully fetched
        return $content !== false;
    }
    function track17Post($number, $tag, $order_id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.17track.net/track/v2/register",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "[\r\n    {\r\n        \"number\": \"$number\",\r\n        \"param\": \"\",\r                \"order_no\": \"$order_id\",\r\n        \"carrier\": ,\r\n        \"final_carrier\": ,\r\n        \"auto_detection\": true,\r\n        \"tag\": \"$tag\"\r\n    }]",
            CURLOPT_HTTPHEADER => [
                "17token:" . env('TRACK17'),
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 0;
        } else {
            return $response;
        }
    }
    public function getTrackingInfo($tracking_id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.17track.net/track/v2.2/gettrackinfo",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "[\r
            {\r
              \"number\": \"$tracking_id\",\r
            }\r
          ]",
            CURLOPT_HTTPHEADER => [
                "17token:" . env('TRACK17'),
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 0;
        } else {
            return $response;
        }
    }

    public function Webhook17track(Request $request)
    {
        \Log::info('17track');
        \Log::info($request->all());
        $data = $request->all();

        $tracking = Tracking::where('tracking_id', 'like', $data['data']['number'])->first();
        $rs = $tracking->update([
            "status" => $data['data']['track_info']['latest_status']['status'],
            "service" => $data['data']['track_info']['tracking']['providers'][0]['provider']['name'],
            "total_day" => $data['data']['track_info']['time_metrics']['days_after_order'],
            "updated_at" => Carbon::now()->toDateTimeString(),
        ]);
        if ($rs) {
            \Log::info('success');
        }
        \Log::info("fail");
    }
    public function getAvgProductTime($startDate, $endDate)
    {
        // $timeLines = TimeLine::whereIn('action', ['complete order', 'create order'])
        //     ->where('created_at', '>=', $startDate->toDateTimeString())
        //     ->where('created_at', '<=', $endDate->toDateTimeString())
        //     ->orderBy('object_id')
        //     // ->limit(100)
        //     ->get();
        // // dd($timeLines);
        // $differences = [];
        // $completedOrders = [];

        // foreach ($timeLines as $timeLine) {
        //     if ($timeLine->action == 'complete order' && isset($completedOrders[$timeLine->object_id]) && $completedOrders[$timeLine->object_id]['status'] == 0) {
        //         // Calculate difference for completed orders
        //         $difference = $timeLine->created_at->diffInHours($completedOrders[$timeLine->object_id]['value']);
        //         $differences[$timeLine->object_id] = $difference;
        //         $completedOrders[$timeLine->object_id]['status'] = 1;
        //         // echo $timeLine->object_id . ' - ' . $difference . '<br>';
        //     }
        //     if ($timeLine->action == 'create order') {
        //         $completedOrders[$timeLine->object_id]['value'] = $timeLine->created_at;
        //         $completedOrders[$timeLine->object_id]['status'] = 0;
        //     }
        // }

        // if (empty($differences)) {
        //     return 0;
        // }
        // // dd($differences);
        // $avgProductionTime = (array_sum($differences) / count($differences)) / 24;
        // return round($avgProductionTime, 3);
    }
    private $orderId;
    private $typeButton;
    public function test1(Request $request)
    {
        // $supports = Supports::where('order_id', '182950')->get();
        // dd($supports);
        // foreach ($supports as $key => $support) {
        //     //bỏ id đầu tiên
        //     if ($key == 0) {
        //         continue;
        //     }
        //     $support->delete();
        // }
        // $order = Order::where('fulfill_status', 'fulfill_partner')->first();
        // $order->fulfill_status = 'test_order';
        // $order->save();
        // dd($order);
        // $support = Supports::where('status', 'New')->get();
        // foreach ($support as $item) {
        //     $item->status = 'Solved';
        //     $item->save();
        // }   
        // dd($support);
        // $order->fulfill_status = 'shipped';
        // $order->save();
        // $todayStart = now()->startOfDay();
        // $todayEnd = now()->endOfDay();
        // dd($todayStart, $todayEnd);
        // $yesterdayStart = now()->subDay()->startOfDay();
        // $yesterdayEnd = now()->subDay()->endOfDay();

        // // Define the common query conditions
        // $recentOrderItems = OrderItem::with('order')
        //     ->where('created_at', '>=', now()->subDays(3)->startOfDay());

        // // // Orders created today with new_order status
        // // $orderNews = Order::where('created_at', '>=', now()->subDays(1)->startOfDay())
        // //     ->where('fulfill_status', 'new_order')
        // //     ->count();

        // // // Orders in production (printed or pressed)
        // // $orderProduction = $recentOrderItems->clone()
        // //     ->whereHas('order', function ($query) {
        // //         $query->whereIn('fulfill_status', ['printed', 'pressed']);
        // //     })
        // //     ->count();

        // // // Orders shipped today (items)
        // $ordershippedtodays = $recentOrderItems->clone()
        //     ->whereHas('order', function ($query) use ($todayStart, $todayEnd) {
        //         $query->where('fulfill_status', 'shipped')
        //             ->whereBetween('updated_at', [$todayStart, $todayEnd]);
        //     })
        //     ->get();
        // dd($ordershippedtodays);
        // // Orders shipped yesterday (items)
        // $ordershippedyesterdays = $recentOrderItems->clone()
        //     ->whereHas('order', function ($query) use ($yesterdayStart, $yesterdayEnd) {
        //         $query->where('fulfill_status', 'shipped')
        //             ->whereBetween('updated_at', [$yesterdayStart, $yesterdayEnd]);
        //     })
        //     ->count();

        // \Log::info('orderNews: ' . $orderNews);
        // \Log::info('orderProduction: ' . $orderProduction);
        // \Log::info('ordershippedtodays: ' . $ordershippedtodays);
        // \Log::info('ordershippedyesterdays: ' . $ordershippedyesterdays);
    }
    // public function webhook(Request $request)
    // {
    //     \Log::info('webhook');
    //     \Log::info($request->all());
    //     // $order = Order::find(221733);
    //     // $user = $order->user;
    //     // $user->wallet_balance = $user->wallet_balance-2;
    //     // $user->save();
    //     // $order->fulfill_status = 'priority';
    //     // $order->save();
    // // echo count([214562,214592,214594,214617,214619,214628,214647,214649,214652,214669,214671,214681,214688,214695,214715,214725,214734,214756,214785,214793,214810,214812,214818,214888,214977,215012,215031,215041,215077,215078,215139,215152,215158,215163,215166,215286,215287,215288,215292,215324,215330,215360,215383,215388,215390,215449,215479,215497,215596,215618,215647,215708,215728,215734,215740,215750,215780,215788,215798,215802,215828,215848,215854,215890,215929,215931,215935,215946,215982,215994,215995,216002,216005,216036,216039,216060,216080,216097,216100,216114,216133,216201,216205,216242,216337,216373,216398,216411,216412,216413,216449,216547,216633,216710,216726,216735,216745,216749,216750,216752,216755,216846,216908,216939,216998,217018,217080,217114,217174,217215,217227,217253,217263,217271,217275,217290,217326,217327,217340,217367,217383,217459,217474,217492,217501,217510,217521,217524,217531,217555,217571,217578,217585,217624,217635,217731,217752,217767,217773,217775,217779,217780,217814,217826,217827,217828,217861,217866,217879,217880,217893,218018,218124,218127,218135,218141,218151,218153,218156,218157,218190,218229,218245,218247,218252,218260,218302,218304,218328,218329,218334,218362,218364,218385,218456,218478,218504,218515,218520,218547,218571,218591,218597,218614,218637,218649,218652,218674,218683,218715,218771,218772,218776,218777,218794,218845,218913,218927,218974,218980,218986,218992,218993,219000,219013,219026,219036,219061,219071,219078,219084,219088,219089,219092,219094,219113,219154,219156,219175,219196,219216,219220,219222,219233,219280,219290,219292,219297,219334,219348,219391,219404,219422,219458,219470,219498,219503,219526,219536,219537,219590,219603,219608,219609,219621,219632,219641,219645,219654,219693,219701,219719,219740,219753,219797,219798,219799,219806,219833,219843,219849,219863,219895,219947,219949,219980,219981,219986,219991,220004,220018,220034,220074,220076,220089,220105,220151,220184,220190,220205,220212,220232,220269,220274,220295,220305,220318,220350,220406,220461,220504,220510,220511,220518,220526,220541,220546,220552,220562,220566,220567,220580,220581,220594,220604,220610,220612,220629,220646,220667,220672,220676,220678,220741,220747,220768,220782,220818,220832,220838,220856,220857,220899,220925,220954,220966,220994,221005,221008,221019,221053,221066,221084,221088,221090,221107,221140,221148,221157,221160,221167,221181,221196,221249,221256,221263,221290,221298,221377,221381,221388,221389,221409,221416,221459,221470,221475,221526,221530,221560,221563,221607,221623,221689,221692,221733,221734,221739,221759,221763,221769,221794,221828,221856,221863,221870]);
    //     // $this->refund(4, 9.1);
    //     // $cost = Order::select('id')
    //     //     ->where('id', '>=', 214528)
    //     //     ->whereNull('address_1')
    //     //     ->where('seller_id', 4)
    //     //     ->where('shipping_cost', '>', 0.65)
    //     //     ->whereHas('items', function ($query) {
    //     //         // đơn có 2 item trở lên 
    //     //         $query->groupBy('order_id')->havingRaw('COUNT(order_id) > 1');
    //     //     })
    //     //     // tính tổng giá shipping_cost - 0.65
    //     //     ->get()
    //     //     ->sum(function ($order) {
    //     //         return $order->shipping_cost - 0.65;
    //     //     });
    //     // return response()->json($cost);
    //     // $order = Order::select('id')
    //     //     ->where('id', '>=', 214528)
    //     //     ->whereNull('address_1')
    //     //     ->whereIn('seller_id', [4,5,2])
    //     //     ->where('shipping_cost', '>', 0.65)
    //     //     ->whereHas('items', function ($query) {
    //     //         // đơn có 2 item trở lên 
    //     //         $query->groupBy('order_id')->havingRaw('COUNT(order_id) > 1');
    //     //     })
    //     //     // ->limit(50)
    //     //     ->pluck('id')->toArray();
    //     // $rs = implode(',', $order);
    //     // dd($rs);
    //     // // // $refund = 0;

    //     // $message = [];
    //     // foreach ($order as $item) {
    //     //     // dd($item);
    //     //     array_push($message, $item);
    //     //     $order = Order::find($item);
    //     //     $order->shipping_cost = 0.65;
    //     //     $order->total_cost = $order->shipping_cost + $order->print_cost;
    //     //     $order->save();
    //     // }
    //     // return response()->json($message);
    //     // $order->fulfill_status = 'new_order';
    //     // $order->save();
    //     // dd($order);      
    //     // $product = ProductVariants::where('variant_id', 2730)->first();
    //     // dd($product);
    //     // $order = Order::find(195523);
    //     // // $order->fulfill_status = 'printed';
    //     // // $order->save();
    //     // $user = $order->user;
    //     // $user->wallet_balance = $user->wallet_balance+1;
    //     // $user->save();
    //     // dd($user);
    //     // $order->fulfill_status = 'printed';
    //     // $order->save();
    //     // $supports = Supports::select('id', 'status','order_id')->where('order_id', 188578)->where('status', '!=','Solved')->get();
    //     // dd($supports);
    //     // foreach ($supports as $key => $support) {
    //     //     if($key==0){
    //     //         continue;
    //     //     }
    //     //     $support->delete();
    //     // }
    //     // $order = Order::find(168671);
    //     // $order->fulfill_status = 'printed';
    //     // $order->save();
    //     // dd($order);
    //     // $support = Supports::where('status', 'New')->get();
    //     // foreach ($support as $item) {
    //     //     $item->status = 'Solved';
    //     //     $item->save();
    //     // }   
    //     // dd($support);
    //     // $order->fulfill_status = 'shipped';
    //     // $order->save();
    //     // $todayStart = now()->startOfDay();
    //     // $todayEnd = now()->endOfDay();
    //     // dd($todayStart, $todayEnd);
    //     // $yesterdayStart = now()->subDay()->startOfDay();
    //     // $yesterdayEnd = now()->subDay()->endOfDay();

    //     // // Define the common query conditions
    //     // $recentOrderItems = OrderItem::with('order')
    //     //     ->where('created_at', '>=', now()->subDays(3)->startOfDay());

    //     // // // Orders created today with new_order status
    //     // // $orderNews = Order::where('created_at', '>=', now()->subDays(1)->startOfDay())
    //     // //     ->where('fulfill_status', 'new_order')
    //     // //     ->count();

    //     // // // Orders in production (printed or pressed)
    //     // // $orderProduction = $recentOrderItems->clone()
    //     // //     ->whereHas('order', function ($query) {
    //     // //         $query->whereIn('fulfill_status', ['printed', 'pressed']);
    //     // //     })
    //     // //     ->count();

    //     // // // Orders shipped today (items)
    //     // $ordershippedtodays = $recentOrderItems->clone()
    //     //     ->whereHas('order', function ($query) use ($todayStart, $todayEnd) {
    //     //         $query->where('fulfill_status', 'shipped')
    //     //             ->whereBetween('updated_at', [$todayStart, $todayEnd]);
    //     //     })
    //     //     ->get();
    //     // dd($ordershippedtodays);
    //     // // Orders shipped yesterday (items)
    //     // $ordershippedyesterdays = $recentOrderItems->clone()
    //     //     ->whereHas('order', function ($query) use ($yesterdayStart, $yesterdayEnd) {
    //     //         $query->where('fulfill_status', 'shipped')
    //     //             ->whereBetween('updated_at', [$yesterdayStart, $yesterdayEnd]);
    //     //     })
    //     //     ->count();

    //     // \Log::info('orderNews: ' . $orderNews);
    //     // \Log::info('orderProduction: ' . $orderProduction);
    //     // \Log::info('ordershippedtodays: ' . $ordershippedtodays);
    //     // \Log::info('ordershippedyesterdays: ' . $ordershippedyesterdays);
    // }

    public function deleteWithAuth($numCredentials, $nameImage, $folderNameIdLabel)
    {
        $googleDriver = new GoogleDriverServices($numCredentials);
        return $googleDriver->deleteFileInFolder($nameImage, $folderNameIdLabel);
    }
    public function test3(Request $request)
    {

    }
    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];
        for ($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }
    public function getOrderDay(Carbon $date)
    {
        $user = Auth::user();
        return OrderItem::whereHas('order', function ($query) use ($user) {
            if ($user->role_id == 3) {
                $query->where('seller_id', $user->id);
            }
        })->where('created_at', '>=', $date->startOfDay()->toDateTimeString())->where('created_at', '<=', $date->endOfDay()->toDateTimeString())->count();
    }
    public function test2(Request $request)
    {
        // $createTwelveHours = Carbon::now()->subHours(12)->toDateTimeString();
        // $createTwentyHours = Carbon::now()->subHours(20)->toDateTimeString();
        // $createSixHours = Carbon::now()->subHours(6)->toDateTimeString();

        // $tracking = Tracking::select('tracking_id as tracking', 'service as carrier', 'status', 'order_id')
        //     ->where(function ($query) use ($createTwelveHours, $createTwentyHours, $createSixHours) {
        //         $query->where(function ($innerQuery) use ($createSixHours) {
        //             $innerQuery->where('status', 'Info received')
        //                 ->where('updated_at', '<=', $createSixHours);
        //         })->orWhere(function ($innerQuery) use ($createTwentyHours) {
        //             $innerQuery->where('status', 'Pending')
        //                 ->where('updated_at', '<=', $createTwentyHours);
        //         })->orWhere(function ($innerQuery) use ($createTwelveHours) {
        //             $innerQuery->where('status', '!=', 'Delivered')
        //                 ->WhereNotNull('tracking_id')
        //                 ->where('status', '!=', 'Info received') // Exclude 'Info received' status
        //                 ->where('status', '!=', 'Pending') // Exclude 'Pending' status
        //                 ->where('updated_at', '<=', $createTwelveHours);
        //         });
        //     })
        //     // ->limit(7)
        //     ->orderBy('updated_at', 'ASC')
        //     ->get()
        //     ->toArray();

        // shuffle($tracking);
        // return json_encode($tracking);
    }
    private function downloadImage(string $url, string $folder, $id)
    {
        $name = $id . '.png';
        $directoryPath = public_path("$folder");

        // Check if the directory exists, if not, create it
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        $filePath = "$directoryPath/$name";

        // Check if the file already exists in the storage
        if (!file_exists($filePath)) {
            // File doesn't exist, proceed with downloading
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $contents = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Check if download was successful
            if ($httpCode == 200 && $contents !== false) {
                // Save the contents to the specified file path
                file_put_contents($filePath, $contents);
                \Log::info('Download image from URL: ' . $url);
            } else {
                // Handle download failure
                \Log::error('Download failed for image from URL: ' . $url);
                return null;
            }
        }

        return $filePath;
    }
    public function convertImageOld(Request $request)
    {
        $this->id = $request->itemID;
        $this->type = $request->type;
        try {
            $this->deleteMeta($request->itemID, $request->type . '_qr');
            $checkqr = OrderItemMeta::where('order_item_id', $this->id)->where('meta_key', $this->type . '_qr')->first();
            if ($checkqr == null) {
                $orderItem = OrderItem::find($this->id);
                if ($orderItem != null) {
                    $printedDesign = OrderItemMeta::where('order_item_id', $orderItem->id)->where('meta_key', $this->type)->first();

                    if ($printedDesign != null) {
                        \Log::info($this->id . " " . $this->type . " " . $printedDesign->meta_value . " ");
                        // dd($printedDesign->meta_value);
                        // Load the design image with transparency support
                        $designPath = $this->downloadImage($printedDesign->meta_value, "design/{$this->id}");
                        if ($designPath == null) {
                            \Log::info("download image error");
                            return;
                        }
                        \Log::info("download design path: " . $designPath);

                        if (!file_exists($designPath)) {
                            \Log::info("download image error");
                            return;
                        }
                        // Tạo ảnh từ đường dẫn
                        $designType = exif_imagetype($designPath);

                        if ($designType !== IMAGETYPE_PNG) {
                            // Ví dụ:
                            \Log::info("link design no have");
                            return;
                        }

                        $design = @imagecreatefrompng($designPath);

                        \Log::info("Image design path: " . $designPath);

                        // Get the dimensions of the original image
                        $imageSize = getimagesize($designPath);

                        // Lấy chiều rộng và chiều cao từ mảng kết quả
                        $originalWidth = $imageSize[0];
                        $originalHeight = $imageSize[1];

                        // Find the bounding box of the non-transparent pixels
                        $minX = $originalWidth - 1;
                        $maxX = 0;
                        $minY = $originalHeight - 1;
                        $maxY = 0;

                        for ($x = 0; $x < $originalWidth; $x++) {
                            for ($y = 0; $y < $originalHeight; $y++) {
                                // Check if the pixel is not transparent
                                $colorIndex = imagecolorat($design, $x, $y);
                                $color = imagecolorsforindex($design, $colorIndex);

                                // Check if the pixel is not transparent
                                if ($color['alpha'] != 127) { // 127 is the default alpha value for transparency
                                    // Update bounding box coordinates
                                    $minX = min($minX, $x);
                                    $maxX = max($maxX, $x);
                                    $minY = min($minY, $y);
                                    $maxY = max($maxY, $y);
                                }
                            }
                        }

                        // Calculate the dimensions of the new image
                        $newHeight = $maxY + 450;
                        // Create a transparent image with the same dimensions as the cropped region
                        $croppedDesign = imagecreatetruecolor($originalWidth, $newHeight);

                        // Enable alpha blending
                        imagealphablending($croppedDesign, false);

                        // Allocate a transparent color
                        $transparentColor = imagecolorallocatealpha($croppedDesign, 0, 0, 0, 127);

                        // Loop through the image rows in chunks
                        for ($y = 0; $y < $newHeight; $y++) {
                            for ($x = 0; $x < $originalWidth; $x++) {
                                imagesetpixel($croppedDesign, $x, $y, $transparentColor);
                            }
                        }
                        imagesavealpha($croppedDesign, true);

                        // Crop the original image to the calculated dimensions
                        imagecopy($croppedDesign, $design, 0, 0, 0, 0, $originalWidth, $maxY);
                        //make qr item
                        $overlayImagePath = env('APP_URL') . '/image/qr/' . $this->id . '?type=' . $this->type;

                        $fileimage = file_get_contents($overlayImagePath);

                        $overlayImage = imagecreatefromstring($fileimage);

                        if (!$overlayImage) {
                            \Log::info("Get image error");
                            return;
                        }
                        $resizedQrImage = imagescale($overlayImage, 1300, 250);

                        // Get the dimensions of the soverlay image
                        $overlayWidth = imagesx($resizedQrImage);
                        $overlayHeight = imagesy($resizedQrImage);
                        \Log::info($overlayWidth);
                        \Log::info($overlayHeight);
                        // Merge the overlay image onto the design image at the desired position (e.g., centered)
                        $overlayX = 100; // Center horizontally
                        $overlayY = (imagesy($croppedDesign) - $overlayHeight) - 100; // Center vertically

                        // // Merge resizedQrImage onto backgrounddesign
                        imagecopy($croppedDesign, $resizedQrImage, $overlayX, $overlayY, 0, 0, $overlayWidth, $overlayHeight);
                        \Log::info("design qr");

                        // header('Content-Type: image/png');
                        // imagepng($croppedDesign);

                        $temporaryFilePath = tempnam(sys_get_temp_dir(), 'cropped_image_');
                        \Log::info("create temp");
                        imagepng($croppedDesign, $temporaryFilePath);
                        // \Log::info("add image to temp");
                        $fileContents = file_get_contents($temporaryFilePath);
                        // unlink($temporaryFilePath);

                        $type = substr($this->type, 0, strpos($this->type, '_design'));
                        $fileName = "/design/{$orderItem->order->id}_{$orderItem->id}_{$type}.png";
                        $path = Storage::disk('b2')->put($fileName, $fileContents, 'public');
                        $link = "https://pressifypod.s3.us-west-004.backblazeb2.com" . $fileName;

                        $this->storeMeta($orderItem->id, $this->type . '_qr', $link);

                        unlink($designPath);
                        $directoryPath = storage_path("app/image/design/{$this->id}");
                        if (File::isDirectory($directoryPath)) {
                            rmdir($directoryPath);
                        }
                        header('Content-Type: image/png');
                        imagepng($croppedDesign);
                        // Output the final image
                        imagedestroy($design);
                        imagedestroy($overlayImage);
                        imagedestroy($croppedDesign);
                        \Log::info("Append qr success:" . $link);
                        // return;
                    }
                    \Log::info("No found type in meta");
                    // return;
                }
                \Log::info("No found item");
            }
            \Log::info("Item is append qr");
        } catch (\Throwable $th) {
            \Log::info($th);
        }
    }


    public function testbasecost(Request $request)
    {
        return response()->json([
            'print basecost' => printBaseCostNewForOrder($request->id),
            'shipping fee' => shipBaseCostNew($request->id),
            'total cost' => shipBaseCostNew($request->id) + printBaseCostNewForOrder($request->id),
        ]);
        dd(123);

        // $orders = Order::all();
        $page = $request->page;
        $orders = Order::paginate(200, ['*'], 'page', $page);

        $checkOrder = [];
        foreach ($orders as $order) {
            $orderItems = $order->items;
            // \Log::info($orderItems);
            $printCost = 0;
            // dd($order);
            foreach ($orderItems as $orderItem) {
                $priceForItem = printBaseCostNewForItem($orderItem->id);
                // if($priceForItem != 'error'){
                $orderItem->price = $priceForItem;
                $orderItem->save();
                $printCost += $priceForItem;
                // }else{
                //     $checkOrder[] = $order->id;
                //     // dd($order);
                // }
                // dd(printBaseCostNewForItem($orderItem->id));

            }
            $shipForOrder = shipBaseCostNew($order->id);
            // if($shipForOrder == 'error'){
            //     $checkOrder[] = $order->id;
            //     // dd($order);
            // }
            // dd(shipBaseCostNew($orderItem->id));
            // $order->shipping_cost = $shipForOrder == 'error' ? $order->shipping_cost : $shipForOrder;
            $order->shipping_cost = $shipForOrder;
            $order->print_cost = $printCost;
            $order->total_cost = $printCost + $order->shipping_cost;
            $order->save();
            $checkOrder[] = $order->id;
            // dd($checkOrder);
        }
        return response()->json($checkOrder);
        // dd($checkOrder);


    }
    public function testDriver(Request $request)
    {
        $googleDriver = new GoogleDriverServices();
        // $credentialsPath = public_path('credentials.json');
        $credentialsPath = public_path('key_driver/credentials_supover.json');
        $folderNameIdDesign = $googleDriver->getClient2($credentialsPath);
        dd($folderNameIdDesign);
    }

    // private function downloadImage(string $url, string $folder)
    // {
    //     $name = substr($url, strrpos($url, '/') + 1);
    //     $directoryPath = public_path("$folder");

    //     // Check if the directory exists, if not, create it
    //     if (!file_exists($directoryPath)) {
    //         mkdir($directoryPath, 0755, true);
    //     }

    //     $filePath = "$directoryPath/$name";

    //     // Check if the file already exists in the storage
    //     if (!file_exists($filePath)) {
    //         // File doesn't exist, proceed with downloading
    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, $url);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         $contents = curl_exec($ch);
    //         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //         curl_close($ch);

    //         // Check if download was successful
    //         if ($httpCode == 200 && $contents !== false) {
    //             // Save the contents to the specified file path
    //             file_put_contents($filePath, $contents);
    //             \Log::info('Download image from URL: ' . $url);
    //         } else {
    //             // Handle download failure
    //             \Log::error('Download failed for image from URL: ' . $url);
    //             return null;
    //         }
    //     }

    //     return $filePath;
    // }
    private function downloadImagefile(string $url, string $folder)
    {
        $name = substr($url, strrpos($url, '/') + 1);
        $directoryPath = public_path("$folder");

        // Check if the directory exists, if not, create it
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        $filePath = "$directoryPath/$name";

        // Check if the file already exists in the storage
        if (!file_exists($filePath)) {
            // File doesn't exist, proceed with downloading
            $contents = @file_get_contents($url);

            // Check if download was successful
            if ($contents !== false) {
                // Save the contents to the specified file path
                file_put_contents($filePath, $contents);
                \Log::info('Download image from URL: ' . $url);
            } else {
                // Handle download failure
                \Log::error('Download failed for image from URL: ' . $url);
                return null;
            }
        }

        return $filePath;
    }

    public function downloadPdf(string $url, string $folder)
    {
        $contents = file_get_contents($url);

        // Extract filename from the URL using basename
        $name = basename(parse_url($url, PHP_URL_PATH));

        // Save the file to the specified folder
        Storage::put("image/$folder/$name", $contents);

        // Return the full path to the saved file
        return storage_path("app/image/$folder/$name");
    }
    public function storeMeta($orderID, $name, $value)
    {
        $meta = new OrderItemMeta();
        $meta->order_item_id = $orderID;
        $meta->meta_key = $name;
        $meta->meta_value = $value;
        $meta->save();
    }
    public function deleteMeta($orderID, $name)
    {
        $meta = OrderItemMeta::where('order_item_id', $orderID)->where('meta_key', $name)->first();
        if ($meta) {
            $meta->delete();
        }
    }
    public function getMeta($orderID, $name)
    {
        $meta = OrderItemMeta::where('order_item_id', $orderID)->where('meta_key', $name)->first();
        if ($meta) {
            return $meta->meta_value;
        }
        return "";
    }

    public function addProcessTimeById($id, $value)
    {
        // dd($value);
        $order = Order::find($id);
        $order->process_time = $value;
        $order->save();
        return $order;
    }
    public function getTrackingFromJpg(Request $request)
    {
        // $this->compareTracking($request);
        // $orders = Order::whereNull('tracking_id')->whereNotNull('convert_label')->where('fulfill_status','shipped')->limit(2)->get();
        // $orders = Order::whereNull('tracking_id')->where('fulfill_status','shipped')->whereNotNull('shipping_label')->limit(1)->get();
        // $orders = DB::table('orders')
        // ->leftJoin('tracking', function($join) {
        //     $join->on('orders.id', '=', 'tracking.order_id')
        //         ->whereRaw('orders.tracking_id != tracking.tracking_id');
        // })
        // ->select('orders.*')
        // ->whereNotNull('tracking.id')
        // ->get();
        // $tracking
        $orders = Order::where('id', $request->id)->get();
        // $orders = Order::where('fulfill_status', "shipped")
        //     ->whereNotNull("convert_label")
        //     ->whereNull("tracking_id")
        //     ->where('created_at', '>=', now()->subDays(30))
        //     ->limit(5)
        //     ->get();
        // // dd($orders);
        // $message = [];
        foreach ($orders as $order) {

            // get image from link $order->convert_label add temp
            $tempfile = tempnam(sys_get_temp_dir(), 'tracking_') . '.jpg';
            file_put_contents($tempfile, file_get_contents(str_replace(" ", "%20", $order->shipping_label)));
            // Generate a unique temporary file name for the image
            $tempImagePath = tempnam(sys_get_temp_dir(), 'ocr_') . '.jpg';

            // Convert PDF to JPG
            $pdf = new Pdf($tempfile);
            $pdf->setOutputFormat('jpg');
            $pdf->setResolution(300);
            $pdf->saveImage($tempImagePath);
            $result = (new TesseractOCR($tempImagePath))->run();

            // Extract numbers from the OCR result
            preg_match_all('/\d+/', $result, $matches);
            $numbers = $matches[0];
            // dd($numbers);
            //get từ số thứ 4 ở cuối $numbers lấy 6 số
            $position_9400 = array_search("9400", $numbers);
            $position_9200 = array_search("9200", $numbers);
            if ($position_9400 !== false) {
                $position = $position_9400;
            } elseif ($position_9200 !== false) {
                $position = $position_9200;
            } else {
                // If neither "9400" nor "9200" is found, take the last 6 numbers
                $position = 0;
            }

            if ($position) {
                $tracking_numbers = array_slice($numbers, $position, 6);
            } else {
                $tracking_numbers = array_slice($numbers, sizeOf($numbers) - 6, 6);
            }

            $tracking_numbers_string = implode('', $tracking_numbers);

            unlink($tempImagePath);
            unlink($tempfile);
            dd($tracking_numbers_string);

            $order->tracking_id = $tracking_numbers_string;
            $rs = $order->save();
            if ($rs) {
                array_push($message, $order->id);
            }
        }
        return response()->json(['message' => $message]);
    }
    public function compareTracking(Request $request)
    {
        $orders = DB::table('orders')
            ->leftJoin('tracking', function ($join) {
                $join->on('orders.id', '=', 'tracking.order_id')
                    ->whereRaw('orders.tracking_id != tracking.tracking_id');
            })
            ->select('orders.*')
            ->whereNotNull('tracking.id')
            ->get();
        // $orders = Order::where('id',$request->id)->get();
        // dd($orders);
        $message = [];
        foreach ($orders as $order) {
            $find = Tracking::where('order_id', $order->id)->first();
            if ($find) {
                $find->tracking_id = $order->tracking_id;
                $rs = $find->save();
            } else {
                $tracking = new Tracking();
                $tracking->tracking_id = $order->tracking_id;
                $tracking->order_id = $order->id;
                // $tracking->status = $order->id;
                $rs = $tracking->save();
            }
            if ($rs) {
                array_push($message, $order->id);
            }
        }
        return response()->json(['message' => $message]);
    }
    public function testTransactions(Request $request)
    {

        $orders = Order::join('users', 'users.id', '=', 'orders.seller_id')
            ->select('orders.*')
            ->whereColumn('users.wallet_balance', '>=', 'orders.total_cost')
            ->where('orders.payment_status', 'pending')
            ->where('orders.fulfill_status', '!=', 'cancelled')
            ->where('orders.fulfill_status', '!=', 'test_order')
            ->where('orders.created_at', '<', now()->subHours(1))
            ->orderBy('orders.created_at', 'ASC')
            ->limit(10)->get();
        dd($orders);
        $orders = DB::table('orders')
            // ->select('id','fulfill_status','payment_status')
            ->where('fulfill_status', '<>', 'cancelled')
            ->where('fulfill_status', '<>', 'test_order')
            // ->where('payment_status', ['paid'])
            ->whereDate('created_at', '>=', '2024-03-04')
            ->orderBy('created_at', 'DESC')
            ->get();
        $listOrder = [];
        // return 123;
        foreach ($orders as $order) {

            // $transaction = Transaction::where('order_id', $order->id)->first();

            // if ($transaction) {
            //     // $transaction->amount = -1 * abs($order->total_cost);
            //     // $transaction->remaining_balance = -1 * abs($order->total_cost);
            //     $transaction->delete();
            // }
            $listOrder[] = $order->id;
            // \Log::info("pay order");
            // dd($transaction);
            PayOrderJob::dispatch($order->id);
            // dd($order->id);
        }
        dd($listOrder);
    }

    public function listOrderPending(Request $request)
    {
        $orderId = $request->id;

        $order = Order::select('ref_id', 'id')
            ->where('ref_id', $orderId)
            ->first();
        if ($order) {
            return response()->json($order->id);
        }
        return response()->json('');


    }
    public function test10(Request $request)
    {
        $googleDriver = new GoogleDriverServices();
        // $check = $googleDriver->getFolderId('designs');
        $check = $googleDriver->uploadFileFromUrl('https://supoverdesign.nyc3.digitaloceanspaces.com/OJ%20Inspired%20Comfort%20Colors%20T-Shirt_321.png', 'test', '14OJIvvB9FNbm-ssX3qNS_LLwFHMcS51W');
        dd($check);
        // $orderId = 12900;
        // $side = 'front_design';
        // $sideValue = 'https://supoverdesign.nyc3.digitaloceanspaces.com/OJ%20Inspired%20Comfort%20Colors%20T-Shirt_321.png';
        // $side2 = 'front_design_printed';
        // $datas = [
        //     [
        //         'order_item_id' => $orderId,
        //         'meta_key'=> $side,
        //         'meta_value'=> $sideValue,
        //     ],
        //     [
        //         'order_item_id' => $orderId,
        //         'meta_key'=> $side2,
        //         'meta_value'=> 0,
        //     ],
        // ];
        // foreach($datas as $data){
        //     $orderItem = OrderItemMeta::create($data);
        // }


        // return $orderItem;
    }
}
