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
                            <label>Product compliance</label>
                            <div class="form-check ml-2">
                                <input class="form-check-input" type="checkbox"
                                    id="ornament" onclick="showOrnament(this)">
                                <label class="form-check-label" for="ornament">ornament</label>
                            </div>
                            <div class="col-12 showOrnament" style="display:none">
                                <label for="name">Aerosols</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="aerosols"
                                        value="yes" id="ca_prop_65_carcinogens_yes">
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="aerosols"
                                        value="no" id="ca_prop_65_carcinogens_no" checked>
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_no">No</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="aerosols"
                                        value="not sure" id="ca_prop_65_carcinogens_no">
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_no">Not Sure</label>
                                </div>
                            </div>

                            <div class="col-12 showOrnament" style="display:none">
                                <label for="name">Flammable Liquid</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="flammable_liquid"
                                        value="yes" id="ca_prop_65_carcinogens_yes">
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="flammable_liquid"
                                        value="no" id="ca_prop_65_carcinogens_no" checked>
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_no">No</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="flammable_liquid"
                                        value="not sure" id="ca_prop_65_carcinogens_no">
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_no">Not Sure</label>
                                </div>
                            </div>
                            <div class="col-12 showOrnament" style="display:none">
                                <label for="name">Contains Batteries or Cells?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="contains_batteries_or_cells"
                                        value="batteries" id="ca_prop_65_carcinogens_yes">
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_yes">Batteries</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="contains_batteries_or_cells"
                                        value="cells" id="ca_prop_65_carcinogens_no">
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_no">Cells</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="contains_batteries_or_cells"
                                        value="none" id="ca_prop_65_carcinogens_no" checked>
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_no">None</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="contains_batteries_or_cells"
                                        value="not sure" id="ca_prop_65_carcinogens_no">
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_no">No Sure</label>
                                </div>
                            </div>
                            <div class="col-12 showOrnament" style="display:none">
                                <label for="name">Other Dangerous Goods or Hazardous Materials</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="other_dangerous_goods_or_hazardous_materials"
                                        value="yes" id="ca_prop_65_carcinogens_yes">
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="other_dangerous_goods_or_hazardous_materials"
                                        value="no" id="ca_prop_65_carcinogens_no" checked>
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_no">No</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="other_dangerous_goods_or_hazardous_materials"
                                        value="not sure" id="ca_prop_65_carcinogens_no">
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_no">Not Sure</label>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
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

                                <div class="form-group row mt-2">
                                    <label for="name" class="col-sm-1 col-form-label">Style</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="style_variant" id="style_variant"
                                            value="T-shirt" onchange="changeStyle(this)">
                                    </div>
                                    <div class="col-sm-2">
                                        <input class="btn btn-sm" type="checkbox" id="style_variant_checked"
                                            onclick="hideinput('style_variant',this)"></input>
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label for="name" class="col-sm-1 col-form-label">Color</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="color_variant" id="color_variant"
                                            value="Black, White, Sand, Dark Heather, Sport Grey, Ash, Navy, Light Blue, Light Pink, Military Green, Forest Green, Maroon, Purple, Dark Chocolate, Red, Orange"
                                            onchange="changeColor(this)">
                                        <!-- <input class="form-control" type="checkbox" name="show_color" id="show_color" onchange="click_show_color(this)"> -->
                                    </div>
                                    <div class="col-sm-2">
                                        <input class="btn btn-sm" type="checkbox" id="color_variant_checked"
                                            onclick="hideinput('color_variant',this)"></input>
                                    </div>
                                    <div class="card-header row col-10 m-3"
                                        style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;display:none;"
                                        id="list_image_color">
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label for="name" class="col-sm-1 col-form-label">Size</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="size_variant" id="size_variant"
                                            value="S, M, L, XL, 2XL, 3XL" onchange="changeSize(this)">
                                    </div>
                                    <div class="col-sm-2">
                                        <input class="btn btn-sm" type="checkbox" id="size_variant_checked"
                                            onclick="hideinput('size_variant',this)"></input>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary px-3 py-2 rounded mx-1 mt-2" onclick="generate()">
                                        Create Variant List
                                </button>
                            </form>
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
                                <button type="button" class="btn btn-success col-1 mr-1 mt-2"
                                    onclick="applyVariantFilter()">Apply</button>
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
    var styleVariant = "T-shirt";
    var colorVariant = "Black, White, Sand, Dark Heather, Sport Grey, Ash, Navy, Light Blue, Light Pink, Military Green, Forest Green, Maroon, Purple, Dark Chocolate, Red, Orange";
    var sizeVariant = "S, M, L, XL, 2XL, 3XL";
    let selectedFiles = [];
    var listpriceconvert = [[0, 1, 2, 3, 4, 5, 6], [0, 0, 0, 1, 2, 3, 4]]
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
    addOptions("styleedit", styleVariant);
    addOptions("coloredit", colorVariant);
    addOptions("sizeedit", sizeVariant);
    function hideinput(name, target) {
        console.log(target.checked);

        // Get the first part of the name (before the underscore) and capitalize it
        var firstPart = name.split('_')[0];
        var capitalizedFirstPart = firstPart.charAt(0).toUpperCase() + firstPart.slice(1).toLowerCase();

        // Get the index of the option to remove from optionVariants
        var index = optionVariants.indexOf(capitalizedFirstPart);

        if (target.checked) {
            if (index > -1) {
                optionVariants.splice(index, 1); // Remove the option from the array
            }
        } else {
            if (index === -1) {
                optionVariants.push(capitalizedFirstPart); // Add the option back to the array
            }
        }

        $("#" + name).prop('disabled', target.checked);

        console.log(optionVariants);
    }

    // generateImageColor();
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
            "options": [
                {
                    "id": randomId(),
                    "product_id": productID,
                    "name": "Style",
                    "position": 1,
                    "values": []
                },
                {
                    "id": randomId(),
                    "product_id": productID,
                    "name": "Color",
                    "position": 2,
                    "values": []
                },
                {
                    "id": randomId(),
                    "product_id": productID,
                    "name": "Size",
                    "position": 3,
                    "values": []
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
        // $(target).prop('disabled', true);
        const description = $("#editor").val();
        const discount = $("#discount").val();

        const imagesizechart = $('#file-sizechart-select').attr('src');;


        console.log("description");
        console.log(description);
        console.log("imagesizechart");
        console.log(imagesizechart);
        console.log("varianttmp");
        console.log(varianttmp);

        json.product.description = description;
        if (varianttmp.length == 0) {
            json.product.variants = {
                'only_price': $("#only_price").val(),
                'only_quantity': $("#only_quantity").val(),
            }
        } else {
            json.product.variants = varianttmp;
        }
        json.product.imagesizechart = imagesizechart;

        const styleoption = [];
        const coloroption = [];
        const sizeoption = [];
        if (styleVariant.split(",").length > 0 && !$("#style_variant_checked").prop("checked")) {
            json.product.options[0].values = styleVariant.split(",");
        }
        if (colorVariant.split(",").length > 0 && !$("#color_variant_checked").prop("checked")) {
            json.product.options[1].values = colorVariant.split(",");
        }
        if (sizeVariant.split(",").length > 0 && !$("#size_variant_checked").prop("checked")) {
            json.product.options[2].values = sizeVariant.split(",");
        }


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
        console.log("ornament");
        console.log($('#ornament').prop('checked'));
        if($('#ornament').prop('checked')){
            console.log("ornament");
            json.product.aerosols = $("input[name='aerosols']:checked").val()
            json.product.flammable_liquid = $("input[name='flammable_liquid']:checked").val()
            json.product.contains_batteries_or_cells = $("input[name='contains_batteries_or_cells']:checked").val()
            json.product.other_dangerous_goods_or_hazardous_materials = $("input[name='other_dangerous_goods_or_hazardous_materials']:checked").val()
        }
        json.product.ca_prop_65_repro_chems = ca_prop_65_repro_chems
        json.product.ca_prop_65_carcinogens = ca_prop_65_carcinogens
        json.product.weight = weight
        json.product.height = parseInt(height)
        json.product.width = parseInt(width)
        json.product.length = parseInt(length)

        console.log("idcategory")
        console.log(idcategory)

        console.log("selectedAttributes")
        console.log(selectedAttributes)
        json.product.category_id = idcategory;
        json.product.selectedAttributes = selectedAttributes;

        console.log("json")
        console.log(json)
        const name = $("#name").val();
        json.product.title = name;
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
        //taoj varianttmp rỗng
        varianttmp = [];
        var style = styleVariant.split(",");
        var color = colorVariant.split(",");
        var size = sizeVariant.split(",");
        console.log(style);
        console.log(color);
        console.log(size);
        if (style.length > 0) {
            var position = 0
            console.log($("#style_variant_checked").prop("checked"));  // Correctly check if the checkbox is checked

            // Initialize an array to hold the active options
            let activeOptions = [];

            // Push active options based on the checked boxes
            if (!$("#style_variant_checked").prop("checked")) activeOptions.push(style);
            if (!$("#color_variant_checked").prop("checked")) activeOptions.push(color);
            if (!$("#size_variant_checked").prop("checked")) activeOptions.push(size);

            // Only proceed if at least one option is selected
            if (activeOptions.length > 0) {
                // Recursive function to generate all combinations
                const generateCombinations = (arrays, index = 0, currentCombination = []) => {
                    if (index === arrays.length) {
                        const [option1 = "", option2 = "", option3 = ""] = currentCombination;
                        varianttmp.push({
                            "id": randomId(),
                            "product_id": productID,
                            "title": [option1, option2, option3].filter(Boolean).join(" / "),
                            "price": "00.00",
                            "sku": null,
                            "quantity": 999,
                            "position": position,
                            "compare_at_price": "",
                            "fulfillment_service": "manual",
                            "inventory_management": null,
                            "option1": option1,
                            "option2": option2,
                            "option3": option3,
                            "created_at": "2019-05-28T02:37:13+07:00",
                            "updated_at": "2019-05-30T04:33:43+07:00",
                            "taxable": true,
                            "barcode": null,
                            "grams": 0,
                            "image_id": "53047686077292",
                            "weight": 0.4101,
                            "weight_unit": "lb",
                            "requires_shipping": true
                        });
                        position++;
                        return;
                    }
                    // Loop through the current array and recurse for the next
                    arrays[index].forEach(item => {
                        generateCombinations(arrays, index + 1, [...currentCombination, item]);
                    });
                };

                // Generate combinations for the active options
                generateCombinations(activeOptions);
            } else {

            }


        } else {

        }
        console.log("varianttmp");
        console.log(varianttmp);
        createtableVariant(optionVariants, varianttmp, filter)
    }
    function createtableVariant(optionVariants, variantlist, filter) {
        // Get the table element
        var table = document.getElementById("variantTable");

        // Clear the existing table content (both headers and rows)
        table.innerHTML = "";

        // Create the header row
        var header = table.createTHead();
        var headerRow = header.insertRow(0);

        // Loop through optionVariants to create dynamic headers
        optionVariants.forEach(option => {
            var th = document.createElement("th");
            th.innerHTML = option; // Add option name as header (Style, Color, Size, etc.)
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
        if (variantlist.length > 0) {
            variantlist.forEach((variant) => {
                // Check if the variant matches the filter
                // If filter[0] is "All", we don't filter by Style, etc.
                if ((filter[0] === "All" || filter[0] === variant.option1.trim()) &&
                    (filter[1] === "All" || filter[1] === variant.option2.trim()) &&
                    (filter[2] === "All" || filter[2] === variant.option3.trim())) {

                    var row = tbody.insertRow();

                    // For each variant, create a cell for each option (Style, Color, Size)
                    optionVariants.forEach(option => {
                        var cell = row.insertCell();
                        // Dynamically add the corresponding variant property (option1, option2, option3)
                        if (option === "Style" && variant.option1 != "") {
                            cell.innerHTML = variant.option1;
                        }
                        if ((option === "Color") && variant.option2 != "") {
                            cell.innerHTML = variant.option2;
                        } 
                        if (option === "Size"){
                            if(variant.option3 != "") {
                                cell.innerHTML = variant.option3;
                            }else{
                                cell.innerHTML = variant.option2;                                
                            }
                        }
                    });

                    // Create Price and Quantity cells with editable inputs
                    var cellPrice = row.insertCell();
                    cellPrice.innerHTML = `<input type="text" value="${variant.price}" class="price-input form-control" onchange="changePriceVatiant('${variant.option1.trim()}','${variant.option2.trim()}','${variant.option3.trim()}', this)"/>`;

                    var cellQuantity = row.insertCell();
                    cellQuantity.innerHTML = `<input type="number" value="${variant.quantity}" class="quantity-input form-control " onchange="changeQuantityVatiant('${variant.option1.trim()}','${variant.option2.trim()}','${variant.option3.trim()}', this)"/>`;

                    var cellDelete = row.insertCell();
                    cellDelete.innerHTML = `<span onclick="deleteVariant('${variant.option1.trim()}','${variant.option2.trim()}','${variant.option3.trim()}', this)"><i class="fa fa-trash"></i></span>`;
                }
            });
        } else {
            var row = tbody.insertRow();

            var cellPrice = row.insertCell();
            cellPrice.innerHTML = `<input type="text" value="0" class="price-input form-control" id="only_price" />`;

            var cellQuantity = row.insertCell();
            cellQuantity.innerHTML = `<input type="number" value="0" class="quantity-input form-control" id="only_quantity"/>`;

        }
        // Loop through the variantlist array to create rows
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
        // Get the price and quantity from the input fields (defaults if empty)
        const priceedit = parseFloat($("#priceedit").val()) || 0;  // Ensure it's a number
        const quantityedit = parseInt($("#quantityedit").val()) || 999;  // Ensure it's an integer
        console.log("priceedit", priceedit);
        console.log("quantityedit", quantityedit);
        console.log("varianttmp", varianttmp);

        varianttmp.forEach((e) => {
            if ((filter[0] === "All" || filter[0] === e.option1.trim()) &&
                (filter[1] === "All" || filter[1] === e.option2.trim()) &&
                (filter[2] === "All" || filter[2] === e.option3.trim())) {

                e.price = cenvertPricefromSize(priceedit, e.option3.trim())
                e.quantity = quantityedit;
            }
        });

        console.log("Updated varianttmp:", varianttmp);

        createtableVariant(optionVariants, varianttmp, filter);
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

    // document.getElementById('designs').addEventListener('change', function (event) {
    //     let filePreview = document.getElementById('file-preview');

    //     Array.from(event.target.files).forEach(function (file) {
    //         selectedFiles.push(file);
    //     });

    //     filePreview.innerHTML = '';

    //     if (selectedFiles.length > 0) {
    //         let row = document.createElement('div');
    //         row.classList.add('row');  // Create a Bootstrap row

    //         selectedFiles.forEach(function (file, index) {
    //             let col = document.createElement('div');
    //             col.classList.add('col-md-3', 'mb-3', 'position-relative');  // Column style with relative position for delete button

    //             if (file.type.startsWith('image/')) {
    //                 let reader = new FileReader();
    //                 reader.onload = function (e) {
    //                     let image = new Image();
    //                     image.src = e.target.result;
    //                     image.classList.add('img-fluid');  // Bootstrap class to make the image responsive
    //                     image.style.objectFit = 'contain';  // Cover the fixed size
    //                     image.style.backgroundColor = "#bdbdbd";
    //                     image.style.width = '200px';
    //                     image.style.height = '200px';

    //                     let deleteBtn = document.createElement('button');
    //                     deleteBtn.innerHTML = '<i class="fa fa-trash"></i>';
    //                     deleteBtn.classList.add('btn', 'btn-danger', 'position-absolute', 'top-0', 'end-0', 'm-1', 'p-1', 'btn-sm');
    //                     deleteBtn.style.borderRadius = '50%';
    //                     deleteBtn.style.padding = '5px';

    //                     deleteBtn.addEventListener('click', function () {
    //                         selectedFiles.splice(index, 1);  // Remove the file from the array
    //                         renderFilePreview();  // Re-render the preview
    //                     });

    //                     let filename = document.createElement('p');
    //                     filename.classList.add('text-center', 'mt-2');  // Center the filename text
    //                     filename.innerText = file.name;

    //                     col.appendChild(image);
    //                     col.appendChild(deleteBtn);
    //                     col.appendChild(filename);
    //                 };
    //                 reader.readAsDataURL(file);
    //             }

    //             row.appendChild(col);  // Append the column to the row
    //         });

    //         filePreview.appendChild(row);  // Append the row to the file-preview section
    //     } else {
    //         filePreview.innerHTML = '<p>No files selected yet.</p>';
    //     }
    //     console.log("selectedFiles");
    //     console.log(selectedFiles);
    // });

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