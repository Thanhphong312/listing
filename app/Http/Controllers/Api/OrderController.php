<?php

namespace Vanguard\Http\Controllers\Api;

use Illuminate\Http\Response;
use Vanguard\Http\Controllers\Api\ApiController;
use Vanguard\Models\Order\Order;
use Vanguard\Models\Order\OrderItem;
use Vanguard\Http\Requests\Order\CreateOrderRequest;
use Vanguard\Models\Order\OrderItemMeta;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Vanguard\ProductVariants;
use Vanguard\User;
use Vanguard\Models\Store\Store;
use Vanguard\Models\TimeLine;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Spatie\PdfToImage\Pdf;
use Carbon\Carbon;
use Vanguard\Models\Tracking;
use Vanguard\Models\Transaction;
use Vanguard\Services\Blaze\BlazeService;
use Illuminate\Support\Facades\File;
use Vanguard\Jobs\BackUpDataJsonCloudJob;

class OrderController extends ApiController
{
    public function postOrder(CreateOrderRequest $request)
    {
        try {
            $dataJson = $request->validated();
            \Log::info('dataJson:', $dataJson);
            $store = $this->getStore($dataJson['api_key']);
            if (!$store) {
                return $this->jsonResponse('', 'chua co api key', 'error');
            }

            if (!$this->isSeller($store)) {
                return $this->jsonResponse('', 'khong phai seller, or api ky duplicate', 'error');
            }

            $data = $this->prepareData($dataJson, $store);
            $order = $this->createOrder($data, json_encode($dataJson));

            $lineItems = $dataJson['line_items'];
            $productVariants = $this->getProductVariants(array_column($lineItems, 'variant_id'));

            if (!$this->validateProductVariants($lineItems, $productVariants)) {
                return $this->jsonResponse('', 'khong co variant_id trong bang product', 'error');
            }

            $this->createOrderItems($lineItems, $order, $productVariants, $dataJson['order_status']);
            $this->updateOrderTotals($order, $store->user);

            $this->createTimelineEntry($order, $store->user);

            return $this->jsonResponse($order->id, '', 'success');
        } catch (\Exception $e) {
            return $this->jsonResponse([], $e->getMessage(), 'error');
        }

    }
    // Function Definitions

    function jsonResponse($data, $msg, $status)
    {
        return json_encode(['data' => $data, 'msg' => $msg, 'status' => $status]);
    }

    function getStore($apiKey)
    {
        return Store::with(['user', 'user.role'])->select('id', 'user_id')->where('api_key', $apiKey)->first();
    }

    function isSeller($store)
    {
        return $store->user->role->name === 'Seller';
    }

    function prepareData($dataJson, $store)
    {
        $dataInfo = [
            'ref_id' => $dataJson['ref_id'],
            'shipping_service' => $dataJson['shipping_service'] ?? null,
            'shipping_method' => $dataJson['shipping_method'] ?? 'standard',
            'fulfill_status' => $dataJson['order_status'],
            'store_id' => $store['id'],
            'seller_id' => $store['user_id'],
            'shipping_label' => $dataJson['shipping_label'] ?? null,
        ];

        $dataAddress = [];
        if (empty($dataJson['shipping_label'])) {
            $dataAddress = $this->extractAddress($dataJson['address']);
        }

        return array_merge($dataInfo, $dataAddress);
    }

    function extractAddress($address)
    {
        $fullName = $address['name'];
        $nameParts = explode(' ', $fullName, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? null;

        $addressFields = ['name', 'phone', 'street1', 'street2', 'city', 'state', 'zip', 'country'];
        foreach ($addressFields as $field) {
            if (strpos($address[$field], '*') !== false) {
                return $this->jsonResponse('', 'Order post address must not contain *', 'error');
            }
        }

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $address['phone'],
            'address_1' => $address['street1'],
            'address_2' => $address['street2'],
            'city' => $address['city'],
            'state' => $address['state'],
            'postcode' => $address['zip'],
            'country' => $address['country']
        ];
    }

    function createOrder($data, $dataJson)
    {
        $order = Order::create($data);
        $orderStt = date('m_d') . ' ' . number_format($order->id, 0, '', '.');
        $order->update(['order_stt' => $orderStt]);
        // $blazeService = new BlazeService();
        $this->pushOrderJsonToCloud($dataJson, $order->id);
        return $order;
    }
    public function pushOrderJsonToCloud($dataOrderJson, $nameFile)
    {
        try{
            $directory = public_path('data_json');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
    
            file_put_contents(public_path('data_json/' . $nameFile), $dataOrderJson);
    
            $sourceFilePath = public_path('data_json/' . $nameFile);
    
            $fileName = 'data_json/' . $nameFile;
            $filePath = Storage::disk('b2')->put($fileName, file_get_contents($sourceFilePath), 'public');
            
            $filePathLocal = public_path('data_json/' . $nameFile);
    
            if (file_exists($filePathLocal)) {
                File::delete($filePathLocal);
            }
        }catch(\Exception $e){

        }
    }
    function getProductVariants($variantIds)
    {
        return ProductVariants::select('id', 'variant_id', 'active', 'stock')
            ->whereIn('variant_id', $variantIds)
            ->get()->keyBy('variant_id');
    }

