<?php

namespace Vanguard\Http\Controllers\Web;

use EcomPHP\TiktokShop\Resources\Product;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Jobs\addProductFlashdealjob;
use Vanguard\Jobs\syncAllFlashDealJob;
use Vanguard\Jobs\syncAllProductStoreFldJob;
use Vanguard\Jobs\syncFlashDealJob;
use Vanguard\Jobs\syncFlashDealProductJob;
use Vanguard\Models\FlashDeals;
use Vanguard\Models\ProductFlashdeals;
use Vanguard\Models\ProductTiktoks;
use Vanguard\Models\Store\Store;
use Illuminate\Support\Facades\Auth;
use Vanguard\Services\Flashdeals\FlashdealService;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Carbon\Carbon;
use Vanguard\Services\Users\UserService;

class FlashDealController extends Controller
{
    public function __construct(private readonly FlashdealService $flashdealService,private readonly UserService $userService){

    }
    public function index(Request $request)
    {
        $query = FlashDeals::query();
        $flashdeals = $query->orderBy('created_at', 'DESC')->paginate(20);
        return view('flashdeals.index', compact('flashdeals'));
    }
    public function show(Request $request, $store_id)
    {
        $filter = $request->all();
        $flashdeals = $this->flashdealService->panigate($filter, $store_id);
        $store = Store::select('name', 'message', 'updated_at', 'syncfld')->find($store_id);
        $store_name = $store->name ?? null;
        $message = $store->message ?? null;
        $updated_at = $store->updated_at ?? null;
        $syncfld = $store->syncfld ?? null;
        $producttiktok = ProductTiktoks::select('id')->where('store_id', $store_id)->count();
        return view('flashdeals.show.index', compact('flashdeals', 'store_name', 'store_id', 'message', 'request', 'producttiktok', 'updated_at', 'syncfld'));
    }
    public function ajax(Request $request, $id)
    {
        $flashdeal = FlashDeals::find($id);

        $productFlashDeals = ProductFlashdeals::select('message', 'flashdeal_id')
            ->where('flashdeal_id', $flashdeal->activity_id)
            ->get();

        // Đếm tổng số bản ghi
        $totalfld = $productFlashDeals->count();

        // Đếm tổng số bản ghi có message là 'success'
        $totalsuccess = $productFlashDeals->where('message', 'success')->count();

        $store_id = $request->store_id;
        return view('flashdeals.ajax.index', compact('flashdeal', 'store_id', 'totalfld', 'totalsuccess'));
    }
    public function showproduct(Request $request, $id)
    {
        $store_id = $request->store_id;
        $query = ProductFlashdeals::query()->select('id', 'product_id', 'message', 'total_sku');
        $query->where('flashdeal_id', $request->id);
        $flashdealproducts = $query->get();
        $flashdeal = FlashDeals::select('activity_id', 'begin_time', 'end_time', 'promotion_name')->where('activity_id', $request->id)->first();
        $begin_time = Carbon::createFromTimestamp($flashdeal->begin_time);
        $end_time = Carbon::createFromTimestamp($flashdeal->end_time);
        $promotion_name = $flashdeal->promotion_name;
        $totalfld = $flashdealproducts->count();
        $total_skus = $flashdealproducts->sum('total_sku');
        $totalsuccess = $flashdealproducts->where('message', 'success')->count();
        return view('flashdeals.show.flashdeal', compact('total_skus', 'totalfld', 'totalsuccess', 'flashdealproducts', 'store_id', 'id', 'begin_time', 'end_time', 'promotion_name'));

    }
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            // 'activity_id','store_id', 'promotion_name', 'activity_type', 'product_level', 'status_fld', 'begin_time', 'end_time', 'auto', 'status'
            try {
                $store = Store::find($request->store_add);

                $storetiktok = (new ConnectAppPartnerService())->connectAppPartnerPostProduct($store)['client'];

                $storetiktok->useVersion(202406);
                $promotion = $storetiktok->Promotion;
                $promotion->useVersion(202309);

                $title = $request->name_add;
                $type = $request->activity_add;
                $begin_time = Carbon::parse($request->datefrom)->timestamp;
                $end_time = Carbon::parse($request->dateto)->timestamp;
                $product_level = $request->level_add;
                $createFlashdeal = $promotion->createActivity($title, $type, $begin_time, $end_time, $product_level);
                // dd($createFlashdeal);
                // $flashDeals = new FlashDeals();
                // $flashDeals->store_id = $request->store_id;
                // $flashDeals->activity_id = $createFlashdeal['activity_id'];
                // $flashDeals->status_fld = $createFlashdeal['status'];
                // $flashDeals->begin_time = $createFlashdeal['create_time'];
                // $flashDeals->end_time = $createFlashdeal['update_time'];
                // $flashDeals->save();
                $activity = $promotion->getActivity($createFlashdeal['activity_id']);

                FlashDeals::updateorCreate([
                    'activity_id' => $activity['activity_id']
                ], [
                    'store_id' => $request->store_add,
                    'promotion_name' => $activity['title'],
                    'activity_type' => $activity['activity_type'],
                    'product_level' => $activity['product_level'],
                    'status_fld' => $activity['status'],
                    'begin_time' => $activity['begin_time'],
                    'end_time' => $activity['end_time'],
                    'auto' => 1,
                    'status' => 1
                ]);

                if ($store->create_flashdeal == 1) {
                    $store->create_flashdeal = 0;
                    $store->save();
                }

                return response(json_encode(["message" => true, "data" => []]), 200);
            } catch (\Throwable $th) {
                return response(json_encode(["message" => $th->getMessage(), "data" => []]), 400);
            }

        }
        // $query = Store::query();
        // $user = Auth::user();
        // $role = $user->role->name;
        // if ($role == 'Seller') {
        //     $query->where('user_id', $user->id);
        // }
        // $stores = $query->get();
        // dd($request->store_id);
        $store = Store::find($request->store_id);
        // dd($store);
        $name = $store->name.'_'.Carbon::now()->toDateTimeString();
        // dd($name);
        return view('flashdeals.add.index',compact('name'));
    }
    public function addFlashdeal(Request $request)
    {
        try {
            $store = Store::find($request->store_add);

            $storetiktok = (new ConnectAppPartnerService())->connectAppPartnerPostProduct($store)['client'];

            $storetiktok->useVersion(202406);
            $promotion = $storetiktok->Promotion;
            $promotion->useVersion(202309);

            $title = $request->name_add;
            $type = $request->activity_add;
            $begin_time = Carbon::parse($request->datefrom)->timestamp;
            $end_time = Carbon::parse($request->dateto)->timestamp;
            $product_level = $request->level_add;
            $createFlashdeal = $promotion->createActivity($title, $type, $begin_time, $end_time, $product_level);
            // dd($createFlashdeal);
            // $flashDeals = new FlashDeals();
            // $flashDeals->store_id = $request->store_id;
            // $flashDeals->activity_id = $createFlashdeal['activity_id'];
            // $flashDeals->status_fld = $createFlashdeal['status'];
            // $flashDeals->begin_time = $createFlashdeal['create_time'];
            // $flashDeals->end_time = $createFlashdeal['update_time'];
            // $flashDeals->save();
            $activity = $promotion->getActivity($createFlashdeal['activity_id']);

            FlashDeals::updateorCreate([
                'activity_id' => $activity['activity_id']
            ], [
                'store_id' => $request->store_add,
                'promotion_name' => $activity['title'],
                'activity_type' => $activity['activity_type'],
                'product_level' => $activity['product_level'],
                'status_fld' => $activity['status'],
                'begin_time' => $activity['begin_time'],
                'end_time' => $activity['end_time'],
                'auto' => 1,
                'status' => 1
            ]);
            return "success";
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }
    public function delete(Request $request, $id)
    {
        $productFlashdeals = ProductFlashdeals::where('id', $id)->first();
        if($productFlashdeals){
            $productFlashdeals->delete();
            return response(json_encode(["message" => true, "data" => []]), 200);   
        }
        return response(json_encode(["message" => true, "data" =>""]), 404);

    }
    public function sync_flashdeal(Request $request, $store_id)
    {
        // dd($store_id);
        syncFlashDealJob::dispatch($store_id)->delay(2)->onQueue('sync-flashdeal');
        return response(json_encode(["message" => true, "data" => []]), 200);
    }
    public function sync_all_flashdeal(Request $request)
    {
        syncAllFlashDealJob::dispatch()->delay(2)->onQueue('sync-all-flashdeal');
        return response(json_encode(["message" => true, "data" => []]), 200);

    }
    public function sync_product_store(Request $request, $id)
    {
        syncAllProductStoreFldJob::dispatch($id)->delay(2)->onQueue('sync-all-flashdeal');
        return response(json_encode(["message" => true, "data" => []]), 200);

    }
    public function getallproduct(Request $request)
    {
        // $store = Store::find($request->store_id);
        $query = ProductTiktoks::query()
            ->with('storeProduct')
            ->where('store_id', $request->store_id)
            ->whereIn('status', ['ACTIVATE', 'PENDING'])
            ->whereDoesntHave('flashdealproduct.flashdeal', function ($query) {
                $query->where('status_fld', 'ONGOING');
            });
            if (isset($request->name) && !empty($request->name)) {
                $query->where('title', 'like', "%" . $request->name . "%");
            }
            if (isset($request->remote_id) && !empty($request->remote_id)) {
                $query->where('remote_id', (string) $request->remote_id);
            }
        $total = $query->count();
        $producttiktoks = $query->simplePaginate(50);
        return ['data' => $producttiktoks, 'total' => $total];
    }
    public function getallproducttiktok(Request $request)
    {
        $query = ProductTiktoks::query()
            ->where('store_id', $request->store_id);
        if (isset($request->name) && !empty($request->name)) {
            $query->where('title', 'like', "%" . $request->name . "%");
        }
        if (isset($request->remote_id) && !empty($request->remote_id)) {
            $query->where('remote_id', (string) $request->remote_id);
        }

        $total = $query->count();
        $producttiktoks = $query->orderBy('is_flashdeal', 'asc')->simplePaginate(50);
        return ['data' => $producttiktoks, 'total' => $total];
    }
    public function editallproducttiktok(Request $request){
        $producttiktoklists = $request->producttiktoklist;
        $discount = $request->discount;
        foreach($producttiktoklists as $producttiktoklist){
            $producttiktok = ProductTiktoks::select('id','discount')->where('id', $producttiktoklist)->first();
            $producttiktok->discount = $discount;
            $producttiktok->save();
        }
        return response(json_encode(["message" => true, "data" => []]), 200);
    }
    public function editallproductflashdeal(Request $request){
        $producttiktoklists = $request->producttiktoklist;
        $discount = $request->discount;
        foreach($producttiktoklists as $producttiktoklist){
            $producttiktok = ProductTiktoks::select('id','discount')->where('id', $producttiktoklist)->first();
            // dd($producttiktok);
            $producttiktok->discount = $discount;
            $producttiktok->save();
        }
        return response(json_encode(["message" => true, "data" => []]), 200);
    }
    public function sync_product_flashdeal(Request $request)
    {
        // dd($request->all());
        syncFlashDealProductJob::dispatch($request->store_id, $request->flashdeal_id)->onQueue('sync-product-flashdeal');
        return response(json_encode(["message" => true, "data" => []]), 200);

    }
    public function postproductflashdeals(Request $request)
    {
        // $store_id, $activity_id, $remote_id, $discount, $quantity_limit, $quantity_per_user

        $store_id = $request->store_id;
        $activity_id = $request->activity_id; // 7426262189666928427
        $remote_ids = $request->remote_ids; //1729715265519981146
        $quantity_limit = $request->quantity_limit;
        $quantity_per_user = $request->quantity_per_user;
        // dd($store_id, $activity_id, $remote_ids, $discount, $quantity_limit, $quantity_per_user);
        foreach ($remote_ids as $remote_id) {
            
            $producttiktoks = ProductTiktoks::select('discount')->where('remote_id', $remote_id)->first();
            if($producttiktoks){
                $producttiktoks->is_flashdeal = 1;
                $producttiktoks->save();
                
                $productFlashdeals = ProductFlashdeals::updateOrCreate([
                    'product_id' => $remote_id,
                ], [
                    'flashdeal_id' => $activity_id,
                    'quantity_limit' => $quantity_limit,
                    'quantity_per_user' => $quantity_per_user,
                    'discount' =>  $producttiktoks->discount,
                    'total_sku' => 0,
                    'success' => 0,
                    'message' => '',
                ]);
                addProductFlashdealjob::dispatch($store_id, $activity_id, $remote_id, $producttiktoks->discount, $quantity_limit, $quantity_per_user)->onQueue('add-product-to-flashdeals');
            }            
        }
        return response(json_encode(["message" => true, "data" => []]), 200);

    }
    public function repostproductflashdeals(Request $request)
    {
        $ids = $request->ids;
        if (is_array($ids)) {
            $product_flashdeals = ProductFlashdeals::whereIn('id', $ids)->get();
        } else {
            $product_flashdeals = ProductFlashdeals::whereIn('id', [$ids])->get();
        }
        // dd($ids);
        // dd($product_flashdeals);
        foreach ($product_flashdeals as $product_flashdeal) {
            $store_id = $request->store_id;
            $activity_id = $product_flashdeal->flashdeal_id; // 7426262189666928427
            $remote_id = $product_flashdeal->product_id; //1729715265519981146
            $discount = $product_flashdeal->discount;
            $quantity_limit = $product_flashdeal->quantity_limit;
            $quantity_per_user = $product_flashdeal->quantity_per_user;
            // dd($store_id, $activity_id, $remote_id, $discount,$quantity_limit, $quantity_limit);
            addProductFlashdealjob::dispatch($store_id, $activity_id, $remote_id, $discount, $quantity_limit, $quantity_per_user)->onQueue('add-product-to-flashdeals');
        }
        return response(json_encode(["message" => true, "data" => []]), 200);

    }
    public function ajaxdetail(Request $request, $id)
    {
        $flashdealproduct = ProductFlashdeals::find($id);
        // $skus = json_decode($flashdealproduct->skus)??[];
        // $totalAmount = 0;

        // foreach ($skus as $sku) {
        //     $totalAmount += (float) $sku->activity_price->amount;
        // }
        // if($totalAmount==0){
        $discount = $flashdealproduct->discount;
        // }else{
        //     $discount = calPercentProduct($totalAmount, $flashdealproduct->product_id);            
        // }
        return view('flashdeals.show.ajax.index', compact('flashdealproduct', 'discount'));
    }
    public function changeStatusFld(Request $request, $id)
    {
        $flashDeals = FlashDeals::find($id);
        $flashDeals->auto = $request->status;
        $flashDeals->save();
        return response(json_encode(["message" => true, "data" => []]), 200);
    }
    public function changeRenewFld(Request $request, $id)
    {
        $flashDeals = FlashDeals::find($id);
        $flashDeals->renew = $request->status;
        $flashDeals->save();
        return response(json_encode(["message" => true, "data" => []]), 200);
    }
    public function countproductshop(Request $request, $store_id)
    {
        $total = ProductTiktoks::where('store_id', $store_id)->count();
        return response(json_encode(["total" => $total]), 200);
    }
    public function deactiveflashdeal(Request $request, $id)
    {
        try {
            $store = Store::find($request->store_id);
            $activity_id = $id;
            $flashdeal = FlashDeals::where('activity_id', $activity_id)->first();
            $status = "DEACTIVATED";
            $tiktok = (new ConnectAppPartnerService())->connectAppPartnerPostProduct($store)['client'];
            if ($status == "DEACTIVATED") {
                $promotion = $tiktok->Promotion->deactivateActivity($activity_id);
                if (!isset($deleteproduct[0])) {
                    $flashdeal->renew = 0;
                    $flashdeal->status_fld = $status;
                    $flashdeal->save();
                    return response(json_encode(["message" => true, "data" => []]), 200);
                }
            }
            //code...
        } catch (\Throwable $th) {
            return response(json_encode(["message" => true, "data" => $th->getMessage()]), 404);
        }


    }
    public function changediscountproduct(Request $request)
    {
        $flashdeal = ProductTiktoks::where('id', $request->id)->first();
        $flashdeal->discount = $request->discount;
        $flashdeal->save();
        return response(json_encode(["message" => true, "data" => []]), 200);
    }
    public function fldrenew(Request $request)
    {
        $stores = Store::select('id', 'name_flashdeal')->where('create_flashdeal', 1)->get();
        return view('flashdeals.extention', compact('stores'));
    }
    public function changepriority(Request $request)
    {
        $flashdealproduct = ProductFlashdeals::find($request->id);
        // dd();
        if($flashdealproduct){
            $flashdealproduct->priority = $request->checked=="true"?1:0;
            $flashdealproduct->save();
            return response(json_encode(["message" => true, "data" => []]), 200);
        }
        return response(json_encode(["message" => true, "data" => ""]), 404);
    }
}
