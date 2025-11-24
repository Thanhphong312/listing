<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\Meta;

class MetaController extends Controller
{
    public function update(Request $request) {
            $store_id = $request->store_id;
            $access_token = $request->access_token; 
            $refresh_token = $request->refresh_token; 
            $access_token_expire = $request->access_token_expire; 
            $refresh_token_expire = $request->refresh_token_expire; 

            try {
                Meta::updateOrCreate([
                    'key' => 'access_token',
                    'store_id' => $store_id
                ],[
                    'value' => $access_token
                ]);

                Meta::updateOrCreate([
                    'key' => 'refresh_token',
                    'store_id' => $store_id
                ],[
                    'value' => $refresh_token
                ]);

                Meta::updateOrCreate([
                    'key' => 'access_token_expire',
                    'store_id' => $store_id
                ],[
                    'value' => $access_token_expire
                ]);

                Meta::updateOrCreate([
                    'key' => 'refresh_token_expire',
                    'store_id' => $store_id
                ],[
                    'value' => $refresh_token_expire
                ]);
    
                return response()->json([
                    'success' => true,
                    'message' => 'Update successful'
                ])->send();

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Update failed: ' . $e->getMessage()
                ], 500)->send();
            }

    }
}
