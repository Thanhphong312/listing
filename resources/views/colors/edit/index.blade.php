<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                    <div class="col-5 m-2 border-light">
                        <label for="name_edit_add">Name </label>
                        <input class="form-control" type="text" name="name_edit" id="name_edit" value="{{$color->name}}">
                    </div>  
                    <div class="col-5 m-2 border-light">
                        <label for="hex_edit">Hex </label>
                        <input class="form-control" type="text" name="hex_edit" id="hex_edit" value="{{$color->hex}}">
                    </div> 
                    <div class="col-5 m-2 border-light">
                        <label for="status_add">Type</label>
                        <select class="form-control" name="type_edit" id="type_edit" >
                        <option value="1" {{($color->type==1?'selected':'')}}>Light</option>
                        <option value="0" {{($color->type==0?'selected':'')}}>Dark</option>
                        </select>
                    </div>                  
                    <div class="col-5 m-2 border-light">
                        <label for="status_add">Status</label>
                        <select class="form-control" name="status_edit" id="status_edit" >
                            <option value="1" {{($color->status==1?'selected':'')}}>Active</option>
                            <option value="0" {{($color->status==0?'selected':'')}}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12 m-2 border-light">
                        <input class="btn btn-primary"  type="button" name="submit" id="submit" onclick="edit('{{$color->id}}')" value="Submit"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   
</script>