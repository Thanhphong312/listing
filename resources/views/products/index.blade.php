@extends('layouts.app')

@section('page-title', __('Products'))
@section('page-heading', __('Products'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Products')
    </li>
@stop
<style>
    
</style>
@section('content')
    <div class="element-box">
        <div class="card">
            <div class="card-body">

                @include('partials.messages')
                <div class="modal fade" id="showmockup">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="alert alert-danger" style="display:none"></div>
                                <form id="body_showmockup" class="form-horizontal" enctype="multipart/form-data">

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal fade" id="showstore">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="alert alert-danger" style="display:none"></div>
                                <form id="body_showstore" class="form-horizontal" enctype="multipart/form-data">

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal fade" id="confirmmultidelete">
                    <div class="modal-dialog modal-lg" style="width: 200px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                Multi Delete product
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <input type="hidden" id="id_product_delete">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary">Exit</button>
                                    </div>
                                    <div class="col-md-6 text-end" style="display: flex;justify-content: end;">
                                        <button type="button" class="btn btn-danger"
                                            onclick="deletemultiproduct()">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="confirmdelete">
                    <div class="modal-dialog modal-lg" style="width: 200px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                Delete product
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <input type="hidden" id="id_product_delete">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary">Exit</button>
                                    </div>
                                    <div class="col-md-6 text-end" style="display: flex;justify-content: end;">
                                        <button type="button" class="btn btn-danger"
                                            onclick="deleteproduct()">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="confirmdup">
                    <div class="modal-dialog modal-lg" style="width: 500px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                Duplicate product
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        
                                                <select class="form-control w-100" id="dup_template" name="dup_template" style="width: 200px; "
                                                    onchange="changetemplate()">
                                                    <option value="">template...</option>
                                                    @foreach($templates as $template)
                                                        <option value="{{ $template->id }}">
                                                            {{ $template->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                          
                                    </div>
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
                <div class="modal fade" id="confirmmultidup">
                    <div class="modal-dialog modal-lg" style="width: 500px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                Multi Duplicate product
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        
                                                <select class="form-control w-100" id="multi_dup_template" name="multi_dup_template" style="width: 200px; "
                                                    onchange="changetemplate()">
                                                    <option value="">template...</option>
                                                    @foreach($templates as $template)
                                                        <option value="{{ $template->id }}">
                                                            {{ $template->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                          
                                    </div>
                                    <input type="hidden" id="id_product_dup">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary">Exit</button>
                                    </div>
                                    <div class="col-md-6 text-end" style="display: flex;justify-content: end;">
                                        <button type="button" class="btn btn-success" onclick="multidupproduct()">Dup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-1">
                    <div class="row">
                        <form id="filter-form" class="col-12 row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="id" name="id"
                                        placeholder="Product ID" value="{{ $request->id }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Title"
                                        value="{{ $request->title }}">
                                </div>
                            </div>
                            @if ($role != 'Staff')
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-control select2" id="staff_id" name="staff_id">
                                            <option value="">Staff...</option>
                                            @foreach (listStaff() as $staff)
                                                <option value="{{ $staff['id'] }}"
                                                    {{ $request->staff_id == $staff['id'] ? 'selected' : '' }}>
                                                    {{ $staff['username'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($role != 'Seller')
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control select2" id="seller_id" name="seller_id">
                                                <option value="">Seller...</option>
                                                @foreach (listSeller() as $staff)
                                                    <option value="{{ $staff['id'] }}"
                                                        {{ $request->seller_id == $staff['id'] ? 'selected' : '' }}>
                                                        {{ $staff['username'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control select2" id="store_id" name="store_id">
                                        <option value="">Store...</option>
                                        @foreach (listStore() as $store)
                                            <option value="{{ $store['id'] }}"
                                                {{ $request->store_id == $store['id'] ? 'selected' : '' }}>
                                                {{ $store['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <input class="form-control datepicker" name="datefrom" id="datefrom" autocomplete="off"
                                    value="{{ Request::get('datefrom') }}" placeholder="Date from">
                            </div>
                            <div class="col-6 col-md-2">
                                <input class="form-control datepicker" name="dateto" id="dateto" autocomplete="off"
                                    value="{{ Request::get('dateto') }}" placeholder="Date to">
                            </div>
                            <div class="col-12 d-flex justify-content-end mt-2 gap-2">
                                <button type="submit" class="btn btn-primary btn-rounded">
                                    <i class="fas fa-search"></i> Filters
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-rounded">
                                    <i class="fas fa-sync-alt"></i> Clear
                                </a>
                            </div>

                        </form>
                        <div class="col-md-6">
                            <a type="button" class="btn btn-primary" href="{{ route('products.add') }}">
                                <i class="fas fa-plus"></i> Add Product
                            </a>
                            <a type="button" class="btn btn-primary" href="{{ route('products.addtemplate') }}">
                                <i class="fas fa-plus"></i> Add Product Template
                            </a>
                            <a type="button" class="btn btn-primary" href="{{ route('designs.cenvert') }}">
                                <i class="fas fa-plus"></i> Add Design
                            </a>
                        </div>
                        <div class="col-md-6 text-end" style="display: flex;justify-content: end;">
                            <div type="button" class="btn btn-primary m-2" onclick="showMultiDup()">
                                <i class="fas fa-clone"></i> Dup
                            </div>
                            <div type="button" class="btn btn-primary m-2" onclick="showStore()">
                                <i class="fas fa-store"></i> Store
                            </div>
                            <div type="button" class="btn btn-danger m-2" onclick="confirmmultidelete()">
                                <i class="fas fa-trash"></i> Delete
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-3">
                    <div class="table-responsive">
                    <table id="data-table-default" class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th ><input type="checkbox" onclick="check_all(this)"></th>
                                <th >ID</th>
                                <th style ="min-width:600px;">Product</th>
                                <th >Store</th>
                                <th >Discount flashdeal</th>
                                <th >Templete</th>
                                <th >User</th>
                                <th >Created</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody class="product-list">
                            @foreach ($products as $product)
                                <tr data-id="{{ $product->id }}" id="product_{{ $product->id }}">

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    </div>
                </div>
                <div class="pagination-links">
                    @if (count($products))
                        {{ $products->appends($_GET)->links() }}
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
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

@section('scripts')
    <script>
        $('.product-list').each(function() {
            $(this).find("tr").each(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: './products/ajax/' + id,
                    type: 'get',
                    data: {},
                    success: function(response) {
                        $('#product_' + id).html(response);
                    },
                    error: function(xhr, status, error) {
                        // if(store_type == 4){
                        // alert('order load fail: Order ID:'+ id);
                        // }
                    }
                });
            });
        });

        function edit_idea(id) {
            $.get('./designs/edit/' + id, function(data) {
                $("#body_edit").html(data);
            });
            $('#addIdea').modal('show');
        }

        function add_idea() {
            $.get('./designs/add', function(data) {
                $("#body_add").html(data);
            });
            $('#addIdea').modal('show');
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
                success: function(response) {
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
                success: function(response) {
                    // Handle the response, e.g., reload the page
                    if (JSON.parse(response).message) {
                        location.reload();
                    }
                },
                error: function(response) {
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
                    url: '{{ route('designs.upload') }}',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle the response, e.g., reload the page
                        if (JSON.parse(response).message) {
                            $("#showchat").attr("src", JSON.parse(response).data);
                        }
                    },
                    error: function(response) {
                        // Handle the error, e.g., show an alert
                        alert(response.responseJSON.message);
                    }
                });
            }
        }

        function view(id) {
            $.ajax({
                url: './products/view-mockup/' + id,
                method: 'GET',
                success: function(response) {
                    $("#body_showmockup").html(response);
                    let imageContainer = document.getElementById('imageContainer');
                    let productId = imageContainer.getAttribute('data-product-id');
                    console.log("imageContainer");
                    console.log(imageContainer);
                    new Sortable(imageContainer, {
                        animation: 150,
                        onEnd: function() {
                            // Get the new order of images
                            let order = [];
                            document.querySelectorAll('#imageContainer .col-3').forEach((
                                element) => {
                                order.push(JSON.parse(element.getAttribute('data-id')));
                            });

                            // Send the new order to the server
                            fetch(`/products/update-image-order`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        id: productId,
                                        order: order
                                    })
                                }).then(response => response.json())
                                .then(data => {
                                    // if (data.status === 'success') {
                                    //     alert("Order updated successfully!");
                                    // } else {
                                    //     alert("Failed to update order.");
                                    // }
                                }).catch(error => console.error('Error:', error));
                        }
                    });
                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
            $('#showmockup').modal('show');

        }

        function check_all(target) {
            const checkboxallstore = document.querySelectorAll('input[class="checkboxallproduct"]');

            checkboxallstore.forEach((e) => {
                e.checked = target.checked;
            })
        }

        function check_all_store(target) {
            const checkboxallstore = document.querySelectorAll('input[class="checkboxallstore"]');
            checkboxallstore.forEach((e) => {
                e.checked = target.checked;
            })
        }

        function showStore() {
            $.ajax({
                url: './products/view-store',
                method: 'GET',
                success: function(response) {
                    $("#body_showstore").html(response);
                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
            $('#showstore').modal('show');
        }

        function posttoStore() {
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
                data: {
                    ids: valueids,
                    products: valueproducts,
                },
                success: function(response) {
                    alert("Post Success");
                    $('#showstore').modal('hide');
                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }

        function posttoStoreTiktok() {
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
                data: {
                    ids: valueids,
                    products: valueproducts,
                },
                success: function(response) {
                    alert("Post to Store & Tiktok Success");
                    $('#showstore').modal('hide');
                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
        function confirmmultidelete(id) {
            $("#confirmmultidelete").modal('show');
        }

        function deletemultiproduct() {
            const valueproducts = [];

            const checkboxallproduct = document.querySelectorAll('input.checkboxallproduct');
            checkboxallproduct.forEach((checkbox) => {
                if (checkbox.checked) {
                    valueproducts.push(checkbox.getAttribute('data-id'));
                }
            });
            if(valueproducts.length==0){
                alert("please choose products!");
                return;
            }
            console.log(valueproducts);

            $.ajax({
                url: './products/delete-multi',
                method: 'GET',
                data:{
                    valueproducts: valueproducts
                },
                success: function(response) {
                    $('#confirmdelete').modal('hide');
                    location.reload();
                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
        function confirmdelete(id) {
            $("#id_product_delete").val(id);
            $("#confirmdelete").modal('show');
        }

        function deleteproduct() {
            id = $("#id_product_delete").val();
            $.ajax({
                url: './products/delete/' + id,
                method: 'GET',
                success: function(response) {
                    $('#confirmdelete').modal('hide');
                    location.reload();
                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }

        function confirmdup(id) {
            $("#id_product_dup").val(id);
            $("#confirmdup").modal('show');
        }

        function dupproduct() {
            id = $("#id_product_dup").val();
            dup_template = $("#dup_template").val();
            console.log(id);
            $.ajax({
                url: './products/duplicate/' + id,
                method: 'GET',
                data:{
                    dup_template:dup_template
                },
                success: function(response) {
                    $('#confirmdup').modal('hide');
                    location.reload();
                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
        function showMultiDup(){
            $("#confirmmultidup").modal('show');
        }
        function multidupproduct() {
            const valueproducts = [];

            const checkboxallproduct = document.querySelectorAll('input.checkboxallproduct');
            checkboxallproduct.forEach((checkbox) => {
                if (checkbox.checked) {
                    valueproducts.push(checkbox.getAttribute('data-id'));
                }
            });
            multi_dup_template = $("#multi_dup_template").val();
            if(valueproducts.length==0){
                alert("please choose products!");
                return;
            }
            console.log(valueproducts);
            $.ajax({
                url: './products/multi-duplicate',
                method: 'GET',
                data:{
                    ids : valueproducts,
                    multi_dup_template:multi_dup_template
                },
                success: function(response) {
                    $('#confirmdup').modal('hide');
                    location.reload();
                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
        function showstoreproduct(product_id) {
            $.ajax({
                url: './products/showstore/' + product_id,
                method: 'GET',
                success: function(response) {
                    $("#showmockup").modal('show');
                    $("#body_showmockup").html(response);

                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
    </script>
@stop

@endsection
