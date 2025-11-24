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
                <div class="col-9 row">
                    <div class="col-12">

                        <div class="card-header row col-12"
                            style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                            <div class="form-group col-10">
                                <label for="name">Name</label>
                                <input class="form-control" type="text" name="name" id="name">
                            </div>
                            <div class="form-group col-10">
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
                            <div class="col-12">
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
                                    placeholder="Enter the product weight" value="0.3">
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
                            style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                            <!-- Form for uploading multiple designs -->
                            <div class="form-group col-4">
                                <label for="designs">Choose size chart</label>
                                <select class="custom-select col-12 ml-1" id="select_size_chart"
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
                            <div class="m-3 col-12" id="file-sizechart-preview">

                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="card-header row col-12"
                            style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                            <form class="col-6">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Style</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" name="style_variant" id="style_variant"
                                            value="T-shirt" onchange="changeStyle(this)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Color</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" name="color_variant" id="color_variant"
                                            value="Red, Ash, Light Pink, Pink, Purple, Sand, Sport Grey, White, Orange, Black, Dark Chocolate, Dark Heather, Forest Green, Maroon, Military Green, Navy" 
                                            onchange="changeColor(this)">
                                        <input class="form-control" type="checkbox" name="show_color" id="show_color" onchange="click_show_color(this)">
                                    </div>
                                    <div class="card-header row col-10 m-3"
                                        style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;display:none;" id="list_image_color">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Size</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" name="size_variant" id="size_variant"
                                            value="S, M, L, XL, 2XL, 3XL" onchange="changeSize(this)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <button type="button" class="btn btn-primary" onclick="generate()">Create Variant
                                        List</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-header row col-12 mt-2"
                            style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                            <div class="col-12 row">
                                <select class="custom-select col-2 ml-1" id="styleedit"
                                    onchange="changeFilter(this, 0)">
                                    <option selected value="All">Style...</option>
                                </select>
                                <select class="custom-select col-2 ml-1" id="coloredit"
                                    onchange="changeFilter(this, 1)">
                                    <option selected value="All">Color...</option>
                                </select>
                                <select class="custom-select col-2 ml-1" id="sizeedit" onchange="changeFilter(this, 2)">
                                    <option selected value="All">Size...</option>
                                </select>
                                    <select class="custom-select col-1 ml-1" name="select_option_price" id="select_option_price" onchange="selectAddPrice(this)">
                                        <option value="1" select>1</option>
                                        <option value="2">2</option>
                                    </select>
                                <input type="text" class="form-control col-1 ml-1" id="priceedit"
                                    aria-describedby="emailHelp" placeholder="Enter price">
                                <input type="text" class="form-control col-2 ml-1" id="quantityedit"
                                    aria-describedby="emailHelp" placeholder="Enter quantity">
                                <button type="button" class="btn btn-success col-1 mr-1"
                                    onclick="applyVariantFilter()">Apply</button>
                            </div>
                            <form class="col-12">
                                <table id="variantTable" class="table">
                                    <!-- The header and body rows will be dynamically inserted here -->
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card-header row col-12"
                        style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                        <div class="form-group col-12">
                            <label for="name">Category</label>
                            <select class="custom-select col-12 ml-1" id="category" name="category">
                                <option selected value="">Categories...</option>
                                @foreach ($categories as $category)
                                    <option selected value="{{$category->name}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label for="name">Set</label>
                            <select class="custom-select col-12 ml-1" id="set" name="set">
                                <option selected value="0">Male</option>
                                <option selected value="1">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-header row col-12 mt-2 "
                        style="display: flex;justify-content: end;border-radius:5px;border: 0;box-shadow: 0px 0px 8px #8080807a;">
                        <button class="btn btn-primary" onclick="publish(this)">Publish Edit Product</button>
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
            input.setAttribute('readonly', true);

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
                    handleMultiSelection(attribute, checkbox, input, dropdown); // Gọi hàm khi click vào label
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
    getAttributes('{{$category_id}}')
    function getAttributes(idcategory) {
        $.ajax({
            url: `../products/get-attributes`,
            method: 'GET',
            data: { store_id: 8, idcategory: idcategory },
            success: function (response) {
                renderAttributes(response);
            },
            error: function (xhr, status, error) {
                console.error('Error fetching attributes:', error);
                alert('Không thể tải thuộc tính. Vui lòng thử lại sau.');
            },
        });
    }

