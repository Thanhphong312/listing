<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="pt-3 container">
                <div id="timezonenowfaddflashdeal">

                </div>
                <div class="row mx-auto">
                    <div class="col-12 m-2 border-light">
                        <label for="title_add">Name</label>
                        <input class="form-control" type="text" name="name_add" id="name_add" value="{{$name}}">
                    </div>
                    <div class="col-4 m-2">
                        <input class="form-control datetimepicker" name="datefrom" id="datefrom" autocomplete="off"
                            value="{{ Request::get('datefrom') }}" placeholder="Date from">
                    </div>
                    <div class="col-4 m-2">
                        <input class="form-control datetimepicker" name="dateto" id="dateto" autocomplete="off"
                            value="{{ Request::get('dateto') }}" placeholder="Date to">
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add"> Level </label>
                        <select class="form-control select2" id="level_add" name="level_add">
                            <option value="PRODUCT">Product</option>
                            <option value="VARIATION" selected>Variant</option>
                        </select>
                    </div>
                    <div class="col-12 m-2 border-light">
                        <label for="idea_add"> Activity </label>
                        <select class="form-control select2" id="activity_add" name="activity_add">
                            <option value="DIRECT_DISCOUNT">DIRECT_DISCOUNT</option>
                            <option value="FIXED_PRICE">FIXED_PRICE</option>
                            <option value="FLASHSALE" selected>FLASHSALE</option>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.40/moment-timezone-with-data.min.js"></script>

<script>
    $(document).ready(function () {
        // Initialize datetimepicker with custom timezone format for TikTok (America/New_York)
        $('#datefrom').datetimepicker({
            format: 'Y-m-d H:i', // Format theo kiểu YYYY-MM-DD HH:mm
            step: 30,
            onShow: function (ct) {
                var now = moment.tz('America/New_York'); // Lấy thời gian theo múi giờ America/New_York
                this.setOptions({
                    value: now.format('YYYY-MM-DD HH:mm') // Hiển thị thời gian đúng theo múi giờ TikTok
                });
            }
        });

        $('#dateto').datetimepicker({
            format: 'Y-m-d H:i',
            step: 30,
            onShow: function (ct) {
                var now = moment.tz('America/New_York'); // Lấy thời gian theo múi giờ America/New_York
                this.setOptions({
                    value: now.format('YYYY-MM-DD HH:mm') // Hiển thị thời gian đúng theo múi giờ TikTok
                });
            }
        });
    });

</script>