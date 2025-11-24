@extends('layouts.app')

@section('page-title', __('Design Items'))
@section('page-heading', __('Design Items'))
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

    .custom-select-status {
        appearance: none;
        /* Remove default arrow icon on Firefox */
        -webkit-appearance: none;
        /* Remove default arrow icon on Chrome and Safari */
        -moz-appearance: none;
        /* Remove default arrow icon on older versions of Firefox */
    }
</style>
@section('breadcrumbs')
<li class="breadcrumb-item active"> @lang('Design Items') </li>
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
                            <h4 class="modal-title">Add design item</h4>
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
                            <h4 class="modal-title">Edit design item</h4>
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
                        <button type="button" class="btn btn-primary" onclick="add_idea()">  <i class="fas fa-plus"></i> Add Design Item</button>
                    </div>
                </div>
            </div>
            <div class="pt-3">
                <table class="table table-striped table-borderless" id="data-table-default">
                    <thead>
                        <tr>
                            <th style="width:5%">ID</th>
                            <!-- <th style="width:7%">NUMBER SIDE</th> -->
                            <th style="width:10%">Title</th>
                            <th style="width:15%">Category ID</th>
                            <th style="width:80%">Design</th>
                            <th style="width:5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($designitems as $designitem)
                        <tr>
                            <td>
                                {{$designitem->id}}
                            </td>
                            <!-- <td>
                                <select class="form-control custom-select-status btn btn-sm btn-rounded btn-primary" name="number_side_add" id="number_side_add" onchange="changeNumberDesignItem('{{$designitem->id}}',event)">
                                    <option value="">ety</option>
                                    <option value="1" {{$designitem->number_side==1?'selected':''}}>1</option>
                                    <option value="2" {{$designitem->number_side==2?'selected':''}}>2</option>
                                </select>
                            </td> -->
                            <td>
                                    {{$designitem->title}}
                            </td>
                            <td>
                                @php
                                $categoryDesigns = $designitem->categoryDesignItems->pluck('category_id')->toArray();
                                @endphp
                                @forEach($categories as $category)
                                <div class="m-2">
                                    <input type="checkbox" id="chooseCategory{{ $category->id }}" name="chooseCategory[]"
                                        value="{{ $category->id }}" @php if(in_array($category->id, $categoryDesigns)) echo 'checked';
                                    @endphp onchange="checkCategory(event,'{{$category->id}}','{{$designitem->id}}')" ? <label
                                        for="chooseCategory{{ $category->id }}">{{ $category->name }}</label>
                                </div>
                                @endforeach
                            </td>
                            <td class="row">
                                @if($designitem->front_design)
                                <figure class="col-3 position-relative">
                                    <a href="{{$designitem->front_design}}" target="_blank">
                                        <img src="{{$designitem->front_design}}" class="img-thumbnail" alt="Product Image" style="height: 240px;width: 200px;object-fit: contain;"> 
                                    </a>
                                    @if($role=='Admin')
                                    <button type="button" style="margin-right: 1.2em!important;" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeImage('{{$designitem->id}}','{{$designitem->front_design}}','front_design')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </figure>
                                @else
                                <div class="col-3">
                                    <label for="size_chart_add">Front</label>
                                    <input class="form-control" type="file" name="size_chart_add" id="size_chart_add" value="" onchange="handleFileChangeShow(event,'front_design','{{$designitem->id}}')">
                                </div>
                                @endif

                                @if($designitem->back_design)
                                <figure class="col-3 position-relative">
                                    <a href="{{$designitem->back_design}}" target="_blank">
                                        <img src="{{$designitem->back_design}}" class="img-thumbnail" alt="Product Image" style="height: 240px;width: 200px;object-fit: contain;">
                                    </a>
                                    @if($role=='Admin')
                                    <button type="button" style="margin-right: 1.2em!important;" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeImage('{{$designitem->id}}','{{$designitem->back_design}}','back_design')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </figure>
                                @else
                                <div class="col-3">
                                    <label for="size_chart_add">Back</label>
                                    <input class="form-control" type="file" name="size_chart_add" id="size_chart_add" value="" onchange="handleFileChangeShow(event,'back_design','{{$designitem->id}}')">
                                </div>
                                @endif

                                @if($designitem->sleeve_left_design)
                                <figure class="col-3 position-relative">
                                    <a href="{{$designitem->sleeve_left_design}}" target="_blank">
                                        <img src="{{$designitem->sleeve_left_design}}" class="img-thumbnail" alt="Product Image" style="height: 240px;width: 200px;object-fit: contain;">
                                    </a>
                                    @if($role=='Admin')

                                    <button type="button" style="margin-right: 1.2em!important;" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeImage('{{$designitem->id}}','{{$designitem->sleeve_left_design}}','sleeve_left_design')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </figure>
                                @else
                                <div class="col-3">
                                    <label for="size_chart_add">Sleeve Left</label>
                                    <input class="form-control" type="file" name="size_chart_add" id="size_chart_add" value="" onchange="handleFileChangeShow(event,'sleeve_left_design','{{$designitem->id}}')">
                                </div>
                                @endif

                                @if($designitem->sleeve_right_design)
                                <figure class="col-3 position-relative">
                                    <a href="{{$designitem->sleeve_right_design}}" target="_blank">
                                        <img src="{{$designitem->sleeve_right_design}}" class="img-thumbnail" alt="Product Image" style="height: 240px;width: 200px;object-fit: contain;">
                                    </a>
                                    @if($role=='Admin')
                                    <button type="button" style="margin-right: 1.2em!important;" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeImage('{{$designitem->id}}','{{$designitem->sleeve_right_design}}','sleeve_right_design')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </figure>
                                @else
                                <div class="col-3">
                                    <label for="size_chart_add">Sleeve Right</label>
                                    <input class="form-control" type="file" name="size_chart_add" id="size_chart_add" value="" onchange="handleFileChangeShow(event,'sleeve_right_design','{{$designitem->id}}')">
                                </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{route('designItems.cenvert', $designitem->id)}}" target="_blank">
                                    <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                        <i class="fa fa-download"></i>
                                    </button>
                                </a>
                                <div onclick="edit_idea('{{$designitem->id}}')">
                                            <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </div>
                                @if($role=='Admin')

                                <a href="{{route('designItems.delete', $designitem->id)}}">
                                    <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
            <div class="pagination-links">
                {{$designitems->appends($_GET)->links()}}
            </div>
        </div>
    </div>
