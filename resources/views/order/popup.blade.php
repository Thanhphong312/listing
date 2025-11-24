<div class="card">
    <div class="card-header bg-light mb-3">
        Shipping
    </div>
    <div class="card-body">
        <div class="form-row">
            <input type="text" id="orderid" value="{{$id}}" disabled>
            <div class="form-group col-md-6">
                <label for="inputFirstname">First name</label>
                <input type="text" class="form-control" id="inputFirstname" value="{{$first_name}}" placeholder="First name" {{($fulfill_status=="shipped" || $shipping_label!=null)?($role!='Admin'&&$role!='Support')?"disabled":"":""}}>
            </div>
            <div class="form-group col-md-6">
                <label for="inputLastname">Last name</label>
                <input type="text" class="form-control" id="inputLastname" value="{{$last_name}}" placeholder="Last name" {{($fulfill_status=="shipped" || $shipping_label!=null)?($role!='Admin'&&$role!='Support')?"disabled":"":""}}>
            </div>
        </div>
        <div class="form-group">
            <label for="inputAddress">Address</label>
            <input type="text" class="form-control" id="inputAddress" value="{{$address_1}}" placeholder="Address line 1" {{($fulfill_status=="shipped" || $shipping_label!=null)?($role!='Admin'&&$role!='Support')?"disabled":"":""}}>
        </div>
        <div class="form-group">
            <label for="inputAddress2">Address 2</label>
            <input type="text" class="form-control" id="inputAddress2" value="{{$address_2}}" placeholder="Apartment, studio, or floor" {{($fulfill_status=="shipped" || $shipping_label!=null)?($role!='Admin'&&$role!='Support')?"disabled":"":""}}>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputCity">City</label>
                <input type="text" class="form-control" value="{{$city}}" id="inputCity" {{($fulfill_status=="shipped" || $shipping_label!=null)?($role!='Admin'&&$role!='Support')?"disabled":"":""}}>
            </div>
            <div class="form-group col-md-4">
                <label for="inputState">State</label>
                <input type="text" class="form-control" value="{{$state}}" id="inputState" {{($fulfill_status=="shipped" || $shipping_label!=null)?($role!='Admin'&&$role!='Support')?"disabled":"":""}}>
            </div>
            <div class="form-group col-md-4">
                <label for="inputZip">Postcode</label>
                <input type="text" class="form-control" value="{{$postcode}}" id="inputZip" {{($fulfill_status=="shipped" || $shipping_label!=null)?($role!='Admin'&&$role!='Support')?"disabled":"":""}}>
            </div>
            <div class="form-group col-md-4">
                <label for="inputCountry">Country</label>
                <input type="text" class="form-control" value="{{$country}}" id="inputCountry" {{($fulfill_status=="shipped" || $shipping_label!=null)?($role!='Admin'&&$role!='Support')?"disabled":"":""}}>
            </div>
        </div>
        <div class="form-group">
            <label for="inputLabel">Label</label>
            <input type="text" class="form-control" id="inputLabel" value="{{$shipping_label}}" placeholder="Label link .jpg" {{($role!='Admin'&&$role!='Support')?"disabled":""}}>
        </div>
        <div class="form-group">
            <label for="inputtrackingid">Tracking id</label>
            <input type="text" class="form-control" id="inputtrackingid" value="{{$tracking_id}}" placeholder="Tracking id" {{($role!='Admin'&&$role!='Support')?"disabled":""}}>
        </div>
    </div>
