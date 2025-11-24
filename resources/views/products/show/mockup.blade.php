<div class="row" id="imageContainer" data-product-id="{{ $product->id }}">
    @foreach ($images as $image)
        <div class="col-3" data-id="{{ json_encode($image) }}" >
            <figure>
                <a href="{{ $image->src }}" target="_blank">
                    <img src="{{ $image->src }}" class="img-thumbnail" alt="Product Image" style="height:200px">
                </a>
            </figure>
        </div>
    @endforeach
</div>
