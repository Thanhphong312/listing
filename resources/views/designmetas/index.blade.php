@extends('layouts.app')

@section('page-title', __('Designs'))
@section('page-heading', __('Designs'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Designs')
</li>
@stop

@section('content')
<div class="element-box">
    <div class="card">
        <div class="card-body">

            @include('partials.messages')
            <div class="panel-body clearfix mt-3" id="shortlink">
            
            </div>
            <div class="">
                <!-- Filter Form -->
                <form id="filter-form">
                    {{-- <div class="row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <input type="text" class="form-control" id="order_id" name="order_id"
                                    placeholder="Order ID" value="{{$request->order_id}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="ref_id" name="ref_id"
                                    placeholder="Search by ref ID" value="{{$request->ref_id}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Search by name" value="{{$request->name}}">
                            </div>
                        </div>
                        @if($role != 'Seller')
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control select2" id="seller" name="seller">
                                        <option value="">Seller</option>
                                        @foreach(listSeller() as $seller)
                                            <option value="{{$seller['id']}}" {{ $request->seller == $seller['id'] ? 'selected' : '' }}>
                                                {{$seller['username']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" id="filterlabel" name="filterLabel">
                                    <option>Label Tracking</option>
                                    <option value="have_label" {{ $request->filterLabel == 'have_label' ? 'selected' : '' }}>Have label</option>
                                    <option value="no_label" {{ $request->filterLabel == 'no_label' ? 'selected' : '' }}>
                                        No label</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" id="filterfulfill" name="filterFulfill">
                                    <option value="">Fulfill Status</option>
                                    @foreach(orderStatuss() as $keyStatus => $orderStatus)
                                        <option value="{{$keyStatus}}" {{ $request->filterFulfill == $keyStatus ? 'selected' : '' }}>
                                            {{$orderStatus}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary form-control btn-rounded">Filters</button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('orders.index')}}" type="submit"
                                    class="btn btn-primary form-control btn-rounded">Clear Filters</a>
                            </div>
                        </div>
                    </div> --}}
                </form>
            </div>
            <div class="pt-3">
                <table class="table table-striped table-borderless" id="data-table-default">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="checkboxorders" onclick="checkboxorders()" disabled>
                            </th>
                            <th style="min-width: 250px">
                                Info
                            </th>
                            <th>Ticket</th>
                            <th style="min-width: 150px">Fulfill Status</th>
                            <th>Label Status</th>
                            <th style="min-width: 200px">Shipping address</th>
                            <th>Shipping Medthod</th>
                            <th>Tracking ID / Label</th>
                            <th>Tracking Status</th>
                            <th style="min-width: 100px">Total Cost</th>
                            <!-- gearment -->
                            <th style="min-width: 200px">Fulfull Partner</th>
                            <th style="min-width: 200px">Note</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Process Time</th>
                            <th style="min-width: 100px">action</th>
                        </tr>
                    </thead>
                    <tbody class="order-list">
                        @foreach($orders as $order)
                            <tr style="background-color: {{ $order->getBackgroupRecord() }};" data-id="{{$order->id}}" id="ajaxorder_{{$order->id}}">

                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
            <ul class="pagination mt-3">
                
            </ul>
        </div>
    </div>
</div>

<style>

</style>
@section('scripts')
<script>
    
</script>
@stop

@endsection