    function validateProductVariants($lineItems, $productVariants)
    {
        foreach ($lineItems as $item) {
            $productVariant = $productVariants[$item['variant_id']] ?? null;
            if (!$productVariant || $productVariant->active == 0 || $productVariant->stock == 0) {
                return false;
            }
        }
        return true;
    }

    function createOrderItems($lineItems, $order, $productVariants, $orderStatus)
    {
        $arrMeta = [];
        foreach ($lineItems as $item) {
            $productVariant = $productVariants[$item['variant_id']];
            if ($orderStatus != 'test_order') {
                $newStock = max(0, $productVariant->stock - $item['quantity']);
                $productVariant->update(['stock' => $newStock]);
            }

            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['product_name'],
                'variant_id' => $item['variant_id'],
                'status' => null,
                'quantity' => $item['quantity'],
                'mockup' => $item['mockup'] ?? null,
                'mockup_back' => $item['mockup_back'] ?? null,
            ]);

            $metaKeys = [];
            foreach ($item['print_files'] as $file) {
                $key = $file['key'];
                $url = $file['url'];
                if ($key && in_array($key, ['front', 'back', 'sleeve_right', 'sleeve_left'])) {
                    $arrMeta[$orderItem->id][$key . '_design'] = $url;
                    // $arrMeta[$orderItem->id][$key . '_design_printed'] = 0;
                    $metaKeys[] = $key . '_design';
                    // $metaKeys[] = $key . '_design_printed';
                }
            }

