<?php

namespace Vanguard\Repositories\Order;

use Vanguard\Repositories\Order\OrderRepository;
use Vanguard\Product;
use Maatwebsite\Excel\Facades\Excel;
use Vanguard\Imports\ProductsImport;
use Maatwebsite\Excel\Validators\ValidationException;
use Vanguard\Imports\OrdersImport;
use Vanguard\Models\Order\Order;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Vanguard\Models\Order\OrderItem;

class EloquentOrderRepository implements OrderRepository
{
    /**
     * Get all orders
     * 
     * @param $request
     */
    public function getOrders($request)
    {
        $threeMonth = Carbon::now()->subDays(45)->toDateTimeString();
        $user = Auth::user();
        $role = $user->role->name;

        $orders = Order::where('created_at', '>=', $threeMonth)
            ->select(
                'id',
                'ref_id',
                'shipping_label',
                'fulfill_status',
                'seller_id',
                'store_id',
                'created_at',
                'updated_at'
            );
        if (!empty($request->order_id) || !empty($request->ref_id) || !empty($request->name)) {
            if (!empty($request->order_id)) {
                if (strpos($request->order_id, ',') !== false) {
                    $orderIds = array_map('trim', preg_split('/[\s,]+/', $request->order_id));
                        $orders->where(function ($query) use ($orderIds) {
                        foreach ($orderIds as $orderId) {
                            $cleanedOrderId = str_replace('.', '', $orderId);
                            $query->orWhere('id', '=', $cleanedOrderId);
                        }
                    });
                } else {
                    $cleanedOrderId = str_replace('.', '', $request->order_id);
                    $orders->where('id', $cleanedOrderId);
                }
                
            }

            // Filter by reference ID
            if (!empty($request->ref_id)) {
                if (strpos($request->ref_id, ',') !== false) {
                    $refIds = array_map('trim', preg_split('/[\s,]+/', $request->ref_id));
                    // Handle ref IDs with periods specifically
                    $orders->where(function ($query) use ($refIds) {
                        foreach ($refIds as $refId) {
                            $cleanedRefId = str_replace('.', '', $refId);
                            $query->orWhere('ref_id', '=', $cleanedRefId);
                        }
                    });
                } else {
                    $orders->where('ref_id', $request->ref_id);
                }
            }

            // Filter by product name
            if (!empty($request->name)) {
                $orders->whereHas('items', function ($subQuery) use ($request) {
                    $subQuery->where('product_name', 'like', "%" . $request->name . "%");
                });
            }
            if ($role === 'Seller') {
                $orders->where('seller_id', $user->id);
            }
        } else {
            // Seller-specific filtering
            if ($role === 'Seller') {
                $orders->where('seller_id', $user->id);
            } else {


                if (!empty($request->seller)) {
                    $orders->where('seller_id', $request->seller);
                }
            }
            // Payment status filtering
            if (!empty($request->paymentStatus)) {
                if ($request->paymentStatus === 'pending_payment') {
                    $orders->where('payment_status', 'pending');
                }
            }
            // Apply additional filters
            if (!empty($request->filterFulfill)) {
                $orders->where('fulfill_status', $request->filterFulfill);
            } else {
                if (empty($request->order_id) && empty($request->ref_id) && empty($request->name)) {
                    $orders->whereNotIn('fulfill_status', ['cancelled', 'shipped', 'test_order']);
                }
            }

            if (!empty($request->store)) {
                $orders->where('store_id', $request->store);
            }

            if (!empty($request->filterLabel)) {
                if ($request->filterLabel === 'have_label') {
                    $orders->whereNotNull('shipping_label');
                } elseif ($request->filterLabel === 'no_label') {
                    $orders->whereNull('shipping_label');
                }
            }


            // Supplier-specific filtering
            if ($role === 'Supplier') {
                $orders->where('payment_status', 'paid');
            }
        }
        // $orders->where('fulfill_status', '!=', 'fulfill_partner');
        return $orders->orderBy('created_at', 'ASC')->paginate(20);
    }

    /**
     * Get all orders today
     * 
     * @return int
     */
    public function ordersToday()
    {
        $timenow = now()->toDateTimeString();
        $timesevenpm = now()->startOfDay()->addHours(19)->toDateTimeString();
        if ($timenow < $timesevenpm) {
            $startDate = now()->subDays(1)->endOfDay()->subHours(5);
            $endDate = now()->startOfDay()->addHours(11);
        } else {
            $startDate = $timesevenpm;
            $endDate = now()->endOfDay();
        }

        return OrderItem::whereHas('order', function ($query) {
            $query->where('fulfill_status', '!=', 'cancelled')->where('fulfill_status', '!=', 'test_order');
        })->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
    }

