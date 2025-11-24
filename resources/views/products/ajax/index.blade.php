<td>
    <input type="checkbox" class="checkboxallproduct" id="checkbox_{{ $product->id }}" data-id="{{ $product->id }}"><br>
</td>

<td>
    {{ $product->id }}
</td>

<td class="card-header p-2" style="border-radius:5px; border:0; box-shadow: 0px 0px 8px #8080807a;">
    <div class="d-flex flex-wrap align-items-start justify-content-start w-100">
    @php
        $json = json_decode($product->data)->product ?? null;

        if ($json) {
            $imagefirst = isset($json->images[0]) ? $json->images[0]->src : './assets/img/image-default.png';
            $imagesizechar = isset($json->imagesizechart) ? $json->imagesizechart : './assets/img/image-default.png';
            $pricefirst = is_array($json->variants)
                ? (isset($json->variants[0]->price)
                    ? $json->variants[0]->price
                    : 0)
                : $json->variants->only_price;

            // Check if `options` array has enough elements before accessing each one
            $styles = isset($json->options[0]->values) ? $json->options[0]->values : [];
            $colors = isset($json->options[1]->values) ? $json->options[1]->values : [];
            $sizes = isset($json->options[2]->values) ? $json->options[2]->values : [];

            $category = $json->category ?? null;
            $set = $json->set ?? null;
        } else {
            // Fallback in case `product` is not properly decoded
            $imagefirst = './assets/img/image-default.png';
            $imagesizechar = './assets/img/image-default.png';
            $pricefirst = null;
            $styles = [];
            $colors = [];
            $sizes = [];
            $category = null;
            $set = null;
        }
    @endphp
    <div class="col-md-2 col-12 mb-2">
        <figure>
            <a href="{{ $imagefirst }}" target="_blank">
                <img src="{{ $imagefirst }}" class="img-thumbnail" alt="Image first" style="height:200px; object-fit: cover; width: 100%;">
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
                                            <span class="btn btn-sm btn-primary">{{$pricefirst}}$</span>
                                            {{ substr($json->title, 0, 90) }} 
                                        </div>
        <div class="col-12">
            <div>
                @foreach ($styles as $style)
                    <span class="badge m-0 mb-1 badge-dark">{{ $style }}</span>
                @endforeach
            </div>
            <div>
                @foreach ($sizes as $size)
                    <span class="badge chip m-0 mb-1">{{ $size }}</span>
                @endforeach
            </div>
            <div>
                @foreach ($colors as $color)
                    <span class="color-chip mb-1" data-toggle="tooltip" data-placement="top"
                        title="{{ $color }}"
                        style="background-color: {{ convertColor(trim($color)) }};"></span>
                @endforeach
            </div>
        </div>
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
