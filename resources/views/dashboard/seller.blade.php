@extends('layouts.app')

@section('page-title', __('Dashboard'))
@section('page-heading', __('Dashboard'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
  @lang('Dashboard')
</li>
@stop

@section('content')
@include('partials.messages')
<div class="row pt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Fulfill Dashboard</h5>
        <form class="d-flex">
          <select class="form-select form-select-sm">
            <option value="Pending">Today</option>
            <option value="Active">Last Week</option>
            <option value="Cancelled">Last 30 Days</option>
          </select>
        </form>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-sm-6">
                <div class="card el-tablo shadow-sm rounded border-0">
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="value mb-3">
                            <span id="total_order_today" class="display-5 fw-bold">99999</span>
                        </div>
                        <div class="horizontal-values text-center mb-3">
                            <div class="text-danger">
                                <i class="mdi mdi-arrow-up-circle-outline"></i>
                                <span id="total_revenue_today">Rev: 99999$</span>
                            </div>
                            <div class="text-success">
                                <i class="mdi mdi-arrow-up-circle-outline"></i>
                                <span id="total_amount_today">FF: 99999$</span>
                            </div>
                        </div>
                        <div class="label text-muted mt-2">Today</div>
                    </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="card el-tablo shadow-sm rounded border-0">
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="value mb-3">
                            <span id="total_order_yesterday" class="display-5 fw-bold">99999</span>
                        </div>
                        <div class="horizontal-values text-center mb-3">
                            <div class="text-danger">
                                <i class="mdi mdi-arrow-up-circle-outline"></i>
                                <span id="total_revenue_yesterday">Rev: 99999$</span>
                            </div>
                            <div class="text-success">
                                <i class="mdi mdi-arrow-up-circle-outline"></i>
                                <span id="total_amount_yesterday">FF: 99999$</span>
                            </div>
                        </div>
                        <div class="label text-muted mt-2">Yesterday</div>
                    </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                  <div class="card el-tablo shadow-sm rounded border-0">
                      <div class="card-body d-flex flex-column align-items-center">
                          <div class="value mb-3">
                              <span id="total_order_this_month" class="display-5 fw-bold">99999</span>
                          </div>
                          <div class="horizontal-values text-center mb-3">
                              <div class="text-danger">
                                  <i class="mdi mdi-arrow-up-circle-outline"></i>
                                  <span id="total_revenue_this_month">Rev: 99999$</span>
                              </div>
                              <div class="text-success">
                                  <i class="mdi mdi-arrow-up-circle-outline"></i>
                                  <span id="total_amount_this_month">FF: 99999$</span>
                              </div>
                          </div>
                          <div class="label text-muted mt-2">This month</div>
                      </div>
                  </div>
              </div>
              <div class="col-sm-6">
                  <div class="card el-tablo shadow-sm rounded border-0">
                      <div class="card-body d-flex flex-column align-items-center">
                          <div class="value mb-3">
                              <span id="total_order_last_month" class="display-5 fw-bold">99999</span>
                          </div>
                          <div class="horizontal-values text-center mb-3">
                              <div class="text-danger">
                                  <i class="mdi mdi-arrow-up-circle-outline"></i>
                                  <span id="total_revenue_last_month">Rev: 99999$</span>
                              </div>
                              <div class="text-success">
                                  <i class="mdi mdi-arrow-up-circle-outline"></i>
                                  <span id="total_amount_last_month">FF: 99999$</span>
                              </div>
                          </div>
                          <div class="label text-muted mt-2">Last month</div>
                      </div>
                  </div>
              </div>
            </div>
          </div>

            <div class="col-md-6">
              <!--START - Chart Box-->
              <div class="card">
                <div class="card-body text-center">
                  <h4 id="total_orders">
                    537
                  </h4>
                  <p class="text-muted">
                    Orders
                  </p>
                </div>
                <div class="el-chart-w">
                  <canvas height="170px" id="lineChart" width="600px"></canvas>
                </div>
              </div>
              <!--END - Chart Box-->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--END - Grid of tablo statistics-->
  </div>
</div>
<div class="row g-3">
  <div class="col-sm-7 col-xxl-6">
    <!-- START - Create Flashdeal -->
    <div class="card shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">Create Flashdeal</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered text-center">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Number</th>
              </tr>
            </thead>
            <tbody>
              @foreach($stores as $store)
              <tr>
                <td>
                  <span class="d-none d-xl-inline-block">{{$store->id}}</span>
                </td>
                <td>{{$store->name}}</td>
                <td>
                  {{ $store->create_flashdeal ? ceil(getAllSkusTiktok($store->id) / 10000) : '0' }}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- END - Create Flashdeal -->
  </div>

  <div class="col-sm-5 col-xxl-6">  
    <div class="card shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">Production Progress</h6>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Printing</span>
            <span class="text-muted">3 printing / 10 new</span>
          </div>
          <div class="progress">
            <div class="progress-bar bg-primary" role="progressbar" style="width: 70%;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
        <div>
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Packaged</span>
            <span class="text-muted">4 Packaged / 10 new</span>
          </div>
          <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: 40%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div><!--------------------
          START - Color Scheme Toggler
          -------------------->

<!--------------------
          END - Color Scheme Toggler
          -------------------->

@stop

@section('scripts')
<script>
  $(document).ready(function () {
    $.ajax({
      url: `/dashboard/ajax/AjaxOrderToday`,
      success: function (response) {
        console.log(response.total_order);
        $("#total_order_today").text(response.total_order);
        $("#total_amount_today").text(`FF: ${response.total_base_cost}$`);
        $("#total_revenue_today").text(`Rev: ${response.total_revenue}$`);
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
    $.ajax({
      url: `/dashboard/ajax/AjaxOrderYesterday`,
      success: function (response) {
        console.log(response.total_order);
        $("#total_order_yesterday").text(response.total_order);
        $("#total_amount_yesterday").text(`FF: ${response.total_base_cost}$`);
        $("#total_revenue_yesterday").text(`Rev: ${response.total_revenue}$`);
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
    $.ajax({
      url: `/dashboard/ajax/AjaxOrderThisMonth`,
      success: function (response) {
        console.log(response.total_order);
        $("#total_order_this_month").text(response.total_order);
        $("#total_amount_this_month").text(`FF: ${response.total_base_cost}$`);
        $("#total_revenue_this_month").text(`Rev: ${response.total_revenue}$`);
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
    $.ajax({
      url: `/dashboard/ajax/AjaxOrderLastMonth`,
      success: function (response) {
        console.log(response.total_order);
        $("#total_order_last_month").text(response.total_order);
        $("#total_amount_last_month").text(`FF: ${response.total_base_cost}$`);
        $("#total_revenue_last_month").text(`Rev: ${response.total_revenue}$`);
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
  });
</script>
@stop