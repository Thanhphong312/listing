@extends('layouts.app')

@section('page-title', __('Flashdeals'))
@section('page-heading', __('Flashdeals( ' . $promotion_name . ' ) from ' . $begin_time . ' to ' . $end_time))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Flashdeals')
</li>
@stop

@section('content')
<div class="element-box">
    <div class="card">
        <div class="card-body">

            @include('partials.messages')
            <div class="modal fade" id="show_all_product">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="width: 1200px;">
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

                                        @include('partials.messages')
                                        <div class="panel-body clearfix mt-3" id="shortlink">
                                            <div class="row mb-2">
                                                <div class="col-2">Number Sku: <span id="numbersku"></span></div>
                                                <div class="col-10 row">
                                                    <div class="col-3">
                                                        <label for="size_chart_add">Quantity limit</label>
                                                        <input class="form-control" type="text"
                                                            name="quantity_limit_value" id="quantity_limit_value"
                                                            value="-1">
                                                    </div>
                                                    <div class="col-3">
                                                        <label for="size_chart_add">Quantity per user </label>
                                                        <input class="form-control" type="text"
                                                            name="quantity_per_user_value" id="quantity_per_user_value"
                                                            value="-1">
                                                    </div>
                                                    <div class="col-3">
                                                        <span class="btn btn-primary" onclick="donechoose()">done</span>
                                                    </div>
                                                </div>
                                            </div>
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

                                                    <button type="submit" class="btn btn-primary form-control btn-rounded"
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
                                            <table class="table table-striped table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <input type="checkbox" id="checkboxorders"
                                                                onclick="checkboxproducts(this)">
                                                        </th>
                                                        <th style="min-width: 250px">
                                                            REMOTE ID
                                                        </th>
                                                        <th>NAME</th>
                                                        <th>IMAGE</th>
                                                        <th style="min-width: 150px">NUMBER SKU</th>
                                                        <th style="min-width: 150px">Discounnt</th>
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
            <div class="row">
                <div class="col-8">
                    <span class="btn btn-primary m-2">TOTAL : {{$totalfld}}</span> - <span
                        class="btn btn-success m-2">SUCCESS : {{$totalsuccess}}</span>
                    <button class="btn btn-danger" onclick="reup()">Re up product flashdeal</button>
                    <button class="btn btn-primary" onclick="getproduct()">get product</button>
                    <button class="btn btn-success" onclick="syncproductflashdeal()">sync product flashdeal</button>
                </div>
            </div>
            <div class="row">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkallproductfld" onclick="checkallproductfld(this)"></th>
                            <th style="width:10%">STT</th>
                            <th style="width:10%">ID</th>
                            <th style="width:40%">NAME</th>
                            <th style="width:10%">DISCOUNT </th>
                            <th style="width:10%">Quantity limit </th>
                            <th style="width:10%">Quantity per user</th>
                            <th style="width:10%">Note</th>
                            <th style="width:10%">Priority</th>
                            <th style="width:10%">Updated At</th>
                            <th style="width:10%">OPTiON</th>
                        </tr>
                    </thead>
                    <!-- 'store_id', 'promotion_name', 'activity_type', 'product_level', 'status_fld', 'begin_time', 'end_time', 'auto', 'status' -->
                    <tbody class="flashdeal-list">
                        @foreach($flashdealproducts as $key => $flashdealproduct)
                            <tr id="flashdealproduct_{{$flashdealproduct->id}}" data-id="{{$flashdealproduct->id}}">

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('CSS')
<link rel="stylesheet prefetch"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
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
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

