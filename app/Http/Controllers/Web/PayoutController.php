<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\Payout;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $role = $user->role->name;
        $query = Payout::query();
        if($role=='Seller'||$role=='Staff'){
            $query->where('user_id', $user->id);
        }
        if(isset($request->payout_id)&&!empty($request->payout_id)){
            $query->where('payout_id',$request->payout_id);
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
            if ($arrDate[1] != null) {
                $query->whereBetween('created_at', [
                    Carbon::parse($arrDate[0])->toDateTimeString(),
                    Carbon::parse($arrDate[1])->endOfDay()->toDateTimeString()
                ]);
            }else{
                $query->whereBetween('created_at', [
                    Carbon::parse($arrDate[0])->startOfDay()->toDateTimeString(),
                    Carbon::parse($arrDate[0])->addHour(23)->addSecond(59)->addMinute(59)->toDateTimeString()
                ]);
            }
        }
        $payouts = $query->paginate(20);
        return view('payouts.index',compact('payouts','request','user','role'));
    }
    public function ajaxtotalpayout(Request $request){
        $user = Auth::user();
        $role = $user->role->name;
        $query = Payout::query();
        if(isset($request->payout_id)&&!empty($request->payout_id)){
            $query->where('payout_id',$request->payout_id);
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
            if ($arrDate[1] != null) {
                $query->whereBetween('date', [
                    Carbon::parse($arrDate[0])->toDateTimeString(),
                    Carbon::parse($arrDate[1])->endOfDay()->toDateTimeString()
                ]);
            }else{
                $query->whereBetween('date', [
                    Carbon::parse($arrDate[0])->startOfDay()->toDateTimeString(),
                    Carbon::parse($arrDate[0])->addHour(23)->addSecond(59)->addMinute(59)->toDateTimeString()
                ]);
            }
        }
        $payouts = $query->count();
        return $payouts;
    }
}
