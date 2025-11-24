@extends('layouts.app')

@section('page-title', __('Edit Products'))
@section('page-heading', __('Edit Products'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Edit Products')
</li>
@stop
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
<style>
    .mydropdown {
        margin-top: 63px !important;
        top: auto !important;
        left: auto !important;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        z-index: 1000;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-item {
        cursor: pointer;
    }

    .dropdown-item:hover {
        background-color: #2689ff;
        color: white;
    }

    .dropdown-menu {
        max-height: 600px;
        overflow-y: auto;
    }

    #categoryContainer .col {
        margin-right: 15px;
        padding: 0;
    }

    .list-group-item {
        cursor: pointer;
        transition: background-color 0.2s, color 0.2s;
    }

    .list-group-item:hover,
    .list-group-item.active {
        background-color: #2689ff;
        color: #fff;
    }

    h6 {
        margin-bottom: 10px;
    }
</style>
@section('content')
<div class="element-box">
    <div class="card">
        <div class="card-body">
            @include('partials.messages')
            <div class="row">
                <div class="col-12 row">
                    <div class="col-12">

                        <div class="card-header row col-12"
                            style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                            <div class="form-group col-12">
                                <label for="editor">Name</label>
                                <input class="form-control" type="text" name="name" id="name" value="" >                            
                            </div>
                            <div class="form-group col-12">
                                <label for="editor">Description</label>
                                <input id="editor" type="hidden" name="description" value="{{ old('content', '') }}">
                                <trix-editor input="editor" row="4"></trix-editor>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="card-header row col-12"
                            style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                            <div class="form-group col-12">
                                <label>Categories</label>
                                <input type="text" class="form-control" id="categoryInput" placeholder="Categories" value="{{$breadcrumb}}"
                                    readonly data-toggle="dropdown">
                                <div class="dropdown-menu p-3 shadow" id="categoryDropdown">
                                    <div class="container row" id="categoryContainer">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" id="attributesContainer">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">

                        <div class="card-header row col-12"
                            style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                            <div class="col-10">
                                <label for="weight">Weight with Package (KILOGRAM)</label>
                                <input type="number" class="form-control" name="weight" id="weight"
                                    placeholder="Enter the product weight" value="0.113">
                            </div>
                            <div class="col-4 mt-2">
                                <label for="weight">Height (CENTIMETER)</label>
                                <input type="number" class="form-control" name="height" id="height" placeholder="height"
                                    value="5">
                            </div>
                            <div class="col-4 mt-2">
                                <label for="weight">Width (CENTIMETER)</label>
                                <input type="number" class="form-control" name="width" id="width" placeholder="width"
                                    value="15">
                            </div>
                            <div class="col-4 mt-2">
                                <label for="weight">Length (CENTIMETER)</label>
                                <input type="number" class="form-control" name="length" id="length" placeholder="length"
                                    value="15">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="card-header row col-12"
                            style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                            <!-- Form for uploading multiple designs -->
                            <div class="col-md-4">
                                <label for="select_size_chart" class="form-label">Choose size chart</label>
                                <select class="form-select"  id="select_size_chart"
                                    name="select_size_chart" onchange="chooseSizeChart(this)">
                                    <option data-image="" value="0">size chart...</option>
                                    @foreach ($sizecharts as $sizechart)
                                        <option data-image="{{$sizechart->url}}" value="{{$sizechart->id}}">
                                            {{$sizechart->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Preview selected files and images -->
                            <div class="col-12 mt-3" id="file-sizechart-preview"></div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="card-header row col-12"
                            style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                            <form class="col-10">
                                <div id="variantList">

                                </div>
                                <button type="button" class="btn btn-success px-3 py-2 rounded mx-1 mt-2" onclick="addNewVariant()">
                                        Add Variant
                                </button>
                                <button type="button" class="btn btn-primary px-3 py-2 rounded mx-1 mt-2" onclick="generate()">
                                        Create Variant List
                                </button>
                            </form>
                        </div>
                        <div class="card-header row col-12 mt-2"
                            style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                            <div class="col-12 row">
                                        <div id="filterVariant" class="col-6 row">

                                        </div>
                                        
                                        <div class="col-2">
                                            <select class="form-select" name="select_option_price" id="select_option_price" onchange="selectAddPrice(this)">
                                                <option value="1" selected>1</option>
                                                <option value="2">2</option>
                                            </select>
                                        </div>
                                        <div class="col-1">
                                            <input type="text" class="form-control" id="priceedit" aria-describedby="emailHelp" placeholder="Enter price">
                                        </div>
                                        <div class="col-2">
                                            <input type="text" class="form-control" id="quantityedit" aria-describedby="emailHelp" placeholder="Enter quantity">
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-success mr-1 mt-2" onclick="applyVariantFilter()">Apply</button>
                                        </div>
                            </div>
                            <form class="col-12 mt-2">
                                <table id="variantTable" class="table">
                                    <!-- The header and body rows will be dynamically inserted here -->
                                </table>
                            </form>
                        </div>
                        <div class="card-header row col-12 mt-2"
                                style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                                <div class="form-group col-4">
                                    <label for="name">Discount flashdeals</label>
                                    <input class="form-control" type="text" name="discount" id="discount" value="{{$templetes->discount}}">
                                </div>
                                <div class="mt-2 d-flex">
                                    <button class="btn btn-primary" onclick="publish(this)">Publish Product</button>
                                </div>
                        </div>
                    </div>
                </div>
              
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

    trix-editor {
        min-height: 200px;
        /* Adjust this value to your desired height */
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>

@section('scripts')
<script>
    let json = {!! $encodedJson !!};
    console.log(json);
    let selectedCategories = [];
    let idcategory = json.category_id;
    let selectedAttributes = []; // Mảng lưu trữ các thuộc tính đã chọn
    const description = json.description;
    let jsonArray = [];
    console.log(description);
    // Set the value of the hidden input linked to Trix
    $("#editor").val(description);
    // Trigger a change event to update Trix Editor's content
    document.querySelector("trix-editor").editor.loadHTML(description);
    const productID = randomId();
    console.log("product ID", productID)
    let varianttmp = json.skus;
    var VariantList = generateVariantList(varianttmp);

    const filter = ["All", "All", "All"];
    createOptions();
    createtableVariant(varianttmp, filter);

    function createtableVariant(variantlist, filter) {
        var table = document.getElementById("variantTable");

        // Clear the existing table content (both headers and rows)
        table.innerHTML = "";

        // Create the header row
        var header = table.createTHead();
        var headerRow = header.insertRow(0);
        console.log(variantlist);
        // Loop through optionVariants to create dynamic headers
        variantlist[0].sales_attributes.forEach(option => {
            const { name, value_name } = option;    
            var th = document.createElement("th");
            th.innerHTML = name;
            headerRow.appendChild(th);
        });

        // Add fixed columns: Price and Quantity
        var thPrice = document.createElement("th");
        thPrice.innerHTML = "Price";
        headerRow.appendChild(thPrice);

        var thQuantity = document.createElement("th");
        thQuantity.innerHTML = "Quantity";
        headerRow.appendChild(thQuantity);

        // Create the table body
        var tbody = table.createTBody();
            variantlist.forEach((variant, index) => {
                // Check if the variant matches the filter
                // If filter[0] is "All", we don't filter by Style, etc.
                
                    var row = tbody.insertRow();
                    let sales_attributes = variant.sales_attributes;
                    // For each variant, create a cell for each option (Style, Color, Size)
                    sales_attributes.forEach(option => {
                        const { name, value_name } = option;    
                        var cell = row.insertCell();
                        cell.innerHTML = value_name;
                    });

                    // Create Price and Quantity cells with editable inputs
                    var cellPrice = row.insertCell();
                    cellPrice.innerHTML = `<input type="text" value="${variant.price.amount}" class="price-input form-control" onchange="changePriceVariant('${index}', this)"/>`;

                    var cellQuantity = row.insertCell();
                    cellQuantity.innerHTML = `<input type="number" value="${variant.inventory[0].quantity}" class="quantity-input form-control " onchange="changeQuantityVariant('${index}', this)"/>`;

                    var cellDelete = row.insertCell();
                    cellDelete.innerHTML = `<span onclick="deleteVariant('', this)"><i class="fa fa-trash"></i></span>`;
                
            });
    }
    function hideinput(inputId, buttonElement) {
        // Xoá phần tử HTML
        const row = buttonElement.closest(".form-group");
        if (row) {
            row.remove();
        }

        // Tìm và xoá khỏi VariantList
        const key = inputId.replace("_variant", "").toLowerCase();

        const index = VariantList.findIndex(item => item.key.toLowerCase() === key);
        if (index !== -1) {
            VariantList.splice(index, 1);
            console.log("Updated VariantList:", VariantList);
        }
    }

    function changeValue(inputElement) {
        const inputId = inputElement.id; // ví dụ: "style_variant"
        const key = inputId.replace("_variant", "").toLowerCase();
        const newValue = inputElement.value;

        VariantList.forEach(variant => {
            if (variant.key.toLowerCase() === key) {
                variant.value = newValue;
            }
        });

        console.log("Updated VariantList:", VariantList);
    }
    function addNewVariant() {
        const timestamp = Date.now();
        const tempKey = 'custom_' + timestamp;

        // Thêm phần tử tạm vào VariantList
        VariantList.push({ key: tempKey, value: '' });

        const container = document.getElementById("variantList");

        const html = `
            <div class="form-group row align-items-center mt-2" id="variant-row-${tempKey}">
                <div class="col-sm-1">
                    <input type="checkbox" onclick="addImage(this, '${tempKey}')" id="input_add_image[${tempKey}]">
                </div>
                <div class="col-sm-3">
                    <input class="form-control" type="text" placeholder="Key"
                        onblur="updateVariantKey('${tempKey}', this)" id="input-key-${tempKey}">
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="${tempKey}_variant" id="${tempKey}_variant"
                            value="" placeholder="Value" onchange="changeValue(this)">
                </div>
                <div class="col-sm-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-danger" 
                        onclick="hideinput('${tempKey}_variant', this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        container.insertAdjacentHTML("beforeend", html);
    }


    function updateVariantKey(oldKey, inputElement) {
        const newKey = inputElement.value.trim();
        if (!newKey) return;

        const lowerOldKey = oldKey.toLowerCase();
        const lowerNewKey = newKey.toLowerCase();

        // 🔁 Cập nhật trong VariantList
        const variantIndex = VariantList.findIndex(v => v.key === oldKey);
        if (variantIndex === -1) return;

        VariantList[variantIndex].key = newKey;

        // 🔁 Cập nhật phần tử dòng trong DOM
        const row = document.getElementById(`variant-row-${lowerOldKey}`);
        if (row) {
            row.id = `variant-row-${lowerNewKey}`;

            // Cập nhật label nếu có
            const label = row.querySelector("label");
            if (label) {
                label.textContent = newKey;
                label.setAttribute("for", `${lowerNewKey}_variant`);
            }

            // Cập nhật input
            const valueInput = row.querySelector("input[placeholder='Value']");
            if (valueInput) {
                valueInput.name = `${lowerNewKey}_variant`;
                valueInput.id = `${lowerNewKey}_variant`;
            }

            // Cập nhật nút xóa
            const deleteButton = row.querySelector("button.btn-danger");
            if (deleteButton) {
                deleteButton.setAttribute("onclick", `hideinput('${lowerNewKey}_variant', this)`);
            }
        }
    }
    function changePriceVariant(index, input) {
        const value = input.value.trim();

        if (!jsonArray[index]) return;

        // Optional: parseFloat + check số
        const amount = parseFloat(value);
        if (!isNaN(amount)) {
            jsonArray[index].price.amount = amount.toFixed(2); // luôn giữ định dạng 2 chữ số
        } else {
            alert("Invalid price value.");
        }
    }

    function changeQuantityVariant(index, input) {
        const value = input.value.trim();

        if (!jsonArray[index]) return;

        const quantity = parseInt(value);
        if (!isNaN(quantity) && quantity >= 0) {
            // đảm bảo inventory tồn tại
            if (!Array.isArray(jsonArray[index].inventory)) {
                jsonArray[index].inventory = [{ quantity: 0 }];
            }
            jsonArray[index].inventory[0].quantity = quantity;
        } else {
            alert("Invalid quantity value.");
        }
    }
    function generateVariantList(skus) {
        const attributeMap = {};

        skus.forEach(sku => {
            sku.sales_attributes.forEach(attr => {
                const key = attr.name;
                const value = attr.value_name;

                if (!attributeMap[key]) {
                    attributeMap[key] = new Set();
                }
                attributeMap[key].add(value);
            });
        });

        const VariantList = Object.entries(attributeMap).map(([key, valueSet]) => {
            const valuesArray = Array.from(valueSet);
            return {
                key,
                value: valuesArray.join(", ")
            };
        });

        return VariantList;
    }

    function createOptions() {
        const container = document.getElementById("variantList");
        container.innerHTML = ""; // Xóa nội dung cũ (nếu có)

        VariantList.forEach(variant => {
            const { key, value } = variant;
            const lowerKey = key.toLowerCase();

            const html = `
                <div class="form-group row align-items-center mt-2" id="variant-row-${lowerKey}">
                    <div class="col-sm-1">
                        <input type="checkbox"
                            onclick="addImage(this, '${lowerKey}')" id="input_add_image[${lowerKey}]" data-id="${lowerKey}">
                    </div>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" value="${key}" placeholder="Key" 
                            onblur="updateVariantKey('${key}', this)">
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="${lowerKey}_variant" id="${lowerKey}_variant"
                            value="${value}" placeholder="Value" onchange="changeValue(this)">
                    </div>
                    <div class="col-sm-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-danger" 
                            onclick="hideinput('${lowerKey}_variant', this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div id="image_list_variant_${lowerKey}"><div>
                </div>
            `;
            container.insertAdjacentHTML("beforeend", html);
        });
    }
    function addImage(element, key){
        if(element.checked){
            let variantValue = $(`#${key}_variant`).val();
            const listVariant = variantValue.split(",");
            let html = ''
            listVariant.forEach(value => {
                html += `
                    <div class="col-12 m-2" style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                        <div class="form-group col-12">
                            <label for="designs" style="font-weight: bold;">Choose image variant color(${value.trim()})</label>
                            <input type="file" class="form-control" name="image_color[${value.trim()}]" id="image_${key}_[${value.trim()}]" onchange="uploadAndPreviewImage('${value.trim()}','${key}')" >
                        </div>
                        <div class="m-3 col-12" id="file-image-preview[${key}][${value.trim()}]">
                            
                        </div>
                    </div>
                `;
            });
            
            $(`#image_list_variant_${key}`).html(html);
        }else{
            $(`#image_list_variant_${key}`).html('');
        }
    }
    generateEdit()
    function generateEdit() {
        let filterVariant = document.getElementById("filterVariant");
        filterVariant.innerHTML = ""
        VariantList.forEach((variant, index) => {
            const { key, value } = variant;
            // Tạo thẻ div bao bọc
            const div = document.createElement("div");
            div.className = "col-md-4";

            // Tạo thẻ select
            const select = document.createElement("select");
            select.className = "form-select";
            select.id = `styleedit-${index}`;
            select.setAttribute("onchange", `changeFilter(this, ${index})`);

            // Thêm option mặc định
            const defaultOption = document.createElement("option");
            defaultOption.selected = true;
            defaultOption.value = "All";
            defaultOption.textContent = `${key}...`;
            select.appendChild(defaultOption);

            // Tạo các option từ values (giả sử là string phân cách bởi dấu phẩy)
            value.split(',').forEach(value => {
                const option = document.createElement("option");
                option.value = value.trim();
                option.textContent = value.trim();
                select.appendChild(option);
            });

            // Gắn select vào div, rồi thêm vào filterVariant
            div.appendChild(select);
            filterVariant.appendChild(div);
        });

        const parsedVariants = VariantList.map(variant => ({
            key: variant.key,
            values: variant.value.split(",").map(v => v.trim())
        }));

        function combineVariants(index = 0, current = []) {
            if (index === parsedVariants.length) {
                return [current];
            }

            const { key, values } = parsedVariants[index];
            let result = [];

            values.forEach(value => {
                result = result.concat(combineVariants(index + 1, [...current, { key, value }]));
            });

            return result;
        }

        const combinations = combineVariants();
        jsonArray = [];
        combinations.forEach(variant => {
            const jsonObject = {
                price: {
                    currency: "USD",
                    amount: ""
                },
                sales_attributes: [],
                seller_sku: "",
                inventory: [
                    {
                        quantity: 0,
                        warehouse_id: ""
                    }
                ]
            };

            variant.forEach(e => {
                const { key, value } = e;    
                jsonObject.sales_attributes.push({
                    name: key,
                    value_name: value.trim()
                });
            });
            jsonArray.push(jsonObject);

        });
        $("#name").val('{{$name}}');
        let imageSizeChart = json.size_chart.image;
        // $('select[name="set"]').val(json.product.set)
        // $('select[name="category"]').val(json.product.category)
        $("#select_size_chart option").each(function () {
            let optionImage = $(this).data("image");

            if (optionImage === imageSizeChart) {
                $(this).prop("selected", true);
                var previewDiv = document.getElementById('file-sizechart-preview');

                // Clear any previous content in the file-preview
                previewDiv.innerHTML = '';

                // Create an image element to display the selected image
                var imgElement = document.createElement('img');
                imgElement.src = imageSizeChart;
                imgElement.id = 'file-sizechart-select';
                imgElement.alt = 'Size chart preview';
                imgElement.style.maxWidth = '150px'; // Ensure the image fits the preview area

                // Append the image to the preview div
                previewDiv.appendChild(imgElement);
            }
        });
    }
    function applyImageSku(jsonArray){
        let checkedCheckboxes = $("input[id^='input_add_image'][type='checkbox']:checked");

        console.log("checkedCheckboxes");
        console.log(checkedCheckboxes);
        checkedCheckboxes.each(function() {
            let id = $(this).attr("data-id");
            console.log(id.toLowerCase())
            jsonArray.forEach(element => {
                console.log(element);
                let key = element.sales_attributes.find(e => e.name.toLowerCase().replace(/\s+/g, '') === id.toLowerCase().replace(/\s+/g, ''));
                console.log(`#fileimagecolornew[${id}][${key.value_name.replace(/\s+/g, '')}]`);

                // Escape dấu [] trong selector
                let selector = `#fileimagecolornew\\[${id}\\]\\[${key.value_name.replace(/\s+/g, '')}\\]`;
                let sku_img = $(selector);

                if (sku_img.length && sku_img.attr('src')) {
                    console.log(sku_img.attr('src'));
                    // Thêm vào sales_attributes (nên dùng object, không phải array)
                    key.id = "100000";
                    key.sku_img = sku_img.attr('src')
                }
            });
        });
        console.log(jsonArray);
        
    }
    function publish(target) {
        // $(target).prop('disabled', true);
        const description = $("#editor").val();
        const discount = $("#discount").val();
        const name = $("#name").val();
        const imagesizechart = $('#file-sizechart-select').attr('src');
        applyImageSku(jsonArray);
        console.log("description");
        console.log(description);
        console.log("imagesizechart");
        console.log(imagesizechart);
        console.log("varianttmp");
        console.log(varianttmp);

        json.description = description;
        
        json.category_id = idcategory;
        json.package_weight.value = $("#weight").val();
        json.package_weight.unit = "KILOGRAM";
        json.package_dimensions.height = $("#height").val();
        json.package_dimensions.length = $("#length").val();
        json.package_dimensions.width = $("#width").val();
        json.package_dimensions.unit = "CENTIMETER";
        json.size_chart.image = imagesizechart;
        json.skus = jsonArray;
        json.product_attributes = selectedAttributes;

        console.log("json")
        console.log(json)
        var formData = new FormData();
        formData.append('json', JSON.stringify(json))
        formData.append('name', name)
        formData.append('discount', discount)

            $.ajax({
                url: './{{$templetes->id}}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // Handle the response, e.g., reload the page
                    if (JSON.parse(response).message) {
                        location.href = '../../templates';
                    }
                },
                error: function (response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
    }
    function generate() {
        let filterVariant = document.getElementById("filterVariant");
        filterVariant.innerHTML = ""
        VariantList.forEach((variant, index) => {
            const { key, value } = variant;
            // Tạo thẻ div bao bọc
            const div = document.createElement("div");
            div.className = "col-md-4";

            // Tạo thẻ select
            const select = document.createElement("select");
            select.className = "form-select";
            select.id = `styleedit-${index}`;
            select.setAttribute("onchange", `changeFilter(this, ${index})`);

            // Thêm option mặc định
            const defaultOption = document.createElement("option");
            defaultOption.selected = true;
            defaultOption.value = "All";
            defaultOption.textContent = `${key}...`;
            select.appendChild(defaultOption);

            // Tạo các option từ values (giả sử là string phân cách bởi dấu phẩy)
            value.split(',').forEach(value => {
                const option = document.createElement("option");
                option.value = value.trim();
                option.textContent = value.trim();
                select.appendChild(option);
            });

            // Gắn select vào div, rồi thêm vào filterVariant
            div.appendChild(select);
            filterVariant.appendChild(div);
        });

        const parsedVariants = VariantList.map(variant => ({
            key: variant.key,
            values: variant.value.split(",").map(v => v.trim())
        }));

        function combineVariants(index = 0, current = []) {
            if (index === parsedVariants.length) {
                return [current];
            }

            const { key, values } = parsedVariants[index];
            let result = [];

            values.forEach(value => {
                result = result.concat(combineVariants(index + 1, [...current, { key, value }]));
            });

            return result;
        }

        const combinations = combineVariants();
        jsonArray = [];
        combinations.forEach(variant => {
            const jsonObject = {
                price: {
                    currency: "USD",
                    amount: ""
                },
                sales_attributes: [],
                seller_sku: "",
                inventory: [
                    {
                        quantity: 0,
                        warehouse_id: ""
                    }
                ]
            };

            variant.forEach(e => {
                const { key, value } = e;    
                jsonObject.sales_attributes.push({
                    name: key,
                    value_name: value.trim()
                });
            });
            jsonArray.push(jsonObject);

        });

        createtableVariant(jsonArray, filter)
    }


    function uploadAndPreviewImage(color, key) {
        var fileInput = document.getElementById(`image_${key}_[${color}]`);
        var file = fileInput.files[0];
        var formData = new FormData();
        formData.append('image_color', file);

        // AJAX request to upload the image
        $.ajax({
            url: '../../products/upload-image-color',  // Replace with your actual upload URL
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Assuming response contains the image URL
                var imageUrl = response.url;
                var previewDiv = document.getElementById(`file-image-preview[${key}][${color}]`);

                // Clear any previous content in the preview div
                previewDiv.innerHTML = '';

                // Create an image element to display the uploaded image
                var imgElement = document.createElement('img');
                imgElement.src = imageUrl;
                imgElement.name = color;
                imgElement.id = `fileimagecolornew[${key}][${color.replace(/\s+/g, '')}]`;
                imgElement.alt = 'Size chart preview';
                imgElement.style.maxWidth = '150px'; // Ensure the image fits the preview area

                // Append the image to the preview div
                previewDiv.textContent = "New:";  
                previewDiv.appendChild(imgElement);
            },
            error: function(xhr, status, error) {
                console.error("Image upload failed: ", error);
            }
        });
    }

    
    function changeonlyprice(){
        json.product.variants.only_price = $("#only_price").val();
    }
    function changeonlyquantity(){
        json.product.variants.only_quantity = $("#only_quantity").val();
    }


    function applyVariantFilter() {
        const priceedit = parseFloat($("#priceedit").val()) || 0;
        const quantityedit = parseInt($("#quantityedit").val()) || 999;
        
        console.log("priceedit", priceedit);
        console.log("quantityedit", quantityedit);
        console.log("filter", filter);

        jsonArray.forEach(variant => {
            const attributes = variant.sales_attributes;

            let matched = true;
            for (let i = 0; i < filter.length; i++) {
                const filterVal = filter[i];
                const attrVal = attributes[i]?.value_name || '';

                if (filterVal !== 'All' && filterVal !== attrVal) {
                    matched = false;
                    break;
                }
            }

            if (matched) {
                variant.price.amount = priceedit.toString(); // Cập nhật giá
                if (variant.inventory.length > 0) {
                    variant.inventory[0].quantity = quantityedit; // Cập nhật số lượng
                }
                console.log("Updated variant:", variant);
            }
        });
        console.log(jsonArray);
        createtableVariant(jsonArray, filter);
    }
    function changePriceVatiant(style, color, size, target) {
        const priceedit = parseFloat($(target).val()) || 0;  // Ensure it's a number
        console.log("priceedit", priceedit);
        varianttmp.forEach((e) => {
            if ((e.option1.trim() === style.trim()) &&
                (e.option2.trim() === color.trim()) &&
                (e.option3.trim() === size.trim())) {

                e.price = cenvertPricefromSize(priceedit, e.option3.trim())
            }
        });

        console.log("Updated varianttmp:", varianttmp);
    }
    function changeQuantityVatiant(style, color, size, target) {
        const quantityedit = parseFloat($(target).val()) || 0;  // Ensure it's a number
        console.log("quantityedit", quantityedit);

        varianttmp.forEach((e) => {
            if ((e.option1.trim() === style.trim()) &&
                (e.option2.trim() === color.trim()) &&
                (e.option3.trim() === size.trim())) {
                e.quantity = quantityedit;
            }
        });

        console.log("Updated varianttmp:", varianttmp);
    }
    function deleteVariant(style, color, size, target) {
        // Filter out the variants that match the criteria
        varianttmp = varianttmp.filter((e) => {
            return !(
                (e.option1.trim() === style.trim()) &&
                (e.option2.trim() === color.trim()) &&
                (e.option3.trim() === size.trim())
            );
        });

        console.log("Updated varianttmp after deletion:", varianttmp);
        createtableVariant(optionVariants, varianttmp, filter);

    }
    function cenvertPricefromSize(price, size) {

        size = size.trim().toUpperCase();

        // Sử dụng biểu thức chính quy để trích xuất kích cỡ trong dấu ngoặc đơn
        const sizeMatch = size.match(/\(([^)]+)\)/);
        let extractedSize = 'S';
        if (!sizeMatch) {
            extractedSize = size;
        } else {
            extractedSize = sizeMatch[1];
        }

        var sizetemp = ['XS','S', 'M', 'L', 'XL', '2XL', '3XL', '4XL']
        var index = sizetemp.indexOf(extractedSize)
        var totalPrice = parseFloat(price) + parseFloat(priceConvert[index]??0);
        return totalPrice.toFixed(2);
    };

    function changeFilter(target, index) {
        const value = target.value;
        filter[index] = value
        createtableVariant(optionVariants, varianttmp, filter)
    }

    function addOptions(selectElementId, variantArray) {
        var selectElement = document.getElementById(selectElementId);
        selectElement.innerHTML = '';
        var option = document.createElement("option");
            option.value = 'All';
            option.text = 'All';
            selectElement.appendChild(option);
        variantArray.split(',').forEach(function (variant) {
            var option = document.createElement("option");
            option.value = variant.trim();
            option.text = variant.trim();
            selectElement.appendChild(option);
        });
       
    }
    function randomId() {
        var randomNumber = Math.floor(Math.random() * 100000000000000);
        return String(randomNumber).padStart(14, '0');
    }

    // Function to re-render file preview after any changes (addition/removal)
    function renderFilePreview() {
        let filePreview = document.getElementById('file-preview');
        filePreview.innerHTML = '';  // Clear the preview area

        if (selectedFiles.length > 0) {
            let row = document.createElement('div');
            row.classList.add('row');  // Create a Bootstrap row

            selectedFiles.forEach(function (file, index) {
                let col = document.createElement('div');
                col.classList.add('col-md-3', 'mb-3', 'position-relative');  // Column style with relative position for delete button

                if (file.type.startsWith('image/')) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        let image = new Image();
                        image.src = e.target.result;
                        image.classList.add('img-fluid');
                        image.style.objectFit = 'contain';
                        image.style.width = '200px';
                        image.style.height = '200px';
                        image.style.backgroundColor = "#bdbdbd";

                        let deleteBtn = document.createElement('button');
                        deleteBtn.innerHTML = '<i class="fa fa-trash"></i>';
                        deleteBtn.classList.add('btn', 'btn-danger', 'position-absolute', 'top-0', 'end-0', 'm-1', 'p-1', 'btn-sm');
                        deleteBtn.style.borderRadius = '50%';

                        deleteBtn.addEventListener('click', function () {
                            selectedFiles.splice(index, 1);  // Remove file from array
                            renderFilePreview();  // Re-render the preview
                        });

                        let filename = document.createElement('p');
                        filename.classList.add('text-center', 'mt-2');
                        filename.innerText = file.name;

                        col.appendChild(image);
                        col.appendChild(deleteBtn);
                        col.appendChild(filename);
                    };
                    reader.readAsDataURL(file);
                }

                row.appendChild(col);  // Append the column to the row
            });

            filePreview.appendChild(row);  // Append the row to the file-preview section
        } else {
            filePreview.innerHTML = '<p>No files selected yet.</p>';
        }
    }
    function chooseSizeChart(selectElement) {
        // Get the selected option
        var selectedOption = selectElement.options[selectElement.selectedIndex];

        // Get the image URL from the data-image attribute
        var imageUrl = selectedOption.getAttribute('data-image');

        // Update the file-preview div with the selected image
        var previewDiv = document.getElementById('file-sizechart-preview');

        // Clear any previous content in the file-preview
        previewDiv.innerHTML = '';

        // Create an image element to display the selected image
        var imgElement = document.createElement('img');
        imgElement.src = imageUrl;
        imgElement.id = 'file-sizechart-select';
        imgElement.alt = 'Size chart preview';
        imgElement.style.maxWidth = '150px'; // Ensure the image fits the preview area

        // Append the image to the preview div
        previewDiv.appendChild(imgElement);
    }
    function click_show_color(checkbox) {
        if ($(checkbox).is(':checked')) {
            $("#list_image_color").css('display', 'block');
        } else {
            $("#list_image_color").css('display', 'none');
        }
    }
</script>
<script>
    const categories = <?php echo json_encode($categorietemps); ?>;
    
    function createCategoryList(parentId, level) {
        const levelContainer = $(`<div class="col"><ul class="list-group level" data-level="${level}"></ul></div>`);
        $('#categoryContainer').append(levelContainer);

        let hasChild = false;

        categories.forEach(category => {
            if (parseInt(category.parent_id) === parentId) {
                $(`.level[data-level="${level}"]`).append(
                    `<li class="list-group-item" data-id="${category.id}">${category.local_name}</li>`
                );
                hasChild = true;
            }
        });

        $(`.level[data-level="${level}"]`).on('click', 'li', function (event) {
            event.stopPropagation();
            const id = $(this).data('id');
            const selectedText = $(this).text();

            selectedCategories = selectedCategories.slice(0, level - 1);
            selectedCategories.push(selectedText);

            clearLowerLevels(level);

            if (categories.some(cat => parseInt(cat.parent_id) === id)) {
                createCategoryList(id, level + 1);
            } else {
                idcategory = id;
                getAttributes(idcategory);
                $('#categoryInput').val(selectedCategories.join(' > '));
                $('#categoryDropdown').removeClass('show');
            }

            $(this).addClass('active').siblings().removeClass('active');
        });
    }

    function clearLowerLevels(startLevel) {
        for (let i = startLevel + 1; i <= 10; i++) {
            $(`.level[data-level="${i}"]`).parent().remove();
        }
    }

    createCategoryList(0, 1);

    function renderAttributes(response) {
        const container = document.getElementById('attributesContainer');
        container.innerHTML = '';

        // Mảng các giá trị mặc định
        const defaultAttributes = json.product_attributes

        // Lọc và tạo các thuộc tính
        const filteredChemicals = response.attributes.filter(chemical =>
            chemical.name !== "CA Prop 65: Repro. Chems" && chemical.name !== "CA Prop 65: Carcinogens"
        );

        filteredChemicals.forEach(attribute => {
            const row = document.createElement('div');
            row.classList.add('row', 'm-3', 'col-3');

            const title = document.createElement('h6');
            title.innerText = attribute.name;
            row.appendChild(title);

            const input = document.createElement('input');
            input.type = 'text';
            input.classList.add('form-control', 'categoryInput');
            input.placeholder = `Choose ${attribute.name}...`;

            const dropdown = document.createElement('div');
            dropdown.classList.add('dropdown-menu', 'mydropdown');

            // Kiểm tra nếu thuộc tính có lựa chọn nhiều giá trị
            if (attribute.is_multiple_selection) {
                attribute.values?.forEach(value => {
                    const item = document.createElement('div');
                    item.classList.add('dropdown-item');

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = `checkbox-${value.id}`;
                    checkbox.value = value.name;

                    checkbox.onchange = () => handleMultiSelection(attribute, checkbox, input, dropdown);

                    const label = document.createElement('label');
                    label.htmlFor = checkbox.id;
                    label.innerText = value.name;

                    item.appendChild(checkbox);
                    item.appendChild(label);
                    dropdown.appendChild(item);

                    // Kiểm tra nếu giá trị mặc định có trong danh sách
                    const defaultAttr = defaultAttributes.find(attr => attr.id === attribute.id);
                    if (defaultAttr && defaultAttr.values.some(v => v.id === value.id)) {
                        checkbox.checked = true;
                        handleMultiSelection(attribute, checkbox, input, dropdown); // Chọn mặc định
                    }
                });
            } else {
                attribute.values?.forEach(value => {
                    const item = document.createElement('button');
                    item.classList.add('dropdown-item');
                    item.innerText = value.name;
                    item.onclick = (event) => handleSelection(attribute, value.name, value.id, input, dropdown, event);

                    // Kiểm tra nếu giá trị mặc định có trong danh sách
                    const defaultAttr = defaultAttributes.find(attr => attr.id === attribute.id);
                    if (defaultAttr && defaultAttr.values.some(v => v.id === value.id)) {
                        input.value = value.name;
                        handleSelection(attribute, value.name, value.id, input, dropdown, event); // Chọn mặc định
                    }

                    dropdown.appendChild(item);
                });
            }

            row.appendChild(input);
            row.appendChild(dropdown);
            container.appendChild(row);

            input.onclick = (event) => {
                event.stopPropagation();
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (menu !== dropdown) {
                        menu.classList.remove('show');
                    }
                });
                dropdown.classList.toggle('show');
            };
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
        });
    }

    function handleMultiSelection(attribute, checkbox, input, dropdown) {
        const selectedValues = [];
        dropdown.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            if (cb.checked) {
                selectedValues.push({ id: cb.id.replace('checkbox-', ''), name: cb.value });
            }
        });

        input.value = selectedValues.map(value => value.name).join(', ');

        const existingAttribute = selectedAttributes.find(attr => attr.id === attribute.id);
        if (existingAttribute) {
            existingAttribute.values = selectedValues;
        } else {
            selectedAttributes.push({ id: attribute.id, name: attribute.name, values: selectedValues });
        }

        dropdown.classList.add('show');
    }

    function handleSelection(attribute, valueName, id, input, dropdown, event) {
        event.stopPropagation();
        input.value = valueName;

        const existingAttribute = selectedAttributes.find(attr => attr.id === attribute.id);
        if (existingAttribute) {
            if (valueName === "none") {
                selectedAttributes = selectedAttributes.filter(attr => attr.id !== attribute.id);
            } else {
                existingAttribute.values = [{ id: id, name: valueName }];
            }
        } else {
            selectedAttributes.push({
                id: attribute.id,
                name: attribute.name,
                values: [{ id: id, name: valueName }]
            });
        }

        console.log(selectedAttributes);
    }

    getAttributes('{{$category_id}}')
    function getAttributes(idcategory) {
        $.ajax({
            url: `../../products/get-attributes`,
            method: 'GET',
            data: { store_id: 124, idcategory: idcategory },
            success: function (response) {
                renderAttributes(response);
            },
            error: function (xhr, status, error) {
                // $("#publish").prop('disabled', true);
                // console.error('Error fetching attributes:', error);
                alert(JSON.parse(response.responseText).message);
                // $("#publish").prop('disabled', true);

            },
        });
    }

</script>
@stop

@endsection