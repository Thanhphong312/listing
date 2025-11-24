@foreach($orderItems as $orderItem)
    <div class="row">
        <input type="hidden" value="{{$order_id}}" id="id_chooseside">
        <div class="col-4 row">
            <div class="col-6">
                <a href="{{$orderItem->mockup}}" target="_blank">
                    <img src="{{$orderItem->mockup ? $orderItem->mockup : ''}}" class="img-thumbnail" alt="Product Image">
                </a>
            </div>
            <div class="col-6">
                <a href="{{$orderItem->mockup}}" target="_blank">
                    <img src="{{$orderItem->mockup ? $orderItem->mockup : ''}}" class="img-thumbnail" alt="Product Image">
                </a>
            </div>
        </div>
        <div class="col-8 row">
            <?php $orderItemMetas = $orderItem->orderItemMetas->whereIn('meta_key', ['front_design', 'back_design', 'sleeve_left_design', 'sleeve_right_design']); ?>
            @foreach ($orderItemMetas as $orderItemMeta)
                <div class="col-3 text-center">
                    <a href="{{$orderItemMeta->meta_value}}" target="_blank">
                        <img src="{{$orderItemMeta->meta_value ? $orderItemMeta->meta_value : ''}}" class="img-thumbnail" alt="Product Image" style="background-color: #cccccc;">
                    </a>
                    <!-- Add a checkbox below the image -->
                    <div class="form-check mt-2">
                        <input class="form-check-input checkCheckedOversize" type="checkbox" value="{{$orderItemMeta->id}}" id="checkCheckedOversize{{$orderItemMeta->id}}">
                        <label class="form-check-label" for="checkCheckedOversize{{$orderItemMeta->id}}">
                            Checked
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach
