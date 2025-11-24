@extends('layouts.app')

@section('page-title', __('Report Payout'))
@section('page-heading', __('Report Payout'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Report Payout')
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
                    <div style="
                        text-align: center;
                        font-weight: bold;
                        font-size: x-large;
                    ">Staff</div>

                    <table class="table table-striped table-borderless" id="data-table-default">
                        <thead>
                            <tr>
                                <th style="width:10%">
                                    Seller
                                </th>
                                <th style="width:10%">
                                    Total Payout($)
                                </th>
                                <th style="width:10%">
                                    Total Payout Amout($)
                                </th>
                                <th style="width:10%">
                                    Total Settlement Amount($)
                                </th>
                                <th style="min-width: 10px">
                                    Total Amount Before Exchange($)
                                </th>
                            </tr>
                        </thead>
                        <tbody class="report_payout">
                            @foreach (listStaff() as $seller)
                                <tr data-id="{{$seller['id']}}" id="ajax_report_payout_{{$seller['id']}}">

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <div style="
                            text-align: center;
                            font-weight: bold;
                            font-size: x-large;
                        ">Seller</div>
                    <table class="table table-striped table-borderless" id="data-table-default">
                        <thead>
                            <tr>
                                <th style="width:10%">
                                    Seller
                                </th>
                                <th style="width:10%">
                                    Total Payout($)
                                </th>
                                <th style="width:10%">
                                    Total Payout Amout($)
                                </th>
                                <th style="width:10%">
                                    Total Settlement Amount($)
                                </th>
                                <th style="min-width: 10px">
                                    Total Amount Before Exchange($)
                                </th>
                            </tr>
                        </thead>
                        <tbody class="report_payout">
                            @foreach (listSeller() as $seller)
                                <tr data-id="{{$seller['id']}}" id="ajax_report_payout_{{$seller['id']}}">

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
                                    Total Payout($)
                                </th>
                                <th style="width:10%">
                                    Total Payout Amout($)
                                </th>
                                <th style="width:10%">
                                    Total Settlement Amount($)
                                </th>
                                <th style="min-width: 10px">
                                    Total Amount Before Exchange($)
                                </th>
                            </tr>
                        </thead>
                        <tbody class="report_payout">
                            <tr>
                                <td>Total</td>
                                <td id="total_payout"></td>
                                <td id="total_payout_amount"></td>
                                <td id="total_settlement_amount"></td>
                                <td id="total_amount_before_exchange"></td>
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
    let total_payout = 0;
    let total_payout_amount = 0;
    let total_settlement_amount = 0;
    let total_amount_before_exchange = 0;
    let promises = [];
    let listid = [];

    function report() {
        // Reset totals, promises, and listid to avoid accumulation from previous calls
        total_payout = 0;
        total_payout_amount = 0;
        total_settlement_amount = 0;
        total_amount_before_exchange = 0;
        promises = [];
        listid = [];

        // Loop through each row with class 'report_seller'
        $(".report_payout").find("tr").each(function () {
            let id = $(this).attr("data-id"); // Get data-id for each row
            if (!id) return; // Skip rows without a valid data-id

            let start_date = $("#start_date").val(); // Get start date
            let end_date = $("#end_date").val(); // Get end date

            // Create AJAX call
            let ajaxCall = $.ajax({
                url: '/reports/report-payout',
                type: 'get',
                data: {
                    id: id,
                    start_date: start_date,
                    end_date: end_date,
                }
            }).done((response) => {
                // Populate the response HTML in the target element
                $('#ajax_report_payout_' + id).html(response);
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
            // console.log("Final Totals:");

            results.forEach((result, index) => {
                let id = listid[index];
                total_payout += parseFloat($('#total_payout_' + id).data('value') || 0);
                total_payout_amount += parseFloat($('#total_payout_amount_' + id).data('value') || 0);
                total_settlement_amount += parseFloat($('#total_settlement_amount_' + id).data('value') || 0);
                total_amount_before_exchange += parseFloat($('#total_amount_before_exchange_' + id).data('value') || 0);
            });

            console.log("Total Payout:", total_payout);
            console.log("Total Payout Amount:", total_payout_amount);
            console.log("Total Settlement Amount:", total_settlement_amount);
            console.log("Total Amount Before Exchange:", total_amount_before_exchange);
            $("#total_payout").text(total_payout);
            $("#total_payout_amount").text(total_payout_amount.toFixed(2));
            $("#total_settlement_amount").text(total_settlement_amount.toFixed(2));
            $("#total_amount_before_exchange").text(total_amount_before_exchange.toFixed(2));
        }).catch((error) => {
            console.error("Error in processing all promises:", error);
        });
    }
</script>




@stop