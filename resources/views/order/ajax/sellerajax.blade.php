<td>
    <input type="checkbox" id="checkbox_{{$order->id}}" data-id="{{$order->id}}"><br>

</td>
<td>
    ID: {{number_format($order->id, 0, '', '.')}}<br>
    Ref: {{$order->ref_id}}<br>
    <button id="eo_{{$order->id}}" data-id="{{$order->id}}" type="button" class="btn btn-sm btn-info edit-order-btn mt-1" onclick="edit_order_btn('{{$order->id}}','{{$order->shipping_label}}','{{$order->tracking_id}}','{{$order->tracking_link}}','{{$order->fulfill_status}}','{{$order->first_name}}','{{$order->last_name}}','{{$order->phone}}','{{$order->address_1}}','{{$order->address_2}}','{{$order->city}}','{{$order->state}}','{{$order->postcode}}','{{$order->country}}','{{$role}}')">Edit</button>
    <span id="info_{{$order->id}}" data-id="{{$order->id}}" class="btn btn-sm btn-info btn-sm mt-1" onclick="copy_info('{{$order->id}}')">Copy info</span>
    <button id="detail_{{$order->id}}" data-id="{{$order->id}}" class="btn btn-sm btn-info btn-sm mt-1" onclick="show_detail('{{$role}}','{{$order->ref_id}}','{{$order->id}}','{{$order->seller_id}}','{{$order->total_cost}}','{{$order->created_at}}','{{$order->fulfill_status}}','{{$order->print_cost}}','{{$order->shipping_cost}}','{{$order->total_cost}}')">Show detail</button>
    <button id="orderff_{{$order->id}}" data-id="{{$order->id}}" class="btn btn-sm btn-info btn-sm mt-1" onclick="check_ff('{{$order->id}}')">Check ff</button>
</td>
<td>
    @if(!empty($supports))
        @foreach($supports as $support)
            @if($support['status'] != 'Solved')
                <a style="color:red" href="{{ route('tickets.view', $support['id']) }}" target="_blank">#{{$support['id']}}</a>
            @else
                <a style="color:green" href="{{ route('tickets.view', $support['id']) }}" target="_blank">#{{$support['id']}}</a>
            @endif
        @endforeach
    @else

    @endif
</td>
<td>
    @if(!empty($order->tracking_id))
        <a href="https://t.17track.net/en#nums={{$order->tracking_id}}" target="_blank" >{{ $order->tracking_id }}</a> 
        <br>
        <!-- <span class="btn btn-sm btn-info">In transit</span> -->
        @if($order->shipping_label)
            <a href="{{ $order->shipping_label }}" target="_blank">Link Label</a>
        @endif
    @else
        @if($order->shipping_label)
            <a href="{{ $order->shipping_label }}" target="_blank">Link Label</a>
        @endif
    @endif
</td>

<td>
    @php
        $statusClasses = [
            'new_order' => 'btn-success',
            'fixed' => 'btn-success',
            'wrongsize' => 'btn-danger',
            'shipped' => 'btn-info',
            'cancelled' => 'btn-danger',
            'printed' => 'btn-warning',
            'label_printed' => 'btn-pink',
            'onhold' => 'btn-sienna',
            'reprint' => 'btn-purple',
            'overdue' => 'btn-greenlight',
            'priority' => 'btn-bluelight',
            'oversize' => 'btn-orangelight',
            'overprio' => 'btn-overprio',
            'pressed' => 'btn-light',
        ];

        $colorbtn = $statusClasses[$order->fulfill_status] ?? 'btn-success';

        $selectableStatuses = [
            'printed' => ['printed'],
            'wrongsize' => ['wrongsize', 'fixed'],
            'default' => ['new_order', 'priority', 'oversize', 'overprio']
        ];
    @endphp

    @if(in_array($order->fulfill_status, ['printed', 'wrongsize']) || $order->fulfill_status == 'new_order')
    <select name="fulfill_status_{{$order->id}}" id="fulfill_status_{{$order->id}}" onchange="changestatus('{{$order->id}}','{{$role}}')" class="custom-select-status btn {{$colorbtn}} btn-sm btn-rounded">
            @foreach ($selectableStatuses[$order->fulfill_status] ?? $selectableStatuses['default'] as $status)
                @php
                    $statusLabel = str_replace('_', ' ', $status);
                    $statusClass = $statusClasses[$status] ?? 'btn-success';
                @endphp
                <option class="btn {{$statusClass}} btn-rounded" value="{{$status}}" {{$order->fulfill_status == $status ? 'selected' : ''}}>{{$statusLabel}}</option>
            @endforeach
        </select>
    @else
        <span class="btn btn-sm {{$colorbtn}} btn-rounded">{{ str_replace('_', ' ', $order->fulfill_status) }}</span>
    @endif
