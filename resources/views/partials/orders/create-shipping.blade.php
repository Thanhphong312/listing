<div class="col-12">
    SELLER SHIP
</div>
<div class="col-4 ">

    <div class="form-row">
        <label for="inputFirstname">Store</label>
        <select class="form-control" id="shopping_store">
            <option value="">Store</option>
            @foreach($stores as $store)
                <option value="{{ $store->api_key }}">{{ $store->name }}</option>
            @endforeach
        </select>
        <!-- <div class="form-group col-md-6">
            <label for="inputLastname">Order Id</label>
            <input type="text" class="form-control" id="shopping_order" value="" placeholder="Order id">
        </div> -->
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputFirstname">Fullname</label>
            <input type="text" class="form-control" id="shipping_fullname" value="" placeholder="Full name">
        </div>
        <div class="form-group col-md-6">
            <label for="inputLastname">Email</label>
            <input type="text" class="form-control" id="shipping_email" value="" placeholder="Email">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputFirstname">Phone number</label>
            <input type="text" class="form-control" id="shipping_phone" value="" placeholder="Phone number">
        </div>

    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="shipping_address_1">Adress 1</label>
            <input type="text" class="form-control" value="" id="shipping_address_1" placeholder="Address 1">
        </div>
        <div class="form-group col-md-4">
            <label for="shipping_address_2">Adress 2</label>
            <input type="text" class="form-control" value="" id="shipping_address_2" placeholder="Address 2">
        </div>
        <div class="form-group col-md-4">
            <label for="shipping_city">City</label>
            <input type="text" class="form-control" value="" id="shipping_city" placeholder="City">
        </div>
        <div class="form-group col-md-4">
            <label for="shipping_state">State</label>
            <input type="text" class="form-control" value="" id="shipping_state" placeholder="State">
        </div>
        <div class="form-group col-md-4">
            <label for="shipping_zipcode">Zip code</label>
            <input type="text" class="form-control" value="" id="shipping_zipcode" placeholder="Zip code">
        </div>
        <div class="form-group col-md-4">
            <label for="shipping_country">Country</label>
            <input type="text" class="form-control" value="" id="shipping_country" placeholder="Country">
        </div>
    </div>
</div>