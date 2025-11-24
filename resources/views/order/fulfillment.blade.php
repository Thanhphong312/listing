@extends('layouts.app')

@section('page-title', __('Fulfillment'))
@section('page-heading', __('Fulfillment'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Fulfillment')
</li>
@stop

@section('content')
<style>
    .list-group {
        max-height: 600px;
        /* Đặt chiều cao tối đa để hiển thị thanh trượt khi cần thiết */
        overflow-y: auto;
        /* Tự động hiển thị thanh trượt khi nội dung vượt quá chiều cao */
    }
</style>

<div class="element-box">
    <div class="card">
        <div class="card-body">

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="gearment-tab" data-toggle="tab" href="#gearment" role="tab" aria-controls="gearment" aria-selected="true">Gearment</a>
                </li>

                <!-- <li class="nav-item">
                <a class="nav-link" id="hoodie-tab" data-toggle="tab" href="#hoodie" role="tab" aria-controls="hoodie" aria-selected="false">another fulfill</a>
            </li> -->
            </ul>
            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="gearment" role="tabpanel" aria-labelledby="gearment-tab">
                    <div class="row mb-3">
                        <div class="col-12">
                            <h4>Order: {{$order->ref_id}}</h4>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-4">
                            <div class="list-group">
                                <h5>Choose product</h5>
                                @foreach ($gm_product as $product)
                                <a name="x" class="list-group-item list-group-item-action flex-column align-items-start ">
                                    <div class="d-flex w-100 justify-content-between">
                                        <img class="float-left" src="{{$product['product_img']}}" width="80px">
                                        <div class="ml-2">
                                            {{$product['product_name']}}<br>
                                            @php
                                            $colors = [];
                                            foreach ($product['variants'] as $variant) {
                                            $colors[] = $variant["color"];
                                            }
                                            $colors = array_unique($colors);
                                            $color_list = '';
                                            foreach ($colors as $color) {
                                            $color_list .= $color. ' | ';
                                            }
                                            @endphp
                                            <button type="button" class="btn btn-x-small" data-html="true" data-toggle="popover" title="{{$product['product_name']}}" data-content="{{$color_list}}">List colors</button>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-8">
                            <!-- query variant -->
                            <div class="col-12">
                                <h5>Get variant ID</h5>
                                <form id="getvariant">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="exampleFormControlSelect1">Product type</label>
                                            <select id="product_id" data-id="id" class="form-control select2" name="storeFilter">
                                                <option>-</option>
                                                @foreach($gm_product as $product)
                                                <option value="{{$product['product_id']}}">{{$product['product_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Color</label>
                                            <input class="form-control" id="color">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Size</label>
                                            <input class="form-control" id="size">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>&nbsp;</label>
                                            <a id="getVariant" style="color:white" class="form-control btn btn-success">Submit</a>
                                        </div>
                                    </div>
                                    <label id="sku" style="color: red"></label>
                                </form>
                            </div>

                            <!-- shipping address -->
                            <div class="col-12 mb-3">
                                <h5>Shipping Address:
                                    @if (!empty($order->shipping_label))
                                    <a href="{{$order->shipping_label}}" target="_blank">Label Tracking</a>
                                    @endif
                                </h5>
                                <div class="list-group">
                                    @if (!empty($order->address_1))
                                    <div class="item">

                                        <p class="mb-0">Name: <b>{{$order->first_name}} {{$order->last_name}}</b></p>
                                        <p class="mb-0">Address1: <b>{{$order->address_1}}</b></p>
                                        <p class="mb-0">Address2: <b>{{$order->address_2}}</b></p>
                                        <p class="mb-0">City State: <b>{{$order->city}} - {{$order->state}} - {{$order->postcode}} - {{$order->country}}</b></p>
                                        <p>Phone: <b>{{$order->phone}}</b></p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- product detail -->
                            <div class="col-12">
                                <h5>Product Detail</h5>
                                <span class="text-danger">This Order has: {{count($items)}} item</span>
                                <form action="/fulfillment/push-gearment/{{$order->id}}" method="post">
                                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                                    <div class="list-group">
                                        @foreach($items as $keyItem => $item)

                                        <div class="row ml-2 mr-2 mt-2 pd-2" style="border: 2px solid #f4f4f4; padding: 10px; border-radius: 20px;">

                                            <!-- mockup and design -->
                                            <div class="col-4">
                                                <div class="row align-items-center" style="justify-content: center;">
                                                    <div class="mr-1 text-center" style="padding: 0;">
                                                        @if($item->mockup)
                                                        <figure style="margin: 0;">
                                                            <figcaption class="text-center">Front</figcaption>
                                                            <a href="{{$item->mockup}}" target="_blank">
                                                                <img src="{{$item->mockup}}" width="100px" class="img-thumbnail" alt="Product Image Back">
                                                            </a>
                                                        </figure>
                                                        @endif
                                                    </div>

                                                    <div class="mr-1 text-center" style="padding: 0;">
                                                        @if($item->mockup_back)
                                                        <figure style="margin: 0;">
                                                            <figcaption class="text-center">Back</figcaption>
                                                            <a href="{{$item->mockup_back}}" target="_blank">
                                                                <img src="{{$item->mockup_back}}" width="100px" class="img-thumbnail" alt="Product Image Back">
                                                            </a>
                                                        </figure>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- product detail -->
                                            <div class="col-8">
                                                <input type="checkbox" name="select[{{$item->id}}]" value="true" data-toggle="switch" checked> <span>Item {{$keyItem + 1}}</span>
                                                <p>Quality: {{$item->quantity}}</p>
                                                <p>Product Name: <span class="text-danger">{{$item->product_name}}</span></p>
                                                <p>Sku: <span class="text-danger">{{$item->product ? $item->product->style.' - '.$item->product->color.' - '.$item->product->size:''}}</span></p>

                                                <div class="row mt-2">
                                                    <div class="col-auto mt-2">Variant ID: </div>
                                                    <div class="col-auto">
                                                        <input type="text" name="variant_id[{{$item->id}}]" class="form-control" required placeholder="Input variant ID">
                                                    </div>
                                                </div>
                                                <!-- <div class="row">
                                                    <div class="col-3">
                                                        Variant ID:
                                                        <input type="text" name="variant_id[{{$item->id}}]" class="form-control" required placeholder="Input variant ID">
                                                    </div>
                                                    <div class="col-4">
                                                        Product type:
                                                        <select name="producttype[{{$item->id}}]" class="form-control select2" required>
                                                            <option value="">-</option>
                                                            @foreach($product_type as $value)
                                                            <option value="{{$value}}">{{$value}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div> -->

                                                <!-- design -->
                                                @php
                                                $designKeys = ['front_design', 'back_design'];
                                                @endphp
                                                <div class="d-flex flex-wrap mt-2">
                                                    @foreach($item->orderItemMetas as $itemMeta)
                                                    @if(in_array($itemMeta->meta_key, $designKeys))

                                                    @php
                                                    $key = $itemMeta->meta_key;
                                                    $name = str_replace('_design', '', $key);
                                                    @endphp
                                                    <div class="col-3 text-center" style="padding: 0;">
                                                        <figure>
                                                            <a href="{{$itemMeta->meta_value}}" target="_blank">
                                                                <img src="{{$itemMeta->meta_value}}" class="img-thumbnail" alt="{{$name}} Image" width="70px">
                                                            </a>
                                                            <figcaption>{{$name}}</figcaption>
                                                        </figure>
                                                    </div>

                                                    @endif
                                                    @endforeach

                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    @endforeach

                                    <div class="row mt-2">
                                        <div class="col-auto mt-2">Shipping: </div>
                                        <div class="col-auto">
                                            <select name="shipping" class="form-control" required>
                                                <option value="0">Standard</option>
                                                <option value="1">Fast 2 Day</option>
                                                <option value="2">Ground</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">

                                        @if (empty($order->gm_id))
                                        <button type="submit" class="btn btn-primary">Fulfill to Gearment</button>
                                        @else
                                        <span>Gearment id: {{$order->gm_id}}</span>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).on('click', function(e) {
        if (!$(e.target).is('[data-toggle="popover"], .popover, .popover *')) {
            $('[data-toggle="popover"]').popover('hide');
        }
    });

    $(function() {
        $('#getVariant').on('click', function() {
            // console.log('click');
            $('#sku').empty();
            $.ajax({
                url: '/ajax/get-variant-gearment',
                data: {
                    id: $("#product_id").val(),
                    color: $("#color").val(),
                    size: $("#size").val(),
                },
                dataType: 'json',
                success: function(res) {

                    var variantID = res.variant_id;
                    var name = res.name;
                    var text = 'Variant ID: ' + variantID + ' - ' + name;
                    $('#sku').append(text);
                    //alert(variantID);
                }
            });
        });
    });
</script>
@stop