<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Title </label>
                        <input class="form-control" type="text" name="title_add" id="title_add" value="">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add">Ideas </label>
                        <select class="form-control select2" id="idea_add" name="idea_add">
                            <option value="">Idea</option>
                            @foreach(getIdeas() as $idea)
                                <option value="{{$idea->id}}">
                                    {{$idea->title}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 m-2 border-light row">
                        <div class="col-4">
                            <label for="size_chart_add">Size chart</label>
                            <input class="form-control" type="file" name="size_chart_add" id="size_chart_add" value="" onchange="handleFileChange(event)">
                        </div>
                        <div class="col-8">
                            <label for="name_edit_add">Review</label>
                            <figure class="position-relative">
                                <a href="" target="_blank">
                                    <img src="" id="showchat" class="img-thumbnail" alt="Product Image" style="width:200px; height: 200px;">
                                </a>
                            </figure>
                        </div>
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="name_edit_add">List color </label>
                        <div class="group">
                            <div class="title_container">
                                <ul class="flex">
                                    <li>
                                        <input type="radio" id="allColors" name="rColor" value="All">
                                        <label for="allColors"> Tất cả</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="darkColor" name="rColor" value="All" >
                                        <label for="darkColor"> Tối (Black)</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="lightColor" name="rColor" value="All" checked>
                                        <label for="lightColor"> Sáng (Light/White)</label>
                                    </li>
                                </ul>
                            </div>
                            <div class="color_container" id="checkColor">
                                <ul class="flex">
                                    @foreach ( $colors as $color)
                                        <li>
                                            <input type="checkbox" id="{{$color->name}}" name="{{$color->name}}" value="{{$color->id}}">
                                            <label for="{{$color->name}}" > {{$color->name}}</label >
                                            <span style="border:1px solid black; width:10px;height:10px;background-color:{{$color->hex}};" class="btn btn-square-md m-1"></span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 m-2 border-light">
                        <input class="btn btn-primary" type="button" name="submit" id="submit" onclick="add()"
                            value="Submit" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const allColorsRadio = document.getElementById('allColors');
        const darkColorRadio = document.getElementById('darkColor');
        const lightColorRadio = document.getElementById('lightColor');
        const checkColorDiv = document.getElementById('checkColor');
        
        allColorsRadio.addEventListener('change', handleColorRadioChange);
        darkColorRadio.addEventListener('change', handleColorRadioChange);
        lightColorRadio.addEventListener('change', handleColorRadioChange);
        
        function handleColorRadioChange() {
            const checkboxes = checkColorDiv.querySelectorAll('input[type="checkbox"]');
            
            // Tạo các mảng JavaScript từ các giá trị PHP
            const darkColors = {!! json_encode($darks) !!};
            const lightColors = {!! json_encode($lights) !!};

            checkboxes.forEach(checkbox => {
                switch (this.id) {
                    case 'allColors':
                        checkbox.checked = true;
                        break;
                    case 'darkColor':
                        checkbox.checked = darkColors.includes(checkbox.id);
                        break;
                    case 'lightColor':
                        checkbox.checked = lightColors.includes(checkbox.id);
                        break;
                    default:
                        break;
                }
            });
        }
    })();

</script>