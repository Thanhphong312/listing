<td><input data-id="{{$flashdealproduct->id}}" data-productid="{{$flashdealproduct->product_id}}" data-status="{{($flashdealproduct->message!='success')?'error':'success'}}" type="checkbox" id="product_fld[{{$flashdealproduct->product_id}}]"></td>
<td>{{$flashdealproduct->id}}</td>
<td>{{$flashdealproduct->product_id}}</td>
<td>{{getProductNameById($flashdealproduct->product_id)}}</td>
<td style="color:{{($discount>0)?'green':'red'}}">{{$discount}}%</td>
<td>{{$flashdealproduct->quantity_limit}}</td>
<td>{{$flashdealproduct->quantity_per_user}}</td>
<td>
    <textarea name="" id="" cols="30" rows="10">{{$flashdealproduct->message}}</textarea>
</td>
<td><input data-id="{{$flashdealproduct->id}}" type="checkbox" onclick="changepriority('{{$flashdealproduct->id}}', this)" {{$flashdealproduct->priority?"checked":"";}}></td>

<td>{{$flashdealproduct->updated_at}}</td>

<td>
    <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black"
        onclick="deleteflashdealproduct('{{$flashdealproduct->id}}')">
        <i class="fa fa-trash"></i>
    </button>
</td>