</script>
<script>
    let json = {!! $encodedJson !!};
    console.log(json);
    var optionVariants = ["Style", "Color", "Size"];
    var styleVariant = json.product.options[0].values.join(",");
    var colorVariant = json.product.options[1].values.join(",");
    var sizeVariant = json.product.options[2].values.join(",");
    let selectedFiles = [];
    var listpriceconvert = [[0, 1, 2, 3, 4, 5, 6], [0, 0, 0, 0, 1, 2, 3]]
    let priceConvert = listpriceconvert[0]
    function selectAddPrice(target){
        console.log(target.value);
        priceConvert = listpriceconvert[target.value-1]
    }
    const description = json.product.description;
    console.log(description);
    // Set the value of the hidden input linked to Trix
    $("#editor").val(description);

    // Trigger a change event to update Trix Editor's content
    document.querySelector("trix-editor").editor.loadHTML(description);
    $("#style_variant").val(styleVariant)
    $("#color_variant").val(colorVariant)
    $("#size_variant").val(sizeVariant)
    addOptions("styleedit", styleVariant);
    addOptions("coloredit", colorVariant);
    addOptions("sizeedit", sizeVariant);
    generateImageColor();

    const productID = randomId();
    console.log("product ID", productID)
    let varianttmp = json.product.variants;
    const filter = ["All", "All", "All"];
    createtableVariant(optionVariants, varianttmp, filter)
    generateEdit()
    function generateEdit() {
        $("#name").val(json.product.title);
        let imageSizeChart = json.product.imagesizechart;
        $('select[name="set"]').val(json.product.set)
        $('select[name="category"]').val(json.product.category)
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

        let filePreview = document.getElementById('file-preview');

        filePreview.innerHTML = '';
        console.log(json.product.images);
        
        
        if (json.product.images.length > 0) {
            const fetchPromises = json.product.images.map(function (imageurl, index) {
                return fetch(imageurl.src.replace('https://s3.us-west-004.backblazeb2.com/Windycloud', 'https://windycloud.s3.us-west-004.backblazeb2.com'))  // Removed 'no-cors' mode
                    .then(response => response.blob()) // Convert the image to a blob
                    .then(blob => {
                        const fileName = imageurl.src.split('/').pop().split('?')[0]; // Extract file name (without query parameters)

                        // Create a new file from the blob
                        const file = new File([blob], fileName, { type: blob.type });
                        return file;
                    })
                    .catch(error => {
                        console.error('Error fetching the image:', error);
                    });
            });

            // Wait for all fetches to complete
            Promise.all(fetchPromises).then(files => {
                selectedFiles.push(...files);  // Add fetched files to selectedFiles array
                renderFilePreview();  // Render the file preview
            });
        }
        console.log("selectedFiles");
        console.log(selectedFiles);
    }
    function publish(target) {
        $(target).prop('disabled', true);

        const title = $("#name").val();
        const description = $("#editor").val();
        const image = selectedFiles;
        const category = $('select[name="category"]').val();
        const set = $('select[name="set"]').val();
        const imagesizechart = $('#file-sizechart-select').attr('src');
        console.log($("#show_color").prop('checked'));
        if($("#show_color").prop('checked')){
            const image_colorolds = $('[id^="fileimagecolorold["]');
            const image_colornews = $('[id^="fileimagecolornew["]');

            const image_colors_old_src = [];
            const image_colors_new_src = [];

            image_colorolds.each(function() {
                const src = $(this).attr('src');
                const name = $(this).attr('name');
                if (src) {
                    image_colors_old_src.push({
                        'color' : name,
                        'src' : src
                    }); // Add to array if src exists
                }
            });

            image_colornews.each(function() {
                const src = $(this).attr('src');
                const name = $(this).attr('name');
                if (src) {
                    image_colors_new_src.push({
                        'color' : name,
                        'src' : src
                    }); // Add to array if src exists
                }
            });
            console.log(image_colors_old_src); 

            console.log(image_colors_new_src); 

            // Loop through old images and replace their src if a matching new image exists
            image_colors_old_src.forEach(function(oldImage) {
                // Find if the color exists in the new array
                const matchingNewImage = image_colors_new_src.find(function(newImage) {
                    return newImage.color === oldImage.color;
                });

                // If a matching color is found in the new array, replace the old src with the new src
                if (matchingNewImage) {
                    oldImage.src = matchingNewImage.src; // Replace old URL with new URL
                }
            });

            console.log(image_colors_old_src); 
            json.product.imagevariants = image_colors_old_src

        }
        
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
        json.product.title = title;
        json.product.description = description;
        json.product.variants = varianttmp;
        json.product.imagesizechart = imagesizechart;

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

        console.log("category");
        console.log(category);
        console.log("set");
        console.log(set);
        json.product.category = category
        json.product.set = set
        console.log("json")
        console.log(json)

        var formData = new FormData();
        formData.append('json', JSON.stringify(json))
        image.forEach((file) => {
            formData.append('images[]', file)
        })
        formData.append('name', title)
        formData.append('product_id', productID)

        $.ajax({
            url: './{{$product->id}}',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // Handle the response, e.g., reload the page
                if (JSON.parse(response).message) {
                    location.href = '../../products';
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
            style.forEach((st, styleindex) => {
                color.forEach((cl, colorindex) => {
                    size.forEach((sz, sizeindex) => {
                        varianttmp.push({
                            "id": randomId(),
                            "product_id": productID,
                            "title": st + " / " + cl + " / " + sz,
                            "price": "00.00",
                            "sku": null,
                            "quantity": 999,
                            "position": position,
                            "compare_at_price": "",
                            "fulfillment_service": "manual",
                            "inventory_management": null,
                            "option1": st,
                            "option2": cl,
                            "option3": sz,
                            "created_at": "2019-05-28T02:37:13+07:00",
                            "updated_at": "2019-05-30T04:33:43+07:00",
                            "taxable": true,
                            "barcode": null,
                            "grams": 0,
                            "image_id": "53047686077292",
                            "weight": 0.4101,
                            "weight_unit": "lb",
                            "requires_shipping": true
                        })
                        position++;
                    })
                })
            })
        } else {

        }
        console.log("varianttmp");
        console.log(varianttmp);
        createtableVariant(optionVariants, varianttmp, filter)
    }

    function generateImageColor() {
        console.log("colorVariant");
        console.log(colorVariant);
        colors = colorVariant.split(",");
        console.log(colors);
        // $("#list_image_color").html(``);
        let html = ``;
        
        colors.forEach(color => {
            const matchedImage = Array.isArray(json.product?.imagevariants)
                ? json.product.imagevariants.filter((image) => image.color.trim() === color.trim())
                : [];

            let url_old = matchedImage.length > 0 ? matchedImage[0].src : null;
            html += `
                <div class="col-12 m-2" style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                    <div class="form-group col-12">
                        <label for="designs" style="font-weight: bold;">Choose image variant color(${color.trim()})</label>
                        <input type="file" class="form-control" name="image_color[${color.trim()}]" id="image_color[${color.trim()}]" onchange="uploadAndPreviewImage('${color.trim()}')" >
                    </div>
                    <div class="m-3 col-12" id="file-image-color-old-preview-${color.trim()}">
                        Old: 
                        <img src="${url_old}" 
                            name="${color.trim()}" 
                            id="fileimagecolorold[${color.trim()}]" 
                            alt="Size chart preview" 
                            style="max-width: 150px;">
                    </div>
                    <div class="m-3 col-12" id="file-image-color-preview-${color.trim()}">
                        
                    </div>
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
            url: '../upload-image-color',  // Replace with your actual upload URL
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
                imgElement.id = `fileimagecolornew[${color}]`;
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

        // Loop through the variantlist array to create rows
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
                    if (option === "Style") {
                        cell.innerHTML = variant.option1;
                    } else if (option === "Color") {
                        cell.innerHTML = variant.option2;
                    } else if (option === "Size") {
                        cell.innerHTML = variant.option3;
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

    document.getElementById('designs').addEventListener('change', function (event) {
        let filePreview = document.getElementById('file-preview');

        Array.from(event.target.files).forEach(function (file) {
            selectedFiles.push(file);
        });

        filePreview.innerHTML = '';

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
                        image.classList.add('img-fluid');  // Bootstrap class to make the image responsive
                        image.style.objectFit = 'contain';  // Cover the fixed size
                        image.style.backgroundColor = "#bdbdbd";
                        image.style.width = '200px';
                        image.style.height = '200px';

                        let deleteBtn = document.createElement('button');
                        deleteBtn.innerHTML = '<i class="fa fa-trash"></i>';
                        deleteBtn.classList.add('btn', 'btn-danger', 'position-absolute', 'top-0', 'end-0', 'm-1', 'p-1', 'btn-sm');
                        deleteBtn.style.borderRadius = '50%';
                        deleteBtn.style.padding = '5px';

                        deleteBtn.addEventListener('click', function () {
                            selectedFiles.splice(index, 1);  // Remove the file from the array
                            renderFilePreview();  // Re-render the preview
                        });

                        let filename = document.createElement('p');
                        filename.classList.add('text-center', 'mt-2');  // Center the filename text
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
    });

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