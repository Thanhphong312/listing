@foreach ($json as $key => $file)
    @if(in_array($key, $listshirtid))
        <figure class="col-2 m-2 position-relative">
            <a target="_blank">
                <img src="https://global24watermark.site/teespring/generate/{{$type}}/{{$key}}/design.png"  onclick="chooseDesign(this)" data-type="{{$type}}" data-key="{{$key}}"  class="img-thumbnail" alt="Product Image" style="height:200px">
            </a>
        </figure>
    @endif
@endforeach