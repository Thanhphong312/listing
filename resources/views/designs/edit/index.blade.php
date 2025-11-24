<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Title</label>
                        <input class="form-control" type="text" name="title" id="title" value="{{$design->title??''}}">
                    </div>     
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Tag</label>
                        <input class="form-control" type="text" name="tag" id="tag" value="{{$design->tag??''}}">
                    </div>     
                    <div class="col-12 m-2 border-light">
                        <input class="btn btn-primary" type="button" name="submit" id="submit" onclick="edit('{{$design->id}}')" value="Submit" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

</script>