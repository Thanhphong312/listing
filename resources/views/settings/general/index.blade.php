@extends('layouts.app')

@section('page-title', __('General Settings'))
@section('page-heading', __('General Settings'))

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">
        @lang('Settings')
    </li>
    <li class="breadcrumb-item active">
        @lang('General')
    </li>
@stop

@section('content')
    <div class="element-box">
        <div class="card">
            <div class="card-body">

                @include('partials.messages')
                <div class="modal fade" id="addSetting">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Add General Setting</h4>
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
                <div class="modal fade" id="editSetting">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Edit General Setting</h4>
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
                <div class="m-1">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" onclick="add_setting()">
                                Add General Setting
                            </button>
                        </div>
                    </div>
                </div>
                <div class="pt-12">
                    <table class="table table-striped table-borderless" id="data-table-default">
                        <thead>
                            <tr>
                                <th style="">
                                    ID
                                </th>
                                <th style="min-width: 240px">
                                    Key
                                </th>
                                <th style="min-width: 150px">
                                    Value
                                </th>
                                <th style="">
                                    Fteeck Token
                                </th>
                                <th style="min-width: 100px">action</th>
                            </tr>
                        </thead>
                        <tbody class="order-list">
                            @foreach ($settings as $setting)
                                <tr>
                                    <td>
                                        {{ $setting->id }}
                                    </td>
                                    <td>
                                        {{ $setting->key }}
                                    </td>
                                    <td>
                                        {{ $setting->value }}
                                    </td>
                                    <td>
                                        {{ $setting->fteeck_token }}
                                    </td>
                                    <td>
                                        <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black"
                                            onclick="edit_setting('{{ $setting->id }}')">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </td>
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
        function edit_setting(id) {
            $.get('./settings/general/edit/' + id, function(data) {
                $("#body_edit").html(data);
            });
            $('#editSetting').modal('show');
        }

        function add_setting() {
            $.get('./settings/general/add', function(data) {
                $("#body_add").html(data);
            });
            $('#addSetting').modal('show');
        }

        function edit(id) {
            var key = $('input[name="key_edit"]').val();
            var value = $('input[name="value_edit"]').val();
            var fteeck_token = $('input[name="fteeck_token_edit"]').val();
            $.ajax({
                url: `./settings/general/edit/${id}`,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    key: key,
                    value: value,
                    fteeck_token: fteeck_token,
                },
                success: function(response) {
                    if (JSON.parse(response).message) {
                        location.reload();
                    }
                },
            });
        }

        function add() {
            var key = $('input[name="key_add"]').val();
            var value = $('input[name="value_add"]').val();
            var fteeck_token = $('input[name="fteeck_token_add"]').val();
            $.ajax({
                url: `./settings/general/add`,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    key: key,
                    value: value,
                    fteeck_token: fteeck_token,
                },
                success: function(response) {
                    console.log(response);
                    if (JSON.parse(response).message) {
                        location.reload();
                    }
                },
                error: function(response) {
                    console.log(response.responseJSON.message);
                    alert(response.responseJSON.message);
                }
            });
        }
    </script>
@stop

@endsection
