<?php

namespace Vanguard\Imports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Vanguard\Models\Order\Order;
use Vanguard\Models\Order\OrderItem;

class OrdersImport implements ToCollection, ToModel, WithHeadingRow, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

    }
    public function model(array $row)
    {
        // dd($row);
        // $key = $row['NGÀY'];

        // Access the data of each row using the "NGÀY" column as the key
        // dump($key, $row);
        $findorder = Order::where('ref_id',trim($row['order_id']))->first();
        if($findorder==null){
            // $carbonDate = Carbon::createFromFormat('d-m', $row['day']);

            $order = new Order();
            $order->ref_id = $row['order_id'];
            $order->address_1 = $row['customer_info'];
            // $order->created_at = $carbonDate->toDateTimeString();
            $order->save();
            $findItem = OrderItem::find($row['item_id']);
            if($findItem==null){
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->price = $row['price'];
                $orderItem->sku = null;
                $orderItem->status = $row['status'];
                $orderItem->quantity = $row['quantity'];
                $orderItem->front_design = $row['design_front'];
                $orderItem->back_design = $row['design_back'];
                $orderItem->mockup = $row['mockup'];
                $orderItem->variant_id = $row['variant_id'];
                $orderItem->save();
            }
            return $order;
        }else{
            $findItem = OrderItem::find($row['item_id']);
            if($findItem==null){
                $orderItem = new OrderItem();
                $orderItem->order_id = $findorder->id;
                $orderItem->price = $row['price'];
                $orderItem->sku = null;
                $orderItem->status = $row['status'];
                $orderItem->quantity = $row['quantity'];
                $orderItem->front_design = $row['design_front'];
                $orderItem->back_design = $row['design_back'];
                $orderItem->mockup = $row['mockup'];
                $orderItem->variant_id = $row['variant_id'];
                $orderItem->save();
                return $findItem;
            }
        }
        return $findorder;
    }
    public function startRow(): int
    {
        return 2;
    }
}
