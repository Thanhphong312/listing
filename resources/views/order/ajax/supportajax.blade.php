<td>
    <input type="checkbox" id="checkbox_{{$order->id}}" data-id="{{$order->id}}"><br>

</td>

<td>
    <span>ID: {{number_format($order->id, 0, '', '.')}}</span><br>
    <span>Ref: {{$order->ref_id}}</span><br>
    <span>Seller: {{getUsernameById($order->seller_id)}}</span><br>

    <button id="eo_{{$order->id}}" data-id="{{$order->id}}" type="button"
        class="btn btn-sm btn-info edit-order-btn mt-1"
        onclick="edit_order_btn('{{$order->id}}','{{$order->shipping_label}}','{{$order->tracking_id}}','{{$order->tracking_link}}','{{$order->fulfill_status}}','{{$order->first_name}}','{{$order->last_name}}','{{$order->phone}}','{{$order->address_1}}','{{$order->address_2}}','{{$order->city}}','{{$order->state}}','{{$order->postcode}}','{{$order->country}}','{{$role}}')">Edit</button>
    <span id="info_{{$order->id}}" data-id="{{$order->id}}" class="btn btn-sm btn-info btn-sm mt-1"
        onclick="copy_info('{{$order->id}}',this)">Copy info</span>
    <button id="tl_{{$order->id}}" data-id="{{$order->id}}" type="button" class="btn btn-sm btn-info timeline-btn mt-1"
        onclick="timeline_btn(this)">Timeline</button>
    <button id="orderff_{{$order->id}}" data-id="{{$order->id}}" class="btn btn-sm btn-info btn-sm mt-1" onclick="check_ff('{{$order->id}}')">Check ff</button>
