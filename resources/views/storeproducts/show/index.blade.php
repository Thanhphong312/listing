@extends('layouts.app')

@section('page-title', __($store->name . ' Store'))
@section('page-heading', __($store->name . ' Store'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Store Products')
</li>
@stop

@section('content')
<div class="element-box">
    <div class="card">
        <div class="card-body">

            @include('partials.messages')
            <div class="modal fade" id="showmockup">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="alert alert-danger" style="display:none"></div>
                            <form id="body_showmockup" class="form-horizontal" enctype="multipart/form-data">
                               
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal fade" id="confirmdelete">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="alert alert-danger" style="display:none"></div>
                            <form id="body_confirmdelete" class="form-horizontal" enctype="multipart/form-data">
                                <button type="button" class=" btn btn-primary form-control btn btn-danger" onclick="deleteproduct()">Delete product</button>
                                <button type="button" class=" btn btn-primary form-control btn btn-primary" onclick="nodelete()">Exit</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row" style="justify-content: start;">
                <form id="filter-form" class="col-12">
                    <div class="row">
                        <div class="col-md-2 col-12 mb-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="remote_id" name="remote_id"
                                    placeholder="Remote ID" value="{{$request->remote_id}}">
                            </div>
                        </div>
                        <div class="col-md-2 col-12 mb-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Name" value="{{$request->name}}">
                            </div>
                        </div>
                        <div class="col-md-2 col-12 mb-3">
                            <button type="submit" class="btn btn-primary form-control btn-rounded">
                                <i class="fas fa-search"></i> Filters
                            </button>
                        </div>
                        <div class="col-md-2 col-12 mb-3">
                            <a href="{{ route('storeproducts.show', $store->id)}}" class="btn btn-secondary form-control btn-rounded">
                                <i class="fas fa-sync-alt"></i> Clear
                            </a>
                        </div>
                        <div class="col-md-2 col-12 mb-3">
                            <button type="button" class="btn btn-success form-control btn-rounded" onclick="posttotiktok()">
                            Post To Tiktok
                            </button>
                        </div>
                        <div class="col-md-2 col-12 mb-3">
                            <button type="button" class="btn btn-danger form-control btn-rounded" onclick="deleteproducttiktok()">
                            Delete product
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row" style="justify-content: end;">
                <div class="col-2 row" style="justify-content: end;">
                </div>
            </div>
            <div class="pt-3">
            <div class="table-responsive">
                <table id="data-table-default" class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th style="width:5%"><input type="checkbox" onclick="check_all(this)"></th>
                            <th>ID</th>
                            <th>Product ID</th>
                            <th style ="min-width:1000px;">Product</th>
                            <th>Status</th>
                            <th>Quality</th>
                            <th>Category</th>
                            <th>Set</th>
                            <th>Remote ID</th>
                            <th>Message</th>
                            <th>Updated at</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($storeproducts as $storeproduct)
                            <tr>
                                <td>
                                    <input type="checkbox" id="checkbox_{{$storeproduct->id}}" data-id="{{$storeproduct->id}}" data-remote="{{$storeproduct->remote_id}}"><br>
                                </td>
                                <td>
                                    {{$storeproduct->id}}
                                </td>
                                <td>
                                    <a href="../../products?id={{$storeproduct->product_id}}" target="_blank">
                                        {{$storeproduct->product_id}}
                                    </a>
                                </td>
                                <td class="card-header p-2" style="border-radius:5px; border:0; box-shadow: 0px 0px 8px #8080807a;">
                                    <div class="d-flex flex-wrap align-items-start justify-content-start w-100">
                                    @php 
                                        $product = json_decode($storeproduct->data);
                                        if($product){
                                            if(isset($product->product)){
                                                $json = $product->product;
                                                $title = $json->title;
                                                $imagefirst = isset($json->images[0]) 
                                                    ? preg_match('/url=([^&]*)/', $json->images[0]->src, $matches) 
                                                        ? urldecode($matches[1]) 
                                                        : './assets/img/image-default.png'
                                                    : './assets/img/image-default.png';
                                                $imagesizechar = $json->imagesizechart ?? './assets/img/image-default.png';
                                                $pricefirst = is_array($json->variants)?$json->variants[0]->price ?? null:$json->variants->only_price;

                                                // Check if `options` array has enough values before accessing them
                                                $styles = $json->options[0]->values ?? [];
                                                $colors = $json->options[1]->values ?? [];
                                                $sizes = $json->options[2]->values ?? [];
                                                $category = $json->category;
                                                $set = $json->set;
                                            }else{
                                                $imagefirst = './assets/img/image-default.png';
                                                $imagesizechar = './assets/img/image-default.png';
                                                $pricefirst = 0;
                                                $title = "";
                                                // Check if `options` array has enough values before accessing them
                                                $styles = [];
                                                $colors =  [];
                                                $sizes = [];
                                                $category = 'T-shirt';
                                                $set = 1;
                                            }
                                        }else{
                                            $imagefirst = './assets/img/image-default.png';
                                            $imagesizechar = './assets/img/image-default.png';
                                            $pricefirst = 0;
                                            $title = "";
                                            // Check if `options` array has enough values before accessing them
                                            $styles = [];
                                            $colors =  [];
                                            $sizes = [];
                                            $category = 'T-shirt';
                                            $set = 1;
                                        }
                                        
                                    @endphp 
                                    <div class="col-2">
                                        <figure>
                                            <a href="{{$imagefirst}}" target="_blank">
                                                <img src="{{$imagefirst}}" class="img-thumbnail" alt="Image first" style="height:150px; object-fit: cover;">
                                            </a>
                                            <figcaption class="text-center mt-1"> <span class="btn btn-sm btn-dark" onclick="view('{{$storeproduct->product_id}}')">view</span> </figcaption>
                                        </figure>
                                    </div>
                                    <div class="col-1">
                                        <figure style="display: flex;align-items: center;">
                                            <a href="{{$imagesizechar}}" target="_blank">
                                                <img src="{{$imagesizechar}}" class="img-thumbnail" alt="Image size chart" style="height:60px; object-fit: cover;">
                                            </a>
                                        </figure>
                                    </div>
                                    <div class="col-9 row">
                                        <div class="text-body-1 font-weight-medium col-12" style="display: flex;align-items: center;" data-toggle="tooltip" data-placement="top" title="{{$title}}"> <span class="btn btn-sm btn-primary mr-1">{{$pricefirst}}$</span> {{substr($title, 0, length: 90)}}</div>
                                        <div class="col-12">
                                            <div>
                                                @foreach($styles as $style)
                                                <span class="badge m-0 mb-1 badge-dark">{{ $style }}</span>
                                                @endforeach
                                            </div>
                                            <div>
                                                @foreach($sizes as $size)
                                                <span class="badge chip m-0 mb-1">{{ $size }}</span>
                                                @endforeach
                                            </div>
                                            <div>
                                                @foreach($colors as $color)
                                                <span class="color-chip mb-1" data-toggle="tooltip" data-placement="top" title="{{$color}}" style="background-color: {{ convertColor(trim($color))}};"></span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td id="status_{{$storeproduct->id}}">
                                    {{$storeproduct->status??""}}
                                </td>
                                <td id="quality_{{$storeproduct->id}}">
                                    @php
                                        $quality = $storeproduct->quality ?? '';
                                        $colors = [
                                            'POOR' => ['red', 'white', 'white'],
                                            'FAIR' => ['yellow', 'yellow', 'white'],
                                            'GOOD' => ['green', 'green', 'green']
                                        ];
                                        $colorSet = $colors[$quality] ?? ['white', 'white', 'white'];
                                    @endphp

                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        @foreach($colorSet as $color)
                                            <span class="btn btn-sm" style="width: 20px; height: 10px; background-color: {{ $color }}; border: 1px solid #ccc;"></span>
                                        @endforeach

                                        {!! ($quality)
                                            ? '<span class="btn btn-sm">'.$quality.'</span>'
                                            : 'PENDING'
                                        !!}
                                        <button class="btn btn-primary btn-sm" onclick="syncQuanlity('{{$storeproduct->id}}')"><i class="fa fa-arrows-rotate"></i></button>
                                    </div>
                                </td>
                                <td>
                                    {{$category}}
                                </td>
                                <td>
                                    {{$set ? 'Female' : 'Male'}}
                                </td>
                                <td>
                                    {{$storeproduct->remote_id}}
                                </td>
                                <td>
                                    {{$storeproduct->message}}
                                </td>
                                <td>
                                    {{$storeproduct->updated_at}}
                                </td>
                                <td>
                                    {{$storeproduct->created_at}}
                                </td>
                                <td>
                                    <div onclick="edit_store('{{$storeproduct->id}}')">
                                        <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </div>
                                    <div onclick="deleteoneproduct('{{$storeproduct->remote_id}}')">
                                        <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            </div>
            <div class="pagination-links">
                @if (count($storeproducts))
                    {{$storeproducts->appends($_GET)->links()}}
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .boxaddColor {
        width: 300px;
    }

    .boxaddSize {
        width: 300px;
    }

    .chip {
        display: inline-block;
        font-weight: bold;
        padding: 0.375rem 0.75rem;
        line-height: 0.5;
        border-radius: 0.55rem;
        background-color: #9ca2a9;
    }

    .color-chip {
        display: inline-block !important;
        width: 20px !important;
        height: 20px !important;
        border-radius: 50% !important;
        border: 0.2px solid #9ca2a9 !important;
        text-align: center;
        line-height: 0.5 !important;
        vertical-align: middle;
    }

    .btn-add-chip {
        display: inline-block !important;
        width: 20px !important;
        height: 20px !important;
        border-radius: 50% !important;
        border: 2.2px solid #9ca2a9 !important;
        text-align: center;
        line-height: 1.1 !important;
        padding-left: 1px;
        vertical-align: middle;
        padding-top: 0 !important;
        padding-right: 0 !important;
        padding-bottom: 0 !important;

    }

    .log-add {
        display: flex;
        justify-content: center;
    }
</style>
@section('scripts')
<script>
    let checkboxes = document.querySelectorAll('td input[type="checkbox"]');

    function edit_store(id) {
        $.get('./stores/edit/' + id, function (data) {
            $("#body_edit").html(data);
        });
        $('#editStore').modal('show');
    }
    function add_store() {
        $.get('./stores/add', function (data) {
            $("#body_add").html(data);
        });
        $('#addStore').modal('show');
    }
    function edit(id) {
        // alert("a");
        var name_edit = $('input[name="name_edit"]').val();
        var access_token_edit = $('input[name="access_token_edit"]').val();
        var seller_edit = $('select[name="seller_edit"]').val();
        var partner_edit = $('input[name="partner_edit"]').val();

        $.ajax({
            url: `./stores/edit/${id}`,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                name_edit: name_edit,
                access_token_edit: access_token_edit,
                partner_edit: partner_edit,
                seller_edit: seller_edit,
            },
            success: function (response) {
                if (JSON.parse(response).message) {
                    location.reload();
                }
            },
        });
    }

    function add() {
        var name_add = $('input[name="name_add"]').val();
        var partner_add = $('input[name="partner_add"]').val();
        var access_token_add = $('input[name="access_token_add"]').val();
        var seller_add = $('select[name="seller_add"]').val();
        var formData = new FormData();
        formData.append('name_add',name_add)
        formData.append('partner_add',partner_add)
        formData.append('seller_add',seller_add)
        formData.append('access_token_add',access_token_add)

        $.ajax({
            url: './stores/add',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // Handle the response, e.g., reload the page
                if (JSON.parse(response).message) {
                    location.reload();
                }
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    }
    function handleFileChange(event,design_id) {
        const file = event.target.files[0];
        if (file) {
            console.log('Selected file:', file.name);
            // Bạn có thể thực hiện các hành động khác với file tại đây
            var formData = new FormData();

            formData.append('_token', "{{ csrf_token() }}");
            formData.append('design_id', design_id);
            formData.append('file', file);

            $.ajax({
                url: '{{route("designs.upload")}}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // Handle the response, e.g., reload the page
                    if (JSON.parse(response).message) {
                        $("#showchat").attr("src", JSON.parse(response).data);
                    }
                },
                error: function (response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
    }
    function view(id){
        $.ajax({
            url: '../../products/view-mockup/'+id,
            method: 'GET',
            success: function (response) {
                $("#body_showmockup").html(response);  
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
        $('#showmockup').modal('show');

    }
    function check_all(target){
        console.log(checkboxes);
        console.log(checkboxes.checked);
        checkboxes.forEach((e)=>{
            e.checked = target.checked;
        })
    }
    function posttotiktok(){
        const valueids = [];
        checkboxes.forEach((e)=>{
            if (e.checked) {
                valueids.push(e.getAttribute('data-id'));
            }
        })
        $.ajax({
            url: '../../storeproducts/post-to-tiktok',
            method: 'GET',
            data:{
                ids:valueids,
            },
            success: function (response) {
                alert("Wait for seconds post products to tiktok");
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    }
    function deleteproducttiktok(){
        $("#confirmdelete").modal('show');
    }
    function nodelete(){
        $("#confirmdelete").modal('hide');
    }
    function deleteproduct(){
        const valueids = [];
        const store_id = '{{$store->id}}';
        checkboxes.forEach((e)=>{
            if(e.checked){
                valueids.push(e.getAttribute('data-remote'));
            }
        })
        $.ajax({
            url: '../../storeproducts/delete-product-tiktok',
            method: 'GET',
            data:{
                ids:valueids,
                store_id:store_id,
            },
            success: function (response) {
                alert("Wait for seconds delete products");
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    }
    function deleteoneproduct(remote_id){
        const valueids = [remote_id];
        const store_id = '{{$store->id}}';
        $.ajax({
            url: '../../storeproducts/delete-product-tiktok',
            method: 'GET',
            data:{
                ids:valueids,
                store_id:store_id,
            },
            success: function (response) {
                alert("Wait for seconds delete this product");
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    }
    function syncQuanlity(store_product_id) {
        const store_id = '{{$store->id}}';

        $.ajax({
            url: '../../storeproducts/sync-quality-product',
            method: 'GET',
            data: {
                store_id: store_id,
                store_product_id: store_product_id,
            },
            success: function (response) {
                const data = JSON.parse(response).data;
                const quality = data[0];
                const status = data[1];

                // Update the status element
                document.getElementById("status_"+store_product_id).innerHTML = status;

                // Define color sets based on quality
                const colors = {
                    'POOR': ['red', 'white', 'white'],
                    'FAIR': ['yellow', 'yellow', 'white'],
                    'GOOD': ['green', 'green', 'green']
                };
                const colorSet = colors[quality] || ['white', 'white', 'white'];

                // Update the quality element
                const qualityDiv = document.getElementById("quality_"+store_product_id);
                qualityDiv.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 5px;">
                        ${colorSet.map(color => `<span class="btn btn-sm" style="width: 20px; height: 10px; background-color: ${color}; border: 1px solid #ccc;"></span>`).join('')}
                        <span class="btn btn-sm">${quality || 'PENDING'}</span>
                        <button class="btn btn-primary btn-sm" onclick="syncQuanlity('${store_product_id}')"><i class="fa fa-arrows-rotate"></i></button>
                    </div>
                `;
            },
            error: function (response) {
                alert(response.responseJSON.message);
            }
        });
    }

</script>
@stop

@endsection