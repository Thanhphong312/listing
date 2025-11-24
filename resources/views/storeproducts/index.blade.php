@extends('layouts.app')

@section('page-title', __('Stores'))
@section('page-heading', __('Stores'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Stores')
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
            <div class="row justify-content-end">
                <div class="col-2 mb-3">
                    <button class="btn btn-danger w-100" onclick="deleteproducttiktok()">Delete product</button>
                </div>
                <div class="col-2 mb-3">
                    <button class="btn btn-success w-100" onclick="posttotiktok()">Post To Tiktok</button>
                </div>
            </div>

            <div class="pt-3">
                <div class="table-responsive">
                <table class="table table-striped table-borderless" id="data-table-default">
                    <thead>
                        <tr>
                            <th><input type="checkbox" onclick="check_all(this)"></th>
                            <th>ID</th>
                            <th style ="min-width:00px;">Product</th>
                            <th>Remote ID</th>
                            <th>Note</th>
                            <th>Updated at</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($storeproducts as $storeproduct)
                            <tr>
                                <td>
                                    <input type="checkbox" id="checkbox_{{$storeproduct->id}}" data-id="{{$storeproduct->id}}"><br>
                                </td>
                                <td>
                                    {{$storeproduct->id}}
                                </td>
                                <td class="card-header p-2" style="border-radius:5px; border:0; box-shadow: 0px 0px 8px #8080807a;">
                                    <div class="d-flex flex-wrap align-items-start justify-content-start w-100">
                                    @php 
                                        $json = json_decode($storeproduct->data)->product;
                                        //dd($json);
                                        $imagefirst = isset($json->images[0])?$json->images[0]->src:'./assets/img/image-default.png';
                                        $imagesizechar = $json->imagesizechart??'./assets/img/image-default.png';
                                        $pricefirst = $json->variants[0]->price ?? null;

                                        // Check if `options` array has enough values before accessing them
                                        $styles = $json->options[0]->values ?? [];
                                        $colors = $json->options[1]->values ?? [];
                                        $sizes = $json->options[2]->values ?? [];
                                    @endphp 
                                    <div class="col-md-2 col-12 mb-2">
                                        <figure>
                                            <a href="{{$imagefirst}}" target="_blank">
                                                <img src="{{$imagefirst}}" class="img-thumbnail" alt="Image first" style="height:150px; object-fit: cover; width: 100%;">
                                            </a>
                                            <figcaption class="text-center mt-1"> <span class="btn btn-sm btn-dark" onclick="view('{{$storeproduct->product_id}}')">view</span> </figcaption>
                                        </figure>
                                    </div>
                                    <div class="col-md-1 col-12 mb-2">
                                        <figure style="display: flex;align-items: center;">
                                            <a href="{{$imagesizechar}}" target="_blank">
                                                <img src="{{$imagesizechar}}" class="img-thumbnail" alt="Image size chart" style="height:70px; object-fit: cover; width: 100%;">
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
                                    </div>
                                </td>
                                <td>
                                    {{$storeproduct->remote_id}}
                                </td>
                                <td>
                                    {{$storeproduct->updated_at}}
                                </td>
                                <td>
                                    {{$storeproduct->created_at}}
                                </td>
                                <td>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="4"> {{$storeproduct->message}}</textarea>
                                </td>
                                <td>
                                    <div onclick="edit_store('{{$storeproduct->id}}')">
                                        <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </div>
                                    <a href="{{route('stores.delete', $storeproduct->id)}}">
                                        <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </a>
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
            url: './products/view-mockup/'+id,
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
            valueids.push(e.getAttribute('data-id'));
        })
        $.ajax({
            url: './storeproducts/post-to-tiktok',
            method: 'GET',
            data:{
                ids:valueids,
            },
            success: function (response) {
                alert("Post Success");
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    }
    function deleteproducttiktok(){
        const valueids = [];
        const store_id = '{{}}';
        checkboxes.forEach((e)=>{
            valueids.push(e.getAttribute('data-id'));
        })
        $.ajax({
            url: './storeproducts/delete-product-tiktok',
            method: 'GET',
            data:{
                ids:valueids,
            },
            success: function (response) {
                alert("Delete Product Success");
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    }
</script>
@stop

@endsection