@extends('layouts.app')

@section('page-title', __('Designs'))
@section('page-heading', __('Designs'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Designs')
</li>
@stop

@section('content')

@include('partials.messages')

<div class="modal fade" id="addDesignFile">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add file</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                <form id="body_add_file" class="form-horizontal" enctype="multipart/form-data">
                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="addDesignUrl">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add url</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                <form id="body_add_url" class="form-horizontal" enctype="multipart/form-data">
                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="editDesign">
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
<div class="modal fade" id="showImage">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                <form id="body_showImage" class="form-horizontal" enctype="multipart/form-data">

                </form>
            </div>

        </div>
    </div>
</div>

<div class="row g-3">
    <form method="GET">
        <div class="col-auto flex-grow-1 overflow-auto">
            <div class="btn-group col-1">
                <div class="btn-group position-static">
                    <input name="id" value="{{$request->id}}" class="form-control px-2" type="text" placeholder="Id...">
                </div>
            </div>

            @if ($role != 'Staff')
                <div class="btn-group col-1">
                    <div class="form-group col-12">
                        <select class="form-control select2" id="staff_id" name="staff_id">
                            <option value="">Staff...</option>
                            @foreach ($listStaff as $staff)
                                <option value="{{ $staff['id'] }}" {{ $request->staff_id == $staff['id'] ? 'selected' : '' }}>
                                    {{ $staff['username'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if ($role != 'Seller')
                    <div class="btn-group col-1">
                        <div class="form-group col-12">
                            <select class="form-control select2" id="seller_id" name="seller_id">
                                <option value="">Seller...</option>
                                @foreach ($listSeller as $staff)
                                    <option value="{{ $staff['id'] }}" {{ $request->seller_id == $staff['id'] ? 'selected' : '' }}>
                                        {{ $staff['username'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
            @endif
            <div class="btn-group col-1">
                <div class="btn-group position-static">
                    <input name="tag" value="{{$request->tag}}" class="form-control px-2" type="text"
                        placeholder="Tag...">
                </div>
            </div>
            <div class="btn-group col-2">
                <div class="btn-group position-static">
                    <input name="created_at" value="{{$request->created_at}}" class="form-control px-2" type="date">
                </div>
            </div>
            <div class="btn-group col-2">
                <div class="btn-group position-static">
                    <button type="submit" class="btn btn-primary px-4 raised d-flex gap-2"><i
                            class="fa-solid fa-magnifying-glass"></i> Filter</button>
                </div>
                <div class="btn-group position-static">
                    <a type="button" class="btn btn-dark px-2" href="{{route('designs.index')}}">Clear</a>
                </div>
            </div>
        </div>
    </form>
    <div class="col-auto">
        <div class="d-flex align-items-center gap-2 justify-content-lg-end">
            <button class="btn btn-primary px-4 mr-2" onclick="add_design_file()"><i class="fa-solid fa-plus"></i> Add
                file</button>
            <button class="btn btn-primary px-4 mr-2" onclick="add_design_url()"><i class="fa-solid fa-plus"></i> Add
                url</button>

        </div>
    </div>
</div>

<div class="pt-3">
    <table class="table table-striped table-borderless">
        <thead>
            <tr>

                <th>
                    <!-- <input class="form-check-input" type="checkbox" onclick="check_all(this)"> -->
                </th>

                <th>ID</th>
                <th style="min-width: 150px;">Product</th>
                <th style="min-width: 150px;">Image</th>
                <th>Title</th>
                <th>User</th>
                <th>Niche</th>
                <th>Mix</th>
                <th>Sku</th>
                <th>Tag</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($designs as $design)
                    <tr>
                        <td>
                            <input name="design_id" data-id="{{$design->id}}" value="{{$design->id}}"
                                onchange="onchange_checkbox_export(event)" class="design_id" type="checkbox">
                        </td>
                        <td>{{$design->id}}</td>
                        <td>
                            <a href="../products?id=10819" target="_blank" rel="noopener noreferrer">
                                @if($design->product_listing)
                                    <span class="badge bg-success-jade" style="color: green;">Created
                                        @if($design->product_listing)
                                            <a href="../products?id={{$design->product_listing}}" target="_blank" rel="noopener noreferrer">
                                                <i class="fa fa-external-link"></i>
                                            </a>
                                        @endif
                                    </span>
                                @endif
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-box">
                                    <img src="{{$design->thumbnail}}" width="150" class="rounded-3" alt="image design" style="background-color: #c3c3c3;">
                                    <figcaption class="text-center mt-1"> <span class="btn btn-sm btn-dark"
                                            onclick="viewImage({{$design->id}})">view</span> </figcaption>
                                </div>
                            </div>
                        </td>

                        <td>{{ $design->title }}</td>
                        <td>{{ $design?->user?->username }}</td>
                        <td>{{ $design->niche }}</td>
                        <td>{{ $design->mix }}</td>
                        <td>{{ $design->sku }}</td>


                        <td>
                            @if ($design->tag)
                                            @php
                                                $tags = $design->tag;
                                                $tags = (explode(",", $tags));
                                            @endphp
                                            <div class="product-tags">
                                                @foreach ($tags as $tag)

                                                    <a href="j avascript:;" class="btn-tags">{{$tag}}</a>
                                                @endforeach
                                            </div>
                            @endif

                        </td>
                        <td>{{$design->created_at}}</td>

                        <td>
                            <a href="{{ route('designs.download_design', $design->id) }}">
                                <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                    <i class="fa fa-download"></i>
                                </button>
                            </a>
                            <div onclick="edit_idea({{$design->id}})">
                                <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </div>
                            @if($role == 'Admin' || $user->id == $design->user_id)
                                <a href="{{route('designs.delete', $design->id)}}">
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
    @if (count($designs))
        {{$designs->appends($_GET)->links()}}
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
        function check_all(target) {
            const checkboxallstore = document.querySelectorAll('input[class="design_id"]');

            checkboxallstore.forEach((e) => {
                e.checked = target.checked;
            })
        }
        function edit_idea(id) {
            $.get('./designs/edit/' + id, function (data) {
                $("#body_edit").html(data);
            });
            $('#editDesign').modal('show');
        }

        function add_design_file() {
            $.get('./designs/add', function (data) {
                $("#body_add_file").html(data);
            });
            $('#addDesignFile').modal('show');
        }

        function add_design_url() {
            $.get('./designs/addurl', function (data) {
                $("#body_add_url").html(data);
            });
            $('#addDesignUrl').modal('show');
        }

        function edit(id) {
            // alert("a");
            var title = $('input[name="title"]').val();
            var tag = $('input[name="tag"]').val();
            $.ajax({
                url: `./designs/edit/${id}`,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    title: title,
                    tag: tag,
                },
                success: function (response) {
                    if (JSON.parse(response).message) {
                        location.reload();
                    }
                },
            });
        }

        function add() {
            var title = $('input[name="title"]').val();
            var niche = $('select[name="niche"]').val();
            var mix = $('select[name="mix"]').val();
            var bl_and_wt = $('input[name="bl_and_wt"]').is(':checked');
            var tag = $('input[name="tag"]').val();

            // Get the file input elements (not the src attributes)
            var file_front = $('#file_front')[0].files[0];
            var file_back = $('#file_back')[0].files[0];
            var file_sleeve_left = $('#file_sleeve_left')[0].files[0];
            var file_sleeve_right = $('#file_sleeve_right')[0].files[0];
            var file_front_bl = $('#file_front_bl')[0].files[0];
            var file_back_bl = $('#file_back_bl')[0].files[0];
            var file_sleeve_left_bl = $('#file_sleeve_left_bl')[0].files[0];
            var file_sleeve_right_bl = $('#file_sleeve_right_bl')[0].files[0];
            var file_front_wt = $('#file_front_wt')[0].files[0];
            var file_back_wt = $('#file_back_wt')[0].files[0];
            var file_sleeve_left_wt = $('#file_sleeve_left_wt')[0].files[0];
            var file_sleeve_right_wt = $('#file_sleeve_right_wt')[0].files[0];

            var formData = new FormData();
            formData.append('title', title);
            formData.append('niche', niche);
            formData.append('mix', mix);
            formData.append('bl_and_wt', bl_and_wt);
            formData.append('tag', tag);

            // Append file inputs to FormData (only if the files exist)
            if (bl_and_wt) {
                if (file_front_bl) {
                    formData.append('front_design_bl', file_front_bl);
                }
                if (file_back_bl) {
                    formData.append('back_design_bl', file_back_bl);
                }
                if (file_sleeve_left_bl) {
                    formData.append('sleeve_left_design_bl', file_sleeve_left_bl);
                }
                if (file_sleeve_right_bl) {
                    formData.append('sleeve_right_design_bl', file_sleeve_right_bl);
                }
                if (file_front_wt) {
                    formData.append('front_design_wt', file_front_wt);
                }
                if (file_back_wt) {
                    formData.append('back_design_wt', file_back_wt);
                }
                if (file_sleeve_left_wt) {
                    formData.append('sleeve_left_design_wt', file_sleeve_left_wt);
                }
                if (file_sleeve_right_wt) {
                    formData.append('sleeve_right_design_wt', file_sleeve_right_wt);
                }
            } else {
                if (file_front) {
                    formData.append('front_design', file_front);
                }
                if (file_back) {
                    formData.append('back_design', file_back);
                }
                if (file_sleeve_left) {
                    formData.append('sleeve_left_design', file_sleeve_left);
                }
                if (file_sleeve_right) {
                    formData.append('sleeve_right_design', file_sleeve_right);
                }
            }
            formData.append('_token', "{{csrf_token()}}")

            $.ajax({
                url: './designs/add',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // Handle the response, e.g., reload the page
                    // if (JSON.parse(response).message) {
                    location.reload();
                    // }
                },
                error: function (response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
        function addurl() {

            var title = $('input[name="title"]').val();
            var niche = $('select[name="niche"]').val();
            var mix = $('select[name="mix"]').val();
            var bl_and_wt = $('input[name="bl_and_wt"]').is(':checked');
            var tag = $('input[name="tag"]').val();

            // Get the file input elements (not the src attributes)
            var file_front = $('#file_front').val();
            var file_back = $('#file_back').val();
            var file_sleeve_left = $('#file_sleeve_left').val();
            var file_sleeve_right = $('#file_sleeve_right').val();
            var file_front_bl = $('#file_front_bl').val();
            var file_back_bl = $('#file_back_bl').val();
            var file_sleeve_left_bl = $('#file_sleeve_left_bl').val();
            var file_sleeve_right_bl = $('#file_sleeve_right_bl').val();
            var file_front_wt = $('#file_front_wt').val();
            var file_back_wt = $('#file_back_wt').val();
            var file_sleeve_left_wt = $('#file_sleeve_left_wt').val();
            var file_sleeve_right_wt = $('#file_sleeve_right_wt').val();

            var formData = new FormData();
            formData.append('title', title);
            formData.append('niche', niche);
            formData.append('mix', mix);
            formData.append('bl_and_wt', bl_and_wt);
            formData.append('tag', tag);

            // Append file inputs to FormData (only if the files exist)
            if (bl_and_wt) {
                if (file_front_bl) {
                    formData.append('front_design_bl', file_front_bl);
                }
                if (file_back_bl) {
                    formData.append('back_design_bl', file_back_bl);
                }
                if (file_sleeve_left_bl) {
                    formData.append('sleeve_left_design_bl', file_sleeve_left_bl);
                }
                if (file_sleeve_right_bl) {
                    formData.append('sleeve_right_design_bl', file_sleeve_right_bl);
                }
                if (file_front_wt) {
                    formData.append('front_design_wt', file_front_wt);
                }
                if (file_back_wt) {
                    formData.append('back_design_wt', file_back_wt);
                }
                if (file_sleeve_left_wt) {
                    formData.append('sleeve_left_design_wt', file_sleeve_left_wt);
                }
                if (file_sleeve_right_wt) {
                    formData.append('sleeve_right_design_wt', file_sleeve_right_wt);
                }
            } else {
                if (file_front) {
                    formData.append('front_design', file_front);
                }
                if (file_back) {
                    formData.append('back_design', file_back);
                }
                if (file_sleeve_left) {
                    formData.append('sleeve_left_design', file_sleeve_left);
                }
                if (file_sleeve_right) {
                    formData.append('sleeve_right_design', file_sleeve_right);
                }
            }
            formData.append('_token', "{{csrf_token()}}")

            $.ajax({
                url: './designs/addurl',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // Handle the response, e.g., reload the page
                    // if (JSON.parse(response).message) {
                    location.reload();
                    // }
                },
                error: function (response) {
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
                    url: '{{route("designs.upload")}}',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        const parsedResponse = JSON.parse(response);
                        if (parsedResponse.message) {
                            $("#show_" + side).attr("src", parsedResponse.data);
                            $("#link_show_" + side).attr("href", parsedResponse.data);
                        }
                    },
                    error: function (response) {
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
                    url: '{{route("designs.uploadShow")}}',
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
                url: './designs/edit-category/' + design_item_id,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // Handle the response, e.g., reload the page
                    if (response.message) {
                        alert("Updated category done.")
                    }
                },
                error: function (response) {
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
                url: '{{route("designs.deleteImage")}}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // Handle the response, e.g., reload the page
                    if (response.message) {
                        location.reload();
                    }
                },
                error: function (response) {
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
                url: '{{route("designs.changeNumberDesignItem")}}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // Handle the response, e.g., reload the page
                    if (response.message) {
                        alert("update number side success");
                    }
                },
                error: function (response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }

        function import_design() {
            $('#import').modal('show');
        }

        function handleRadioChange(event, side) {
            let radio = $("input[name='bl_and_wt']").is(':checked');
            console.log(radio);
            let one_ds = document.getElementById('one_ds').classList;
            let two_ds = document.getElementById('two_ds').classList;
            if (radio) {
                one_ds.add('hide');
                two_ds.remove('hide');
            } else {
                one_ds.remove('hide');
                two_ds.add('hide');
            }
        }

        function viewImage(id) {
            $.ajax({
                url: './designs/list-image/' + id,
                method: 'GET',
                success: function (response) {
                    $("#body_showImage").html(response);
                },
                error: function (response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
            $('#showImage').modal('show');

        }
        var list_id = [];
        function removeElement(array, elem) {
            var index = array.indexOf(elem);
            if (index > -1) {
                array.splice(index, 1);
            }
        }
        function onchange_checkbox_export(event) {
            if (event.target.checked) {
                let id = event.target.value;
                list_id.push(id)
                console.log(list_id);
            } else {
                let id = event.target.value;
                removeElement(list_id, id)
                console.log(list_id);
            }
        } 
    </script>
    @stop

@endsection