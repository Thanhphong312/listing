<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\Order\Order;
use Vanguard\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vanguard\Jobs\SyncStoreSupover;
use Vanguard\Models\Meta;
use Vanguard\Models\PartnerApp;
use Vanguard\Role;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();
        $user = Auth::user();
        $role = $user->role->name;
        if($role=='Seller'||$role=='Staff'){
            $query->where('user_id', $user->id);
        }
        if(isset($request->tiktok_order_id)&&!empty($request->tiktok_order_id)){
            $query->where('tiktok_order_id',$request->tiktok_order_id);
        }
        if(isset($request->staff_id)&&!empty($request->staff_id)){
            $query->where('user_id',$request->staff_id);
        }
        if(isset($request->seller_id)&&!empty($request->seller_id)){
            $query->where('user_id',$request->seller_id);
        }
        if(isset($request->store_id)&&!empty($request->store_id)){
            $query->where('store_id', $request->store_id);
        }
        if (isset($request->datefrom) && !empty($request->datefrom)) {
            $arrDate = [$request->datefrom,$request->dateto];
            // dd($arrDate);
            if ($arrDate[1] != null) {
                $query->whereBetween('tiktok_create_date', [
                    Carbon::parse($arrDate[0])->toDateTimeString(),
                    Carbon::parse($arrDate[1])->endOfDay()->toDateTimeString()
                ]);
            }else{
                // echo Carbon::parse($arrDate[0])->startOfDay()->toDateTimeString();
                // dd();
                $query->whereBetween('tiktok_create_date', [
                    Carbon::parse($arrDate[0])->startOfDay()->toDateTimeString(),
                    Carbon::parse($arrDate[0])->addHour(23)->addSecond(59)->addMinute(59)->toDateTimeString()
                ]);
            }
        }
        $orders = $query->orderBy('created_at','desc')->paginate(20);
        return view('orders.index', compact('orders','request','user','role'));
    }
    public function ajaxtotalorder(Request $request){
        $query = Order::query();
        $user = Auth::user();
        $role = $user->role->name;
        if($role=='Seller'||$role=='Staff'){
            $query->where('user_id', $user->id);
        }
        if(isset($request->tiktok_order_id)&&!empty($request->tiktok_order_id)){
            $query->where('tiktok_order_id',$request->tiktok_order_id);
        }
        if(isset($request->staff_id)&&!empty($request->staff_id)){
            $query->where('user_id',$request->staff_id);
        }
        if(isset($request->seller_id)&&!empty($request->seller_id)){
            $query->where('user_id',$request->seller_id);
        }
        if(isset($request->store_id)&&!empty($request->store_id)){
            $query->where('store_id', $request->store_id);
        }
        if (isset($request->datefrom) && !empty($request->datefrom)) {
            $arrDate = [$request->datefrom,$request->dateto];
            // dd($arrDate);
            if ($arrDate[1] != null) {
                $query->whereBetween('tiktok_create_date', [
                    Carbon::parse($arrDate[0])->toDateTimeString(),
                    Carbon::parse($arrDate[1])->endOfDay()->toDateTimeString()
                ]);
            }else{
                // echo Carbon::parse($arrDate[0])->startOfDay()->toDateTimeString();
                // dd();
                $query->whereBetween('tiktok_create_date', [
                    Carbon::parse($arrDate[0])->startOfDay()->toDateTimeString(),
                    Carbon::parse($arrDate[0])->addHour(23)->addSecond(59)->addMinute(59)->toDateTimeString()
                ]);
            }
        }
        $orders = $query->count();
        return $orders;
    }
}
