<?php

namespace Vanguard\Http\Controllers\Web;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Jobs\CenvertDriveBlaze;
use Vanguard\Jobs\CreateImageMockupJobs;
use Vanguard\Models\CategoryDesignItems;
use Vanguard\Models\Designs;
use Illuminate\Support\Facades\Storage;
use Vanguard\Models\Colors;
use Vanguard\Models\Templetes;
use Illuminate\Support\Facades\Auth;
use Vanguard\Services\Blaze\BlazeService;
use Vanguard\Services\Design\DesignService;
use Vanguard\Services\ImageService;
use Vanguard\Models\DesignMetas;
use Vanguard\Http\Requests\Design\DesignFilterRequest;
use Vanguard\Services\Users\UserService;

class DesignController extends Controller
{
    public function __construct(private readonly DesignService $designService, private readonly UserService $userService)
    {

    }

    public function index(DesignFilterRequest $request)
    {
        $filter = $request->validationData();
        $designs = $this->designService->panigate($filter);
        $listSeller = $this->userService->getUsersByRoleId(3);
        $listStaff = $this->userService->getUsersByRoleId(5);
        $user = Auth::user();
        $role = $user->role->name;

        return view('designs.index', compact('designs', 'request', 'role', 'user', 'listSeller', 'listStaff'));
        // return view('maintenance.index');

    }
    public function addFile(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = Auth::user();

            // Create and populate the design record
            $design = new Designs();
            $design->fill([
                'title' => $request->title,
                'niche' => $request->niche,
                'mix' => $request->mix,
                'user_id' => $user->id,
                'sku' => $user->user_code . $request->niche . $request->mix,
                'tag' => $request->tag,
            ]);
            $design->save();

            // Add a unique SKU after saving the design
            $design->sku .= $design->id;
            $design->save();

            // Count the number of images in the request
            $count_image = $this->count_image_request($request);

            // Process design image files and upload them to Blaze
            $this->processDesignImageFiles($request, $count_image, $design->sku, $design->id);

            return response()->json(["message" => true, "data" => $design], 200);
        }

