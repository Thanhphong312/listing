<div class="row">
    <div class="col-12 row">
        <div class="col-12">
            <div class="m-3 d-flex align-items-center">  
                <span class="ml-2" style="font-weight: bold; font-size: 23px;">
                    Seller:{{getUsernameById($seller_id)}}<br>
                    <span style="font-size: 15px;">ref_id: #{{$ref_id}}</span>
                </span>
                <span class="ml-2" style="font-weight: bold; font-size: 23px; border-left: 2px solid; padding-left: 10px;">
                    ${{$total_cost}}<br>
                    <span style="font-size: 15px;">{{$items->count()}} item(s)</span>
                </span>
            </div>
        </div>
        <!-- <div class="col-4"></div> -->
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-6">Mockup</div>
                    <div class="col-4">Design</div>
                    <div class="col-1">print cost</div>
                    <div class="col-1">shipping cost</div>
                </div>
            </div>
            <div class="card-body">
                    <div class="row align-items-center mb-3">
                        @if($items->count() > 0)
                        <div class="col-10 row">
                            @foreach($items as $item)
                                <!-- Hình ảnh -->
                                <div class="col-3">
                                    @if($item->mockup)
                                    <figure>
                                        <figcaption class="text-center">Front</figcaption>
                                        <a href="{{$item->mockup}}" target="_blank">
                                            <img src="{{$item->mockup ? $item->mockup : ''}}" class="img-thumbnail lazy" alt="Product Image" loading="lazy">
                                        </a>
                                    </figure>
                                    @endif
                                </div>
                                <div class="col-3">
                                    @if($item->mockup_back)
                                    <figure>
                                        <figcaption class="text-center">Back</figcaption>
                                        <a href="{{$item->mockup_back}}" target="_blank">
                                            <img src="{{$item->mockup_back ? $item->mockup_back : ''}}" class="img-thumbnail lazy" alt="Product Image" loading="lazy">
                                        </a>
                                    </figure>
                                    @endif
                                </div>
                                <!-- Thông tin và nút -->
                                <div class="col-6 mt-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-body-1 font-weight-medium" data-toggle="tooltip" data-placement="top" title="{{$item->product_name}}">
                                            <span class="btn btn-sm {{($item->quantity >= 2) ? 'btn-danger' : 'btn-warning'}}">QTY: {{$item->quantity}}</span>
                                            {{substr($item->product_name, 0, 40)}}
                                        </span>
                                        <span class="text-body-1 font-weight-medium">Variant ID: {{$item->variant_id}} - 
                                            {{$item->product ? $item->product->style . ' ' . $item->product->color . ' ' . $item->product->size : ''}} - 
                                            Stock: {{$item->product->stock ?? 0 }}
                                        </span>
                                        <div class="d-flex flex-wrap">
                                            @php
                                            $designKeys = ['front_design', 'back_design', 'sleeve_left_design', 'sleeve_right_design'];
                                            $designKeyQrs = ['front_design_qr', 'back_design_qr', 'sleeve_left_design_qr', 'sleeve_right_design_qr'];
                                            $orderItemMetas = $item->orderItemMetas->sortByDesc('meta_key');
                                            @endphp
                                            @foreach($orderItemMetas as $itemMeta)
                                                @if(in_array($itemMeta->meta_key, $designKeys))
                                                    @php
                                                    $key = $itemMeta->meta_key;
                                                    $name = str_replace('_design', '', $key);
                                                    @endphp
                                                    <span class="text-body-1 font-weight-medium mr-1 mt-1" style="color: white;">
                                                        @if($itemMeta->oversize==1)
                                                            <a  class="btn btn-sm btn-success" style="color: red;"  href="{{$itemMeta->meta_value}}" target="_blank">
                                                                {{$name}}
                                                            </a>
                                                        @else
                                                            <a class="btn btn-sm btn-{{$itemMeta->meta_value > 0 ? 'dark' : 'info'}}" href="{{$itemMeta->meta_value}}" target="_blank">
                                                                {{$name}}
                                                            </a>
                                                        @endif
                                                    </span>
                                                    @if($role!='Seller')
                                                        <span class="text-body-1 font-weight-medium mr-1 mt-1" style="color: black;">
                                                            <button class="btn btn-sm btn-danger" onclick="overideCenvertDesign('{{$itemMeta->order_item_id}}','{{$key}}')">
                                                                ↻
                                                            </button>
                                                        </span> 
                                                        @if($fulfill_status == 'wrongsize'||$fulfill_status == 'fixed'||$fulfill_status == 'test_order')
                                                            <span class="text-body-1 font-weight-medium mr-1 mt-1" style="color: black;">
                                                                <button class="btn btn-sm btn-warning" onclick="overideScaleDesign('{{$itemMeta->order_item_id}}','{{$key}}')">
                                                                    ↻
                                                                </button>
                                                            </span> 
                                                        @endif
                                                    @endif
                                                    <div class="vertical"></div>

                                                @elseif(in_array($itemMeta->meta_key, $designKeyQrs) && $role!='Seller')
                                                    @php
                                                    $key = str_replace('_qr', '', $itemMeta->meta_key);
                                                    $name = str_replace('_design', '', $key);
                                                    @endphp
                                                    <span class="text-body-1 font-weight-medium mr-1 mt-1 " style="color: black!important; ">
                                                        <a  style="color: {{($itemMeta->overide_qr_design==1)?'black!important':''}}; " class="btn btn-sm {{($itemMeta->overide_qr_design==1)?'btn-success':'btn-info'}}" href="{{$itemMeta->meta_value}}" target="_blank">
                                                            <i class="fa fa-qrcode"></i>
                                                        </a>
                                                    </span>
                                                @endif
                                            @endforeach 
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        

                        <div class="col-1">
                            {{$print_cost}}$
                        </div>
                        <div class="col-1">
                            {{$shipping_cost}}$
                        </div>
                        @else
                            <div class="col-12 p-3 m-3">
                                <div class="text-center">Order item processing...</div>
                            </div>
                        @endif
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- Include a lazy load library like lazysizes for better support -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" integrity="sha512-X6dF2SLQhKtK8mH1aQMWVft5/NM1boTfQQAZAq4jv+es3BlJ1zFYyctX8vj34CZCczvjwYztqdmGzwT9xd5XHg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
