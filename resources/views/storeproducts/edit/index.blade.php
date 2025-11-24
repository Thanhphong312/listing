<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Name </label>
                        <input class="form-control" type="text" name="name_edit" id="name_edit" value="{{$store->name}}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Access token</label>
                        <input class="form-control" type="text" name="access_token_edit" id="access_token_edit" value="{{$access_token}}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add"> Partner app </label>
                        <select class="form-control select2" id="partner_edit" name="partner_edit">
                            <option value="">partner...</option>
                            @foreach($partners as $partner)
                                <option value="{{$partner->id}}" {{$store->partner_id==$partner->id?'selected':''}}>
                                    {{$partner->app_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add">Seller </label>
                        <select class="form-control select2" id="seller_edit" name="seller_edit">
                            <option value="">Seller...</option>
                            @foreach(listSeller() as $seller)
                                <option value="{{$seller['id']}}" {{$store->user_id==$seller['id']?'selected':''}}>
                                    {{$seller['username']}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-12 m-2 border-light">
                        <input class="btn btn-primary" type="button" name="submit" id="submit" onclick="edit('{{$store->id}}')"
                            value="Submit" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

</script>