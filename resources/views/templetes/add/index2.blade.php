@extends('layouts.app')

@section('page-title', __('Add Templetes'))
@section('page-heading', __('Add Templetes'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Add Templetes')
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
                                <input class="form-control" type="text" name="name" id="name" value="">
                            </div>
                            <div class="form-group col-12 mt-2">
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
                                <input type="text" class="form-control" id="categoryInput" placeholder="Categories"
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
                                <select  class="form-select" id="select_size_chart"
                                    name="select_size_chart" onchange="chooseSizeChart(this)">
                                    <option data-image="" value="0">Size chart...</option>
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
                                    <input class="form-control" type="text" name="discount" id="discount" value="">
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
    const categories = <?php echo json_encode($categorietemps); ?>;
    let selectedCategories = [];
    let idcategory = 0;
    let selectedAttributes = []; // Mảng lưu trữ các thuộc tính đã chọn
    let jsonArray = [];

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
        const filteredChemicals = response.attributes;

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

            const item = document.createElement('div');
            item.classList.add('dropdown-item');

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = `checkbox`; // ID duy nhất cho checkbox
            checkbox.value = "";

            // Gán sự kiện onchange cho checkbox
            checkbox.onchange = () => handleMultiSelection(attribute, checkbox, input, dropdown);



            if (attribute.is_multiple_selection) {
                // const label = document.createElement('label');
                // label.htmlFor = checkbox.id; // Liên kết label với checkbox
                // label.innerText = "none";

                // // Thêm sự kiện click cho label để chọn checkbox
                // item.onclick = (event) => {
                //     handleMultiSelection(attribute, checkbox, input, dropdown); // Gọi hàm khi click vào label
                // };

                // item.appendChild(checkbox);
                // item.appendChild(label);
                // dropdown.appendChild(item);
                attribute.values?.forEach(value => {
                    const item = document.createElement('div');
                    item.classList.add('dropdown-item');

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = `checkbox-${value.id}`; // ID duy nhất cho checkbox
                    checkbox.value = value.name;

                    // Gán sự kiện onchange cho checkbox
                    checkbox.onchange = () => handleMultiSelection(attribute, checkbox, input, dropdown);

                    const label = document.createElement('label');
                    label.htmlFor = checkbox.id; // Liên kết label với checkbox
                    label.innerText = value.name;

                    // Thêm sự kiện click cho label để chọn checkbox
                    item.onclick = (event) => {
                        handleMultiSelection(attribute, checkbox, input, dropdown); // Gọi hàm khi click vào label
                    };

                    item.appendChild(checkbox);
                    item.appendChild(label);
                    dropdown.appendChild(item);
                });
            } else {
                const item = document.createElement('button');
                item.id = ""
                item.classList.add('dropdown-item');
                item.innerText = "none";
                item.onclick = (event) => handleSelection(attribute, value.name, "", input, dropdown, event);
                dropdown.appendChild(item);
                attribute.values?.forEach(value => {
                    const item = document.createElement('button');
                    item.id = value.id
                    item.classList.add('dropdown-item');
                    item.innerText = value.name;
                    item.onclick = (event) => handleSelection(attribute, value.name, value.id, input, dropdown, event);
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

            document.addEventListener('click', function () {
                dropdown.classList.remove('show');
            });
        });
    }


    function handleMultiSelection(attribute, checkbox, input, dropdown) {
        const selectedValues = [];

        dropdown.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            if (cb.checked) {
                selectedValues.push({ id: cb.id.replace('checkbox-', ''), name: cb.value });
            }
        });

        // Cập nhật giá trị của input với các giá trị đã chọn
        input.value = selectedValues.map(value => value.name).join(', ');

        // Cập nhật mảng selectedAttributes
        const existingAttribute = selectedAttributes.find(attr => attr.id === attribute.id);
        if (existingAttribute) {
            existingAttribute.values = selectedValues; // Cập nhật giá trị
        } else {
            selectedAttributes.push({ id: attribute.id, name: attribute.name, values: selectedValues }); // Thêm mới
        }

        console.log(selectedAttributes); // In ra mảng để kiểm tra
        dropdown.classList.add('show'); // Đảm bảo dropdown vẫn mở

    }
    // Lắng nghe sự kiện click cho tài liệu
    document.addEventListener('click', function (event) {
        const isClickInsideDropdown = dropdown.contains(event.target);
        if (!isClickInsideDropdown) {
            dropdown.classList.remove('show'); // Đóng dropdown nếu click bên ngoài
        }
    });
    function handleSelection(attribute, valueName, id, input, dropdown, event) {
        event.stopPropagation(); // Ngăn chặn click trên dropdown đóng nó

        input.value = valueName;

        // Cập nhật mảng selectedAttributes
        const existingAttribute = selectedAttributes.find(attr => attr.id === attribute.id);
        if (existingAttribute) {
            if (valueName == "none") {
                selectedAttributes = selectedAttributes.filter(attr => attr.id !== attribute.id);
            } else {
                existingAttribute.values = [{ id: attribute.id, name: valueName }]; // Cập nhật giá trị
            }
        } else {
            selectedAttributes.push({
                id: attribute.id,
                name: attribute.name,
                values: [{ id: id, name: valueName }]
            }); // Thêm mới
        }

        console.log(selectedAttributes); // In ra mảng để kiểm tra
    }

    function getAttributes(idcategory) {
        $.ajax({
            url: `../products/get-attributes`,
            method: 'GET',
            data: { store_id: 8, idcategory: idcategory },
            success: function (response) {
                renderAttributes(response);
            },
            error: function (xhr, status, error) {
                // $("#publish").prop('disabled', true);
                // console.error('Error fetching attributes:', error);
                alert(JSON.parse(response.responseText).message);
            },
        });
    }
    function showOrnament(target) {
        document.querySelectorAll(".showOrnament").forEach(element => {
            element.style.display = target.checked ? '':'none';
        });
    }