    /**
     * Get all reports
     * 
     * @param $request
     */
    public function reports($request)
    {
        $user = Auth::user();
        $created = Carbon::now()->subDays(30)->startOfDay();
        $query = Order::with('items')
            ->where('fulfill_status', '<>', 'test_order')->where('fulfill_status', '<>', 'cancelled');

        if ($user->isSeller()) {
            $query->where('seller_id', $user->id);
        }
        if (isset($request->nameSearch) && !empty($request->nameSearch)) {
            $query->where(function ($query) use ($request) {
                $query->where('id', $request->nameSearch)
                    ->orWhere('ref_id', $request->nameSearch);
            });
        }

        if (isset($request->fillterstore) && !empty($request->fillterstore)) {
            $query->where('store_id', $request->fillterstore);
        }

        if (isset($request->fillteruser) && !empty($request->fillteruser)) {
            $query->where('seller_id', $request->fillteruser);
        }

        if (isset($request->datefrom) && !empty($request->datefrom)) {
            $arrDate = [$request->datefrom, $request->dateto];
            // dd($arrDate);
            if ($arrDate[1] != null) {
                $query->whereBetween('created_at', [
                    Carbon::parse($arrDate[0])->toDateTimeString(),
                    Carbon::parse($arrDate[1])->endOfDay()->toDateTimeString()
                ]);
            } else {
                $query->whereBetween('created_at', [
                    Carbon::parse($arrDate[0])->startOfDay()->toDateTimeString(),
                    Carbon::parse($arrDate[0])->addHour(23)->addSecond(59)->addMinute(59)->toDateTimeString()
                ]);
            }
        }else{
            $query->where('created_at',">=",$created);
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return Order::find($id->id)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $product = Order::updateOrCreate(
            $data
        );
        //add tags
        $tagsArray = explode(',', $data['tags']);
        $product->attachTags($tagsArray);

        // //add category
        // $category = Category::firstOrCreate(['name' => $data['category']], ['product_id' => $product->id]);
        // $product->categories()->attach($category->id);
        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($perPage, $filter = [], $orderBy = null, $status = null)
    {
        $query = Order::query();
        $appends = [];

        if (isset($filter['name'])) {
            //get where Order->items have product_name $filter['name']
            $query->whereHas('items', function ($query) use ($filter) {
                $query->where('product_name', 'like', '%' . $filter['name'] . '%');
            });
        }
        if (isset($filter['filterstock'])) {
            if ($filter['filterstock']) {
                $query->whereHas('items.product', function ($query) {
                    $query->where('stock', '>', 0);
                });
            } else {
                $query->whereHas('items.product', function ($query) {
                    $query->where('stock', '=', 0);
                });
            }

        }

        if (isset($filter['filterlabel'])) {
            if ($filter['filterlabel']) {
                $query->whereHas('items.images');
            } else {
                $query->whereDoesntHave('items.images');
            }
        }

        if (isset($filter['fulfill'])) {
            $query->where('fulfill_status', 'new_order');
        }
        if (!empty($orderBy)) {
            $appends['sort_by'] = $orderBy;

            // Split the sort option into column and direction
            $parts = explode('_', strtolower($orderBy));
            $partCounts = count($parts);
            if ($partCounts >= 2) {
                $sortDirection = array_pop($parts);
                $sortBy = implode('_', $parts);

                // Apply sorting
                if (in_array($sortBy, Order::SORTABLE) && in_array($sortDirection, ['asc', 'desc'])) {
                    $query->orderBy($sortBy, $sortDirection);
                }
            }
        }

        $result = $query->paginate();

        if (!empty($appends)) {
            $result->appends($appends);
        }
        // dd($result);
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        // dd($data);
        $product = Product::find($id->id);
        $product->update($data);
        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $product = $this->find($id->id);

        return $product->delete();
    }


    public function import(array $data)
    {
        // try{
        Excel::import(new OrdersImport(), $data['file']);
        return 1;
        // }catch (ValidationException $e) {
        //     return 0;
        // } catch (\Exception $e) {
        //     return 0;
        // }
    }

}