</div>


@section('scripts')
<script>
    function edit_idea(id) {
        $.get('./design-items/edit/' + id, function(data) {
            $("#body_edit").html(data);
        });
        $('#editIdea').modal('show');
    }

    function add_idea() {
        $.get('./design-items/add', function(data) {
            $("#body_add").html(data);
        });
        $('#addIdea').modal('show');
    }

    function edit(id) {
        // alert("a");
        var title = $('input[name="title"]').val();
        $.ajax({
            url: `./design-items/edit/${id}`,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                title: title,
            },
            success: function(response) {
                if (JSON.parse(response).message) {
                    location.reload();
                }
            },
        });
    }

    function add() {
        // var design_add = $('select[name="design_add"]').val();
        var title = $('input[name="title"]').val();

        var listCategory = [];
        var category_add = $('input[id="chooseCategory[]"]');

        category_add.each(function() {
            if (this.checked) {
                listCategory.push($(this).val());
            }
        });

        console.log(listCategory);
        var show_front = $('#show_front').attr('src');
        var show_back = $('#show_back').attr('src');
        var show_sleeve_left = $('#show_sleeve_left').attr('src');
        var show_sleeve_right = $('#show_sleeve_right').attr('src');

        var formData = new FormData();
        formData.append('title', title)
        formData.append('list_category', listCategory)
        formData.append('front_design', show_front)
        formData.append('back_design', show_back)
        formData.append('sleeve_left_design', show_sleeve_left)
        formData.append('sleeve_right_design', show_sleeve_right)
        // console.log(design_add);
        console.log(category_add);
        console.log(show_front);
        console.log(show_back);
        console.log(show_sleeve_left);
        console.log(show_sleeve_right);

        $.ajax({
            url: './design-items/add',
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

    function handleFileChange(event, side) {
        const file = event.target.files[0];
        if (file) {
            console.log('Selected file:', file.name);
            // Bạn có thể thực hiện các hành động khác với file tại đây
            var formData = new FormData();

            formData.append('_token', "{{ csrf_token() }}");
            formData.append('side', side);
            formData.append('file', file);

            $.ajax({
                url: '{{route("designItems.upload")}}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Handle the response, e.g., reload the page
                    if (JSON.parse(response).message) {
                        $("#show_" + side).attr("src", JSON.parse(response).data);
                        $("#link_show_" + side).attr("href", JSON.parse(response).data);
                    }
                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
    }

    function handleFileChangeShow(event, side, design_item_id) {
        const file = event.target.files[0];
        if (file) {
            console.log('Selected file:', file.name);
            // Bạn có thể thực hiện các hành động khác với file tại đây
            var formData = new FormData();

            formData.append('_token', "{{ csrf_token() }}");
            formData.append('side', side);
            formData.append('file', file);
            formData.append('design_item_id', design_item_id);

            $.ajax({
                url: '{{route("designItems.uploadShow")}}',
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
    }

    function checkCategory(event, category_id, design_item_id) {
        console.log(event.target.checked);
        console.log(category_id);
        console.log(design_item_id);
        const check = event.target.checked;
        var formData = new FormData();

        formData.append('_token', "{{ csrf_token() }}");
        formData.append('check', check);
        formData.append('category_id', category_id);
        formData.append('design_item_id', design_item_id);
        $.ajax({
            url: './design-items/edit-category/' + design_item_id,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Handle the response, e.g., reload the page
                if (response.message) {
                    alert("Updated category done.")
                }
            },
            error: function(response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    }

    function removeImage(design_item_id, file_name, side) {
        var formData = new FormData();

        formData.append('_token', "{{ csrf_token() }}");
        formData.append('design_item_id', design_item_id);
        formData.append('file_name', file_name);
        formData.append('side', side);

        $.ajax({
            url: '{{route("designItems.deleteImage")}}',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Handle the response, e.g., reload the page
                if (response.message) {
                    location.reload();
                }
            },
            error: function(response) {
                // Handle the error, e.g., show an alert
                alert(response.responseJSON.message);
            }
        });
    }
    function changeNumberDesignItem(design_item_id, event) {
        const valueselect = event.target.value;
        console.log(valueselect);
        var formData = new FormData();

        formData.append('_token', "{{ csrf_token() }}");
        formData.append('design_item_id', design_item_id);
        formData.append('number_side', valueselect);

        $.ajax({
            url: '{{route("designItems.changeNumberDesignItem")}}',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Handle the response, e.g., reload the page
                if (response.message) {
                    alert("update number side success");
                }
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