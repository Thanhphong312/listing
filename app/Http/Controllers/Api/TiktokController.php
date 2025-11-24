<?php

namespace Vanguard\Http\Controllers\Api;

use EcomPHP\TiktokShop\Client;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use App\Services\Tiktok\ConnectAppPartnerService;

class TiktokController extends Controller
{
    public function test(){

        $app_key = '6cmmsps58uksg';
        $app_secret = '0724e28d875fb6451001cca72f42e51945b0349b';

        $client = new Client($app_key, $app_secret);
        dd($timestamp = time());
    }
}