<script>
    $(".datepicker").datepicker({
        autoclose: false,
        todayHighlight: true,
        clearBtn: true
    });
    $('.flashdeal-list').each(function () {
        $(this).find("tr").each(function () {
            var id = $(this).data('id');

            $.ajax({
                url: '../ajax-detail/' + id,
                success: function (response) {
                    $('#flashdealproduct_' + id).html(response);
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
    let listproductinflashdeal = @json($flashdealproducts->pluck('product_id'));
    <?php 
        $skuCounts = $total_skus;
    ?>
    let numberskuchoose = {{$skuCounts}};
    $("#numbersku").text(parseInt(numberskuchoose));
    let listproductjob = [];
    // function edit_store(id) {
    //     $.get('./flashdeals/edit/' + id, function (data) {
    //         $("#body_edit").html(data);
    //     });
    //     $('#editStore').modal('show');
    // }
    function checkboxproducts(target) {
        let checkboxes = document.querySelectorAll('td input[type="checkbox"][class="chooseproductfld"]');

        checkboxes.forEach(function (checkbox) {
            checkbox.checked = target.checked; // Cập nhật trạng thái checked
            // if(checkbox.checked){
            checkproduct(checkbox, checkbox.dataset.id); // Sử dụng dataset để lấy giá trị data-id
            // }
        });
    }
    function deleteflashdealproduct(id) {
        $.ajax({
                url: '../delete/' + id,
                success: function (response) {
                    location.reload();
                    // $('#flashdealproduct_' + id).html(response);
                    // checkButtonFfGm(id);
                },
                error: function (xhr, status, error) {
                    // if (store_type == 4) {
                    //     alert('order load fail: Order ID:' + id);
                    // }

                }
            });
    }
    function changepriority(id, target){
        var formData = new FormData();
        formData.append('id', id)
        formData.append('checked', target.checked)
        $.ajax({
            url: '../changepriority',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (JSON.parse(response).message) {
                    console.log("ok");
                }
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
    function add_flashdeal() {
        $.get('../add', function (data) {
            $("#body_add").html(data);
        });
        $('#addStore').modal('show');
    }
    function showStore() {
        var name = $("#name").val();
        var remote_id = $("#remote_id").val();

        $.get('../get-all-product?store_id={{$store_id}}&name=' + name + '&remote_id=' + remote_id + '&page=1', function (data) {
            // Make sure data.data is an array before proceeding
            if (Array.isArray(data.data.data)) {
                console.log(data.data.data);

                // Clear the existing product list
                $(".product-list").html("");

                // Loop through the fetched products and append them to the table
                data.data.data.forEach(function (product) {
                    let skusCount = JSON.parse(product.skus).length; // Count the number of SKUs
                    let productRow = '';
                    if (listproductinflashdeal.includes(product.remote_id) || listproductjob.includes(product.remote_id)) {
                        if (listproductinflashdeal.includes(product.remote_id)) {
                            productRow = `
                                <tr data-id="${product.id}" id="ajaxorder_${product.id}" data-total="${skusCount}" style="background-color: #73ff87;">
                                    <td><input  class="chooseproductfld" data-id="${product.remote_id}" data-numsku="${skusCount}" type="checkbox" id="product_${product.id}" onchange="checkproduct(this, '${product.remote_id}')"  checked disabled></td>
                                    <td>${product.remote_id}</td>
                                    <td>${product.title}</td>
                                    <td>${skusCount}</td>
                                    <td><input class="form-control" id="discount_${product.id}" onblur="changeDiscount(${product.id},this)" type="text" value="${product.discount}"></input></td>
                                </tr>
                            `;
                        } else {
                            productRow = `
                                <tr data-id="${product.id}" id="ajaxorder_${product.id}" data-total="${skusCount}" style="background-color: #73ff87;">
                                    <td><input class="chooseproductfld"  data-id="${product.remote_id}" data-numsku="${skusCount}" type="checkbox" id="product_${product.id}" onchange="checkproduct(this, '${product.remote_id}')"  checked></td>
                                    <td>${product.remote_id}</td>
                                    <td>${product.title}</td>
                                    <td>${skusCount}</td>
                                    <td><input class="form-control" id="discount_${product.id}" onblur="changeDiscount(${product.id},this)" type="text" value="${product.discount}"></input></td>
                                </tr>
                            `;
                        }

                    } else {
                        productRow = `
                        <tr data-id="${product.id}" id="ajaxorder_${product.id}" data-total="${skusCount}">
                            <td><input  class="chooseproductfld" data-id="${product.remote_id}" data-numsku="${skusCount}" type="checkbox" id="product_${product.id}" onchange="checkproduct(this, '${product.remote_id}')"></td>
                            <td>${product.remote_id}</td>
                            <td>${product.title}</td>
                            <td>${skusCount}</td>
                            <td><input class="form-control" id="discount_${product.id}" onblur="changeDiscount(${product.id},this)" type="text" value="${product.discount}"></input></td>
                        </tr>
                    `;
                    }


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

    function setup(){
        let checkboxes = document.querySelectorAll('td input[type="checkbox"][class="chooseproductfld"]');
        var discount = $("#discount").val();
        console.log(discount);
        var producttiktoklist = [];
        checkboxes.forEach(function (checkbox) {
            if(checkbox.checked){
                // console.log(checkbox.getAttribute('id'));
                const checkboxId = checkbox.getAttribute('id');
                const number = checkboxId.match(/\d+/)[0]; // Tìm số trong chuỗi
                console.log(number); 
                producttiktoklist.push(number);
                $("#discount_"+number).val(discount);
            }
        });
        $.ajax({
            url: `../edit-all-product-flashdeal`,
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
    function getproduct(page = 1) {
        var name = $("#name").val();
        var remote_id = $("#remote_id").val();
        
        $.get('../get-all-product?store_id={{$store_id}}&name=' + name + '&remote_id=' + remote_id + '&page='+page, function (data) {
            // Make sure data.data is an array before proceeding
            if (Array.isArray(data.data.data)) {
                console.log(data.data.data);

                // Clear the existing product list
                $(".product-list").html("");

                // Loop through the fetched products and append them to the table
                data.data.data.forEach(function (product) {
                    let skusCount = JSON.parse(product.skus).length; // Count the number of SKUs
                    let productRow = '';
                    if (listproductinflashdeal.includes(product.remote_id) || listproductjob.includes(product.remote_id)) {
                        if (listproductinflashdeal.includes(product.remote_id)) {
                            productRow = `
                                <tr data-id="${product.id}" id="ajaxorder_${product.id}" data-total="${skusCount}" style="background-color: #73ff87;">
                                    <td><input  class="chooseproductfld" data-id="${product.remote_id}" data-numsku="${skusCount}" type="checkbox" id="product_${product.id}" onchange="checkproduct(this, '${product.remote_id}')"  checked disabled></td>
                                    <td>${product.remote_id}</td>
                                    <td>${product.title}</td>
                                    <td>${product?.store_product?.data}</td>
                                    <td>${skusCount}</td>
                                    <td><input class="form-control" id="discount_${product.id}" onblur="changeDiscount(${product.id},this)" type="text" value="${product.discount}"></input></td>
                                </tr>
                            `;
                        } else {
                            productRow = `
                                <tr data-id="${product.id}" id="ajaxorder_${product.id}" data-total="${skusCount}" style="background-color: #73ff87;">
                                    <td><input class="chooseproductfld"  data-id="${product.remote_id}" data-numsku="${skusCount}" type="checkbox" id="product_${product.id}" onchange="checkproduct(this, '${product.remote_id}')"  checked></td>
                                    <td>${product.remote_id}</td>
                                    <td>${product.title}</td>
                                    <td>${product?.store_product?.data}</td>
                                    <td>${skusCount}</td>
                                    <td><input class="form-control" id="discount_${product.id}" onblur="changeDiscount(${product.id},this)" type="text" value="${product.discount}"></input></td>
                                </tr>
                            `;
                        }

                    } else {
                        productRow = `
                        <tr data-id="${product.id}" id="ajaxorder_${product.id}" data-total="${skusCount}">
                            <td><input  class="chooseproductfld" data-id="${product.remote_id}" data-numsku="${skusCount}" type="checkbox" id="product_${product.id}" onchange="checkproduct(this, '${product.remote_id}')"></td>
                            <td>${product.remote_id}</td>
                            <td>${product.title}</td>
                            <td>
                            <img src="${product?.store_product?.data 
                                    ? JSON.parse(product.store_product.data)?.product?.image?.src 
                                    : './assets/img/image-default.png'}" class="img-thumbnail" alt="Image size chart" style="height:50px; object-fit: cover; width: 100%;">
                            </td>
                            <td>${skusCount}</td>
                            <td><input class="form-control" id="discount_${product.id}" onblur="changeDiscount(${product.id},this)" type="text" value="${product.discount}"></input></td>
                        </tr>
                    `;
                    }


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
    function donechoose() {
        //         // $store_id, $activity_id, $remote_id, $discount, $quantity_limit, $quantity_per_user
        var quantity_limit_value = $("#quantity_limit_value").val();
        var quantity_per_user_value = $("#quantity_per_user_value").val();
        $.ajax({
            url: '../post-product-flashdeals',
            method: 'POST',
            data: {
                activity_id: '{{$id}}',
                store_id: '{{$store_id}}',
                remote_ids: listproductjob,
                quantity_limit: quantity_limit_value,
                quantity_per_user: quantity_per_user_value,
            },
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
        $('#show_all_product').modal('hide');
    }
    function checkproduct(target, remote_id) {
        console.log(target.checked);
        console.log(remote_id);
        console.log(listproductinflashdeal.includes(remote_id));
        console.log(listproductjob.includes(remote_id));
        if (target.checked) {
            // Kiểm tra nếu remote_id đã có trong mảng listproductjob
            if (!listproductinflashdeal.includes(remote_id) && !listproductjob.includes(remote_id)) {
                numberskuchoose = parseInt(numberskuchoose) + parseInt(target.dataset.numsku);
                $("#numbersku").text(numberskuchoose);

                // Thêm remote_id vào mảng listproductjob
                listproductjob.push(remote_id);
                console.log(`Added ${remote_id} to listproductjob`);
            }

        } else {
            if (listproductjob.includes(remote_id)) {
                numberskuchoose = parseInt(numberskuchoose) - parseInt(target.dataset.numsku);
                $("#numbersku").text(numberskuchoose);
                // Xoá remote_id ra khỏi mảng listproductjob
                let index = listproductjob.indexOf(remote_id);
                if (index > -1) {
                    listproductjob.splice(index, 1);  // Xoá 1 phần tử tại vị trí index
                }
                console.log(`Removed ${remote_id} from listproductjob`);
            }
        }

        // Kiểm tra kết quả
        console.log(listproductjob);
        if(numberskuchoose > 10000){
            // alert("Số sku vượt quá 10000");
            target.checked = false;
            numberskuchoose = parseInt(numberskuchoose) - parseInt(target.dataset.numsku);
            $("#numbersku").text(numberskuchoose);
            let index = listproductjob.indexOf(remote_id);
            if (index > -1) {
                listproductjob.splice(index, 1);  // Xoá 1 phần tử tại vị trí index
            }
        }
    }


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
                alert(response.responseJSON.message);
            }
        });
    }
    function sync_flashdeal(id) {
        $.ajax({
            url: '../sync-flash-deal/' + id,
            method: 'POST',
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
    function syncproductflashdeal() {
        $.ajax({
            url: '../sync-product-flashdeal',
            method: 'POST',
            data: {
                flashdeal_id: '{{$id}}',
                store_id: '{{$store_id}}',
            },
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
    function checkallproductfld(target) {
        const product_flds = $('[id^="product_fld["]');
        console.log("product_flds");
        console.log(product_flds);
        product_flds.each(function (index, element) {
            element.checked = $(target).prop('checked');
        });
    }
    function reup() {
        const product_flds = $('[id^="product_fld["]');
        console.log("product_flds");
        console.log(product_flds);
        const listreup = []
        product_flds.each(function (index, element) {
            if (element.dataset.status != 'success') {
                listreup.push(element.dataset.id);
                element.checked = true;
            }
        });
        // console.log(listreup);
        // var discount_value = $("#re_discount_value").val();
        // var quantity_limit_value = $("#re_quantity_limit_value").val();
        // var quantity_per_user_value = $("#re_quantity_per_user_value").val();
        $.ajax({
            url: '../re-post-product-flashdeals',
            method: 'POST',
            data: {
                activity_id: '{{$id}}',
                store_id: '{{$store_id}}',
                ids: listreup,
                // discount: discount_value,
                // quantity_limit: quantity_limit_value,
                // quantity_per_user: quantity_per_user_value,
            },
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