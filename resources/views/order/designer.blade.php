@extends('layouts.app')

@section('page-title', __('Orders'))
@section('page-heading', __('Orders'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Orders')
</li>
@stop

@section('content')

<div id="ajaxCountOrderToday">

</div>
<!-- Modal Order detail -->
<div class="modal fade" id="orderdetailModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Order Detail</h4>
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
            <div class="modal-body">
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="supportModal" role="dialog">
    <div class="modal-dialog" style="max-width: 750;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Support</h4>
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
            <div class="support-modal-body">

            </div>
        </div>

    </div>
</div>
<!-- Modal timeline -->
<div class="modal fade" id="timelineModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Order Timeline</h4>
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
            <div class="modal-body">
            </div>
        </div>

    </div>
</div>
<!-- Modal timeline -->
<!-- Modal Edit Order -->
<div class="modal fade" id="editOrderModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Order</h4>
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" data-orderid="" class="btn btn-primary" id="btnAjaxUpdate">Submit</button>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="chooseOrderModal" role="dialog">
    <div class="modal-dialog" style="max-width: 750;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Choose Oversize Order</h4>
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" data-orderid="" class="btn btn-primary" id="update_order_overize" onclick="update_order_overize()">Submit</button>
            </div>
        </div>

    </div>
</div>
<div class="element-box">
    <div class="card">
        <div class="card-body">

            @include('partials.messages')
            <div class="panel-body clearfix mt-3">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=&paymentStatus=pending_payment"
                            id="ajaxPendingPaymentOrder">Pending Payment (0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=new_order"
                            id="ajaxNewOrder">New (0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=wrongsize"
                            id="ajaxWrongsizeOrder">Wrongsize(0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=fixed"
                            id="ajaxFixedOrder">Fixed(0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=printed"
                            id="ajaxPrintedOrder">Printed(0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=label_printed"
                            id="ajaxLabelPrintedOrder">Label Printed(0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=reprint"
                            id="ajaxReprintOrder">Reprint(0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=onhold"
                            id="ajaxOnholdOrder">Onhold(0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=pressed"
                            id="ajaxPressedOrder">Pressed(0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=overdue"
                            id="ajaxOverdieOrder">Overdue(0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=priority"
                            id="ajaxPriorityOrder">Priority(0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=oversize"
                            id="ajaxOversizeOrder">Oversize(0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=overprio"
                            id="ajaxOversize_priority">Oversize + priority(0)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=shipped"
                            id="ajaxShippedOrder">Shipped(0)</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                            href="/orders?order_id=&ref_id=&filterStock=&filterLabel=Label+Tracking&filterFulfill=test_order"
                            id="ajaxTestOrder">Test Order(0)</a>
                    </li>
                </ul>
            </div>
            <div class="">
                <!-- Filter Form -->
                <form id="filter-form">
                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <input type="text" class="form-control" id="order_id" name="order_id"
                                    placeholder="Order ID" value="{{$request->order_id}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="ref_id" name="ref_id"
                                    placeholder="Search by ref ID" value="{{$request->ref_id}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Search by name" value="{{$request->name}}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" id="filterfulfill" name="filterFulfill">
                                    <option value="">Fulfill Status</option>
                                    @foreach(orderStatuss() as $keyStatus => $orderStatus)
                                        <option value="{{$keyStatus}}" {{ $request->filterFulfill == $keyStatus ? 'selected' : '' }}>
                                            {{$orderStatus}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control" id="seller" name="seller">
                                        <option value="">Seller</option>
                                        @foreach(listSeller() as $seller)
                                            <option value="{{$seller['id']}}" {{ $request->seller == $seller['id'] ? 'selected' : '' }}>
                                                {{$seller['username']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1" style="width: 190px;">
                                <div class="form-group">
                                    <select class="form-control " id="quantity" name="quantity" >
                                        <option value="">Quantity</option>
                                            <option value="1" {{ $request->quantity == 1 ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ $request->quantity == 2 ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ $request->quantity == 3 ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ $request->quantity == 4 ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ $request->quantity == 5 ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ $request->quantity == 6 ? 'selected' : '' }}>6</option>
                                            <option value="7" {{ $request->quantity == 7 ? 'selected' : '' }}>7</option>
                                            <option value="8" {{ $request->quantity == 8 ? 'selected' : '' }}>8</option>
                                            <option value="9" {{ $request->quantity == 9 ? 'selected' : '' }}>9</option>
                                            <option value="10" {{ $request->quantity == 10 ? 'selected' : '' }}>10</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control" id="explodeseller" name="explodeseller">
                                        <option value="">Explode seller</option>
                                        @foreach(listSeller() as $seller)
                                            <option value="{{$seller['id']}}" {{ $request->explodeseller == $seller['id'] ? 'selected' : '' }}>
                                                {{$seller['username']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control" id="explodestatus" name="explodestatus">
                                        <option value="">Explode Status</option>
                                        @foreach(orderStatuss() as $keyStatus => $orderStatus)
                                            <option value="{{$keyStatus}}" {{ $request->explodestatus == $keyStatus ? 'selected' : '' }}>
                                                {{$orderStatus}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <div class="col-md-3 row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary form-control btn-rounded">Filters</button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('orders.index')}}" type="submit"
                                    class="btn btn-primary form-control btn-rounded">Clear Filters</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="pt-3">
                @if($request->filterFulfill == 'overdue')
                    <label for="formFile" class="form-label m-3">Upload excel order overdue</label>

                    <div class="col-3 input-group">
                        <input class="form-control" type="file" id="excelOverdieOrder" id="excelOverdieOrder">
                        <button class="btn btn-primary" id="uploadExcelOrderOverdie">Submit</button>
                    </div>
                @endif
                <div class="pb-2" id="sync_driver" style="display: flex; align-items: center; display: none">
                        <button type="submit" class="btn btn-primary m-1 btn-rounded" id="sync_all"
                            onclick="syncDriver('sync_all_driver')">Sync All</button>
                        <button type="submit" class="btn btn-primary m-1 btn-rounded" id="sync_design"
                            onclick="syncDriver('sync_design_driver')">Sync Design</button>
                        <button type="submit" class="btn btn-primary m-1 btn-rounded" id="sync_label"
                            onclick="syncDriver('sync_label_driver')">Sync Label</button>
                        <button type="submit" class="btn btn-primary m-1 btn-rounded"
                            onclick="convertData('convert_design')">Convert Design</button>
                        <button type="submit" class="btn btn-primary m-1 btn-rounded"
                            onclick="convertData('convert_label')">Convert Label</button>
                        <button type="submit" class="btn btn-primary m-1 btn-rounded" onclick="checkBuyLabelAll()">Check Buy
                            Label</button>
                        <button type="submit" class="btn btn-success m-1 btn-rounded" id="buy_label_all"
                            onclick="buyLabelAll()" style="display:none">Buy Label</button>
                        <button type="submit" class="btn btn-primary m-1 btn-rounded" onclick="checkPayOrder()">Check
                            Payorder</button>
                    <!-- <button type="submit" class="btn btn-primary m-1 btn-rounded" onclick="checkPayOrder()">Check
                            Payorder</button> -->
                    <!-- transaction -->
                    <button type="submit" class="btn btn-success mr-1 btn-rounded"  id="pay_label_all"  onclick="payOrder()">Pay
                        Order</button>
                    <!-- <button type="submit" class="btn btn-primary mt-1 btn-rounded" onclick="syncDriver('mark_printed_design')">Mark Printed Design</button> -->
                    <!-- <button type="submit" class="btn btn-primary mt-1 btn-rounded" onclick="syncDriver('mark_printed_label')">Mark Label Printed</button> -->
                </div>
                <table class="table table-striped table-borderless" id="data-table-default">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="checkboxorders" onclick="checkboxorders()" disabled>
                            </th>
                            <th style="min-width: 200px">
                                Info
                            </th>
                            <th>Ticket</th>
                            <th style="min-width: 150px">Fulfill Status</th>
                            <th>Paymant status</th>
                            <th>Tracking id</th>
                            <th>Design Status</th>
                            <!-- <th>Buy Label</th> -->
                            <th style="min-width: 600px">Product items</th>
                            <th style="min-width: 200px">Note</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="order-list">
                        @foreach($orders as $order)
                            <tr style="background-color: {{ $order->getBackgroupRecord() }};" data-id="{{$order->id}}" id="ajaxorder_{{$order->id}}">

                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
            <ul class="pagination mt-3">
                
            </ul>
        </div>
    </div>
</div>
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
@section('scripts')
<script>
    //ajax detail each order
    $('.order-list').each(function () {
        $(this).find("tr").each(function () {
            var id = $(this).data('id');
            var role = '{{$role}}'
            // alert(id);
            // console.log(store_type);
            $.ajax({
                url: '/order/detail',
                type: 'get',
                data: {
                    id: id,
                    role: role
                },
                success: function (response) {
                    $('#ajaxorder_' + id).html(response);
                    // checkButtonFfGm(id);
                },
                error: function (xhr, status, error) {
                    // if (store_type == 4) {
                    //     alert('order load fail: Order ID:' + id);
                    // }

                }
            });
        });
    });

    // <div class="input-group input-group-sm m-1 col-2">
    //     <div class="input-group-prepend">
    //             <button class="btn btn-primary" type="button" id="search-page">search</button>
    //     </div>
    //     <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" id="page">
    // </div>
    // add this html to class pagination jquery when load class pagination 
    // Tạo HTML cho trường tìm kiếm và nút tìm kiếm
    var searchHtml = '<div class="input-group input-group-sm m-1 col-2">';
    searchHtml += '<div class="input-group-prepend">';
    searchHtml += '<button class="btn btn-primary" type="button" id="search-page">Search</button>';
    searchHtml += '</div>';
    searchHtml += '<input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" id="page">';
    searchHtml += '</div>';

    // Thêm HTML vào class pagination
    
    

    let checkboxes = document.querySelectorAll('td input[type="checkbox"]');
    let checkAllCheckbox = document.getElementById('checkboxorders');
    let syncToDriver = document.getElementById('sync_driver');
    let syncAll = document.getElementById('sync_all');
    let syncDesign = document.getElementById('sync_design');
    let syncLabel = document.getElementById('sync_label');

    setTimeout(function () {
        checkboxes = document.querySelectorAll('td input[type="checkbox"]');
        // alert(checkboxes.length);

        checkAllCheckbox = document.getElementById('checkboxorders');
        checkAllCheckbox.disabled = false;

        syncToDriver = document.getElementById('sync_driver');
        syncAll = document.getElementById('sync_all');
        syncDesign = document.getElementById('sync_design');
        syncLabel = document.getElementById('sync_label');

        if (syncAll != null || syncDesign != null || syncLabel != null) {
            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('click', function (event) {
                    var dataId = checkbox.getAttribute('data-id');
                    console.log(dataId);
                    // console.log(arrayItem);
                    let checkDesign = 1;
                    let checkLabel = 1;
                    
                    syncToDriver.style.display = checkbox.checked ? 'inline' : 'none';

                });
            });
        } else {
            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('click', function (event) {
                    syncToDriver.style.display = checkbox.checked ? 'inline' : 'none';
                })
            })
        }
    }, 10000); // <-- time in milliseconds

    function checkboxorders() {
            syncAll.disabled = false;
            syncDesign.disabled = false;
            syncLabel.disabled = false;
        checkboxes.forEach(function (checkbox) {
            var dataId = checkbox.getAttribute('data-id');
            syncToDriver.style.display = checkAllCheckbox.checked ? 'inline' : 'none';
           
            console.log("dataId: " + dataId);
            checkbox.checked = checkAllCheckbox.checked; //cac o se dc chon khi click vao checkbox
           
        });

    }


    function syncDriver(typeButton) {
        const checkboxesSync = document.querySelectorAll('td input[type="checkbox"]:checked');
        const checkedIds = [];

        checkboxesSync.forEach(checkbox => {
            const id = checkbox.id.split('_')[1];
            checkedIds.push(id);
        });
        console.log(checkedIds);
        $.ajax({
            url: './sync-desgin-driver',
            type: 'POST',
            contentType: 'application/json', // Chỉ định loại dữ liệu gửi đi là JSON
            data: JSON.stringify({
                orderIds: checkedIds,
                typeButton: typeButton
            }),
            success: function (response) {
                // console.log(response);
                // location.reload();
                if(response.message){
                    alert('Sync all success');
                }
            },
            error: function (xhr, status, error) {
                alert('chua sync dc')
            }
        });

        // console.log(checkedIds);

    }
    function payOrder(id) {
        // alert('dang code');
        const checkboxesSync = document.querySelectorAll('td input[type="checkbox"]:checked');
        const checkedIds = [];

        checkboxesSync.forEach(checkbox => {
            const id = checkbox.id.split('_')[1];
            checkedIds.push(id);
        });
        // console.log(checkedIds)

        $.ajax({
            url: './walletpay/pay-order',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                orderIds: checkedIds,
            }),
            success: function (response) {
                location.reload();
            },
            error: function (xhr, status, error) {
                alert('chua pay dc')
            }
        });
    }
    function convertData(typeButton) {
        // alert('dang bao tri')
        const checkboxesSync = document.querySelectorAll('td input[type="checkbox"]:checked');
        const checkedIds = [];

        checkboxesSync.forEach(checkbox => {
            const id = checkbox.id.split('_')[1];
            checkedIds.push(id);
        });
        // alert(123)
        $.ajax({
            url: './convert-design',
            type: 'POST',
            contentType: 'application/json', // Chỉ định loại dữ liệu gửi đi là JSON
            data: JSON.stringify({
                typeButton: typeButton,
                orderIds: checkedIds,
            }),
            success: function (response) {
                // console.log(response);
                location.reload();
            },
            error: function (xhr, status, error) {
                alert('chua convert dc')
            }
        });

        // console.log(checkedIds);

    }
    function changestatus(id, role) {
        var selectedColor = $("#fulfill_status_" + id).find(':selected').data('color');
        var status = $('select[name="fulfill_status_' + id + '"]').val();
        if(status=='oversize'){
            // $('.update_order_overize').
            $('#update_order_overize').attr('data-orderid', id);

            $.ajax({
                url: "order/modal-oversize",
                type: 'get',
                data: {
                    id: id,
                },
                success: function (response) {
                    $('#chooseOrderModal .modal-body').html(response);
                    // Display Modal
                    $('#chooseOrderModal').modal('show'); 
                },
                error: function (response) {
                   
                }
            });
        }else{
            console.log(status)
            $.post({
                url: "orders/changeStatus",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    role: role,
                    status: status,
                },
                success: function (response) {
                    // Handle the response here (e.g., display a success message)
                    if (response) {
                        $("#fulfill_status_" + id).removeClass('btn-success btn-warning btn-info btn-light').addClass(selectedColor);
                        alert('Change success');
                    }
                },
                error: function (response) {
                    var jsonResponse = JSON.parse(response.responseText);
                    var data = jsonResponse.data;
                    alert(data);
                }
            });
        }
        // if(status!='cancelled'){
        
        // }

    };
    function update_order_overize(){
        var checkedIds = [];
        $(".checkCheckedOversize:checked").each(function(){
            checkedIds.push($(this).val());
        });
        var totalsize = checkedIds.length;
        var orderid = $('#update_order_overize').data('orderid');
        console.log(checkedIds);
        console.log(orderid);

        $.ajax({
            url: 'order/update-oversize-side',
            type: 'get',
            data: {
                order_id: orderid,
                checkedIds: checkedIds,
                totalsize:totalsize
            },
            success: function (response) {
                $('#chooseOrderModal').modal('hide');
            }
        });
    }
    function edit_order_btn(id, shipping_label, tracking_id, tracking_link, fulfill_status, first_name, last_name, phone, address_1, address_2, city, state, postcode, country, role) {
        // Retrieve the value of data-id attribute using jQuery's data method
        let popupUrl = '/order/popup';
        $.ajax({
            url: popupUrl,
            type: 'get',
            data: {
                order_id: id,
                shipping_label: shipping_label,
                tracking_id: tracking_id,
                tracking_link: tracking_link,
                fulfill_status: fulfill_status,
                first_name: first_name,
                last_name: last_name,
                phone: phone,
                address_1: address_1,
                address_2: address_2,
                city: city,
                state: state,
                postcode: postcode,
                country: country,
                role: role
            },
            success: function (response) {
                // Add response in Modal body
                $('#editOrderModal .modal-body').html(response);
                // Display Modal
                $('#btnAjaxUpdate').attr('data-orderid', order_id);
                $('#editOrderModal').modal('show');
            }
        });
    }
    function support(order_id) {
        $.ajax({
            url: '/support',
            type: 'get',
            data: {
                order_id: order_id
            },
            success: function (response) {
                console.log(response);
                // Add response in Modal body
                $('.support-modal-body').html(response);
                // Display Modal
                $('#supportModal').modal('show');

            }
        });
    }

    function addSupport(id) {
        console.log("support");
        console.log(id);
        $("#btnAddSupport").prop('disabled', true);
        var subject = $("#subject_" + id).val();
        var message = $("#message_" + id).val();
        var file_data = $("#file_" + id).prop('files')[0]; // Get the file data
        if (subject != "") {
            var form_data = new FormData(); // Create a FormData object
            form_data.append('_csrf', "{{csrf_token()}}");
            form_data.append('file', file_data ?? null);
            form_data.append('order_id', id);
            form_data.append('user_id', "{{ $user_id }}");
            form_data.append('subject', subject ?? null);
            form_data.append('message', message ?? null);

            $.ajax({
                url: '/tickets/add',
                type: 'POST',
                data: form_data,
                processData: false,
                contentType: false,
                success: function (response) {
                    $("#btnAddSupport").prop('disabled', false);
                    $('#supportModal').modal('hide');
                    location.reload()
                },
                error: function (response) {
                    $("#btnAddSupport").prop('disabled', false);
                    alert("Error");
                }
            });
        } else {
            alert("Please fill subject!");
        }

    }

    $(document).on('click', 'body #btnAjaxUpdate', function () {
        let order_id = $('#orderid').val();
        console.log("order_id");
        console.log(order_id);
        var form_data = new FormData(); // Create a FormData object
        form_data.append('_csrf', "{{csrf_token()}}");
        if($('#inputFirstname').val()!= ''){
            form_data.append('first_name', $('#inputFirstname').val());
        }
        if($('#inputLastname').val()!= ''){
            form_data.append('last_name', $('#inputLastname').val());
        }
        if($('#inputAddress').val()!= ''){
            form_data.append('address_1', $('#inputAddress').val());
        }
        if($('#inputAddress2').val()!= ''){
            form_data.append('address_2', $('#inputAddress2').val());
        }
        if($('#inputCity').val()!= ''){
            form_data.append('city', $('#inputCity').val());
        }
        if($('#inputState').val()!= ''){
            form_data.append('state', $('#inputState').val());
        }
        if($('#inputZip').val()!= ''){
            form_data.append('postcode', $('#inputZip').val());
        }
        if($('#inputCountry').val()!= ''){
            form_data.append('country', $('#inputCountry').val());
        }
        if($('#inputLabel').val()!= ''){
            form_data.append('label', $('#inputLabel').val());
        }
        if($('#inputtrackingid').val()!= ''){
            form_data.append('tracking_id', $('#inputtrackingid').val());
        }
        // form_data.append('first_name', $('#inputFirstname').val());
        // form_data.append('last_name', $('#inputLastname').val());
        // form_data.append('address_1', $('#inputAddress').val());
        // form_data.append('address_2', $('#inputAddress2').val());
        // form_data.append('city', $('#inputCity').val());
        // form_data.append('state', $('#inputState').val());
        // form_data.append('postcode', $('#inputZip').val());
        // form_data.append('country', $('#inputCountry').val());
        // form_data.append('label', $('#inputLabel').val());
        // form_data.append('tracking_id', $('#inputtrackingid').val());

        var mockup_front = $("input[id^='inputVariant\\[']");
        mockup_front.each(function () {
            var inputValue = $(this).val(); // Lấy giá trị của mỗi phần tử
            var dataId = $(this).data('id');
            form_data.append('variant_id[' + dataId + ']', inputValue);
        });
        // var inputSleeveLeftDesign = $("input[id^='inputSleeveLeftDesign\\[']");
        // inputSleeveLeftDesign.each(function() {
        //     var inputValue = $(this).val(); // Lấy giá trị của mỗi phần tử
        //     var dataId = $(this).data('id');
        //     form_data.append('sleeve_left_design[' + dataId + ']', inputValue);
        // });

        // var mockup_front = $("input[id^='mockup_front");
        // var inputValue = mockup_front.val(); // Lấy giá trị của mỗi phần tử
        // form_data.append('mockup_front', inputValue);

        var mockup_front = $("input[id^='mockup_front\\[']");
        mockup_front.each(function () {
            var inputValue = $(this).val(); // Lấy giá trị của mỗi phần tử
            var dataId = $(this).data('id');
            form_data.append('mockup_front[' + dataId + ']', inputValue);
        });

        var mockup_back = $("input[id^='mockup_back\\[']");
        mockup_back.each(function () {
            var inputValue = $(this).val(); // Lấy giá trị của mỗi phần tử
            var dataId = $(this).data('id');
            form_data.append('mockup_back[' + dataId + ']', inputValue);
        });

        // var mockup_back = $("input[id^='mockup_back");
        // var inputValue = mockup_front.val(); // Lấy giá trị của mỗi phần tử
        // form_data.append('mockup_back', inputValue);

        var inputFrontDesign = $("input[id^='inputFrontDesign\\[']");
        inputFrontDesign.each(function () {
            var inputValue = $(this).val(); // Lấy giá trị của mỗi phần tử
            var dataId = $(this).data('id');
            form_data.append('front_design[' + dataId + ']', inputValue);
        });

        var inputBackDesign = $("input[id^='inputBackDesign\\[']");
        inputBackDesign.each(function () {
            var inputValue = $(this).val(); // Lấy giá trị của mỗi phần tử
            var dataId = $(this).data('id');
            form_data.append('back_design[' + dataId + ']', inputValue);
        });

        var inputSleeveRightDesign = $("input[id^='inputSleeveRightDesign\\[']");
        inputSleeveRightDesign.each(function () {
            var inputValue = $(this).val(); // Lấy giá trị của mỗi phần tử
            var dataId = $(this).data('id');
            form_data.append('sleeve_right_design[' + dataId + ']', inputValue);
        });

        var inputSleeveLeftDesign = $("input[id^='inputSleeveLeftDesign\\[']");
        inputSleeveLeftDesign.each(function () {
            var inputValue = $(this).val(); // Lấy giá trị của mỗi phần tử
            var dataId = $(this).data('id');
            form_data.append('sleeve_left_design[' + dataId + ']', inputValue);
        });

        ajaxUpdate(order_id, form_data);
    });
    function copy_info(order_id, order) {
        // Define the order information with line breaks
        var orderInfo = "ID: \nRef: \nSTT: \nSeller: \nStore: ";
        $.ajax({
            url: '/order/copy-info?order_id='+order_id,
            type: 'get',
            success: function (response) {
                console.log(response);
                orderInfo = response.data;
                console.log(orderInfo);
                // Create a temporary textarea element to hold the order info
                let textarea = document.createElement("textarea");
                textarea.value = orderInfo;

                // Append the textarea to the body (it must be in the document for the copy command to work)
                document.body.appendChild(textarea);

                // Select the textarea content
                textarea.select();
                textarea.setSelectionRange(0, 99999); // For mobile devices

                // Execute the copy command
                document.execCommand("copy");

                // Remove the textarea from the document
                document.body.removeChild(textarea);
                order.textContent = 'Copied'; 
                setTimeout(function() {
                    order.textContent = 'Copy info';
                }, 1000);
            }
        });
    }
    function timeline_btn(order) {
        var order_id = $(order).data('id');

        // AJAX request
        $.ajax({
            url: '/timelineorder/ajax',
            type: 'get',
            data: {
                object_id: order_id
            },
            success: function (response) {
                // Add response in Modal body
                $('#timelineModal .modal-body').html(response);
                // Display Modal
                $('#timelineModal').modal('show');
            }
        });
    }

    function orderChange(orderId, typeChange) {
        var textNoteValue = document.getElementById("note_order_" + orderId).value;
        var selectedStatus = document.getElementById("orderStatusSelect_" + orderId);

        var data = {
            textNoteValue: textNoteValue,
            typeChange: typeChange
        }


        if (typeChange != 'note_order') {
            var result = confirm("Bạn có muốn thực hiện hành động này?");
            if (!result) {

                if (selectedStatus.value == 'cancelled') {
                    selectedStatus.value = 'new_order'
                } else {
                    selectedStatus.value = 'cancelled'
                }
                return
            } else {
                var selectedStatusValue = selectedStatus.value
                data.selectedStatusValue = selectedStatusValue
                if (selectedStatusValue === 'cancelled') {
                    $('#orderStatusSelect_' + orderId).removeClass('btn-success').addClass('btn-danger');
                } else {
                    $('#orderStatusSelect_' + orderId).removeClass('btn-danger').addClass('btn-success');
                }
            }
        }



        $.ajax({
            url: '/qrChangeStatus/' + orderId,
            type: 'GET',
            data: data,
            success: function (response) {
                // location.reload()
                if (response) {
                    alert(JSON.parse(response).data);
                }
            },
            error: function (response) {
                var jsonResponse = JSON.parse(response.responseText);
                var data = jsonResponse.data;
                alert(data);
            }
        });

    }

    function ajaxUpdate(orderId, orderData) {
        let url = "order/ajaxUpdate/" + orderId;
        $.ajax({
            url: url,
            type: 'POST',
            data: orderData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                // Handle the response here (e.g., display a success message)
                if (response.message == 'ok') {
                    $('#editOrderModal').modal('hide');
                } else {
                    alert('Error!');
                }
            },
        });
    }
    function create_item(order_id){
        $.ajax({
            url: './create-item',
            type: 'POST',
            contentType: 'application/json', // Chỉ định loại dữ liệu gửi đi là JSON
            data: JSON.stringify({
                order_id: order_id,
            }),
            success: function (response) {
                // console.log(response);
                // location.reload();
                if(response.message){
                }
            },
            error: function (xhr, status, error) {
            }
        });
    }
    function overideCenvertDesign(itemID, type) {
        console.log("Overide");
        console.log(itemID);
        console.log(type);
        $.ajax({
            url: '/overidedesign',
            type: 'get',
            data: {
                itemID: itemID,
                type: type
            },
            success: function (response) {
                if (JSON.parse(response).message) {
                    alert('Overide success. ')
                    // location.reload();
                }
            },
            error: function (response) {
                alert('Overide error');
            },
        });
    }
    function overideScaleDesign(itemID, type) {
        console.log("scale");
        console.log(itemID);
        console.log(type);
        $.ajax({
            url: '/scaledesign',
            type: 'get',
            data: {
                itemID: itemID,
                type: type
            },
            success: function (response) {
                if (JSON.parse(response).message) {
                    alert('Overide success. ')
                    // location.reload();
                }
            },
            error: function (response) {
                alert('Overide error');
            },
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
                a.href = `/orders?${queryString.toString()}`;
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

    // Usage in your AJAX success callback
    $.ajax({
        url: '/order/ajaxCountStatusOrder',
        type: 'get',
        data: {
            order_id: "{{$request->order_id??''}}",
            ref_id: "{{$request->ref_id??''}}",
            seller_id: "{{$request->seller_id??''}}",
            name: "{{$request->name??''}}",
            store_id: "{{$request->store_id??''}}",
            filterStock: "{{$request->filterStock??''}}",
            filterLabel: "{{$request->filterLabel??''}}",
            filterFulfill: "{{$request->filterFulfill??''}}",
            paymentStatus: "{{$request->paymentStatus??''}}",
            quantity: "{{$request->quantity??''}}",
            user_id: '{{$user_id}}',
            role: '{{$role}}'
        },
        success: function (response) {
            console.log(response);
            const data = response;
            $('#ajaxNewOrder').html(`New(${data.news})`);
            $('#ajaxWrongsizeOrder').html(`Wrongsize(${data.wrongsize})`);
            $('#ajaxFixedOrder').html(`Fixed(${data.fixed})`);
            $('#ajaxPrintedOrder').html(`Printed(${data.printed})`);
            $('#ajaxLabelPrintedOrder').html(`Label Printed(${data.labelprinted})`);
            $('#ajaxReprintOrder').html(`Reprint(${data.reprint})`);
            $('#ajaxOnholdOrder').html(`On hold(${data.onhold})`);
            $('#ajaxPressedOrder').html(`Pressed(${data.pressed})`);
            $('#ajaxOverdieOrder').html(`Overdue(${data.overdue})`);
            $('#ajaxPriorityOrder').html(`Priority(${data.priority})`);
            $('#ajaxOversizeOrder').html(`Oversize(${data.oversize})`);
            $('#ajaxOversize_priority').html(`Oversize + priority(${data.oversize_priority})`);
            $('#ajaxShippedOrder').html(`Shipped(${data.shipped})`);
            $('#ajaxTestOrder').html(`Test Order(${data.testOrder})`);
            var status = '{{$request->filterFulfill}}';
            var searchorder_id = '{{$request->order_id}}';
            var searchref_id = '{{$request->ref_id}}';
            var searchname = '{{$request->name}}';
            var pageActive = '{{$request->page ?? 1}}';
            pageActive = parseInt(pageActive);
            if(searchorder_id!='' || searchref_id!='' || searchname!=''){
                var total = data.news + data.wrongsize + data.fixed + data.printed + data.labelprinted + data.reprint + data.onhold + data.pressed + data.overdue + data.priority + data.oversize+ data.shipped + data.testOrder;
                createPagination(total, pageActive, 40);
            }else{
                if (status == 'new_order') {
                    var total = data.news;
                    createPagination(total, pageActive,40);
                } else if(status == 'wrongsize'){
                    var total = data.wrongsize;
                    createPagination(total, pageActive,40);
                } else if(status == 'fixed'){
                    var total = data.fixed;
                    createPagination(total, pageActive,40);
                } else if(status == 'printed'){
                    var total = data.printed;
                    createPagination(total, pageActive,40);
                } else if(status == 'labelprinted'){
                    var total = data.labelprinted;
                    createPagination(total, pageActive,40);
                } else if(status == 'reprint'){
                    var total = data.reprint;
                    createPagination(total, pageActive,40);
                } else if(status == 'onhold'){
                    var total = data.onhold;
                    createPagination(total, pageActive,40);
                } else if(status == 'pressed'){
                    var total = data.pressed;
                    createPagination(total, pageActive,40);
                } else if(status == 'overdue'){
                    var total = data.overdue;
                    createPagination(total, pageActive,40);
                } else if(status == 'priority'){
                    var total = data.priority;
                    createPagination(total, pageActive,40);
                } else if(status == 'oversize'){
                    var total = data.oversize;
                    createPagination(total, pageActive,40);
                } else if(status == 'overprio'){
                    var total = data.oversize_priority;
                    createPagination(total, pageActive,40);
                } else if(status == 'shipped'){
                    var total = data.shipped;
                    createPagination(total, pageActive,40);
                } else if(status == 'test_order'){
                    var total = data.testOrder;
                    createPagination(total, pageActive,40);
                } else {
                    var total = data.news + data.wrongsize + data.fixed + data.printed + data.labelprinted + data.reprint + data.onhold + data.pressed + data.overdue + data.priority + data.oversize;
                    createPagination(total, pageActive, 40);
                }
            }
            $('.pagination').prepend(searchHtml);
            $('#search-page').click(function () {
                var page = $('#page').val();
                if (page != '') {
                    // Lấy URL hiện tại
                    var url = window.location.href;

                    // Kiểm tra xem URL đã có tham số truy vấn "page" chưa
                    var regex = new RegExp("[?&]page(=([^&#]*)|&|#|$)");
                    var match = regex.exec(url);

                    // Nếu đã tồn tại tham số "page" trong URL, thay thế giá trị của nó
                    if (match !== null) {
                        url = url.replace(/([&?]page=)[^&]+/, "$1" + encodeURIComponent(page));
                    } else {
                        // Nếu chưa tồn tại, thêm tham số "page" vào URL
                        url += (url.indexOf('?') !== -1 ? '&' : '?') + 'page=' + encodeURIComponent(page);
                    }

                    // Chuyển hướng đến URL mới với tham số truy vấn đã được cập nhật
                    window.location.href = url;

                }
            });
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
        }
    });
    </script>
@stop

@endsection