@extends('layouts.app')

@section('page-title', __('Ideas'))
@section('page-heading', __('Ideas'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    <a href="{{ route('ideas.index')}}">@lang('Ideas')</a>
</li>
<li class="breadcrumb-item">
    {{$idea->id ?? ""}}
</li>
@stop
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
<style>
    figure.add {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100px;
        /* Chiều cao tùy chỉnh cho figure */
        border: 2px solid #ddd;
        /* Đường viền tùy chọn */
        background-color: #f9f9f9;
        /* Màu nền tùy chọn */
    }

    figure.add i {
        font-size: 24px;
        /* Kích thước biểu tượng tùy chỉnh */
        color: #333;
        /* Màu biểu tượng tùy chỉnh */
    }
    .position-relative {
        position: relative;
    }

    .position-absolute {
        position: absolute;
    }

    .top-0 {
        top: 0;
    }

    .end-0 {
        right: 0;
    }

    .m-2 {
        margin: 0.5rem;
    }

</style>
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
            <div class="modal fade" id="editIdea">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Edit idea</h4>
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
                <h3 class="title" id="title">{{$idea->title}}</h3>
            </div>
            <div class="pt-3">
                <div class="col-12">
                    <label for="list_image">Image</label>
                    <div class="row">
                        @foreach ($imageideas as $imageidea)
                            <figure class="col-2 position-relative">
                                <a href="{{$imageidea->url}}" target="_blank">
                                    <img src="{{$imageidea->url}}" class="img-thumbnail" alt="Product Image" width="100%">
                                </a>
                                <button type="button" style="margin-right: 1.2em!important;" class="btn btn-danger position-absolute top-0 end-0 m-2" onclick="removeImage('{{$imageidea->id}}','{{$imageidea->url}}')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </figure>
                        @endforeach
                        <!-- thêm figure hiện + add thêm image -->
                        <figure class="add col-1 m-2 btn btn-info" onclick="triggerFileInput()">
                            <i class="fas fa-plus"></i>
                        </figure>
                        <input type="file" id="hiddenFileInput" style="display: none;"
                            onchange="handleFileChange(event)" />

                    </div>

                </div>
                <div>
                    <label for="editor">Description</label>
                    <input id="editor" type="hidden" name="content"
                        value="{{ old('content', $idea->description ?? '') }}">
                    <trix-editor input="editor"></trix-editor>
                </div>
            </div>
        </div>
    </div>
</div>

<style>

</style>

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>

<script>


    // Flag to check if we are already editing
    let isEditing = false;

    $("#title").on('click', function () {
        if (isEditing) return; // If already editing, do nothing

        isEditing = true; // Set editing flag to true
        const content = $(this).text(); // Get the current text content

        // Replace the title with an input field
        $(this).html("<input type='text' id='title-input' class='form-control' value='" + content + "'>");

        // Automatically focus on the input field
        $('#title-input').focus();

        // When the input field loses focus, update the title
        $('#title-input').on('blur', function () {
            const updatedContent = $(this).val(); // Get the new value from the input
            $("#title").html(updatedContent); // Update the title with the new value
            isEditing = false; // Reset editing flag
        });

        // Optionally, allow the user to press Enter to save the changes
        $('#title-input').on('keypress', function (e) {
            if (e.which === 13) { // Enter key pressed
                $(this).blur(); // Trigger the blur event to save the changes
                $.ajax({
                    url: '{{route("ideas.updateTitle")}}',
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: '{{$idea->id}}',
                        title: $(this).val(),
                    },
                    success: function (response) {

                    },
                });
            }
        });
    });
    function triggerFileInput() {
        document.getElementById('hiddenFileInput').click();
    }

    function handleFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            console.log('Selected file:', file.name);
            // Bạn có thể thực hiện các hành động khác với file tại đây
            var formData = new FormData();

            formData.append('_token', "{{ csrf_token() }}");
            formData.append('idea_id', "{{$idea->id}}");
            formData.append('file', file);

            $.ajax({
                url: '{{route("ideas.upload")}}',
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
    }
    document.addEventListener('trix-change', function (event) {
        const editorElement = event.target;
        const editorContent = editorElement.innerHTML;
        console.log(editorContent);

        var formData = new FormData();

        formData.append('_token', "{{ csrf_token() }}");
        formData.append('idea_id', "{{$idea->id}}");
        formData.append('description', editorContent);

        $.ajax({
            url: '{{route("ideas.updateDescription")}}',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // Handle the response, e.g., reload the page
                // if (JSON.parse(response).message) {
                //     location.reload();
                // }
            },
            error: function (response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    });
    function removeImage(image_idea_id, file_name){
        var formData = new FormData();

        formData.append('_token', "{{ csrf_token() }}");
        formData.append('image_idea_id', image_idea_id);
        formData.append('file_name', file_name);

        $.ajax({
            url: '{{route("ideas.deleteImageIdea")}}',
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