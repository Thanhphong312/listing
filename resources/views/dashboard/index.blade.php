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
            <div style="
                  font-weight: bold;
                  text-align: center;
                  margin: 30px;
                  font-size: 20px;
              ">Team 1</div>
            <div class="row">
              <div class="col-sm-6">
                <div class="card el-tablo shadow-sm rounded border-0">
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="value mb-3">
                            <span id="total_order_today_team_1" class="display-5 fw-bold">99999</span>
                        </div>
                        <div class="horizontal-values text-center mb-3">
                            <div class="text-danger">
                                <i class="mdi mdi-arrow-up-circle-outline"></i>
                                <span id="total_revenue_today_team_1">Rev: 99999$</span>
                            </div>
                            <div class="text-success">
                                <i class="mdi mdi-arrow-up-circle-outline"></i>
                                <span id="total_amount_today_team_1">FF: 99999$</span>
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
                            <span id="total_order_yesterday_team_1" class="display-5 fw-bold">99999</span>
                        </div>
                        <div class="horizontal-values text-center mb-3">
                            <div class="text-danger">
                                <i class="mdi mdi-arrow-up-circle-outline"></i>
                                <span id="total_revenue_yesterday_team_1">Rev: 99999$</span>
                            </div>
                            <div class="text-success">
                                <i class="mdi mdi-arrow-up-circle-outline"></i>
                                <span id="total_amount_yesterday_team_1">FF: 99999$</span>
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
                              <span id="total_order_this_month_team_1" class="display-5 fw-bold">99999</span>
                          </div>
                          <div class="horizontal-values text-center mb-3">
                              <div class="text-danger">
                                  <i class="mdi mdi-arrow-up-circle-outline"></i>
                                  <span id="total_revenue_this_month_team_1">Rev: 99999$</span>
                              </div>
                              <div class="text-success">
                                  <i class="mdi mdi-arrow-up-circle-outline"></i>
                                  <span id="total_amount_this_month_team_1">FF: 99999$</span>
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
                              <span id="total_order_last_month_team_1" class="display-5 fw-bold">99999</span>
                          </div>
                          <div class="horizontal-values text-center mb-3">
                              <div class="text-danger">
                                  <i class="mdi mdi-arrow-up-circle-outline"></i>
                                  <span id="total_revenue_last_month_team_1">Rev: 99999$</span>
                              </div>
                              <div class="text-success">
                                  <i class="mdi mdi-arrow-up-circle-outline"></i>
                                  <span id="total_amount_last_month_team_1">FF: 99999$</span>
                              </div>
                          </div>
                          <div class="label text-muted mt-2">Last month</div>
                      </div>
                  </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div style="
                  font-weight: bold;
                  text-align: center;
                  margin: 30px;
                  font-size: 20px;
              ">Team 2</div>
            <div class="row">
              <div class="col-sm-6">
                <div class="card el-tablo shadow-sm rounded border-0">
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="value mb-3">
                            <span id="total_order_today_team_2" class="display-5 fw-bold">99999</span>
                        </div>
                        <div class="horizontal-values text-center mb-3">
                            <div class="text-danger">
                                <i class="mdi mdi-arrow-up-circle-outline"></i>
                                <span id="total_revenue_today_team_2">Rev: 99999$</span>
                            </div>
                            <div class="text-success">
                                <i class="mdi mdi-arrow-up-circle-outline"></i>
                                <span id="total_amount_today_team_2">FF: 99999$</span>
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
                            <span id="total_order_yesterday_team_2" class="display-5 fw-bold">99999</span>
                        </div>
                        <div class="horizontal-values text-center mb-3">
                            <div class="text-danger">
                                <i class="mdi mdi-arrow-up-circle-outline"></i>
                                <span id="total_revenue_yesterday_team_2">Rev: 99999$</span>
                            </div>
                            <div class="text-success">
                                <i class="mdi mdi-arrow-up-circle-outline"></i>
                                <span id="total_amount_yesterday_team_2">FF: 99999$</span>
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
                              <span id="total_order_this_month_team_2" class="display-5 fw-bold">99999</span>
                          </div>
                          <div class="horizontal-values text-center mb-3">
                              <div class="text-danger">
                                  <i class="mdi mdi-arrow-up-circle-outline"></i>
                                  <span id="total_revenue_this_month_team_2">Rev: 99999$</span>
                              </div>
                              <div class="text-success">
                                  <i class="mdi mdi-arrow-up-circle-outline"></i>
                                  <span id="total_amount_this_month_team_2">FF: 99999$</span>
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
                              <span id="total_order_last_month_team_2" class="display-5 fw-bold">99999</span>
                          </div>
                          <div class="horizontal-values text-center mb-3">
                              <div class="text-danger">
                                  <i class="mdi mdi-arrow-up-circle-outline"></i>
                                  <span id="total_revenue_last_month_team_2">Rev: 99999$</span>
                              </div>
                              <div class="text-success">
                                  <i class="mdi mdi-arrow-up-circle-outline"></i>
                                  <span id="total_amount_last_month_team_2">FF: 99999$</span>
                              </div>
                          </div>
                          <div class="label text-muted mt-2">Last month</div>
                      </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- List Orders -->
  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">List Orders</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center">ID</th>
                <th class="text-center">User</th>
                <th class="text-center">Number</th>
              </tr>
            </thead>
            <tbody class="order-list">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Production Progress -->
  <div class="col-md-5">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Production Progress</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Printing</label>
          <div class="progress">
            <div class="progress-bar bg-primary" role="progressbar" style="width: 70%">3 printing / 10 new</div>
          </div>
        </div>
        <div>
          <label class="form-label">Packaged</label>
          <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: 40%">4 Packaged / 10 new</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@stop

