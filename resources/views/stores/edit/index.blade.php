<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Name </label>
                        <input class="form-control" type="text" name="name_edit" id="name_edit"
                            value="{{ $store->name }}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Keyword </label>
                        <input class="form-control" type="text" name="keyword_edit" id="keyword_edit"
                            value="{{ $store->keyword }}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Watermark </label>
                        <input class="form-control" type="text" name="watermark_edit" id="watermark_edit"
                            value="{{ $store->watermark }}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="name_flashdeal_edit">Name flashdeal</label>
                        <input class="form-control" type="text" name="name_flashdeal_edit" id="name_flashdeal_edit"
                            value="{{ $store->name_flashdeal }}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Sup store id </label>
                        <input class="form-control" type="text" name="sup_store_id_edit" id="sup_store_id_edit"
                            value="{{ $store->sup_store_id }}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Access token</label>
                        <input class="form-control" type="text" name="access_token_edit" id="access_token_edit"
                            value="{{ $access_token }}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Refresh token</label>
                        <input class="form-control" type="text" name="refresh_token_edit" id="refresh_token_edit"
                            value="{{ $refresh_token }}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add"> Status </label>
                        <select class="form-control select2" id="status_edit" name="status_edit">
                            <option value="0" {{ $store->status==0?'selected':'' }}>Inactive</option>
                            <option value="1" {{ $store->status==1?'selected':'' }}>Active</option>
                        </select>
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add"> Partner app </label>
                        <select class="form-control select2" id="partner_edit" name="partner_edit">
                            <option value="">partner...</option>
                            @foreach ($partners as $partner)
                                <option value="{{ $partner->id }}"
                                    {{ $store->partner_id == $partner->id ? 'selected' : '' }}>
                                    {{ $partner->app_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add">Seller </label>
                        <select class="form-control select2" id="seller_edit" name="seller_edit">
                            <option value="">Seller...</option>
                            @if ($role == 'Seller' || $role == 'Staff')
                                <option value="{{ $user->id }}" selected>{{ $user->username }}</option>
                            @else
                                @foreach (listSeller() as $seller)
                                    <option value="{{ $seller['id'] }}"
                                        {{ $store->user_id == $seller['id'] ? 'selected' : '' }}>
                                        {{ $seller['username'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add">Staff </label>
                        <select class="form-control select2" id="staff_edit" name="staff_edit">
                            <option value="">Staff...</option>
                            @if ($role == 'Staff')
                                <option value="{{ $user->id }}" selected>{{ $user->username }}</option>
                            @else
                                @foreach (listStaff() as $staff)
                                    <option value="{{ $staff['id'] }}"
                                        {{ $store->staff_id == $staff['id'] ? 'selected' : '' }}>
                                        {{ $staff['username'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">shop code </label>
                        <input class="form-control" type="text" name="order_code_edit" id="order_code_edit"
                            value="{{ $store->shop_code }}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <input class="btn btn-primary" type="button" name="submit" id="submit"
                            onclick="edit('{{ $store->id }}')" value="Submit" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script></script>
