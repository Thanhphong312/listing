@extends('layouts.app')

@section('page-title', __('Images'))
@section('page-heading', __('Images'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Images')
</li>
@stop

@section('content')
<div class="element-box">
    <div class="card">
        <div class="card-body">

            @include('partials.messages')
            <div class="modal fade" id="addIdea">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Add image</h4>
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
            <div class="m-1">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary" onclick="add_store()"> <i class="fas fa-plus"></i> Add Image</button>
                    </div>
                </div>
            </div>
            <div class="pt-3">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th style="width:20%">ID</th>
                            <th style="width:20%">NAME</th>
                            <th style="width:20%">TYPE</th>
                            <th style="width:20%">IMAGE</th>
                            <th style="width:5%">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($metaImages as $metaImage)
                            <tr>
                                <td>
                                    {{$metaImage->id}}
                                </td>
                                <td>
                                    {{$metaImage->name}}
                                </td>
                                <td>
                                    {{getNameTypeImage($metaImage->type)}}
                                </td>
                                <td>
                                    <figure style="display: flex;align-items: center;">
                                        <a href="{{$metaImage->url}}" target="_blank">
                                            <img src="{{$metaImage->url}}" class="img-thumbnail" alt="Product Image" style="height:70px">
                                        </a>
                                    </figure>   
                                </td>
                                <td>
                                    <a href="{{route('images.delete', $metaImage->id)}}">
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
            <div class="pagination-links">
                @if (count($metaImages))
                    {{$metaImages->appends($_GET)->links()}}
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
    function edit_store(id) {
        $.get('./images/edit/' + id, function (data) {
            $("#body_edit").html(data);
        });
        $('#addIdea').modal('show');
    }
    function add_store() {
        $.get('./images/add', function (data) {
            $("#body_add").html(data);
        });
        $('#addIdea').modal('show');
    }
    function edit(id) {
        // alert("a");
        var name = $('input[name="name_edit"]').val();
        var status = $('select[name="status_edit"]').val();
        $.ajax({
            url: `./images/edit/${id}`,
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
        var name_add = $('input[name="name_add"]').val();
        var type_add = $('select[name="type_add"]').val();
        var size_chart = $('#showchat').attr('src');

        var formData = new FormData();
        formData.append('name_add',name_add)
        formData.append('type_add',type_add)
        formData.append('size_chart',size_chart)

        $.ajax({
            url: './images/add',
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
                url: './images/upload',
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

</script>
@stop

@endsection