@if(empty($supports))
<div class="row m-0" style="height: 600px; overflow: scroll;">
    <table class="table table-lightborder" >
        <thead>
            <tr>
                <th>
                    Id
                </th>
                <th>
                    Order ID
                </th>
                <th>
                    Subject
                </th>
                <th class="text-center">
                    Last reply
                </th>
                <th class="text-center">
                    Create At
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($supports as $support)
            <tr>
                <td>
                    <a href="/tickets/view/{{$support->id}}" target="_blank" rel="noopener noreferrer">{{$support->id}}</a>
                </td>
                <td>
                    <a href="orders?order_id={{$support->order_id}}" target="_blank" rel="noopener noreferrer">{{$support->order_id}}</a>
                </td>
                <td>
                    {{$support->subject}}
                </td>
                <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($support->updated_at))->diffForHumans(null, true) }}</td>
                <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($support->created_at))->diffForHumans(null, true) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="row m-0">
    <table class="table table-lightborder" >
        <thead>
            <tr>
                <th>
                    Id
                </th>
                <th>
                    Order ID
                </th>
                <th>
                    Subject
                </th>
                <th class="text-center">
                    Last reply
                </th>
                <th class="text-center">
                    Create At
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                No data
            </tr>
        </tbody>
    </table>
</div>
@endif

    
