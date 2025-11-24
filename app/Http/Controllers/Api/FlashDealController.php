<?php
namespace Vanguard\Http\Controllers\Api;

use Vanguard\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Vanguard\Models\FlashDeals;
use Vanguard\Models\Store;
use Vanguard\Models\ProductFlashDeals;
use Vanguard\Models\Products;

class FlashDealController extends Controller
{
    
    public function createFlashDeal(Request $request)
    {

        $validatedData = $request->validate([
            'activity_id' => 'required|integer',
            'store_id' => 'required|integer',
            'promotion_name' => 'required|string',
            'activity_type' => 'required|string',
            'product_level' => 'required|string',
            'begin_time' => 'required|date',
            'end_time' => 'required|date',
            'auto' => 'required|boolean',
        ]);

        try {
            $storeId = $validatedData['store_id'];
            $store = Store::find($storeId);

            if (!$store) {
                return response()->json([
                    'message' => 'Store not found',
                    'error' => 'The store ID provided does not exist.'
                ], 404);
            }

            $begin_time = Carbon::parse($validatedData['begin_time'])->timestamp;
            $end_time = Carbon::parse($validatedData['end_time'])->timestamp;

            $flashdeal = FlashDeals::create([
                'activity_id' => $validatedData['activity_id'],
                'store_id' => $validatedData['store_id'],
                'promotion_name' => $validatedData['promotion_name'],
                'activity_type' => $validatedData['activity_type'],
                'product_level' => $validatedData['product_level'],
                'begin_time' => $begin_time,
                'end_time' => $end_time,
                'auto' => $validatedData['auto'],
                'status' => 1, 
            ]);

            return response()->json([
                'message' => 'Flashdeal created successfully',
                'data' => $flashdeal
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating flashdeal',
                'error' => $e->getMessage()
            ], 400);
        }
    }
 
    public function addProductToFlashDeal(Request $request)
    {
       
        $validatedData = $request->validate([
            'flashdeal_id' => 'required|integer', 
            'product_id' => 'required|integer',
            'discount' => 'required|numeric',
            'quantity_limit' => 'required|integer',
            'quantity_per_user' => 'required|integer',
            'skus' => 'required', 
            'total_sku' => 'required|integer',
        ]);

        try {
            
            $flashdeal = FlashDeals::find($validatedData['flashdeal_id']);
            if (!$flashdeal) {
                return response()->json([
                    'message' => 'Flashdeal not found',
                ], 404);
            }

           
            $product = Products::find($validatedData['product_id']);
            if (!$product) {
                return response()->json([
                    'message' => 'Product not found',
                ], 404);
            }

            $productFlashDeal = ProductFlashDeals::create([
                'flashdeal_id' => $validatedData['flashdeal_id'],
                'product_id' => $validatedData['product_id'],
                'discount' => $validatedData['discount'],
                'quantity_limit' => $validatedData['quantity_limit'],
                'quantity_per_user' => $validatedData['quantity_per_user'],
                'skus' => json_encode($validatedData['skus']),
                'total_sku' => $validatedData['total_sku'],
            ]);

            return response()->json([
                'message' => 'Product added to flash deal successfully',
                'data' => $productFlashDeal
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error adding product to flash deal',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
