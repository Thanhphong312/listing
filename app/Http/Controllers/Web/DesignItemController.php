<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Jobs\CreateImageMockupJobs;
use Vanguard\Models\Categories;
use Vanguard\Models\CategoryDesignItems;
use Vanguard\Models\DesignItems;
use Vanguard\Models\Designs;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Vanguard\Models\Colors;
use Vanguard\Models\Templetes;
use Illuminate\Support\Facades\Auth;
use Vanguard\Services\ImageService;

class DesignItemController extends Controller
{
    public function index(Request $request)
    {
        $designitems = DesignItems::query();
        $user = Auth::user();
        $role = $user->role->name;
        if (isset($request->design_id) && !empty($request->design_id)) {
            $designitems->where('design_id', $request->design_id);
        }
        if($role=='Seller'||$role=='Staff'){
            $designitems->where('user_id', $user->id);
        }
        $designitems = $designitems->paginate(20);
        $categories = Categories::get();
        return view('designitems.index', compact('designitems', 'categories','role'));
    }
    public function add(Request $request)
    {
        $designs = Designs::select('id', 'title')->get();
        $categories = Categories::select('id', 'name')->get();
        if ($request->isMethod('post')) {
            $designitem = new DesignItems();
            $designitem->title = $request->title;
            // $sdesignitem->design_id = $request->design_id;
            $designitem->front_design = $request->front_design;
            $designitem->back_design = $request->back_design;
            $designitem->sleeve_left_design = $request->sleeve_left_design;
            $designitem->sleeve_right_design = $request->sleeve_right_design;
            $designitem->user_id = Auth::user()->id;
            $rs = $designitem->save();
            if ($rs) {
                $list = explode(",", $request->list_category);
                foreach ($list as $category_id) {
                    $categoriydesignitem = new CategoryDesignItems();
                    $categoriydesignitem->design_item_id = $designitem->id;
                    $categoriydesignitem->category_id = $category_id;
                    $categoriydesignitem->save();
                }

                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        return view('designitems.add.index', compact('designs', 'categories'));
    }
    public function edit(Request $request, $id){
        $designitem = DesignItems::find($id);
        $user = Auth::user();
        $role = $user->role->name;
        if ($request->isMethod('post')) {
            $designitem->title = $request->title;
            $rs = $designitem->save();

            // dd($listColors);
            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }
        
        return view('designitems.edit.index', compact('designitem','role','user'));
    }
    public function update(Request $request)
    {
        $design = Designs::find($request->id);

        if (!$design) {
            return response(json_encode(["message" => false, "data" => []]), 404);
        }

        $design->idea_id = $request->idea_add;

        foreach ($design->designcolors as $designColor) {
            $designColor->delete();
        }

        $listColors = explode(",", $request->color);
        foreach ($listColors as $listColor) {
            $designColor = new DesignColor();
            $designColor->design_id = $design->id;
            $designColor->color_id = $listColor;
            $designColor->save();
        }

        $rs = $design->save();

        if ($rs) {
            return response(json_encode(["message" => true, "data" => $rs]), 200);
        } else {
            return response(json_encode(["message" => false, "data" => []]), 404);
        }
    }

    public function updateTitle(Request $request)
    {
        $idea = Designs::find($request->id);
        $idea->title = $request->title;
        $rs = $idea->save();
    }
    public function delete(Request $request)
    {
        $designitem = DesignItems::find($request->id);
        if ($designitem->front_design) {
            $fileName = basename($designitem->front_design);
            $rs = Storage::disk(config('filesystems.public'))->delete('designitems/' . $fileName);
        }
        if ($designitem->back_design) {
            $fileName = basename($designitem->front_design);
            $rs = Storage::disk(config('filesystems.public'))->delete('designitems/' . $fileName);
        }
        if ($designitem->sleeve_left_design) {
            $fileName = basename($designitem->sleeve_left_design);
            $rs = Storage::disk(config('filesystems.public'))->delete('designitems/' . $fileName);
        }
        if ($designitem->sleeve_right_design) {
            $fileName = basename($designitem->sleeve_right_design);
            $rs = Storage::disk(config('filesystems.public'))->delete('designitems/' . $fileName);
        }
        $designitem->delete();
        return redirect()->route('designItems.index');
    }
    public function upload(Request $request)
    {
        $image = $request->file;
        $side = $request->side;

        $img_name = '/designitems/design_item_' . $side . '_' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
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

    public function uploadShow(Request $request)
    {
        $image = $request->file;
        $side = $request->side;

        $img_name = '/designitems/design_item_' . $side . '_' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
        // dd($img_name);
        Storage::disk(config('filesystems.public'))->put($img_name, file_get_contents($image), 'public');

        $url = Storage::disk('b2')->temporaryUrl($img_name, '', []);

        $urlDesign = substr($url, 0, strpos($url, '?X-Amz'));

        if ($urlDesign) {
            $designitem = DesignItems::find($request->design_item_id);
            if ($request->side == 'front_design') {
                $designitem->front_design = $urlDesign;
            }
            if ($request->side == 'back_design') {
                $designitem->back_design = $urlDesign;
            }
            if ($request->side == 'sleeve_left_design') {
                $designitem->sleeve_left_design = $urlDesign;
            }
            if ($request->side == 'sleeve_right_design') {
                $designitem->sleeve_right_design = $urlDesign;
            }
            $designitem->save();
            return response(json_encode(["message" => true, "data" => $urlDesign]), 200);
        } else {
            return response(json_encode(["message" => false, "data" => []]), 404);
        }
    }
    public function updateDescription(Request $request)
    {
        $idea_id = $request->idea_id;
        $description = $request->description;
        $idea = Designs::find($idea_id);
        $idea->description = $description;
        $rs = $idea->save();
        if ($rs) {
            return response(json_encode(["message" => true, "data" => $rs]), 200);
        } else {
            return response(json_encode(["message" => false, "data" => []]), 404);
        }
    }
    public function deleteImage(Request $request)
    {
        $design_item_id = $request->design_item_id;
        $url = $request->file_name;
        $fileName = basename($url);
        $rs = Storage::disk(config('filesystems.public'))->delete('designitems/' . $fileName);
        if ($rs) {
            $designitem = DesignItems::where('id', $design_item_id)->first();
            if ($designitem) { // Kiểm tra xem $design có tồn tại hay không
                if ($request->side == 'front_design') {
                    $designitem->front_design = null;
                }
                if ($request->side == 'back_design') {
                    $designitem->back_design = null;
                }
                if ($request->side == 'sleeve_left_design') {
                    $designitem->sleeve_left_design = null;
                }
                if ($request->side == 'sleeve_right_design') {
                    $designitem->sleeve_right_design = null;
                }
                $rsdel = $designitem->save();
                if ($rsdel) {
                    return response()->json(["message" => true, "data" => $rs], 200);
                } else {
                    return response()->json(["message" => false, "data" => []], 404);
                }
            } else {
                // Trả về thông báo lỗi nếu không tìm thấy bản ghi
                return response()->json(["message" => false, "error" => "Design not found"], 404);
            }
        } else {
            return response()->json(["message" => false, "error" => "File deletion failed"], 404);
        }
    }
    public function editCategory(Request $request)
    {
        if ($request->check) {
            $categoryDesign = new CategoryDesignItems();
            $categoryDesign->design_item_id = $request->design_item_id;
            $categoryDesign->category_id = $request->category_id;
            $rs = $categoryDesign->save();
            if ($rs) {
                return response()->json(["message" => true, "data" => $rs], 200);
            } else {
                return response()->json(["message" => false, "data" => []], 404);
            }
        } else {
            $categoryDesign = CategoryDesignItems::where('design_item_id', $request->design_item_id)
                ->where('category_id', $request->category_id)->first();
            $rs = $categoryDesign->save();
            if ($rs) {
                return response()->json(["message" => true, "data" => $rs], 200);
            } else {
                return response()->json(["message" => false, "data" => []], 404);
            }
        }

    }
    public function changeNumberDesignItem(Request $request)
    {

        $designItem = DesignItems::find($request->design_item_id);
        $designItem->number_side = $request->number_side;
        $rs = $designItem->save();
        if ($rs) {
            return response()->json(["message" => true, "data" => $rs], 200);
        } else {
            return response()->json(["message" => false, "data" => []], 404);
        }
    }
    public function cenvert(Request $request, $id)
    {
        $designItems = DesignItems::find($id);
        $front_url = $designItems->front_design;
        $title = $designItems->title;
        $back_url = $designItems->back_design;
        $sleeve_left_url = $designItems->sleeve_left_design;
        $sleeve_right_url = $designItems->sleeve_right_design;
        // dd($designItems);
        // $list = ["T-shirt","Sweatshirt","Hoodie","Couple"];
        $url = 'https://global24watermark.site/get-temp/temp.json';

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
            )
        );

        // Execute cURL request and get response
        $response = curl_exec($ch);
        $json = json_decode($response);

        $colors = Colors::where('status', 1)->select('name', 'id', 'hex', 'type')->get();
        $darks = $colors->filter(function ($color) {
            return $color->type === 0;
        })->pluck('id')->toArray();

        // Lọc màu thuộc loại "light"
        $lights = $colors->filter(function ($color) {
            return $color->type === 1;
        })->pluck('id')->toArray();

        return view('designitems.cenvert.index', compact('title','json', 'colors', 'darks', 'lights', 'front_url', 'back_url', 'sleeve_left_url', 'sleeve_right_url'));
    }
    public function ajaxMockup(Request $request)
    {
        $numberside = $request->numberside;
        $type = $request->name;
        $url = 'https://global24watermark.site/get-mockup-folder/mockup.json?category=' . $request->name;

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
            )
        );

