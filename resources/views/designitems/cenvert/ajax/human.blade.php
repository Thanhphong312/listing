<div class="row">
    <div class="col-12 mt-2">
        <div class="card-header row col-12" style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
            <select class="form-control select2" id="template" name="template">
                <option value="">template...</option>
                @foreach($templates as $template)
                    <option value="{{ $template->id }}" data-json="{{ $template->data }}">
                        {{ $template->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-12" style="display: flex;justify-content: end;">
        <div class="btn btn-light btn-sm m-2" onclick="setuphuman()">setup</div>
        <button type="button" class="btn btn-primary btn-sm m-2" id="generatehumanid" onclick="generatehuman()" disabled>generate human</button>
        <button type="button" class="btn btn-success btn-sm m-2" id="nextcreateproduct" onclick="post()" disabled >post ></button>
    </div>
    @foreach ($json as $key => $file)
        @if(in_array($key, $listhumanid))
            @php 
                $arr = explode("/",$file);
            @endphp 
            <figure class="col-3 mt-2 position-relative">
                <a target="_blank">
                    <img src="https://global24watermark.site/teespring/generate/{{$file}}"
                        onclick="chooseDesignHuman(this)" data-type="{{$arr[0]}}" data-key="{{$arr[1]}}" class="img-thumbnail"
                        alt="Product Image" style="height:200px">
                </a>
            </figure>
        @endif
    @endforeach
    <div class="col-12 bg-light m-2 rounded row" style="border: 2px solid; min-height:200px;"
        id="list-gen-mockup-human">

    </div>
    <div class="col-12 bg-light m-2 rounded row" style="border: 2px solid; min-height:200px;"
        id="list-gen-mockup-human">
        <div class="col-12 mt-2">
            <div class="card-header row col-12" style="border-radius:5px; border: 0; box-shadow: 0px 0px 8px #8080807a;">
                <!-- Form for uploading multiple designs -->
                <div class="form-group col-6">
                    <label for="designs">Upload Designs</label>
                    <input type="file" class="form-control" name="designs[]" id="designs" multiple>
                </div>
        
                <!-- Preview selected files and images -->
                <div class="m-3 col-12" id="file-preview">
                    <p>No files selected yet.</p>
                </div>
            </div>
        </div>
    </div>
</div>