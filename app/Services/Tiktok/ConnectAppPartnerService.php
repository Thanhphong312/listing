<?php

namespace Vanguard\Services\Tiktok;

use Exception;
use Illuminate\Support\Facades\Log;
use EcomPHP\TiktokShop\Client as TiktokApiClient;
use Carbon\Carbon;
use App\Models\OverdueExtension;
use App\Models\Notification;
use App\Services\Notification\NotificationService;
use Vanguard\Models\Meta;
use Vanguard\Models\PartnerApp;
use GuzzleHttp\Client;

class ConnectAppPartnerService
{
    // protected $notificationService;
    // public function __construct(NotificationService $notificationService)
    // {
    //     $this->notificationService = $notificationService;
    // }

    private function sendRequest($url, $method, $formdata = null)
    {

        $curl = curl_init();

        $url = str_replace(" ", '%20', $url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $formdata);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            // 'Authorization: Bearer '.$apiKey
        ));
        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            return array("status" => "error", "msg" => "Curl error: " . $error);
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            curl_close($curl);

            return array("status" => "error", "msg" => "HTTP error: " . $httpCode . $response);
        }
        // dd($response);
        curl_close($curl);
        return json_decode($response);
    }

    public function connectAppPartner($store)
    {
        // try{
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
                    $dataConnectAppPartner = [];
                    // dd($access_token);
                    // dd($appPartner)
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
                        // dd($proxy, $proxyUsername . ':' . $proxyPassword . '@' . $proxyAddress . ':' . $proxyPort);
                        $client = new TiktokApiClient($app_key, $app_secret, ['proxy' => 'http://' . $proxyUsername . ':' . $proxyPassword . '@' . $proxyAddress . ':' . $proxyPort]);
                        // dd($client);
                        $auth = $client->auth();
                        $nowTime = now();
                        
                        if(!empty($access_token_expire)&&!empty($refresh_token_expire)&&$access_token_expire < $nowTime || $refresh_token_expire < $nowTime){

                            $dataNewToken = $auth->refreshNewToken($refresh_token);
                            // dd($dataNewToken);
                            $store->open_id = $dataNewToken['open_id'];
                            $store->save();

                            $meta = Meta::updateOrCreate([
                                'key' => 'access_token',
                                'store_id' => $store->id
                            ],[
                                'value' => $dataNewToken['access_token']
                            ]);
                    
                            $meta = Meta::updateOrCreate([
                                'key' => 'refresh_token',
                                'store_id' => $store->id
                            ],[
                                'value' => $dataNewToken['refresh_token'],
                            ]);
                            
                            $meta = Meta::updateOrCreate([
                                'key' => 'access_token_expire',
                                'store_id' => $store->id
                            ],[
                                'value' => $dataNewToken['access_token_expire_in'],
                            ]);
                    
                            $meta = Meta::updateOrCreate([
                                'key' => 'refresh_token_expire',
                                'store_id' => $store->id
                            ],[
                                'value' => $dataNewToken['refresh_token_expire_in']
                            ]);

                            $access_token = $dataNewToken['access_token'];
                            $client->setAccessToken($access_token);
                            $authorizedShopList = $client->Authorization->getAuthorizedShop();
                            $shop_cipher = $authorizedShopList['shops'][0]['cipher'];
                            $client->setShopCipher($shop_cipher);

                            $dataConnectAppPartner += [
                                'client' => $client,
                            ];
                            return $dataConnectAppPartner;

                        }
                        $client->setAccessToken($access_token);
                        $authorizedShopList = $client->Authorization->getAuthorizedShop();
                        if(is_null($store->shop_code)){
                            $store->shop_code = $authorizedShopList['shops'][0]['code'];
                            $store->save();
                        }
                        if(is_null($store->shop_id)){
                            $store->shop_id = $authorizedShopList['shops'][0]['id'];
                            $store->save();
                        }
                        $shop_cipher = $authorizedShopList['shops'][0]['cipher'];
                        $client->setShopCipher($shop_cipher);
                            // dd($client);

                        $dataConnectAppPartner += [
                            'client' => $client,
                        ];
                    } else {
                    }

                    return $dataConnectAppPartner;
        // }catch(Exception $e){
        //     return null;
        // }
        
    }
    public function connectAppPartnerPostProduct($store)
    {
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
        $dataConnectAppPartner = [];
        // dd($access_token);

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
            
            if(!empty($access_token_expire)&&!empty($refresh_token_expire)&&$access_token_expire < $nowTime || $refresh_token_expire < $nowTime){

                $dataNewToken = $auth->refreshNewToken($refresh_token);
                // dd($dataNewToken);
                $store->open_id = $dataNewToken['open_id'];
                $store->save();

                $meta = Meta::updateOrCreate([
                    'key' => 'access_token',
                    'store_id' => $store->id
                ],[
                    'value' => $dataNewToken['access_token']
                ]);
        
                $meta = Meta::updateOrCreate([
                    'key' => 'refresh_token',
                    'store_id' => $store->id
                ],[
                    'value' => $dataNewToken['refresh_token'],
                ]);
                
                $meta = Meta::updateOrCreate([
                    'key' => 'access_token_expire',
                    'store_id' => $store->id
                ],[
                    'value' => $dataNewToken['access_token_expire_in'],
                ]);
        
                $meta = Meta::updateOrCreate([
                    'key' => 'refresh_token_expire',
                    'store_id' => $store->id
                ],[
                    'value' => $dataNewToken['refresh_token_expire_in']
                ]);

                $access_token = $dataNewToken['access_token'];
                $client->setAccessToken($access_token);
                $authorizedShopList = $client->Authorization->getAuthorizedShop();
                $shop_cipher = $authorizedShopList['shops'][0]['cipher'];
                $client->setShopCipher($shop_cipher);

                $dataConnectAppPartner += [
                    'client' => $client,
                ];
                return $dataConnectAppPartner;

            }
            $client->setAccessToken($access_token);
            $authorizedShopList = $client->Authorization->getAuthorizedShop();
            if(is_null($store->shop_code)){
                $store->shop_code = $authorizedShopList['shops'][0]['code'];
                $store->save();
            }
            if(is_null($store->shop_id)){
                $store->shop_id = $authorizedShopList['shops'][0]['id'];
                $store->save();
            }
            $shop_cipher = $authorizedShopList['shops'][0]['cipher'];
            $client->setShopCipher($shop_cipher);

            $dataConnectAppPartner += [
                'client' => $client,
            ];
        } else {
        }

        return $dataConnectAppPartner;
    }
    public function connectAppPartnerPostProductTMP($store)
    {
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
        $dataConnectAppPartner = [];
        // dd($access_token);

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
            
            if(!empty($access_token_expire)&&!empty($refresh_token_expire)&&$access_token_expire < $nowTime || $refresh_token_expire < $nowTime){

                $dataNewToken = $auth->refreshNewToken($refresh_token);
                // dd($dataNewToken);
                $store->open_id = $dataNewToken['open_id'];
                $store->save();

                $meta = Meta::updateOrCreate([
                    'key' => 'access_token',
                    'store_id' => $store->id
                ],[
                    'value' => $dataNewToken['access_token']
                ]);
        
                $meta = Meta::updateOrCreate([
                    'key' => 'refresh_token',
                    'store_id' => $store->id
                ],[
                    'value' => $dataNewToken['refresh_token'],
                ]);
                
                $meta = Meta::updateOrCreate([
                    'key' => 'access_token_expire',
                    'store_id' => $store->id
                ],[
                    'value' => $dataNewToken['access_token_expire_in'],
                ]);
        
                $meta = Meta::updateOrCreate([
                    'key' => 'refresh_token_expire',
                    'store_id' => $store->id
                ],[
                    'value' => $dataNewToken['refresh_token_expire_in']
                ]);

                $access_token = $dataNewToken['access_token'];
                $client->setAccessToken($access_token);
                $authorizedShopList = $client->Authorization->getAuthorizedShop();
                $shop_cipher = $authorizedShopList['shops'][0]['cipher'];
                $client->setShopCipher($shop_cipher);

                $dataConnectAppPartner += [
                    'client' => $client,
                ];
                return $dataConnectAppPartner;

            }
            $client->setAccessToken($access_token);
            $authorizedShopList = $client->Authorization->getAuthorizedShop();
            if(is_null($store->shop_code)){
                $store->shop_code = $authorizedShopList['shops'][0]['code'];
                $store->save();
            }
            if(is_null($store->shop_id)){
                $store->shop_id = $authorizedShopList['shops'][0]['id'];
                $store->save();
            }
            if($store->name != $authorizedShopList['shops'][0]['name']&&isset($authorizedShopList['shops'][0]['name'])){
                $store->name = $authorizedShopList['shops'][0]['name'];
                $store->save();
                echo "update store name".$store->id."<br>";
            }
            $shop_cipher = $authorizedShopList['shops'][0]['cipher'];
            $client->setShopCipher($shop_cipher);

            $dataConnectAppPartner += [
                'client' => $client,
            ];
        } else {
        }

        return $dataConnectAppPartner;
    }
    public function getProduct($store_id, $remote_id){
        $client = $this->connectAppPartnerPostProduct($store_id)['client'];
        return $client->Product->getProduct($remote_id);
    }
    public function sendRequestFlashdeal($url, $method)
    {
        return self::sendRequest($url, $method);
    }

    public function postOrderJob($data)
    {
        $url = 'https://jobs.supover.com/api/job-tiktok-order-new';
        $method = 'POST';
        return self::sendRequest($url, $method, $data);
    }

    public function overdueExtension($orderTiktokId, $data)
    {
        $overdueOrder = OverdueExtension::where('order_tiktok_id', $orderTiktokId)->first();
        if ($overdueOrder) {
            $overdueOrder->update($data);
        } else {
            $data['order_tiktok_id'] = $orderTiktokId;
            $overdueOrder = OverdueExtension::create($data);
        }
    }

    public function ConnectWithoutModel(
        $store_id,
        $access_token, 
        $refresh_token, 
        $access_token_expire = null, 
        $refresh_token_expire = null, 
        $app_key, 
        $app_secret, 
        $proxy = null
    ) {
        $dataConnectAppPartner = [];

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

        if(!empty($access_token_expire)&&!empty($refresh_token_expire)&&$access_token_expire < $nowTime || $refresh_token_expire < $nowTime){
            $dataNewToken = $auth->refreshNewToken($refresh_token);

            //POST CURL
            $clientHttp = new Client([
                'timeout' => 0.5,
            ]);
            $url = route('metas.update');
            try {
                $response = $clientHttp->post($url, [
                    'form_params' => [
                        'store_id' => $store_id,
                        'access_token' => $dataNewToken['access_token'],
                        'refresh_token' => $dataNewToken['refresh_token'],
                        'access_token_expire' => $dataNewToken['access_token_expire_in'],
                        'refresh_token_expire' => $dataNewToken['refresh_token_expire_in'],
                    ],
                ]);
            } catch(ConnectException $e) {
                \Log::error('Connection Error', [
                    'message' => $e->getMessage()
                ]);
            } catch(RequestException $e) {
                if ($e->hasResponse()) {
                    $response = $e->getResponse();
                    $errorBody = json_decode($response->getBody(), true);
                    
                    \Log::error('Request Error', [
                        'status' => $response->getStatusCode(),
                        'body' => $errorBody
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Complete Request Error', [
                    'message' => $e->getMessage(),
                    'class' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            $access_token = $dataNewToken['access_token'];
            $client->setAccessToken($access_token);

            $authorizedShopList = $client->Authorization->getAuthorizedShop();
            $shop_cipher = $authorizedShopList['shops'][0]['cipher'];
            $client->setShopCipher($shop_cipher);

            $dataConnectAppPartner += [
                'client' => $client,
            ];
            return $dataConnectAppPartner;
        }
        $client->setAccessToken($access_token);
        $authorizedShopList = $client->Authorization->getAuthorizedShop();
        $shop_cipher = $authorizedShopList['shops'][0]['cipher'];
        $client->setShopCipher($shop_cipher);

        $dataConnectAppPartner += [
            'client' => $client,
        ];

        return $dataConnectAppPartner;
    }
}