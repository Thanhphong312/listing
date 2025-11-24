<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\Colors;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        $colors = Colors::get();
        return view('colors.index', compact('colors'));
    }
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $color = new Colors();
            $color->name = $request->name;
            $color->hex = $request->hex;
            $color->type = $request->type;
            $color->status = $request->status;
            $rs = $color->save();
            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        return view('colors.add.index');
    }
    public function edit(Request $request)
    {
        $color = Colors::find($request->id);
        if ($request->isMethod('post')) {
            $color->name = $request->name;
            $color->hex = $request->hex;
            $color->type = $request->type;
            $color->status = $request->status;
            $rs = $color->save();
            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        return view('colors.edit.index',compact('color'));
    }
    public function delete(Request $request)
    {
        $category = Colors::find($request->id);
        $category->delete();
        return redirect()->route('colors.index');        
    }
}
