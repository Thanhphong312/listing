<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Name </label>
                        <input class="form-control" type="text" name="name_add" id="name_add">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Keyword </label>
                        <input class="form-control" type="text" name="keyword_add" id="keyword_add">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Watermark </label>
                        <input class="form-control" type="text" name="watermark_add" id="watermark_add">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="name_flashdeal_add">Name flashdeal</label>
                        <input class="form-control" type="text" name="name_flashdeal_add" id="name_flashdeal_add">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Sup store id </label>
                        <input class="form-control" type="text" name="sup_store_id_add" id="sup_store_id_add">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Access token</label>
                        <input class="form-control" type="text" name="access_token_add" id="access_token_add">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Refresh token</label>
                        <input class="form-control" type="text" name="refresh_token_add" id="refresh_token_add">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add"> Partner app </label>
                        <select class="form-control select2" id="partner_add" name="partner_add">
                            <option value="">partner...</option>
                            @foreach ($partners as $partner)
                                <option value="{{ $partner->id }}">
                                    {{ $partner->app_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($role == 'Admin' || $role == 'Seller')
                        <div class="col-12 m-2 border-light">
                            <label for="idea_add">Seller </label>
                            <select class="form-control select2" id="seller_add" name="seller_add">
                                <option value="">Seller...</option>
                                @if ($role == 'Seller')
                                    <option value="{{ $user->id }}" selected>{{ $user->username }}</option>
                                @else
                                    @foreach (listSeller() as $seller)
                                        <option value="{{ $seller['id'] }}">
                                            {{ $seller['username'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif
                    @if ($role != 'Staff')
                        <div class="col-12 m-2 border-light">
                            <label for="idea_add">Staff </label>
                            <select class="form-control select2" id="staff_add" name="staff_add">
                                <option value="">Staff...</option>
                                @foreach (listStaff() as $staff)
                                    <option value="{{ $staff['id'] }}">
                                        {{ $staff['username'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Order code</label>
                        <input class="form-control" type="text" name="order_code_add" id="order_code_add">
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

<script></script>
