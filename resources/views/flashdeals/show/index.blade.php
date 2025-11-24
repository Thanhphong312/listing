@extends('layouts.app')

@section('page-title', __('Flashdeals'))
@section('page-heading', __('Flashdeals(' . $store_name . ')'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Flashdeals')
</li>
@stop
@section('styles')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('bower_components/datetimepicker-master/jquery.datetimepicker.css')}}">
    <link rel="stylesheet prefetch"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
    <link rel="stylesheet" href="{{ asset('bower_components/datetimepicker-master/build/jquery.datetimepicker.min.css')}}">

@endsection
@section('content')
<div class="element-box">
    <div class="card">
        <div class="card-body">

            @include('partials.messages')
            <div class="modal fade" id="show_all_product">
                <div class="modal-dialog modal-lg" style="max-width: 1300px!important;">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title"><span id="name_show_flashdeal"></span></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="alert alert-danger" style="display:none"></div>
                            <div class="element-box">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-2" style="display: flex;align-items: center;">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="remote_id"
                                                        name="remote_id" placeholder="Remote id">
                                                </div>
                                            </div>
                                            <div class="col-2" style="display: flex;align-items: center;">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        placeholder="Name">
                                                </div>
                                            </div>
                                            <div class="col-2" style="display: flex;align-items: center;">
                                                <div class="form-group">

                                                    <button type="submit"
                                                        class="btn btn-primary form-control btn-rounded"
                                                        onclick="showStore()">Filters</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2" style="display: flex;align-items: center;">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="discount"
                                                        name="discount" placeholder="discount">
                                                </div>
                                            </div>
                                            <div class="col-2" style="display: flex;align-items: center;">
                                                <div class="form-group">

                                                    <button type="submit"
                                                        class="btn btn-primary form-control btn-rounded"
                                                        onclick="setup()">set</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body clearfix mt-3" id="shortlink">
                                            <table class="table table-striped table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <input type="checkbox" id="checkboxorders" checked
                                                                onclick="checkboxproducts(this)">
                                                        </th>
                                                        <th>STT</th>
                                                        <th>ID</th>
                                                        <th style="min-width: 250px">
                                                            REMOTE ID
                                                        </th>
                                                        <th>NAME</th>
                                                        <th style="min-width: 150px">NUMBER SKU</th>
                                                        <th style="min-width: 150px">Discounnt</th>
                                                        <th style="min-width: 50px">FLASHDEAL</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="product-list">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <ul class="pagination mt-3">

                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal fade" id="addStore">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Add flashdeal <span id="messagefld"></span></h4>
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
            <div class="modal fade" id="show_flashdeal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title"><span id="name_show_flashdeal"></span></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="alert alert-danger" style="display:none"></div>
                            <form id="body_show_flashdeal" class="form-horizontal" enctype="multipart/form-data">
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div id="timezonenow">

            </div>
            <div class="m-1">
                <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary mt-2" onclick="add_flashdeal()">Add
                            flashdeal</button>
                        <button type="button" class="btn btn-success mt-2" onclick="sync_flashdeal('{{$store_id}}')"
                            {{$producttiktok == 0 || $syncfld == 1 ? "disabled" : ""}}>Sync
                            flashdeal</button>
                        <button type="button" class="btn btn-success mt-2" id="sync_all_product"
                            onclick="sync_all_product('{{$store_id}}')">Sync
                            all product</button>
                        <button class="btn btn-primary mt-2" onclick="getproduct(1)">get product</button>

                    </div>
                    <div class="col-8">
                        <form id="filter-form">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-control" id="statusfld" name="statusfld">
                                            <option value="">Status Fld</option>
                                            @foreach(listStatusFld() as $orderStatus)
                                                <option value="{{$orderStatus}}" {{ $request->statusfld == $orderStatus ? 'selected' : '' }}>
                                                    {{$orderStatus}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-control" id="autorenew" name="autorenew">
                                            <option value="">Auto renew</option>
                                            <option value="1" {{ $request->autorenew == '1' ? 'selected' : '' }}>On
                                            </option>
                                            <option value="0" {{ $request->autorenew == '0' ? 'selected' : '' }}>Off
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-control" id="renewed" name="renewed">
                                            <option value="">Renewed</option>
                                            <option value="1" {{ $request->renewed == '1' ? 'selected' : '' }}>On</option>
                                            <option value="0" {{ $request->renewed == '0' ? 'selected' : '' }}>Off
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 row">
                                    <div class="col-md-12">
                                        <button type="submit"
                                            class="btn btn-primary form-control btn-rounded">Filters</button>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <textarea class="form-control" id="exampleFormControlTextarea1"
                                            rows="4">{{$message . "|"}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="pt-3 table-responsive">
                <table class="table table-striped table-borderless" id="data-table-default">
                    <thead>
                        <tr>
                            <th style="width:5%">ID</th>
                            <th style="width:5%">STORE ID</th>
                            <th style="width:10%">activity ID</th>
                            <th style="min-width:400px">promotion name</th>
                            <th style="width:5%">product</th>
                            <th style="width:10%">activity type</th>
                            <th style="width:10%">product level</th>
                            <th style="width:15%">status fld</th>
                            <th style="min-width: 110px;">begin time</th>
                            <th style="min-width: 110px;">end time</th>
                            <th style="width:10%">auto rewnew</th>
                            <th style="width:10%">rewnew</th>
                            <th style="min-width: 300px;">note</th>
                            <th style="width:5%">ACTION</th>
                        </tr>
                    </thead>
                    <!-- 'store_id', 'promotion_name', 'activity_type', 'product_level', 'status_fld', 'begin_time', 'end_time', 'auto', 'status' -->
                    <tbody class="flashdeal-list">
                        @foreach($flashdeals as $flashdeal)
                            <tr data-id="{{$flashdeal->id}}" id="flashdeal_{{$flashdeal->id}}">
                                <td>{{$flashdeal->id}}</td>
                                <td>{{$flashdeal->store_id}}</td>
                                <td>{{$flashdeal->activity_id}}</td>
                                <td>{{$flashdeal->promotion_name}}</td>
                                <td>
                                    <span>
                                        {{ $flashdeal->getFldSuccess() }}/{{ $flashdeal->productflashdeal->count() }}
                                    </span>
                                </td>

                                <td>{{$flashdeal->activity_type}}</td>
                                <td>{{$flashdeal->product_level}}</td>
                                <td>
                                    @if($flashdeal->status_fld != "ONGOING")
                                        <div class="btn btn-sm {{getbtnfld($flashdeal->status_fld)}}" disabled>
                                            {{$flashdeal->status_fld}}
                                        </div>`
                                    @else
                                        <select onchange="deactiveFld(this, '{{$flashdeal->activity_id}}')"
                                            name="fld_deactive_{{$flashdeal->id}}"
                                            class="btn btn-sm {{getbtnfld($flashdeal->status_fld)}} btn-rounded"
                                            id="fld_deactive_{{$flashdeal->id}}">
                                            <option class="btn btn-sm btn-success btn-rounded p-1" value="ONGOING"
                                                data-color="btn-success" {{$flashdeal->status_fld == 'ONGOING' ? 'selected' : ''}}>ONGOING</option>
                                            <option class="btn btn-sm btn-danger btn-rounded p-1" value="DEACTIVATED"
                                                data-color="btn-danger" {{$flashdeal->status_fld == 'DEACTIVATED' ? 'selected' : ''}}>DEACTIVATED</option>
                                        </select>
                                    @endif

                                </td>
                                <td>{{\Carbon\Carbon::createFromTimestamp($flashdeal->begin_time)}}</td>
                                <td>{{\Carbon\Carbon::createFromTimestamp($flashdeal->end_time)}}</td>
                                <td>
                                    <select onchange="changeStatusFld(this, '{{$flashdeal->id}}')"
                                        name="fld_status_{{$flashdeal->id}}" id="fld_status_{{$flashdeal->id}}"
                                        class="custom-select-status btn {{$flashdeal->auto == 1 ? 'btn-success' : 'btn-danger'}} btn-sm btn-rounded">
                                        <option class="btn btn-sm btn-success btn-rounded p-1" value="1"
                                            data-color="btn-success" {{$flashdeal->auto == 1 ? 'selected' : ''}}>ON</option>
                                        <option class="btn btn-sm btn-danger btn-rounded p-1" value="0"
                                            data-color="btn-danger" {{$flashdeal->auto == 0 ? 'selected' : ''}}>OFF</option>
                                    </select>
                                </td>
                                <td>
                                    <select onchange="changeRenewFld(this, '{{$flashdeal->id}}')"
                                        name="fld_status_{{$flashdeal->id}}" id="fld_renew_{{$flashdeal->id}}"
                                        class="custom-select-status btn {{$flashdeal->renew == 1 ? 'btn-success' : 'btn-danger'}} btn-sm btn-rounded">
                                        <option class="btn btn-sm btn-success btn-rounded p-1" value="1"
                                            data-color="btn-success" {{$flashdeal->renew == 1 ? 'selected' : ''}}>ON</option>
                                        <option class="btn btn-sm btn-danger btn-rounded p-1" value="0"
                                            data-color="btn-danger" {{$flashdeal->renew == 0 ? 'selected' : ''}}>OFF</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="form-control" id="exampleFormControlTextarea1"
                                        rows="4">{{$flashdeal->message}}</textarea>

                                </td>
                                <td>
                                    <a href="../show/{{$flashdeal->activity_id}}?store_id={{$store_id}}" target="_blank">
                                        <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
            <div class="pagination-links">
                @if (count($flashdeals))
                    {{$flashdeals->appends($_GET)->links()}}
                @endif
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.40/moment-timezone.min.js"></script>

    <script>
        function updateTime() {
            var now = new Date(); // Lấy giờ hiện tại
            var timeOptions = { timeZone: 'America/New_York', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            var dateOptions = { timeZone: 'America/New_York', year: 'numeric', month: '2-digit', day: '2-digit' };

            // Lấy giờ và ngày theo múi giờ America/New_York
            var timeString = now.toLocaleTimeString('en-US', timeOptions);
            var dateString = now.toLocaleDateString('en-US', dateOptions);

            // Hiển thị thời gian và ngày tháng năm trong div với id="timezonenow"
            document.getElementById('timezonenow').innerHTML = dateString + ' ' + timeString;
            document.getElementById('timezonenowfaddflashdeal').innerHTML = dateString + ' ' + timeString;
        }

        // Cập nhật giờ mỗi giây
        setInterval(updateTime, 1000);

        // Gọi hàm ngay khi trang load
        updateTime();

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

    <script>
        function showStore() {
            var name = $("#name").val();
            var remote_id = $("#remote_id").val();
            $.get('../get-all-product-tiktok?store_id={{$store_id}}&name=' + name + '&remote_id=' + remote_id + '&page=1', function (data) {
                // Make sure data.data is an array before proceeding
                if (Array.isArray(data.data.data)) {
                    console.log(data.data.data);

                    // Clear the existing product list
                    $(".product-list").html("");

                    // Loop through the fetched products and append them to the table
                    stt = 1;
                    data.data.data.forEach(function (product) {
                        let skusCount = JSON.parse(product.skus).length; // Count the number of SKUs
                        let productRow = '';
                        if (product.is_flashdeal) {
                            productRow = `
                                        <tr data-id="${product.id}" id="ajaxorder_${product.id}" data-total="${skusCount}" >
                                            <td><input  class="chooseproductfld" data-id="${product.remote_id}" data-numsku="${skusCount}" type="checkbox" id="product_${product.id}" onchange="checkproduct(this, '${product.remote_id}')"  checked ></td>
                                            <td>${stt}</td>
                                            <td>${product.id}</td>
                                            <td>${product.remote_id}</td>
                                            <td>${product.title}</td>
                                            <td>${skusCount}</td>
                                            <td><input class="form-control" onblur="changeDiscount(${product.id},this)" type="text" value="${product.discount}"></input></td>
                                            <td>True</td>
                                        </tr>
                                    `;
                        } else {
                            productRow = `
                                        <tr data-id="${product.id}" id="ajaxorder_${product.id}" data-total="${skusCount}" style="background-color: #e9ff73;">
                                            <td><input  class="chooseproductfld" data-id="${product.remote_id}" data-numsku="${skusCount}" type="checkbox" id="product_${product.id}" onchange="checkproduct(this, '${product.remote_id}')"  checked ></td>
                                            <td>${stt}</td>
                                            <td>${product.id}</td>
                                            <td>${product.remote_id}</td>
                                            <td>${product.title}</td>
                                            <td>${skusCount}</td>
                                            <td><input class="form-control" onblur="changeDiscount(${product.id},this)" type="text" value="${product.discount}"></input></td>
                                            <td>False</td>
                                        </tr>
                                    `;
                        }
                        stt++;
                        // Append each product row to the product list
                        $(".product-list").append(productRow);
                    });

                    // Create pagination based on the total data
                    createPagination(data.total, data.data.current_page, data.data.per_page);
                } else {
                    console.error("Expected an array for data.data.data but got:", data.data);
                }
            });

            // Show the modal
            $('#show_all_product').modal('show');
        }
        function setup() {
            let checkboxes = document.querySelectorAll('td input[type="checkbox"][class="chooseproductfld"]');
            var discount = $("#discount").val();
            console.log(discount);
            var producttiktoklist = [];
            checkboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    // console.log(checkbox.getAttribute('id'));
                    const checkboxId = checkbox.getAttribute('id');
                    const number = checkboxId.match(/\d+/)[0]; // Tìm số trong chuỗi
                    console.log(number);
                    producttiktoklist.push(number);
                    $("#discount_" + number).val(discount);
                }
            });
            $.ajax({
                url: `../edit-all-product-tiktok`,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    producttiktoklist: producttiktoklist,
                    discount: discount,
                },
                success: function (response) {
                    alert("done");
                },
            });
        }
        function checkboxproducts(target) {
            let checkboxes = document.querySelectorAll('td input[type="checkbox"][class="chooseproductfld"]');

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = target.checked; // Cập nhật trạng thái checked
            });
        }
        // function edit_store(id) {
        //     $.get('./flashdeals/edit/' + id, function (data) {
        //         $("#body_edit").html(data);
        //     });
        //     $('#editStore').modal('show');
        // }
        function add_flashdeal() {
            $.get('../add?store_id={{$store_id}}', function (data) {
                $("#body_add").html(data);
                // $('#datefrom').datetimepicker({
                //     format: 'Y-m-d H:i', // Format theo kiểu YYYY-MM-DD HH:mm
                //     step: 30,
                //     onShow: function (ct) {
                //         var now = moment.tz('America/New_York'); // Lấy thời gian theo múi giờ America/New_York
                //         this.setOptions({
                //             value: now.format('YYYY-MM-DD HH:mm') // Hiển thị thời gian đúng theo múi giờ TikTok
                //         });
                //     }
                // });

                // $('#dateto').datetimepicker({
                //     format: 'Y-m-d H:i',
                //     step: 30,
                //     onShow: function (ct) {
                //         var now = moment.tz('America/New_York'); // Lấy thời gian theo múi giờ America/New_York
                //         this.setOptions({
                //             value: now.format('YYYY-MM-DD HH:mm') // Hiển thị thời gian đúng theo múi giờ TikTok
                //         });
                //     }
                // });
            });
            $('#addStore').modal('show');
        }
        function show_flashdeal(activity_id, promotion_name) {
            $.get('../show/' + activity_id + '?store_id={{$store_id}}', function (data) {
                $("#body_show_flashdeal").html(data);
                $("#name_show_flashdeal").text("Flashdeal " + promotion_name);
            });
            $('#show_flashdeal').modal('show');
        }
        // function getproduct() {
        //     $.get('../get-all-product?store_id={{$store_id}}', function (data) {
        //         $("#body_show_all_product").html(data);
        //     });
        //     $('#show_all_product').modal('show');
        // }
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
            var dateto = $('input[name="dateto"]').val();
            var store_add = '{{$store_id}}';
            var level_add = $('select[name="level_add"]').val();
            var activity_add = $('select[name="activity_add"]').val();

            var formData = new FormData();
            formData.append('name_add', name_add)
            formData.append('datefrom', datefrom)
            formData.append('dateto', dateto)
            formData.append('store_add', store_add)
            formData.append('level_add', level_add)
            formData.append('activity_add', activity_add)

            $.ajax({
                url: '../add',
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
                    const res = response;
                    alert(JSON.parse(res.responseText).message);

                    // alert(JSON.parse(response).message);
                }
            });
        }
        function sync_flashdeal(id) {
            $.ajax({
                url: '../sync-flash-deal/' + id,
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
        function sync_all_product(id) {
            $.ajax({
                url: '../sync-all-product-store/' + id,
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
        function changeStatusFld(target, id) {
            const status = target.value;
            console.log(status);
            $.ajax({
                url: '../change-status-fld/' + id,
                method: 'GET',
                data: {
                    status: status,
                },
                success: function (response) {
                    // Handle the response, e.g., reload the page
                    if (JSON.parse(response).message) {
                        alert("change success");
                        // location.reload();
                    }
                },
                error: function (response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
        function changeRenewFld(target, id) {
            const status = target.value;
            console.log(status);
            $.ajax({
                url: '../change-renew-fld/' + id,
                method: 'GET',
                data: {
                    status: status,
                },
                success: function (response) {
                    // Handle the response, e.g., reload the page
                    if (JSON.parse(response).message) {
                        alert("change success");
                        // location.reload();
                    }
                },
                error: function (response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
        function deactiveFld(target, id) {
            const status = target.value;
            console.log(status);
            console.log(id);
            $.ajax({
                url: '../deactiveflashdeal/' + id,
                method: 'GET',
                data: {
                    store_id: {{$store_id}},
                },
                success: function (response) {
                    // Handle the response, e.g., reload the page
                    if (JSON.parse(response).message) {
                        alert("change success");
                        // location.reload();
                    }
                },
                error: function (response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
        numberproduct();
        function numberproduct() {

            $.ajax({
                url: '../count-product-shop/{{$store_id}}',
                method: 'GET',
                success: function (response) {
                    console.log(response);
                    $("#sync_all_product").text("Sync all product(" + JSON.parse(response).total + ")");
                },
                error: function (response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
        function createPagination(totalItems, currentPage = 1, itemsPerPage = 10) {
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const paginationContainer = document.querySelector('.pagination');
            paginationContainer.innerHTML = ''; // Clear existing pagination

            // Get current query parameters and remove existing page parameter if any
            const queryString = new URLSearchParams(window.location.search);
            queryString.delete('page');

            const createPageItem = (page, isActive = false, isDisabled = false, isEllipsis = false) => {
                const li = document.createElement('li');
                li.classList.add('page-item');
                if (isActive) li.classList.add('active');
                if (isDisabled) li.classList.add('disabled');

                const a = document.createElement('a');
                a.classList.add('page-link');
                if (isEllipsis) {
                    a.innerText = '...';
                } else {
                    a.innerText = page;
                    queryString.set('page', page);
                    a.onclick = function () {
                        getproduct(page);
                    };
                }

                li.appendChild(a);
                return li;
            };

            // Previous button
            const prevPage = currentPage - 1;
            paginationContainer.appendChild(createPageItem('«', false, prevPage < 1, false));

            // Page numbers
            if (totalPages <= 10) {
                for (let i = 1; i <= totalPages; i++) {
                    paginationContainer.appendChild(createPageItem(i, i === currentPage));
                }
            } else {
                if (currentPage <= 6) {
                    for (let i = 1; i <= 8; i++) {
                        paginationContainer.appendChild(createPageItem(i, i === currentPage));
                    }
                    paginationContainer.appendChild(createPageItem('...', false, true, true));
                    paginationContainer.appendChild(createPageItem(totalPages));
                } else if (currentPage >= totalPages - 5) {
                    paginationContainer.appendChild(createPageItem(1));
                    paginationContainer.appendChild(createPageItem('...', false, true, true));
                    for (let i = totalPages - 7; i <= totalPages; i++) {
                        paginationContainer.appendChild(createPageItem(i, i === currentPage));
                    }
                } else {
                    paginationContainer.appendChild(createPageItem(1));
                    paginationContainer.appendChild(createPageItem('...', false, true, true));
                    for (let i = currentPage - 2; i <= currentPage + 2; i++) {
                        paginationContainer.appendChild(createPageItem(i, i === currentPage));
                    }
                    paginationContainer.appendChild(createPageItem('...', false, true, true));
                    paginationContainer.appendChild(createPageItem(totalPages));
                }
            }

            // Next button
            const nextPage = currentPage + 1;
            paginationContainer.appendChild(createPageItem('»', false, nextPage > totalPages, false));
        }
        function getproduct(page = 1) {
            var name = $("#name").val();
            var remote_id = $("#remote_id").val();
            $.get('../get-all-product-tiktok?store_id={{$store_id}}&name=' + name + '&remote_id=' + remote_id + '&page=' + page, function (data) {
                // Make sure data.data is an array before proceeding
                if (Array.isArray(data.data.data)) {
                    console.log(data.data.data);

                    // Clear the existing product list
                    $(".product-list").html("");

                    // Loop through the fetched products and append them to the table
                    stt = 1;
                    data.data.data.forEach(function (product) {
                        let skusCount = JSON.parse(product.skus).length; // Count the number of SKUs
                        let productRow = '';
                        if (product.is_flashdeal) {
                            productRow = `
                                        <tr data-id="${product.id}" id="ajaxorder_${product.id}" data-total="${skusCount}" >
                                            <td><input  class="chooseproductfld" data-id="${product.remote_id}" data-numsku="${skusCount}" type="checkbox" id="product_${product.id}" onchange="checkproduct(this, '${product.remote_id}')"  checked ></td>
                                            <td>${stt}</td>
                                            <td>${product.id}</td>
                                            <td>${product.remote_id}</td>
                                            <td>${product.title}</td>
                                            <td>${skusCount}</td>
                                            <td><input class="form-control" id="discount_${product.id}" onblur="changeDiscount(${product.id},this)" type="text" value="${product.discount}"></input></td>
                                            <td>True</td>
                                        </tr>
                                    `;
                        } else {
                            productRow = `
                                        <tr data-id="${product.id}" id="ajaxorder_${product.id}" data-total="${skusCount}" style="background-color: #e9ff73;">
                                            <td><input  class="chooseproductfld" data-id="${product.remote_id}" data-numsku="${skusCount}" type="checkbox" id="product_${product.id}" onchange="checkproduct(this, '${product.remote_id}')"  checked ></td>
                                            <td>${stt}</td>
                                            <td>${product.id}</td>
                                            <td>${product.remote_id}</td>
                                            <td>${product.title}</td>
                                            <td>${skusCount}</td>
                                            <td><input class="form-control" id="discount_${product.id}" onblur="changeDiscount(${product.id},this)" type="text" value="${product.discount}"></input></td>
                                            <td>False</td>
                                        </tr>
                                    `;
                        }
                        stt++;
                        // Append each product row to the product list
                        $(".product-list").append(productRow);
                    });

                    // Create pagination based on the total data
                    createPagination(data.total, data.data.current_page, data.data.per_page);
                } else {
                    console.error("Expected an array for data.data.data but got:", data.data);
                }
            });

            // Show the modal
            $('#show_all_product').modal('show');
        }
        function changeDiscount(id, target) {
            discount = $(target).val();
            console.log(discount);
            $.ajax({
                url: '../changediscountproduct',
                method: 'GET',
                data: {
                    id: id,
                    discount: discount,
                },
                success: function (response) {
                    // Handle the response, e.g., reload the page
                    if (JSON.parse(response).message) {
                        alert(done)
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