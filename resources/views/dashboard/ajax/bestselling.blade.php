@foreach($orderBestSelling as $order)
<tr>
    <td>
        <div class="user-with-avatar">
            <span class="d-xl-inline-block">{{getNameProductById($order->product->product_id)}}</span>
        </div>
    </td>
    <td class="text-center">
        {{$order->product->color}}
    </td>
    <td class="text-center">
        {{$order->product->size}}
    </td>
    <td class="text-center">
        {{$order->count}}
    </td>
</tr>
@endforeach