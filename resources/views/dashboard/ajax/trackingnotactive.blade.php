@foreach($listTrackingInfoReceices as $listTrackingInfoReceice)
    <div class="row">
        <div class="col-1 text-center">
            <input type="checkbox" id="checkbox_{{$listTrackingInfoReceice->id}}" style="margin-top: 4px;" data-id="{{$listTrackingInfoReceice->id}}">

        </div>
        <div class="col-1 text-center">
            <a href="./orders?order_id={{$listTrackingInfoReceice->order->id}}">
                #{{$listTrackingInfoReceice->order->id}}
            </a>
            <select name="resole" class="btn btn-sm  btn-info custom-select-status" id="resole_{{$listTrackingInfoReceice->id}}" onchange="updateResoleStatus('{{$listTrackingInfoReceice->id}}')">
                <option value="">Unresolved</option>
                <option value="1">Resole</option>
            </select>
        </div>
        <div class="col-2 text-center">
            {{getUsernameById($listTrackingInfoReceice->order->seller_id)}}
        </div>
        <div class="col-3 text-center">
            <a href="https://t.17track.net/en#nums={{$listTrackingInfoReceice->tracking_id}}" target="_blank">
                {{$listTrackingInfoReceice->tracking_id}}
            </a>
        </div>
        <div class="col-2 text-center">
            {{ $listTrackingInfoReceice->total_day . " day" }}
        </div>
        <div class="col-2 text-center">
            <textarea id="note_tracking_{{$listTrackingInfoReceice->id}}" type="text" rows="2" cols="40" class="form-control text-left m-1" name="note" placeholder="Note" onchange="statusChange('{{$listTrackingInfoReceice->id}}')">{{($listTrackingInfoReceice->metas->first())?$listTrackingInfoReceice->metas->first()->meta_value:""}}</textarea>
        </div>
        <div class="col-1 text-center">
            {{ \Carbon\Carbon::createFromTimeStamp(strtotime($listTrackingInfoReceice->updated_at))->diffForHumans(null, true) }}
        </div>
    </div>
@endforeach
 