</td>
<td>
    @if($order->address_1)
        <span>{{$order->first_name . ' ' . $order->last_name}}</span>
        <br>
        <span>{{ $order->address_1 }} </span>
        <br>
        <span>{{ $order->city . ', ' . $order->state . ', ' . $order->postcode . ', ' . $order->country }}</span>
        <br>
        {{ $order->phone }}
    @endif
</td>
<td>
    @if($order->fulfill_status=='new_order'&&$order->address_1)
    <select name="shipping_method_{{$order->id}}" id="shipping_method_{{$order->id}}"
        onchange="changeShippingLevel('{{$order->id}}')" class="custom-select-status btn-light btn-sm btn-rounded">
        <option class="btn btn-light btn-rounded m-2" value="standard" data-color="btn-success"
            {{$order->shipping_method == 'standard' ? 'selected' : ''}}>standard</option>
        <option class="btn btn-light btn-rounded p-1" value="priority" data-color="btn-warning"
            {{$order->shipping_method == 'priority' ? 'selected' : ''}}>priority</option>
    </select>
    @else
        standard
    @endif
</td>
@if($order->total_cost==null)
    <td>
        processing
    </td>
    <td>
        processing
    </td>
@else
<td>
    {{ $order->paid_cost ? $order->paid_cost . '$' : '' }}
</td>
<td>
    <span id="payorder_{{$order->id}}" style="color: {{$order->payment_status == 'paid' ? 'green' : 'red'}};">
        {{ $order->total_cost ? $order->total_cost . '$' : '' }}<small
            style="color: {{$order->priority == 1 ? "red" : ""}} {{$order->priority == 2 ? "green" : ""}};font-style: italic;font-weight: bold;">{{($order->priority == 1 || $order->priority == 2) ? '(P)' : ''}}</small>
    </span>
</td>
@endif
<td>
    <textarea id="note_order_{{ $order->id }}" type="text" rows="4" cols="50" class="form-control text-left order-note"
        data-id="{{ $order->id }}" name="note" placeholder="Note"
        onchange="orderChange('{{$order->id}}','note_order')">{{ $order->note }}</textarea>
</td>
<td>{{ $order->created_at }}</td>
@if($order->process_time)
    <td>{{ $order->process_time }} Days</td>
@else
    @if($order->fulfill_status=='shipped')
        @if($order->complete_time)
            <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($order->complete_time))->diffForHumans(null, true) }}</td>
        @else
            @php

                $timeLines = $order->timeLines->keyBy('action'); // Chuyển đổi collection thành key-value map theo action

                $createOrderTime = \Carbon\Carbon::parse($timeLines->get('create order')?->created_at);
                $completeOrderTime = \Carbon\Carbon::parse($timeLines->get('complete order')?->created_at);

                // Tính sự khác biệt
                $difference = $completeOrderTime->diff($createOrderTime);

                $differenceString = "{$difference->h} giờ ";
            @endphp
            <td>{{ $differenceString }}</td>
        @endif
    @else  
    <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($order->created_at))->diffForHumans(null, true) }}</td>
    @endif
@endif
<td>
    <button class="mt-1 btn btn-sm btn-info" onclick="support('{{$order->id}}')">Support</button>
    <button id="tl_{{$order->id}}" data-id="{{$order->id}}" type="button" class="btn btn-sm btn-info timeline-btn mt-1" onclick="timeline_btn(this)">Timeline</button>                                                                                                                                                                                                                                          <!-- id, ref_id, shipping_label, tracking_id, tracking_link, fulfill_status, first_name, last_name, phone, address_1, address_2, city, state, postcode, country -->
    <button id="dup_{{$order->id}}" data-id="{{$order->id}}" type="button" class="btn btn-sm btn-info mt-1" onclick="duplicate('{{$order->id}}')">Duplicate</button>
</td>