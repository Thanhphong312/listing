@extends('layouts.app')

@section('page-title', __('Tracking Not Active'))
@section('page-heading', __('Tracking Not Active'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Tracking Not Active')
</li>
@stop
<style>

</style>
@section('content')
<!-- Modal timeline -->

<div class="element-box">
    <div class="card">
        <div class="card-body">
            @include('partials.messages')
            <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="details-tab" data-toggle="tab" href="#unresolved" role="tab" aria-controls="home" aria-selected="true">
                        @lang('Unresole')
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="authentication-tab" data-toggle="tab" href="#resolved" role="tab" aria-controls="home" aria-selected="true">
                        @lang('Resole')
                    </a>
                </li>
            </ul>

            <div class="tab-content mt-4" id="nav-tabContent">
                <div class="tab-pane fade show active px-2" id="unresolved" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="">
                        <!-- Filter Form -->
                        <form id="filter-form">
                            <div class="row">
                                <!-- <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" class="form-control" id="order_id" name="order_id" placeholder="Order ID" value="{{$request->order_id}}">
                            </div>
                        </div>
                        <div class="col-md-3 row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary form-control btn-rounded">Filters</button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('orders.index')}}" type="submit" class="btn btn-primary form-control btn-rounded">Clear Filters</a>
                            </div>
                        </div> -->
                            </div>
                            <!-- <div class="row"> -->
                            <!-- <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Filters</button>
                        </div> -->
                            <!-- </div> -->
                        </form>
                    </div>
                    <div class="pt-12">
                        <table class="table table-striped table-borderless" id="data-table-default">
                            <thead>
                                <tr>
                                    <th style="width:10%">Order id</th>
                                    <th style="width:10%">17 track</th>
                                    <th style="width:40%">Track order</th>
                                    <th style="width:5%">Total day</th>
                                    <th style="width:50%">Note</th>
                                    <th style="width:20%">Created at</th>
                                    <th style="width:20%">Updated at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listTrackingInfoReceicesunresolves as $listTrackingInfoReceicesunresolve)
                                <tr>
                                    <td>
                                        <a href="../orders?order_id={{$listTrackingInfoReceicesunresolve->order_id}}" target="_black">{{$listTrackingInfoReceicesunresolve->order_id}}</a>
                                    </td>
                                    <td>
                                        <a href="https://t.17track.net/en#nums={{$listTrackingInfoReceicesunresolve->tracking_id}}" target="_black">{{$listTrackingInfoReceicesunresolve->tracking_id}}</a>
                                    </td>
                                    <td>
                                        <a href="https://pressify.us/trackings?so_id={{$listTrackingInfoReceicesunresolve->tracking_id}}" target="_black">{{$listTrackingInfoReceicesunresolve->tracking_id}}</a>
                                    </td>
                                    <td>
                                        {{$listTrackingInfoReceicesunresolve->total_day}}
                                    </td>
                                    <td>
                                        <textarea id="note_tracking_{{$listTrackingInfoReceicesunresolve->id}}" type="text" rows="2" cols="40" class="form-control text-left m-1" name="note" placeholder="Note" onchange="statusChange('{{$listTrackingInfoReceicesunresolve->id}}')">{{($listTrackingInfoReceicesunresolve->metas->first())?$listTrackingInfoReceicesunresolve->metas->first()->meta_value:""}}</textarea>
                                    </td>
                                    <td>
                                        {{$listTrackingInfoReceicesunresolve->created_at}}
                                    </td>
                                    <td>
                                        {{$listTrackingInfoReceicesunresolve->updated_at}}
                                    </td>
                                    <td>
                                        <select name="resole" class="btn btn-sm  btn-info custom-select-status" id="resole_{{$listTrackingInfoReceicesunresolve->id}}" onchange="updateResoleStatus('{{$listTrackingInfoReceicesunresolve->id}}')">
                                            <option value="">Unresolved</option>
                                            <option value="1" >Resolved</option>
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    {{ $listTrackingInfoReceicesunresolves->appends($_GET)->links() }}
                </div>

                <div class="tab-pane fade px-2" id="resolved" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="">
                        <!-- Filter Form -->
                        <form id="filter-form">
                            <div class="row">
                                <!-- <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" class="form-control" id="order_id" name="order_id" placeholder="Order ID" value="{{$request->order_id}}">
                            </div>
                        </div>
                        <div class="col-md-3 row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary form-control btn-rounded">Filters</button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('orders.index')}}" type="submit" class="btn btn-primary form-control btn-rounded">Clear Filters</a>
                            </div>
                        </div> -->
                            </div>
                            <!-- <div class="row"> -->
                            <!-- <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Filters</button>
                        </div> -->
                            <!-- </div> -->
                        </form>
                    </div>
                    <div class="pt-12">
                        <table class="table table-striped table-borderless" id="data-table-default">
                            <thead>
                                <tr>
                                    <th style="width:10%">Order id</th>
                                    <th style="width:10%">17 track</th>
                                    <th style="width:40%">Track order</th>
                                    <th style="width:5%">Total day</th>
                                    <th style="width:50%">Note</th>
                                    <th style="width:20%">Created at</th>
                                    <th style="width:20%">Updated at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listTrackingInfoReceices as $listTrackingInfoReceice)
                                <tr>
                                    <td>
                                        <a href="../orders?order_id={{$listTrackingInfoReceice->order_id}}" target="_black">{{$listTrackingInfoReceice->order_id}}</a>
                                    </td>
                                    <td>
                                        <a href="https://t.17track.net/en#nums={{$listTrackingInfoReceice->tracking_id}}" target="_black">{{$listTrackingInfoReceice->tracking_id}}</a>
                                    </td>
                                    <td>
                                        <a href="https://pressify.us/trackings?so_id={{$listTrackingInfoReceice->tracking_id}}" target="_black">{{$listTrackingInfoReceice->tracking_id}}</a>
                                    </td>
                                    <td>
                                        {{$listTrackingInfoReceice->total_day}}
                                    </td>
                                    <td>
                                        <textarea id="note_tracking_{{$listTrackingInfoReceice->id}}" type="text" rows="2" cols="40" class="form-control text-left m-1" name="note" placeholder="Note" onchange="statusChange('{{$listTrackingInfoReceice->id}}')">{{($listTrackingInfoReceice->metas->first())?$listTrackingInfoReceice->metas->first()->meta_value:""}}</textarea>
                                    </td>
                                    <td>
                                        {{$listTrackingInfoReceice->created_at}}
                                    </td>
                                    <td>
                                        {{$listTrackingInfoReceice->updated_at}}
                                    </td>
                                    <td>
                                       {{$listTrackingInfoReceice->order->resole_tracking_not_active?"Resolved":"Unresolved"}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    {{ $listTrackingInfoReceices->appends($_GET)->links() }}
                </div>


            </div>


        </div>
    </div>
</div>
</div>
<style>

</style>
@section('scripts')
<script>
    function updateResoleStatus(tracking_id) {
        var resole = $('#resole_' + tracking_id).val();
        $.ajax({
            url: '../dashboard/trackingResole',
            type: 'GET',
            data: {
                id: tracking_id,
                resole: resole
            },
            success: function(response) {
                if (response) {
                    alert('update resole success');
                }
            },
            error: function(response) {
                var jsonResponse = JSON.parse(response.responseText);
                var data = jsonResponse.data;
                alert(data);
            }
        });

    }

    function statusChange(tracking_id) {
        var note = $('#note_tracking_' + tracking_id).val();
        $.ajax({
            url: '../trackingNote',
            type: 'GET',
            data: {
                id: tracking_id,
                note: note
            },
            success: function(response) {
                if (response) {
                    alert('update note success');
                }
            },
            error: function(response) {
                var jsonResponse = JSON.parse(response.responseText);
                var data = jsonResponse.data;
                alert(data);
            }
        });
    }
</script>
@stop

@endsection