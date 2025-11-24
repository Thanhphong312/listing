@extends('layouts.app')

@section('page-title', __('Categories'))
@section('page-heading', __('Categories'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Categories')
</li>
@stop

@section('content')
<div class="element-box">
    <div class="card">
        <div class="card-body">

            @include('partials.messages')
            <div class="modal fade" id="addCategory">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Add Category</h4>
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
            <div class="modal fade" id="editCategory">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Category</h4>
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
                        <button type="button" class="btn btn-primary" onclick="add_category()">  <i class="fas fa-plus"></i> Add category</button>
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
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="order-list">
                        @foreach($categories as $category)
                            <tr>
                                <td>{{$category->id}}</td>
                                <td>{{$category->name}}</td>
                                <td>{{$category->status?'active':'inactive'}}</td>
                                <td>
                                    <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black"
                                        onclick="edit_category('{{$category->id}}')">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a href="{{route('categories.delete', $category->id)}}">
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
        $.get('./categories/edit/' + id, function (data) {
            $("#body_edit").html(data);
        });
        $('#editCategory').modal('show');
    }
    function add_category() {
        $.get('./categories/add', function (data) {
            $("#body_add").html(data);
        });
        $('#addCategory').modal('show');
    }
    function edit(id) {
        // alert("a");
        var name = $('input[name="name_edit"]').val();
        var status = $('select[name="status_edit"]').val();
        $.ajax({
            url: `./categories/edit/${id}`,
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
        var name = $('input[name="name_add"]').val();
        var status = $('select[name="status_add"]').val();
        $.ajax({
            url: `./categories/add`,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                name: name,
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