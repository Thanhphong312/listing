<div class="row">
    @foreach ($images as $image)
        <div class="col-3">
            <figure>
                <a href="{{$image->src}}" target="_blank">
                    <img src="{{$image->src}}" class="img-thumbnail" alt="Product Image" style="height:200px">
                </a>
            </figure>
        </div>
    @endforeach
    
</div>