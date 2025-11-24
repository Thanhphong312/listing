<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\MetaImages;
use Illuminate\Support\Facades\Auth;

class MetaImageController extends Controller
{
    public function index(Request $request){
        $query = MetaImages::query();
        $user = Auth::user();
        $role = $user->role->name;
        if($role=='Seller'||$role=='Staff'){
            $query->where('user_id', $user->id);
        }
        $metaImages = $query->paginate(20);
        return view('metaimages.index',compact('metaImages'));
    }
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $image = new MetaImages();
            $image->name = $request->name_add;
            $image->user_id = Auth::user()->id;
            $image->url = $request->size_chart;
            $image->type = $request->type_add;
            $rs = $image->save();
            // dd($listColors);
            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        return view('metaimages.add.index');
    }
    public function upload(Request $request)
    {
        $image = $request->file;

        $img_name = '/designs/sizechart_' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
        // dd($img_name);
        Storage::disk(config('filesystems.public'))->put($img_name, file_get_contents($image), 'public');

        $url = Storage::disk('b2')->temporaryUrl($img_name, '', []);

        $urlDesign = substr($url, 0, strpos($url, '?X-Amz'));

        if ($urlDesign) {
            return response(json_encode(["message" => true, "data" => $urlDesign]), 200);
        } else {
            return response(json_encode(["message" => false, "data" => []]), 404);
        }
    }
    public function delete(Request $request)
    {
        $design = MetaImages::find($request->id);
        if ($design) {
            $design->delete();
        }
        return redirect()->route('images.index');
    }
}
