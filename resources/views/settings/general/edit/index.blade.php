<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                    <div class="col-12 m-2 border-light">
                        <label for="key_edit">Key</label>
                        <input class="form-control" type="text" name="key_edit" id="key_edit"
                            value="{{ $setting->key }}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="value_edit">Value</label>
                        <input class="form-control" type="text" name="value_edit" id="value_edit"
                            value="{{ $setting->value }}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="fteeck_token_edit">Fteeck Token</label>
                        <input class="form-control" type="text" name="fteeck_token_edit" id="fteeck_token_edit"
                            value="{{ $setting->fteeck_token }}">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <input class="btn btn-primary" type="button" name="submit" id="submit"
                            onclick="edit('{{ $setting->id }}')" value="Submit" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script></script>
