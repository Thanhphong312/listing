<td>
    <input type="checkbox" id="checkbox_{{$order->id}}" data-id="{{$order->id}}"><br>

</td>

<td>
    <span>ID: {{number_format($order->id, 0, '', '.')}}</span><br>
    <span>Ref: {{$order->ref_id}}</span><br>
    <button id="eo_{{$order->id}}" data-id="{{$order->id}}" type="button"
        class="btn btn-sm btn-info edit-order-btn mt-1"
        onclick="edit_order_btn('{{$order->id}}','{{$order->shipping_label}}','{{$order->tracking_id}}','{{$order->tracking_link}}','{{$order->fulfill_status}}','{{$order->first_name}}','{{$order->last_name}}','{{$order->phone}}','{{$order->address_1}}','{{$order->address_2}}','{{$order->city}}','{{$order->state}}','{{$order->postcode}}','{{$order->country}}','{{$role}}')">Edit</button>
    <span id="info_{{$order->id}}" data-id="{{$order->id}}" class="btn btn-sm btn-info btn-sm mt-1"
        onclick="copy_info('{{$order->id}}',this)">Copy info</span>
    <button id="detail_{{$order->id}}" data-id="{{$order->id}}" class="btn btn-sm btn-info btn-sm mt-1"
        onclick="show_detail('{{$role}}','{{$order->ref_id}}','{{$order->id}}','{{$order->seller_id}}','{{$order->total_cost}}','{{$order->created_at}}','{{$order->fulfill_status}}','{{$order->print_cost}}','{{$order->shipping_cost}}','{{$order->total_cost}}')">Show
        detail</button>
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
    @endphp

    @if($order->fulfill_status != 'fulfill_partner')
        <select name="fulfill_status_{{$order->id}}" id="fulfill_status_{{$order->id}}"
            onchange="changestatus('{{$order->id}}','{{$role}}')" class="custom-select-status btn {{$colorbtn}} btn-sm btn-rounded">
            @php
                $statuses = [
                    'new_order' => 'new order',
                    'printed' => 'printed',
                    'reprint' => 'reprint',
                    'label_printed' => 'label printed',
                    'onhold' => 'onhold',
                    'wrongsize' => 'wrongsize',
                    'fixed' => 'fixed',
                    'pressed' => 'pressed',
                    'overdue' => 'overdue',
                    'priority' => 'priority',
                    'oversize' => 'oversize',
                    'shipped' => 'shipped',
                    'overprio' => 'oversize + priority',
                    'test_order' => 'test order'
                ];

                $disabledStatuses = ['printed', 'reprint', 'label_printed'];
            @endphp

            @foreach ($statuses as $value => $label)
                @if($value != 'test_order' || $order->fulfill_status == 'test_order')
                    @php
                        $isDisabled = in_array($value, $disabledStatuses) && ($order->fulfill_status == 'new_order' && $order->payment_status != 'paid');
                        $isSelected = $order->fulfill_status == $value;
                        $isDisabled = $isDisabled ? 'disabled' : '';
                    @endphp

                    <option class="btn {{$statusClasses[$value] ?? ''}} btn-rounded p-1" value="{{$value}}"
                        data-color="{{$statusClasses[$value] ?? ''}}" {{$isSelected ? 'selected' : ''}} {{$isDisabled}}>{{$label}}
                    </option>
                @endif
            @endforeach

            <option class="btn btn-danger btn-rounded p-1" value="cancelled" data-color="btn-danger"
                {{$order->fulfill_status == 'cancelled' ? 'selected' : ''}}>cancelled</option>
        </select>
    @else
        <span class="btn btn-danger btn-rounded m-2">fulfill_partner</span>
    @endif
</td>
<td>
    @if(!$order->convert_label && $order->sync_label == '')

        @if(!$order->shipping_label && !strpos($order->address_1, '*'))
            @if($order->fulfill_status != 'test_order')
                <a id="bl_{{$order->id}}" data-id="{{$order->id}}" class="btn btn-sm btn-info buylabel-btn"
                    onclick="buyLabel('{{$order->id}}')">
                    Buy Label
                </a>
            @endif
        @else
            <span class="btn btn-sm btn-rounded btn-success mt-1">ready</span>
        @endif
    @else
        @if($order->convert_label)

            <a id="converted_{{$order->id}}" href="{{$order->convert_label}}" target="_blank">
                <span class="btn btn-sm btn-rounded btn-warning mt-1">converted</span>
            </a>
        @endif
        @if($order->sync_label != '')
            <span
                class="btn btn-sm btn-rounded btn-{{$order->sync_label == 1 ? 'warning' : 'danger'}} mt-1">{{$order->sync_label == 1 ? 'synced' : 'synced error'}}</span>
        @endif


    @endif
