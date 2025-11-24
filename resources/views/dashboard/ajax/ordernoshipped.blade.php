@foreach($orderOrderThreeDayNoShippeds as $orderOrderThreeDayNoShipped)
@if($user->role->name == 'Seller')
@if($user->id == $orderOrderThreeDayNoShipped->seller_id)
<tr>
    <td>
        <a href="./orders?order_id={{$orderOrderThreeDayNoShipped->id}}">
            #{{$orderOrderThreeDayNoShipped->id}}
        </a>
    </td>
    <td>
        {{getSellernameById($orderOrderThreeDayNoShipped->seller_id)}}
    </td>
    <td class="text-center">
        {{$orderOrderThreeDayNoShipped->ref_id}}
    </td>
    <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($orderOrderThreeDayNoShipped->created_at))->diffForHumans(null, true) }}</td>
    <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($orderOrderThreeDayNoShipped->updated_at))->diffForHumans(null, true) }}</td>

</tr>
@endif
@else
<tr>
    <td>
        <a href="./orders?order_id={{$orderOrderThreeDayNoShipped->id}}">
            #{{$orderOrderThreeDayNoShipped->id}}
        </a>
    </td>
    <td>
        {{getSellernameById($orderOrderThreeDayNoShipped->seller_id)}}
    </td>
    <td class="text-center">
        {{$orderOrderThreeDayNoShipped->ref_id}}
    </td>
    <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($orderOrderThreeDayNoShipped->created_at))->diffForHumans(null, true) }}</td>
    <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($orderOrderThreeDayNoShipped->updated_at))->diffForHumans(null, true) }}</td>
</tr>
@endif
@endforeach