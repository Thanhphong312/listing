<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\PartnerApp;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PartnerAppController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->name;
        $query = PartnerApp::query();
        if($role=='Seller'){
            $query->where('seller_id', $user->id);
        }
        if($role=='Staff'){
            $query->where('staff_id', $user->id);
        }
        $partnerapps = $query->paginate(20);
        return view('partnerapps.index', compact('partnerapps','role'));
    }
    public function add(Request $request){
         $user = Auth::user();
        $role = $user->role->name;
        if ($request->isMethod('post')) {
            $partnerApp = new PartnerApp();
            $partnerApp->app_name = $request->name_add;
            $partnerApp->app_key = $request->key_add;
            $partnerApp->app_secret = $request->secret_add;
            $partnerApp->proxy = $request->proxy_add;
            $partnerApp->auth_link = $request->auth_link_add;
            if( $role!="Staff"){
                $partnerApp->seller_id = $request->seller_add;
            }
            $partnerApp->staff_id = $request->staff_add;
            $partnerApp->status = 1;
            $rs = $partnerApp->save();           
            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
       
        return view('partnerapps.add.index',compact('role','user'));
    }
    public function edit(Request $request, $id){
        $partnerApp = PartnerApp::find($id);
        $user = Auth::user();
        $role = $user->role->name;
        if ($request->isMethod('post')) {
            // 'app_name','app_key','app_secret','auth_link','proxy','seller_id','status','webhook_domain','count_shop_connect'
            $partnerApp->app_name = $request->name_edit;
            $partnerApp->app_key = $request->key_edit;
            $partnerApp->app_secret = $request->secret_edit;
            $partnerApp->auth_link = $request->auth_link_edit;
            $partnerApp->proxy = $request->proxy_edit;
            if( $role!="Staff"){
                $partnerApp->seller_id = $request->seller_edit;
            }
            $partnerApp->staff_id = $request->staff_edit;

            $rs = $partnerApp->save();

            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        
        return view('partnerapps.edit.index', compact('partnerApp', 'user','role'));
    }
    public function delete(Request $request)
    {
        $design = PartnerApp::find($request->id);
        if ($design) {
            $design->delete();
        }
        return redirect()->route('partnerapps.index');
    }

    public function checkproxy(Request $request, $id)
    {
        $proxy = PartnerApp::where('id', $id)->value('proxy');

        $parts = explode(':', $proxy);

        if (count($parts) === 4) {
            $ip = $parts[0];
            $port = $parts[1];
            $username = $parts[2];
            $password = $parts[3];

            $proxyString = "$username:$password@$ip:$port";
        } elseif (count($parts) === 2) {
            $ip = $parts[0];
            $port = $parts[1];

            $proxyString = "$ip:$port";
        } else {
            return response()->json(
                ['error' => 'Invalid proxy format',
                 'proxy' => $proxy,
                 'count' => count($parts)
                ],
                400);
        }

        // URL để kiểm tra proxy (httpbin trả về IP)
        $url = 'http://httpbin.org/ip';

        $client = new Client();

        $options = [
            'proxy' => 'http://' . $proxyString,
            'timeout' => 10, // Timeout sau 10 giây
        ];

        if (!empty($username) && !empty($password)) {
            $options['proxy'] = 'http://' . $username . ':' . $password . '@' . $ip . ':' . $port;
        }

        try {
            $response = $client->get($url, $options);

            if ($response->getStatusCode() === 200) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Proxy is working',
                    'response' => json_decode($response->getBody(), true),
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Proxy is not working',
            ]);
        } catch (RequestException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Proxy is not working',
                'error' => $e->getMessage(),
            ]);
        }

    }

}
