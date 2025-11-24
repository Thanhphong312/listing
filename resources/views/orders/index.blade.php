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
                <div class="modal fade" id="addOrder">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Add Order</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="alert alert-danger" style="display:none"></div>
                                <form id="body_add" class="form-horizontal" enctype="multipart/form-data">
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal fade" id="editOrder">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Edit Order</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="alert alert-danger" style="display:none"></div>
                                <form id="body_edit" class="form-horizontal" enctype="multipart/form-data">
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <form id="filter-form" class="col-12">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="tiktok_order_id" name="tiktok_order_id" 
                                        placeholder="Tiktok Order ID" value="{{ $request->tiktok_order_id }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <select class="form-control select2" id="store_id" name="store_id">
                                        <option value="">Store...</option>
                                        @foreach (listStore() as $store)
                                            <option value="{{ $store['id'] }}" {{ $request->store_id == $store['id'] ? 'selected' : '' }}>
                                                {{ $store['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" class="form-control"  name="datefrom"  id="datefrom" value="{{$request->datefrom}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" class="form-control" name="dateto" id="dateto" value="{{$request->dateto}}">
                                </div>
                            </div>
                            @if ($role != 'Staff')
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <select class="form-control select2" id="staff_id" name="staff_id">
                                            <option value="">Staff...</option>
                                            @foreach (listStaff() as $staff)
                                                <option value="{{ $staff['id'] }}" {{ $request->staff_id == $staff['id'] ? 'selected' : '' }}>
                                                    {{ $staff['username'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <select class="form-control select2" id="seller_id" name="seller_id">
                                            <option value="">Seller...</option>
                                            @foreach (listSeller() as $staff)
                                                <option value="{{ $staff['id'] }}" {{ $request->seller_id == $staff['id'] ? 'selected' : '' }}>
                                                    {{ $staff['username'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-2 col-6">
                                <button type="submit" class="btn btn-primary btn-rounded w-100">
                                    <i class="fas fa-search"></i> Filters
                                </button>
                            </div>
                            <div class="col-md-2 col-6">
                                <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-rounded w-100">
                                    <i class="fas fa-sync-alt"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="m-1">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" onclick="">Add Order</button>
                        </div>
                    </div>
                </div>
                <div class="m-1">
                    <div class="row">
                        <div id="total">Total: </div>
                    </div>
                </div>

               
                <div class="pt-12">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless" id="data-table-default">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Store</th>
                                <th>User</th>
                                <th>Tiktok order ID</th>
                                <th>Tracking number</th>
                                <th style="min-width: 600px">Items</th>
                                <th>Seller discount</th>
                                <th>Total amount</th>
                                <th>Status</th>
                                <th>Tiktok create date</th>
                                <th>Net revenue</th>
                                <th>Base cost</th>
                                <th>Dessign fee</th>
                                <th>Profit</th>
                               <th>Created At</th>
                               <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="order-list">
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->store ? $order->store->name : '' }}</td>
                                    <td>{{ $order->user ? $order->user->username : '' }}</td>
                                    <td>{{ $order->tiktok_order_id }}</td>
                                    <td>
                                        <a href="https://t.17track.net/en#nums={{ $order->tracking_number }}" target="_blank" rel="noopener noreferrer">
                                        {{ $order->tracking_number }}    
                                        </a>
                                    </td>
                                    <td>
                                        <div>
                                            @foreach($order->items as $item)
                                            <div class="row align-items-center mb-3">
                                                <!-- Hình ảnh -->
                                                <div class="col-2">
                                                    @if($item->sku_image)
                                                    <figure>
                                                        <figcaption class="text-center">Sku Image</figcaption>
                                                        <a href="{{$item->sku_image}}" target="_blank">
                                                            <img src="{{$item->sku_image}}" class="img-thumbnail" alt="Product Image">
                                                        </a>
                                                    </figure>
                                                    @endif
                                                </div>
                                                <!-- Thông tin và nút -->
                                                <div class="col-8">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-body-1 font-weight-medium" data-toggle="tooltip" data-placement="top" title="{{$item->product_name}}"><span class="btn btn-sm {{($item->quantity >= 2) ? 'btn-danger' : 'btn-warning'}}">QTY:
                                                                {{$item->quantity}}</span> {{substr($item->product_name, 0, 40)}}</span>
                                                        <span class="text-body-1 font-weight-medium">Sku: {{$item->sku_name}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>{{ $order->seller_discount }}</td>
                                    <td>{{ $order->total_amount }}</td>
                                    <td>{{ $order->order_status }}</td>
                                    <td>{{ $order->tiktok_create_date }}</td>
                                    <td>{{ $order->net_revenue }}</td>
                                    <td>{{ $order->base_cost }}</td>
                                    <td>{{ $order->design_fee }}</td>
                                    <td>{{  $order->net_revenue - $order->base_cost - $order->design_fee }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>
                                        <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black"
                                            onclick="">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <a href="">
                                            <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
                <div class="pagination-links">
                    @if (count($orders))
                        {{ $orders->appends($_GET)->links() }}
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
                url: './orders/ajax-total',
                type: 'get',
                data: {
                    tiktok_order_id:'{{$request->tiktok_order_id}}',
                    store_id:'{{$request->store_id}}',
                    staff_id:'{{$request->staff_id}}',
                    seller_id:'{{$request->seller_id}}',
                    datefrom:'{{$request->datefrom}}',
                    dateto:'{{$request->dateto}}',
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
