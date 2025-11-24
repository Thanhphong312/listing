@foreach($ajaxTrackingNotFounds as $ajaxTrackingNotFound)
    <div class="row">
        <div class="col-1 text-center">
            <input type="checkbox" id="checkbox_{{$ajaxTrackingNotFound->id}}" style="margin-top: 4px;" data-id="{{$ajaxTrackingNotFound->id}}">

        </div>
        <div class="col-1 text-center">
            <a href="./orders?order_id={{$ajaxTrackingNotFound->order->id}}">
                #{{$ajaxTrackingNotFound->order->id}}
            </a>
            <select name="resole" class="btn btn-sm  btn-info custom-select-status" id="resole_{{$ajaxTrackingNotFound->id}}" onchange="updateResoleStatus('{{$ajaxTrackingNotFound->id}}')">
                <option value="">Unresolved</option>
                <option value="1">Resole</option>
            </select>
        </div>
        <div class="col-2 text-center">
            {{getUsernameById($ajaxTrackingNotFound->order->seller_id)}}
        </div>
        <div class="col-3 text-center">
            <a href="https://t.17track.net/en#nums={{$ajaxTrackingNotFound->tracking_id}}" target="_blank">
                {{$ajaxTrackingNotFound->tracking_id}}
            </a>
        </div>
        <div class="col-2 text-center">
            {{ $ajaxTrackingNotFound->total_day . " day" }}
        </div>
        <div class="col-2 text-center">
            <textarea id="note_tracking_{{$ajaxTrackingNotFound->id}}" type="text" rows="2" cols="40" class="form-control text-left m-1" name="note" placeholder="Note" onchange="statusChange('{{$ajaxTrackingNotFound->id}}')">{{($ajaxTrackingNotFound->metas->first())?$ajaxTrackingNotFound->metas->first()->meta_value:""}}</textarea>
        </div>
        <div class="col-1 text-center">
            {{ \Carbon\Carbon::createFromTimeStamp(strtotime($ajaxTrackingNotFound->updated_at))->diffForHumans(null, true) }}
        </div>
    </div>
@endforeach
 