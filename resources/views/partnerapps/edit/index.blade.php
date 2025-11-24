<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Name </label>
                        <input class="form-control" type="text" name="name_edit" id="name_edit"
                            value="{{$partnerApp->app_name}}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">app key</label>
                        <input class="form-control" type="text" name="key_edit" id="key_edit"
                            value="{{$partnerApp->app_key}}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">app secret</label>
                        <input class="form-control" type="text" name="secret_edit" id="secret_edit"
                            value="{{$partnerApp->app_secret}}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">app proxy</label>
                        <input class="form-control" type="text" name="proxy_edit" id="proxy_edit"
                            value="{{$partnerApp->proxy}}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">auth link</label>
                        <input class="form-control" type="text" name="auth_link_edit" id="auth_link_edit" value="{{$partnerApp->auth_link}}">
                    </div>
                    @if($role!='Staff')
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add">Seller </label>
                        <select class="form-control select2" id="seller_edit" name="seller_edit">
                            <option value="">Seller...</option>
                            @if($role == 'Seller')
                                <option value="{{$user->id}}" selected>{{$user->username}}</option>
                            @else
                                @foreach(listSeller() as $seller)
                                    <option value="{{$seller['id']}}" {{$partnerApp->seller_id == $seller['id'] ? "selected" : ""}}>
                                        {{$seller['username']}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    @endif
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add">Staff </label>
                        <select class="form-control select2" id="staff_edit" name="staff_edit">
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
                        <input class="btn btn-primary" type="button" name="submit" id="submit"
                            onclick="edit('{{$partnerApp->id}}')" value="Submit" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

</script>