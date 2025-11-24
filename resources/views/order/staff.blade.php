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
    <div class="modal-dialog" style="max-width: 1050px;">
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
<!-- End Modal Edit Order -->
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
<!-- Bootstrap modal HTML -->
<div class="modal fade" id="shippingLevelModal" tabindex="-1" role="dialog" aria-labelledby="shippingMethodModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shippingLevelModalLabel">Would you like to buy labels?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitShippingLevelButton">Submit</button>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap modal HTML -->
<div class="modal fade" id="createOrderModal" tabindex="-1" role="dialog" aria-labelledby="shippingMethodModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <!-- Maintenance -->
                <a href="./order/add?type=shipping" type="button" class="btn btn-primary" id="createOrderLabel">Seller
                    ship</a>
                <a href="./order/add?type=label" type="button" class="btn btn-primary" id="createOrderManual">Label
                    ship</a>
            </div>
        </div>
    </div>
</div>
<div class="element-box">
    <div class="card">
        <div class="card-body">

            @include('partials.messages')
            <div class="panel-body clearfix mt-3" id="shortlink">
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
                        @if($role != 'Seller')
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control" id="store" name="store" style="width: 190px;">
                                        <option value="">Store</option>
                                        @foreach(liststore() as $store)
                                            <option value="{{$store['id']}}" {{ $request->store == $store['id'] ? 'selected' : '' }}>
                                                {{$store['name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" id="filterlabel" name="filterLabel">
                                    <option>Label Tracking</option>
                                    <option value="have_label" {{ $request->filterLabel == 'have_label' ? 'selected' : '' }}>Have label</option>
                                    <option value="no_label" {{ $request->filterLabel == 'no_label' ? 'selected' : '' }}>
                                        No label</option>
                                </select>
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
                    <!-- <div class="row"> -->
                    <!-- <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Filters</button>
                        </div> -->
                    <!-- </div> -->
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
                    @if($role != 'Seller')
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
                    @endif
                    <button type="submit" class="btn btn-success mr-1 btn-rounded"  id="pay_label_all"  onclick="payOrder()">Pay
                        Order</button>
                </div>
                <table class="table table-striped table-borderless" id="data-table-default">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="checkboxorders" onclick="checkboxorders()" disabled>
                            </th>
                            <th style="min-width: 250px">
                                Info
                            </th>
                            <th style="min-width: 150px">Fulfill Status</th>
                            <th>Label Status</th>
                            <th style="min-width: 200px">Shipping address</th>
                            <th>Shipping Medthod</th>
                            <th>Tracking ID / Label</th>
                            <th>Tracking Status</th>
                            <th style="min-width: 100px">Total Cost</th>
                            <!-- gearment -->
                            <th style="min-width: 200px">Fulfull Partner</th>
                            <th style="min-width: 200px">Note</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Process Time</th>
                            <th style="min-width: 100px">action</th>
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
                    role: role,
                    user_id: '{{$user_id}}'
                },
                success: function (response) {
                    $('#ajaxorder_' + id).html(response);
                    // checkButtonFfGm(id);
                },
                error: function (xhr, status, error) {
                    if (store_type == 4) {
                        alert('order load fail: Order ID:' + id);
                    }

                }
            });
        });
    });

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
    }, 15000); // <-- time in milliseconds

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

    function checkboxOrdersPay() {
        syncToDriver.style.display = checkAllCheckbox.checked ? 'inline' : 'none';
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = checkAllCheckbox.checked;
        });

    }

    function checkBuyLabelAll() {
        checkboxes.forEach((check) => {
            check.checked = false;
        });
        var buyLabelAll = document.querySelectorAll(".buylabel-btn");
        buyLabelAll.forEach((e) => {
            var id = e.getAttribute('data-id');
            var check = document.getElementById('checkbox_' + id);
            check.checked = true;
            $("#buy_label_all").css("display", "inline");
        });

    }

    function checkPayOrder() {
        checkboxes.forEach((check) => {
            check.checked = false;
        });
        checkboxes.forEach(function (checkbox) {
            var dataId = checkbox.getAttribute('data-id');
            var payorder = document.getElementById('payorder_' + dataId);
            var status = payorder.textContent;
            console.log(status);
            if (status == 'pending') {
                checkbox.checked = true;
            }
            $("#pay_label_all").css("display", "inline");
        });
    }

    function buyLabelAll() {
        var buyLabelAll = document.querySelectorAll(".buylabel-btn");
        console.log(buyLabelAll);
        buyLabelAll.forEach((e) => {
            var id = e.getAttribute('data-id');
            var check = document.getElementById('checkbox_' + id);
            if (check.checked) {
                $.ajax({
                    url: './buy-all-label',
                    type: 'POST',
                    contentType: 'application/json', // Chỉ định loại dữ liệu gửi đi là JSON
                    data: JSON.stringify({
                        _token: "{{ csrf_token() }}",
                        id: id,
                    }),
                    success: function (response) {
                        // console.log(response);
                    },
                    error: function (xhr, status, error) {
                        // alert('chua sync dc')
                    }
                });
            }
        });
        alert("Add buy Label done.");
        // location.reload();
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
        if(status == 'cancelled'){
            if(confirm('Are you sure you want to cancel this order?')){
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
    };
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
    function show_detail(role, ref_id, order_id, seller_id, total_cost,created_at, fulfill_status,print_cost,shipping_cost,total_cost){
        $("#detail_"+order_id).prop('disabled', true);
        $.ajax({
            url: '/order/popup-detail',
            type: 'get',
            data: {
                role:role,
                ref_id:ref_id,
                order_id: order_id,
                seller_id: seller_id,
                total_cost: total_cost,
                created_at:created_at,
                fulfill_status:fulfill_status,
                print_cost:print_cost,
                shipping_cost:shipping_cost,
                total_cost:total_cost
            },
            success: function (response) {
                // Add response in Modal body
                $('#orderdetailModal .modal-body').html(response);
                // Display Modal
                $('#orderdetailModal').modal('show');
                $("#detail_"+order_id).prop('disabled', false);
            }
        });
    }
    $(document).on('click', 'body #btnAjaxUpdate', function () {
        let order_id = $('#orderid').val();
        console.log("order_id");
        console.log(order_id);
        var form_data = new FormData(); // Create a FormData object
        form_data.append('_csrf', "{{csrf_token()}}");
        form_data.append('first_name', $('#inputFirstname').val());
        form_data.append('last_name', $('#inputLastname').val());
        form_data.append('address_1', $('#inputAddress').val());
        form_data.append('address_2', $('#inputAddress2').val());
        form_data.append('city', $('#inputCity').val());
        form_data.append('state', $('#inputState').val());
        form_data.append('postcode', $('#inputZip').val());
        form_data.append('country', $('#inputCountry').val());
        form_data.append('label', $('#inputLabel').val());
        form_data.append('tracking_id', $('#inputtrackingid').val());

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


    function importfile() {
        var fileInput = $("#import")[0];
        var file = fileInput.files[0];
        if (!file) {
            alert("Please select a file before importing.");
            return;
        }
        $("#btnimport").prop('disabled', true);
        console.log("file");
        console.log(file);
        var formData = new FormData();
        formData.append('file', file);
        formData.append('_token', "{{ csrf_token() }}");
        $.ajax({
            url: './import-orders',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (JSON.parse(response).message) {
                    location.reload();
                }
            },
            error: function (xhr, status, error) {
                if (JSON.parse(response).message == 0) {
                    location.reload();
                }
            }
        });
    }

    function support(id, role, order_id) {
        console.log("support");
        console.log(order_id);
        console.log(role);
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
                }
            });
        } else {
            alert("Please fill subject!");
        }

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

    function buyLabel(orderId) {
        // Show the Bootstrap modal
        $('#shippingLevelModal').modal('show');
        $('#submitShippingLevelButton').on('click', function () {
            submitShippingLevel(orderId);
        });
    }

    function submitShippingLevel(orderId) {
        $.ajax({
            url: './buy-label',
            type: 'GET',
            contentType: 'application/json',
            data: {
                orderId: orderId,
            },
            success: function (response) {
                $('#shippingLevelModal').modal('hide');
                location.reload();
            },
            error: function (xhr, status, error) {
                alert('...');
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

    function changeShippingLevel(orderId) {
        var level = $("#shipping_method_" + orderId).val();
        console.log(level);
        $.post({
            url: "orders/changeShippingMethod",
            data: {
                _token: "{{ csrf_token() }}",
                id: orderId,
                level: level,
            },
            success: function (response) {
                // Handle the response here (e.g., display a success message)
                if (response) {
                    alert('Change success');
                }
            },
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

    function duplicate(id) {
        var confirmation = confirm('Your want to duplicate order id: ' + id + ' ?');
        if (confirmation) {
            $.ajax({
                url: './order/duplicate',
                type: 'GET',
                contentType: 'application/json',
                data: {
                    id: id,
                },
                success: function (response) {
                    // Xử lý khi request thành công
                },
                error: function (xhr, status, error) {
                    // Xử lý khi có lỗi xảy ra
                }
            });
        } else {
            // Xử lý khi người dùng nhấp vào nút "No" hoặc đóng hộp thoại
        }
    }

    function createOrder() {
        $('#createOrderModal').modal('show');
        $('#createOrderLabel').on('click', function () {
            createOrderLabel();
        });
        $('#createOrderManual').on('click', function () {
            createOrderManual();
        });
    }
    $("#uploadExcelOrderOverdie").on("click", function () {
        //get file excel from input id excelOverdieOrder
        var file_data = $('#excelOverdieOrder').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('_token', "{{ csrf_token() }}");
        $.ajax({
            url: '/order/upload-excel-overdie',
            type: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response) {
                    alert('Upload success');
                    location.reload();
                }
            },
            error: function (xhr, status, error) {
                //  alert('Upload error');
            }
        });
    });

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
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
        }
    });

    </script>
@stop

@endsection