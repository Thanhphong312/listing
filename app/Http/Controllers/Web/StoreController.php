<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\Store\Store;
use Vanguard\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vanguard\Http\Requests\Store\EditStoreRequest;
use Vanguard\Jobs\SyncStoreSupover;
use Vanguard\Models\Meta;
use Vanguard\Models\PartnerApp;
use Vanguard\Role;
use EcomPHP\TiktokShop\Client as TiktokApiClient;

class StoreController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Store::query();
        $user = Auth::user();
        $role = $user->role->name;
        if ($role == 'Seller') {
            $query->where('user_id', $user->id);
        }
        if ($role == 'Staff') {
            $query->where('staff_id', $user->id);
        }
        if (isset($request->staff_id) && !empty($request->staff_id)) {
            $query->where('staff_id', $request->staff_id);
        }
        if (isset($request->seller_id) && !empty($request->seller_id)) {
            $query->where('user_id', $request->seller_id);
        }
        if (isset($request->shop_code) && !empty($request->shop_code)) {
            $query->where('shop_code', $request->shop_code);
        }
        if (isset($request->name) && !empty($request->name)) {
            $query->where('name', 'like', "%" . $request->name . "%");
        }
        if (isset($request->store_id) && !empty($request->store_id)) {
            $query->where('id', $request->store_id);
        }
        if(isset($request->status_e)&&!empty($request->status_e)){
            if($request->status_e=='active'){
                $query->where('status', 1);
            }else{
                $query->where('status', 0);
            }
        }else{
            $query->where('status', 1);
        }
        if(isset($request->is_flashdeal)&&!empty($request->is_flashdeal)){
            $query->where('create_flashdeal', $request->is_flashdeal);
        }

        $stores = $query->paginate(20);
        return view('stores.index', compact('stores', 'role', 'request'));
    }

    public function ajax(Request $request, $id)
    {
        $store = Store::find($id);
        return view('stores.ajax.index', compact('store'));
    }

    public function add(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->name;
        if ($request->isMethod('post')) {
            // dd($request->all());
            $store = new Store();
            $store->name = $request->name_add;
            $store->sup_store_id = $request->sup_store_id_add;
            $store->partner_id = $request->partner_add;
            if ($role == 'Seller' || $role == 'Admin') {
                $store->staff_id = $request->staff_add;
                $store->user_id = $request->seller_add;
            } else {
                $store->user_id = $request->seller_add;
            }
            $store->keyword = $request->keyword_add;
            $store->watermark = $request->watermark_add;
            $store->name_flashdeal = $request->name_flashdeal_add;
            $store->type = 1;
            $store->status = 1;
            $store->shop_code = $request->order_code_add;
            $rs = $store->save();
            $meta = Meta::updateOrCreate([
                'key' => 'access_token',
                'store_id' => $store->id
            ], [
                'value' => $request->access_token_edit
            ]);

            $meta = Meta::updateOrCreate([
                'key' => 'refresh_token',
                'store_id' => $store->id
            ], [
                'value' => $request->refresh_token_add,
                'user_id' => null
            ]);
            // dd($listColors);
            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }

        $partners = PartnerApp::query();
        if ($role == 'Seller') {
            $partners->where('seller_id', $user->id);
        }
        $partners = $partners->get();
        return view('stores.add.index', compact('partners', 'user', 'role'));
    }
    public function view(Request $request, $id)
    {
        $store = Store::find($id);
        $access_token = Meta::where('store_id', $store->id)->where('meta_key', 'access_token')->first();
        return view('stores.show.index', compact('store', 'access_token'));
    }
    public function edit(Request $request, $id)
    {
        $store = Store::find($id);
        $user = Auth::user();
        $role = $user->role->name;
        if ($request->isMethod('post')) {
            $store->name = $request->name_edit;
            $store->sup_store_id = $request->sup_store_id_edit;
            $store->partner_id = $request->partner_edit;
            if ($role == 'Seller' || $role == 'Admin') {
                $store->staff_id = $request->staff_edit;
                $store->user_id = $request->seller_edit;
            } else {
                $store->staff_id = $request->staff_edit;
            }
            $store->keyword = $request->keyword_edit;
            $store->watermark = $request->watermark_edit;
            $store->name_flashdeal = $request->name_flashdeal_edit;
            $store->type = 1;
            $store->status = $request->status_edit;
            $store->shop_code = $request->order_code_edit;

            $rs = $store->save();

            $meta = Meta::updateOrCreate([
                'key' => 'access_token',
                'store_id' => $store->id
            ], [
                'value' => $request->access_token_edit,
                'user_id' => null
            ]);

            $meta = Meta::updateOrCreate([
                'key' => 'refresh_token',
                'store_id' => $store->id
            ], [
                'value' => $request->refresh_token_edit,
                'user_id' => null
            ]);
            // dd($listColors);
            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }

        // dd($store);
        $partners = PartnerApp::query();
        if ($role == 'Seller') {
            $partners->where('seller_id', $user->id);
        }
        $partners = $partners->get();
        $access_token = Meta::where('store_id', $store->id)->where('key', 'access_token')->first()?->value;
        $refresh_token = Meta::where('store_id', $store->id)->where('key', 'refresh_token')->first()?->value;
        return view('stores.edit.index', compact('store', 'access_token', 'refresh_token', 'partners', 'role', 'user'));
    }
    public function syncStoreSupover(Request $request, $id)
    {
        $store = Store::find($id);
        $sup_store_id = $store->shop_code;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://ai.supover.com/api/store/info?shop_code='.$sup_store_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Optional: Set headers if needed
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        
        $response = curl_exec($ch);
        $data = json_decode($response);
        // dd($data);
        $meta = Meta::updateOrCreate([
            'key' => 'access_token',
            'store_id' => $store->id
        ],[
            'value' => $data->access_token
        ]);

        $meta = Meta::updateOrCreate([
            'key' => 'refresh_token',
            'store_id' => $store->id
        ],[
            'value' => $data->refresh_token,
        ]);
        
        $meta = Meta::updateOrCreate([
            'key' => 'access_token_expire',
            'store_id' => $store->id
        ],[
            'value' => $data->access_token_expire,
        ]);

        $meta = Meta::updateOrCreate([
            'key' => 'refresh_token_expire',
            'store_id' => $store->id
        ],[
            'value' => $data->refresh_token_expire
        ]);
        return response(json_encode(["message" => true, "data" => []]), 200);
    }
    public function delete(Request $request)
    {
        $design = Store::find($request->id);

        if ($design) {
            $design->delete();
        }
        return redirect()->route('stores.index');
    }

    public function syncName($id)
    {
        $store = Store::find($id);

        $storeMetas = Meta::where('store_id', $store->id)->get();
        $access_token_expire = "";
        $refresh_token_expire = "";
        foreach ($storeMetas as $storeMeta) {

            if ($storeMeta->key == 'access_token') {
                $access_token = $storeMeta->value;
            }
            if ($storeMeta->key == 'refresh_token') {
                $refresh_token = $storeMeta->value;
            }
            if ($storeMeta->key == 'access_token_expire') {
                $access_token_expire = date('Y-m-d H:i:s', $storeMeta->value);
            }
            if ($storeMeta->key == 'refresh_token_expire') {
                $refresh_token_expire = date('Y-m-d H:i:s', $storeMeta->value);
            }
        }

        $appPartner = PartnerApp::find($store->partner_id);
        if (!empty($appPartner)) {
            $app_key = $appPartner->app_key;
            $app_secret = $appPartner->app_secret;
            $proxy = $appPartner->proxy;
            if (!empty($proxy)) {
                $proxyParts = explode(':', $proxy);
                $proxyAddress = $proxyParts[0];
                $proxyPort = $proxyParts[1];
                $proxyUsername = $proxyParts[2];
                $proxyPassword = $proxyParts[3];
            } else {
            }

            $client = new TiktokApiClient($app_key, $app_secret, ['proxy' => 'http://' . $proxyUsername . ':' . $proxyPassword . '@' . $proxyAddress . ':' . $proxyPort]);
            $auth = $client->auth();
            $nowTime = now();

            if (!empty($access_token_expire) && !empty($refresh_token_expire) && $access_token_expire < $nowTime || $refresh_token_expire < $nowTime) {
                $dataNewToken = $auth->refreshNewToken($refresh_token);
                
                $store->open_id = $dataNewToken['open_id'];
                $store->save();

                $meta = Meta::updateOrCreate([
                    'key' => 'access_token',
                    'store_id' => $store->id
                ], [
                    'value' => $dataNewToken['access_token']
                ]);

                $meta = Meta::updateOrCreate([
                    'key' => 'refresh_token',
                    'store_id' => $store->id
                ], [
                    'value' => $dataNewToken['refresh_token'],
                ]);

                $meta = Meta::updateOrCreate([
                    'key' => 'access_token_expire',
                    'store_id' => $store->id
                ], [
                    'value' => $dataNewToken['access_token_expire_in'],
                ]);

                $meta = Meta::updateOrCreate([
                    'key' => 'refresh_token_expire',
                    'store_id' => $store->id
                ], [
                    'value' => $dataNewToken['refresh_token_expire_in']
                ]);

                $access_token = $dataNewToken['access_token'];
                
                $client->setAccessToken($access_token);
                $authorizedShopList = $client->Authorization->getAuthorizedShop();
                $shop_cipher = $authorizedShopList['shops'][0]['cipher'];
                $client->setShopCipher($shop_cipher);
            }
            $client->setAccessToken($access_token);
            $authorizedShopList = $client->Authorization->getAuthorizedShop();

            $newName = null;
            if ($store->name != $authorizedShopList['shops'][0]['name'] && isset($authorizedShopList['shops'][0]['name'])) {
                $newName = $authorizedShopList['shops'][0]['name'];
                $store->name = $newName;
                $store->save();
            }
        }
        return response(json_encode(["message" => true, "data" => $newName]), 200);
    }
    public function changeStatus(Request $request, $id){
        $store = Store::find($id);
        if(isset($request->status)){
            $status = $request->status;
            $store->status = $status;
            $store->save();
            return response(json_encode(["message"=> true,"data"=> $status]),200);
        }
        return response(json_encode(["message"=> false,"data"=> ""]));
    }
    public function changeCron(Request $request, $id){
        $store = Store::find($id);
        if(isset($request->status)){
            $status = $request->status;
            $store->cron = $status;
            $store->save();
            return response(json_encode(["message"=> true,"data"=> $status]),200);
        }
        return response(json_encode(["message"=> false,"data"=> ""]));
    }
}