            foreach ($metaKeys as $key) {
                OrderItemMeta::updateOrCreate(
                    ['order_item_id' => $orderItem->id, 'meta_key' => $key],
                    ['meta_value' => $arrMeta[$orderItem->id][$key] ?? null]
                );
            }
        }
    }

    function updateOrderTotals($order, $user)
    {
        $printCost = 0;
        foreach ($order->items as $itemOrd) {
            $printCostItem = $user->private_seller == 1 ? printBaseCostNewForItemPrivate($itemOrd->id) : printBaseCostNewForItemPublic($itemOrd->id);
            $printCost += $printCostItem;
            $itemOrd->update(['price' => $printCostItem]);
        }

        $typeship = empty($order->shipping_label) ? 'seller' : 'tiktok';
        \Log::info($order->id.':typeship:' . $typeship);
        $shipping_cost = shipBaseCostNew($order->id, $typeship, $user);
        $order->update([
            'shipping_cost' => $shipping_cost,
            'print_cost' => $printCost,
            'total_cost' => $printCost + $shipping_cost,
        ]);
    }

    function createTimelineEntry($order, $user)
    {
        Timeline::create([
            'object' => 'order',
            'object_id' => $order->id,
            'owner_id' => $user->id,
            'action' => 'create order',
            'note' => $user->username . ' create ' . $order->id . ' order ',
        ]);
    }
    ///////////////////////////////////////////
    public function orderImport(Request $request)
    {
        if (!empty($request->variant_id)) {
            $productVariant = ProductVariants::with('product')->where('variant_id', $request->variant_id)->first();
            if ($productVariant) {
                $product = $productVariant->product->first(); // Truy cập mối quan hệ products()
                // dd($product);
                return json_encode([
                    "order_item_id" => $productVariant->variant_id,
                    "sku" => $productVariant->sku,
                    "product_name" => $product->name,
                    "quantity" => $productVariant->quantity,
                    "status" => "",
                    "price" => $productVariant->price,
                    "color" => $productVariant->color,
                    "size" => $productVariant->size,
                    "weight" => $productVariant->weight,
                    "length" => $productVariant->length,
                    "width" => $productVariant->width,
                    "height" => $productVariant->height,
                    "mockup" => "",
                    "print_files" => [],
                    "preview_files" => []
                ]);
            }
        }
    }
    public function getDetailOrder(Request $request)
    {
        try {
            if (isset($request->api_key) && !empty($request->api_key)) {
                $store = Store::select('id')->where('api_key', $request->api_key)->first();
                if (!$store) {
                    return json_encode([
                        'data' => $request->id,
                        'msg' => 'api key is not match with store',
                        'status' => 'error'
                    ]);
                }
                $order = Order::with('tracking')->select([
                    'id',
                    'ref_id',
                    'store_id',
                    'shipping_label',
                    // 'label_printed',
                    'shipping_service',
                    'shipping_method',
                    'tracking_id',
                    // 'tracking_status',
                    // 'tracking_link',
                    'fulfill_status',
                    'total_cost',
                    'paid_cost',
                    'print_cost',
                    'shipping_cost',
                    "first_name",
                    "last_name",
                    "phone",
                    "address_1",
                    "address_2",
                    "city",
                    "state",
                    "postcode",
                    "country",
                    "payment_status"
                ])->where('id', $request->id)->first();
                //$order bỏ store_id khỏi $order
                // $order->makeHidden('store_id');
                // dd($order->tracking);
                $order->shipping_service = "USPS";
                if (!$order) {
                    // Order not found, handle gracefully
                    return response()->json(['error' => 'Order not found'], 404);
                }

                if ($order->store_id == $store->id) {

                    // $json = $order;
                    $order['items'] = OrderItem::select([
                        "id",
                        "price",
                        "sku",
                        "status",
                        "quantity",
                        "product_name",
                        "mockup",
                        "mockup_back",
                        "variant_id",
                    ])->where('order_id', $request->id)->get();
                    return json_encode($order, JSON_UNESCAPED_SLASHES);
                } else {
                    return json_encode([
                        'data' => $order->id,
                        'msg' => 'api key is not match with store id',
                        'status' => 'error'
                    ]);
                }
            } else {
                return json_encode([
                    'data' => "",
                    'msg' => 'the api key field is required.',
                    'status' => 'error'
                ]);
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    public function cancelOrder(Request $request)
    {
        $order = Order::select('fulfill_status', 'store_id')->find($request->id);
        if (isset($request->api_key) && !empty($request->api_key)) {
            $store = Store::select('id')->where('api_key', $request->api_key)->first();
            if (!$store) {
                return json_encode([
                    'data' => $order->id,
                    'msg' => 'api key is not match with store',
                    'status' => 'error'
                ]);
            }
            if ($order->store_id == $store->id) {
                $order = Order::with('user')->find($request->id);
                if ($order->fulfill_status != 'new_order') {
                    return json_encode([
                        'data' => $order->id,
                        'msg' => 'Order was only canceled when status is new order',
                        'status' => 'error'
                    ]);
                }
                // dd($order->user);
                $this->refund($order->user, $order);
                $rs = $order->update([
                    'fulfill_status' => 'cancelled'
                ]);
                if ($rs) {
                    return json_encode([
                        'data' => $order->id,
                        'msg' => 'Order was canceled successfully',
                        'status' => 'success'
                    ]);
                } else {
                    return json_encode([
                        'data' => $order->id,
                        'msg' => 'Order was not canceled',
                        'status' => 'error'
                    ]);
                }
            } else {
                return json_encode([
                    'data' => $order->id,
                    'msg' => 'api key is not match with store',
                    'status' => 'error'
                ]);
            }
        } else {
            return json_encode([
                'data' => "",
                'msg' => 'the api key field is required.',
                'status' => 'error'
            ]);
        }


    }
    public function refund($seller, $order)
    {
        $seller->wallet_balance = $seller->wallet_balance + $order->total_cost;
        $transaction = new Transaction();
        // dd($order->id);
        $transaction->seller_id = $seller->id;
        $transaction->order_id = $order->id;
        $transaction->amount = abs($order->total_cost);
        $transaction->remaining_balance = $seller->wallet_balance;
        $transaction->type = 'refund';
        $transaction->status = 'approved';
        $transaction->note = "Refund for order ID {$order->id}";
        $transaction->save();

        $seller->save();
    }
    public function labelConvert(Request $request)
    {
        $sort = "asc";
        if (isset($request->sort)) {
            $sort = $request->sort;
        }
        $orders = Order::select('id', 'shipping_label', 'order_stt')
            ->with([
                'items' => function ($query) {
                    $query->with('product'); // Include product information for each item
                }
            ])
            // ->where('update_at_cenvert_label', '<=', Carbon::now()->subMinutes(10))
            ->where('fulfill_status', '!=', 'test_order')
            ->where('fulfill_status', '!=', 'cancelled')
            ->where('fulfill_status', '!=', 'shipped')
            // ->where('fulfill_status', '!=', 'fulfill_partner')
            ->whereNull('convert_label')
            ->whereNotNull('shipping_label')
            ->orderByRaw("CASE 
                            WHEN fulfill_status = 'priority' THEN 0 
                            ELSE 1 
                        END") // Prioritize 'priority' fulfill_status
            ->orderBy('orders.id', $sort)
            ->limit(70)
            ->get()
            ->toArray();
        return json_encode($orders);
    }
    public function updateLabel(Request $request)
    {
        $order = Order::find($request->id);
        \Log::info("Update convert label api:");
        \Log::info($request->all());
        // $order->update_at_cenvert_label = Carbon::now()->toDateTimeString();
        if ($request->status == 'error') {
            $order->save();
            return json_encode([
                'data' => $order->id,
                'msg' => 'Order was updated time successfully',
                'status' => 'success'
            ]);
        }
        $order->convert_label = $request->link;
        $order->save();
        if ($order->tracking_id == null) {
            //kiểm tra $request->tracking_id phải là số
            if (is_numeric($request->tracking_id)) {
                $order->tracking_id = $request->tracking_id;

                if (!Tracking::where('tracking_id', $request->tracking_id)->first()) {
                    if ($order->tracking_id != NULL) {
                        $tracking = new Tracking();
                        $tracking->tracking_id = $request->tracking_id;
                        $tracking->order_id = $order->id;
                        $tracking->status = 'pending';
                        $tracking->created_at = $order->created_at;
                        $tracking->save();

                        $orderItemMetas = new OrderItemMeta();
                        $orderItemMetas->tracking_id = $tracking->id;
                        $orderItemMetas->meta_key = 'resole_tracking';
                        $orderItemMetas->meta_value = "";
                        $orderItemMetas->save();
                        \Log::info("tracking order id: " . $order->id . " add table tracking: " . $request->tracking_id);
                    }
                }
                $rs = $order->save();
            }
            //\Log::info("Tracking Label api:");
            //\Log::info($tracking_numbers_string);
        } else {
            if (!Tracking::where('tracking_id', $order->tracking_id)->first()) {
                if ($order->tracking_id != NULL) {
                    $tracking = new Tracking();
                    $tracking->tracking_id = $order->tracking_id;
                    $tracking->order_id = $order->id;
                    $tracking->status = 'pending';
                    $tracking->created_at = $order->created_at;
                    $tracking->save();

                    $orderItemMetas = new OrderItemMeta();
                    $orderItemMetas->tracking_id = $tracking->id;
                    $orderItemMetas->meta_key = 'resole_tracking';
                    $orderItemMetas->meta_value = "";
                    $orderItemMetas->save();
                    \Log::info("tracking order id: " . $order->id . "add table tracking: " . $order->tracking_id);
                }
            }
        }
        return json_encode([
            'data' => $order->id,
            'msg' => 'Order was updated successfully',
            'status' => 'success'
        ]);

    }
    public function updateDesignQr(Request $request)
    {
        // \Log::info("Update Design Qr api:");
        // \Log::info($request->all());
        $printedDesign = OrderItemMeta::with('orderItem')->select([
            'id',
            'order_item_id',
            'meta_key',
            'meta_value',
            'update_time',
        ])->where('order_item_id', $request->order_item_id)->where('meta_key', $request->type)->first();
        // if($request->overide == 1){
        //     $printedDesign->overide_qr_design = 1;
        // }
        $printedDesign->update_time = Carbon::now()->toDateTimeString();
        $printedDesign->save();
        if ($request->status == 'success') {

            $this->storeMeta($request->order_item_id, $request->type . '_qr', $request->link);
            return json_encode([
                'data' => $request->order_item_id,
                'msg' => 'Order was updated time successfully',
                'status' => 'success'
            ]);
        } else if ($request->status == 'wrongsize') {
            if ($printedDesign->orderItem->order->fulfill_status != 'shipped' && $printedDesign->orderItem->order->fulfill_status != 'wrongsize'&& $printedDesign->orderItem->order->fulfill_status != 'cancelled'&& $printedDesign->orderItem->order->fulfill_status != 'test_order') {
                \Log::info($printedDesign->orderItem->order->id . " Update Design Wrongsize api");
                $printedDesign->orderItem->order->update([
                    'fulfill_status' => 'wrongsize'
                ]);
            }

        }

        return json_encode([
            'data' => [
                'order_item_id' => $request->order_item_id,
                'type' => $request->type,
                'link' => $request->link,
                'status' => $request->status
            ],
            'msg' => 'Order was updated successfully',
            'status' => 'success'
        ]);
    }
    public function storeMeta($orderID, $name, $value)
    {
        $meta = OrderItemMeta::updateOrCreate(
            [
                'order_item_id' => $orderID,
                'meta_key' => $name
            ],
            [
                'meta_value' => $value,
                'append_qr_design' => 1,
                'update_time' => Carbon::now()->toDateTimeString()
            ]
        );
        \Log::info('storeMeta result:', [
            'id' => $meta->id,
            'order_item_id' => $meta->order_item_id,
            'meta_key' => $meta->meta_key,
            'meta_value' => $meta->meta_value,
            'append_qr_design' => $meta->append_qr_design,
            'update_time' => $meta->update_time
        ]);
        return $meta->id;
    }

    public function deleteMeta($orderID, $name)
    {
        $meta = OrderItemMeta::where('order_item_id', $orderID)->where('meta_key', $name)->first();
        if ($meta) {
            $meta->delete();
        }
    }
}
