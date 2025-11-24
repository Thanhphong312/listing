@extends('layouts.app')

@section('page-title', __('Tickets'))
@section('page-heading', __('Tickets'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Tickets')
</li>
@stop
<style>

</style>
@section('content')
<!-- Modal timeline -->
<div class="modal fade" id="timelineModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ticket Timeline</h4>
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
            <div class="modal-body">
            </div>
        </div>

    </div>
</div>
<div class="element-box">
    <div class="card">
        <div class="card-body">
            @include('partials.messages')
            <div class="panel-body clearfix mt-3">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/tickets?status=New">New ({{$news}})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/tickets?status=Solved">Solved ({{$solves}})</a>
                    </li>
                </ul>
            </div>
            <div class="">
                <!-- Filter Form -->
                <form id="filter-form">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" class="form-control" id="ticket_id" name="ticket_id" placeholder="Ticket ID" value="{{$request->ticket_id}}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" class="form-control" id="order_id" name="order_id" placeholder="Order ID" value="{{$request->order_id}}">
                            </div>
                        </div>
                        @if($role!='Seller')
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control select2" id="support" name="support">
                                        <option value="">Support</option>
                                        @foreach(listSupport() as $support)
                                            <option value="{{$support['id']}}" {{ $request->support == $support['id'] ? 'selected' : '' }}>
                                                {{$support['username']}}
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
                                <a href="{{ route('orders.index')}}" type="submit" class="btn btn-primary form-control btn-rounded">Clear Filters</a>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row"> -->
                    <!-- <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Filters</button>
                        </div> -->
                    <!-- </div> -->
                </form>
            </div>
            <div class="pt-3">
                <table class="table table-striped table-borderless" id="data-table-default">
                    <thead>
                        <tr>
                            <th style="width:10%">Id</th>
                            <th style="width:10%">Order id</th>
                            <th style="width:10%">Status</th>
                            <th style="width:50%">Subject</th>
                            <th style="width:50%">Last reply</th>
                            <th style="width:20%">Created at</th>
                            <th style="width:20%">Updated at</th>
                            <th style="width:20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supports as $support)
                        <tr>
                            <td>
                                <a href="tickets/view/{{$support->id}}" target="_blank">#{{$support->id}}</a>
                            </td>
                            <td>
                                <a href="orders?order_id={{$support->order_id}}" target="_black">{{$support->order_id}}</a>
                            </td>
                            <td>
                                {{$support->status}}
                            </td>
                            <td>
                                {{$support->subject}}
                            </td>
                            @if($support->chats->isNotEmpty() && $support->chats->last()->message != null )
                                <td>
                                    {{$support->chats->last()->message}}
                                </td>
                            @else 
                                <td>
                                </td>
                            @endif
                            <td>
                                {{$support->created_at}}
                            </td>
                            <td>
                                {{$support->updated_at}}
                            </td>
                            <td>
                                @if($support->status=='Solved')
                                    <button id="tl_{{$support->id}}" data-id="{{$support->id}}" type="button" class="btn btn-sm btn-info timeline-btn mt-1"
                                    onclick="timeline_btn(this)">Timeline</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            {{ $supports->appends($_GET)->links() }}
        </div>
    </div>
</div>
</div>
<style>

</style>
@section('scripts')
<script>
    function timeline_btn(order) {
        var order_id = $(order).data('id');

        // AJAX request
        $.ajax({
            url: '/timelineticket/ajax',
            type: 'get',
            data: {
                object_id: order_id
            },
            success: function (response) {
                // Add response in Modal body
                $('#timelineModal .modal-body').html(response);
                // Display Modal
                $('#timelineModal').modal('show');
            }
        });
    }
</script>
@stop

@endsection
