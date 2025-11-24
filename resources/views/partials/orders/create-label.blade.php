<div class="col-12">
    LABEL SHIP
</div>
<div class="mb-4 col-3">
    <label for="label" class="form-label">Choose label</label>
    <input class="form-control" type="text" id="label">
</div>
<div class="col-2">
    <div class="form-group">
        <label for="inputLastname">Store Id</label>
        <select class="form-control" id="shopping_store">
            <option value="">Store</option>
            @foreach($stores as $store)
                <option value="{{ $store->api_key }}">{{ $store->name }}</option>
            @endforeach
        </select>
    </div>
</div>