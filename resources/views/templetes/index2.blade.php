@extends('layouts.app')

@section('page-title', __('Templetes'))
@section('page-heading', __('Templetes'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Templetes')
</li>
@stop

@section('content')
<div class="element-box">
    <div class="card">
        <div class="card-body">

            @include('partials.messages')
            <div class="modal fade" id="confirmdup">
                <div class="modal-dialog modal-lg" style="width: 200px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            Duplicate product
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" id="id_product_dup">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary">Exit</button>
                                </div>
                                <div class="col-md-6 text-end" style="display: flex;justify-content: end;">
                                    <button type="button" class="btn btn-success" onclick="dupproduct()">Dup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="showstaff">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="alert alert-danger" style="display:none"></div>
                            <form id="body_showstaff" class="form-horizontal" enctype="multipart/form-data">

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal fade" id="confirmdelete">
                <div class="modal-dialog modal-lg" style="width: 200px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            Delete Templete
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" id="id_product_delete" >
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary" >Exit</button>
                                </div>
                                <div class="col-md-6 text-end" style="display: flex;justify-content: end;">
                                    <button type="button" class="btn btn-danger" onclick="deleteproduct()">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="setupTemplate">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Setup</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="alert alert-danger" style="display:none"></div>
                            <div id="body_setup" class="form-horizontal">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
            <div class="m-1">
                <div class="row">
                    <div class="col-md-6">
                        <a type="button" class="btn btn-primary" href="{{route('templates2.add')}}">  <i class="fas fa-plus"></i> Add Templete</a>
                        <button type="button" class="btn btn-primary ms-3" onclick="setup()">  <i class="fas fa-plus"></i> Setup</button>
                    </div>
                </div>
            </div>
            <div class="pt-3">
            <div class="table-responsive">
                <table id="data-table-default" class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th style ="min-width:300px;">Product</th>
                            <th>Discount</th>
                            <th>Staff</th>
                            <th>User</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templetes as $templete)
                            <tr>
                                <td>
                                   {{$templete->id}}
                                </td>
                                <td>
                                    {{$templete->name}}
                                </td>
                                <td class="card-header p-2" style="border-radius:5px; border:0; box-shadow: 0px 0px 8px #8080807a;">
                                <div class="d-flex flex-wrap align-items-start justify-content-start w-100">
                                    @php 
                                        $json = json_decode($templete);
                                        if ($json) {
                                                $imagefirst = $json->main_images[0] ?? './assets/img/image-default.png';
                                                $imagesizechar =  isset($json->size_chart) ?$json->size_chart: './assets/img/image-default.png';
                                                $pricefirst = isset($json->skus[0])?$json->skus[0]->price->amount : 0;

                                                // Check if `options` array has enough elements before accessing each one
                                                $styles =  [];
                                                $colors = [];
                                                $sizes = [];

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
                                    
                                    <div class="col-4">
                                        <figure style="display: flex;align-items: center;">
                                            <a href="{{$imagesizechar}}" target="_blank">
                                                <img src="{{$imagesizechar}}" class="img-thumbnail" alt="Image size chart" style="height:150px">
                                            </a>
                                        </figure>
                                    </div>
                                    <div class="col-8 row">
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
                                <td>
                                    {{$templete->discount}}%
                                </td>
                                <td>
                                    {{countStaff($templete->id)}}
                                </td>
                                <td>
                                    {{getUsernameById($templete->user_id)}}
                                </td>
                                <td>
                                    {{$templete->created_at}}
                                </td>
                                <td> 
                                    @if($role=="Admin") 
                                        <button onclick="chooseUser('{{$templete->id}}')" class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                            <i class="fa fa-key"></i>
                                        </button> 
                                        <a href="{{route('templates2.edit', $templete->id)}}">
                                            <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </a>
                                        <button onclick="confirmdelete('{{$templete->id}}')" class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                            <i class="fa fa-trash"></i>
                                        </button>  
                                        <button onclick="confirmdup('{{$templete->id}}')" class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                            <i class="fa fa-clone"></i>
                                        </button> 
                                    @else 
                                        @if($user->id==$templete->user_id)
                                            <a href="{{route('templates.edit', $templete->id)}}">
                                                <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </a>
                                            <button onclick="confirmdelete('{{$templete->id}}')" class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                                <i class="fa fa-trash"></i>
                                            </button>  
                                            <button onclick="confirmdup('{{$templete->id}}')" class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                                <i class="fa fa-clone"></i>
                                            </button> 
                                        @endif                                  
                                    @endif                                  
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
            <div class="pagination-links">
                @if (count($templetes))
                    {{$templetes->appends($_GET)->links()}}
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
    function edit_idea(id) {
        $.get('./designs/edit/' + id, function (data) {
            $("#body_edit").html(data);
        });
        $('#addIdea').modal('show');
    }
    function add_idea() {
        $.get('./designs/add', function (data) {
            $("#body_add").html(data);
        });
        $('#addIdea').modal('show');
    }

    function setup() {
        $.get('./templates/setup', function (data) {
            $("#body_setup").html(data);
        });
        $('#setupTemplate').modal('show');
    }

    function edit(id) {
        // alert("a");
        var name = $('input[name="name_edit"]').val();
        var status = $('select[name="status_edit"]').val();
        $.ajax({
            url: `./designs/edit/${id}`,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                name: name,
                status: status,
            },
            success: function (response) {
                if (JSON.parse(response).message) {
                    location.reload();
                }
            },
        });
    }

    function add() {
        var title_add = $('input[name="title_add"]').val();
        var idea_add = $('select[name="idea_add"]').val();
        var size_chart = $('#showchat').attr('src');
        var color = [];
        const checkColorDiv = document.getElementById('checkColor');
        const checkboxes = checkColorDiv.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(element => {
            if (element.checked) {
                color.push(element.value)
            }
        });
        var formData = new FormData();
        formData.append('title_add', title_add)
        formData.append('idea_add', idea_add)
        formData.append('size_chart', size_chart)
        formData.append('color', color)
        console.log(title_add);
        console.log(idea_add);
        console.log(size_chart_add);
        console.log(color);

        $.ajax({
            url: './designs/add',
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
    function handleFileChange(event, design_id) {
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
        const checkboxallstore = document.querySelectorAll('input[class="checkboxallproduct"]');

        checkboxallstore.forEach((e)=>{
            e.checked = target.checked;
        })
    }
    function check_all_store(target){
        const checkboxallstore = document.querySelectorAll('input[class="checkboxallstore"]');
        checkboxallstore.forEach((e)=>{
            e.checked = target.checked;
        })
    }
    function showStore(){
        $.ajax({
            url: './products/view-store',
            method: 'GET',
            success: function (response) {
                $("#body_showstore").html(response);  
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
        $('#showstore').modal('show');
    }
    function posttoStore(){
        const valueids = [];
        const checkboxallstore = document.querySelectorAll('input.checkboxallstore');
        const valuestores = [];  
        const valueproducts = [];  

        checkboxallstore.forEach((checkbox) => {
            if (checkbox.checked) {
                valueids.push(checkbox.getAttribute('data-id'));
            }
        });
        const checkboxallproduct = document.querySelectorAll('input.checkboxallproduct');
        checkboxallproduct.forEach((checkbox) => {
            if (checkbox.checked) {
                valueproducts.push(checkbox.getAttribute('data-id'));
            }
        });
        $.ajax({
            url: './products/post-to-store',
            method: 'GET',
            data:{
                ids:valueids,
                products:valueproducts,
            },
            success: function (response) {
                alert("Post Success");
                $('#showstore').modal('hide');
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    }
    function posttoStoreTiktok(){
        const valueids = [];
        const checkboxallstore = document.querySelectorAll('input.checkboxallstore');
        const valuestores = [];  
        const valueproducts = [];  

        checkboxallstore.forEach((checkbox) => {
            if (checkbox.checked) {
                valueids.push(checkbox.getAttribute('data-id'));
            }
        });
        const checkboxallproduct = document.querySelectorAll('input.checkboxallproduct');
        checkboxallproduct.forEach((checkbox) => {
            if (checkbox.checked) {
                valueproducts.push(checkbox.getAttribute('data-id'));
            }
        });
        $.ajax({
            url: './products/post-to-store-tiktok',
            method: 'GET',
            data:{
                ids:valueids,
                products:valueproducts,
            },
            success: function (response) {
                alert("Post to Store & Tiktok Success");
                $('#showstore').modal('hide');
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    }
    function confirmdelete(id){
        $("#id_product_delete").val(id);
        $("#confirmdelete").modal('show');
    }
    function deleteproduct(){
        id =  $("#id_product_delete").val();
        $.ajax({
            url: './templates/delete/'+id,
            method: 'GET',
            success: function (response) {
                $('#confirmdelete').modal('hide');
                location.reload();
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    }
    function confirmdup(id){
        $("#id_product_dup").val(id);
        $("#confirmdup").modal('show');
    }
    function dupproduct(){
        id =  $("#id_product_dup").val();
        console.log(id);
        $.ajax({
             url: './templates/duplicate/'+id,
             method: 'GET',
             success: function (response) {
                 $('#confirmdup').modal('hide');
                 location.reload();
             },
             error: function (response) {
                 // Handle the error, e.g., show an alert
                 alert(response.responseJSON.message);
             }
         });
    }
    function chooseUser(id){
        $.ajax({
            url: './templates/choose-user/'+id,
            method: 'GET',
            success: function (response) {
                $("#body_showstaff").html(response);
                $("#showstaff").modal('show');
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
         });
    }
    function accepttemp(user_id, template_id){
        console.log(user_id);
        console.log(template_id);
        $.ajax({
            url: './templates/accept-user/'+template_id,
            method: 'GET',
            data:{
                user_id:user_id,
            },
            success: function (response) {
                // $("#body_showstaff").html(response);
                // $("#showstaff").modal('show');
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                // alert(response.responseJSON.message);
            }
         });
    }
</script>
@stop

@endsection