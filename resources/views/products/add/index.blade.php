@extends('layouts.app')

@section('page-title', __('Add Products'))
@section('page-heading', __('Add Products'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Add Products')
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
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input class="form-control" type="text" name="name" id="name">
                                    </div>
                                </div>

                                <div class="col-12 row align-items-end">
                                    <label for="nameSelect" class="form-label col-12">Suggested name</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="nameSelect" name="nameSelect">
                                            <option value="" disabled selected>----- Select a name -----</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" id="applyNameButton" class="btn btn-success w-100" disabled>Apply</button>
                                    </div>
                                </div>

                                <div class="form-group col-12 mt-2">
                                    <label for="editor">Description</label>
                                    <input id="editor" type="hidden" name="description"
                                        value="{{ old('content', '') }}">
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
                                <label>Product compliance</label>
                                <div class="col-12">
                                    <label for="name">CA Prop 65: Repro. Chems</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="ca_prop_65_repro_chems"
                                            value="yes" id="ca_prop_65_repro_chems_yes">
                                        <label class="form-check-label" for="ca_prop_65_repro_chems_yes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="ca_prop_65_repro_chems"
                                            value="no" id="ca_prop_65_repro_chems_no" checked>
                                        <label class="form-check-label" for="ca_prop_65_repro_chems_no">No</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <label for="name">CA Prop 65: Carcinogens</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="ca_prop_65_carcinogens"
                                            value="yes" id="ca_prop_65_carcinogens_yes">
                                        <label class="form-check-label" for="ca_prop_65_carcinogens_yes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="ca_prop_65_carcinogens"
                                            value="no" id="ca_prop_65_carcinogens_no" checked>
                                        <label class="form-check-label" for="ca_prop_65_carcinogens_no">No</label>
                                    </div>
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
                                    <input type="number" class="form-control" name="height" id="height"
                                        placeholder="height" value="5">
                                </div>
                                <div class="col-4 mt-2">
                                    <label for="weight">Width (CENTIMETER)</label>
                                    <input type="number" class="form-control" name="width" id="width"
                                        placeholder="width" value="15">
                                </div>
                                <div class="col-4 mt-2">
                                    <label for="weight">Length (CENTIMETER)</label>
                                    <input type="number" class="form-control" name="length" id="length"
                                        placeholder="length" value="15">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="card-header row col-12"
                                style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                                <!-- Form for uploading multiple designs -->
                                <div class="form-group col-6">
                                    <label for="designs">Upload Designs</label>
                                    <input type="file" class="form-control" name="designs[]" id="designs" multiple>
                                </div>

                                <!-- Preview selected files and images -->
                                <div class="m-3 col-12" id="file-preview">
                                    <p>No files selected yet.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="card-header row col-12"
                                style="border-radius: 5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                                <!-- Form for selecting size chart -->
                                <div class="col-md-4">
                                    <label for="select_size_chart" class="form-label">Choose size chart</label>
                                    <select class="form-select" id="select_size_chart" name="select_size_chart" onchange="chooseSizeChart(this)">
                                        <option data-image="" value="0">Size chart...</option>
                                        @foreach ($sizecharts as $sizechart)
                                            <option data-image="{{ $sizechart->url }}" value="{{ $sizechart->id }}">
                                                {{ $sizechart->name }}
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
                                <div class="col-6" >
                                    <div id="list_variant">

                                    </div>
                                   
                                    <button type="button" class="btn btn-primary px-3 py-2 rounded mx-1 mt-2" onclick="generate()">
                                        Create Variant List
                                    </button>
                                   
                                </div>
                            </div>
                            <div class="card-header row col-12 mt-2"
                                style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                                <div class="col-12 row">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-2">
                                            <select class="form-select" id="styleedit" onchange="changeFilter(this, 0)">
                                                <option selected value="All">Style...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-select" id="coloredit" onchange="changeFilter(this, 1)">
                                                <option selected value="All">Color...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-select" id="sizeedit" onchange="changeFilter(this, 2)">
                                                <option selected value="All">Size...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-select" name="select_option_price" id="select_option_price" onchange="selectAddPrice(this)">
                                                <option value="1" selected>1</option>
                                                <option value="2">2</option>
                                            </select>
                                        </div>
                                    </div>

                                    <input type="text" class="form-control col-1 ml-1 mt-2" id="priceedit"
                                        aria-describedby="emailHelp" placeholder="Enter price">
                                    <input type="text" class="form-control col-2 ml-1 mt-2" id="quantityedit"
                                        aria-describedby="emailHelp" placeholder="Enter quantity">

                                    <button type="button" class="btn btn-success col-1 mr-1 ml-1 mt-2"
                                        onclick="applyVariantFilter()">Apply</button>
                                </div>
                                <form class="col-12">
                                    <table id="variantTable" class="table">
                                        <!-- The header and body rows will be dynamically inserted here -->
                                    </table>
                                </form>
                            </div>
                          
                            <div class="card-header row col-12 mt-2"
                                style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                                <div class="form-group col-4">
                                    <label for="name">Discount flashdeals</label>
                                    <input class="form-control" type="text" name="discount" id="discount"
                                        value="">
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
        let variant = [{
            style: 'T-shirt',
            color: 'Black, White, Sand, Dark Heather, Sport Grey, Ash, Navy, Light Blue, Light Pink, Military Green, Forest Green, Maroon, Purple, Dark Chocolate, Red, Orange',
            size: 'S, M, L, XL, 2XL, 3XL, 4XL',
        }];

        function generateVariant() {
            let html = '';
            variant.forEach((item, index) => {
                html += `<div class="variant-item p-3 mb-2 border rounded" id="variant_${index}">`;

                Object.keys(item).forEach((key) => {
                    html += `
                    <div class="form-group row mt-2" id="variant_${index}_${key}">
                        <div class="col-sm-3">
                            <input class="form-control key-input" type="text" value="${key}" 
                                onblur="updateKey(${index}, '${key}', this.value)">
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="${key}_variant_${index}" value="${item[key]}"
                                onblur="updateValue(${index}, '${key}', this.value)">
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-danger" onclick="removeKey(${index}, '${key}')">X</button>
                        </div>
                    </div>`;
                });

                // Nút thêm key
                html += `
                    <button type="button" class="btn btn-success mt-2" onclick="addKey(${index})">Thêm Key</button>
                </div>`;
            });

            $('#list_variant').html(html);
        }

        // Hàm thêm key mới vào biến thể
        function addKey(index) {
            let newKey = prompt("Nhập tên thuộc tính mới:");
            if (newKey && !variant[index][newKey]) {
                variant[index][newKey] = ''; // Thêm key mới với giá trị rỗng
                generateVariant(); // Cập nhật lại giao diện
            } else {
                alert("Tên key đã tồn tại hoặc không hợp lệ!");
            }
        }

        // Hàm xóa từng key trong biến thể
        function removeKey(index, key) {
            delete variant[index][key]; // Xóa key trong object
            generateVariant(); // Cập nhật lại giao diện
        }

        // Hàm cập nhật key (tên thuộc tính)
        function updateKey(index, oldKey, newKey) {
            if (newKey && oldKey !== newKey && !variant[index][newKey]) {
                variant[index][newKey] = variant[index][oldKey]; // Gán giá trị cho key mới
                delete variant[index][oldKey]; // Xóa key cũ
                generateVariant(); // Cập nhật lại giao diện
            }
        }

        // Hàm cập nhật giá trị của biến thể
        function updateValue(index, key, value) {
            variant[index][key] = value;
        }

        // Gọi hàm generateVariant khi trang tải xong
        $(document).ready(function() {
            generateVariant();
        });

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

            $(`.level[data-level="${level}"]`).on('click', 'li', function(event) {
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
            const filteredChemicals = response.attributes.filter(chemical =>
                chemical.name != "CA Prop 65: Repro. Chems" && chemical.name != "CA Prop 65: Carcinogens"
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

                const item = document.createElement('div');
                item.classList.add('dropdown-item');

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.id = `checkbox`; // ID duy nhất cho checkbox
                checkbox.value = "";

                // Gán sự kiện onchange cho checkbox
                checkbox.onchange = () => handleMultiSelection(attribute, checkbox, input, dropdown);



                if (attribute.is_multiple_selection) {
                    const label = document.createElement('label');
                    label.htmlFor = checkbox.id; // Liên kết label với checkbox
                    label.innerText = "none";

                    // Thêm sự kiện click cho label để chọn checkbox
                    item.onclick = (event) => {
                        handleMultiSelection(attribute, checkbox, input,
                            dropdown); // Gọi hàm khi click vào label
                    };

                    item.appendChild(checkbox);
                    item.appendChild(label);
                    dropdown.appendChild(item);
                    attribute.values?.forEach(value => {
                        const item = document.createElement('div');
                        item.classList.add('dropdown-item');

                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.id = `checkbox-${value.id}`; // ID duy nhất cho checkbox
                        checkbox.value = value.name;

                        // Gán sự kiện onchange cho checkbox
                        checkbox.onchange = () => handleMultiSelection(attribute, checkbox, input,
                            dropdown);

                        const label = document.createElement('label');
                        label.htmlFor = checkbox.id; // Liên kết label với checkbox
                        label.innerText = value.name;

                        // Thêm sự kiện click cho label để chọn checkbox
                        item.onclick = (event) => {
                            handleMultiSelection(attribute, checkbox, input,
                                dropdown); // Gọi hàm khi click vào label
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
                        item.onclick = (event) => handleSelection(attribute, value.name, value.id, input,
                            dropdown, event);
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

                document.addEventListener('click', function() {
                    dropdown.classList.remove('show');
                });
            });
        }
        // document.addEventListener('click', function (event) {
        //     const isClickInsideDropdown = Array.from(document.querySelectorAll('.dropdown-menu')).some(drop => drop.contains(event.target));
        //     if (!isClickInsideDropdown) {
        //         // Chỉ đóng nếu không nhấp vào dropdown
        //         document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
        //             menu.classList.remove('show');
        //         });
        //     }
        // });

        function handleMultiSelection(attribute, checkbox, input, dropdown) {
            const selectedValues = [];

            dropdown.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                if (cb.checked) {
                    selectedValues.push({
                        id: cb.id.replace('checkbox-', ''),
                        name: cb.value
                    });
                }
            });

            // Cập nhật giá trị của input với các giá trị đã chọn
            input.value = selectedValues.map(value => value.name).join(', ');

            // Cập nhật mảng selectedAttributes
            const existingAttribute = selectedAttributes.find(attr => attr.id === attribute.id);
            if (existingAttribute) {
                existingAttribute.values = selectedValues; // Cập nhật giá trị
            } else {
                selectedAttributes.push({
                    id: attribute.id,
                    name: attribute.name,
                    values: selectedValues
                }); // Thêm mới
            }

            console.log(selectedAttributes); // In ra mảng để kiểm tra
            dropdown.classList.add('show'); // Đảm bảo dropdown vẫn mở

        }
        // Lắng nghe sự kiện click cho tài liệu
        document.addEventListener('click', function(event) {
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
                    existingAttribute.values = [{
                        id: attribute.id,
                        name: valueName
                    }]; // Cập nhật giá trị
                }
            } else {
                selectedAttributes.push({
                    id: attribute.id,
                    name: attribute.name,
                    values: [{
                        id: id,
                        name: valueName
                    }]
                }); // Thêm mới
            }

            console.log(selectedAttributes); // In ra mảng để kiểm tra
        }

        function getAttributes(idcategory) {
            $.ajax({
                url: `../products/get-attributes`,
                method: 'GET',
                data: {
                    store_id: 8,
                    idcategory: idcategory
                },
                success: function(response) {
                    renderAttributes(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching attributes:', error);
                    alert('Không thể tải thuộc tính. Vui lòng thử lại sau.');
                },
            });
        }
    </script>
    <script>
        var optionVariants = ["style", "color", "size"];
        var styleVariant = "T-shirt";
        var colorVariant =
            "Black, White, Sand, Dark Heather, Sport Grey, Ash, Navy, Light Blue, Light Pink, Military Green, Forest Green, Maroon, Purple, Dark Chocolate, Red, Orange";
        var sizeVariant = "S, M, L, XL, 2XL, 3XL";
        let selectedFiles = [];
        var listpriceconvert = [
            [0, 1, 2, 3, 4, 5, 6],
            [0, 0, 0, 0, 1, 2, 3]
        ]
        let priceConvert = listpriceconvert[0]

        function selectAddPrice(target) {
            console.log(target.value);
            priceConvert = listpriceconvert[target.value - 1]
        }
        const description =
            `<div><strong>Welcome to the store!<br></strong>&nbsp;_ Experience unparalleled comfort and style with our versatile collection of hoodies, sweatshirts, and t-shirts. Crafted with a passion for providing the perfect shopping experience, our products are designed to keep you warm and cozy throughout the winter.<br><br></div><div>&nbsp;_ Feel free to explore a wide range of soft, comfy hoodies that are perfect for the season. We take pride in offering customization options, allowing you to choose your preferred color or even request a custom design. Should you have any questions or specific concerns, our dedicated team is always ready to assist – just drop us a message.<br><br></div><div>&nbsp;_ The standout feature of our products lies in the captivating images printed on the fabric using cutting-edge digital printing technology. Unlike embroidered designs, these images are seamlessly integrated, ensuring they neither peel off nor fade over time. Our hoodies, made from a blend of 50% cotton and 50% polyester, provide a classic fit with a double-lined hood and color-matched drawcord.<br><br></div><div>&nbsp;_ For those seeking premium shirts, our collection of soft, high-quality shirts is a perfect fit. Immerse yourself in 100% cotton shirts, available in various colors and styles. The innovative digital printing technology ensures that the vibrant images on these shirts remain intact for the long haul.<br><br></div><div>&nbsp;_ Embrace the winter chill with our warm sweatshirts, designed with your comfort in mind. The images are intricately printed using advanced digital technology, creating a lasting impression. The sweatshirts, featuring a classic fit and 1x1 rib with spandex, guarantee enhanced stretch and recovery.<br><br></div><div>&nbsp;_ Elevate your winter wardrobe with our curated selection of cozy and stylish apparel. Your satisfaction is our priority, and we look forward to making your shopping experience truly exceptional.<br><br></div><div>&nbsp;<strong>SIZE CHART</strong><figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:422,&quot;url&quot;:&quot;https://lh7-rt.googleusercontent.com/docsz/AD_4nXcYWzTf9lQI-6q9WAfOVAyBsi2k5d8Sg761yGCaH8v9bcBIeU-h5TYr8qLa1VgImzcufQekU4U3Vk9Vcoi5tJCfoTox3U6nNP23ZjTGomEn_O1plUi482krLx5bq5avivsnUozwNQ?key=f1JtNQbLIQCKVVZiWjM_1g&quot;,&quot;width&quot;:549}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://lh7-rt.googleusercontent.com/docsz/AD_4nXcYWzTf9lQI-6q9WAfOVAyBsi2k5d8Sg761yGCaH8v9bcBIeU-h5TYr8qLa1VgImzcufQekU4U3Vk9Vcoi5tJCfoTox3U6nNP23ZjTGomEn_O1plUi482krLx5bq5avivsnUozwNQ?key=f1JtNQbLIQCKVVZiWjM_1g\" width=\"549\" height=\"422\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>RETURNS OR EXCHANGES<br></strong><br></div><div>All of our shirts are custom printed so we do not accept returns or exchanges due to the sizing so please make sure you take all the steps to ensure you get the size you are wanting. However, if there are any issues with the shirt itself, please message us and we'd be happy to help correct the error.<br><br></div><div><strong>PRODUCTION AND SHIPPING<br></strong><br></div><div>Production: 1-3 days&nbsp;<br>Standard Shipping : 3-6 business days after production time<br><br></div><div><strong>THANK YOU<br></strong><br></div>`;
        // Set the value of the hidden input linked to Trix
        $("#editor").val(description);

        // Trigger a change event to update Trix Editor's content
        document.querySelector("trix-editor").editor.loadHTML(description);
        addOptions("styleedit", styleVariant);
        addOptions("coloredit", colorVariant);
        addOptions("sizeedit", sizeVariant);
        generateImageColor();
        const productID = randomId();
        console.log("product ID", productID)
        let varianttmp = [];
        const filter = ["All", "All", "All"];
        let json = {
            "product": {
                "id": productID,
                "title": "",
                "set": "",
                "category": "",
                "category_id": "",
                "selectedAttributes": "",
                "imagesizechart": "",
                "ca_prop_65_repro_chems": "",
                "ca_prop_65_carcinogens": "",
                "weight": "",
                "height": "",
                "width": "",
                "length": "",
                "body_html": "",
                "description": "",
                "vendor": "CustomCat",
                "product_type": "",
                "created_at": "2019-05-28T02:37:07+07:00",
                "handle": "titljson.toLowerCase().replace(/ /g, " - ")",
                "updated_at": "2019-08-28T11:45:17+07:00",
                "published_at": "2019-05-28T02:37:06+07:00",
                "template_suffix": null,
                "published_scope": "web",
                "tags": "",
                "variants": [],
                "options": [{
                        "id": randomId(),
                        "product_id": productID,
                        "name": "Style",
                        "position": 1,
                        "values": "type"
                    },
                    {
                        "id": randomId(),
                        "product_id": productID,
                        "name": "Color",
                        "position": 2,
                        "values": "color"
                    },
                    {
                        "id": randomId(),
                        "product_id": productID,
                        "name": "Size",
                        "position": 3,
                        "values": "size"
                    }
                ],
                "images": [],
                "imagevariants": [],
                "image": {
                    "id": "image_id_first",
                    "product_id": productID,
                    "position": 1,
                    "created_at": "2019-05-30T04:33:43+07:00",
                    "updated_at": "2019-10-22T09:03:30+07:00",
                    "width": 1155,
                    "height": 1155,
                    "src": "",
                    "variant_ids": []
                }
            }
        };

        function publish(target) {
            $(target).prop('disabled', true);
            const title = $("#name").val();
            const description = $("#editor").val();
            const discount = $("#discount").val();
            if (discount == "") {
                alert("Please add discount flashdeal");
                $(target).prop('disabled', false);
                return;
            }
            const image = selectedFiles;
            // const category = $('select[name="category"]').val();
            // const set = $('select[name="set"]').val();
            const imagesizechart = $('#file-sizechart-select').attr('src');;
            const image_colors = $('[id^="fileimagecolor["]');
            const image_colors_src = [];

            image_colors.each(function() {
                const src = $(this).attr('src');
                const name = $(this).attr('name');
                if (src) {
                    image_colors_src.push({
                        'color': name,
                        'src': src
                    }); // Add to array if src exists
                }
            });

            console.log("title");
            console.log(title);
            console.log("description");
            console.log(description);
            console.log("image");
            console.log(image);
            console.log("varianttmp");
            console.log(varianttmp);
            console.log("imagesizechart");
            console.log(imagesizechart);
            console.log("image_color");
            console.log(image_colors);
            console.log("image_colors_src");
            console.log(image_colors_src);

            json.product.title = title;
            json.product.description = description;
            json.product.variants = varianttmp;
            json.product.imagesizechart = imagesizechart;
            json.product.imagevariants = image_colors_src
            // "ca_prop_65_repro_chems":"",
            //             "ca_prop_65_carcinogens":"",
            //             "weight":"",
            //             "height":"",
            //             "width":"",
            //             "length":"",
            const ca_prop_65_repro_chems = $("input[name='ca_prop_65_repro_chems']:checked").val();
            const ca_prop_65_carcinogens = $("input[name='ca_prop_65_carcinogens']:checked").val();
            const weight = $("#weight").val();
            const height = $("#height").val();
            const width = $("#width").val();
            const length = $("#length").val();

            console.log("ca_prop_65_repro_chems");
            console.log(ca_prop_65_repro_chems);
            console.log("ca_prop_65_carcinogens");
            console.log(ca_prop_65_carcinogens);
            console.log("weight");
            console.log(weight);
            console.log("height");
            console.log(height);
            console.log("width");
            console.log(width);
            console.log("length");
            console.log(length);

            json.product.ca_prop_65_repro_chems = ca_prop_65_repro_chems
            json.product.ca_prop_65_carcinogens = ca_prop_65_carcinogens
            json.product.weight = weight
            json.product.height = height
            json.product.width = width
            json.product.length = length

            const styleoption = [];
            const coloroption = [];
            const sizeoption = [];
            if (styleVariant.split(",").length > 0) {
                json.product.options[0].values = styleVariant.split(",");
            }
            if (colorVariant.split(",").length > 0) {
                json.product.options[1].values = colorVariant.split(",");
            }
            if (sizeVariant.split(",").length > 0) {
                json.product.options[2].values = sizeVariant.split(",");
            }



            console.log("idcategory")
            console.log(idcategory)

            console.log("selectedAttributes")
            console.log(selectedAttributes)
            json.product.category_id = idcategory;
            json.product.selectedAttributes = selectedAttributes.map(attribute => {
                const filteredValues = attribute.values.filter(value => value.name !== "");
                return {
                    ...attribute,
                    values: filteredValues
                };
            }).filter(attribute => attribute.values.length > 0);

            console.log("json")
            console.log(json)

            var formData = new FormData();
            formData.append('json', JSON.stringify(json))
            image.forEach((file) => {
                formData.append('images[]', file)
            })
            formData.append('name', title)
            formData.append('product_id', productID)
            formData.append('discount', discount)
            $.ajax({
                url: './add',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Handle the response, e.g., reload the page
                    if (JSON.parse(response).message) {
                        location.href = '../products';
                    }
                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }

        function generate() {

            if (variant.length > 0) {
                let position = 0;
                varianttmp = [];
                variant.forEach((item) => {
                    // Lấy tất cả key trong object, bỏ qua "id" và "product_id" nếu có
                    let keys = Object.keys(item).filter(key => key !== "id" && key !== "product_id");

                    // Tạo danh sách giá trị từ mỗi key
                    let values = keys.map(key => (item[key] ? item[key].split(",").map(val => val.trim()) : []));

                    // Tạo tổ hợp tất cả giá trị từ các key
                    let combinations = generateCombinations(values);

                    // Tạo danh sách biến thể từ tổ hợp giá trị
                    combinations.forEach((combination) => {
                        let variantObject = {
                            "id": randomId(),
                            "product_id": productID,
                            "title": combination.join(" / "),
                            "price": "00.00",
                            "sku": null,
                            "quantity": 999,
                            "position": position,
                            "compare_at_price": "",
                            "fulfillment_service": "manual",
                            "inventory_management": null,
                            "created_at": new Date().toISOString(),
                            "updated_at": new Date().toISOString(),
                            "taxable": true,
                            "barcode": null,
                            "grams": 0,
                            "image_id": "53047686077292",
                            "weight": 0.4101,
                            "weight_unit": "lb",
                            "requires_shipping": true,
                            "options": [] // Mảng lưu trữ key và value
                        };

                        // Gán key và value vào mảng `options`
                        keys.forEach((key, index) => {
                            variantObject.options.push({ "name": key, "value": combination[index] });
                        });

                        varianttmp.push(variantObject);
                        position++;
                    });
                });
            }

            console.log("varianttmp", varianttmp);
            createtableVariant(varianttmp, filter);
        }

        // Hàm tạo tổ hợp tất cả giá trị từ các danh sách giá trị
        function generateCombinations(arrays, prefix = []) {
            if (!arrays.length) return [prefix];
            let result = [];
            let firstArray = arrays[0];
            let remainingArrays = arrays.slice(1);

            firstArray.forEach(value => {
                result = result.concat(generateCombinations(remainingArrays, [...prefix, value]));
            });

            return result;
        }



        function createtableVariant(variantlist, filter) {
            var table = document.getElementById("variantTable");
            table.innerHTML = ""; // Xóa nội dung cũ

            // Tạo tiêu đề bảng (header)
            var header = table.createTHead();
            var headerRow = header.insertRow(0);
            var optionVariants = Object.keys(variant[0]);

            // Thêm tiêu đề cột cho từng option
            optionVariants.forEach(option => {
                var th = document.createElement("th");
                th.innerHTML = option; 
                headerRow.appendChild(th);
            });
            console.log("optionVariants");
            console.log(optionVariants);
            // Thêm tiêu đề cột cho Price, Quantity và Delete
            ["Price", "Quantity", "Delete"].forEach(title => {
                var th = document.createElement("th");
                th.innerHTML = title;
                headerRow.appendChild(th);
            });

            // Tạo thân bảng (tbody)
            var tbody = table.createTBody();

            // Duyệt qua danh sách variantlist để tạo các dòng
            variantlist.forEach((variant) => {
                var row = tbody.insertRow();

                // Thêm dữ liệu của từng option vào bảng
                optionVariants.forEach(optionName => {
                    var cell = row.insertCell();
                    var foundOption = variant.options.find(opt => opt.name === optionName);
                    cell.innerHTML = foundOption ? foundOption.value : "-"; // Nếu không có giá trị thì hiển thị "-"
                });

                // Thêm ô input cho Price
                var cellPrice = row.insertCell();
                cellPrice.innerHTML = `
                    <input type="text" value="${variant.price}" class="price-input form-control" 
                        onchange="changePriceVariant('${variant.id}', this)">
                `;

                // Thêm ô input cho Quantity
                var cellQuantity = row.insertCell();
                cellQuantity.innerHTML = `
                    <input type="number" value="${variant.quantity}" class="quantity-input form-control" 
                        onchange="changeQuantityVariant('${variant.id}', this)">
                `;

                // Thêm nút xóa
                var cellDelete = row.insertCell();
                cellDelete.innerHTML = `
                    <span onclick="deleteVariant('${variant.id}', this)">
                        <i class="fa fa-trash"></i>
                    </span>
                `;
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
                        <input type="file" class="form-control" name="image_color[${color.trim()}]" id="image_color[${color.trim()}]" onchange="uploadAndPreviewImage('${color.trim()}')" >
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
                url: './upload-image-color', // Replace with your actual upload URL
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
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
                error: function(xhr, status, error) {
                    console.error("Image upload failed: ", error);
                }
            });
        }
        function applyVariantFilter() {
            // Lấy giá trị price và quantity từ input (mặc định nếu trống)
            const priceedit = parseFloat($("#priceedit").val()) || 0; // Đảm bảo là số
            const quantityedit = parseInt($("#quantityedit").val()) || 999; // Đảm bảo là số nguyên

            console.log("priceedit", priceedit);
            console.log("quantityedit", quantityedit);
            console.log("varianttmp", varianttmp);

            varianttmp.forEach((variant) => {
                console.log(variant);
                const size = variant.options.find(opt => opt.name === "size")?.value || "";
                variant.price = cenvertPricefromSize(priceedit, size.trim());
                variant.quantity = quantityedit;
        
            });

            console.log("Updated varianttmp:", varianttmp);

            createtableVariant(varianttmp, filter);
        }


        function changePriceVatiant(style, color, size, target) {
            const priceedit = parseFloat($(target).val()) || 0; // Ensure it's a number
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
            const quantityedit = parseFloat($(target).val()) || 0; // Ensure it's a number
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
            createtableVariant(varianttmp, filter);

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
            createtableVariant(varianttmp, filter)
        }

        function addOptions(selectElementId, variantArray) {
            var selectElement = document.getElementById(selectElementId);
            selectElement.innerHTML = '';
            var option = document.createElement("option");
            option.value = 'All';
            option.text = 'All';
            selectElement.appendChild(option);
            variantArray.split(',').forEach(function(variant) {
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

        document.getElementById('designs').addEventListener('change', function(event) {
            let filePreview = document.getElementById('file-preview');

            Array.from(event.target.files).forEach(function(file) {
                selectedFiles.push(file);
            });

            filePreview.innerHTML = '';

            if (selectedFiles.length > 0) {
                let row = document.createElement('div');
                row.classList.add('row'); // Create a Bootstrap row

                selectedFiles.forEach(function(file, index) {
                    let col = document.createElement('div');
                    col.classList.add('col-md-3', 'mb-3',
                        'position-relative'); // Column style with relative position for delete button

                    if (file.type.startsWith('image/')) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            let image = new Image();
                            image.src = e.target.result;
                            image.classList.add(
                                'img-fluid'); // Bootstrap class to make the image responsive
                            image.style.objectFit = 'contain'; // Cover the fixed size
                            image.style.backgroundColor = "#bdbdbd";
                            image.style.width = '200px';
                            image.style.height = '200px';

                            let deleteBtn = document.createElement('button');
                            deleteBtn.innerHTML = '<i class="fa fa-trash"></i>';
                            deleteBtn.classList.add('btn', 'btn-danger', 'position-absolute', 'top-0',
                                'end-0', 'm-1', 'p-1', 'btn-sm');
                            deleteBtn.style.borderRadius = '50%';
                            deleteBtn.style.padding = '5px';

                            deleteBtn.addEventListener('click', function() {
                                selectedFiles.splice(index,
                                    1); // Remove the file from the array
                                renderFilePreview(); // Re-render the preview
                            });

                            let filename = document.createElement('p');
                            filename.classList.add('text-center', 'mt-2'); // Center the filename text
                            filename.innerText = file.name;

                            col.appendChild(image);
                            col.appendChild(deleteBtn);
                            col.appendChild(filename);
                        };
                        reader.readAsDataURL(file);
                    }

                    row.appendChild(col); // Append the column to the row
                });

                filePreview.appendChild(row); // Append the row to the file-preview section
            } else {
                filePreview.innerHTML = '<p>No files selected yet.</p>';
            }
            console.log("selectedFiles");
            console.log(selectedFiles);
        });

        // Function to re-render file preview after any changes (addition/removal)
        function renderFilePreview() {
            let filePreview = document.getElementById('file-preview');
            filePreview.innerHTML = ''; // Clear the preview area

            if (selectedFiles.length > 0) {
                let row = document.createElement('div');
                row.classList.add('row'); // Create a Bootstrap row

                selectedFiles.forEach(function(file, index) {
                    let col = document.createElement('div');
                    col.classList.add('col-md-3', 'mb-3',
                        'position-relative'); // Column style with relative position for delete button

                    if (file.type.startsWith('image/')) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            let image = new Image();
                            image.src = e.target.result;
                            image.classList.add('img-fluid');
                            image.style.objectFit = 'contain';
                            image.style.width = '200px';
                            image.style.height = '200px';
                            image.style.backgroundColor = "#bdbdbd";

                            let deleteBtn = document.createElement('button');
                            deleteBtn.innerHTML = '<i class="fa fa-trash"></i>';
                            deleteBtn.classList.add('btn', 'btn-danger', 'position-absolute', 'top-0', 'end-0',
                                'm-1', 'p-1', 'btn-sm');
                            deleteBtn.style.borderRadius = '50%';

                            deleteBtn.addEventListener('click', function() {
                                selectedFiles.splice(index, 1); // Remove file from array
                                renderFilePreview(); // Re-render the preview
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

                    row.appendChild(col); // Append the column to the row
                });

                filePreview.appendChild(row); // Append the row to the file-preview section
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

        $('#name').on('blur', function() {
            const name = $(this).val().trim();

            if (name) {
                $('#applyNameButton').text('Loading...').prop('disabled', true);

                $.ajax({
                    url: '/products/generate-titles',
                    method: 'POST',
                    data: {
                        name: name
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        // $('#nameSelect').empty(); // Delete old options

                        // $('#nameSelect').append(
                        //     '<option value="" disabled selected>-----Select a name-----</option>'
                        // ); // Add option default

                        if (response.titles) {
                            const names = response.titles.split(
                                "\n");
                            names.forEach(function(name) {
                                if (name.trim() && name.match(
                                        /^\d+\./)) { // Check if the title starts with a number
                                    const cleanedName = name.replace(/^\d+\.\s*/, '').replace(
                                        /"/g, ''); // Remove numbers and double quotes
                                    $('#nameSelect').append(
                                        `<option value="${cleanedName.trim()}">${cleanedName.trim()}</option>`
                                    );
                                }
                            });

                            $('#applyNameButton').text('Apply').prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert(
                            'An error occurred while generating names. Please try again.'
                        );
                    },
                });
            }
        });

        $('#applyNameButton').on('click', function() {
            const selectedName = $('#nameSelect').val();
            if (selectedName) {
                $('#name').val(selectedName);
            }
        });
    </script>
@stop

@endsection
