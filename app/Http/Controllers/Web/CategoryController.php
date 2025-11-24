<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\Categories;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Categories::get();
        return view('categories.index', compact('categories'));
    }
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $category = new Categories();
            $category->name = $request->name;
            $category->status = $request->status;
            $rs = $category->save();
            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        return view('categories.add.index');
    }
    public function edit(Request $request)
    {
        $category = Categories::find($request->id);
        if ($request->isMethod('post')) {
            $category->name = $request->name;
            $category->status = $request->status;
            $rs = $category->save();
            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        return view('categories.edit.index',compact('category'));
    }
    public function delete(Request $request)
    {
        $category = Categories::find($request->id);
        $category->delete();
        return redirect()->route('categories.index');        
    }
}
