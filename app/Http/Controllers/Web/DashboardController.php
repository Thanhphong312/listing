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

class DashboardController extends Controller
{
    /**
     * Displays the application dashboard.
     *
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->name;
        $query = Store::query()->select('id', 'name','create_flashdeal','user_id','staff_id');
        $query->where('create_flashdeal', 1);
        if($role=='Seller'||$role=='Staff'){
            $query->where('user_id', $user->id);
        }
        $stores = $query->get();
        if($role=='Admin'){
            return view('dashboard.index',compact('stores','role','user'));

        }else if($role=='Staff'||$role=='Seller'){
            return view('dashboard.seller',compact('stores','role','user'));
            
        }
    }
    public function trackingNotActive(Request $request){
        $user = Auth::user();
        $fourDay = Carbon::now()->subDays(7)->toDateTimeString();
        $ThreeDay = Carbon::now()->subDays(2)->subHours(12)->toDateTimeString();

        $listTrackingInfoReceices = Tracking::select(
            'id',
            'tracking_id',
            'tracking_link',
            'order_id',
            'total_day',
            'created_at',
            'updated_at',
            )->whereHas('order', function ($query) use ($user, $fourDay,$ThreeDay){
            if ($user->role_id == 3) {
                $query->where('seller_id', $user->id);
            }
            $query->where('fulfill_status', '<>', 'cancelled');
            $query->where('fulfill_status', '<>', 'test_order');
            $query->where('fulfill_status', '<>', 'fulfill_partner');
            $query->whereNotNull('resole_tracking_not_active');
            $query->where('tracking.created_at', '>=', $fourDay);
            $query->where('tracking.created_at', '<=', $ThreeDay);
        })->where(function ($query) {
            $query->where('status', 'Info received')
                  ->orWhere('status', 'InfoReceived');
        })->orderBy('order_id', 'ASC')->paginate(20);

        $listTrackingInfoReceicesunresolves = Tracking::select(
            'id',
            'tracking_id',
            'tracking_link',
            'order_id',
            'total_day',
            'created_at',
            'updated_at',
            )->whereHas('order', function ($query) use ($user, $fourDay,$ThreeDay){
                if ($user->role_id == 3) {
                    $query->where('seller_id', $user->id);
                }
                $query->where('fulfill_status', '<>', 'cancelled');
                $query->where('fulfill_status', '<>', 'test_order');
                $query->where('fulfill_status', '<>', 'fulfill_partner');
                $query->whereNull('resole_tracking_not_active');
                $query->where('tracking.created_at', '>=', $fourDay);
                $query->where('tracking.created_at', '<=', $ThreeDay);
            })->where(function ($query) {
                $query->where('status', 'Info received')
                    ->orWhere('status', 'InfoReceived');
            })->orderBy('order_id', 'ASC')->paginate(20);
        // dd($listTrackingNotActives);
        return view('dashboard.trackingnotactive',compact('listTrackingInfoReceices','listTrackingInfoReceicesunresolves','request'));
    }
}
