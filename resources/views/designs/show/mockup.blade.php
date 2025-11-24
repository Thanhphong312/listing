<div class="row" id="imageContainer">
    @foreach ($designs as $design)
        <div class="col-auto" >
            <figure>
                <a href="{{ $design->value }}" target="_blank">
                    <img src="{{ $design->thumbnail }}" class="img-thumbnail" alt="Product Image" style="height:200px">
                </a>
            </figure>
        </div>
    @endforeach
</div>
