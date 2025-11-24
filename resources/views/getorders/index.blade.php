@extends('layouts.app')

@section('page-title', __('Orders'))
@section('page-heading', __('Orders'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Orders')
</li>
@stop

@section('content')

<div class="element-box">
    <div class="card">
        <div class="card-body">
            @include('partials.messages')
            <div class="table-responsive">
                    <div class="m-1">
                        <div class="row">
                            <div id="total">Total: {{$total_count}}</div>
                        </div>
                    </div>
                    <a href="?page_token={{$next_page_token}}">next ></a>
                    <table class="table table-striped table-borderless" id="data-table-default">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>TRACKING</th>
                                <th>ITEMS</th>
                                <th>STATUS</th>
                                <th>SHIPPING FEE</th>
                                <th>COST</th>
                                <th>TOTAL AMOUNT</th>
                               <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody class="order-list">
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order['id'] }}</td>
                                    <td>{{ $order['tracking_number'] ?? "" }}</td>
                                    <td>{{ count($order['line_items']) }}</td>
                                    <td>{{ $order['payment']['shipping_fee']?? ""}}</td>
                                    <td>{{ $order['payment']['sub_total'] ?? ""}}</td>
                                    <td>{{ $order['payment']['total_amount']?? "" }}</td>
                                    <td>{{ $order['tts_sla_time'] ?? ""}}</td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>
<style>
    .custom-select-status {
        appearance: none;
        /* Remove default arrow icon on Firefox */
        -webkit-appearance: none;
        /* Remove default arrow icon on Chrome and Safari */
        -moz-appearance: none;
        /* Remove default arrow icon on older versions of Firefox */
    }
</style>
@section('scripts')
    <script>
    
    </script>
@stop

@endsection