</script>
<script>
    var optionVariants = ["Style", "Color", "Size"];
    var VariantList = [
        { key: "Style", value: "T-shirt" },
        { key: "Color", value: "Black, White, Sand, Dark Heather, Sport Grey, Ash, Navy, Light Blue, Light Pink, Military Green, Forest Green, Maroon, Purple, Dark Chocolate, Red, Orange" },
        { key: "Size", value: "S, M, L, XL, 2XL, 3XL" }
    ];


    createOptions();
    let selectedFiles = [];
    var listpriceconvert = [[0, 1, 2, 3, 4, 5, 6], [0, 0, 0, 0, 1, 2, 3]]
    let priceConvert = listpriceconvert[0]
    function selectAddPrice(target) {
        console.log(target.value);
        priceConvert = listpriceconvert[target.value - 1]
    }
    const description = `<div><strong>Welcome to the store!<br></strong>&nbsp;_ Experience unparalleled comfort and style with our versatile collection of hoodies, sweatshirts, and t-shirts. Crafted with a passion for providing the perfect shopping experience, our products are designed to keep you warm and cozy throughout the winter.<br><br></div><div>&nbsp;_ Feel free to explore a wide range of soft, comfy hoodies that are perfect for the season. We take pride in offering customization options, allowing you to choose your preferred color or even request a custom design. Should you have any questions or specific concerns, our dedicated team is always ready to assist – just drop us a message.<br><br></div><div>&nbsp;_ The standout feature of our products lies in the captivating images printed on the fabric using cutting-edge digital printing technology. Unlike embroidered designs, these images are seamlessly integrated, ensuring they neither peel off nor fade over time. Our hoodies, made from a blend of 50% cotton and 50% polyester, provide a classic fit with a double-lined hood and color-matched drawcord.<br><br></div><div>&nbsp;_ For those seeking premium shirts, our collection of soft, high-quality shirts is a perfect fit. Immerse yourself in 100% cotton shirts, available in various colors and styles. The innovative digital printing technology ensures that the vibrant images on these shirts remain intact for the long haul.<br><br></div><div>&nbsp;_ Embrace the winter chill with our warm sweatshirts, designed with your comfort in mind. The images are intricately printed using advanced digital technology, creating a lasting impression. The sweatshirts, featuring a classic fit and 1x1 rib with spandex, guarantee enhanced stretch and recovery.<br><br></div><div>&nbsp;_ Elevate your winter wardrobe with our curated selection of cozy and stylish apparel. Your satisfaction is our priority, and we look forward to making your shopping experience truly exceptional.<br><br></div><div>&nbsp;<strong>SIZE CHART</strong><figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:422,&quot;url&quot;:&quot;https://lh7-rt.googleusercontent.com/docsz/AD_4nXcYWzTf9lQI-6q9WAfOVAyBsi2k5d8Sg761yGCaH8v9bcBIeU-h5TYr8qLa1VgImzcufQekU4U3Vk9Vcoi5tJCfoTox3U6nNP23ZjTGomEn_O1plUi482krLx5bq5avivsnUozwNQ?key=f1JtNQbLIQCKVVZiWjM_1g&quot;,&quot;width&quot;:549}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://lh7-rt.googleusercontent.com/docsz/AD_4nXcYWzTf9lQI-6q9WAfOVAyBsi2k5d8Sg761yGCaH8v9bcBIeU-h5TYr8qLa1VgImzcufQekU4U3Vk9Vcoi5tJCfoTox3U6nNP23ZjTGomEn_O1plUi482krLx5bq5avivsnUozwNQ?key=f1JtNQbLIQCKVVZiWjM_1g\" width=\"549\" height=\"422\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>RETURNS OR EXCHANGES<br></strong><br></div><div>All of our shirts are custom printed so we do not accept returns or exchanges due to the sizing so please make sure you take all the steps to ensure you get the size you are wanting. However, if there are any issues with the shirt itself, please message us and we'd be happy to help correct the error.<br><br></div><div><strong>PRODUCTION AND SHIPPING<br></strong><br></div><div>Production: 1-3 days&nbsp;<br>Standard Shipping : 3-6 business days after production time<br><br></div><div><strong>THANK YOU<br></strong><br></div>`;
    // Set the value of the hidden input linked to Trix
    $("#editor").val(description);

    // Trigger a change event to update Trix Editor's content
    document.querySelector("trix-editor").editor.loadHTML(description);

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
                <div class="col-sm-3">
                    <input class="form-control" type="text" placeholder="Key"
                        onblur="updateVariantKey('${tempKey}', this)" id="input-key-${tempKey}">
                </div>
                <div class="col-sm-7">
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


    // generateImageColor();
    const productID = randomId();
    console.log("product ID", productID)
    let varianttmp = [];
    const filter = ["All", "All", "All"];
    let json = {
        "description": "",
        "title": "Anyone Can Cook Shirt, Cute Cooking TShirt, Little Chef Tee, Mouse Chef TShirt, Funny Gift for Anyone , Funny Party Shirt",
        "is_cod_open": true,
        "category_id": "601302",
        "category_version": "v2",
        "main_images": [
        ],
        "skus": [],
        "package_weight": {
            "value": "0.113",
            "unit": "KILOGRAM"
        },
        "package_dimensions": {
            "height": "5",
            "length": "15",
            "unit": "CENTIMETER",
            "width": "15"
        },
        "product_attributes": [],
        "size_chart": {
            "image": "",
        }
    };
    function publish(target) {
        // $(target).prop('disabled', true);
        const description = $("#editor").val();
        const discount = $("#discount").val();
        const name = $("#name").val();
        const imagesizechart = $('#file-sizechart-select').attr('src');;


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
            url: './add',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // Handle the response, e.g., reload the page
                if (JSON.parse(response).message) {
                    location.href = '../templates';
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
    function createtableVariant(variantlist, filter) {
        // Get the table element
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

    function generateImageColor() {
        console.log("colorVariant");
        console.log(colorVariant);
        colors = colorVariant.split(",");
        console.log(colors);
        // $("#list_image_color").html(``);
        let html = ``;
        colors.forEach(color => {
            html += `
                <div class="col-12 m-2" style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                    <div class="form-group col-12">
                        <label for="designs" style="font-weight: bold;">Choose image variant color(${color.trim()})</label>
                        <input type="file" class="form-control" name="image_color[${color.trim()}]" id="image_color[${color.trim()}]" onchange="updateVariantListValue()" >
                    </div>

                    <div class="m-3 col-12" id="file-image-color-preview-${color.trim()}"></div>
                </div>
            `;
        });
        $("#list_image_color").html(html);
    }

    function uploadAndPreviewImage(color) {
        var fileInput = document.getElementById(`image_color[${color}]`);
        var file = fileInput.files[0];
        var formData = new FormData();
        formData.append('image_color', file);

        // AJAX request to upload the image
        $.ajax({
            url: './upload-image-color',  // Replace with your actual upload URL
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // Assuming response contains the image URL
                var imageUrl = response.url;
                var previewDiv = document.getElementById(`file-image-color-preview-${color}`);

                // Clear any previous content in the preview div
                previewDiv.innerHTML = '';

                // Create an image element to display the uploaded image
                var imgElement = document.createElement('img');
                imgElement.src = imageUrl;
                imgElement.name = color;
                imgElement.id = `fileimagecolor[${color}]`;
                imgElement.alt = 'Size chart preview';
                imgElement.style.maxWidth = '150px'; // Ensure the image fits the preview area

                // Append the image to the preview div
                previewDiv.appendChild(imgElement);
            },
            error: function (xhr, status, error) {
                console.error("Image upload failed: ", error);
            }
        });
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
            let size = "";
            for (let i = 0; i < filter.length; i++) {
                const filterVal = filter[i];
                const attrVal = attributes[i]?.value_name || '';
                if(attributes[i]?.name=="Size"){
                    size = attributes[i]?.value_name;
                }
                if (filterVal !== 'All' && filterVal !== attrVal) {
                    matched = false;
                    break;
                }
            }

            if (matched) {
                variant.price.amount = cenvertPricefromSize(priceedit.toString(), size); // Cập nhật giá
                if (variant.inventory.length > 0) {
                    variant.inventory[0].quantity = quantityedit; // Cập nhật số lượng
                }
                console.log("Updated variant:", variant);
            }
        });
        console.log(jsonArray);
        createtableVariant(jsonArray, filter);
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
            // Trả về giá gốc hoặc xử lý khi không có kích cỡ
            extractedSize = size;
        } else {
            extractedSize = sizeMatch[1];
        }

        // var priceConvert = [0, 1, 2, 3, 4, 5, 6]
        var sizetemp = ['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL']
        var index = sizetemp.indexOf(extractedSize)
        var totalPrice = parseFloat(price) + parseFloat(priceConvert[index]);
        return totalPrice.toFixed(2);
    };
    function changeStyle(target) {
        styleVariant = target.value
        addOptions("styleedit", styleVariant);
    }
    function changeColor(target) {
        colorVariant = target.value
        addOptions("coloredit", colorVariant);
        generateImageColor();
    }
    function changeSize(target) {
        sizeVariant = target.value
        addOptions("sizeedit", sizeVariant);

    }
    function changeFilter(target, index) {
        const value = target.value;
        filter[index] = value
    }
    function addOptions(variantArray) {

    }
    function createOptions() {
        const container = document.getElementById("variantList");
        container.innerHTML = ""; // Xóa nội dung cũ (nếu có)

        VariantList.forEach(variant => {
            const { key, value } = variant;
            const lowerKey = key.toLowerCase();

            const html = `
                <div class="form-group row align-items-center mt-2" id="variant-row-${lowerKey}">
                    <div class="col-sm-3">
                        <input class="form-control" type="text" value="${key}" placeholder="Key" 
                            onblur="updateVariantKey('${key}', this)">
                    </div>
                    <div class="col-sm-7">
                        <input class="form-control" type="text" name="${lowerKey}_variant" id="${lowerKey}_variant"
                            value="${value}" placeholder="Value" onchange="changeValue(this)">
                    </div>
                    <div class="col-sm-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-danger" 
                            onclick="hideinput('${lowerKey}_variant', this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML("beforeend", html);
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
@stop

@endsection