        return view('designs.add.index');
    }

    public function addurl(Request $request)
    {
        if ($request->isMethod('post')) {
            // \Log::info('Request to edit ', ['value' => $request->all()]);
            $user = Auth::user();

            // Tạo và lưu thông tin trong bảng designs
            $design = new Designs();
            $design->fill([
                'title' => $request->title,
                'niche' => $request->niche,
                'mix' => $request->mix,
                'user_id' => $user->id,
                'sku' => $user->user_code . $request->niche . $request->mix,
                'tag' => $request->tag,
            ]);
            $design->save();

            // Cập nhật lại SKU để thêm ID của design
            $design->sku .= $design->id;
            $design->save();

            $id = $design->id;
            $sku = $design->sku;
            $count_image = $this->count_image_request($request);

            // Xử lý hình ảnh và lưu vào bảng design_metas
            $this->processDesignImageUrls($request, $count_image, $sku, $id);

            return response()->json(["message" => true, "data" => $design], 200);
        }

        return view('designs.add.addurl');
    }

    private function processDesignImageUrls(Request $request, $count_image, $sku, $id)
    {

        $bl_and_wt = $request->bl_and_wt === "true";
        $designs = [
            'front' => 'F',
            'back' => 'B',
            'sleeve_left' => 'L',
            'sleeve_right' => 'R',
        ];

        foreach ($designs as $key => $side) {
            if ($bl_and_wt) {
                $this->uploadDesignImageUrl($request, "{$key}_design_bl", "{$side}_BL", $count_image, $sku, $id);
                $this->uploadDesignImageUrl($request, "{$key}_design_wt", "{$side}_WT", $count_image, $sku, $id);
            } else {
                $this->uploadDesignImageUrl($request, "{$key}_design", $side, $count_image, $sku, $id);
            }
        }
    }
    private function processDesignImageFiles(Request $request, $count_image, $sku, $id)
    {

        $bl_and_wt = $request->bl_and_wt === "true";
        $designs = [
            'front' => 'F',
            'back' => 'B',
            'sleeve_left' => 'L',
            'sleeve_right' => 'R',
        ];

        foreach ($designs as $key => $side) {
            if ($bl_and_wt) {
                $this->uploadDesignImageFile($request, "{$key}_design_bl", "{$side}_BL", $count_image, $sku, $id);
                $this->uploadDesignImageFile($request, "{$key}_design_wt", "{$side}_WT", $count_image, $sku, $id);
            } else {
                $this->uploadDesignImageFile($request, "{$key}_design", $side, $count_image, $sku, $id);
            }
        }
    }
    private function uploadDesignImageUrl(Request $request, $field, $side, $count, $sku, $id)
    {
        $img_name = "{$sku}_{$count}s_{$side}";
        $fileName = "{$img_name}";
        $url = $request->{$field};

        // Dispatch the job to handle image processing
        CenvertDriveBlaze::dispatch($url, $fileName, $id, $field)
            ->onQueue('add-design-image-url')
            ->delay(2);
    }
    // private function uploadDesignImageFile(Request $request, $field, $side, $count, $sku, $id)
    // {
    //     $img_name = "{$sku}_{$count}s_{$side}";
    //     $fileName = "{$img_name}";
    //     $file = $request->{$field};
    //     // Upload file image to Blaze 
    // }
    public function uploadDesignImageFile(Request $request, $field, $side, $count, $sku, $id)
    {
        $img_name = "{$sku}_{$count}s_{$side}";
        $fileName = "{$img_name}.png";
        $file = $request->{$field};
        // dd($file);
        // Upload file image to Blaze 
        if ($file) {
            $fileUrl = $this->uploadFileToBlaze($file, $fileName);
        } else {
            $googleDriveFileId = $request->input('google_drive_file_id');

            if ($googleDriveFileId) {
                $fileUrl = $this->uploadFromGoogleDrive($googleDriveFileId, $fileName);
            } else {
                \Log::error("No file uploaded and no Google Drive file ID provided for Design ID {$id}, Side {$side}");
                return;
            }
        }
        if ($fileUrl) {
            $this->saveToDesignMetas($id, $field, $fileUrl);
        } else {
            \Log::error("No valid file URL to save for Design ID {$id}, Side {$side}");
        }
    }

    private function uploadFromGoogleDrive($fileId, $fileName)
    {
        $urlvalue = "https://drive.google.com/uc?export=view&id={$fileId}";
        $fileContents = file_get_contents($urlvalue);

        if ($fileContents === false) {
            \Log::error("Failed to download file from Google Drive: {$urlvalue}");
            return;
        }

        $tempFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $fileName;
        file_put_contents($tempFilePath, $fileContents);

        // Upload the file to Blaze
        $this->uploadFileToBlaze($tempFilePath, $fileName);

        unlink($tempFilePath);
    }
    private function uploadFileToBlaze($filePath, $fileName)
    {
        // dd($filePath, $fileName);
        // Initialize Blaze service for uploading
        $blazeService = new BlazeService();
        $bucketId = env('B2_BUCKET_ID');

        $result = $blazeService->uploadFile($filePath, 'design/' . $fileName, $bucketId);

        if (!$result || !isset($result['fileName'])) {
            \Log::error("Failed to upload file to Blaze: {$fileName}");
            return null;
        }


        $fileUrl = $result['fileName'];

        \Log::info("Successfully uploaded file to Blaze: {$fileName}");

        return $fileUrl;
    }

    private function saveToDesignMetas($designId, $side, $fileUrl)
    {
        $key = strtoupper($side);
        $imageresize = new ImageService();
        // dd("https://felinepropduct.s3.us-west-004.backblazeb2.com/{$fileUrl}");
        $thumbnailurl = $imageresize->resizeImage("https://windycloud.s3.us-west-004.backblazeb2.com/{$fileUrl}", $fileUrl . "_thumbnail_" . random_int(100000, 999999) . ".png", 600, 600);
        $design = Designs::find($designId);

        if ($design->thumbnail == null) {
            $design->thumbnail = $thumbnailurl;
            $design->save();
        }
        DesignMetas::updateOrCreate(
            ['design_id' => $designId, 'key' => $key],
            [
                'value' => "https://windycloud.s3.us-west-004.backblazeb2.com/{$fileUrl}",
                'thumbnail' => $thumbnailurl,
                'updated_at' => now(),
                'created_at' => now()
            ]
        );
        \Log::info("Successfully saved file URL to DesignMetas for Design ID: {$designId}, Side: {$side}");
    }

    public function edit(Request $request, $id)
    {
        $design = Designs::find($id);
        $user = Auth::user();
        $role = $user->role->name;
        // dd($request);
        if ($request->isMethod('post')) {
            $design->title = $request->title;
            $design->tag = $request->tag;
            $rs = $design->save();

            if ($rs) {
                return response(json_encode(["message" => true, "data" => $rs]), 200);
            } else {
                return response(json_encode(["message" => false, "data" => []]), 404);
            }
        }

        return view('designs.edit.index', compact('design', 'role', 'user'));
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
        $designitem = Designs::find($request->id);
        DesignMetas::where('design_id', $request->id)->delete();
        $designitem->delete();

        return redirect()->route('designs.index');
    }
    public function upload(Request $request)
    {
        $startTime = microtime(true); // Start time

        $image = $request->file;
        $side = $request->side;

        $img_name = '/designitems/design_item_' . $side . '_' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();

        Storage::disk(config('filesystems.public'))->putFileAs('', $image, $img_name, 'public');

        $url = Storage::disk('b2')->temporaryUrl($img_name, '', []);

        $urlDesign = substr($url, 0, strpos($url, '?X-Amz'));

        $endTime = microtime(true); // End time
        $uploadDuration = $endTime - $startTime; // Time taken for upload

        if ($urlDesign) {
            return response(json_encode(["message" => true, "data" => $urlDesign, "upload_time" => $uploadDuration]), 200);
        } else {
            return response(json_encode(["message" => false, "data" => [], "upload_time" => $uploadDuration]), 404);
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
            $designitem = Designs::find($request->design_item_id);
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
        $rs = Storage::disk(config('filesystems.public'))->delete('designs/' . $fileName);
        if ($rs) {
            $designitem = Designs::where('id', $design_item_id)->first();
            if ($designitem) { // Kiểm tra xem $design có tồn tại hay không
                $designmetas = DesignMetas::select('id')->where('design_id', $designitem->id)->get();
                foreach ($designmetas as $designmeta) {
                    $designmeta->delete();
                }
                return response()->json(["message" => true, "data" => $rs], 200);
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
            $categoryDesign = CategoryDesigns::where('design_item_id', $request->design_item_id)
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

        $designItem = Designs::find($request->design_item_id);
        $designItem->number_side = $request->number_side;
        $rs = $designItem->save();
        if ($rs) {
            return response()->json(["message" => true, "data" => $rs], 200);
        } else {
            return response()->json(["message" => false, "data" => []], 404);
        }
    }
    public function cenvert(Request $request)
    {
        // $designItems = Designs::find($id);
        // $front_url = $designItems->front_design;
        // $title = $designItems->title;
        // $back_url = $designItems->back_design;
        // $sleeve_left_url = $designItems->sleeve_left_design;
        // $sleeve_right_url = $designItems->sleeve_right_design;
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
        // dd($json, $colors, $darks, $lights);
        return view('designs.cenvert.index', compact('json', 'colors', 'darks', 'lights'));
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
            [1, 4, 6, 200, 300, 500, 900, 1000, 7000, 8000, 12000, 1004, 1005],
            [2, 5, 100, 101, 800, 2000, 4000, 5000, 'c_1', 'c_2', 'c_3']
        ];
        $listshirtid = $list[$numberside - 1];
        return view('designs.cenvert.ajax.design', compact('listshirtid', 'json', 'type'));
    }
    public function ajaxMockupHuman(Request $request)
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

        $templates = Templetes::get();
        $list = [
            [3, 400, 700, 3000, 9000, 10000, 11000, 8],
            [7, 600, 6000, 'c_1', 'c_2', 'c_3', 1001, 1002, 1003, 900, 9, 103, 104]
        ];
        $listhumanid = $list[$numberside - 1];
        return view('designs.cenvert.ajax.human', compact('listhumanid', 'json', 'type', 'templates'));
    }
    public function createProductDesign(Request $request)
    {

        $mockupjson = json_decode($request->mockupchoose);
        $mockuphumanjson = json_decode($request->mockupchooseHuman);
        $imagefirst = $request->firstmockup;
        $template = Templetes::find($request->template);
        $product = json_decode($template->data)->product;
        if(isset($request->design_id)&&!empty($request->design_id)){
            $design = Designs::find($request->design_id);
            $design->product_listing = 1;
            $design->save();
        }

        if ($request->images) {
            $images = [];
            foreach ($request->images as $designimage) {
                $filename = random_int(10000, 99999) . "_" . $designimage->getClientOriginalName();            // Store the uploaded file temporarily
                $path = $designimage->store('products');  // Store the uploaded file in storage/designs

                // Retrieve the file using the path stored earlier
                $imagePath = Storage::path($path);  // Get the full local path of the file
                $imgName = 'designs/' . $filename;
                // Create an Imagick object
                $imageservice = new ImageService();
                $url = str_replace(' ', '%20', $imagePath); // Thay thế khoảng trắng

                $urlDesign = $imageservice->uploadImage($url, $imgName);
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

        $randomImages = [];

        $randomImages[] = $this->formatImage($imagefirst, $product->id);


        foreach ($mockuphumanjson as $key => $mockuphuman) {
            if($key<=5){
                array_push($randomImages, $this->formatImage($mockuphuman->src, $product->id));
            }
        }

        $imagevariants = [];

        foreach ($mockupjson as $mockup) {
            $imagevariants[] = [
                "color" => getColorByHex($mockup->color),
                "src" => $mockup->src
            ];
        }
        $randomImages = array_merge($randomImages, $images);
        $product->images = $randomImages;
        $product->imagevariants = $imagevariants;
        $product->templete_id = $request->selectedtemplete;
        $user_id = Auth::user()->id;
        CreateImageMockupJobs::dispatch($user_id, $product->id, $template->discount, $product, $request->design_id)->delay(1)->onQueue('create-product-mockup');

        return response()->json(["message" => true, "data" => ""], 200);
    }
    function randomId()
    {
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
    function genPositions(Request $request)
    {
        // dd($designItem);
        $frontCoordinatesvalue = explode(",", $request->frontCoordinatesvalue);
        $backCoordinatesvalue = explode(",", $request->backCoordinatesvalue);

        $reconvertCoordinatesfront = $this->reconvertCoordinates($frontCoordinatesvalue[2], $frontCoordinatesvalue[3], 1200, 1200);
        $xfront = (int) $reconvertCoordinatesfront['x'];
        $yfront = (int) $reconvertCoordinatesfront['y'];
        $widthfront = $frontCoordinatesvalue[0] ? (int) round($frontCoordinatesvalue[0] * (1000 / 1200), 0) + 5 : 0;
        $heightfront = $frontCoordinatesvalue[1] ? (int) round($frontCoordinatesvalue[1] * (1000 / 1200), 0) + 5 : 0;

        $reconvertCoordinatesback = $this->reconvertCoordinates($backCoordinatesvalue[2], $backCoordinatesvalue[3], 1200, 1200);
        $xback = (int) $reconvertCoordinatesback['x'];
        $yback = (int) $reconvertCoordinatesback['y'];
        $widthback = $backCoordinatesvalue[0] ? (int) round($backCoordinatesvalue[0] * (1000 / 1200), 0) + 5 : 0;
        $heightback = $backCoordinatesvalue[1] ? (int) round($backCoordinatesvalue[1] * (1000 / 1200), 0) + 5 : 0;
        // dd($xfront, $yfront, $widthfront, $heightfront, $xback, $yback, $widthback, $heightback);
        $mockupUrl = $request->mockupUrl;
        $fontdesign = $request->front_url;
        $backdesign = $request->back_url;
        // dd($position);
        return view('designs.cenvert.temp', compact('fontdesign', 'backdesign', 'mockupUrl', 'xfront', 'yfront', 'widthfront', 'heightfront', 'xback', 'yback', 'widthback', 'heightback'));
    }
    function genPositionhumans(Request $request)
    {
        // dd($designItem);
        $frontCoordinatesvalue = explode(",", $request->frontCoordinatesvalue);
        $backCoordinatesvalue = explode(",", $request->backCoordinatesvalue);

        $reconvertCoordinatesfront = $this->reconvertCoordinates($frontCoordinatesvalue[2], $frontCoordinatesvalue[3], 1200, 1200);
        $xfront = (int) $reconvertCoordinatesfront['x'];
        $yfront = (int) $reconvertCoordinatesfront['y'];
        $widthfront = $frontCoordinatesvalue[0] ? (int) round($frontCoordinatesvalue[0] * (1000 / 1200), 0) + 5 : 0;
        $heightfront = $frontCoordinatesvalue[1] ? (int) round($frontCoordinatesvalue[1] * (1000 / 1200), 0) + 5 : 0;

        $reconvertCoordinatesback = $this->reconvertCoordinates($backCoordinatesvalue[2], $backCoordinatesvalue[3], 1200, 1200);
        $xback = (int) $reconvertCoordinatesback['x'];
        $yback = (int) $reconvertCoordinatesback['y'];
        $widthback = $backCoordinatesvalue[0] ? (int) round($backCoordinatesvalue[0] * (1000 / 1200), 0) + 5 : 0;
        $heightback = $backCoordinatesvalue[1] ? (int) round($backCoordinatesvalue[1] * (1000 / 1200), 0) + 5 : 0;
        // dd($xfront, $yfront, $widthfront, $heightfront, $xback, $yback, $widthback, $heightback);
        $mockupUrl = $request->mockupUrl;
        $fontdesign = $request->front_url;
        $backdesign = $request->back_url;
        // dd($position);
        return view('designs.cenvert.temphuman', compact('fontdesign', 'backdesign', 'mockupUrl', 'xfront', 'yfront', 'widthfront', 'heightfront', 'xback', 'yback', 'widthback', 'heightback'));
    }
    function reconvertCoordinates($x_old, $y_old, $oldWidth, $oldHeight)
    {
        $newWidth = 1000;
        $newHeight = 1000;

        $x_new = ($x_old / $oldWidth) * $newWidth;
        $y_new = ($y_old / $oldHeight) * $newHeight;

        return ['x' => round($x_new, 0), 'y' => round($y_new, 0)];
    }
    // function getDesignUrl(Request $request)
    // {
    //     if (isset($request->id)) {
    //         // dd($request->id);
    //         $design = Designs::select(['title', 'front_design', 'back_design', 'sleeve_left_design', 'sleeve_right_design'])
    //             ->where('id', (int) $request->id)
    //             ->first();
    //         // dd($design);
    //         if ($design) {
    //             return response()->json([
    //                 'title' => $design->title,
    //                 'front_design' => $design->front_design ?? "",
    //                 'back_design' => $design->back_design ?? "",
    //                 'sleeve_left_design' => $design->sleeve_left_design ?? "",
    //                 'sleeve_right_design' => $design->sleeve_right_design ?? "",
    //             ]);
    //         }

    //         return response()->json(['error' => 'Design not found'], 404);
    //     }
    // }
    // public function getDesignUrl(Request $request)
    // {
    //     if (isset($request->id)) {
    //         // dd($request->id);
    //         $design = Designs::select('title')
    //             ->where('id', (int) $request->id)

    //             ->first();
    //         // dd($design);
    //         if ($design) {
    //             $designMetas = DesignMetas::where('design_id', (int) $request->id)
    //                 ->get();
    //             $front_design = null;
    //             $back_design = null;
    //             $sleeve_left_design = null;
    //             $sleeve_right_design = null;

    //            // Kiểm tra các key, bao gồm các key với "_bl", "_wt" và các key mặc định
    //            foreach ($designMetas as $meta) {
    //             if ($meta->key == 'front_design' || $meta->key == 'front_design_bl' || $meta->key == 'front_design_wt') {
    //                 $front_design = $meta->value;
    //             } elseif ($meta->key == 'back_design' || $meta->key == 'back_design_bl' || $meta->key == 'back_design_wt') {
    //                 $back_design = $meta->value;
    //             } elseif ($meta->key == 'sleeve_left_design' || $meta->key == 'sleeve_left_design_bl' || $meta->key == 'sleeve_left_design_wt') {
    //                 $sleeve_left_design = $meta->value;
    //             } elseif ($meta->key == 'sleeve_right_design' || $meta->key == 'sleeve_right_design_bl' || $meta->key == 'sleeve_right_design_wt') {
    //                 $sleeve_right_design = $meta->value;
    //             }
    //         }
    //             return response()->json([
    //                 'title' => $design->title,
    //                 'front_design' => $front_design ?? "",
    //                 'back_design' => $back_design ?? "",
    //                 'sleeve_left_design' => $sleeve_left_design ?? "",
    //                 'sleeve_right_design' => $sleeve_right_design ?? "",
    //             ]);
    //         }

    //         return response()->json(['error' => 'Design not found'], 404);
    //     }

    //     return response()->json(['error' => 'No Design ID provided'], 400);
    // }
    public function getDesignUrl(Request $request)
    {
        if (isset($request->id)) {
            $design = Designs::select('title','product_listing')
                ->where('id', (int) $request->id)
                ->first();

            if ($design) {
                $designMetas = DesignMetas::where('design_id', (int) $request->id)->get();
                $design_data = [];

                // Duyệt qua tất cả các meta và lưu giá trị vào mảng
                foreach ($designMetas as $meta) {
                    // Kiểm tra và thêm vào mảng design_data tất cả các key
                    $design_data[$meta->key] = $meta->thumbnail;
                }

                // Trả về response với tất cả các key và giá trị
                return response()->json([
                    'title' => $design->title,
                    'design_data' => $design_data, // Trả về tất cả các key và giá trị
                    'product_listing' => $design->product_listing, // Trả về tất cả các key và giá trị
                ]);
            }

            return response()->json(['error' => 'Design not found'], 404);
        }

        return response()->json(['error' => 'No Design ID provided'], 400);
    }
    function import_design(Request $request)
    {
        $file_handle = fopen($request->file, 'r');
        while ($csvRow = fgetcsv($file_handle, null, ',')) {
            $line_of_text[] = $csvRow;
        }
        fclose($file_handle);
        foreach ($line_of_text as $key => $design) {
            if ($key != 0 && $design[0] != "") {
                $designitem = Designs::create();
                $designitem['title'] = $design[4];
                $designitem['niche'] = $design[1];
                $designitem['mix'] = $design[2];
                $designitem['user_code'] = $design[0];
                $designitem['sku'] = $design[0] . $design[1] . $design[2] . $designitem['id'];
                $designitem['bl_and_wt'] = $design[3] == "yes" ? 1 : 0;
                $designitem['tag'] = $design[5];
                if ($design[3] == "yes") {
                    $designitem['front_design_bl'] = $design[6];
                    $designitem['back_design_bl'] = $design[7];
                    $designitem['sleeve_left_design_bl'] = $design[8];
                    $designitem['sleeve_right_design_bl'] = $design[9];
                    $designitem['front_design_wt'] = $design[10];
                    $designitem['front_design_wt'] = $design[11];
                    $designitem['front_design_wt'] = $design[12];
                    $designitem['front_design_wt'] = $design[13];
                } else {
                    $designitem['front_design'] = $design[6];
                    $designitem['back_design'] = $design[7];
                    $designitem['sleeve_left_design'] = $design[8];
                    $designitem['sleeve_right_design'] = $design[9];
                }

                $designitem->save();
            }
        }
        return redirect(route('designs.index'));
    }

    function list_image(Request $request, $id)
    {
        // dd($id);
        $designs = DesignMetas::where('design_id', $id)
            ->whereIn('key', [
                'front_design',
                'back_design',
                'sleeve_left_design',
                'sleeve_right_design',
                'front_design_wt',
                'back_design_wt',
                'sleeve_left_design_wt',
                'sleeve_right_design_wt',
                'front_design_bl',
                'back_design_bl',
                'sleeve_left_design_bl',
                'sleeve_right_design_bl'
            ])
            ->get();
        // dd($designs);
        return view('designs.show.mockup', compact('designs'));
    }

    public function export_design(Request $request)
    {
        $arrayId = explode(",", $request->arrayId);
        $filename = "export_design_" . Carbon::now()->format('d/m/Y_h:m:s') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $output = fopen('php://output', 'w');

        if (!$output) {
            return response()->json(['error' => 'Không thể ghi nội dung CSV'], 500);
        }

        fputcsv($output, ['Title', 'Sku']);

        foreach ($arrayId as $id) {
            $design = Designs::find($id);
            if (!$design) {
                continue;
            }

            $designMetas = $design->designMetas;
            foreach ($designMetas as $meta) {
                // Sử dụng basename lấy tên file từ value
                $skuFileName = pathinfo(basename($meta->value), PATHINFO_FILENAME);
                fputcsv($output, [$design->title, $skuFileName]);
            }
        }

        fclose($output);

        return response()->stream(function () use ($output) {
        }, 200, $headers);
    }

    public function download_design($id)
    {
        $design = Designs::findOrFail($id);
        $filename = "design_{$id}_" . Carbon::now()->format('Y_m_d_H_i_s') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Title', 'Sku']);
        foreach ($design->designMetas as $meta) {
            $skuFileName = pathinfo(basename($meta->value), PATHINFO_FILENAME);
            fputcsv($output, [$design->title, $skuFileName]);
        }
        fclose($output);

        return response()->stream(function () use ($output) {
        }, 200, $headers);
    }

    public function count_image($design)
    {
        $countImage = 0;
        if ($design->bl_and_wt) {
            if ($design->front_design_bl) {
                $countImage = $countImage + 1;
            }
            if ($design->back_design_bl) {
                $countImage = $countImage + 1;
            }
            if ($design->sleeve_left_design_bl) {
                $countImage = $countImage + 1;
            }
            if ($design->sleeve_right_design_bl) {
                $countImage = $countImage + 1;
            }
        } else {
            if ($design->front_design) {
                $countImage = $countImage + 1;
            }
            if ($design->back_design) {
                $countImage = $countImage + 1;
            }
            if ($design->sleeve_left_design) {
                $countImage = $countImage + 1;
            }
            if ($design->sleeve_right_design) {
                $countImage = $countImage + 1;
            }
        }
        return $countImage;
    }

    public function count_image_request($request)
    {
        $countImage = 0;
        if ($request->bl_and_wt == "true") {
            if ($request->front_design_bl) {
                $countImage = $countImage + 1;
            }
            if ($request->back_design_bl) {
                $countImage = $countImage + 1;
            }
            if ($request->sleeve_left_design_bl) {
                $countImage = $countImage + 1;
            }
            if ($request->sleeve_right_design_bl) {
                $countImage = $countImage + 1;
            }
        } else {
            if ($request->front_design) {
                $countImage = $countImage + 1;
            }
            if ($request->back_design) {
                $countImage = $countImage + 1;
            }
            if ($request->sleeve_left_design) {
                $countImage = $countImage + 1;
            }
            if ($request->sleeve_right_design) {
                $countImage = $countImage + 1;
            }
        }
        return $countImage;
    }
}
