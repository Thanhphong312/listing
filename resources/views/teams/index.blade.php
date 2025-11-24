@extends('layouts.app')

@section('page-title', __('Teams'))
@section('page-heading', __('Teams'))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    @lang('Teams')
</li>
@stop

@section('content')

@include('partials.messages')
<div class="modal fade" id="showstaff">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                <form id="body_showstaff" class="form-horizontal" enctype="multipart/form-data">

                </form>
            </div>

        </div>
    </div>
</div>
<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-12">
                    <div class="float-right">
                        <a href="{{ route('teams.add') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Team')
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="users-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th class="min-width-100">@lang('Name')</th>
                            <th class="min-width-150">@lang('Display Name')</th>
                            <th class="min-width-150">@lang('# of users with this team')</th>
                            <th class="text-center">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($teams))
                            @foreach ($teams as $team)
                                <tr>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ $team->link_page }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success"
                                            onclick="chooseUser({{ $team->id }})">{{ $team->number_menber() }}</button>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('teams.view', $team) }}" class="btn btn-icon"
                                            title="@lang('Edit Role')" data-toggle="tooltip" data-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4"><em>@lang('No records found.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@section('scripts')
    <script>
        function chooseUser(id) {
            $.ajax({
                url: './teams/choose-user/' + id,
                method: 'GET',
                success: function (response) {
                    $("#body_showstaff").html(response);
                    $("#showstaff").modal('show');
                },
                error: function (response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }
        function accepttemp(user_id, template_id){
            console.log(user_id);
            console.log(template_id);
            $.ajax({
                url: './teams/accept-user/'+template_id,
                method: 'GET',
                data:{
                    user_id:user_id,
                },
                success: function (response) {
                    // $("#body_showstaff").html(response);
                    // $("#showstaff").modal('show');
                },
                error: function (response) {
                    // Handle the error, e.g., show an alert
                    // alert(response.responseJSON.message);
                }
            });
        }
    </script>
@endsection
@stop