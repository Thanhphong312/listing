<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Vanguard\User;

class UserLoginController extends Controller
{
    public function userLogin(Request $request){
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *'); // Allow CORS
        $username = $request->username;
        $check = User::where('username', $username)->first();
        if($check){
            //check 
            return json_encode(['status' => 'success', 'message' => $$check->username]);
        }else{
            return json_encode(['status' => 'error', 'message' => 'User not logged in']);
        }
    }
}
