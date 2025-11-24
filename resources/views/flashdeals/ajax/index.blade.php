<td>{{$flashdeal->id}}</td>
<td>{{$flashdeal->store_id}}</td>
<td>{{$flashdeal->activity_id}}</td>
<td>{{$flashdeal->promotion_name}}</td>
<td>
    <span>
        {{ $totalsuccess }}/{{ $totalfld }}
    </span>
</td>

<td>{{$flashdeal->activity_type}}</td>
<td>{{$flashdeal->product_level}}</td>
<td>
    @if($flashdeal->status_fld != "ONGOING")
        <div class="btn btn-sm {{getbtnfld($flashdeal->status_fld)}}" disabled>
            {{$flashdeal->status_fld}}
        </div>`
    @else
        <select onchange="deactiveFld(this, '{{$flashdeal->activity_id}}')" name="fld_deactive_{{$flashdeal->id}}"
            class="btn btn-sm {{getbtnfld($flashdeal->status_fld)}} btn-rounded" id="fld_deactive_{{$flashdeal->id}}">
            <option class="btn btn-sm btn-success btn-rounded p-1" value="ONGOING" data-color="btn-success"
                {{$flashdeal->status_fld == 'ONGOING' ? 'selected' : ''}}>ONGOING</option>
            <option class="btn btn-sm btn-danger btn-rounded p-1" value="DEACTIVATED" data-color="btn-danger"
                {{$flashdeal->status_fld == 'DEACTIVATED' ? 'selected' : ''}}>DEACTIVATED</option>
        </select>
    @endif

</td>
<td>{{\Carbon\Carbon::createFromTimestamp($flashdeal->begin_time)}}</td>
<td>{{\Carbon\Carbon::createFromTimestamp($flashdeal->end_time)}}</td>
<td>
    <select onchange="changeStatusFld(this, '{{$flashdeal->id}}')" name="fld_status_{{$flashdeal->id}}"
        id="fld_status_{{$flashdeal->id}}"
        class="custom-select-status btn {{$flashdeal->auto == 1 ? 'btn-success' : 'btn-danger'}} btn-sm btn-rounded">
        <option class="btn btn-sm btn-success btn-rounded p-1" value="1" data-color="btn-success" {{$flashdeal->auto == 1 ? 'selected' : ''}}>ON</option>
        <option class="btn btn-sm btn-danger btn-rounded p-1" value="0" data-color="btn-danger" {{$flashdeal->auto == 0 ? 'selected' : ''}}>OFF</option>
    </select>
</td>
<td>
    <select onchange="changeRenewFld(this, '{{$flashdeal->id}}')" name="fld_status_{{$flashdeal->id}}"
        id="fld_renew_{{$flashdeal->id}}"
        class="custom-select-status btn {{$flashdeal->renew == 1 ? 'btn-success' : 'btn-danger'}} btn-sm btn-rounded">
        <option class="btn btn-sm btn-success btn-rounded p-1" value="1" data-color="btn-success"
            {{$flashdeal->renew == 1 ? 'selected' : ''}}>ON</option>
        <option class="btn btn-sm btn-danger btn-rounded p-1" value="0" data-color="btn-danger" {{$flashdeal->renew == 0 ? 'selected' : ''}}>OFF</option>
    </select>
</td>
<td>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="4">{{$flashdeal->message}}</textarea>
    
</td>
<td>
    <a href="../show/{{$flashdeal->activity_id}}?store_id={{$store_id}}" target="_blank">
        <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
            <i class="fa fa-eye"></i>
        </button>
    </a>
</td>