@foreach($pendingStocks as $pendingStock)
@if($pendingStock->need_stock < 0) <tr>
    <td>
        <div class="user-with-avatar">
            <span class="d-xl-inline-block">{{getNameProductById($pendingStock->product_id) }}</span>
        </div>
    </td>
    <td class="text-center">
        {{$pendingStock->color}}
    </td>
    <td class="text-center">
        {{$pendingStock->size}}
    </td>
    <td class="text-center">
        {{abs($pendingStock->need_stock)}}
    </td>
    </tr>
    @endif
    @endforeach