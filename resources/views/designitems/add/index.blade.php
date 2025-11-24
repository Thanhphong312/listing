<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                <div class="col-12 m-2 border-light">
                        <label for="title_add">Title</label>
                        <input class="form-control" type="text" name="title" id="title" value="">
                    </div>      
                    <!-- <div class="col-12 m-2 border-light">
                        <label for="number_side_add">Number side</label>
                        <select class="form-control select2" name="number_side_add" id="number_side_add">
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div> -->
                    <div class="col-12 m-2 border-light">
                        <label for="">Category</label>
                        @forEach($categories as $category)
                        <div class="m-2">
                            <input type="checkbox" id="chooseCategory[]" name="{{$category->name}}" value="{{$category->id}}">
                            <label for="{{$category->name}}">{{$category->name}}</label>
                        </div>
                        
                        @endforeach
                    </div>
                    <div class="col-12 row m-2">
                        <div class="col-4">
                            <label for="size_chart_add">Front</label>
                            <input class="form-control" type="file" name="size_chart_add" id="size_chart_add" value="" onchange="handleFileChange(event,'front')">
                        </div>
                        <div class="col-8">
                            <label for="name_edit_add">Review</label>
                            <figure class="position-relative">
                                <a href="" target="_blank" id="link_show_front">
                                    <img src="" id="show_front" class="img-thumbnail" alt="Product Image" style="width:200px; height: 200px;">
                                </a>
                            </figure>
                        </div>
                    </div>
                    <div class="col-12 row m-2">
                        <div class="col-4">
                            <label for="size_chart_add">Back</label>
                            <input class="form-control" type="file" name="size_chart_add" id="size_chart_add" value="" onchange="handleFileChange(event,'back')">
                        </div>
                        <div class="col-8">
                            <label for="name_edit_add">Review</label>
                            <figure class="position-relative">
                                <a href="" target="_blank" id="link_show_back">
                                    <img src="" id="show_back" class="img-thumbnail" alt="Product Image" style="width:200px; height: 200px;">
                                </a>
                            </figure>
                        </div>
                    </div>
                    <div class="col-12 row m-2">
                        <div class="col-4">
                            <label for="size_chart_add">Sleeve Left</label>
                            <input class="form-control" type="file" name="size_chart_add" id="size_chart_add" value="" onchange="handleFileChange(event,'sleeve_left')">
                        </div>
                        <div class="col-8">
                            <label for="name_edit_add">Review</label>
                            <figure class="position-relative">
                                <a href="" target="_blank" id="link_show_sleeve_left">
                                    <img src="" id="show_sleeve_left" class="img-thumbnail" alt="Product Image" style="width:200px; height: 200px;">
                                </a>
                            </figure>
                        </div>
                    </div>
                    <div class="col-12 row m-2">
                        <div class="col-4">
                            <label for="size_chart_add">Sleeve Right</label>
                            <input class="form-control" type="file" name="size_chart_add" id="size_chart_add" value="" onchange="handleFileChange(event,'sleeve_right')">
                        </div>
                        <div class="col-8">
                            <label for="name_edit_add">Review</label>
                            <figure class="position-relative">
                                <a href="" target="_blank" id="link_show_sleeve_right">
                                    <img src="" id="show_sleeve_right" class="img-thumbnail" alt="Product Image" style="width:200px; height: 200px;">
                                </a>
                            </figure>
                        </div>
                    </div>
                    <div class="col-12 m-2 border-light">
                        <input class="btn btn-primary" type="button" name="submit" id="submit" onclick="add()" value="Submit" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

</script>