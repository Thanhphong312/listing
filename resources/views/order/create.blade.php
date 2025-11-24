@extends('layouts.app')

@section('page-title', $title)
@section('page-heading', $title)

@section('breadcrumbs')
<li class="breadcrumb-item">
    <span>{{$title}}</span>
</li>
@stop

@section('message')
@include('partials.messages')
@endsection

@section('content')
<div class="element-box">
    <div class="card">
        <div class="card-body">

            <div class="row">

                <div class="col-12 row">
                    @if($type == "label")
                        @include('partials.orders.create-label', ['stores' => $stores])
                    @else
                        @include('partials.orders.create-shipping', ['stores' => $stores])
                    @endif
                    <div class="col-3">
                        <div class="form-group">
                            <label for="ref_id">Ref id</label>
                            <input type="text" class="form-control " id="ref_id"></input>
                        </div>
                    </div>
                </div>

                <div class="m-3 col-12">
                    <button type="button" class="btn btn-success" onclick="addItem()">+ Add item</button>
                </div>

                <div id="add_order" class="col-12 row">

                    <div class="col-12 row">
                        <div class="col-12 border p-3">
                            <div class="input-group col-6 mt-3 mb-3 ">
                                <span class="input-group-text">Product Name</span>
                                <input class="form-control" type="text" id="product_name[0]"></input>
                            </div>
                        </div>

                        <div class="col-3 border p-3">
                            PRODUCT

                            <div class="form-group m-2">
                                <select class="form-control form-select" id="type[0]" onchange="getType('0', this)" aria-label="Default select example">
                                    <option selected>Type</option>
                                    <option value="t-shirt">T-shirt</option>
                                    <option value="hoodie">Hoodie</option>
                                    <option value="sweatshirt">Sweatshirt</option>
                                </select>
                            </div>
                            <div class="form-group m-2">
                                <select class=" form-control form-select" id="color[0]" onchange="getColor('0', this)"
                                    aria-label="Default select example">
                                    <option selected>Color</option>
                                </select>
                            </div>
                            <div class="form-group m-2">
                                <select class=" form-control form-select" id="size[0]" onchange="getSize('0', this)"
                                    aria-label="Default select example">
                                    <option selected>Size</option>
                                </select>
                            </div>
                            <div class="form-group m-2">
                                <div class="input-group ">
                                    <span class="input-group-text">Variant ID</span>
                                    <input class="form-control" id="variantId[0]" name="variantId[0]"
                                        onchange="changeVariant(this)" disabled></input>
                                </div>
                            </div>
                            <div class="form-group m-2">
                                <div class="input-group ">
                                    <span class="input-group-text">Quantity</span>
                                    <input class="form-control" type="number" id="quantity[0]"
                                        onchange="changeVariant(this)" value="1"></input>
                                </div>
                            </div>

                            <!-- <input class="form-control m-2"></input> -->

                        </div>
                        <div class="col-3 border p-3">
                            MOCKUP
                            <div class="m-2">
                                <label for="formFile" class="form-label">Mockup</label>
                                <input type="text" class="form-control" id="mockup[0]"
                                    onchange="changeVariant(this)"></input>
                            </div>
                            <div class="m-2">
                                <label for="formFile" class="form-label">Mockup back</label>
                                <input type="text" class="form-control" id="mockup_back[0]"
                                    onchange="changeVariant(this)"></input>
                            </div>
                        </div>
                        <div class="col-2 border p-3">
                            Print price
                            <input class="form-control m-2" id="base_price[0]" onchange="changePrintPrice(this)"
                                disabled></input>
                            <!-- Shipping price -->
                            <input class="form-control m-2" id="shipping_fee[0]" disabled></input>
                            <input hidden class="form-control m-2" id="additional_shipping_fee[0]"></input>
                        </div>
                        <!-- <div class="col-2 border p-3">
                            TOTAL COST
                            <input class="form-control m-2" id="total_cost[0]"></input>
                        </div> -->
                        <div class="col-3 border p-3">
                            DESIGN
                            <div class="m-2">
                                <label for="formFile" class="form-label">Front</label>
                                <input class="form-control" type="text" id="design_front[0]"
                                    onchange="changeVariant(this)">

                            </div>

                            <div class="m-2">
                                <label for="formFile" class="form-label">Back</label>
                                <input class="form-control" type="text" id="design_back[0]"
                                    onchange="changeVariant(this)">
                            </div>

                            <div class="m-2">
                                <label for="formFile" class="form-label">Sleeve Right</label>
                                <input class="form-control" type="text" id="design_sleeve_right[0]"
                                    onchange="changeVariant(this)">

                            </div>

                            <div class="m-2">
                                <label for="formFile" class="form-label">Sleeve Left</label>
                                <input class="form-control" type="text" id="design_sleeve_left[0]"
                                    onchange="changeVariant(this)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 m-2 border-light d-flex align-items-center">

                Shipping cost:
                <input class="form-control m-2 col-3" id="shipping_cost" readonly></input>
                Print cost:
                <input class="form-control m-2 col-3" id="print_cost" readonly value="0"></input>
                Total cost:
                <input class="form-control m-2 col-3" id="total_cost" readonly></input>
                <div>
                    <div class="m-2">
                        <button type="button" id="postOrder" class="btn btn-primary"
                            onclick="postOrder()">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        @stop
        @section('scripts')
        <script>
            let numItem = 1;
            function validateRequiredFieldShippings() {
                var requiredFields = [
                    { id: 'shipping_fullname', name: 'Full Name' },
                    // { id: 'shipping_phone', name: 'Phone' },
                    { id: 'shipping_address_1', name: 'Address 1' },
                    { id: 'shipping_city', name: 'City' },
                    { id: 'shipping_state', name: 'State' },
                    { id: 'shipping_zipcode', name: 'Zip Code' },
                    { id: 'shipping_country', name: 'Country' },
                    { id: 'shopping_store', name: 'store' },
                    { id: 'ref_id', name: 'ref_id' },
                ];

                for (var i = 0; i < requiredFields.length; i++) {
                    var field = document.getElementById(requiredFields[i].id);
                    if (!field || !field.value.trim()) {
                        alert(requiredFields[i].name + ' is required.');
                        field.focus();
                        return false;
                    }
                }
                return true;
            }
            function validateRequiredFieldLabels() {
                var requiredFields = [
                    { id: 'label', name: 'Labels' },
                    { id: 'shopping_store', name: 'store' },
                    { id: 'ref_id', name: 'ref_id' },
                ];

                for (var i = 0; i < requiredFields.length; i++) {
                    var field = document.getElementById(requiredFields[i].id);
                    if (!field || !field.value.trim()) {
                        alert(requiredFields[i].name + ' is required.');
                        field.focus();
                        return false;
                    }
                }
                return true;
            }
            function validateProductNames(i) {
                var productName = document.getElementById('product_name[' + i + ']').value.trim();
                if (!productName) {
                    alert('Please enter a product name for item ' + (i + 1));
                    return false;
                }

                return true;
            }
            function validateVariant(i) {
                var productName = document.getElementById('variantId[' + i + ']').value.trim();
                if (!productName) {
                    alert('Please choose product gen variant id ' + (i + 1));
                    return false;
                }

                return true;
            }
            function validateMockups(i) {
                var mockup = document.getElementById('mockup[' + i + ']');
                var mockupBack = document.getElementById('mockup_back[' + i + ']');

                if ((!mockup) && (!mockupBack)) {
                    alert('Please provide at least one mockup (front or back) for item ' + (i + 1));
                    mockup.focus();
                    return false;
                }

                return true;
            }
            function validateDesigns(i) {
                var designFront = document.getElementById('design_front[' + i + ']');
                var designBack = document.getElementById('design_back[' + i + ']');
                var designSleeveLeft = document.getElementById('design_sleeve_left[' + i + ']');
                var designSleeveRight = document.getElementById('design_sleeve_right[' + i + ']');

                if ((!designFront) &&
                    (!designBack) &&
                    (!designSleeveLeft) &&
                    (!designSleeveRight)) {
                    alert('Please provide at least one design (front, back, sleeve left, or sleeve right) for item ' + (i + 1));
                    designFront.focus();
                    return false;
                }

                return true;
            }


            function validateBeforePost(numItem) {
                // Perform validations here
                var validationFailed = false; // Flag to track validation failure
                console.log('numItem', numItem);
                for (var i = 0; i < numItem; i++) {
                    if (!validateProductNames(i)) {
                        validationFailed = true;
                        break;
                    }
                    if (!validateVariant(i)) {
                        validationFailed = true;
                        break;
                    }
                    if (!validateMockups(i)) {
                        validationFailed = true;
                        break;
                    }
                    if (!validateDesigns(i)) {
                        validationFailed = true;
                        break;
                    }
                }

                if (validationFailed) {
                    // document.getElementById("postOrder").disabled = false;
                    return false; // Validation failed
                } else {
                    return true; // Validation passed
                }
            }

            function postOrder() {
                var shipping_cost = document.getElementById('shipping_cost').value;
                var print_cost = document.getElementById('print_cost').value;
                var totalCost = document.getElementById('total_cost').value;
                if (!print_cost || !totalCost || !shipping_cost) {
                    alert('Please add reload page create order have cost.');
                    return;
                }

                var ref_id = document.getElementById('ref_id').value;
                var api_key = document.getElementById('shopping_store').value;

                let shipping_fullname = null;
                let shipping_email = null;
                let shipping_phone = null;
                let shipping_address_1 = null;
                let shipping_address_2 = null;
                let shipping_city = null;
                let shipping_state = null;
                let shipping_zipcode = null;
                let shipping_country = null;
                let labelFile = null;

                var validationFailed = validateBeforePost(numItem); // Flag to track validation failure
                if (!validationFailed) {
                    return;
                }

                var orderJson = {
                    "ref_id": ref_id,
                    "api_key": api_key,
                    "order_status": "new_order",
                    "shipping_method": "standard",
                    "line_items": []
                };

                @if($type == "shipping")
                    if (!validateRequiredFieldShippings()) {
                        return;
                    }
                    shipping_fullname = document.getElementById('shipping_fullname').value ?? null;
                    shipping_email = document.getElementById('shipping_email').value ?? null;
                    shipping_phone = document.getElementById('shipping_phone').value ?? "123456789";
                    shipping_address_1 = document.getElementById('shipping_address_1').value ?? null;
                    shipping_address_2 = document.getElementById('shipping_address_2').value ?? null;
                    shipping_city = document.getElementById('shipping_city').value ?? null;
                    shipping_state = document.getElementById('shipping_state').value ?? null;
                    shipping_zipcode = document.getElementById('shipping_zipcode').value ?? null;
                    shipping_country = document.getElementById('shipping_country').value ?? null;
                    orderJson.address = {
                        "name": shipping_fullname ?? null,
                        "phone": shipping_phone ?? "123456789",
                        "street1": shipping_address_1 ?? null,
                        "street2": shipping_address_2 ?? null,
                        "city": shipping_city ?? null,
                        "state": shipping_state ?? null,
                        "zip": shipping_zipcode ?? null,
                        "country": shipping_country ?? null,
                    };
                @else
                    if (!validateRequiredFieldLabels()) {
                        return;
                    }
                    labelFile = document.getElementById('label').value;
                    orderJson.shipping_label = labelFile;
                @endif

                

                for (var i = 0; i < numItem; i++) {
                    var variantId = document.getElementById('variantId[' + i + ']').value;
                    var product_name = document.getElementById('product_name[' + i + ']').value;
                    var quantity = document.getElementById('quantity[' + i + ']').value;
                    var basePrice = document.getElementById('base_price[' + i + ']').value;

                    var mockupFile = document.getElementById('mockup[' + i + ']').value ?? null;
                    var mockupBackFile = document.getElementById('mockup_back[' + i + ']').value ?? null;
                    var designFront = document.getElementById('design_front[' + i + ']').value ?? null;
                    var designBack = document.getElementById('design_back[' + i + ']').value ?? null;
                    var designSleeveLeft = document.getElementById('design_sleeve_left[' + i + ']').value ?? null;
                    var designSleeveRight = document.getElementById('design_sleeve_right[' + i + ']').value ?? null;

                    var lineItem = {
                        "variant_id": variantId,
                        "product_name": product_name,
                        "quantity": quantity,
                        "mockup": mockupFile,
                        "mockup_back": mockupBackFile,
                        "print_files": []
                    };

                    if (designFront) {
                        lineItem.print_files.push({
                            "key": "front",
                            "url": designFront
                        });
                    }
                    if (designBack) {
                        lineItem.print_files.push({
                            "key": "back",
                            "url": designBack
                        });
                    }
                    if (designSleeveLeft) {
                        lineItem.print_files.push({
                            "key": "sleeve_left",
                            "url": designSleeveLeft
                        });
                    }
                    if (designSleeveRight) {
                        lineItem.print_files.push({
                            "key": "sleeve_right",
                            "url": designSleeveRight
                        });
                    }

                    orderJson.line_items.push(lineItem);
                }

                console.log('orderJson', orderJson);

                if (validationFailed) {

                    // Validations passed, proceed with posting the order
                    $.ajax({
                        url: '/api/order',
                        type: 'POST',
                        data: JSON.stringify(orderJson),
                        contentType: 'application/json', // Set contentType to 'application/json' since we're sending JSON data
                        processData: false, // Prevent jQuery from processing the data
                        success: function (response) {
                            const json = JSON.parse(response); // No need to parse the response if it is already a JSON object
                            // console.log(json.data);
                            window.location.href = '/orders?order_id='+json.data;
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText); // Log the actual error response
                            // Handle specific error cases here
                            alert('An error occurred: ' + xhr.responseText);
                        }
                    });


                } else {
                    // Validation failed, display an error message or take appropriate action
                    console.log('Validation failed. Please check the form.');
                }

            }
            // alert("You selected: ");
            function getType(id, item) {
                // Lấy giá trị đã chọn
                var selectedValue = document.getElementById('type[' + id + ']').value;
                $.ajax({
                    url: '/api/products?type=' + selectedValue,
                    type: 'GET',
                    success: function (response) {
                        console.log(JSON.parse(response).data);
                        var variants = JSON.parse(response).data;
                        var colors = getAllSameColors(variants);
                        console.log(colors);
                        var colorSelect = document.getElementById('color[' + id + ']');
                        colorSelect.innerHTML = '<option selected>Color</option>';
                        colors.forEach(function (color) {
                            var option = document.createElement('option');
                            option.value = color;
                            option.text = color;
                            colorSelect.appendChild(option);
                        });
                        changeVariant(item);

                    }
                });
            }

            function getColor(id, item) {
                var selectedType = document.getElementById('type[' + id + ']').value;
                var selectedValue = document.getElementById('color[' + id + ']').value;
                console.log(selectedType);
                console.log(selectedValue);
                $.ajax({
                    url: '/api/products?type=' + selectedType + "&color=" + selectedValue,
                    type: 'GET',
                    success: function (response) {
                        console.log(JSON.parse(response).data);
                        var variants = JSON.parse(response).data;
                        var sizes = getAllSameSizes(variants);
                        console.log(sizes);
                        var sizeSelect = document.getElementById('size[' + id + ']');
                        sizeSelect.innerHTML = '<option selected>Size</option>';
                        sizes.forEach(function (size) {
                            var option = document.createElement('option');
                            option.value = size;
                            option.text = size;
                            sizeSelect.appendChild(option);
                        });
                        changeVariant(item);
                    }
                });

            }

            function getSize(id, item) {
                var selectedType = document.getElementById('type[' + id + ']').value;
                var selectedColor = document.getElementById('color[' + id + ']').value;
                var selectedValue = document.getElementById('size[' + id + ']').value;
                console.log(selectedType);
                console.log(selectedColor);
                console.log(selectedValue);
                $.ajax({
                    url: '/api/products?type=' + selectedType + "&color=" + selectedColor + "&size=" + selectedValue,
                    type: 'GET',
                    success: function (response) {
                        console.log(JSON.parse(response).data);
                        var variants = JSON.parse(response).data;
                        var variantId = variants[0].variant_id;
                        console.log(variantId);
                        document.getElementById('variantId[' + id + ']').value = variantId;
                        changeVariant(item);
                    }
                });

            }

            function addItem() {
                var addOrder = document.getElementById('add_order');
                var div = document.createElement('div');
                div.className = 'col-12 row';
                div.innerHTML = `
                <div class="col-12 border p-3">
                            <div class="input-group col-6 mt-3 mb-3 ">
                                <span class="input-group-text">Product Name</span>
                                <input class="form-control" type="text" id="product_name[` + numItem + `]"></input>
                            </div>
                        </div>
        <div class="col-3 border p-3">
            PRODUCT
            <div class="form-group m-2">
                <select class="form-control form-select" id="type[` + numItem + `]" onchange="getType(` + numItem + `,this)"aria-label="Default select example">
                    <option selected>Type</option>
                    <option value="t-shirt">T-shirt</option>
                    <option value="hoodie">Hoodie</option>
                    <option value="sweatshirt">Sweatshirt</option>
                </select>
            </div>
            <div class="form-group m-2">
                <select class=" form-control form-select" id="color[` + numItem + `]" onchange="getColor(` + numItem + `,this)" aria-label="Default select example">
                    <option selected>Color</option>
                </select>
            </div>
            <div class="form-group m-2">
                <select class=" form-control form-select" id="size[` + numItem + `]" onchange="getSize(` + numItem + `,this)" aria-label="Default select example">
                    <option selected>Size</option>
                </select>
            </div>
            <div class="form-group m-2">
                <div class="input-group ">
                    <span class="input-group-text">Variant ID</span>
                    <input class="form-control" id="variantId[` + numItem + `]"  onchange="changeVariant(this)" disabled ></input>
                </div>
            </div>
            <div class="form-group m-2">
                <div class="input-group ">
                    <span class="input-group-text">Quantity</span>
                    <input class="form-control" type="number" id="quantity[` + numItem + `]" onchange="changeVariant(this)" value="1"></input>
                </div>
            </div>
        </div>
        <div class="col-3 border p-3">
            MOCKUP
            <div class="m-2">
                <label for="formFile" class="form-label">Mockup</label>
                <input type="text" class="form-control" id="mockup[` + numItem + `]" onchange="changeVariant(this)"></input>
            </div>
            <div class="m-2">
                <label for="formFile" class="form-label">Mockup back</label>
                <input type="text" class="form-control" id="mockup_back[` + numItem + `]" onchange="changeVariant(this)"></input>
            </div>
        </div>
        <div class="col-2 border p-3">
            Print price
            <input class="form-control m-2" id="base_price[` + numItem + `]" onchange="changePrintPrice(this)" disabled></input>
            <input class="form-control m-2" id="shipping_fee[` + numItem + `]" disabled></input>
            <input hidden class="form-control m-2" id="additional_shipping_fee[` + numItem + `]"></input>
        </div>
        <!-- <div class="col-2 border p-3">
            TOTAL COST
            <input class="form-control m-2" id="total_cost[` + numItem + `]"></input>
        </div> -->
        <div class="col-3 border p-3">
            DESIGN
            <div class="m-2">
                <label for="formFile" class="form-label">Front</label>
                <input class="form-control" type="text" id="design_front[` + numItem + `]" onchange="changeVariant(this)">

            </div>

            <div class="m-2">
                <label for="formFile" class="form-label">Back</label>
                <input class="form-control" type="text" id="design_back[` + numItem + `]" onchange="changeVariant(this)">
            </div>
            <div class="m-2">
                <label for="formFile" class="form-label">Sleece right</label>
                <input class="form-control" type="text" id="design_sleeve_right[` + numItem + `]" onchange="changeVariant(this)">

            </div>

            <div class="m-2">
                <label for="formFile" class="form-label">Sleece left</label>
                <input class="form-control" type="text" id="design_sleeve_left[` + numItem + `]" onchange="changeVariant(this)">
            </div>
        </div>
        `;
                addOrder.appendChild(div);
                numItem++;

            }

            function getAllSameColors(variants) {
                // Tạo một mảng mới để lưu trữ các màu sắc duy nhất
                var uniqueColors = [];

                // Lặp qua mỗi biến thể và thêm màu sắc vào mảng duy nhất nếu nó chưa tồn tại trong mảng
                variants.forEach(function (variant) {
                    if (!uniqueColors.includes(variant.color)) {
                        uniqueColors.push(variant.color);
                    }
                });

                // Trả về mảng các màu sắc duy nhất
                return uniqueColors;
            }

            function getAllSameSizes(variants) {
                // Tạo một mảng mới để lưu trữ các màu sắc duy nhất
                var uniqueSizes = [];

                // Lặp qua mỗi biến thể và thêm màu sắc vào mảng duy nhất nếu nó chưa tồn tại trong mảng
                variants.forEach(function (variant) {
                    if (!uniqueSizes.includes(variant.size)) {
                        uniqueSizes.push(variant.size);
                    }
                });

                // Trả về mảng các màu sắc duy nhất
                return uniqueSizes;
            }
            general_key(0);

            function general_key(id) {
                var key = generateApiKey();
                $("#ref_id").val(key);
            }

            function generateApiKey() {
                var groups = [
                    generateGroupchar(4),
                    generateGroupnumber(12),
                ];
                return "manu_" + groups.join('_');
            }

            function generateGroupnumber(length) {
                var characters = '0123456789';
                var result = '';
                for (var i = 0; i < length; i++) {
                    result += characters.charAt(Math.floor(Math.random() * characters.length));
                }
                return result;
            }

            function generateGroupchar(length) {
                var characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                var result = '';
                for (var i = 0; i < length; i++) {
                    result += characters.charAt(Math.floor(Math.random() * characters.length));
                }
                return result;
            }


            function changeVariant(item) {
                var i = item.id.match(/\d+/)[0]
                console.log('change variant', i);
                var variantId = $("#variantId\\[" + i + "\\]").val();
                var designFront = $("#design_front\\[" + i + "\\]").val();
                var designBack = $("#design_back\\[" + i + "\\]").val();
                var designSleeveLeft = $("#design_sleeve_left\\[" + i + "\\]").val();
                var designSleeveRight = $("#design_sleeve_right\\[" + i + "\\]").val();
                var designBack = $("#design_back\\[" + i + "\\]").val();
                var quantity = $("#quantity\\[" + i + "\\]").val();

                var base_price = $("#base_price\\[" + i + "\\]");
                var shipping_fee = $("#shipping_fee\\[" + i + "\\]");
                var additional_shipping_fee = $("#additional_shipping_fee\\[" + i + "\\]");

                var shipping_cost = $("#shipping_cost");
                var print_cost = $("#print_cost");
                var total_cost = $("#total_cost");

                var label = document.getElementById('label');
                console.log('label', label);
                if (variantId && quantity && (designFront || designBack || designSleeveLeft || designSleeveRight)) {

                    $.ajax({
                        url: '/order/basecost',
                        type: 'get',
                        data: {
                            item: i,
                            variantId: variantId,
                            designFront: designFront,
                            designBack: designBack,
                            designSleeveLeft: designSleeveLeft,
                            designSleeveRight: designSleeveRight,
                            type: "{{ $type }}",
                            private_seller: "{{ Auth::user()->private_seller }}",
                            quantity: quantity,
                        },
                        success: function (response) {
                            console.log('data cost', response);

                            base_price.val(response.print_basecost);
                            shipping_fee.val(response.shipping_fee);

                            var printBaseCostVal = parseFloat(response.print_basecost);

                            // additional_shipping_fee.val(response.additional_shipping_fee);

                            print_cost.val((numPrice().totalOrder).toFixed(2));
                            shipping_cost.val(numPrice().numShipFee.toFixed(2));
                            total_cost.val((numPrice().numShipFee + numPrice().totalOrder).toFixed(2));


                        },
                        error: function (error) {
                            console.error(error);
                            alert('not found variant ID')
                        }
                    });
                } else {
                    $("#base_price\\[" + i + "\\]").val('');
                    shipping_cost.val('');
                    total_cost.val('');
                }


            }

            function changePrintPrice(item) {
                // console.log(item.id.match(/\d+/)[0])
                var i = item.id.match(/\d+/)[0]
                var variantId = $("#variantId\\[" + i + "\\]").val();
                var designFront = $("#design_front\\[" + i + "\\]").val();
                var designBack = $("#design_back\\[" + i + "\\]").val();
                var quantity = $("#quantity\\[" + i + "\\]").val();


                var shipping_cost = $("#shipping_cost");
                var total_cost = $("#total_cost");

                if (variantId && quantity && (designFront || designBack)) {
                    shipping_cost.val(numPrice().numShipFee.toFixed(2));
                    total_cost.val((numPrice().numShipFee + numPrice().totalOrder).toFixed(2));
                } else {
                    shipping_cost.val('');
                    total_cost.val('');
                }


            }

            function numPrice() {
                let quantityOrder = 0;
                let totalOrder = 0;
                let getShipFee = 0;
                // let getAdditionalShippingFee = 0;
                for (var a = 0; a < numItem; a++) {
                    var shipping_fee = $("#shipping_fee\\[" + a + "\\]").val();
                    var quantity = $("#quantity\\[" + a + "\\]").val();
                    var base_price = $("#base_price\\[" + a + "\\]").val();
                    if (shipping_fee == '' || quantity == '' || base_price == '') {
                        continue;
                    }
                    $type = "{{$_GET['type']}}"
                    if ($type == 'label') {
                        var index = 0;
                        getShipFee = parseFloat($("#shipping_fee\\[" + index + "\\]").val());
                    } else {
                        getShipFee += parseFloat($("#shipping_fee\\[" + a + "\\]").val());
                    }
                    quantityOrder += parseFloat($("#quantity\\[" + a + "\\]").val())
                    totalOrder += parseFloat($("#base_price\\[" + a + "\\]").val())
                }


                return {
                    numShipFee: getShipFee,
                    totalOrder: totalOrder,
                    quantityOrder: quantityOrder
                }
            }
        </script>
        @stop