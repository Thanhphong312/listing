<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div class="row mx-auto ">
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Title</label>
                        <input class="form-control" type="text" name="title" id="title" value="">
                    </div>
                    <div class="col-12 row m-2 border-light">
                        <div class="col-6" style="padding: 0 12px 0 0;">
                            <label for="size_chart_add">Niche</label>
                            <select class="form-select" id="single-select-field" name="niche" data-placeholder="Choose one thing">
                                @foreach (niches() as $niche)
                                <option value="{{$niche}}">{{$niche}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6" style="padding: 0 12px 0 12px;">
                            <label for="size_chart_add">Mix</label>
                            <select class="form-select" id="single-select-field" name="mix" data-placeholder="Choose one thing">
                            @foreach (mixs() as $mix)
                                <option value="{{$mix}}">{{$mix}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 row m-2 border-light">
                        <div class="form-check form-check-success">
                            <input class="form-check-input" onchange="handleRadioChange(event)" type="checkbox" name="bl_and_wt" id="bl_and_wt">
                                <label class="form-check-label" for="bl_and_wt">
                                    Black and White
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="one_ds" class="col-12 row m-2 border-light">
                        <div class="col-12 row m-2">
                            <div class="col-12" style="padding: 0 12px 0 0;">
                                <label for="file_front">Front</label>
                                <input class="form-control" type="text" name="file_front" id="file_front" value="">
                            </div>
                        </div>
                        <div class="col-12 row m-2">
                            <div class="col-12" style="padding: 0 12px 0 0;">
                                <label for="file_back">Back</label>
                                <input class="form-control" type="text" name="file_black" id="file_back" value="">
                            </div>
                        </div>
                        <div class="col-12 row m-2">
                            <div class="col-12" style="padding: 0 12px 0 0;">
                                <label for="file_sleeve_left">Sleeve Left</label>
                                <input class="form-control" type="text" name="file_sleeve_left" id="file_sleeve_left" value="">
                            </div>
                        </div>
                        <div class="col-12 row m-2">
                            <div class="col-12" style="padding: 0 12px 0 0;">
                                <label for="file_sleeve_right">Sleeve Right</label>
                                <input class="form-control" type="text" name="file_sleeve_right" id="file_sleeve_right" value="">
                            </div>
                        </div>
                    </div>
                    <div id="two_ds" class="col-12 row m-2 border-light hide">
                        <div class="col-6 row">
                            <h6>Design Black</h6>
                            <div class="col-12 row m-2">
                                <div class="col-12" style="padding: 0 12px 0 0;">
                                    <label for="file_front_bl">Front</label>
                                    <input class="form-control" type="text" name="file_front_bl" id="file_front_bl">
                                </div>
                            </div>
                            <div class="col-12 row m-2">
                                <div class="col-12" style="padding: 0 12px 0 0;">
                                    <label for="file_back_bl">Back</label>
                                    <input class="form-control" type="text" name="file_back_bl" id="file_back_bl" value="">
                                </div>
                            </div>
                            <div class="col-12 row m-2">
                                <div class="col-12" style="padding: 0 12px 0 0;">
                                    <label for="file_sleeve_left_bl">Sleeve Left</label>
                                    <input class="form-control" type="text" name="file_sleeve_left_bl" id="file_sleeve_left_bl" value="">
                                </div>
                            </div>
                            <div class="col-12 row m-2">
                                <div class="col-12" style="padding: 0 12px 0 0;">
                                    <label for="file_sleeve_right_bl">Sleeve Right</label>
                                    <input class="form-control" type="text" name="file_sleeve_right_bl" id="file_sleeve_right_bl" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-6 row">
                            <h6>Design White</h6>
                            <div class="col-12 row m-2">
                                <div class="col-12" style="padding: 0 12px 0 0;">
                                    <label for="file_front_wt">Front</label>
                                    <input class="form-control" type="text" name="file_front_wt" id="file_front_wt" value="">
                                </div>
                            </div>
                            <div class="col-12 row m-2">
                                <div class="col-12" style="padding: 0 12px 0 0;">
                                    <label for="file_back_wt">Back</label>
                                    <input class="form-control" type="text" name="file_back_wt" id="file_back_wt" value="">
                                </div>
                            </div>
                            <div class="col-12 row m-2">
                                <div class="col-12" style="padding: 0 12px 0 0;">
                                    <label for="file_sleeve_left_wt">Sleeve Left</label>
                                    <input class="form-control" type="text" name="file_sleeve_left_wt" id="file_sleeve_left_wt" value="">
                                </div>
                            </div>
                            <div class="col-12 row m-2">
                                <div class="col-12" style="padding: 0 12px 0 0;">
                                    <label for="file_sleeve_right_wt">Sleeve Right</label>
                                    <input class="form-control" type="text" name="file_sleeve_right_wt" id="file_sleeve_right_wt" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 row m-2 border-light">
                        <label for="title_add">Tag</label>
                        <input class="form-control" type="text" name="tag" id="tag" value="">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <input class="btn btn-primary" type="button" name="submit" id="submit" onclick="addurl()" value="Submit" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style >
    .hide {
        display: none;
    }
</style>
<script>

</script>