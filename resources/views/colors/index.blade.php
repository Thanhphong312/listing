@extends('layouts.app')

@section('page-title', __('Colors'))
@section('page-heading', __('Colors'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Colors')
</li>
@stop

@section('content')
<div class="element-box">
    <div class="card">
        <div class="card-body">

            @include('partials.messages')
            <div class="modal fade" id="addColor">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Add Color</h4>
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
            <div class="modal fade" id="editColor">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Color</h4>
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
                        <button type="button" class="btn btn-primary" onclick="add_category()">  <i class="fas fa-plus"></i> Add color</button>
                    </div>
                </div>
            </div>
            <div class="pt-12">
            <div class="table-responsive">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="order-list">
                        @foreach($colors as $color)
                            <tr>
                                <td>
                                    {{$color->id}}
                                </td>
                                <td>
                                    <span style="border:1px solid black; width:20px;height:27px;background-color:{{$color->hex}};" class="btn btn-square-md m-1"></span>
                                    {{$color->name}}
                                </td>
                                <td>
                                    <div class="btn {{$color->type?'btn-success':'btn-secondary'}}">{{$color->type?'Light':'Dark'}}</div>
                                </td>
                                <td>
                                    <div class="btn {{$color->status?'btn-info':'btn-danger'}}">{{$color->status?'active':'inactive'}}</div>
                                </td>
                                <td>
                                    <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black"
                                        onclick="edit_category('{{$color->id}}')">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a href="{{route('categories.delete', $color->id)}}">
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
            <ul class="pagination mt-3">

            </ul>
        </div>
    </div>
</div>

<style>

</style>
@section('scripts')
<script>
    function edit_category(id) {
        $.get('./colors/edit/' + id, function (data) {
            $("#body_edit").html(data);
        });
        $('#editColor').modal('show');
    }
    function add_category() {
        $.get('./colors/add', function (data) {
            $("#body_add").html(data);
        });
        $('#addColor').modal('show');
    }
    function edit(id) {
        // alert("a");
        var name = $('input[name="name_edit"]').val();
        var hex = $('input[name="hex_edit"]').val();
        var type = $('select[name="type_edit"]').val();
        var status = $('select[name="status_edit"]').val();
        $.ajax({
            url: `./colors/edit/${id}`,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                name: name,
                hex: hex,
                type: type,
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
        var name = $('input[name="name_add"]').val();
        var hex = $('input[name="hex_add"]').val();
        var type = $('select[name="type_add"]').val();
        var status = $('select[name="status_add"]').val();
        $.ajax({
            url: `./colors/add`,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                name: name,
                hex: hex,
                type: type,
                status: status,
            },
            success: function (response) {
                console.log(response);
                if (JSON.parse(response).message) {
                    location.reload();
                }
            },
            error: function (response) {
                console.log(response.responseJSON.message);
                alert(response.responseJSON.message);
            }
        });
    }
</script>
@stop

@endsection