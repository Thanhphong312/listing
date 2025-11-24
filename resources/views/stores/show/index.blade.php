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
                <h3 class="title" id="title">{{$design->title}}</h3>
            </div>
            <div class="pt-3">
                <div class="col-6 border-light">
                    <label for="idea_add">Ideas </label>
                    <select class="form-control select2" id="idea_add" name="idea_add" >
                        <option value="">Idea</option>
                        @foreach(getIdeas() as $idea)
                        <option value="{{$idea->id}}" {{$design->idea_id==$idea->id?'selected':''}}>
                            {{$idea->title}}
                        </option>
                        @endforeach
                    </select>
                </div>

                @if($design->size_chart)
                <div class="col-12">
                    <label for="list_image">Size chart</label>
                    <div class="row">
                        <figure class="col-4 position-relative">
                            <a href="{{$design->size_chart}}" target="_blank">
                                <img src="{{$design->size_chart}}" class="img-thumbnail" alt="Product Image" style="height:200px;">
                            </a>
                            <button type="button" style="margin-right: 1.2em!important;" class="btn btn-danger position-absolute top-0 end-0 m-2" onclick="removeImage('{{$design->id}}','{{$design->size_chart}}')">
                                <i class="fas fa-times"></i>
                            </button>
                        </figure>
                    </div>
                </div>

                @else
                <div class="col-4">
                    <label for="size_chart_add">Size chart</label>
                    <input class="form-control" type="file" name="size_chart_add" id="size_chart_add" value="" onchange="handleFileChange(event)">
                </div>
                <div class="col-8">
                    <label for="name_edit_add">Review</label>
                    <figure class="position-relative">
                        <a href="" target="_blank">
                            <img src="" id="showchat" class="img-thumbnail" alt="Product Image" style="width:200px; height: 200px;">
                        </a>
                    </figure>
                </div>
                @endif
                <div class="col-12 m-2 border-light">
                    <label for="name_edit_add">List color </label>
                    <div class="group">
                        <div class="title_container">
                            <ul class="flex">
                                <li>
                                    <input type="radio" id="allColors" name="rColor" value="All">
                                    <label for="allColors"> Tất cả</label>
                                </li>
                                
                                <li>
                                    <input type="radio" id="lightColor" name="rColor" value="All" checked>
                                    <label for="lightColor"> Sáng (Light/White)</label>
                                </li>
                                <li>
                                    <input type="radio" id="darkColor" name="rColor" value="All">
                                    <label for="darkColor"> Tối (Black)</label>
                                </li>
                            </ul>
                        </div>
                        <div class="color_container" id="checkColor">
                            <ul class="flex">
                                @foreach ($colors as $color)
                                <li>
                                    <input type="checkbox"
                                        id="{{ $color->name }}"
                                        name="{{ $color->name }}"
                                        value="{{ $color->id }}"
                                        {{ !empty($color->colordesigns) && $color->colordesigns?->first()?->design_id == $design->id ? 'checked' : '' }}>
                                    <label for="{{ $color->name }}">{{ $color->name }}</label>
                                    <span style="border:1px solid black; width:10px; height:10px; background-color:{{ $color->hex }};" class="btn btn-square-md m-1"></span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" onclick="update()">submit</button>
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

    $("#title").on('click', function() {
        if (isEditing) return; // If already editing, do nothing

        isEditing = true; // Set editing flag to true
        const content = $(this).text(); // Get the current text content

        // Replace the title with an input field
        $(this).html("<input type='text' id='title-input' class='form-control' value='" + content + "'>");

        // Automatically focus on the input field
        $('#title-input').focus();

        // When the input field loses focus, update the title
        $('#title-input').on('blur', function() {
            const updatedContent = $(this).val(); // Get the new value from the input
            $("#title").html(updatedContent); // Update the title with the new value
            isEditing = false; // Reset editing flag
        });

        // Optionally, allow the user to press Enter to save the changes
        $('#title-input').on('keypress', function(e) {
            if (e.which === 13) { // Enter key pressed
                $(this).blur(); // Trigger the blur event to save the changes
                $.ajax({
                    url: '{{route("designs.updateTitle")}}',
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: '{{$design->id}}',
                        title: $(this).val(),
                    },
                    success: function(response) {

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
            formData.append('design_id', '{{$design->id}}');
            formData.append('file', file);

            $.ajax({
                url: '{{route("designs.uploadShow")}}',
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

    document.addEventListener('trix-change', function(event) {
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
            success: function(response) {
                // Handle the response, e.g., reload the page
                // if (JSON.parse(response).message) {
                //     location.reload();
                // }
            },
            error: function(response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    });

    function removeImage(image_idea_id, file_name) {
        var formData = new FormData();

        formData.append('_token', "{{ csrf_token() }}");
        formData.append('design_id', image_idea_id);
        formData.append('file_name', file_name);

        $.ajax({
            url: '{{route("designs.deleteImageSizeChart")}}',
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
    (function() {
        const allColorsRadio = document.getElementById('allColors');
        const darkColorRadio = document.getElementById('darkColor');
        const lightColorRadio = document.getElementById('lightColor');
        const checkColorDiv = document.getElementById('checkColor');

        allColorsRadio.addEventListener('change', handleColorRadioChange);
        darkColorRadio.addEventListener('change', handleColorRadioChange);
        lightColorRadio.addEventListener('change', handleColorRadioChange);

        function handleColorRadioChange() {
            const checkboxes = checkColorDiv.querySelectorAll('input[type="checkbox"]');

            // Tạo các mảng JavaScript từ các giá trị PHP
            const darkColors = {!!json_encode($darks) !!};
            const lightColors = {!!json_encode($lights) !!};

            checkboxes.forEach(checkbox => {
                switch (this.id) {
                    case 'allColors':
                        checkbox.checked = true;
                        break;
                    case 'darkColor':
                        checkbox.checked = darkColors.includes(checkbox.id);
                        break;
                    case 'lightColor':
                        checkbox.checked = lightColors.includes(checkbox.id);
                        break;
                    default:
                        break;
                }
            });
        }
    })();
    function update(){
        var idea_add = $('select[name="idea_add"]').val();
       
        var color = [];
        const checkColorDiv = document.getElementById('checkColor');
        const checkboxes = checkColorDiv.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(element => {
            if(element.checked){
                color.push(element.value)
            }
        });
        var formData = new FormData();
        formData.append('idea_add',idea_add)
        formData.append('color',color)
        console.log(idea_add);
        console.log(color);

        $.ajax({
            url: "{{route('designs.edit',$design->id)}}",
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