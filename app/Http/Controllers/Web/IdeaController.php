<?php

namespace Vanguard\Http\Controllers\Web;

use App\Facades\FileUploadFacade;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Models\Ideas;
use Illuminate\Support\Facades\Storage;
use Vanguard\Models\Imageideas;

class IdeaController extends Controller
{
    public function index(Request $request)
    {
        $ideas = Ideas::paginate(10);
        return view('ideas.index', compact('ideas'));
    }
    public function show(Request $request)
    {
        $idea = Ideas::find($request->id);
        $imageideas = Imageideas::where('idea_id', $idea->id)->get();
        return view('ideas.show.index', compact('idea', 'imageideas'));
    }
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $idea = new Ideas();
            $idea->title = $request->title_add;
            $idea->description = $request->des_add;
            $rs = $idea->save();
            $images = $request->all();
            foreach ($images['files'] as $image) {

                $img_name = '/ideas/idea_' . $idea->id . '_' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                // dd($img_name);
                Storage::disk(config('filesystems.public'))->put($img_name, file_get_contents($image), 'public');

                $url = Storage::disk('b2')->temporaryUrl($img_name, '', []);

                $urlDesign = substr($url, 0, strpos($url, '?X-Amz'));
                $imageideas = new Imageideas();
                $imageideas->idea_id = $idea->id;
                $imageideas->url = $urlDesign;
                $imageideas->save();
            }
            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        return view('ideas.add.index');
    }
    public function update(Request $request)
    {
        // Ideas::where()->get()
        return view('ideas.edit.index');
    }
    public function delete(Request $request)
    {
        $idea = Ideas::find($request->id);
        $idea->delete();
        return redirect()->route('ideas.index');
    }
    public function updateTitle(Request $request)
    {
        $idea = Ideas::find($request->id);
        $idea->title = $request->title;
        $rs = $idea->save();
    }
    public function upload(Request $request)
    {
        $idea_id = $request->idea_id;
        $image = $request->file;

        $img_name = '/ideas/idea_' . $idea_id . '_' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
        // dd($img_name);
        Storage::disk(config('filesystems.public'))->put($img_name, file_get_contents($image), 'public');

        $url = Storage::disk('b2')->temporaryUrl($img_name, '', []);

        $urlDesign = substr($url, 0, strpos($url, '?X-Amz'));

        $imageideas = new Imageideas();
        $imageideas->idea_id = $idea_id;
        $imageideas->url = $urlDesign;
        $rs = $imageideas->save();
        if ($rs) {
            return response(json_encode(["message" => true, "data" => $rs]), 200);
        } else {
            return response(json_encode(["message" => false, "data" => []]), 404);
        }
    }
    public function updateDescription(Request $request){
        $idea_id = $request->idea_id;
        $description = $request->description;
        $idea = Ideas::find($idea_id);
        $idea->description = $description;
        $rs = $idea->save();
        if ($rs) {
            return response(json_encode(["message" => true, "data" => $rs]), 200);
        } else {
            return response(json_encode(["message" => false, "data" => []]), 404);
        }
    }
    public function deleteImageIdea(Request $request){
        $image_idea_id = $request->image_idea_id;
        $url = $request->file_name;
        $fileName = basename($url);
        $rs = Storage::disk(config('filesystems.public'))->delete('ideas/'.$fileName);
        if($rs){
            $imageidea = Imageideas::where('id',$image_idea_id)->first();
            $rsdel = $imageidea->delete();
            if($rsdel){
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
    }
}