</div>
<div class="card bg-light p-3">
    <div class="card-header mb-3">
        Order Items
    </div>
    <div class="card-body">
        @foreach($items as $item)
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputVariant">Variant ID</label>
                <input data-id="{{$item->id}}" type="number" class="form-control" id="inputVariant[{{$item->id}}]" value="{{$item->variant_id}}" 
                {{($fulfill_status!="new_order")?(($role!='Admin'&&$role!='Support'&&$role!='Designer')?"disabled":""):""}}
                >
            </div>
            <div class="form-group col-md-6">
                <label for="inputState">Product</label>
                <input type="text" class="form-control" value="{{$item->product?->style}}" disabled>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputCity">Color</label>
                <input type="text" class="form-control" value="{{$item->product?->color}}" disabled>
            </div>
            <div class="form-group col-md-4">
                <label for="inputState">Size</label>
                <input type="text" class="form-control" value="{{$item->product?->size}}" disabled>
            </div>
            <div class="form-group col-md-4">
                <label for="inputState">Quantity</label>
                <input type="text" class="form-control" value="{{$item->quantity}}" disabled>
            </div>
        </div>
        <div class="form-row">
            <label for="mockup_front" style="color:red; font-weight:bold">Mockup </label>
            <div class="form-group col-md-12">
                <label for="mockup_front" style="color:black; font-weight:bold">Mockup front</label>
                <input type="text" data-id="{{$item->id}}" id="mockup_front[{{$item->id}}]" class="form-control" value="{{$item->mockup}}" {{($fulfill_status!="new_order")?(($role!='Admin'&&$role!='Support'&&$role!='Designer')?"disabled":""):""}}>
            </div>
            <div class="form-group col-md-12">
                <label for="mockup_back" style="color:black; font-weight:bold">Mockup back</label>
                <input type="text" data-id="{{$item->id}}" id="mockup_back[{{$item->id}}]" class="form-control" value="{{$item->mockup_back}}" {{($fulfill_status!="new_order")?(($role!='Admin'&&$role!='Support'&&$role!='Designer')?"disabled":""):""}}>
            </div>
        </div>
        <div class="border-top border-secondary mb-3"></div>
        <br>
        <div class="form-row">
            <label for="mockup_front" style="color:red; font-weight:bold">Design</label>
            @foreach($item->orderItemMetas as $keyitem => $value)
                @if($value->meta_key == 'front_design')
                <div class="form-group col-md-12">
                    <label for="inputFrontDesign[{{$value->id}}]">Front design</label>
                    <input type="text" class="form-control" data-id="{{$value->id}}" id="inputFrontDesign[{{$value->id}}]" value="{{$value->meta_value}}" {{($fulfill_status!="new_order"&&$fulfill_status!="wrongsize"&&$fulfill_status!="oversize")?(($role!='Admin'&&$role!='Support'&&$role!='Designer')?"disabled":""):""}}>
                </div>
                @endif

                @if($value->meta_key == 'back_design')
                <div class="form-group col-md-12">
                    <label for="inputBackDesign[{{$value->id}}]">Back design</label>
                    <input type="text" class="form-control" data-id="{{$value->id}}" id="inputBackDesign[{{$value->id}}]" value="{{$value->meta_value}}" {{($fulfill_status!="new_order"&&$fulfill_status!="wrongsize"&&$fulfill_status!="oversize")?(($role!='Admin'&&$role!='Support'&&$role!='Designer')?"disabled":""):""}}>
                </div>
                @endif
                @if($value->meta_key == 'sleeve_right_design')
                <div class="form-group col-md-12">
                    <label for="inputSleeveRightDesign[{{$value->id}}]">Sleeve right design</label>
                    <input type="text" class="form-control" data-id="{{$value->id}}" id="inputSleeveRightDesign[{{$value->id}}]" value="{{$value->meta_value}}" {{($fulfill_status!="new_order"&&$fulfill_status!="wrongsize"&&$fulfill_status!="oversize")?(($role!='Admin'&&$role!='Support'&&$role!='Designer')?"disabled":""):""}}>
                </div>
                
                @endif
                @if($value->meta_key == 'sleeve_left_design')
                <div class="form-group col-md-12">
                    <label for="inputSleeveLeftDesign[{{$value->id}}]">Sleeve left design</label>
                    <input type="text" class="form-control" data-id="{{$value->id}}" id="inputSleeveLeftDesign[{{$value->id}}]" value="{{$value->meta_value}}" {{($fulfill_status!="new_order"&&$fulfill_status!="wrongsize"&&$fulfill_status!="oversize")?(($role!='Admin'&&$role!='Support'&&$role!='Designer')?"disabled":""):""}}>
                </div>
                
                @endif
            @endforeach

        </div>
        @if (!$loop->last)
        <div class="border-top border-secondary mb-3"></div>
        @endif
        @endforeach
    </div>
</div>