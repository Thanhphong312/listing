<td>
    <input type="checkbox" class="checkboxallproduct" id="checkbox_{{ $product->id }}" data-id="{{ $product->id }}"><br>
</td>

<td>
    {{ $product->id }}
</td>

<td class="card-header p-2" style="border-radius:5px; border:0; box-shadow: 0px 0px 8px #8080807a;">
    <div class="d-flex flex-wrap align-items-start justify-content-start w-100">
    @php
        $json = json_decode($product->data);
        if($json){
            $title = $json->title;
            $main_images = $json->main_images[0];
            $imagesizechar = $json->size_chart->image;
        }
        
    @endphp
    <div class="col-md-2 col-12 mb-2">
        <figure>
            <a href="{{ $main_images }}" target="_blank">
                <img src="{{ $main_images }}" class="img-thumbnail" alt="Image first" style="height:200px; object-fit: cover; width: 100%;">
            </a>
            <figcaption class="text-center mt-1"> <span class="btn btn-sm btn-dark"
                    onclick="view('{{ $product->id }}')">view</span> </figcaption>
        </figure>
    </div>
    <div class="col-md-1 col-12 mb-2">
        <figure style="display: flex;align-items: center;">
            <a href="{{ $imagesizechar }}" target="_blank">
                <img src="{{ $imagesizechar }}" class="img-thumbnail" alt="Image size chart" style="height:50px; object-fit: cover; width: 100%;">
            </a>
        </figure>
    </div>
    <div class="col-md-9 col-12">
        <div class="mb-2" data-toggle="tooltip" data-placement="top" title="{{$json->title}}">
            {{ substr($title, 0, 90) }}
        </div>
    </div>
</td>

<td>
    <span class="btn btn-success" onclick="showstoreproduct('{{ $product->id }}')">
        {{ countStore($product->id) }}
    </span>
</td>

<td>
    {{ $product->discount ? $product->discount . '%' : 'none' }}
</td>

<td>
    {{ $product->templete_id ?? 'none' }}
</td>

<td>
    {{ getUsernameById($product->user_id) }}
</td>

<td>
    {{ $product->created_at }}
</td>

<td>
    <button onclick="confirmdup('{{ $product->id }}')" class="btn btn-success-jade btn-rounded btn-sm"
        style="color: black">
        <i class="fa fa-clone"></i>
    </button>
    <a href="{{ route('products.edit', $product->id) }}">
        <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
            <i class="fa fa-edit"></i>
        </button>
    </a>
    <button onclick="confirmdelete('{{ $product->id }}')" class="btn btn-success-jade btn-rounded btn-sm"
        style="color: black">
        <i class="fa fa-trash"></i>
    </button>
</td>
