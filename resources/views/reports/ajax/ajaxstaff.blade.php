<td>{{getUsernameById($staffreport->user_id)}}</td>
<td id="total_order_{{$staffreport->user_id}}" data-value="{{$staffreport->total_order}}">{{$staffreport->total_order}}</td>
<td id="total_amount_{{$staffreport->user_id}}" data-value="{{$staffreport->total_amount}}">{{$staffreport->total_amount}}</td>
<td id="total_revenue_{{$staffreport->user_id}}" data-value="{{$staffreport->total_revenue}}">{{$staffreport->total_revenue}}</td>
<td id="total_base_cost_{{$staffreport->user_id}}" data-value="{{$staffreport->total_base_cost}}">{{$staffreport->total_base_cost}}</td>
@php 
    $net_profits = $staffreport->total_revenue - $staffreport->total_base_cost - $staffreport->total_design_fee;
@endphp 
<td id="net_profits_{{$staffreport->user_id}}" data-value="{{$net_profits}}">{{$net_profits}}</td>
<td>
    @if($staffreport->total_base_cost && $staffreport->total_base_cost != 0)
        {{round(($net_profits / $staffreport->total_base_cost)*100,2)}}
    @else
        N/A
    @endif
</td>
<td>
    @if($staffreport->total_revenue && $staffreport->total_revenue != 0)
        {{round(($net_profits / $staffreport->total_revenue)*100,2)}}
    @else
        N/A
    @endif
</td>
