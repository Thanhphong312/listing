
@if(!empty($transactions))
<div class="row m-0" style="height: 600px; overflow: scroll;">
    <table class="table table-lightborder">
        <thead>
            <tr>
                <th>Seller</th>
                <th>Type</th>
                <th>Remaining Balance</th>
                <th>Amount</th>
                <th style="min-width: 100px">Status</th>
                <th style="min-width: 120px">Created at</th>
            </tr>
        </thead>
        <tbody id="ajaxTranstions">
            @foreach($transactions as $transaction)
                        @php
                            $statusClass = 'success';
                            if ($transaction->status == 'pending') {
                                $statusClass = 'warning';
                            }
                            if ($transaction->status == 'cancelled') {
                                $statusClass = 'secondary';
                            }
                        @endphp
                        <tr>
                            <td>
                                {{getUsernameById($transaction->seller_id)}}
                            </td>
                            <td>
                                {{Str::title($transaction->type)}}
                            </td>
                            <td>
                                @money($transaction->remaining_balance)
                            </td>
                            <td>
                                @money($transaction->amount)
                            </td>
                            <td>
                                <span class="badge badge-{{$statusClass}}">{{Str::title($transaction->status)}}</span>
                            </td>
                            <td style="width: 200px;">
                                {{$transaction->created_at->format('Y-m-d')}}
                            </td>
                        </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="row m-0" style="height: 600px; overflow: scroll;">
    <table class="table table-lightborder">
        <thead>
            <tr>
                <th>Seller</th>
                <th>Type</th>
                <th>Remaining Balance</th>
                <th>Amount</th>
                <th style="min-width: 100px">Status</th>
                <th style="min-width: 120px">Created at</th>
            </tr>
        </thead>
        <tbody id="ajaxTranstions">
            <tr>
                No data
            </tr>
        </tbody>
    </table>
</div>
@endif