<div class="table-responsive">
    <table class="table table-lightborder">
        <thead>
            <tr>
                <th colspan="2">
                    Product Info
                </th>
                <th width="135px">
                    Quantity
                </th>
                <th colspan="2">
                    Price
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td width="700px">
                        <!-- <div class="product-image" style="background-image: url({{$item->mockup}}) "></div> -->


                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    @if($item->mockup)
                                        <img src="{{$item->mockup}}" alt="Product Mockup" style="width: 250px;">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($item->mockup_back)
                                        <img src="{{$item->mockup_back}}" alt="Product Mockup Back" style="width: 250px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="900px">
                        <div class="product-name">
                            {{$item->product_name}}
                        </div>
                        <div class="product-details mt-1">
                            <strong> STT: {{$order_stt}}</strong>
                        </div>
                        <div class="product-details mt-1" style="font-size: 15px;">
                            <span>Color:</span>
                            <strong> {{$item->product->style}}</strong> - <strong>
                                {{$item->product->size}}</strong> -
                            <strong> {{$item->product->color}}</strong>
                            <div class="color-box"
                                style="background-color: {{ convertColor(strtolower(str_replace(' ', '-', $item->product->color))) ? convertColor(strtolower(str_replace(' ', '-', $item->product->color))) : $item->product->color }}">
                            </div>


                            <div class="d-flex flex-wrap mt-1">
                                @foreach($item->orderItemMetas as $itemMeta)
                                    @if($itemMeta->meta_key == 'front_design')
                                        <span class="text-body-1 font-weight-medium mr-1 mt-1" style="color: white;">
                                            <a id="{{$itemMeta->meta_key}}{{$itemMeta->order_item_id}}"
                                                class="btn btn-sm btn-{{$itemMeta->meta_value > 0 ? 'dark' : 'info'}}"
                                                onclick="printed_design('{{$itemMeta->meta_key}}','{{$itemMeta->order_item_id}}')"
                                                href="{{$itemMeta->meta_value}}" target="_blank">
                                                Front <p style="display: inline;">
                                                    @foreach($item->orderItemMetas as $itemMeta)
                                                        @if($itemMeta->meta_key == 'front_design_printed')
                                                            ({{$itemMeta->meta_value ?? 0}})
                                                        @endif
                                                    @endforeach
                                                </p>
                                            </a>
                                        </span>

                                    @endif

                                    @if($itemMeta->meta_key == 'back_design')
                                        <span class="text-body-1 font-weight-medium mr-1 mt-1" style="color: white;">
                                            <a id="{{$itemMeta->meta_key}}{{$itemMeta->order_item_id}}"
                                                class="btn btn-sm btn-{{$itemMeta->meta_value > 0 ? 'dark' : 'info'}}"
                                                onclick="printed_design('{{$itemMeta->meta_key}}','{{$itemMeta->order_item_id}}')"
                                                href="{{$itemMeta->meta_value}}" target="_blank">
                                                Back <p style="display: inline;">
                                                    @foreach($item->orderItemMetas as $itemMeta)
                                                        @if($itemMeta->meta_key == 'back_design_printed')
                                                            ({{$itemMeta->meta_value ?? 0}})
                                                        @endif
                                                    @endforeach
                                                </p>
                                            </a>
                                        </span>

                                    @endif
                                    @if($itemMeta->meta_key == 'sleeve_right_design')
                                        <span class="text-body-1 font-weight-medium mr-1 mt-1" style="color: white;">
                                            <a id="{{$itemMeta->meta_key}}{{$itemMeta->order_item_id}}"
                                                class="btn btn-sm btn-{{$itemMeta->meta_value > 0 ? 'dark' : 'info'}}"
                                                onclick="printed_design('{{$itemMeta->meta_key}}','{{$itemMeta->order_item_id}}')"
                                                href="{{$itemMeta->meta_value}}" target="_blank">
                                                Sleeve Right <p style="display: inline;">
                                                    @foreach($item->orderItemMetas as $itemMeta)
                                                        @if($itemMeta->meta_key == 'sleeve_right_design_printed')
                                                            ({{$itemMeta->meta_value ?? 0}})
                                                        @endif
                                                    @endforeach
                                                </p>
                                            </a>
                                        </span>

                                    @endif
                                    @if($itemMeta->meta_key == 'sleeve_left_design')
                                        <span class="text-body-1 font-weight-medium mr-1 mt-1" style="color: white;">
                                            <a id="{{$itemMeta->meta_key}}{{$itemMeta->order_item_id}}"
                                                class="btn btn-sm btn-{{$itemMeta->meta_value > 0 ? 'dark' : 'info'}}"
                                                onclick="printed_design('{{$itemMeta->meta_key}}','{{$itemMeta->order_item_id}}')"
                                                href="{{$itemMeta->meta_value}}" target="_blank">
                                                Sleeve Left <p style="display: inline;">
                                                    @foreach($item->orderItemMetas as $itemMeta)
                                                        @if($itemMeta->meta_key == 'sleeve_left_design_printed')
                                                            ({{$itemMeta->meta_value ?? 0}})
                                                        @endif
                                                    @endforeach
                                                </p>
                                            </a>
                                        </span>

                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="quantity-selector">
                            <div class="quantity-input">
                                <div class="input-group">
                                    <a class="product-remove-btn" href="#">
                                        <div class="os-icon os-icon-x"></div>
                                    </a>
                                    {{$item->quantity}}

                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-md-right">
                        <div class="product-price">
                            ${{$print_cost + $shipping_cost ?? '' }}
                        </div>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>