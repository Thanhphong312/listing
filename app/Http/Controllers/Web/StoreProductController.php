<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Jobs\DeleteProductJob;
use Vanguard\Jobs\PostProductToTiktokShop;
use Vanguard\Jobs\PostProductToTiktokShop2;
use Illuminate\Queue\SerializesModels;
use Vanguard\Models\ProductFlashdeals;
use Vanguard\Models\ProductTiktoks;
use Vanguard\Models\Store\Store;
use Vanguard\Models\StoreProducts;
use Vanguard\Services\Tiktok\ConnectAppPartnerService;
use Vanguard\Product;

class StoreProductController extends Controller
{
    public function index(Request $request)
    {
        $query = StoreProducts::query();
        $storeproducts = $query->orderBy('id','desc')->paginate(20);
        return view('storeproducts.index', compact('storeproducts'));
    }
    public function show(Request $request, $id)
    {
        $store = Store::select('name','id')->find($id);
        $query = StoreProducts::where('store_id',$id);
        if(isset($request->remote_id)&&!empty($request->remote_id)){
            $query->where('remote_id', $request->remote_id);
        }
        if (isset($request->name) && !empty($request->name)) {
            // Assuming the 'data' field is a JSON column and contains a 'title' key
            $query->where('data->product->title', 'like', '%' . $request->name . '%');
        }
        $user = Auth::user();
        $role = $user->role->name;
        if($role=='Seller'){
            $query->whereHas('store', function($querystore) use ($user){
                $querystore->where('user_id', $user->id);
            });
        }
        if($role=='Staff'){
            $query->whereHas('store', function($querystore)  use ($user){
                $querystore->where('staff_id', $user->id);
            });
        }
        $storeproducts = $query->orderBy('id','desc')->paginate(20);
        return view('storeproducts.show.index', compact('storeproducts','store','request'));
    }
    public function postToTiktok(Request $request){
        $ids = $request->ids;

        foreach($ids as $id){
            $productStore = StoreProducts::find($id);
            $product = Product::where('id',$productStore->product_id)->first();
            
            PostProductToTiktokShop::dispatch($id, $product->discount)->delay(2)->onQueue('post-product-to-tiktok');
            
        }
    }
    // public function deleteProductTiktok(Request $request){
    //     $store_id = $request->store_id;
    //     $ids = $request->ids;
    //     foreach($ids as $id){
    //         DeleteProductJob::dispatch($store_id, $id)->delay(2)->onQueue('delete-product-tiktok');
    //     }
    // }
    public function deleteProductTiktok(Request $request)
    {
        $store_id = $request->store_id;
        $ids = $request->ids;
       
        foreach ($ids as $id) {
            $store = Store::find($store_id);
            if (!$store) {
                return;
            }
            $productStores = StoreProducts::select('id', 'remote_id')->where('remote_id', $id)->get();
            $tiktok = (new ConnectAppPartnerService())->connectAppPartner($store)['client'];
            foreach ($productStores as $productStore) {
                if ($productStore->remote_id) {
    
                    $deleteProduct = $tiktok->Product->deleteProducts([$productStore->remote_id]);
    
                    if (!isset($deleteProduct[0])) {
                        $productStore->delete();
                        $productTiktoks = ProductTiktoks::where('remote_id', $id)->get();
                        foreach ($productTiktoks as $productTiktok) {
                            $productTiktok->delete();
                        }
    
                        $productFlashdeals = ProductFlashdeals::where('product_id', $id)->get();
                        foreach ($productFlashdeals as $productFlashdeal) {
                            $productFlashdeal->delete();
                        }
                    }  else {
                    }
                } else {
                    foreach ($productStores as $productStore) {
                        $productStore->delete();
                    }
                }
            }
        }
    }
    public function syncQualityProduct(Request $request)
    {
        $store = Store::find($request->store_id);
        $storetiktok = (new ConnectAppPartnerService())->connectAppPartnerPostProduct($store)['client'];
        $storetiktok->useVersion(202309);
        
        $storeproduct = StoreProducts::find($request->store_product_id);

        $product = $storetiktok->Product->getproduct($storeproduct->remote_id);
        
        $storeproduct->quality = $product['listing_quality_tier'] ?? "POOR";
        $storeproduct->status = $product['status'];
        $storeproduct->save();
        return response(json_encode(["message" => true, "data" => [$product['listing_quality_tier'] ?? "POOR", $product['status']]]), 200);
    }
    public function checkproductlisting(Request $request){
        $store_id = $request->store_id;
        $remote_id = $request->remote_id;
        $store = Store::find($store_id);
        $storetiktok = (new ConnectAppPartnerService())->connectAppPartnerPostProduct($store)['client'];
        $storetiktok->useVersion(202405);
        $diagnoses = $storetiktok->Product->checkpPoductListing([
            'product_ids'=>$remote_id
        ])['products'][0]['diagnoses'];
        // return $product['product'];
        if(count($diagnoses)){
            dd($diagnoses);
        }else{
            return "";
        }
    }
}
