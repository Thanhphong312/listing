<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

class DesignMetaController extends Controller
{
    public function index(Request $request){
        return view('designmetas.index');
    }
}
