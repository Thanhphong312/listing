<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Name </label>
                        <input class="form-control" type="text" name="name_add" id="name_add" value="">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add">Type </label>
                        <select class="form-control select2" id="type_add" name="type_add">
                            <option value="">type...</option>
                            <option value="1">Size chart<option>
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
                        <input class="btn btn-primary" type="button" name="submit" id="submit" onclick="add()"
                            value="Submit" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

</script>