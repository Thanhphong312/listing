@foreach($orderSellers as $orderSeller)
<div class="os-progress-bar primary">
    <div class="bar-labels">
        <div class="bar-label-left">
            <span class="bigger">{{getUsernameById($orderSeller->seller_id)}}</span>
        </div>
        <div class="bar-label-right">
            <span class="info">{{$orderSeller->totalOrder}} / {{$totalOrderNearThreeMonth}} order</span>
        </div>
    </div>
    <div class="bar-level-1" style="width: 100%">
        <div class="bar-level-2" style="width: {{($totalOrderNearThreeMonth != 0 ? $orderSeller->totalOrder / $totalOrderNearThreeMonth * 100 : 0)}}%">
        </div>
    </div>
</div>
@endforeach