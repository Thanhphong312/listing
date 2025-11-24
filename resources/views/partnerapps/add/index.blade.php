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
                        <label for="title_add">app key</label>
                        <input class="form-control" type="text" name="key_add" id="key_add" value="">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">app secret</label>
                        <input class="form-control" type="text" name="secret_add" id="secret_add" value="">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">app proxy</label>
                        <input class="form-control" type="text" name="proxy_add" id="proxy_add" value="">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">auth link</label>
                        <input class="form-control" type="text" name="auth_link_add" id="auth_link_add" value="">
                    </div>
                    @if($role!='Staff')
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add">Seller </label>
                        <select class="form-control select2" id="seller_add" name="seller_add">
                            @if($role == 'Seller')
                                <option value="{{$user->id}}" selected>{{$user->username}}</option>
                            @else
                                <option value="">Seller...</option>

                                @foreach(listSeller() as $seller)
                                    <option value="{{$seller['id']}}">
                                        {{$seller['username']}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    @endif
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add">Staff </label>
                        <select class="form-control select2" id="staff_add" name="staff_add">
                            @if($role == 'Staff')
                                <option value="{{$user->id}}" selected>{{$user->username}}</option>
                            @else
                                <option value="">Staff...</option>
                                @foreach(listStaff() as $staff)
                                
                                    <option value="{{$staff['id']}}">
                                        {{$staff['username']}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
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