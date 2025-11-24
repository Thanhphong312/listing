<?php

namespace Vanguard\Http\Controllers\Web;

use Exception;
use Google\Service\Calendar\Setting;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\Order\Order;
use Vanguard\Models\Order\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Vanguard\Models\Supports;
use Vanguard\Models\TimeLine;
use Vanguard\Models\Tracking;
use Vanguard\Models\Transaction;
use Vanguard\User;
use Vanguard\ProductVariants;
use Vanguard\Services\OrderService;
use Symfony\Component\HttpClient\HttpClient;

class AjaxController extends Controller
{
    public function AjaxOrderToday(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->name;
        $query = Order::selectRaw('
                COUNT(id) as total_order, 
                SUM(total_amount) as total_amount, 
                SUM(net_revenue) as total_revenue, 
                SUM(base_cost) as total_base_cost
            ');
        if($role=='Seller'||$role=='Staff'){
            $query->where('user_id', $user->id);
        }
        if (!empty($request->team)) {
            $query->whereHas('user.team', function ($teamQuery) use ($request) {
                $teamQuery->where('teams.id', (int)$request->team);
            });
        }
        
        $order = $query->where('created_at','>=', Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->startOfDay())
            ->where('created_at','<=', Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->endOfDay())
            ->first();

        return [
            'total_order'=>$order->total_order,
            'total_amount'=>$order->total_amount,
            'total_revenue'=>$order->total_revenue,
            'total_base_cost'=>$order->total_base_cost,
        ];
        
    }
    public function AjaxOrderYesterday(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->name;
        $query = Order::selectRaw('
                COUNT(id) as total_order, 
                SUM(total_amount) as total_amount, 
                SUM(net_revenue) as total_revenue, 
                SUM(base_cost) as total_base_cost
            ');
        if($role=='Seller'||$role=='Staff'){
            $query->where('user_id', $user->id);
        }
        if (!empty($request->team)) {
            $query->whereHas('user.team', function ($teamQuery) use ($request) {
                $teamQuery->where('teams.id', (int)$request->team);
            });
        }
        $order = $query
            ->where('created_at','>=', Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->subDay()->startOfDay())
            ->where('created_at','<=', Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->subDay()->endOfDay())
            ->first();

        return [
            'total_order'=>$order->total_order,
            'total_amount'=>$order->total_amount,
            'total_revenue'=>$order->total_revenue,
            'total_base_cost'=>$order->total_base_cost,
        ];
    }
    public function AjaxOrderThisMonth(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->name;
        $query = Order::selectRaw('
                COUNT(id) as total_order, 
                SUM(total_amount) as total_amount, 
                SUM(net_revenue) as total_revenue, 
                SUM(base_cost) as total_base_cost
            ');
        if($role=='Seller'||$role=='Staff'){
            $query->where('user_id', $user->id);
        }
        if (!empty($request->team)) {
            $query->whereHas('user.team', function ($teamQuery) use ($request) {
                $teamQuery->where('teams.id', (int)$request->team);
            });
        }
        $order = $query
            ->where('created_at','>=', Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->startOfMonth())
            ->where('created_at','<=', Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->endOfMonth())
            ->first();

        return [
            'total_order'=>$order->total_order,
            'total_amount'=>$order->total_amount,
            'total_revenue'=>$order->total_revenue,
            'total_base_cost'=>$order->total_base_cost,
        ];
    }
    public function AjaxOrderLastMonth(Request $request)
    {
        
        $user = Auth::user();
        $role = $user->role->name;
        $query = Order::selectRaw('
                COUNT(id) as total_order, 
                SUM(total_amount) as total_amount, 
                SUM(net_revenue) as total_revenue, 
                SUM(base_cost) as total_base_cost
            ');
        if($role=='Seller'||$role=='Staff'){
            $query->where('user_id', $user->id);
        }
        if (!empty($request->team)) {
            $query->whereHas('user.team', function ($teamQuery) use ($request) {
                $teamQuery->where('teams.id', (int)$request->team);
            });
        }
        $order = $query
            ->where('created_at','>=', Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->subMonth()->startOfMonth())
            ->where('created_at','<=', Carbon::now()->setTimezone('Asia/Ho_Chi_Minh')->subMonth()->endOfMonth())
            ->first();

        return [
            'total_order'=>$order->total_order,
            'total_amount'=>$order->total_amount,
            'total_revenue'=>$order->total_revenue,
            'total_base_cost'=>$order->total_base_cost,
        ];
    }

    public function ajaxChart()
    {
        $startDate = Carbon::now()->subDays(6); 
        $endDate = Carbon::now(); 
        $last_week = $this->generateDateRange($startDate, $endDate);
        $week_stats = [];
        foreach ($last_week as $day_last_week) {
            $date_label = date('d-m', strtotime($day_last_week)); // Định dạng ngày
            $date_orders = $this->getOrderDay(Carbon::parse($day_last_week)); // Tổng số đơn cho ngày đó
            $week_stats[$date_label] = $date_orders;
        }
        $max = max($week_stats);
        return json_encode(["week_stats" => $week_stats, "max" => $max]);
    }


    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];
        for ($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d'); // Thêm các ngày vào mảng
        }
        return $dates;
    }

    public function getOrderDay(Carbon $date)
    {
        $user = Auth::user();
        // dd($user);
        return OrderItem::whereHas('order', function ($query) use ($user) {
            if ($user->role_id == 3) {
                $query->where('seller_id', $user->id);
            }
        })->where('created_at', '>=', $date->startOfDay()->toDateTimeString())->where('created_at', '<=', $date->endOfDay()->toDateTimeString())->count();
    }

    public function ajaxListOrders(Request $request)
    {
        $orderSellers = Order::whereHas('user', function ($query) {
            $query->where('role_id', 3);
        })
        ->when($request->day, function ($query) use ($request) {
            $startDate = now()->subDays($request->day)->startOfDay();
            $query->where('created_at', '>=', $startDate);
        })
        ->selectRaw('COUNT(*) as totalOrder, user_id as seller_id')
        ->groupBy('user_id')
        ->get();

        // Tổng số đơn hàng (tất cả seller)
        $totalOrder = $orderSellers->sum('totalOrder');
        // \Log::info('Order Sellers testtest:', ['orderSellers' => $totalOrder]);
        
        return view('dashboard.ajax.listOrderSellers', compact('orderSellers', 'totalOrder', 'request'));
    }

}
