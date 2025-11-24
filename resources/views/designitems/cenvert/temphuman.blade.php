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
            <input  id="frontCoordinateHumans" type="text" value="" class="form-control col-2 m-2" >
            <input  id="backCoordinateHumans" type="text" value="" class="form-control col-2 m-2">
            <button type="button" class="btn btn-primary col-2 m-2" id="zoomIn" style="{{$fontdesign?'':'display:none'}}">Zoom In</button>
            <button type="button" class="btn btn-secondary col-2 m-2" id="zoomOut" style="{{$fontdesign?'':'display:none'}}">Zoom Out</button>
            <button type="button" class="btn btn-primary col-2 m-2" id="zoomInBack" style="{{$backdesign?'':'display:none'}}">Zoom In Back</button>
            <button type="button" class="btn btn-secondary col-2 m-2" id="zoomOutBack" style="{{$backdesign?'':'display:none'}}">Zoom Out Back</button>
            <button type="button" class="btn btn-success col-1 m-2" onclick="donegenpositionHuman()">Done</button>
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
</body>
</html>
