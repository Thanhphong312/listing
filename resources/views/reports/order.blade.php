@extends('layouts.app')

@section('page-title', __('Reports'))
@section('page-heading', __('Reports'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Reports')
    </li>
@stop

@section('content')
    <div class="element-box">
        <div class="card">
            <div class="card-body">
                @include('partials.messages')
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="date" class="form-control" id="start_date">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="date" class="form-control" id="end_date">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="button" class="btn btn-danger" onclick="report()">Reports</button>
                        </div>
                    </div>
                </div>
                <div class="pt-12 row">
                    <div class="col-6">
                        <div
                            style="
                        text-align: center;
                        font-weight: bold;
                        font-size: x-large;
                    ">
                            Staff</div>

                        <table class="table table-striped table-borderless" id="data-table-default">
                            <thead>
                                <tr>
                                    <th style="min-width: 10px">
                                        Seller
                                    </th>
                                    <th style="width:10%">
                                        Orders
                                    </th>
                                    <th style="width:10%">
                                        Total($)
                                    </th>
                                    <th style="width:10%">
                                        Net revenue($)
                                    </th>
                                    <th style="min-width: 10px">
                                        Base cost($)
                                    </th>
                                    <th style="min-width: 10px">
                                        Profits($)
                                    </th>
                                    <th style="min-width: 10px">
                                        ROI(Profit/Base cost)
                                    </th>
                                    <th style="min-width: 10px">
                                        ROS(Profit/Net rev)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="report_seller">
                                @foreach (listStaff() as $seller)
                                    <tr data-id="{{ $seller['id'] }}" id="ajax_report_staff_{{ $seller['id'] }}">

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">
                        <div
                            style="
                            text-align: center;
                            font-weight: bold;
                            font-size: x-large;
                        ">
                            Seller</div>
                        <table class="table table-striped table-borderless" id="data-table-default">
                            <thead>
                                <tr>
                                    <th style="min-width: 10px">
                                        Seller
                                    </th>
                                    <th style="width:10%">
                                        Orders
                                    </th>
                                    <th style="width:10%">
                                        Total($)
                                    </th>
                                    <th style="width:10%">
                                        Net revenue($)
                                    </th>
                                    <th style="min-width: 10px">
                                        Base cost($)
                                    </th>
                                    <th style="min-width: 10px">
                                        Profits($)
                                    </th>
                                    <th style="min-width: 10px">
                                        ROI(Profit/Base cost)
                                    </th>
                                    <th style="min-width: 10px">
                                        ROS(Profit/Net rev)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="report_seller">
                                @foreach (listSeller() as $seller)
                                    <tr data-id="{{ $seller['id'] }}" id="ajax_report_staff_{{ $seller['id'] }}">

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-6 mt-3">
                        <table class="table table-striped table-borderless" id="data-table-default">
                            <thead>
                                <tr>
                                    <th style="min-width: 10px">

                                    </th>
                                    <th style="width:10%">
                                        Orders
                                    </th>
                                    <th style="width:10%">
                                        Total($)
                                    </th>
                                    <th style="width:10%">
                                        Net revenue($)
                                    </th>
                                    <th style="min-width: 10px">
                                        Base cost($)
                                    </th>
                                    <th style="min-width: 10px">
                                        Profits($)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="report_seller">
                                <tr>
                                    <td>Total</td>
                                    <td id="total_order"></td>
                                    <td id="total_amount"></td>
                                    <td id="total_revenue"></td>
                                    <td id="total_base_cost"></td>
                                    <td id="total_profit"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        let total_order = 0;
        let total_amount = 0;
        let total_revenue = 0;
        let total_base_cost = 0;
        let net_profits = 0;
        let promises = [];
        let listid = [];

        function report() {
            // Reset totals, promises, and listid to avoid accumulation from previous calls
            total_order = 0;
            total_amount = 0;
            total_revenue = 0;
            total_base_cost = 0;
            net_profits = 0;
            promises = [];
            listid = [];

            // Loop through each row with class 'report_seller'
            $(".report_seller").find("tr").each(function() {
                let id = $(this).attr("data-id"); // Get data-id for each row
                if (!id) return; // Skip rows without a valid data-id

                let start_date = $("#start_date").val(); // Get start date
                let end_date = $("#end_date").val(); // Get end date

                // Create AJAX call
                let ajaxCall = $.ajax({
                    url: '/reports/report-staff',
                    type: 'get',
                    data: {
                        id: id,
                        start_date: start_date,
                        end_date: end_date,
                    }
                }).done((response) => {
                    // Populate the response HTML in the target element
                    $('#ajax_report_staff_' + id).html(response);
                    console.log("Processing ID:", id);
                    listid.push(id);
                }).fail((xhr, status, error) => {
                    console.error("Error processing ID:", id, error);
                });

                // Add the AJAX call to the promises array
                promises.push(ajaxCall);
            });

            // Wait for all AJAX calls to complete
            Promise.allSettled(promises).then((results) => {
                console.log("Final Totals:");

                results.forEach((result, index) => {
                    let id = listid[index];
                    total_order += parseFloat($('#total_order_' + id).data('value') || 0);
                    total_amount += parseFloat($('#total_amount_' + id).data('value') || 0);
                    total_revenue += parseFloat($('#total_revenue_' + id).data('value') || 0);
                    total_base_cost += parseFloat($('#total_base_cost_' + id).data('value') || 0);
                    net_profits += parseFloat($('#net_profits_' + id).data('value') || 0);
                });

                console.log("Total Orders:", total_order);
                console.log("Total Amount:", total_amount);
                console.log("Total Revenue:", total_revenue);
                console.log("Total Base Cost:", total_base_cost);
                console.log("Net Profits:", net_profits);
                $("#total_order").text(total_order);
                $("#total_amount").text(total_amount.toFixed(2));
                $("#total_revenue").text(total_revenue.toFixed(2));
                $("#total_base_cost").text(total_base_cost.toFixed(2));
                $("#total_profit").text(net_profits.toFixed(2));
            }).catch((error) => {
                console.error("Error in processing all promises:", error);
            });
        }
    </script>




@stop
