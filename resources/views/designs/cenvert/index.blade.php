@extends('layouts.app')

@section('page-title', __('Design Items'))
@section('page-heading', __('Design Items'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    <a href="">@lang('Design Items')</a>
</li>
<li class="breadcrumb-item">
    Cenvert
</li>
@stop
<style>
    .active {
        border: 2px solid white !important;
    }

    .active-color {
        border: 2px solid #ff0000 !important;
    }

    .active-choose-mockup {
        border: 2px solid #00b004 !important;
    }

    .active-choose-mockup.border-black {
        border: 2px solid black !important;
    }

    .active-choose-mockup.border-gray {
        border: 2px solid rgb(128, 128, 128) !important;
    }

    .active-choose-mockup-first {
        background-color: #b00000 !important;
    }
</style>
@section('content')
<div class="modal fade" id="choosehuman">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Choose mockup human</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                <form id="body_choosehuman" class="form-horizontal" enctype="multipart/form-data">

                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="genPosition">
    <div class="modal-dialog modal-lg" style="max-width: 1400px;align-items: center;">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Choose mockup human</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                <form id="body_genPosition" class="form-horizontal" enctype="multipart/form-data">

                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="genPositionHumans">
    <div class="modal-dialog modal-lg" style="max-width: 1400px;align-items: center;">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Choose mockup human</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                <form id="body_genPositionHumans" class="form-horizontal" enctype="multipart/form-data">

                </form>
            </div>

        </div>
    </div>
</div>
<div class="element-box">
    <div class="card">
        <div class="card-body">
            @include('partials.messages')
            <div class="row">
                <div class="col-12 m-2 rounded" style="border: 2px solid;">
                    <input type="text" id="title" class="form-control" placeholder="Title" aria-label="Title">
                </div>
                <div class="col-12 bg-secondary m-2 rounded" style="border: 2px solid;">
                    @foreach ($json as $key => $type)
                        <div class="btn btn-success m-2" id="mockup_{{$key}}" onclick="chooseType('{{$key}}')">
                            {{$key}}
                        </div>
                    @endforeach
                    <div class="btn btn-light btn-sm m-2" onclick="setup()">setup</div>
                    <!-- <button id="preview" type="button" class="btn btn-info btn-sm m-2" onclick="preview()" disabled >preview</button> -->
                    <button id="generate" type="button" class="btn btn-danger btn-sm m-2"
                        onclick="generate()">gen</button>
                    <button id="next" class="btn btn-primary btn-sm m-2" onclick="next()"
                        style="display:none">next</button>
                </div>
                <div class="col-12 row">
                    <div class="col-3 row">
                        <div class="col-12 bg-light m-2" style="border: 2px solid;">
                            <div class="input-group mb-3 mt-3">
                                <input type="text" class="form-control" id="getdesign" placeholder="Design ID"
                                    aria-label="Design ID" aria-describedby="button-addon2">
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2"
                                    onclick="getDesignId()">Get</button>
                                <div id="listdesign" class="row w-100">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="col-12 bg-light m-2 rounded row" style="border: 2px solid;">
                            <div class="col-2">
                                <select class="form-control m-2" id="numberside" name="numberside"
                                    onchange="chooseumberside()">
                                    <option value="0" selected>0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <input type="text" class="form-control m-2" id="front_url" placeholder="front url"
                                    value="" onchange="addUrlFront()">
                            </div>
                            <div class="col-5">
                                <input type="text" class="form-control m-2" id="back_url" placeholder="bank url"
                                    value="" onchange="addUrlBack()">
                            </div>
                        </div>
                        <div class="col-12 bg-light m-2 rounded" style="border: 2px solid;">
                            <span class="m-2">
                                <input type="radio" onclick="chooseTypeColor('all')" name="rColor" value="All" checked>
                                <label for="allColors"> Tất cả</label>
                            </span>
                            <span class="m-2">
                                <input type="radio" onclick="chooseTypeColor('dark')" name="rColor" value="All">
                                <label for="darkColor"> Tối (Black)</label>
                            </span>
                            <span class="m-2">
                                <input type="radio" onclick="chooseTypeColor('light')" name="rColor" value="All">
                                <label for="lightColor"> Sáng (Light/White)</label>
                            </span>
                            @foreach ($colors as $color)
                                <span id="color_{{$color->id}}"
                                    style="border:1px solid black; width:20px; height:27px; background-color:{{$color->hex}};"
                                    class="btn btn-square-md m-1" data-color="{{$color->hex}}" data-type="{{$color->type}}"
                                    onclick="chooseColor('{{$color->id}}','{{$color->name}}','{{$color->hex}}','{{$color->type}}')"></span>
                            @endforeach
                            <div class="form-check m-2">
                                <input class="form-check-input" type="checkbox" value="" id="choosefirstmockup" disabled
                                    onclick="choosefirstmockup(this)">
                                <label class="form-check-label" for="choosefirstmockup">
                                    Choose mockup first
                                </label>
                            </div>
                        </div>
                        <div class="col-12 bg-light m-2 rounded row" style="border: 2px solid; min-height:200px;"
                            id="mockup">

                        </div>
                        <div class="col-12 bg-light m-2 rounded row" style="border: 2px solid; min-height:200px;"
                            id="list-gen-mockup">

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>

</style>
@section('scripts')

<script>
    let listmockupchoose = [];
    let listmockuphumanchoose = [];
    let listcolorchoose = [];
    let listfront = [];
    let listback = [];
    let linksleeveleft = "";
    let linksleeveright = "";
    let mockupchoose = [];
    let mockupchooseHuman = [];
    let classactive = "";
    let typechoose = "shirt";
    let mockupUrlchoose = "";
    let mockupUrlchoosehuman = "";
    let frontCoordinatesvalue = "0,0,0,0";
    let backCoordinatesvalue = "0,0,0,0";
    let frontCoordinatesvaluehuman = "0,0,0,0";
    let backCoordinatesvaluehuman = "0,0,0,0";
    let firstmockup = "";
    let classchoosemockup = "active-choose-mockup";
    let numberside = 0;
    let designid = 0;
    $("#numberside").val(numberside);
    let type = "";
    let position = "";
    function chooseumberside() {
        numberside = $("#numberside").val();
        $("#mockup").html("");
        listmockupchoose = [];
        listmockuphumanchoose = [];
    }
    function addUrlFront() {
        linkfront = $("#front_url").val();
    }
    function addUrlBack() {
        linkback = $("#back_url").val();
    }
    function reset() {
        listmockupchoose = [];
        listmockuphumanchoose = [];
        listfront = [];
        listback = [];

        mockupchoose = [];
        mockupchooseHuman = [];
        classactive = "";
        typechoose = "shirt";
        mockupUrlchoose = "";
        mockupUrlchoosehuman = "";
        firstmockup = "";
        classchoosemockup = "active-choose-mockup";
        numberside = 0;
        designid = 0;
    }
    function getDesignId() {
        reset()
        var id = $("#getdesign").val();
        console.log(id);
        designid = id;

        $.ajax({
            url: '{{route("designs.getDesignUrl")}}',
            method: 'get',
            data: { id: id },
            success: function (response) {
                console.log(response);

                const design_data = response.design_data || {};
                console.log(design_data);
                if(response.product_listing!=null){
                    alert("This design is used in product listing: "+response.product_listing);
                }
                // Variables to track 'front' and 'back' presence
                let has_front = 0;
                let has_back = 0;

                // Iterate over the keys of design_data
                Object.keys(design_data).forEach(key => {
                    let type = ""; // Declare type within the loop
                    if (key.startsWith("front")||key.startsWith("FRONT")) {
                        has_front = 1;
                        type = key.includes('_bl') ? "lights" : "darks";
                        listfront.push([
                            type,
                            design_data[key]
                        ]);
                    }
                    if (key.startsWith("back")||key.startsWith("BACK")) {
                        has_back = 1;
                        type = key.includes('_bl') ? "lights" : "darks";
                        listback.push([
                            type,
                            design_data[key]
                        ]);
                    }
                });

                const numbersize = has_front + has_back;

                // Determine numbersize
                numberside = (has_front ? 1 : 0) + (has_back ? 1 : 0);
                $("#numberside").val(numberside)
                // Hiển thị tiêu đề trong ô input
                const titleInput = document.getElementById('title');
                if (titleInput && response.title) {
                    titleInput.value = response.title; // Đặt tiêu đề vào ô input
                }

                // Xóa các hình ảnh cũ trước khi thêm mới
                const listGenContainer = document.getElementById('listdesign');
                listGenContainer.innerHTML = ''; // Xóa nội dung trước đó

                // Tạo các thẻ hình ảnh
                const designData = response.design_data || {};

                Object.keys(designData).forEach(key => {
                    const link = designData[key];
                    let style = '';

                    // Xác định style cho mỗi key
                    if (key.includes('_bl')) {
                        style = 'border-black';
                    } else if (key.includes('_wt')) {
                        style = 'border-gray'; // Màu xám
                    }
                    const createImageColumn = (link, className, style) => {
                        if (!link) return null;

                        const figureElement = document.createElement('figure');
                        const imageElement = document.createElement('img');
                        const aElement = document.createElement('div');
                        aElement.textContent = key;
                        aElement.style.textAlign = 'center';
                        aElement.style.fontWeight = 'bold';

                        imageElement.src = link;
                        imageElement.className = `img-thumbnail active-choose-mockup mt-2 mb-2 ${style || ''}`;
                        imageElement.alt = 'Generated Mockup';

                        figureElement.appendChild(imageElement);
                        figureElement.appendChild(aElement);

                        const colElement = document.createElement('div');
                        colElement.className = className;
                        colElement.appendChild(figureElement);

                        return colElement;
                    };

                    const colElement = createImageColumn(link, 'col-6', style);
                    if (colElement) {
                        listGenContainer.appendChild(colElement);
                    }
                });
            },
            error: function () {
                const titleInput = document.getElementById('title');
                if (titleInput) {
                    titleInput.value = "Design not found!";
                }
            }
        });
    }

    function chooseType(name) {
        listmockupchoose = [];
        $("[id^='mockup_']").removeClass("active");
        type = name;
        $("#mockup_" + name).addClass("active");
        console.log(numberside);
        if (designid == 0 && numberside == 0) {
            alert("please get design id or choose numbersize!")
            return;
        }
        $.ajax({
            url: '{{route("designs.ajaxMockup")}}',
            method: 'get',
            data: {
                name: name,
                numberside: numberside
            },
            success: function (response) {
                $("#mockup").html(response);
            }
        })
    }

    function createDesign(name) {
        $.ajax({
            url: '{{route("designs.ajaxMockup")}}',
            method: 'get',
            data: {
                name: name,
                numberside: numberside

            },
            success: function (response) {
                $("#mockup").html(response);
            }
        })
    }

    function chooseColor(id, name, hex, type) {
        console.log(hex)
        if ($("#color_" + id).hasClass("active-color")) {
            $("#color_" + id).removeClass("active-color");
            listcolorchoose = listcolorchoose.filter(item => item[0] !== hex);
        } else {
            $("#color_" + id).addClass("active-color");
            listcolorchoose.push([hex, type]);
        }
    }
    chooseTypeColor('all')
    function chooseTypeColor(typeColor) {
        const checkboxes = document.querySelectorAll('span[id^="color_"]'); // Get all spans with id starting with "color_"
        console.log(checkboxes); // Debugging: List of all color elements
        console.log(typeColor);  // Debugging: Type of color selection

        // Create JavaScript arrays from the PHP arrays
        const darkColors = {!! json_encode($darks) !!};
        const lightColors = {!! json_encode($lights) !!};
        listcolorchoose = []
        // Remove 'active-color' from all elements
        checkboxes.forEach(checkbox => {
            const colorId = parseInt(checkbox.id.split('_')[1], 10); // Extract and convert the id after "color_"
            const hex = checkbox.getAttribute('data-color')
            const type = checkbox.getAttribute('data-type')
            console.log("colorId:", colorId); // Debugging: The current color ID
            switch (typeColor) {
                case 'all':
                    console.log("Applying 'active-color' to all colors");
                    $(checkbox).addClass('active-color');
                    listcolorchoose.push([hex, type]);

                    break;

                case 'dark':
                    console.log("Checking for dark colors");
                    console.log("Dark Colors:", darkColors);
                    // Apply 'active-color' only to elements in the darkColors array
                    if (darkColors.includes(colorId)) {
                        $(checkbox).addClass('active-color');
                        console.log("Color is dark, adding 'active-color'");
                        listcolorchoose.push([hex, type]);
                    } else {
                        $(checkbox).removeClass('active-color');
                        listcolorchoose = listcolorchoose.filter(item => item[0] !== hex);
                    }
                    break;

                case 'light':
                    console.log("Checking for light colors");
                    // Apply 'active-color' only to elements in the lightColors array
                    if (lightColors.includes(colorId)) {
                        $(checkbox).addClass('active-color');
                        console.log("Color is light, adding 'active-color'");
                        listcolorchoose.push([hex, type]);
                    } else {
                        $(checkbox).removeClass('active-color');
                        listcolorchoose = listcolorchoose.filter(item => item[0] !== hex);

                    }
                    break;

                default:
                    console.log("No valid typeColor provided, removing 'active-color' from all");
                    $(checkbox).removeClass('active-color');

                    break;
            }
        });
    }

    function chooseDesign(target) {
        const $target = $(target); // Chuyển đổi target thành jQuery object để dễ thao tác

        // Lấy giá trị data-type và data-key
        const dataType = $target.data("type");
        const dataKey = $target.data("key");

        // Kiểm tra xem phần tử đã có lớp 'active-choose-mockup' chưa
        if ($target.hasClass("active-choose-mockup")) {
            // Nếu có, xóa lớp và loại bỏ dữ liệu khỏi listmockupchoose
            $target.removeClass("active-choose-mockup");
            listmockupchoose = listmockupchoose.filter(item => item.dataType !== dataType || item.dataKey !== dataKey);
            mockupUrlchoose = "";
        } else {
            // Nếu chưa có, thêm lớp và thêm dữ liệu vào listmockupchoose
            $target.addClass("active-choose-mockup");
            listmockupchoose.push({ dataType, dataKey });
            mockupUrlchoose = $target.attr('src'); // Sửa lại cách lấy thuộc tính 'src'

        }
    }
    function chooseDesignHuman(target) {
        const $target = $(target); // Chuyển đổi target thành jQuery object để dễ thao tác

        // Lấy giá trị data-type và data-key
        const dataType = $target.data("type");
        const dataKey = $target.data("key");

        // Kiểm tra xem phần tử đã có lớp 'active-choose-mockup' chưa
        if ($target.hasClass("active-choose-mockup")) {
            $target.removeClass("active-choose-mockup");
            listmockuphumanchoose = listmockuphumanchoose.filter(item => item.dataType !== dataType || item.dataKey !== dataKey);
            mockupUrlchoosehuman = "";

        } else {
            $target.addClass("active-choose-mockup");
            listmockuphumanchoose.push({ dataType, dataKey });
            mockupUrlchoosehuman = $target.attr('src'); // Sửa lại cách lấy thuộc tính 'src'

        }
    }
    let listurl = [];

    function setup() {
        console.log("position");
        console.log(frontCoordinatesvalue);
        console.log(backCoordinatesvalue);
        if (mockupUrlchoose == "") {
            alert("Please choose mockup!");
        } else {

            if (designid == 0) {
                alert("Please add image url or get design!");
                return;
            }
            var front_url = listfront[0]?.[1];
            var back_url = listback[0]?.[1];
            console.log(front_url);
            console.log(back_url);
            if (numberside == 1) {
                back_url = "";
            }
            $.ajax({
                url: '{{route("designs.genPositions")}}',
                method: 'get',
                data: {
                    frontCoordinatesvalue: frontCoordinatesvalue,
                    backCoordinatesvalue: backCoordinatesvalue,
                    mockupUrl: mockupUrlchoose,
                    front_url: front_url,
                    back_url: back_url,
                },
                success: function (response) {
                    $("#body_genPosition").html(response);
                    $("#genPosition").modal('show');
                    // const designUpload = document.getElementById('designUpload');
                    const mockupImagePosition = document.getElementById('mockupImagePosition');
                    const designImage = document.getElementById('designImageFront');
                    const zoomInButton = document.getElementById('zoomIn');
                    const zoomOutButton = document.getElementById('zoomOut');
                    const infoBox = document.getElementById('infoBox');

                    // const designUploadback = document.getElementById('designUploadBack');
                    const designImageBack = document.getElementById('designImageBack');
                    const zoomInButtonBack = document.getElementById('zoomInBack');
                    const zoomOutButtonBack = document.getElementById('zoomOutBack');
                    const infoBoxBack = document.getElementById('infoBoxBack');


                    let isDragging = false;
                    let isResizing = false;
                    let startX = {{$xfront ?? 0}};
                    let startY = {{$yfront ?? 0}};
                    let initialLeft = {{$xfront ?? 50}};
                    let initialTop = {{$yfront ?? 440}};
                    let initialWidth = {{$widthfront ?? 300}};
                    let initialHeight = {{$heightfront ?? 350}};
                    let scale = 1;


                    let isDraggingBack = false;
                    let isResizingBack = false;
                    let startXBack = 0;
                    let startYBack = 0;
                    let initialLeftBack = {{$xback ?? 440}};
                    let initialTopBack = {{$yback ?? 50}};
                    let initialWidthBack = {{$widthback ?? 300}};
                    let initialHeightBack = {{$heightback ?? 350}};
                    let scaleBack = 1;


                    // Function to update the position and size info
                    function updateInfoBox() {
                        const left = parseInt(designImage.style.left ?? 50);
                        const top = parseInt(designImage.style.top ?? 440);
                        const width = designImage.offsetWidth * scale;
                        const height = designImage.offsetHeight * scale;
                        infoBox.textContent = `Position: ${Math.round(width)},${Math.round(height)},${left}, ${top}`;
                    }

                    // Update resize handles position based on design image position and size
                    function updateResizeHandles() {
                        const left = parseInt(designImage.style.left ?? 50);
                        const top = parseInt(designImage.style.top ?? 440);
                        const width = designImage.offsetWidth;
                        const height = designImage.offsetHeight;

                        document.getElementById('topLeft').style.left = `${left - 5}px`;
                        document.getElementById('topLeft').style.top = `${top - 5}px`;

                        document.getElementById('topRight').style.left = `${left + width - 5}px`;
                        document.getElementById('topRight').style.top = `${top - 5}px`;

                        document.getElementById('bottomLeft').style.left = `${left - 5}px`;
                        document.getElementById('bottomLeft').style.top = `${top + height - 5}px`;

                        document.getElementById('bottomRight').style.left = `${left + width - 5}px`;
                        document.getElementById('bottomRight').style.top = `${top + height - 5}px`;
                    }

                    // Step 4: Dragging the Design on Mockup
                    designImage.addEventListener('mousedown', (e) => {
                        if (!isResizing) {
                            isDragging = true;
                            startX = e.clientX;
                            startY = e.clientY;
                            initialLeft = parseInt(designImage.style.left || 50);
                            initialTop = parseInt(designImage.style.top || 440);
                            console.log(designImage.style.left);
                            console.log(designImage.style.top);
                            console.log(initialLeft);
                            console.log(initialTop);
                            e.preventDefault();
                        }
                    });

                    document.addEventListener('mousemove', (e) => {
                        if (isDragging) {
                            const deltaX = e.clientX - startX;
                            const deltaY = e.clientY - startY;
                            designImage.style.left = `${initialLeft + deltaX}px`;
                            designImage.style.top = `${initialTop + deltaY}px`;
                            updateResizeHandles();  // Cập nhật vị trí điểm điều khiển khi kéo
                            updateInfoBox();        // Cập nhật thông tin
                        }
                        if (isResizing) {
                            const deltaX = e.clientX - startX;
                            const deltaY = e.clientY - startY;

                            // Tính toán thay đổi kích thước và cập nhật
                            if (currentHandle === 'top-left') {
                                designImage.style.width = `${initialWidth - deltaX}px`;
                                designImage.style.height = `${initialHeight - deltaY}px`;
                                designImage.style.left = `${initialLeft + deltaX}px`;
                                designImage.style.top = `${initialTop + deltaY}px`;
                            } else if (currentHandle === 'top-right') {
                                designImage.style.width = `${initialWidth + deltaX}px`;
                                designImage.style.height = `${initialHeight - deltaY}px`;
                                designImage.style.top = `${initialTop + deltaY}px`;
                            } else if (currentHandle === 'bottom-left') {
                                designImage.style.width = `${initialWidth - deltaX}px`;
                                designImage.style.height = `${initialHeight + deltaY}px`;
                                designImage.style.left = `${initialLeft + deltaX}px`;
                            } else if (currentHandle === 'bottom-right') {
                                designImage.style.width = `${initialWidth + deltaX}px`;
                                designImage.style.height = `${initialHeight + deltaY}px`;
                            }
                            updateResizeHandles();
                            updateInfoBox();
                        }
                    });

                    document.addEventListener('mouseup', () => {
                        isDragging = false;
                        isResizing = false;
                    });



                    // Handle resize at corners
                    let currentHandle = null;
                    document.getElementById('topLeft').addEventListener('mousedown', (e) => {
                        currentHandle = 'top-left';
                        isResizing = true;
                        startX = e.clientX;
                        startY = e.clientY;
                        initialWidth = designImage.offsetWidth;
                        initialHeight = designImage.offsetHeight;
                        initialLeft = parseInt(designImage.style.left || 50);
                        initialTop = parseInt(designImage.style.top || 440);
                        e.preventDefault();
                    });

                    document.getElementById('topRight').addEventListener('mousedown', (e) => {
                        currentHandle = 'top-right';
                        isResizing = true;
                        startX = e.clientX;
                        startY = e.clientY;
                        initialWidth = designImage.offsetWidth;
                        initialHeight = designImage.offsetHeight;
                        initialLeft = parseInt(designImage.style.left || 50);
                        initialTop = parseInt(designImage.style.top || 440);
                        e.preventDefault();
                    });

                    document.getElementById('bottomLeft').addEventListener('mousedown', (e) => {
                        currentHandle = 'bottom-left';
                        isResizing = true;
                        startX = e.clientX;
                        startY = e.clientY;
                        initialWidth = designImage.offsetWidth;
                        initialHeight = designImage.offsetHeight;
                        initialLeft = parseInt(designImage.style.left || 50);
                        initialTop = parseInt(designImage.style.top || 440);
                        e.preventDefault();
                    });

                    document.getElementById('bottomRight').addEventListener('mousedown', (e) => {
                        currentHandle = 'bottom-right';
                        isResizing = true;
                        startX = e.clientX;
                        startY = e.clientY;
                        initialWidth = designImage.offsetWidth;
                        initialHeight = designImage.offsetHeight;
                        initialLeft = parseInt(designImage.style.left || 50);
                        initialTop = parseInt(designImage.style.top || 440);
                        e.preventDefault();
                    });



                    // Function to update the position and size info
                    function updateInfoBoxBack() {
                        const left = parseInt(designImageBack.style.left || 440);
                        const top = parseInt(designImageBack.style.top || 50);
                        const width = designImageBack.offsetWidth * scale;
                        const height = designImageBack.offsetHeight * scale;
                        infoBoxBack.textContent = `Position: ${Math.round(width)},${Math.round(height)},${left}, ${top}`;
                    }

                    // Update resize handles position based on design image position and size
                    function updateResizeHandleBacks() {
                        const left = parseInt(designImageBack.style.left || 440);
                        const top = parseInt(designImageBack.style.top || 50);
                        const width = designImageBack.offsetWidth;
                        const height = designImageBack.offsetHeight;

                        document.getElementById('topLeftBack').style.left = `${left - 5}px`;
                        document.getElementById('topLeftBack').style.top = `${top - 5}px`;

                        document.getElementById('topRightBack').style.left = `${left + width - 5}px`;
                        document.getElementById('topRightBack').style.top = `${top - 5}px`;

                        document.getElementById('bottomLeftBack').style.left = `${left - 5}px`;
                        document.getElementById('bottomLeftBack').style.top = `${top + height - 5}px`;

                        document.getElementById('bottomRightBack').style.left = `${left + width - 5}px`;
                        document.getElementById('bottomRightBack').style.top = `${top + height - 5}px`;
                    }

                    // Step 4: Dragging the Design on Mockup
                    designImageBack.addEventListener('mousedown', (e) => {
                        if (!isResizingBack) {
                            isDraggingBack = true;
                            startXBack = e.clientX;
                            startYBack = e.clientY;
                            initialLeftBack = parseInt(designImageBack.style.left || 440);
                            initialTopBack = parseInt(designImageBack.style.top || 50);
                            e.preventDefault();
                            updateCoordinatesForNewMockup();

                        }
                    });

                    // Handle dragging during mousemove
                    document.addEventListener('mousemove', (e) => {
                        if (isDraggingBack) {
                            const deltaX = e.clientX - startXBack;
                            const deltaY = e.clientY - startYBack;
                            designImageBack.style.left = `${initialLeftBack + deltaX}px`;
                            designImageBack.style.top = `${initialTopBack + deltaY}px`;
                            updateResizeHandleBacks();  // Update the resize handles position
                            updateInfoBoxBack();        // Update position and size info
                            updateCoordinatesForNewMockup();

                        }
                        if (isResizingBack) {
                            const deltaX = e.clientX - startXBack;
                            const deltaY = e.clientY - startYBack;

                            // Update resize behavior for each corner
                            if (currentHandleBack === 'top-left') {
                                designImageBack.style.width = `${initialWidthBack - deltaX}px`;
                                designImageBack.style.height = `${initialHeightBack - deltaY}px`;
                                designImageBack.style.left = `${initialLeftBack + deltaX}px`;
                                designImageBack.style.top = `${initialTopBack + deltaY}px`;
                            } else if (currentHandleBack === 'top-right') {
                                designImageBack.style.width = `${initialWidthBack + deltaX}px`;
                                designImageBack.style.height = `${initialHeightBack - deltaY}px`;
                                designImageBack.style.top = `${initialTopBack + deltaY}px`;
                            } else if (currentHandleBack === 'bottom-left') {
                                designImageBack.style.width = `${initialWidthBack - deltaX}px`;
                                designImageBack.style.height = `${initialHeightBack + deltaY}px`;
                                designImageBack.style.left = `${initialLeftBack + deltaX}px`;
                            } else if (currentHandleBack === 'bottom-right') {
                                designImageBack.style.width = `${initialWidthBack + deltaX}px`;
                                designImageBack.style.height = `${initialHeightBack + deltaY}px`;
                            }
                            updateResizeHandleBacks();
                            updateInfoBoxBack();
                            updateCoordinatesForNewMockup();

                        }
                    });

                    document.addEventListener('mouseup', () => {
                        isDraggingBack = false;
                        isResizingBack = false;
                        updateCoordinatesForNewMockup();

                    });


                    // Handle resize at corners
                    let currentHandleBack = null;
                    document.getElementById('topLeftBack').addEventListener('mousedown', (e) => {
                        currentHandleBack = 'top-left';
                        isResizingBack = true;
                        startXBack = e.clientX;
                        startYBack = e.clientY;
                        initialWidthBack = designImageBack.offsetWidth;
                        initialHeightBack = designImageBack.offsetHeight;
                        initialLeftBack = parseInt(designImageBack.style.left || 440);
                        initialTopBack = parseInt(designImageBack.style.top || 50);
                        e.preventDefault();
                        updateCoordinatesForNewMockup();

                    });

                    document.getElementById('topRightBack').addEventListener('mousedown', (e) => {
                        currentHandleBack = 'top-right';
                        isResizingBack = true;
                        startXBack = e.clientX;
                        startYBack = e.clientY;
                        initialWidthBack = designImageBack.offsetWidth;
                        initialHeightBack = designImageBack.offsetHeight;
                        initialLeftBack = parseInt(designImageBack.style.left || 440);
                        initialTopBack = parseInt(designImageBack.style.top || 50);
                        e.preventDefault();
                        updateCoordinatesForNewMockup();

                    });

                    document.getElementById('bottomLeftBack').addEventListener('mousedown', (e) => {
                        currentHandleBack = 'bottom-left';
                        isResizingBack = true;
                        startXBack = e.clientX;
                        startYBack = e.clientY;
                        initialWidthBack = designImageBack.offsetWidth;
                        initialHeightBack = designImageBack.offsetHeight;
                        initialLeftBack = parseInt(designImageBack.style.left || 440);
                        initialTopBack = parseInt(designImageBack.style.top || 50);
                        e.preventDefault();
                        updateCoordinatesForNewMockup();

                    });

                    document.getElementById('bottomRightBack').addEventListener('mousedown', (e) => {
                        currentHandleBack = 'bottom-right';
                        isResizingBack = true;
                        startXBack = e.clientX;
                        startYBack = e.clientY;
                        initialWidthBack = designImageBack.offsetWidth;
                        initialHeightBack = designImageBack.offsetHeight;
                        initialLeftBack = parseInt(designImageBack.style.left || 440);
                        initialTopBack = parseInt(designImageBack.style.top || 50);
                        e.preventDefault();
                        updateCoordinatesForNewMockup();

                    });

                    // Kích thước mockup cũ
                    let oldWidth = 1000; // Thay giá trị thực tế ở đây
                    let oldHeight = 1000; // Thay giá trị thực tế ở đây

                    // Hàm chuyển đổi tọa độ
                    function convertCoordinates(x_old, y_old, oldWidth, oldHeight) {
                        const newWidth = 1200;
                        const newHeight = 1200;

                        const x_new = (x_old / oldWidth) * newWidth;
                        const y_new = (y_old / oldHeight) * newHeight;

                        return { x: Math.round(x_new), y: Math.round(y_new) };
                    }

                    // Cập nhật tọa độ hiển thị khi thay đổi mockup
                    function updateCoordinatesForNewMockup() {
                        const frontLeft = parseInt(designImage.style.left || 0);
                        const frontTop = parseInt(designImage.style.top || 0);
                        const backLeft = parseInt(designImageBack.style.left || 0);
                        const backTop = parseInt(designImageBack.style.top || 0);
                        console.log(frontLeft);
                        console.log(frontTop);
                        console.log(backLeft);
                        console.log(backTop);
                        console.log(oldWidth);
                        console.log(oldHeight);
                        scaleFactorX = 1200 / oldWidth;
                        scaleFactorY = 1200 / oldHeight;

                        // Chuyển đổi tọa độ
                        const frontCoordinates = convertCoordinates(frontLeft, frontTop, oldWidth, oldHeight);
                        const backCoordinates = convertCoordinates(backLeft, backTop, oldWidth, oldHeight);
                        console.log(frontCoordinates);
                        console.log(backCoordinates);
                        console.log(scaleFactorX);
                        console.log(designImage.width);
                        console.log(designImage.height);
                        const frontWidth = designImage.width * scaleFactorX; // scaleFactorX là tỷ lệ co theo chiều ngang
                        const frontHeight = designImage.height * scaleFactorY; // scaleFactorY là tỷ lệ co theo chiều dọc
                        const backWidth = designImageBack.width * scaleFactorX;
                        const backHeight = designImageBack.height * scaleFactorY;

                        document.getElementById('frontCoordinates').value = `${frontWidth.toFixed(0)},${frontHeight.toFixed(0)},${frontCoordinates.x},${frontCoordinates.y}`;
                        document.getElementById('backCoordinates').value = `${backWidth.toFixed(0)},${backHeight.toFixed(0)},${backCoordinates.x},${backCoordinates.y}`;

                    }
                    $("#frontCoordinates").val(frontCoordinatesvalue);
                    $("#backCoordinates").val(backCoordinatesvalue);
                }
            });
        }
    }
    function setuphuman() {
        console.log("position");
        console.log(frontCoordinatesvaluehuman);
        console.log(backCoordinatesvaluehuman);
        if (mockupUrlchoosehuman == "") {
            alert("Please choose mockup!");
        } else {
            $("#generatehumanid").prop('disabled', false);
            if (designid == 0) {
                alert("Please add image url or get design!");
                return;
            }
            var front_url = listfront[0]?.[1];
            var back_url = listback[0]?.[1];
            console.log(front_url);
            console.log(back_url);
            if (numberside == 1) {
                back_url = "";
            }
            $.ajax({
                url: '{{route("designs.genPositionhumans")}}',
                method: 'get',
                data: {
                    frontCoordinatesvalue: frontCoordinatesvaluehuman,
                    backCoordinatesvalue: backCoordinatesvaluehuman,
                    mockupUrl: mockupUrlchoosehuman,
                    front_url: front_url,
                    back_url: back_url,
                },
                success: function (response) {
                    $("#body_genPositionHumans").html(response);
                    $("#genPositionHumans").modal('show');
                    // const designUpload = document.getElementById('designUpload');
                    const mockupImagePosition = document.getElementById('mockupImagePosition');
                    const designImage = document.getElementById('designImageFront');
                    const zoomInButton = document.getElementById('zoomIn');
                    const zoomOutButton = document.getElementById('zoomOut');
                    const infoBox = document.getElementById('infoBox');

                    // const designUploadback = document.getElementById('designUploadBack');
                    const designImageBack = document.getElementById('designImageBack');
                    const zoomInButtonBack = document.getElementById('zoomInBack');
                    const zoomOutButtonBack = document.getElementById('zoomOutBack');
                    const infoBoxBack = document.getElementById('infoBoxBack');


                    let isDragging = false;
                    let isResizing = false;
                    let startX = {{$xfront ?? 0}};
                    let startY = {{$yfront ?? 0}};
                    let initialLeft = {{$xfront ?? 50}};
                    let initialTop = {{$yfront ?? 440}};
                    let initialWidth = {{$widthfront ?? 300}};
                    let initialHeight = {{$heightfront ?? 350}};
                    let scale = 1;


                    let isDraggingBack = false;
                    let isResizingBack = false;
                    let startXBack = 0;
                    let startYBack = 0;
                    let initialLeftBack = {{$xback ?? 440}};
                    let initialTopBack = {{$yback ?? 50}};
                    let initialWidthBack = {{$widthback ?? 300}};
                    let initialHeightBack = {{$heightback ?? 350}};
                    let scaleBack = 1;


                    // Function to update the position and size info
                    function updateInfoBox() {
                        const left = parseInt(designImage.style.left ?? 50);
                        const top = parseInt(designImage.style.top ?? 440);
                        const width = designImage.offsetWidth * scale;
                        const height = designImage.offsetHeight * scale;
                        infoBox.textContent = `Position: ${Math.round(width)},${Math.round(height)},${left}, ${top}`;
                    }

                    // Update resize handles position based on design image position and size
                    function updateResizeHandles() {
                        const left = parseInt(designImage.style.left ?? 50);
                        const top = parseInt(designImage.style.top ?? 440);
                        const width = designImage.offsetWidth;
                        const height = designImage.offsetHeight;

                        document.getElementById('topLeft').style.left = `${left - 5}px`;
                        document.getElementById('topLeft').style.top = `${top - 5}px`;

                        document.getElementById('topRight').style.left = `${left + width - 5}px`;
                        document.getElementById('topRight').style.top = `${top - 5}px`;

                        document.getElementById('bottomLeft').style.left = `${left - 5}px`;
                        document.getElementById('bottomLeft').style.top = `${top + height - 5}px`;

                        document.getElementById('bottomRight').style.left = `${left + width - 5}px`;
                        document.getElementById('bottomRight').style.top = `${top + height - 5}px`;
                    }

                    // Step 4: Dragging the Design on Mockup
                    designImage.addEventListener('mousedown', (e) => {
                        if (!isResizing) {
                            isDragging = true;
                            startX = e.clientX;
                            startY = e.clientY;
                            initialLeft = parseInt(designImage.style.left || 50);
                            initialTop = parseInt(designImage.style.top || 440);
                            console.log(designImage.style.left);
                            console.log(designImage.style.top);
                            console.log(initialLeft);
                            console.log(initialTop);
                            e.preventDefault();
                        }
                    });

                    document.addEventListener('mousemove', (e) => {
                        if (isDragging) {
                            const deltaX = e.clientX - startX;
                            const deltaY = e.clientY - startY;
                            designImage.style.left = `${initialLeft + deltaX}px`;
                            designImage.style.top = `${initialTop + deltaY}px`;
                            updateResizeHandles();  // Cập nhật vị trí điểm điều khiển khi kéo
                            updateInfoBox();        // Cập nhật thông tin
                        }
                        if (isResizing) {
                            const deltaX = e.clientX - startX;
                            const deltaY = e.clientY - startY;

                            // Tính toán thay đổi kích thước và cập nhật
                            if (currentHandle === 'top-left') {
                                designImage.style.width = `${initialWidth - deltaX}px`;
                                designImage.style.height = `${initialHeight - deltaY}px`;
                                designImage.style.left = `${initialLeft + deltaX}px`;
                                designImage.style.top = `${initialTop + deltaY}px`;
                            } else if (currentHandle === 'top-right') {
                                designImage.style.width = `${initialWidth + deltaX}px`;
                                designImage.style.height = `${initialHeight - deltaY}px`;
                                designImage.style.top = `${initialTop + deltaY}px`;
                            } else if (currentHandle === 'bottom-left') {
                                designImage.style.width = `${initialWidth - deltaX}px`;
                                designImage.style.height = `${initialHeight + deltaY}px`;
                                designImage.style.left = `${initialLeft + deltaX}px`;
                            } else if (currentHandle === 'bottom-right') {
                                designImage.style.width = `${initialWidth + deltaX}px`;
                                designImage.style.height = `${initialHeight + deltaY}px`;
                            }
                            updateResizeHandles();
                            updateInfoBox();
                        }
                    });

                    document.addEventListener('mouseup', () => {
                        isDragging = false;
                        isResizing = false;
                    });



                    // Handle resize at corners
                    let currentHandle = null;
                    document.getElementById('topLeft').addEventListener('mousedown', (e) => {
                        currentHandle = 'top-left';
                        isResizing = true;
                        startX = e.clientX;
                        startY = e.clientY;
                        initialWidth = designImage.offsetWidth;
                        initialHeight = designImage.offsetHeight;
                        initialLeft = parseInt(designImage.style.left || 50);
                        initialTop = parseInt(designImage.style.top || 440);
                        e.preventDefault();
                    });

                    document.getElementById('topRight').addEventListener('mousedown', (e) => {
                        currentHandle = 'top-right';
                        isResizing = true;
                        startX = e.clientX;
                        startY = e.clientY;
                        initialWidth = designImage.offsetWidth;
                        initialHeight = designImage.offsetHeight;
                        initialLeft = parseInt(designImage.style.left || 50);
                        initialTop = parseInt(designImage.style.top || 440);
                        e.preventDefault();
                    });

                    document.getElementById('bottomLeft').addEventListener('mousedown', (e) => {
                        currentHandle = 'bottom-left';
                        isResizing = true;
                        startX = e.clientX;
                        startY = e.clientY;
                        initialWidth = designImage.offsetWidth;
                        initialHeight = designImage.offsetHeight;
                        initialLeft = parseInt(designImage.style.left || 50);
                        initialTop = parseInt(designImage.style.top || 440);
                        e.preventDefault();
                    });

                    document.getElementById('bottomRight').addEventListener('mousedown', (e) => {
                        currentHandle = 'bottom-right';
                        isResizing = true;
                        startX = e.clientX;
                        startY = e.clientY;
                        initialWidth = designImage.offsetWidth;
                        initialHeight = designImage.offsetHeight;
                        initialLeft = parseInt(designImage.style.left || 50);
                        initialTop = parseInt(designImage.style.top || 440);
                        e.preventDefault();
                    });



                    // Function to update the position and size info
                    function updateInfoBoxBack() {
                        const left = parseInt(designImageBack.style.left || 440);
                        const top = parseInt(designImageBack.style.top || 50);
                        const width = designImageBack.offsetWidth * scale;
                        const height = designImageBack.offsetHeight * scale;
                        infoBoxBack.textContent = `Position: ${Math.round(width)},${Math.round(height)},${left}, ${top}`;
                    }

                    // Update resize handles position based on design image position and size
                    function updateResizeHandleBacks() {
                        const left = parseInt(designImageBack.style.left || 440);
                        const top = parseInt(designImageBack.style.top || 50);
                        const width = designImageBack.offsetWidth;
                        const height = designImageBack.offsetHeight;

                        document.getElementById('topLeftBack').style.left = `${left - 5}px`;
                        document.getElementById('topLeftBack').style.top = `${top - 5}px`;

                        document.getElementById('topRightBack').style.left = `${left + width - 5}px`;
                        document.getElementById('topRightBack').style.top = `${top - 5}px`;

                        document.getElementById('bottomLeftBack').style.left = `${left - 5}px`;
                        document.getElementById('bottomLeftBack').style.top = `${top + height - 5}px`;

                        document.getElementById('bottomRightBack').style.left = `${left + width - 5}px`;
                        document.getElementById('bottomRightBack').style.top = `${top + height - 5}px`;
                    }

                    // Step 4: Dragging the Design on Mockup
                    designImageBack.addEventListener('mousedown', (e) => {
                        if (!isResizingBack) {
                            isDraggingBack = true;
                            startXBack = e.clientX;
                            startYBack = e.clientY;
                            initialLeftBack = parseInt(designImageBack.style.left || 440);
                            initialTopBack = parseInt(designImageBack.style.top || 50);
                            e.preventDefault();
                            updateCoordinatesForNewMockup();

                        }
                    });

                    // Handle dragging during mousemove
                    document.addEventListener('mousemove', (e) => {
                        if (isDraggingBack) {
                            const deltaX = e.clientX - startXBack;
                            const deltaY = e.clientY - startYBack;
                            designImageBack.style.left = `${initialLeftBack + deltaX}px`;
                            designImageBack.style.top = `${initialTopBack + deltaY}px`;
                            updateResizeHandleBacks();  // Update the resize handles position
                            updateInfoBoxBack();        // Update position and size info
                            updateCoordinatesForNewMockup();

                        }
                        if (isResizingBack) {
                            const deltaX = e.clientX - startXBack;
                            const deltaY = e.clientY - startYBack;

                            // Update resize behavior for each corner
                            if (currentHandleBack === 'top-left') {
                                designImageBack.style.width = `${initialWidthBack - deltaX}px`;
                                designImageBack.style.height = `${initialHeightBack - deltaY}px`;
                                designImageBack.style.left = `${initialLeftBack + deltaX}px`;
                                designImageBack.style.top = `${initialTopBack + deltaY}px`;
                            } else if (currentHandleBack === 'top-right') {
                                designImageBack.style.width = `${initialWidthBack + deltaX}px`;
                                designImageBack.style.height = `${initialHeightBack - deltaY}px`;
                                designImageBack.style.top = `${initialTopBack + deltaY}px`;
                            } else if (currentHandleBack === 'bottom-left') {
                                designImageBack.style.width = `${initialWidthBack - deltaX}px`;
                                designImageBack.style.height = `${initialHeightBack + deltaY}px`;
                                designImageBack.style.left = `${initialLeftBack + deltaX}px`;
                            } else if (currentHandleBack === 'bottom-right') {
                                designImageBack.style.width = `${initialWidthBack + deltaX}px`;
                                designImageBack.style.height = `${initialHeightBack + deltaY}px`;
                            }
                            updateResizeHandleBacks();
                            updateInfoBoxBack();
                            updateCoordinatesForNewMockup();

                        }
                    });

                    document.addEventListener('mouseup', () => {
                        isDraggingBack = false;
                        isResizingBack = false;
                        updateCoordinatesForNewMockup();

                    });


                    // Handle resize at corners
                    let currentHandleBack = null;
                    document.getElementById('topLeftBack').addEventListener('mousedown', (e) => {
                        currentHandleBack = 'top-left';
                        isResizingBack = true;
                        startXBack = e.clientX;
                        startYBack = e.clientY;
                        initialWidthBack = designImageBack.offsetWidth;
                        initialHeightBack = designImageBack.offsetHeight;
                        initialLeftBack = parseInt(designImageBack.style.left || 440);
                        initialTopBack = parseInt(designImageBack.style.top || 50);
                        e.preventDefault();
                        updateCoordinatesForNewMockup();

                    });

                    document.getElementById('topRightBack').addEventListener('mousedown', (e) => {
                        currentHandleBack = 'top-right';
                        isResizingBack = true;
                        startXBack = e.clientX;
                        startYBack = e.clientY;
                        initialWidthBack = designImageBack.offsetWidth;
                        initialHeightBack = designImageBack.offsetHeight;
                        initialLeftBack = parseInt(designImageBack.style.left || 440);
                        initialTopBack = parseInt(designImageBack.style.top || 50);
                        e.preventDefault();
                        updateCoordinatesForNewMockup();

                    });

                    document.getElementById('bottomLeftBack').addEventListener('mousedown', (e) => {
                        currentHandleBack = 'bottom-left';
                        isResizingBack = true;
                        startXBack = e.clientX;
                        startYBack = e.clientY;
                        initialWidthBack = designImageBack.offsetWidth;
                        initialHeightBack = designImageBack.offsetHeight;
                        initialLeftBack = parseInt(designImageBack.style.left || 440);
                        initialTopBack = parseInt(designImageBack.style.top || 50);
                        e.preventDefault();
                        updateCoordinatesForNewMockup();

                    });

                    document.getElementById('bottomRightBack').addEventListener('mousedown', (e) => {
                        currentHandleBack = 'bottom-right';
                        isResizingBack = true;
                        startXBack = e.clientX;
                        startYBack = e.clientY;
                        initialWidthBack = designImageBack.offsetWidth;
                        initialHeightBack = designImageBack.offsetHeight;
                        initialLeftBack = parseInt(designImageBack.style.left || 440);
                        initialTopBack = parseInt(designImageBack.style.top || 50);
                        e.preventDefault();
                        updateCoordinatesForNewMockup();

                    });

                    // Kích thước mockup cũ
                    let oldWidth = 1000; // Thay giá trị thực tế ở đây
                    let oldHeight = 1000; // Thay giá trị thực tế ở đây

                    // Hàm chuyển đổi tọa độ
                    function convertCoordinates(x_old, y_old, oldWidth, oldHeight) {
                        const newWidth = 1200;
                        const newHeight = 1200;

                        const x_new = (x_old / oldWidth) * newWidth;
                        const y_new = (y_old / oldHeight) * newHeight;

                        return { x: Math.round(x_new), y: Math.round(y_new) };
                    }

                    // Cập nhật tọa độ hiển thị khi thay đổi mockup
                    function updateCoordinatesForNewMockup() {
                        const frontLeft = parseInt(designImage.style.left || 0);
                        const frontTop = parseInt(designImage.style.top || 0);
                        const backLeft = parseInt(designImageBack.style.left || 0);
                        const backTop = parseInt(designImageBack.style.top || 0);
                        console.log(frontLeft);
                        console.log(frontTop);
                        console.log(backLeft);
                        console.log(backTop);
                        console.log(oldWidth);
                        console.log(oldHeight);
                        scaleFactorX = 1200 / oldWidth;
                        scaleFactorY = 1200 / oldHeight;

                        // Chuyển đổi tọa độ
                        const frontCoordinates = convertCoordinates(frontLeft, frontTop, oldWidth, oldHeight);
                        const backCoordinates = convertCoordinates(backLeft, backTop, oldWidth, oldHeight);
                        console.log(frontCoordinates);
                        console.log(backCoordinates);
                        console.log(scaleFactorX);
                        console.log(designImage.width);
                        console.log(designImage.height);
                        const frontWidth = designImage.width * scaleFactorX; // scaleFactorX là tỷ lệ co theo chiều ngang
                        const frontHeight = designImage.height * scaleFactorY; // scaleFactorY là tỷ lệ co theo chiều dọc
                        const backWidth = designImageBack.width * scaleFactorX;
                        const backHeight = designImageBack.height * scaleFactorY;

                        document.getElementById('frontCoordinateHumans').value = `${frontWidth.toFixed(0)},${frontHeight.toFixed(0)},${frontCoordinates.x},${frontCoordinates.y}`;
                        document.getElementById('backCoordinateHumans').value = `${backWidth.toFixed(0)},${backHeight.toFixed(0)},${backCoordinates.x},${backCoordinates.y}`;

                    }
                    $("#frontCoordinates").val(frontCoordinatesvalue);
                    $("#backCoordinates").val(backCoordinatesvalue);
                }
            });
        }
    }
    function donegenposition() {
        console.log("frontCoordinates");
        frontCoordinatesvalue = $("#frontCoordinates").val();
        console.log(frontCoordinatesvalue);
        console.log("backCoordinates");
        backCoordinatesvalue = $("#backCoordinates").val();
        console.log(backCoordinatesvalue);
        $("#body_genPosition").html(" ");
        $("#genPosition").modal('hide');
        $("#preview").prop('disabled', false);
        $("#generate").prop('disabled', false);
    }
    function donegenpositionHuman() {
        console.log("frontCoordinateHumans");
        frontCoordinatesvaluehuman = $("#frontCoordinateHumans").val();
        console.log(frontCoordinatesvaluehuman);
        console.log("backCoordinateHumans");
        backCoordinatesvaluehuman = $("#backCoordinateHumans").val();
        console.log(backCoordinatesvaluehuman);
        $("#choosehuman").css('overflow', 'auto');
        $("#body_genPositionHumans").html(" ");
        $("#genPositionHumans").modal('hide');
        // $("#preview").prop('disabled',false);
        // $("#generate").prop('disabled',false);
    }
    function preview() {
        alert("chưa làm");
        // mockupUrl = `https://global24watermark.site/mockup/${mockup.dataType}/${color.slice(1)}/two/${mockup.dataKey}.jpg?url_1=${linkfront}?w=120&h=150&ver=${randomver}&url_2=${linkback}?w=120&h=150&ver=${randomver}`;
    }
    function sortImages(list, lightsArray, darksArray) {
        list.forEach(item => {
            if (item[0] === 'lights') {
                lightsArray.push(item[1]);
            } else if (item[0] === 'darks') {
                darksArray.push(item[1]);
            }
        });
    }
    function generate() {
        lights = [];
        darks = [];
        console.log("-----")
        console.log(listfront)
        console.log(listback)
        console.log("-----")

        sortImages(listfront, lights, darks);
        sortImages(listback, lights, darks);

        // Log results
        console.log("Lights:", lights);
        console.log("Darks:", darks);
        // console.log(listmockupchoose);
        console.log(listcolorchoose);
        if (listcolorchoose.length == 0) {
            alert("chọn color bạn ê");
            return;
        }
        $("#choosefirstmockup").prop("disabled", false);
        // if (listmockupchoose.length == 0) {
        //     alert("chọn mockup bạn ê");
        //     return;
        // }
        mockupchoose = [];
        console.log("numberside");
        console.log(numberside);

        const listGenContainer = document.getElementById('list-gen-mockup');
        listGenContainer.innerHTML = "";
        const randomver = Math.floor(100000 + Math.random() * 900000);
        console.log(frontCoordinatesvalue);
        console.log(backCoordinatesvalue);
        position = frontCoordinatesvalue + "," + backCoordinatesvalue;
        console.log(position);
        listmockupchoose.forEach((mockup) => {
            listcolorchoose.forEach((color) => {
                console.log(color);
                // Generate the URL
                var mockupUrl = "";
                if ($("#numberside").val() == 1) {

                    if (lights.length == 0 || darks.length == 0) {
                        if (lights.length == 0) {
                            console.log(darks);
                            mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/one/${mockup.dataKey}.jpg?url_1=${darks[0]}?w=120&h=150&ver=${randomver}&position={${position}}`;

                        }
                        if (darks.length == 0) {
                            console.log(lights);
                            mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/one/${mockup.dataKey}.jpg?url_1=${lights[0]}?w=120&h=150&ver=${randomver}&position={${position}}`;

                        }
                    } else {
                        if (lights.length > 0) {
                            console.log(lights);
                            if (color[1] == 1) {
                                mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/one/${mockup.dataKey}.jpg?url_1=${lights[0]}?w=120&h=150&ver=${randomver}&position={${position}}`;
                            }
                        }
                        if (darks.length > 0) {
                            console.log(darks);
                            if (color[1] == 0) {
                                mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/one/${mockup.dataKey}.jpg?url_1=${darks[0]}?w=120&h=150&ver=${randomver}&position={${position}}`;
                            }
                        }
                    }
                    // Add the URL to the list
                    listurl.push(mockupUrl);

                    // Create a new figure element
                    const figureElement = document.createElement('figure');
                    const imageElement = document.createElement('img');
                    const aElement = document.createElement('div');
                    imageElement.src = mockupUrl;
                    imageElement.dataset.color = color; // Corrected line
                    imageElement.className = 'img-thumbnail active-choose-mockup mt-2 mb-2';
                    imageElement.alt = 'Generated Mockup';
                    imageElement.onclick = chooseImage
                    mockupchoose.push({
                        "color": color,
                        "src": mockupUrl,
                    });
                    aElement.href = mockupUrl
                    aElement.target = '_blank';
                    // Append the image to the figure
                    aElement.appendChild(imageElement);
                    figureElement.appendChild(aElement);

                    // Create a new column div to hold the figure
                    const colElement = document.createElement('div');
                    colElement.className = 'col-2';
                    colElement.appendChild(figureElement);

                    // Append the column div to the listgen container
                    listGenContainer.appendChild(colElement);

                } else if ($("#numberside").val() == 2) {
                    console.log("-----")
                    console.log(listfront)
                    console.log(listback)
                    console.log("-----")
                    if (lights.length == 0 || darks.length == 0) {
                        if (lights.length == 0) {
                            console.log(darks);
                            mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/two/${mockup.dataKey}.jpg?url_1=${darks[0]}?w=120&h=150&ver=${randomver}&url_2=${darks[1]}?w=120&h=150&ver=${randomver}&position={${position}}`;

                        }
                        if (darks.length == 0) {
                            console.log(lights);
                            mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/two/${mockup.dataKey}.jpg?url_1=${lights[0]}?w=120&h=150&ver=${randomver}&url_2=${lights[1]}?w=120&h=150&ver=${randomver}&position={${position}}`;

                        }
                    } else {
                        if (lights.length > 0) {
                            console.log(lights);
                            if (color[1] == 1) {
                                mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/two/${mockup.dataKey}.jpg?url_1=${lights[0]}?w=120&h=150&ver=${randomver}&url_2=${lights[1]}?w=120&h=150&ver=${randomver}&position={${position}}`;
                            }
                        }
                        if (darks.length > 0) {
                            console.log(darks);
                            if (color[1] == 0) {
                                mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/two/${mockup.dataKey}.jpg?url_1=${darks[0]}?w=120&h=150&ver=${randomver}&url_2=${darks[1]}?w=120&h=150&ver=${randomver}&position={${position}}`;
                            }
                        }
                    }
                    // Add the URL to the list
                    listurl.push(mockupUrl);

                    // Create a new figure element
                    const figureElement = document.createElement('figure');
                    const imageElement = document.createElement('img');
                    const aElement = document.createElement('div');
                    imageElement.src = mockupUrl;
                    imageElement.dataset.color = color; // Corrected line
                    imageElement.className = 'img-thumbnail active-choose-mockup mt-2 mb-2';
                    imageElement.alt = 'Generated Mockup';
                    imageElement.onclick = chooseImage
                    mockupchoose.push({
                        "color": color,
                        "src": mockupUrl,
                    });
                    aElement.href = mockupUrl
                    aElement.target = '_blank';
                    // Append the image to the figure
                    aElement.appendChild(imageElement);
                    figureElement.appendChild(aElement);

                    // Create a new column div to hold the figure
                    const colElement = document.createElement('div');
                    colElement.className = 'col-2';
                    colElement.appendChild(figureElement);

                    // Append the column div to the listgen container
                    listGenContainer.appendChild(colElement);

                }
            });

        });
        console.log(listurl);
        $("#next").css('display', 'inline');
    }
    function generatehuman() {
        lights = [];
        darks = [];
        console.log("-----")
        console.log(listfront)
        console.log(listback)
        console.log("-----")

        sortImages(listfront, lights, darks);
        sortImages(listback, lights, darks);

        // Log results
        console.log("Lights:", lights);
        console.log("Darks:", darks);

        console.log(listmockuphumanchoose);
        console.log(listcolorchoose);
        if (listcolorchoose.length == 0) {
            alert("chọn color bạn ê");
            return;
        }
        if (listcolorchoose.length == 0) {
            alert("chọn color bạn ê");
            return;
        }
        if (listmockuphumanchoose.length == 0) {
            alert("chọn mockup bạn ê");
            return;
        }
        $("#nextcreateproduct").prop('disabled', false);
        mockupchooseHuman = [];
        // console.log("numberside");
        // console.log(numberside);
        const listGenContainer = document.getElementById('list-gen-mockup-human');
        listGenContainer.innerHTML = "";
        const randomver = Math.floor(100000 + Math.random() * 900000);
        console.log(frontCoordinatesvaluehuman);
        console.log(backCoordinatesvaluehuman);
        position = frontCoordinatesvaluehuman + "," + backCoordinatesvaluehuman;

        listmockuphumanchoose.forEach((mockup) => {
            listcolorchoose.forEach((color) => {
                // console.log(color);
                // Generate the URL
                var mockupUrl = "";
                if ($("#numberside").val() == 1) {

                    if (lights.length == 0 || darks.length == 0) {
                        if (lights.length == 0) {
                            console.log(darks);
                            mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/one/${mockup.dataKey}.jpg?url_1=${darks[0]}?w=120&h=150&ver=${randomver}&position={${position}}`;

                        }
                        if (darks.length == 0) {
                            console.log(lights);
                            mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/one/${mockup.dataKey}.jpg?url_1=${lights[0]}?w=120&h=150&ver=${randomver}&position={${position}}`;

                        }
                    } else {
                        if (lights.length > 0) {
                            console.log(lights);
                            if (color[1] == 1) {
                                mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/one/${mockup.dataKey}.jpg?url_1=${lights[0]}?w=120&h=150&ver=${randomver}&position={${position}}`;
                            }
                        }
                        if (darks.length > 0) {
                            console.log(darks);
                            if (color[1] == 0) {
                                mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/one/${mockup.dataKey}.jpg?url_1=${darks[0]}?w=120&h=150&ver=${randomver}&position={${position}}`;
                            }
                        }
                    }
                    listurl.push(mockupUrl);

                    // Create a new figure element
                    const figureElement = document.createElement('figure');
                    const imageElement = document.createElement('img');
                    const aElement = document.createElement('div');
                    imageElement.src = mockupUrl;
                    imageElement.dataset.color = color; // Corrected line
                    imageElement.dataset.name = color; // Corrected line
                    imageElement.className = 'img-thumbnail mt-2 mb-2';
                    imageElement.alt = 'Generated Mockup';
                    imageElement.onclick = chooseImageHuman
                    // mockupchooseHuman.push({
                    //     "color": color,
                    //     "src": mockupUrl,
                    // });
                    aElement.href = mockupUrl
                    aElement.target = '_blank';
                    // Append the image to the figure
                    aElement.appendChild(imageElement);
                    figureElement.appendChild(aElement);

                    // Create a new column div to hold the figure
                    const colElement = document.createElement('div');
                    colElement.className = 'col-2';
                    colElement.appendChild(figureElement);

                    // Append the column div to the listgen container
                    listGenContainer.appendChild(colElement);


                } else if ($("#numberside").val() == 2) {
                    console.log("-----")
                    console.log(listfront)
                    console.log(listback)
                    console.log("-----")
                    if (lights.length == 0 || darks.length == 0) {
                        if (lights.length == 0) {
                            console.log(darks);
                            mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/two/${mockup.dataKey}.jpg?url_1=${darks[0]}?w=120&h=150&ver=${randomver}&url_2=${darks[1]}?w=120&h=150&ver=${randomver}&position={${position}}`;

                        }
                        if (darks.length == 0) {
                            console.log(lights);
                            mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/two/${mockup.dataKey}.jpg?url_1=${lights[0]}?w=120&h=150&ver=${randomver}&url_2=${lights[1]}?w=120&h=150&ver=${randomver}&position={${position}}`;

                        }
                    } else {
                        if (lights.length > 0) {
                            console.log(lights);
                            if (color[1] == 1) {
                                mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/two/${mockup.dataKey}.jpg?url_1=${lights[0]}?w=120&h=150&ver=${randomver}&url_2=${lights[1]}?w=120&h=150&ver=${randomver}&position={${position}}`;
                            }
                        }
                        if (darks.length > 0) {
                            console.log(darks);
                            if (color[1] == 0) {
                                mockupUrl = `https://global24watermark.site/generateposition/${mockup.dataType}/${color[0].slice(1)}/two/${mockup.dataKey}.jpg?url_1=${darks[0]}?w=120&h=150&ver=${randomver}&url_2=${darks[1]}?w=120&h=150&ver=${randomver}&position={${position}}`;
                            }
                        }
                    }
                    // Add the URL to the list
                    listurl.push(mockupUrl);

                    // Create a new figure element
                    const figureElement = document.createElement('figure');
                    const imageElement = document.createElement('img');
                    const aElement = document.createElement('div');
                    imageElement.src = mockupUrl;
                    imageElement.dataset.color = color; // Corrected line
                    imageElement.dataset.name = color; // Corrected line
                    imageElement.className = 'img-thumbnail mt-2 mb-2';
                    imageElement.alt = 'Generated Mockup';
                    imageElement.onclick = chooseImageHuman
                    // mockupchooseHuman.push({
                    //     "color": color,
                    //     "src": mockupUrl,
                    // });
                    aElement.href = mockupUrl
                    aElement.target = '_blank';
                    // Append the image to the figure
                    aElement.appendChild(imageElement);
                    figureElement.appendChild(aElement);

                    // Create a new column div to hold the figure
                    const colElement = document.createElement('div');
                    colElement.className = 'col-2';
                    colElement.appendChild(figureElement);

                    // Append the column div to the listgen container
                    listGenContainer.appendChild(colElement);


                }

            });

        });
        // console.log(listurl);

    }
    function chooseImageHuman(event) {
        const src = event.currentTarget.src;
        const color = event.currentTarget.getAttribute('data-color');
        const index = mockupchooseHuman.findIndex(item => item.src === src);

        if (event.currentTarget.classList.contains("active-choose-mockup")) {
            event.currentTarget.classList.remove("active-choose-mockup");

            // Remove the src from mockupchoose if it exists
            if (index > -1) {
                mockupchooseHuman.splice(index, 1);
            }


        } else {

            event.currentTarget.classList.add("active-choose-mockup");
            // Add the src to mockupchoose if not already in the array
            if (index === -1) {
                mockupchooseHuman.push({
                    "color": color,
                    "src": src,
                });
            }

        }
        console.log(mockupchooseHuman);
    }
    function chooseImage(event) {
        const src = event.currentTarget.src;
        const color = event.currentTarget.getAttribute('data-color');
        const index = mockupchoose.findIndex(item => item.src === src);
        if (event.currentTarget.classList.contains(classchoosemockup)) {
            event.currentTarget.classList.remove(classchoosemockup);
            if (classchoosemockup == "active-choose-mockup-first") {
                firstmockup = "";
            } else {
                // Remove the src from mockupchoose if it exists
                if (index > -1) {
                    mockupchooseHuman.splice(index, 1);
                }
            }

        } else {
            if (classchoosemockup == "active-choose-mockup-first") {
                if (firstmockup == "") {
                    event.currentTarget.classList.add(classchoosemockup);
                    firstmockup = src;
                }
            } else {
                event.currentTarget.classList.add(classchoosemockup);
                // Add the src to mockupchoose if not already in the array
                if (index === -1) {
                    mockupchooseHuman.push({
                        "color": color,
                        "src": src,
                    });
                }
            }
        }
        console.log(mockupchoose);

    }
    let selectedFiles = [];

    function next() {
        console.log("firstmockup");
        console.log(firstmockup);
        if (firstmockup == "") {
            alert("please choose mockup first");
        } else {
            $.ajax({
                url: '{{route("designs.ajaxMockupHuman")}}',
                method: 'get',
                data: {
                    name: type,
                    numberside: numberside
                },
                success: function (response) {
                    $("#body_choosehuman").html(response);
                    $("#choosehuman").modal('show');
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

                                        let deleteBtn = document.createElement('span');
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
                        console.log("selectedFiles");
                        console.log(selectedFiles);
                    });
                }
            })

        }
    }
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
    function post() {
        $("#nextcreateproduct").prop('disabled', true);
        console.log("listmockupchoose");
        console.log(mockupchoose);
        console.log($("#title").val());
        console.log(mockupchooseHuman);
        console.log(template);
        console.log(selectedFiles);
        if (mockupchooseHuman.length == 0) {
            alert("please choose mockup human!");
            $("#nextcreateproduct").prop('disabled', false);
            return;
        }
        if (template == "") {
            alert("please choose template!");
            $("#nextcreateproduct").prop('disabled', false);
            return;
        }
        let formData = new FormData();
        formData.append('firstmockup', firstmockup);
        formData.append('mockupchoose', JSON.stringify(mockupchoose));
        formData.append('title', $("#title").val());
        formData.append('mockupchooseHuman', JSON.stringify(mockupchooseHuman));
        formData.append('template', $("#template").val());
        formData.append('design_id', $("#getdesign").val());
        if(selectedFiles.length<0){
            alert("please add image color chart!");
            $("#nextcreateproduct").prop('disabled', false);
            return;
        }
        const image = selectedFiles;
        image.forEach((file) => {
            formData.append('images[]', file)
        })
        console.log(image);
        $.ajax({
            url: '{{ route("designs.createProductDesign") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: formData,
            processData: false,  // Prevent jQuery from automatically processing the data
            contentType: false,  // Set contentType to false for FormData
            success: function (response) {
                console.log("Success:", response);
                location.href = "../../products";
                // Handle success response here
            },
            error: function (error) {
                console.error("Error:", error);
                // Handle error response here
            }
        });
    }
    function choosefirstmockup(target) {
        console.log(target.checked)
        if (target.checked) {
            classchoosemockup = "active-choose-mockup-first";
        } else {
            classchoosemockup = "active-choose-mockup";
        }
    }
</script>

@endsection

@endsection