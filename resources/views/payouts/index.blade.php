@extends('layouts.app')

@section('page-title', __('Payouts'))
@section('page-heading', __('Payouts'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Payouts')
    </li>
@stop

@section('content')
    <div class="element-box">
        <div class="card">
            <div class="card-body">

                @include('partials.messages')
                <div class="row">
                    <form id="filter-form" class="col-12 row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="payout_id" name="payout_id" placeholder="payout_id"
                                    value="{{ $request->payout_id }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control select2" id="store_id" name="store_id">
                                    <option value="">Store...</option>
                                    @foreach (listStore() as $store)
                                        <option value="{{ $store['id'] }}"
                                            {{ $request->store_id == $store['id'] ? 'selected' : '' }}>
                                            {{ $store['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if ($role != 'Staff')
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control select2" id="staff_id" name="staff_id">
                                        <option value="">Staff...</option>
                                        @foreach (listStaff() as $staff)
                                            <option value="{{ $staff['id'] }}"
                                                {{ $request->staff_id == $staff['id'] ? 'selected' : '' }}>
                                                {{ $staff['username'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control select2" id="seller_id" name="seller_id">
                                        <option value="">Seller...</option>
                                        @foreach (listSeller() as $staff)
                                            <option value="{{ $staff['id'] }}"
                                                {{ $request->seller_id == $staff['id'] ? 'selected' : '' }}>
                                                {{ $staff['username'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3 row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary form-control btn-rounded">Filters</button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('orders.index') }}" type="submit"
                                    class="btn btn-primary form-control btn-rounded">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="total">Total: </div>
                <div class="pt-12">
                    <table class="table table-striped table-borderless" id="data-table-default">
                        <thead>
                            <tr>
                                <th style="width:10%">
                                    ID
                                </th>
                                <th style="min-width: 80px">
                                    Store
                                </th>
                                <th style="min-width: 80px">
                                    User
                                </th>
                                <th style="width:10%">
                                    Payout ID
                                </th>
                                <th style="width:10%">
                                    Settlement Amount
                                </th>
                                <th style="width:10%">
                                    Amount Before Exchange
                                </th>
                                <th style="width:10%">
                                    Reserve amount
                                </th>
                                <th style="width:10%">
                                    Payout Complete Date
                                </th>
                                <th style="width:10%">
                                    Payout initiation date
                                </th>
                                <th style="width:10%">
                                    Status
                                </th>
                                <th style="width:10%">
                                    Bank Acount
                                </th>
                                <th style="min-width: 100px">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="order-list">
                            @foreach ($payouts as $payout)
                                <tr>
                                    <td>{{ $payout->id }}</td>
                                    <td>{{ getStoreNameById($payout->store_id) }}</td>
                                    <td>{{ getUserNameById($payout->user_id) }}</td>
                                    <td>{{ $payout->payout_id }}</td>
                                    <td>{{ $payout->settlement_amount }}</td>
                                    <td>{{ $payout->amount_before_exchange }}</td>
                                    <td>{{ $payout->reserve_amount }}</td>
                                    <td>{{ $payout->date_complete }}</td>
                                    <td>{{ $payout->date }}</td>
                                    <td>{{ $payout->status }}</td>
                                    <td>{{ $payout->bank_account }}</td>
                                    <td>{{ $payout->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-links">
                    @if (count($payouts))
                        {{ $payouts->appends($_GET)->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>

    </style>
@section('scripts')
    <script>
        $.ajax({
                url: './payouts/ajax-total',
                type: 'get',
                data: {
                    payout_id:'{{$request->payout_id}}',
                    store_id:'{{$request->store_id}}',
                    staff_id:'{{$request->staff_id}}',
                    seller_id:'{{$request->seller_id}}',
                },
                success: function (response) {
                    console.log(response);
                    $("#total").text("Total: "+response);
                },
                error: function (xhr, status, error) {
                    // if(store_type == 4){
                    // alert('order load fail: Order ID:'+ id);
                    // }

                }
            });
    </script>
@stop

@endsection
