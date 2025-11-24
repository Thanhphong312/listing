<form>
    <div class="form-group">
        <div class="col-md-12">
            <div class="row" id="list_support_{{$request->order_id}}">

                <div class="col-12 text-center" style="font-weight: bold; font-size:15px;"><span class="">What can we help you to day ?</span></div>
                <div class="col-2" row>
                    <div class="col-12 p-2 text-center">
                        Subject
                    </div>
                    <div class="col-12 p-2 text-center">
                        Message
                    </div>
                </div>
                <div class="col-10" row>
                    <div class="col-12 p-2">
                        <input type="text" class="form-control" name="subject_{{$request->order_id}}" id="subject_{{$request->order_id}}" required>
                    </div>
                    <div class="col-12 p-2">
                        <textarea class="form-control" rows="8" name="message_{{$request->order_id}}" id="message_{{$request->order_id}}"></textarea>
                    </div>
                    <div class="col-12 p-2">
                        <input type="file" class="form-control-file" name="file_{{$request->order_id}}" id="file_{{$request->order_id}}">
                    </div>
                    <div class="col-12 p-2">
                        <button class="btn btn-primary" id="btnAddSupport" onclick="addSupport('{{$request->order_id}}')" type="button">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>
