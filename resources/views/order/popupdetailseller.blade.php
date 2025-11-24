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
                    <div class="col-4">Mockup</div>
                    <div class="col-4">Design</div>
                    <div class="col-2">Detail</div>
                    <div class="col-1">print cost</div>
                    <div class="col-1">shipping cost</div>
                </div>
            </div>
            <div class="card-body">
                <div class="row align-items-center mb-3">
                    @if($items)
                    <div class="col-10 row">
                        @foreach($items as $item)
                        <!-- Hình ảnh -->
                        <div class="col-2">
                            @if($item->mockup)
                            <figure>
                                <figcaption class="text-center">Front</figcaption>
                                <a href="{{$item->mockup}}" target="_blank">
                                    <img src="{{$item->mockup ? $item->mockup : ''}}" class="img-thumbnail lazy" alt="Product Image" loading="lazy">
                                </a>
                            </figure>
                            @endif
                        </div>
                        <div class="col-2">
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
                        <div class="col-5 mt-4">
                            <div class="d-flex flex-column">

                                <div class="row">
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
                                            <div class="col-3">
                                                <figure>
                                                    <figcaption class="text-center" style="font-weight:bold; color:{{($itemMeta->oversize)?'red':''}}">{{$name}} {{($itemMeta->oversize)?'(O)':''}}</figcaption>
                                                        @if($itemMeta->oversize==1)
                                                            <a href="{{$itemMeta->meta_value}}" target="_blank" >
                                                                <img src="{{$itemMeta->meta_value ? $itemMeta->meta_value : ''}}" class="img-thumbnail lazy" alt="Product Image" loading="lazy" style="background-color: #dddddd;">
                                                            </a>
                                                        @else
                                                            <a href="{{$itemMeta->meta_value}}" target="_blank">
                                                                <img src="{{$itemMeta->meta_value ? $itemMeta->meta_value : ''}}" class="img-thumbnail lazy" alt="Product Image" loading="lazy" style="background-color: #dddddd;">
                                                            </a>
                                                        @endif
                                                </figure>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-3 mt-4">
                            <span class="text-body-1 font-weight-medium" data-toggle="tooltip" data-placement="top" title="{{$item->product_name}}">
                                <div class="btn btn-sm {{($item->quantity >= 2) ? 'btn-danger' : 'btn-warning'}}">QTY: {{$item->quantity}}</div>
                                {{substr($item->product_name, 0, 40)}}
                            </span>

                            <div class="text-body-1 font-weight-medium">Variant ID: {{$item->variant_id}} -
                                {{$item->product ? $item->product->style . ' ' . $item->product->color . ' ' . $item->product->size : ''}} -
                                Stock: {{$item->product->stock ?? 0 }}
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
                    <div class="col-12">
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