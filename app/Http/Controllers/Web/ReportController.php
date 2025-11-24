<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Support\Facades\Http;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Vanguard\Jobs\GetOrderPageJob;
use Vanguard\Jobs\ReportAllOrderStoreJob;
use Vanguard\Jobs\ReportPayoutJob;
use Vanguard\Models\Order\Order;
use Vanguard\Models\Payout;
use Vanguard\Models\Store;
use Vanguard\Models\Teams;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;

class ReportController extends Controller
{
    public function index(){
        return view('reports.index');
    }

    public function getOrderReport(Request $request)
    {
        try {       
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'shop_code' => 'required|string',
                'team' => 'required'
            ]);
            $url = Teams::find($validated['team'])->link_page;
            $tokenResponse = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($url.'/api/account/getToken', [
                'username' => 'admin',
                'password' => ($validated['team']==1)?'Hungngt123@':'Hungngt123#'
            ]);

            $rawBody = $tokenResponse->body();
                     
            if (!$tokenResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to authenticate with external API',
                    'error' => $rawBody
                ], 401);
            }

            $token = $rawBody;

            $requestData = [
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'shop_code' => $validated['shop_code']
            ];

            $initialResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->withBody(
                json_encode($requestData),
                'application/json'
            )->get($url.'/api/orders/getList?page=1');

            if (!$initialResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch report data',
                    'error' => $initialResponse->json()
                ], $initialResponse->status());
            }

            $responseData = $initialResponse->json();

            $total = $responseData['total'] ?? 0;
            $pages = ceil($total / 20);
            \Log::info('Total: ', ['value' => $total]);
            
            $store = Store::select('id','user_id','staff_id')->where('shop_code', $request->shop_code)->first();
            $user_id = null;
            $store_id = null;
            if($store){
                $user_id = $store->user_id??$store->staff_id;
                $store_id = $store->id;
            }
            // dd($store, $user_id, $store_id);
            // add queue
            for ($page = 1; $page <= $pages; $page++) {
                GetOrderPageJob::dispatch($user_id, $store_id, $token, $page, $requestData)->onQueue('order-page');
            }

            return response()->json([
                'success' => true,
                'message' => 'Report Orders imported successfully',
                'data' => $responseData
            ]);

        } catch (\Exception $e) {         
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function reportall(Request $request)
    {
        try {       
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);

            $stores = Store::with(['user','staff','staff.team','user.team'])->select('id','user_id','staff_id','shop_code')->get();

            foreach ($stores as $key => $value) {
                $user = ($value->user??$value->staff);
                if($user){
                    $team = $user->team;
                    if($team){
                        $url = $team->link_page;
                        if($url){
                            ReportAllOrderStoreJob::dispatch($value->shop_code, $request->start_date, $request->end_date, $url, $team->id)->onQueue('report-all-order-store');
                        }
                    }
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Report Orders imported successfully',
                'data' => []
            ]);
        } catch (\Exception $e) {         
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function seller(Request $request){
        return view('reports.order');
    }
    public function reportstaff(Request $request){
        $id = $request->id;
        $start_date = Carbon::parse($request->start_date)->setTimezone('Asia/Ho_Chi_Minh')->startOfDay();
        $end_date = Carbon::parse($request->end_date)->setTimezone('Asia/Ho_Chi_Minh')->endOfDay();
        $staffreport = Order::selectRaw('
                user_id, 
                COUNT(id) as total_order, 
                SUM(total_amount) as total_amount, 
                SUM(net_revenue) as total_revenue, 
                SUM(base_cost) as total_base_cost, 
                SUM(design_fee) as total_design_fee, 
                SUM(net_profits) as total_net_profits
            ')
            ->whereBetween('created_at', [$start_date, $end_date]) // Filter by date range
            ->groupBy('user_id')
            ->where('user_id', $id)
            ->first();
        if(!$staffreport){
            return response()->json([
                'success' => false,
                'message' => []
            ], 500); 
        }
        return view('reports.ajax.ajaxstaff',compact('staffreport'));
    }
    public function reportpayout(Request $request){
        $id = $request->id;
        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();

        $staffreport = Payout::selectRaw('
                user_id, 
                COUNT(id) as total_payout, 
                SUM(payout_amout) as total_payout_amount, 
                SUM(settlement_amount) as total_settlement_amount, 
                SUM(amount_before_exchange) as total_amount_before_exchange
            ')
            ->where('date', '>=', $start_date)
            ->where('date', '<=', $end_date)
            ->groupBy('user_id')
            ->where('user_id', $id)
            ->first();

        if(!$staffreport){
            return response()->json([
                'success' => false,
                'message' => []
            ], 500); 
        }
        return view('reports.ajax.ajaxpayout',compact('staffreport'));
    }
    public function payout(Request $request){
        if($request->isMethod('post')){
            try {       
                $validated = $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
                ]);

                $stores = Store::select('shop_code')->get();
                // dd($stores);
                foreach ($stores as $store) {
                //     try {
                //         $start_date = Carbon::parse($request->start_date)->timestamp;
                //         $end_date = Carbon::parse($request->end_date)->timestamp;
                        // $store = Store::where('shop_code', $store->shop_code)->first();
                //         $storetiktok = (new ConnectAppPartnerService())->connectAppPartnerPostProduct($store)['client'];
                //         $finances = $storetiktok->Finance->getPayments([
                //             'sort_field'=> 'create_time',
                //             'create_time_ge'=> $start_date,
                //             'create_time_lt'=> $end_date,
                //             'page_token' => ""
                //         ]);
                //         dd($finances);
                //     } catch (\Throwable $th) {
                //         dd($th);
                //     }
                    ReportPayoutJob::dispatch($store->shop_code, $request->start_date, $request->end_date,"")->onQueue('report-all-order-payout');
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Report Orders imported successfully',
                    'data' => []
                ]);
            } catch (\Exception $e) {         
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        }
        return view('reports.payout');
    }
}