@section('scripts')
<script>
  $(document).ready(function () {
    $.ajax({
      url: `/dashboard/ajax/AjaxOrderToday?team=1`,
      success: function (response) {
        console.log(response.total_order);
        $("#total_order_today_team_1").text(response.total_order);
        $("#total_amount_today_team_1").text(`FF: ${response.total_base_cost}$`);
        $("#total_revenue_today_team_1").text(`Rev: ${response.total_revenue}$`);
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
    $.ajax({
      url: `/dashboard/ajax/AjaxOrderYesterday?team=1`,
      success: function (response) {
        console.log(response.total_order);
        $("#total_order_yesterday_team_1").text(response.total_order);
        $("#total_amount_yesterday_team_1").text(`FF: ${response.total_base_cost}$`);
        $("#total_revenue_yesterday_team_1").text(`Rev: ${response.total_revenue}$`);
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
    $.ajax({
      url: `/dashboard/ajax/AjaxOrderThisMonth?team=1`,
      success: function (response) {
        console.log(response.total_order);
        $("#total_order_this_month_team_1").text(response.total_order);
        $("#total_amount_this_month_team_1").text(`FF: ${response.total_base_cost}$`);
        $("#total_revenue_this_month_team_1").text(`Rev: ${response.total_revenue}$`);
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
    $.ajax({
      url: `/dashboard/ajax/AjaxOrderLastMonth?team=1`,
      success: function (response) {
        console.log(response.total_order);
        $("#total_order_last_month_team_1").text(response.total_order);
        $("#total_amount_last_month_team_1").text(`FF: ${response.total_base_cost}$`);
        $("#total_revenue_last_month_team_1").text(`Rev: ${response.total_revenue}$`);
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });

    $.ajax({
      url: `/dashboard/ajax/AjaxOrderToday?team=2`,
      success: function (response) {
        console.log(response.total_order);
        $("#total_order_today_team_2").text(response.total_order);
        $("#total_amount_today_team_2").text(`FF: ${response.total_base_cost}$`);
        $("#total_revenue_today_team_2").text(`Rev: ${response.total_revenue}$`);
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
    $.ajax({
      url: `/dashboard/ajax/AjaxOrderYesterday?team=2`,
      success: function (response) {
        console.log(response.total_order);
        $("#total_order_yesterday_team_2").text(response.total_order);
        $("#total_amount_yesterday_team_2").text(`FF: ${response.total_base_cost}$`);
        $("#total_revenue_yesterday_team_2").text(`Rev: ${response.total_revenue}$`);
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
    $.ajax({
      url: `/dashboard/ajax/AjaxOrderThisMonth?team=2`,
      success: function (response) {
        console.log(response.total_order);
        $("#total_order_this_month_team_2").text(response.total_order);
        $("#total_amount_this_month_team_2").text(`FF: ${response.total_base_cost}$`);
        $("#total_revenue_this_month_team_2").text(`Rev: ${response.total_revenue}$`);
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
    $.ajax({
      url: `/dashboard/ajax/AjaxOrderLastMonth?team=2`,
      success: function (response) {
        console.log(response.total_order);
        $("#total_order_last_month_team_2").text(response.total_order);
        $("#total_amount_last_month_team_2").text(`FF: ${response.total_base_cost}$`);
        $("#total_revenue_last_month_team_2").text(`Rev: ${response.total_revenue}$`);
      },
      error: function (error) {
        console.error("Error fetching data:", error);
      },
    });
  });

  

  $.ajax({
    url: '/dashboard/ajax/ajaxListOrders', 
    method: 'GET', 
    dataType: 'html', 
    success: function (response) {
        // Hiển thị dữ liệu vào bảng
        $('.order-list').html(response);
    },
    error: function (xhr, status, error) {
        console.error('Error fetching data:', error);
    }
  });
</script>
@stop