</td>
<td>
    @if(count($supports))
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
                    'overprio' => 'oversize + priority',
                    'shipped' => 'shipped',
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
    <div>
        @foreach($items as $item)
        <div class="row align-items-center mb-3">
            <!-- Hình ảnh -->
            <div class="col-2">
                @if($item->mockup)
                <figure>
                    <figcaption class="text-center">Front</figcaption>
                    <a href="{{$item->mockup}}" target="_blank">
                        <img src="{{$item->mockup ? $item->mockup : ''}}" class="img-thumbnail" alt="Product Image">
                    </a>
                </figure>
                @endif
            </div>
            <div class="col-2">
                @if($item->mockup_back)
                <figure>
                    <figcaption class="text-center">Back</figcaption>
                    <a href="{{$item->mockup_back}}" target="_blank">
                        <img src="{{$item->mockup_back ? $item->mockup_back : ''}}" class="img-thumbnail" alt="Product Image">
                    </a>
                </figure>
                @endif
            </div>
            <!-- Thông tin và nút -->
            <div class="col-8">
                <div class="d-flex flex-column">
                    <span class="text-body-1 font-weight-medium" data-toggle="tooltip" data-placement="top" title="{{$item->product_name}}"><span class="btn btn-sm {{($item->quantity >= 2) ? 'btn-danger' : 'btn-warning'}}">QTY:
                            {{$item->quantity}}</span> {{substr($item->product_name, 0, 40)}}</span>
                    <span class="text-body-1 font-weight-medium">Variant ID: {{$item->variant_id}} -
                        {{$item->product ? $item->product->style . ' ' . $item->product->color . ' ' . $item->product->size : ''}}
                        -
                        Stock: {{$item->product->stock ?? 0 }}</span>
                    <div class="d-flex flex-wrap">
                        @php
                        $designKeys = ['front_design', 'back_design', 'sleeve_left_design', 'sleeve_right_design'];
                        $designKeyQrs = ['front_design_qr', 'back_design_qr', 'sleeve_left_design_qr', 'sleeve_right_design_qr'];

                        $itemMetas = $item->orderItemMetas->sortByDesc('meta_key');

                        @endphp
                        @foreach($itemMetas as $itemMeta)
                            @if(in_array($itemMeta->meta_key, $designKeys))
                                @php
                                $key = $itemMeta->meta_key;
                                $name = str_replace('_design', '', $key);
                                @endphp
                                <span class="text-body-1 font-weight-medium mr-1 mt-1" style="color: white;">
                                    @if($itemMeta->oversize==1)
                                        <a id="{{$order->id}}_{{$itemMeta->order_item_id}}_{{$key}}" class="btn btn-sm btn-success" style="color: red;" onclick="printed_design('{{$itemMeta->meta_key}}','{{$itemMeta->order_item_id}}')" href="{{$itemMeta->meta_value}}" target="_blank">
                                            {{$name}}
                                        </a>
                                    @else 
                                        <a id="{{$order->id}}_{{$itemMeta->order_item_id}}_{{$key}}" class="btn btn-sm btn-{{$itemMeta->meta_value > 0 ? 'dark' : 'info'}}" onclick="printed_design('{{$itemMeta->meta_key}}','{{$itemMeta->order_item_id}}')" href="{{$itemMeta->meta_value}}" target="_blank">
                                            {{$name}}
                                        </a>
                                    @endif
                                </span>
                                <span class="text-body-1 font-weight-medium mr-1 mt-1" style="color: black;">
                                    <button class="btn btn-sm btn-danger" onclick="overideCenvertDesign('{{$itemMeta->order_item_id}}','{{$key}}')">
                                        ↻
                                    </button>
                                </span> 
                                @if($order->fulfill_status == 'wrongsize'||$order->fulfill_status == 'fixed'||$order->fulfill_status == 'test_order')
                                    <span class="text-body-1 font-weight-medium mr-1 mt-1" style="color: black;">
                                        <button class="btn btn-sm btn-warning" onclick="overideScaleDesign('{{$itemMeta->order_item_id}}','{{$key}}')">
                                            ↻
                                        </button>
                                    </span> 
                                @endif
                                <div class="vertical"></div>

                            @elseif(in_array($itemMeta->meta_key, $designKeyQrs))
                                @php
                                $key = str_replace('_qr', '', $itemMeta->meta_key);
                                $name = str_replace('_design', '', $key);
                                @endphp
                                <span class="text-body-1 font-weight-medium mr-1 mt-1 " style="color: black!important; ">
                                    <a id="convert_{{$order->id}}_{{$itemMeta->id}}_{{$key}}" style="color: {{($itemMeta->overide_qr_design==1)?'black!important':''}}; " class="btn btn-sm {{($itemMeta->overide_qr_design==1)?'btn-success':'btn-info'}}" href="{{$itemMeta->meta_value}}" target="_blank">
                                        <i class="fa fa-qrcode"></i>
                                    </a>
                                </span>
                            @endif
                        @endforeach 
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
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
@if($order->total_cost==null)
    <td>processing</td>
    <td>processing</td>
    <td>processing</td>
@else
<td>
    {{$order->print_cost}}$
</td>
<td>
    {{$order->shipping_cost}}$
</td>
<td>
    <span style="color: {{$order->payment_status == 'paid' ? 'green' : 'red'}};">
        {{ $order->total_cost ? $order->total_cost . '$' : '' }} <small
            style="color: {{$order->priority == 1 ? "red" : ""}} {{$order->priority == 2 ? "green" : ""}};font-style: italic;font-weight: bold;">{{($order->priority == 1 || $order->priority == 2) ? '(P)' : ''}}</small>
    </span>
</td>
@endif
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
    <!-- id, ref_id, shipping_label, tracking_id, tracking_link, fulfill_status, first_name, last_name, phone, address_1, address_2, city, state, postcode, country -->
    <button id="dup_{{$order->id}}" data-id="{{$order->id}}" type="button" class="btn btn-sm btn-info mt-1"
        onclick="duplicate('{{$order->id}}')">Duplicate</button>
</td>