@extends('layouts.app')

@section('page-title', __('Add Products'))
@section('page-heading', __('Add Products'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Add Products')
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
                            <div class="form-group col-10">
                                <label for="name">Name</label>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <input class="form-control" type="text" name="name" id="name" style="flex: 1;">
                                    <span id="nameCount" style="min-width: 50px; text-align: right; color: gray;">0</span>
                                </div>
                            </div>
                            <!-- <div class="form-group col-10">
                                <label for="editor">Description</label>
                                <input id="editor" type="hidden" name="description" value="{{ old('content', '') }}">
                                <trix-editor input="editor" row="4"></trix-editor>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="card-header row col-12"
                            style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                            <div class="form-group col-10">
                                <label for="name">Template</label>
                                <select class="form-control select2" id="template" name="template"
                                    onchange="changetemplate()">
                                    <option value="">template...</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}">
                                            {{ $template->name }}
                                        </option>
                                    @endforeach
                                </select>
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
     $('#name').on('input', function() {
        $('#nameCount').text($(this).val().length);
    });
    var optionVariants = ["Style", "Color", "Size"];
    var styleVariant = "T-shirt";
    var colorVariant = "Black, White, Sand, Dark Heather, Sport Grey, Ash, Navy, Light Blue, Light Pink, Military Green, Forest Green, Maroon, Purple, Dark Chocolate, Red, Orange";
    var sizeVariant = "S, M, L, XL, 2XL, 3XL";
    let selectedFiles = [];
    let sortable = null;
    let selectedtemplete = 0;
    var listpriceconvert = [[0, 1, 2, 3, 4, 5, 6], [0, 0, 0, 0, 1, 2, 3]]
    let priceConvert = listpriceconvert[0]
    function selectAddPrice(target) {
        console.log(target.value);
        priceConvert = listpriceconvert[target.value - 1]
    }
    const description = `<div><strong>Welcome to the store!<br></strong>&nbsp;_ Experience unparalleled comfort and style with our versatile collection of hoodies, sweatshirts, and t-shirts. Crafted with a passion for providing the perfect shopping experience, our products are designed to keep you warm and cozy throughout the winter.<br><br></div><div>&nbsp;_ Feel free to explore a wide range of soft, comfy hoodies that are perfect for the season. We take pride in offering customization options, allowing you to choose your preferred color or even request a custom design. Should you have any questions or specific concerns, our dedicated team is always ready to assist – just drop us a message.<br><br></div><div>&nbsp;_ The standout feature of our products lies in the captivating images printed on the fabric using cutting-edge digital printing technology. Unlike embroidered designs, these images are seamlessly integrated, ensuring they neither peel off nor fade over time. Our hoodies, made from a blend of 50% cotton and 50% polyester, provide a classic fit with a double-lined hood and color-matched drawcord.<br><br></div><div>&nbsp;_ For those seeking premium shirts, our collection of soft, high-quality shirts is a perfect fit. Immerse yourself in 100% cotton shirts, available in various colors and styles. The innovative digital printing technology ensures that the vibrant images on these shirts remain intact for the long haul.<br><br></div><div>&nbsp;_ Embrace the winter chill with our warm sweatshirts, designed with your comfort in mind. The images are intricately printed using advanced digital technology, creating a lasting impression. The sweatshirts, featuring a classic fit and 1x1 rib with spandex, guarantee enhanced stretch and recovery.<br><br></div><div>&nbsp;_ Elevate your winter wardrobe with our curated selection of cozy and stylish apparel. Your satisfaction is our priority, and we look forward to making your shopping experience truly exceptional.<br><br></div><div>&nbsp;<strong>SIZE CHART</strong><figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:422,&quot;url&quot;:&quot;https://lh7-rt.googleusercontent.com/docsz/AD_4nXcYWzTf9lQI-6q9WAfOVAyBsi2k5d8Sg761yGCaH8v9bcBIeU-h5TYr8qLa1VgImzcufQekU4U3Vk9Vcoi5tJCfoTox3U6nNP23ZjTGomEn_O1plUi482krLx5bq5avivsnUozwNQ?key=f1JtNQbLIQCKVVZiWjM_1g&quot;,&quot;width&quot;:549}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://lh7-rt.googleusercontent.com/docsz/AD_4nXcYWzTf9lQI-6q9WAfOVAyBsi2k5d8Sg761yGCaH8v9bcBIeU-h5TYr8qLa1VgImzcufQekU4U3Vk9Vcoi5tJCfoTox3U6nNP23ZjTGomEn_O1plUi482krLx5bq5avivsnUozwNQ?key=f1JtNQbLIQCKVVZiWjM_1g\" width=\"549\" height=\"422\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>RETURNS OR EXCHANGES<br></strong><br></div><div>All of our shirts are custom printed so we do not accept returns or exchanges due to the sizing so please make sure you take all the steps to ensure you get the size you are wanting. However, if there are any issues with the shirt itself, please message us and we'd be happy to help correct the error.<br><br></div><div><strong>PRODUCTION AND SHIPPING<br></strong><br></div><div>Production: 1-3 days&nbsp;<br>Standard Shipping : 3-6 business days after production time<br><br></div><div><strong>THANK YOU<br></strong><br></div>`;
    // Set the value of the hidden input linked to Trix
    
    // addOptions("styleedit", styleVariant);
    // addOptions("coloredit", colorVariant);
    // addOptions("sizeedit", sizeVariant);
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
            "handle": "",
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
        const image = selectedFiles;
       
       
        console.log("title");
        console.log(title);
        console.log("image");
        console.log(image);
        


        console.log("selectedtemplete")
        console.log(selectedtemplete)


        var formData = new FormData();
        image.forEach((file) => {
            formData.append('images[]', file)
        })
        formData.append('title', title)
        formData.append('selectedtemplete', selectedtemplete)
        $.ajax({
            url: './product-template',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // Handle the response, e.g., reload the page
                if (JSON.parse(response).message) {
                   location.href = '../products';
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

    // function generateImageColor() {
    //     console.log("colorVariant");
    //     console.log(colorVariant);
    //     colors = colorVariant.split(",");
    //     console.log(colors);
    //     // $("#list_image_color").html(``);
    //     let html = ``;
    //     colors.forEach(color => {
    //         html += `
    //             <div class="col-12 m-2" style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
    //                 <div class="form-group col-12">
    //                     <label for="designs" style="font-weight: bold;">Choose image variant color(${color.trim()})</label>
    //                     <input type="file" class="form-control" name="image_color[${color.trim()}]" id="image_color[${color.trim()}]" onchange="uploadAndPreviewImage('${color.trim()}')" >
    //                 </div>

    //                 <div class="m-3 col-12" id="file-image-color-preview-${color.trim()}"></div>
    //             </div>
    //         `;
    //     });
    //     $("#list_image_color").html(html);
    // }

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

    document.getElementById('designs').addEventListener('change', function (event) {
        Array.from(event.target.files).forEach(function (file) {
            selectedFiles.push(file);
        });

        console.log("The first selectedFiles");
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
    function changetemplate() {
        // Get selected option element
        selectedtemplete = $("#template").val();
        
        console.log(selectedtemplete);
        // if (selectedOption) {
        //     // Retrieve JSON data from data-json attribute and parse it
        //     let dataJson = selectedOption.getAttribute('data-json');
        //     if (dataJson) {
        //         let templateData = JSON.parse(dataJson);
        //         json = templateData;

        //         console.log("Updated json:", json);
        //     }
        // }
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