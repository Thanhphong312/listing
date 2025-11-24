@extends('layouts.app')

@section('page-title', __('Edit Products'))
@section('page-heading', __('Edit Products'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Edit Products')
</li>
@stop
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
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
                                <label for="name">Name</label>
                                <input class="form-control" type="text" name="name" id="name">
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
                            <div class="form-check ml-2">
                                <input class="form-check-input" type="checkbox"
                                    id="ornament" onclick="showOrnament(this)">
                                <label class="form-check-label" for="ornament">ornament</label>
                            </div>
                            <div class="col-12 showOrnament" style="display:none">
                                <label for="name">Aerosols</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aerosols"
                                        value="yes" id="aerosols_yes">
                                    <label class="form-check-label" for="aerosols_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aerosols"
                                        value="no" id="aerosols_no" >
                                    <label class="form-check-label" for="aerosols_no">No</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aerosols"
                                        value="not sure" id="aerosols_not_sure">
                                    <label class="form-check-label" for="aerosols_not_sure">Not Sure</label>
                                </div>
                            </div>

                            <div class="col-12 showOrnament" style="display:none">
                                <label for="name">Flammable Liquid</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flammable_liquid"
                                        value="yes" id="flammable_liquid_yes">
                                    <label class="form-check-label" for="flammable_liquid_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flammable_liquid"
                                        value="no" id="flammable_liquid_no" >
                                    <label class="form-check-label" for="flammable_liquid_no">No</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flammable_liquid"
                                        value="not sure" id="flammable_liquid_not_sure">
                                    <label class="form-check-label" for="flammable_liquid_not_sure">Not Sure</label>
                                </div>
                            </div>
                            <div class="col-12 showOrnament" style="display:none">
                                <label for="name">Contains Batteries or Cells?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="contains_batteries_or_cells"
                                        value="batteries" id="contains_batteries_or_cells_Batteries">
                                    <label class="form-check-label" for="contains_batteries_or_cells_Batteries">Batteries</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="contains_batteries_or_cells"
                                        value="cells" id="contains_batteries_or_cells_Cells">
                                    <label class="form-check-label" for="contains_batteries_or_cells_Cells">Cells</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="contains_batteries_or_cells"
                                        value="none" id="contains_batteries_or_cells_none" >
                                    <label class="form-check-label" for="contains_batteries_or_cells_none">None</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="contains_batteries_or_cells"
                                        value="not sure" id="contains_batteries_or_cells_not_sure">
                                    <label class="form-check-label" for="contains_batteries_or_cells_not_sure">No Sure</label>
                                </div>
                            </div>
                            <div class="col-12 showOrnament" style="display:none">
                                <label for="name">Other Dangerous Goods or Hazardous Materials</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="other_dangerous_goods_or_hazardous_materials"
                                        value="yes" id="other_dangerous_goods_or_hazardous_materials_yes">
                                    <label class="form-check-label" for="other_dangerous_goods_or_hazardous_materials_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="other_dangerous_goods_or_hazardous_materials"
                                        value="no" id="other_dangerous_goods_or_hazardous_materials_no" >
                                    <label class="form-check-label" for="other_dangerous_goods_or_hazardous_materials_no">No</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="other_dangerous_goods_or_hazardous_materials"
                                        value="not sure" id="other_dangerous_goods_or_hazardous_materials_not_sure">
                                    <label class="form-check-label" for="other_dangerous_goods_or_hazardous_materials_not_sure">Not Sure</label>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="name">CA Prop 65: Repro. Chems</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ca_prop_65_repro_chems"
                                        value="yes" id="ca_prop_65_repro_chems_yes">
                                    <label class="form-check-label" for="ca_prop_65_repro_chems_yes" >Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ca_prop_65_repro_chems"
                                        value="no" id="ca_prop_65_repro_chems_no" checked>
                                    <label class="form-check-label" for="ca_prop_65_repro_chems_no">No</label>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="name">CA Prop 65: Carcinogens</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ca_prop_65_carcinogens"
                                        value="yes" id="ca_prop_65_carcinogens_yes">
                                    <label class="form-check-label" for="ca_prop_65_carcinogens_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ca_prop_65_carcinogens"
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
                                            value="Red, Ash, Light Pink, Pink, Purple, Sand, Sport Grey, White, Orange, Black, Dark Chocolate, Dark Heather, Forest Green, Maroon, Military Green, Navy" 
                                            onchange="changeColor(this)">
                                        <input class="form-control" type="checkbox" name="show_color" id="show_color" onchange="click_show_color(this)">
                                    </div>
                                    <div class="col-sm-2">
                                        <input class="btn btn-sm" type="checkbox" id="color_variant_checked"
                                            onclick="hideinput('color_variant',this)"></input>
                                    </div>
                                    <div class="card-header row col-10 m-3"
                                        style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;display:none;" id="list_image_color">
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

                                <input type="text" class="form-control col-1 ml-1  mt-2" id="priceedit"
                                    aria-describedby="emailHelp" placeholder="Enter price">
                                <input type="text" class="form-control col-2 ml-1  mt-2" id="quantityedit"
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
                                    <input class="form-control" type="text" name="discount" id="discount" value="{{$product->discount}}">
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
    let json = {!! $encodedJson !!};
    console.log(json);

    let selectedCategories = [];
    let idcategory = json.product.category_id;
    let selectedAttributes = []; // Mảng lưu trữ các thuộc tính đã chọn
    $("input[name='ca_prop_65_repro_chems']:checked").val(json.product.ca_prop_65_repro_chems);
    $("input[name='ca_prop_65_carcinogens']:checked").val(json.product.ca_prop_65_carcinogens);
    $("#weight").val(json.product.weight);
    $("#height").val(json.product.height);
    $("#width").val(json.product.width);
    $("#length").val(json.product.length);
    console.log("selectedAttributes");
    console.log(selectedAttributes);
    var optionVariants = ["Style", "Color", "Size"];
    var styleVariant = json.product.options[0].values.join(",");
    var colorVariant = json.product.options[1].values.join(",");
    var sizeVariant = json.product.options[2].values.join(",");
    let selectedFiles = [];
    let sortable = null;
    var listpriceconvert = [[0, 1, 2, 3, 4, 5, 6], [0, 0, 0, 1, 2, 3, 4]]
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
    function showOrnament(target) {
        document.querySelectorAll(".showOrnament").forEach(element => {
            element.style.display = target.checked ? '':'none';
        });
    }

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
                // var previewDiv = document.getElementById('file-sizechart-preview');

                // // Clear any previous content in the file-preview
                // previewDiv.innerHTML = '';

                // // Create an image element to display the selected image
                // var imgElement = document.createElement('img');
                // imgElement.src = imageSizeChart;
                // imgElement.id = 'file-sizechart-select';
                // imgElement.alt = 'Size chart preview';
                // imgElement.style.maxWidth = '150px'; // Ensure the image fits the preview area

                // // Append the image to the preview div
                // previewDiv.appendChild(imgElement);
            }
        });
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
        
        if(styleVariant==""){
            $("#style_variant_checked").prop('checked',true);
            $("#style_variant").prop('disabled', true);
        }
        if(colorVariant==""){
            $("#color_variant_checked").prop('checked',true);
            $("#color_variant").prop('disabled', true);
            
        }
        if(sizeVariant==""){
            $("#size_variant_checked").prop('checked',true);
            $("#size_variant").prop('disabled', true);
        }
        if(json.product.aerosols){
            $("#ornament").prop('checked', true);
            document.querySelectorAll(".showOrnament").forEach(element => {
                element.style.display = '';
            });
            const radiosaerosols = document.getElementsByName('aerosols');

            radiosaerosols.forEach(radio => {
                // Đặt giá trị checked thành true nếu giá trị khớp
                radio.checked = (radio.value === json.product.aerosols);
            });

            const radiosflammable_liquid = document.getElementsByName('flammable_liquid');

            radiosflammable_liquid.forEach(radio => {
                // Đặt giá trị checked thành true nếu giá trị khớp
                radio.checked = (radio.value === json.product.flammable_liquid);
            });

            const radioscontains_batteries_or_cells = document.getElementsByName('contains_batteries_or_cells');

            radioscontains_batteries_or_cells.forEach(radio => {
                // Đặt giá trị checked thành true nếu giá trị khớp
                radio.checked = (radio.value === json.product.contains_batteries_or_cells);
            });

            const radiosother_dangerous_goods_or_hazardous_materials = document.getElementsByName('other_dangerous_goods_or_hazardous_materials');

            radiosother_dangerous_goods_or_hazardous_materials.forEach(radio => {
                // Đặt giá trị checked thành true nếu giá trị khớp
                radio.checked = (radio.value === json.product.other_dangerous_goods_or_hazardous_materials);
            });
        }
    }
    function publish(target) {
        $(target).prop('disabled', true);
        
        const title = $("#name").val();
        const description = $("#editor").val();
        
        console.log("The submit selectedFiles");
        console.log(selectedFiles);
        const image = selectedFiles;
        // const category = $('select[name="category"]').val();
        // const set = $('select[name="set"]').val();
        const discount = $("#discount").val();
        if(discount==""){
            alert("Please add discount");
            $(target).prop('disabled', false);
        }
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
        if (varianttmp.length == 0) {
            json.product.variants = {
                'only_price': $("#only_price").val(),
                'only_quantity': $("#only_quantity").val(),
            }
        } else {
            json.product.variants = varianttmp;
        }
        json.product.imagesizechart = imagesizechart;


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

        // console.log("category");
        // console.log(category);
        // console.log("set");
        // console.log(set);
        // json.product.category = category
        // json.product.set = set

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
        }).filter(attribute => attribute.values.length > 0);;
        

        console.log("json")
        console.log(json)
        if(selectedAttributes.length==0){
            alert("please wait load Attributes");
            $(target).prop('disabled', false);
            return;
        }
        var formData = new FormData();
        formData.append('json', JSON.stringify(json))

        // Add image (selectedFiles) in images[]
        image.forEach((file) => {
            formData.append('images[]', file)
        })
        formData.append('name', title)
        formData.append('product_id', productID)
        formData.append('discount', discount)

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
        console.log(json.product?.imagevariants.length);
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
        
        if(json.product?.imagevariants.length>0){
            $("#show_color").prop('checked', true);
            $("#list_image_color").css('display', 'block');
        }
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
            cellPrice.innerHTML = `<input type="text" value="${json.product.variants.only_price}" class="price-input form-control" id="only_price" onchange="changeonlyprice()"/>`;

            var cellQuantity = row.insertCell();
            cellQuantity.innerHTML = `<input type="number" value="${json.product.variants.only_quantity}" class="quantity-input form-control" id="only_quantity" onchange="changeonlyquantity()"/>`;

        }
    }
    function changeonlyprice(){
        json.product.variants.only_price = $("#only_price").val();
    }
    function changeonlyquantity(){
        json.product.variants.only_quantity = $("#only_quantity").val();
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
        Array.from(event.target.files).forEach(function (file) {
            selectedFiles.push(file);
        });

        console.log("The selectedFiles when choose new file to upload");
        console.log(selectedFiles);

        renderFilePreview();
    });

    // Function to re-render file preview after any changes (addition/removal)
    function renderFilePreview() {
        let filePreview = document.getElementById('file-preview');
        filePreview.innerHTML = '';

        if (selectedFiles.length > 0) {
            let row = document.createElement('div');
            row.classList.add('row');  // Create a Bootstrap row
            row.id = 'sortable-container'; // Add ID for Sortable

            selectedFiles.forEach(function (file, index) {
                let col = document.createElement('div');
                col.classList.add('col-md-3', 'mb-3', 'position-relative', 'sortable-item');  // Column style with relative position for delete button
                col.setAttribute('data-index', index); // Add index for tracking

                // Add cursor style and subtle transition
                col.style.cursor = 'move';
                col.style.transition = 'transform 0.2s ease';

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

                        // Delete button
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

            // Initialize Sortable
            if (sortable) {
                sortable.destroy(); // Destroy previous instance if exists
            }

            sortable = new Sortable(row, {
                animation: 150, // Animation speed in ms
                ghostClass: 'sortable-ghost', // Class for the dragging item
                chosenClass: 'sortable-chosen', // Class for the chosen item
                dragClass: 'sortable-drag', // Class for the dragging item
                handle: '.sortable-item', // Drag handle selector
                onEnd: function(evt) {
                    // Update selectedFiles array after drag
                    const oldIndex = evt.oldIndex;
                    const newIndex = evt.newIndex;
                    
                    // Reorder the selectedFiles array
                    const movedItem = selectedFiles.splice(oldIndex, 1)[0]; //Get chosen item and delete it from selectedFiles
                    selectedFiles.splice(newIndex, 0, movedItem); // Insert chosen item in newIndex of selectedFiles

                    console.log("The change selectedFiles");
                    console.log(selectedFiles);

                    // Re-render to update indices
                    renderFilePreview();
                }
            });
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
        let defaultAttributes = [];
        console.log(idcategory);
        console.log({{$category_id}});
        if(parseInt(idcategory)==parseInt({{$category_id}})){
            defaultAttributes = json.product.selectedAttributes
        }
        

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
            input.setAttribute('readonly', true);

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
            url: `../get-attributes`,
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
@stop

@endsection