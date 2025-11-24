<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                    <div class="col-5 m-2 border-light">
                        <label for="name_edit_add">Name </label>
                        <input class="form-control" type="text" name="name_edit" id="name_edit" value="{{$category->name}}">
                    </div>                    
                    <div class="col-5 m-2 border-light">
                        <label for="status_add">Status</label>
                        <select class="form-control" name="status_edit" id="status_edit" >
                            <option value="1" {{($category->status==1?'selected':'')}}>Active</option>
                            <option value="0" {{($category->status==0?'selected':'')}}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12 m-2 border-light">
                        <input class="btn btn-primary"  type="button" name="submit" id="submit" onclick="edit('{{$category->id}}')" value="Submit"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   
</script>