        // Execute cURL request and get response
        $response = curl_exec($ch);
        $json = json_decode($response);
        $list = [
                [1,4,6,200,300,500,900,1000,7000],
                [2,5,100,101,800,2000,4000,5000]
            ];
        $listshirtid = $list[$numberside-1];

        
        return view('designitems.cenvert.ajax.design', compact('listshirtid', 'json', 'type'));
    }
    public function ajaxMockupHuman(Request $request)
    {
        $numberside = $request->numberside;

        $type = $request->name;
        $url = 'https://global24watermark.site/get-mockup-folder/mockup.json?category=all';

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
            )
        );

        // Execute cURL request and get response
        $response = curl_exec($ch);
        $json = json_decode($response);
        
        $templates = Templetes::get();
        $list = [
            [3,400,700,3000],
            [7,600,6000]
        ];
        $listhumanid = $list[$numberside-1];
        return view('designitems.cenvert.ajax.human', compact('listhumanid', 'json', 'type', 'templates'));
    }
    public function createProductDesign(Request $request)
    {
        // dd($request);
        $mockupjson = json_decode($request->mockupchoose);
        $mockuphumanjson = json_decode($request->mockupchooseHuman);
        $imagefirst = $request->firstmockup;
        //2 cái trên có dạng
        // array:23 [ // app/Http/Controllers/Web/DesignItemController.php:331
        //     0 => {#1987
        //       +"color": "#cc011f"
        //       +"src": "https://global24watermark.site/mockup/hoodie/cc011f/two/5.jpg?url_1=https://s3.us-west-004.backblazeb2.com/Windycloud/designitems/design_item_front_design_2340.png?w=120&h=150&ver=231895&url_2=https://s3.us-west-004.backblazeb2.com/Windycloud/designitems/design_item_back_design_8876.png?w=120&h=150&ver=231895"
        //     }
        //     1 => {#1988
        //       +"color": "#e0e0e2"
        //       +"src": "https://global24watermark.site/mockup/hoodie/e0e0e2/two/5.jpg?url_1=https://s3.us-west-004.backblazeb2.com/Windycloud/designitems/design_item_front_design_2340.png?w=120&h=150&ver=231895&url_2=https://s3.us-west-004.backblazeb2.com/Windycloud/designitems/design_item_back_design_8876.png?w=120&h=150&ver=231895"
        //     }
        //     2 => {#1990
        //       +"color": "#FFC0CB"
        //       +"src": "https://global24watermark.site/mockup/hoodie/FFC0CB/two/5.jpg?url_1=https://s3.us-west-004.backblazeb2.com/Windycloud/designitems/design_item_front_design_2340.png?w=120&h=150&ver=231895&url_2=https://s3.us-west-004.backblazeb2.com/Windycloud/designitems/design_item_back_design_8876.png?w=120&h=150&ver=231895"
        //     }
        $template = Templetes::find($request->template);
        $product = json_decode($template->data)->product;


        if($request->images){
            $images = [];
            foreach ($request->images as $designimage) {
                $filename = random_int(10000,99999)."_".$designimage->getClientOriginalName();            // Store the uploaded file temporarily
                $path = $designimage->store('products');  // Store the uploaded file in storage/designs

                // Retrieve the file using the path stored earlier
                $imagePath = Storage::path($path);  // Get the full local path of the file
                $imgName = '/designs/' . $filename;
                // Create an Imagick object
                $imageservice = new ImageService();

                $urlDesign = $imageservice->resizeImage($imagePath, $imgName, 1155, 1155);
                // Delete the original image from local storage
                Storage::delete($path);
            
                // Add the image information to the array
                array_push($images, [
                    "id" => $this->randomId(),
                    "product_id" => $product->id,
                    "position" => 1,
                    "created_at" => "2019-05-30T04:33:43+07:00",
                    "updated_at" => "2019-10-22T09:03:30+07:00",
                    "width" => 1155,
                    "height" => 1155,
                    "src" => $urlDesign,
                    "variant_ids" => []
                ]);            
            
            }
        }
        // dd($images);

        $product->title = $request->title;
        //b1. lấy random 1 ảnh  từ $mockupjson
        //b2. lấy radom 1 ảnh từ $mockuphumanjson
        //b3. lấy random 2 ảnh từ $mockupjson
        //các ảnh trên có dạng json bên dưới và cho vào array
        // {
        //     "id": "56178068971071",
        //     "product_id": "54910610169366",
        //     "position": 1,
        //     "created_at": "2019-05-30T04:33:43+07:00",
        //     "updated_at": "2019-10-22T09:03:30+07:00",
        //     "width": 1155,
        //     "height": 1155,
        //     "src": "https://s3.us-west-004.backblazeb2.com/Windycloud/designs/81147_2%20%285%29.jpg",
        //     "variant_ids": []
        //   }

        // Hàm để chuyển đổi ảnh sang định dạng JSON mong muốn

        // Bước 1: Lấy ngẫu nhiên 1 ảnh từ $mockupjson

        // foreach ($mockupjson as $mockup) {
            $randomImages = [];
            // if (!empty($mockupjson)) {
            //     $randomImage1 = $mockupjson[array_rand($mockupjson)];
            $randomImages[] = $this->formatImage($imagefirst, $product->id);
            // }

            // Bước 2: Lấy ngẫu nhiên 1 ảnh từ $mockuphumanjson
            if (!empty($mockuphumanjson)) {
                $randomImage2 = $mockuphumanjson[0];
                $randomImages[] = $this->formatImage($randomImage2->src, $product->id);
                $randomImage3 = $mockuphumanjson[1];
                $randomImages[] = $this->formatImage($randomImage3->src, $product->id);
                $randomImage4 = $mockuphumanjson[2];
                $randomImages[] = $this->formatImage($randomImage4->src, $product->id);
            }

            // // Bước 3: Lấy ngẫu nhiên 2 ảnh từ $mockupjson
            // if (count($mockupjson) >= 2) {
            //     $randomKeys = array_rand($mockupjson, 2);
            //     foreach ($randomKeys as $key) {
            //         $randomImages[] = $this->formatImage($mockupjson[$key]->src, $product->id);
            //     }
            // }

            $imagevariants = [];

            foreach ($mockupjson as $mockup) {
                $imagevariants[] = [
                    "color" => getColorByHex($mockup->color),
                    "src" => $mockup->src
                ];
            }
            $randomImages = array_merge($randomImages, $images);
            // dd($randomImages);
            $product->images = $randomImages;
            $product->imagevariants = $imagevariants;
            $product->templete_id = $request->selectedtemplete;
            // dd($product->images);
            $user_id = Auth::user()->id;
            CreateImageMockupJobs::dispatch($user_id , $product->id ,$template->discount, $product)->delay(1)->onQueue('create-product-mockup');

        // }
        return response()->json(["message" => true, "data" => ""], 200);

        // dd($product, $randomImages, $imagevariants);

        // dd($mockupjson, $mockuphumanjson);
    }
    function randomId() {
        $randomNumber = mt_rand(0, 99999999999999); // Generate a random number up to 14 digits
        return str_pad($randomNumber, 14, '0', STR_PAD_LEFT); // Pad the number with leading zeros if necessary
    }
    function formatImage($src, $product_id)
    {
        return [
            "id" => uniqid(),
            // Tạo id ngẫu nhiên
            "product_id" => $product_id,
            "position" => 1,
            "created_at" => now()->toIso8601String(),
            "updated_at" => now()->toIso8601String(),
            "width" => 300,
            "height" => 300,
            "src" => $src,
            "variant_ids" => []
        ];
    }
    function genPositions(Request $request){
        // dd($designItem);
        $frontCoordinatesvalue = explode(",",$request->frontCoordinatesvalue);
        $backCoordinatesvalue = explode(",",$request->backCoordinatesvalue);
        
        $reconvertCoordinatesfront = $this->reconvertCoordinates($frontCoordinatesvalue[2], $frontCoordinatesvalue[3], 1200, 1200);
        $xfront = (int)$reconvertCoordinatesfront['x'];
        $yfront = (int)$reconvertCoordinatesfront['y'];
        $widthfront = $frontCoordinatesvalue[0] ? (int)round($frontCoordinatesvalue[0] * (1000 / 1200),0)+5  : 0;
        $heightfront = $frontCoordinatesvalue[1] ? (int)round($frontCoordinatesvalue[1] * (1000 / 1200),0)+5 : 0;

        $reconvertCoordinatesback = $this->reconvertCoordinates($backCoordinatesvalue[2], $backCoordinatesvalue[3], 1200, 1200);
        $xback = (int)$reconvertCoordinatesback['x'];
        $yback = (int)$reconvertCoordinatesback['y'];
        $widthback = $backCoordinatesvalue[0] ?  (int)round($backCoordinatesvalue[0] * (1000 / 1200),0)+5 : 0;
        $heightback = $backCoordinatesvalue[1] ? (int)round($backCoordinatesvalue[1] * (1000 / 1200),0)+5 : 0;
        // dd($xfront, $yfront, $widthfront, $heightfront, $xback, $yback, $widthback, $heightback);
        $mockupUrl = $request->mockupUrl;
        $fontdesign = $request->front_url;
        $backdesign = $request->back_url;
        // dd($position);
        return view('designitems.cenvert.temp',compact('fontdesign','backdesign','mockupUrl','xfront', 'yfront', 'widthfront', 'heightfront', 'xback', 'yback', 'widthback', 'heightback'));
    }
    function genPositionhumans(Request $request){
        // dd($designItem);
        $frontCoordinatesvalue = explode(",",$request->frontCoordinatesvalue);
        $backCoordinatesvalue = explode(",",$request->backCoordinatesvalue);
        
        $reconvertCoordinatesfront = $this->reconvertCoordinates($frontCoordinatesvalue[2], $frontCoordinatesvalue[3], 1200, 1200);
        $xfront = (int)$reconvertCoordinatesfront['x'];
        $yfront = (int)$reconvertCoordinatesfront['y'];
        $widthfront = $frontCoordinatesvalue[0] ? (int)round($frontCoordinatesvalue[0] * (1000 / 1200),0)+5  : 0;
        $heightfront = $frontCoordinatesvalue[1] ? (int)round($frontCoordinatesvalue[1] * (1000 / 1200),0)+5 : 0;

        $reconvertCoordinatesback = $this->reconvertCoordinates($backCoordinatesvalue[2], $backCoordinatesvalue[3], 1200, 1200);
        $xback = (int)$reconvertCoordinatesback['x'];
        $yback = (int)$reconvertCoordinatesback['y'];
        $widthback = $backCoordinatesvalue[0] ?  (int)round($backCoordinatesvalue[0] * (1000 / 1200),0)+5 : 0;
        $heightback = $backCoordinatesvalue[1] ? (int)round($backCoordinatesvalue[1] * (1000 / 1200),0)+5 : 0;
        // dd($xfront, $yfront, $widthfront, $heightfront, $xback, $yback, $widthback, $heightback);
        $mockupUrl = $request->mockupUrl;
        $fontdesign = $request->front_url;
        $backdesign = $request->back_url;
        // dd($position);
        return view('designitems.cenvert.temphuman',compact('fontdesign','backdesign','mockupUrl','xfront', 'yfront', 'widthfront', 'heightfront', 'xback', 'yback', 'widthback', 'heightback'));
    }
    function reconvertCoordinates($x_old, $y_old, $oldWidth, $oldHeight) {
         $newWidth = 1000;
         $newHeight = 1000;

         $x_new = ($x_old / $oldWidth) * $newWidth;
         $y_new = ($y_old / $oldHeight) * $newHeight;

        return [  'x' => round($x_new,0), 'y'=> round($y_new,0) ];
    }
}
