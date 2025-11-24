@extends('layouts.app')

@section('page-title', __('Flashdeals'))
@section('page-heading', __('Flashdeals'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Flashdeals')
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
                            <h4 class="modal-title">Add flashdeal</h4>
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
                            <h4 class="modal-title">Edit flashdeal</h4>
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
            <div class="m-1">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary" onclick="add_flashdeal()">
                            <i class="fas fa-plus"></i> Add Flashdeal
                        </button>
                        <button type="button" class="btn btn-success" onclick="sync_flashdeal()">
                            <i class="fas fa-sync-alt"></i> Sync Flashdeal
                        </button>
                    </div>
                </div>
            </div>
            <div class="pt-3">
            <div class="table-responsive">
                <table class="table table-striped table-borderless" id="data-table-default">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Store ID</th>
                            <th>Promotion Name</th>
                            <th>Activity Type</th>
                            <th>Product Level</th>
                            <th>Status Fld</th>
                            <th>Begin Time</th>
                            <th>End Time</th>
                            <th>Status Fld</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <!-- 'store_id', 'promotion_name', 'activity_type', 'product_level', 'status_fld', 'begin_time', 'end_time', 'auto', 'status' -->
                    <tbody>
                        @foreach($flashdeals as $flashdeal)
                            <tr>
                                <td>{{$flashdeal->id}}</td>
                                <td>{{$flashdeal->store_id}}</td>
                                <td>{{$flashdeal->promotion_name}}</td>
                                <td>{{$flashdeal->activity_type}}</td>
                                <td>{{$flashdeal->product_level}}</td>
                                <td>{{$flashdeal->status_fld}}</td>
                                <td>{{$flashdeal->begin_time}}</td>
                                <td>{{$flashdeal->end_time}}</td>
                                <td>{{$flashdeal->auto}}</td>
                                <td>{{$flashdeal->status}}</td>
                                <td>
                                    <div onclick="edit_store('{{$flashdeal->id}}')">
                                        <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </div>
                                    <a href="{{route('flashdeals.delete', $flashdeal->id)}}">
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
                @if (count($flashdeals))
                    {{$flashdeals->appends($_GET)->links()}}
                @endif
            </div>
        </div>
    </div>
</div>

@section('CSS')
<link rel="stylesheet prefetch"
    href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
<style>
    .custom-select-status {
        appearance: none;
        /* Remove default arrow icon on Firefox */
        -webkit-appearance: none;
        /* Remove default arrow icon on Chrome and Safari */
        -moz-appearance: none;
        /* Remove default arrow icon on older versions of Firefox */
    }
</style>
@endsection
@section('scripts')
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

<script>
    $(".datepicker").datepicker({
        autoclose: false,
        todayHighlight: true,
        clearBtn: true
    });
    function edit_store(id) {
        $.get('./flashdeals/edit/' + id, function (data) {
            $("#body_edit").html(data);
        });
        $('#editStore').modal('show');
    }
    function add_flashdeal() {
        $.get('./flashdeals/add', function (data) {
            $("#body_add").html(data);
        });
        $('#addStore').modal('show');
    }
    function edit(id) {
        // alert("a");
        var name_edit = $('input[name="name_edit"]').val();
        var keyword_edit = $('input[name="keyword_edit"]').val();
        var sup_store_id_edit = $('input[name="sup_store_id_edit"]').val();
        var access_token_edit = $('input[name="access_token_edit"]').val();
        var refresh_token_edit = $('input[name="refresh_token_edit"]').val();
        var seller_edit = $('select[name="seller_edit"]').val();
        var partner_edit = $('select[name="partner_edit"]').val();

        $.ajax({
            url: `./flashdeals/edit/${id}`,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                name_edit: name_edit,
                keyword_edit: keyword_edit,
                sup_store_id_edit: sup_store_id_edit,
                access_token_edit: access_token_edit,
                refresh_token_edit: refresh_token_edit,
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
        var datefrom = $('input[name="datefrom"]').val();
        var dateto = $('select[name="dateto"]').val();
        var store_add = $('select[name="store_add"]').val();
        var level_add = $('select[name="level_add"]').val();
       
        var formData = new FormData();
        formData.append('name_add',name_add)
        formData.append('datefrom',datefrom)
        formData.append('dateto',dateto)
        formData.append('store_add',store_add)
        formData.append('level_add',level_add)

        $.ajax({
            url: './flashdeals/add',
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
    function sync_flashdeal(id){
        $.ajax({
                url: './stores/syncStoreSupover/'+id,
                method: 'GET',
                contentType: false,
                processData: false,
                success: function (response) {
                    // Handle the response, e.g., reload the page
                    if (JSON.parse(response).message) {
                        alert("Wait sync");
                    }
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