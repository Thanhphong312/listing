<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Vanguard\Models\Categories;
use Vanguard\Models\MetaImages;
use Vanguard\Models\StaffTemplate;
use Vanguard\Models\Templetes;
use Vanguard\User;
use Illuminate\Support\Facades\DB;

class TempleteController extends Controller
{
    public function index(Request $request)
    {
        $query = Templetes::query();
        $user = Auth::user();
        $role = $user->role->name;
        if ($role == 'Seller' || $role == 'Staff') {
            // $query->where('user_id', $user->id);
            $query->whereHas('stafftemplate', function($querystaff) use ($user){
                $querystaff->where('user_id', $user->id);
            });
            $query->orWhere('user_id', $user->id);

        }
        $templetes = $query->paginate(20);
        return view('templetes.index', compact('templetes','role','user'));
    }
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            // dd($request);
            $templetes = new Templetes();
            $templetes->data = $request->json;
            $templetes->name = $request->name;
            $templetes->discount = $request->discount;
            $templetes->user_id = Auth::user()->id;
            $rs = $templetes->save();
            // dd($listColors);
            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        $categories = Categories::select(['name'])->get();
        $query = MetaImages::query();
        $user = Auth::user();
        $role = $user->role->name;
        if ($role == 'Seller' || $role == 'Staff') {
            $query->where('user_id', $user->id);
        }
        $sizecharts = $query->get();
        $path = public_path('categories/categories.json'); // Đường dẫn đến file categories.json

        if (file_exists($path)) {
            $jsonData = file_get_contents($path);
            $categorietemps = (json_decode($jsonData));
        }
        return view('templetes.add.index', compact('categories', 'categorietemps', 'user', 'role', 'sizecharts'));
    }
    public function edit(Request $request, $id)
    {
        $templetes = Templetes::find($id);
        if ($request->isMethod('post')) {
            $templetes->data = $request->json;
            $templetes->name = $request->name;
            $templetes->discount = $request->discount;

            $templetes->user_id = Auth::user()->id;
            $rs = $templetes->save();

            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        $categories = Categories::select(['name'])->get();
        $query = MetaImages::query();
        $user = Auth::user();
        $role = $user->role->name;
        $name = $templetes->name;

        if ($role == 'Seller' || $role == 'Staff') {
            $query->where('user_id', $user->id);
        }
        $sizecharts = $query->get();

        $json = json_decode($templetes->data);
        $encodedJson = json_encode($json);
        // dd($json);
        $path = public_path('categories/categories.json'); // Đường dẫn đến file categories.json

        if (file_exists($path)) {
            $jsonData = file_get_contents($path);

            $categorietemps = (json_decode($jsonData));
            $category_id = $json->product->category_id ?? 0;
            $id = $category_id;
            $localNames = [];

            while ($id != 0) {
                // Sử dụng array_filter để tìm danh mục theo category_id
                $category = current(array_filter($categorietemps, function ($cat) use ($id) {
                    return $cat->id == (string) $id;
                }));

                // Nếu tìm thấy danh mục, thêm local_name vào mảng và cập nhật category_id
                if ($category) {
                    $localNames[] = $category->local_name;
                    $id = $category->parent_id;
                } else {
                    // Nếu không tìm thấy, thoát vòng lặp
                    break;
                }
            }

            // Đảo ngược và nối chuỗi local_name với dấu ">"
            $breadcrumb = implode(' > ', array_reverse($localNames));

        }
        return view('templetes.edit.index', compact('categorietemps', 'categories', 'sizecharts', 'encodedJson', 'templetes', 'breadcrumb', 'category_id','name'));
    }
    public function delete(Request $request)
    {
        $design = Templetes::find($request->id);

        if ($design) {
            $design->delete();
        }
        return redirect()->route('templates.index');
    }
    public function getjson(Request $request)
    {
        $templetes = Templetes::find($request->id);
        $json = json_decode($templetes->data);
        $encodedJson = json_encode($json->product);
        return $encodedJson;
    }
    public function duplicate(Request $request, $id)
    {
        $product = Templetes::find($id);
        $newProduct = $product->replicate();
        $newProduct->save();
        return redirect()->route('templates.index');
    }
    public function chooseUser(Request $request, $id)
    {
        $users = User::select(['id', 'username'])->whereIn('role_id', [3, 5])
            ->whereIn('status',['Active'])
            ->get();
        return view('templetes.ajax.chooseuser', compact('users', 'id'));
    }
    public function acceptUser(Request $request, $id)
    {
        $staffTemplate = StaffTemplate::where('user_id', $request->user_id)
            ->where('template_id', $id)
            ->first();

        // If the record exists, delete it; otherwise, create a new one
        if ($staffTemplate) {
            $staffTemplate->delete();
            return response()->json(["message" => "deleted", "data" => true], 200);
        }

        $rs = StaffTemplate::create([
            'user_id' => $request->user_id,
            'template_id' => $id,
        ]);

        return response()->json([
            "message" => $rs ? "added" : "error",
            "data" => $rs ? true : []
        ], $rs ? 200 : 404);

    }
    public function test(Request $request, $id){
        $templetes = Templetes::find($id);
        if ($request->isMethod('post')) {
            $templetes->data = $request->json;
            $templetes->name = $request->name;
            $templetes->discount = $request->discount;

            $templetes->user_id = Auth::user()->id;
            $rs = $templetes->save();

            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        $categories = Categories::select(['name'])->get();
        $query = MetaImages::query();
        $user = Auth::user();
        $name = $templetes->name;
        $role = $user->role->name;
        if ($role == 'Seller' || $role == 'Staff') {
            $query->where('user_id', $user->id);
        }
        $sizecharts = $query->get();

        $json = json_decode($templetes->data);
        $encodedJson = json_encode($json);
        // dd($json);
        $path = public_path('categories/categories.json'); // Đường dẫn đến file categories.json

        if (file_exists($path)) {
            $jsonData = file_get_contents($path);

            $categorietemps = (json_decode($jsonData));
            $category_id = $json->product->category_id ?? 0;
            $id = $category_id;
            $localNames = [];

            while ($id != 0) {
                // Sử dụng array_filter để tìm danh mục theo category_id
                $category = current(array_filter($categorietemps, function ($cat) use ($id) {
                    return $cat->id == (string) $id;
                }));

                // Nếu tìm thấy danh mục, thêm local_name vào mảng và cập nhật category_id
                if ($category) {
                    $localNames[] = $category->local_name;
                    $id = $category->parent_id;
                } else {
                    // Nếu không tìm thấy, thoát vòng lặp
                    break;
                }
            }

            // Đảo ngược và nối chuỗi local_name với dấu ">"
            $breadcrumb = implode(' > ', array_reverse($localNames));

        }
        return view('templetes.edit.test', compact('categorietemps', 'categories', 'sizecharts', 'encodedJson', 'templetes', 'breadcrumb', 'category_id','name'));
    }

    public function setup(Request $request)
    {
        if ($request->isMethod('post')) {
            dd($request);
        }
        $templates = Templetes::get();
        $users = DB::table('users')
            ->select('id', 'username')
            ->whereIn('role_id', [3, 5])
            ->get();
        return view('templetes.setup', compact('templates', 'users'));
    }
}
