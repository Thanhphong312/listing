<?php

use Vanguard\Models\ProductFlashdeals;
use Vanguard\Models\ProductTiktoks;
use Vanguard\Models\StaffTemplate;
use Vanguard\Models\StoreProducts;
use Vanguard\Models\UserTeams;
use Vanguard\Product;
use Vanguard\ProductVariants;
use Vanguard\User;
use Vanguard\Models\Order\Order;
use Vanguard\Models\Order\OrderItem;
use Vanguard\Models\Store\Store;
use Illuminate\Support\Facades\Auth;
use Vanguard\Models\Ideas;
use Vanguard\Models\Colors;

if (!function_exists('settings')) {
    /**
     * Get / set the specified settings value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function settings($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('anlutro\LaravelSettings\SettingStore');
        }

        return app('anlutro\LaravelSettings\SettingStore')->get($key, $default);
    }
}

function getIdeas()
{
    return Ideas::get();
}
function listStoreReport()
{
    if(Auth::user()->role->id==5||Auth::user()->role->id==3){
        return Store::select('id', 'name','shop_code')
            ->where('user_id', Auth::user()->id)
            ->orWhere('staff_id', Auth::user()->id)
            ->get()->toArray();
    }
    return Store::select('id', 'name','shop_code')->get()->toArray();
}
function listSeller()
{
    return User::where('role_id', 3)->orwhere('role_id', 1)->select('id', 'username')->get()->toArray();
}
function listStaff()
{
    if (Auth::user()->role->name == 'Seller') {
        return User::where('role_id', 5)
            ->where('parent_id', Auth::user()->id)
            ->select('id', 'username')
            ->get()
            ->toArray();
    } else {
        return User::where('role_id', 5)->select('id', 'username')->get()->toArray();
    }
}
function convertColor($name)
{
    // Đường dẫn tới file JSON
    $jsonPath = public_path('assets/json/colors.json');

    // Đọc nội dung file JSON
    $jsonContent = file_get_contents($jsonPath);

    // Giải mã nội dung JSON thành mảng
    $colors = json_decode($jsonContent, true);
    // dd($colors);
    // Mảng chứa kết quả
    $result = "";

    // Duyệt qua từng nhóm màu
    foreach ($colors as $group => $colorList) {
        // Duyệt qua từng màu trong danh sách của mỗi nhóm
        foreach ($colorList as $color) {
            // Kiểm tra nếu 'name' của màu khớp với tham số đầu vào
            if (strtolower($color['name']) == strtolower($name)) {
                $result = $color['hex'];
            }
        }
    }
    // Trả về kết quả tìm kiếm
    return $result;
}
function getNameTypeImage($id)
{
    $list = ["Size Chart"];
    return $list[$id - 1] ?? "";
}
function getUsernameById($id)
{
    return User::where('id', $id)->first()?->username;
}

function getStoreNameById($id)
{
    return Store::where('id', $id)->first()?->name;
}
function getProductNameById($id)
{
    return ProductTiktoks::where('remote_id', $id)->first()?->title;
}
function calPercentProduct($priceactive, $product_id)
{
    try {
        $product = ProductTiktoks::where('remote_id', 'like', '%' . $product_id . '%')->first();

        $skus = json_decode($product->skus, true);

        $totalAmount = 0;

        foreach ($skus as $sku) {
            $totalAmount += (float) $sku['price'];
        }
        // dd($totalAmount);
        $percentDifference = (($totalAmount - $priceactive) / $totalAmount) * 100;

        return round($percentDifference, 0);
    } catch (\Throwable $th) {
        return 0;
    }

}

function checkFlashdealProduct($activity_id, $product_id)
{
    $productFlashdeals = ProductFlashdeals::where('flashdeal_id', $activity_id)
        ->where('product_id', $product_id)
        ->first();

    return $productFlashdeals ? true : false;
}
function getbtnfld($status_fld)
{
    // - DRAFT: Promotion activities with this status are not available to TikTok users.
    // - NOT_START: Promotion activities with this status are not available to TikTok users until the set activity start time.
    // - ONGOING: Promotion activities with this status are available to TikTok users.
    // - EXPIRED: Promotion activities with this status are not available to TikTok users because it has expired.
    // - DEACTIVATED: The activity has been deactivated by the seller and is not available to TikTok users.
    // - NOT_EFFECTIVE:  The activity is terminated by the platform and is not available to TikTok users.
    $btn = "btn-success";
    switch ($status_fld) {
        case 'ONGOING':
            $btn = "btn-success";
            break;
        case 'EXPIRED':
            $btn = "btn-secondary";
            break;
        case 'DEACTIVATED':
            $btn = "btn-danger";
            break;
        case 'DRAFT':
            $btn = "btn-warning";
            break;
        case 'NOT_START':
            $btn = "btn-info";
            break;
        case 'NOT_EFFECTIVE':
            $btn = "btn-light";
            break;
        default:

            break;
    }
    return $btn;
}

function niches(){
    return [
        'FMP', 'STW', 'DRK', 'QUT', 'POL', 'SPT', 'CRT', 'ANM', 'FUN', 'FAM', 'NAT', 'ANE', 'MOV', 'VEH',
         'WET','HLW', 'CHR', 'LIB', 'TGC', 'THK', 'HOL', 'FOD', 'LBR', 'EDU', 'GEM', 'SES', 'REG'
    ];
}

function mixs(){
    return [
         'HLW', 'THK', 'CHR', 'CLN', 'NON'
    ];
}

function listStatusFld()
{
    return ["ONGOING", "EXPIRED", "DEACTIVATED", "DRAFT", "NOT_START", "NOT_EFFECTIVE"];
}
function getColorByHex($hex)
{
    return Colors::where('hex', $hex)->first()->name ?? "";
}

function listStore()
{
    if (Auth::user()->role->name == 'Seller') {
        return Store::select('id', 'name')
            ->where('user_id', Auth::user()->id)
            ->get()
            ->toArray();
    } else if (Auth::user()->role->name == 'Staff') {
        return Store::select('id', 'name')
            ->where('staff_id', Auth::user()->id)
            ->get()
            ->toArray();
    } else {
        return Store::select('id', 'name')->get()->toArray();
    }
}
function checkaccept($user_id, $id)
{
    $staffTemplate = StaffTemplate::select(['id'])->where("user_id", $user_id)->where('template_id', $id)->first() ?? null;
    return ($staffTemplate) ? 1 : 0;
}
function checkacceptTeam($user_id, $id)
{
    $staffTemplate = User::select(['id'])->where("id", $user_id)->where('team_id', $id)->first() ?? null;
    return ($staffTemplate) ? 1 : 0;
}
function countStaff($id)
{
    $staffTemplate = StaffTemplate::select(['id'])->where('template_id', $id)->count() ?? 0;
    return ($staffTemplate);
}
function countStore($product_id)
{
    return StoreProducts::select('product_id')->where('product_id', $product_id)->count() ?? 0;
}

function getAllSkusTiktok($store_id)
{
    // Fetch data
    $producttiktoks = ProductTiktoks::select('skus')->whereNotNull('discount')
        ->whereDoesntHave('flashdealproduct')
        ->where('store_id', $store_id)
        ->get();
    $totalSkus = 0;
    foreach ($producttiktoks as $producttiktok) {
        if ($producttiktok) {
            // dd(count(json_decode($producttiktok->skus)));
            $totalSkus += count(json_decode($producttiktok->skus));
        }
    }
    return $totalSkus;
}

