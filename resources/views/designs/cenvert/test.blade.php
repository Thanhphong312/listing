<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mockup Design Tool</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS để căn chỉnh hình ảnh và khung vùng */
        .mockup-container {
            position: relative;
            width: 1000px;  /* Kích thước mockup */
            height: 1000px;
            border: 0px solid #ccc;
            overflow: hidden;
            border: 2px solid rgba(255, 0, 0, 0.5); /* Khung cho ảnh */
        }
        .mockup-image {
            width: 1000px;  /* Kích thước ảnh mockup sẽ luôn chiếm 100% */
            height: 1000px;
        }
        .design-image {
            position: absolute;
            width: {{$widthfront?$widthfront:300}}px;  
            height: {{$heightfront?$heightfront:350}}px;
            top: {{$yfront?$yfront:400}}px;
            left: {{$xfront?$xfront:50}}px;
            cursor: move;
            transform-origin: top left;
            border: 2px solid rgba(255, 0, 0, 0.5); /* Khung cho ảnh */
        }
        .design-image-back {
            position: absolute;
            width: {{$widthback?$widthback:300}}px;  
            height: {{$heightback?$heightback:350}}px;
            top: {{$yback?$yback:50}}px;
            left: {{$xback?$xback:440}}px;
            cursor: move;
            transform-origin: top left;
            border: 2px solid rgba(255, 0, 0, 0.5); /* Khung cho ảnh */
        }
        .resize-handle {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #ff0000;
            cursor: pointer;
        }
        .top-left {
            top: -5px;
            left: -5px;
            cursor: nwse-resize;
        }
        .top-right {
            top: -5px;
            right: -5px;
            cursor: nesw-resize;
        }
        .bottom-left {
            bottom: -5px;
            left: -5px;
            cursor: nesw-resize;
        }
        .bottom-right {
            bottom: -5px;
            right: -5px;
            cursor: nwse-resize;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="mb-3">
        <span class="row">
            <input  id="frontCoordinates" type="text" value="" class="form-control col-2 m-2" >
            <input  id="backCoordinates" type="text" value="" class="form-control col-2 m-2">
            <button type="button" class="btn btn-primary col-2 m-2" id="zoomIn" style="{{$fontdesign?'':'display:none'}}">Zoom In</button>
            <button type="button" class="btn btn-secondary col-2 m-2" id="zoomOut" style="{{$fontdesign?'':'display:none'}}">Zoom Out</button>
            <button type="button" class="btn btn-primary col-2 m-2" id="zoomInBack" style="{{$backdesign?'':'display:none'}}">Zoom In Back</button>
            <button type="button" class="btn btn-secondary col-2 m-2" id="zoomOutBack" style="{{$backdesign?'':'display:none'}}">Zoom Out Back</button>
            <button type="button" class="btn btn-success col-1 m-2" onclick="donegenposition()">Done</button>
        </span>
    </div>
    <!-- Mockup Container -->
    <div class="mockup-container">
        <img id="mockupImagePosition" class="mockup-image" src="{{$mockupUrl}}" alt="Mockup">
        <img id="designImageFront" class="design-image" src="{{$fontdesign}}" alt="Design Front" style="
            width: {{$widthfront?$widthfront:300}}px;  
            height: {{$heightfront?$heightfront:350}}px;
            top: {{$yfront?$yfront:400}}px;
            left: {{$xfront?$xfront:50}}px;
            {{$fontdesign?'':'display:none'}}">
        <img id="designImageBack" class="design-image-back" src="{{$backdesign}}" alt="Design Back" style="
            width: {{$widthback?$widthback:300}}px;  
            height: {{$heightback?$heightback:350}}px;
            top: {{$yback?$yback:50}}px;
            left: {{$xback?$xback:440}}px;
            {{$backdesign?'':'display:none'}}">
        <!-- Các điểm điều khiển để thay đổi kích thước -->
        <div id="topLeft" class="resize-handle top-left"></div>
        <div id="topRight" class="resize-handle top-right"></div>
        <div id="bottomLeft" class="resize-handle bottom-left"></div>
        <div id="bottomRight" class="resize-handle bottom-right"></div>

        <div id="topLeftBack" class="resize-handle top-left"></div>
        <div id="topRightBack" class="resize-handle top-right"></div>
        <div id="bottomLeftBack" class="resize-handle bottom-left"></div>
        <div id="bottomRightBack" class="resize-handle bottom-right"></div>
    </div>
    <div class="">
        <!-- Display position and size of design image -->
        <span class="info-box" id="infoBox">Position: (100, 100), Size: 500x600</span>
        <span class="info-box" id="infoBoxBack">Position: (100, 100), Size: 500x600</span>
        
    </div>
    
    
</div>

<script>
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
    let startX = {{$xfront??0}};
    let startY = {{$yfront??0}};
    let initialLeft = {{$xfront??50}};
    let initialTop = {{$yfront??440}};
    let initialWidth = {{$widthfront??300}};
    let initialHeight = {{$heightfront??350}};
    let scale = 1;


    let isDraggingBack = false;
    let isResizingBack = false;
    let startXBack = 0;
    let startYBack = 0;
    let initialLeftBack = {{$xback??440}};
    let initialTopBack = {{$yback??50}};
    let initialWidthBack = {{$widthback??300}};
    let initialHeightBack = {{$heightback??350}};
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
</script>
</body>
</html>