</td>

<td>
    @if($order->address_1)
        <span>{{$order->first_name . ' ' . $order->last_name}}</span>
        <br>
        <span>{{ $order->address_1 }} </span>
        <br>
        @if($order->address_2)
            <span>{{ $order->address_2 }} </span>
            <br>
        @endif
        <span>{{ $order->city . ', ' . $order->state . ', ' . $order->postcode . ', ' . $order->country }}</span>
        <br>
        {{ $order->phone }}
    @endif
</td>

<td>
    <select name="shipping_method_{{$order->id}}" id="shipping_method_{{$order->id}}"
        onchange="changeShippingLevel('{{$order->id}}')" class="custom-select-status btn-light btn-sm btn-rounded">
        <option class="btn btn-light btn-rounded m-2" value="standard" data-color="btn-success"
            {{$order->shipping_method == 'standard' ? 'selected' : ''}}>standard</option>
        <option class="btn btn-light btn-rounded p-1" value="priority" data-color="btn-warning"
            {{$order->shipping_method == 'priority' ? 'selected' : ''}}>priority</option>
    </select>
</td>
<td>
    @if(!empty($order->tracking_id))
        <a href="https://t.17track.net/en#nums={{ $order->tracking_id }}" target="_blank">{{$order->tracking_id}}</a>
    @endif
    <br />
    @if(!empty($order->shipping_label))
        <a href="{{ $order->shipping_label }}" target="_blank">Link Label</a>
    @endif

</td>
<td>
    <a href="./trackings?so_id={{$order->id}}" target="_blank">status</a>
</td>
<td>
    <span style="color: {{$order->payment_status == 'paid' ? 'green' : 'red'}};">
        {{ $order->total_cost ? $order->total_cost . '$' : '' }} <small
            style="color: {{$order->priority == 1 ? "red" : ""}} {{$order->priority == 2 ? "green" : ""}};font-style: italic;font-weight: bold;">{{($order->priority == 1 || $order->priority == 2) ? '(P)' : ''}}</small>
    </span>
</td>

<!-- gearment -->
<td>

    @if($order->fulfill_status == 'new_order' && empty($order->fulfill_partner) && !empty($order->paid_cost))
        <did id="check_button_ff_gearment_{{$order->id}}" data-order-id="{{$order->id}}">

            </div>
    @elseif(!empty($order->gm_id))

        <p>GM cost: ${{$order->gm_cost}}</p>
        <p class="mt-1">Order:

            @if(!empty($order->fulfill_partner))
                <span>{{$order->fulfill_partner}}</span>
            @endif
        </p>
        <p class="mt-1">GM status:

            @if(!empty($order->gm_status))
                <a href="/fulfillment/gearment-status/{{$order->id}}" target="_blank"
                    class="btn btn-sm btn-rounded btn-info">{{$order->gm_status}}</a>
            @endif
        </p>
    @endif

</td>


<td>
    <textarea id="note_order_{{$order->id}}" type="text" rows="4" cols="50" class="form-control text-left order-note"
        data-id="{{ $order->id }}" name="note" placeholder="Note"
        onchange="orderChange('{{$order->id}}','note_order')">{{ $order->note }}</textarea>
</td>
<td>{{ $order->created_at }}</td>
<td>{{ $order->updated_at }}</td>
@if($order->process_time)
    <td>{{ $order->process_time }} Days</td>
@else

    @if($order->created_at)
        <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($order->created_at))->diffForHumans(null, true) }}</td>
    @else
        <td>0 seconds</td>
    @endif
@endif
<td>
    <button id="tl_{{$order->id}}" data-id="{{$order->id}}" type="button" class="btn btn-sm btn-info timeline-btn mt-1"
        onclick="timeline_btn(this)">Timeline</button>
    <!-- id, ref_id, shipping_label, tracking_id, tracking_link, fulfill_status, first_name, last_name, phone, address_1, address_2, city, state, postcode, country -->
    <button id="dup_{{$order->id}}" data-id="{{$order->id}}" type="button" class="btn btn-sm btn-info mt-1"
        onclick="duplicate('{{$order->id}}')">Duplicate</button>
</td>