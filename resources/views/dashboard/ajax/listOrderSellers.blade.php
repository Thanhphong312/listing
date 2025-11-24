@foreach($orderSellers as $orderSeller)
    <tr>
        <td class="text-center">
        {{ $loop->iteration }}
        </td>
        <td class="text-center">
            {{getUsernameById($orderSeller->seller_id)}}
        </td>
        <td class="text-center">
        {{$orderSeller->totalOrder}} order
        </td>
    </tr>
@endforeach