@extends('layouts.app')

@section('page-title', __('Ideas'))
@section('page-heading', __('Ideas'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Ideas')
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
                            <h4 class="modal-title">Add idea</h4>
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
                        <button type="button" class="btn btn-primary" onclick="add_idea()">Add Idea</button>
                    </div>
                </div>
            </div>
            <div class="pt-3">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th style="width:10%">Title</th>
                            <th style="width:30%">Image</th>
                            <th style="width:45%">Description</th>
                            <th style="width:5%">action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ideas as $idea)
                            <tr>
                                <td>
                                    <a href="{{route('ideas.show', $idea->id)}}">
                                        {{$idea->title}}
                                    </a>
                                </td>
                                <td class="row">
                                    @php 
                                        $imageideas = $idea->imageideas()->limit(4)->get();
                                    @endphp 
                                    @foreach ($imageideas as $imageidea)
                                        <figure class="col-3">
                                            <!-- <figcaption class="text-center">Image</figcaption> -->
                                            <a href="{{$imageidea->url}}" target="_blank">
                                                <img src="{{$imageidea->url}}" class="img-thumbnail" alt="Product Image"
                                                    width="110">
                                            </a>
                                        </figure>
                                    @endforeach
                                </td>
                                <td>{!! $idea->description !!}</td>
                                <td>
                                    <a href="{{route('ideas.delete', $idea->id)}}">
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
                @if (count($ideas))
                    {{$ideas->appends($_GET)->links()}}
                @endif
            </div>
        </div>
    </div>
</div>

<style>

</style>
@section('scripts')
<script>
    function add_idea() {
        $.get('./ideas/add', function (data) {
            $("#body_add").html(data);
        });
        $('#addIdea').modal('show');
    }

    function add() {
        var title_add = $('input[name="title_add"]').val();
        var des_add = $('textarea[name="des_add"]').val();
        var formData = new FormData();

        formData.append('_token', "{{ csrf_token() }}");
        formData.append('title_add', title_add);
        formData.append('des_add', des_add);

        // Handle file inputs
        $('input[name="list_image[]"]').each(function () {
            if (this.files.length > 0) {
                $.each(this.files, function (index, file) {
                    formData.append('files[]', file);
                });
            }
        });

        $.ajax({
            url: './ideas/add',
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


</script>
@stop

@endsection