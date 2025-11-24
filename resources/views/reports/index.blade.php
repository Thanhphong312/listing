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
                            <label>Start Date</label>
                            <input type="date" class="form-control" id="start_date">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" class="form-control" id="end_date">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Shop Code</label>
                            <select class="form-control select2" id="shop_code" name="shop_code">
                                <option value="">store...</option>
                                @foreach (listStoreReport() as $store)
                                    <option value="{{ $store['shop_code'] }}">
                                        {{ $store['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary" id="generateReport">Report Order</button>
                <button class="btn btn-primary" id="reportall">Report All</button>
                <button class="btn btn-primary" id="reportpayout">Report All Payout</button>

                <div class="mt-4" id="reportResult">

                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#generateReport').click(function() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();
                const shopCode = $('#shop_code').val();

                if (!startDate || !endDate || !shopCode) {
                    alert('Please fill in all required fields');
                    return;
                }

                $(this).prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );
                $('#reportResult').html(
                    '<div class="text-center"><div class="spinner-border" role="status"></div></div>');

                $.ajax({
                    url: '{{ route('report.orders') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify({
                        start_date: startDate,
                        end_date: endDate,
                        shop_code: shopCode,
                        team: {{ Auth::user()?->team?->id??1 }}
                    }),
                    success: function(response) {
                        if (response.success) {
                            $('#reportResult').html(`
                                <div class="alert alert-success">
                                    ${response.message}
                                </div>
                            `);
                        } else {
                            $('#reportResult').html(
                                `<div class="alert alert-danger">Failed to load report data: ${response.message || ''}</div>`
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'An error occurred while fetching the report';
                        if (xhr.responseJSON) {
                            errorMessage = xhr.responseJSON.message || xhr.responseJSON.error ||
                                errorMessage;
                            if (xhr.responseJSON.url) {
                                console.log('Failed URL:', xhr.responseJSON.url);
                            }
                        }
                        $('#generateReport').prop('disabled', false).text('Report Order');
                    },
                    complete: function() {
                        $('#generateReport').prop('disabled', false).text('Report Order');
                    }
                });
            });
            $('#reportall').click(function() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                if (!startDate || !endDate) {
                    alert('Please fill in all required fields');
                    return;
                }

                $(this).prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );
                $('#reportResult').html(
                    '<div class="text-center"><div class="spinner-border" role="status"></div></div>');

                $.ajax({
                    url: '{{ route('report.reportall') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify({
                        start_date: startDate,
                        end_date: endDate,
                    }),
                    success: function(response) {
                        if (response.success) {
                            $('#reportResult').html(`
                                <div class="alert alert-success">
                                    ${response.message}
                                </div>
                            `);
                        } else {
                            $('#reportResult').html(
                                `<div class="alert alert-danger">Failed to load report data: ${response.message || ''}</div>`
                            );
                        }
                        $(this).prop('disabled', false)
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'An error occurred while fetching the report';
                        if (xhr.responseJSON) {
                            errorMessage = xhr.responseJSON.message || xhr.responseJSON.error ||
                                errorMessage;
                            if (xhr.responseJSON.url) {
                                console.log('Failed URL:', xhr.responseJSON.url);
                            }
                        }
                        $('#reportResult').html(
                            `<div class="alert alert-danger">${errorMessage}</div>`);
                        $('#generateReport').prop('disabled', false).text('Order');
                    },
                    complete: function() {
                        $('#generateReport').prop('disabled', false).text('Order');
                    }
                });
            });
            $('#reportpayout').click(function() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                if (!startDate || !endDate) {
                    alert('Please fill in all required fields');
                    return;
                }

                $(this).prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );
                $('#reportResult').html(
                    '<div class="text-center"><div class="spinner-border" role="status"></div></div>');

                $.ajax({
                    url: '{{ route('report.payouts') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify({
                        start_date: startDate,
                        end_date: endDate,
                    }),
                    success: function(response) {
                        if (response.success) {
                            $('#reportResult').html(`
                                <div class="alert alert-success">
                                    ${response.message}
                                </div>
                            `);
                        } else {
                            $('#reportResult').html(
                                `<div class="alert alert-danger">Failed to load report data: ${response.message || ''}</div>`
                            );
                        }
                        $(this).prop('disabled', false)
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'An error occurred while fetching the report';
                        if (xhr.responseJSON) {
                            errorMessage = xhr.responseJSON.message || xhr.responseJSON.error ||
                                errorMessage;
                            if (xhr.responseJSON.url) {
                                console.log('Failed URL:', xhr.responseJSON.url);
                            }
                        }
                        $('#reportResult').html(
                            `<div class="alert alert-danger">${errorMessage}</div>`);
                        $(this).prop('disabled', false)
                    },
                    complete: function() {
                        $('#generateReport').prop('disabled', false).text('Report Order');
                    }
                });
            });
        });
    </script>
@stop
