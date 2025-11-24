<div class="tab-pane fade show active px-2" id="qctoday" role="tabpanel" aria-labelledby="nav-home-tab">
    <table class="table table-lightborder">
        <thead>
            <tr>
                <th>
                    STAFF
                </th>
                <th class="text-center">
                    NUMBER OF QC
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($completetodays as $completetoday)
                <tr>
                    <td>
                        <div class="user-with-avatar">
                            <span class="d-xl-inline-block">{{$completetoday->username}}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        {{$completetoday->total}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>Total QC : {{$completetodays->sum('total')}}</div>
    <div>Total Side : {{$modelShippedToday}}</div>

</div>

<div class="tab-pane fade px-2" id="qcyesterday" role="tabpanel" aria-labelledby="nav-profile-tab">
    <table class="table table-lightborder">
        <thead>
            <tr>
                <th>
                    STAFF
                </th>
                <th class="text-center">
                    NUMBER OF QC
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($completeyesterdays as $completeyesterday)
                <tr>
                    <td>
                        <div class="user-with-avatar">
                            <span class="d-xl-inline-block">{{$completeyesterday->username}}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        {{$completeyesterday->total}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>Total QC : {{$completeyesterdays->sum('total')}}</div>
    <div>Total Side : {{$modelShippedYesterday}}</div>
</div>