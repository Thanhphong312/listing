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
                <div class="modal fade" id="addStore">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Add store</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="alert alert-danger" style="display:none"></div>
                                <form id="body_add" class="form-horizontal" enctype="multipart/form-data">
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal fade" id="editStore">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Add store</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="alert alert-danger" style="display:none"></div>
                                <form id="body_edit" class="form-horizontal" enctype="multipart/form-data">
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <form id="filter-form" class="col-12 row">
                        <div class="col-md-2 mt-3">
                            <div class="form-group">
                           
                                <input type="text" class="form-control" id="name" name="name" placeholder="name"
                                    value="{{ $request->name }}">
                             </div>
                        </div>
                        <div class="col-md-2 mt-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="shop_code" name="shop_code" placeholder="shop_code"
                                    value="{{ $request->shop_code }}">
                            </div>
                        </div>
                        <div class="col-md-2 mt-3">
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
                        <div class="col-md-2 mt-3">
                            <div class="form-group">
                                <select class="form-control select2" id="is_flashdeal" name="is_flashdeal">
                                    <option value="">...</option>
                                    <option value="1">Create Flashdeal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mt-3">
                            <div class="form-group">
                                <select class="form-control" id="status_e" name="status_e">
                                    <option value="">Status</option>
                                    <option value="inactive" {{ $request->status_e=='inactive'?'selected':'' }}>Inactive</option>
                                    <option value="active" {{ $request->status_e=='active'?'selected':'' }}>Active</option>
                                </select>
                            </div>
                        </div>
                        @if ($role != 'Staff')
                        <div class="col-md-2 mt-3">
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
                            <div class="col-md-2 mt-3">
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
                        @endif
                        <div class="col-12 d-flex justify-content-end mt-2 gap-2">
                                <button type="submit" class="btn btn-primary btn-rounded">
                                    <i class="fas fa-search"></i> Filters
                                </button>
                                <a href="{{ route('stores.index') }}" class="btn btn-secondary btn-rounded">
                                    <i class="fas fa-sync-alt"></i> Clear
                                </a>
                            </div>

                       
                    </form>
                </div>
                @if ($role == 'Admin' || $role == 'Seller')
                    <div class="m-1">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" onclick="add_store()">  <i class="fas fa-plus"></i> Add store</button>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="pt-3">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless" id="data-table-default">
                        <thead>
                            <tr>
                                <th>Cron Order</th>
                                <th>ID</th>
                                <th>Sup Store ID</th>
                                <th>Name</th>
                                <th>Keyword</th>
                                <th>Watermark</th>
                                <th>Flashdeal</th>
                                <th>Name Flashdeal</th>
                                <th>User</th>
                                <th>Staff</th>
                                <th>Status</th>
                                <th>Action</th>


                            </tr>
                        </thead>
                        <tbody class="store-list">
                            @foreach ($stores as $store)
                                <tr data-id="{{ $store->id }}" id="store_{{ $store->id }}">

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
                <div class="pagination-links">
                    @if (count($stores))
                        {{ $stores->appends($_GET)->links() }}
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
        $('.store-list').each(function() {
            $(this).find("tr").each(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: './stores/ajax/' + id,
                    type: 'get',
                    data: {},
                    success: function(response) {
                        $('#store_' + id).html(response);
                    },
                    error: function(xhr, status, error) {
                        // if(store_type == 4){
                        // alert('order load fail: Order ID:'+ id);
                        // }
                    }
                });
            });
        });
        function cronOrder(id, targer){
            var status = targer.checked ? 1 : 0;
            console.log(status);
            $.ajax({
                url: './stores/changeCron/' + id,
                method: 'GET',
                data:{
                    status : status
                },
                success: function(response) {
                    alert("done");
                }
            });
        }
        function edit_store(id) {
            $.get('./stores/edit/' + id, function(data) {
                $("#body_edit").html(data);
            });
            $('#editStore').modal('show');
        }

        function add_store() {
            $.get('./stores/add', function(data) {
                $("#body_add").html(data);
            });
            $('#addStore').modal('show');
        }

        function edit(id) {
            // alert("a");
            var name_edit = $('input[name="name_edit"]').val();
            var keyword_edit = $('input[name="keyword_edit"]').val();
            var name_flashdeal_edit = $('input[name="name_flashdeal_edit"]').val();
            var watermark_edit = $('input[name="watermark_edit"]').val();
            var sup_store_id_edit = $('input[name="sup_store_id_edit"]').val();
            var access_token_edit = $('input[name="access_token_edit"]').val();
            var refresh_token_edit = $('input[name="refresh_token_edit"]').val();
            var seller_edit = $('select[name="seller_edit"]').val();
            var staff_edit = $('select[name="staff_edit"]').val();
            var partner_edit = $('select[name="partner_edit"]').val();
            var order_code_edit = $('input[name="order_code_edit"]').val();
            var status_edit = $('select[name="status_edit"]').val();

            $.ajax({
                url: `./stores/edit/${id}`,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    name_edit: name_edit,
                    keyword_edit: keyword_edit,
                    watermark_edit: watermark_edit,
                    name_flashdeal_edit: name_flashdeal_edit,
                    sup_store_id_edit: sup_store_id_edit,
                    access_token_edit: access_token_edit,
                    refresh_token_edit: refresh_token_edit,
                    partner_edit: partner_edit,
                    seller_edit: seller_edit,
                    staff_edit: staff_edit,
                    order_code_edit: order_code_edit,
                    status_edit: status_edit,
                },
                success: function(response) {
                    if (JSON.parse(response).message) {
                        location.reload();
                    }
                },
            });
        }

        function add() {
            var name_add = $('input[name="name_add"]').val();
            var keyword_add = $('input[name="keyword_add"]').val();
            var watermark_add = $('input[name="watermark_add"]').val();
            var name_flashdeal_add = $('input[name="name_flashdeal_add"]').val();
            var partner_add = $('select[name="partner_add"]').val();
            var sup_store_id_add = $('input[name="sup_store_id_add"]').val();
            var access_token_add = $('input[name="access_token_add"]').val();
            var refresh_token_add = $('input[name="refresh_token_add"]').val();
            var seller_add = $('select[name="seller_add"]').val();
            var staff_add = $('select[name="staff_add"]').val();
            var order_code_add = $('input[name="order_code_add"]').val();
            var formData = new FormData();
            formData.append('name_add', name_add)
            formData.append('keyword_add', keyword_add)
            formData.append('watermark_add', watermark_add)
            formData.append('name_flashdeal_add', name_flashdeal_add)
            formData.append('sup_store_id_add', sup_store_id_add)
            formData.append('partner_add', partner_add)
            formData.append('seller_add', seller_add)
            formData.append('staff_add', staff_add)
            formData.append('access_token_add', access_token_add)
            formData.append('refresh_token_add', refresh_token_add)
            formData.append('order_code_add', order_code_add)

            $.ajax({
                url: './stores/add',
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

        function syncStoreSupover(id) {
            $.ajax({
                url: './stores/syncStoreSupover/' + id,
                method: 'GET',
                contentType: false,
                processData: false,
                success: function(response) {
                    // Handle the response, e.g., reload the page
                    if (JSON.parse(response).message) {
                        alert("Wait sync");
                    }
                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }

        function syncName(id) {
            const btn = $(`#btn_syncName_store_${id}`);
            btn.prop('disabled', true);
            btn.html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
            );

            $.ajax({
                url: './stores/syncName/' + id,
                method: 'GET',
                contentType: false,
                processData: false,
                success: function(response) {
                    const newName = JSON.parse(response).data;
                    if (newName) {
                        $(`#name_store_${id}`).text(newName);
                    }
                    
                    btn.prop('disabled', false);
                    btn.html('<i class="fas fa-sync-alt"></i>');
                }
            });
        }
        function changeStatus(id, targer){
            var status = targer.value;
            console.log(status);
            $.ajax({
                url: './stores/changeStatus/' + id,
                method: 'GET',
                data:{
                    status : status
                },
                success: function(response) {
                    alert("done");
                }
            });
        }
    </script>
@stop

@endsection
