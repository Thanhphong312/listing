@extends('layouts.app')

@section('page-title', __('Partner apps'))
@section('page-heading', __('Partner apps'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Partner apps')
    </li>
@stop

@section('content')
    <div class="element-box">
        <div class="card">
            <div class="card-body">

                @include('partials.messages')
                <div class="modal fade" id="editIdea">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Edit PartnerApp</h4>
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
                <div class="modal fade" id="addIdea">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Add PartnerApp</h4>
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
                <div class="m-1">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" onclick="add_store()">  <i class="fas fa-plus"></i> Add PartnerApp</button>
                        </div>
                    </div>
                </div>
                <div class="pt-3">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless" id="data-table-default">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>KEY</th>
                                <th>SECRET</th>
                                <th>PROXY</th>
                                <th>PROXY STATUS</th>
                                <th>USER</th>
                                <th>STAFF</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="partnerapp-list">
                            @foreach ($partnerapps as $partnerapp)
                                <tr>
                                    <td>
                                        {{ $partnerapp->id }}
                                    </td>
                                    <td>
                                        <div>{{ $partnerapp->app_name }}</div>
                                        <div class="btn btn-sm btn-primary m-1"
                                            onclick="copyToClipboardAuth(this, '{{ $partnerapp->auth_link }}')">link auth
                                        </div>
                                        <div class="btn btn-sm btn-primary m-1"
                                            onclick="copyToClipboardWebhook(this, '{{ url('/api/tiktok-webhook/') }}/{{ $partnerapp->app_secret }}')">
                                            link webhook</div>
                                    </td>
                                    <td>
                                        {{ $partnerapp->app_key }}
                                    </td>
                                    <td>
                                        {{ $partnerapp->app_secret }}
                                    </td>
                                    <td>
                                        {{ $partnerapp->proxy }}
                                    </td>
                                    <td data-id="{{ $partnerapp->id }}" id="partnerapp_{{ $partnerapp->id }}">
                                        {{-- ajax to get proxy status --}}
                                    </td>
                                    <td>
                                        {{ getUsernameById($partnerapp->seller_id) }}
                                    </td>
                                    <td>
                                        {{ getUsernameById($partnerapp->staff_id) }}
                                    </td>
                                    <td>
                                        <div class="btn {{ $partnerapp->status ? 'btn-success' : 'btn-danger' }}">
                                            {{ $partnerapp->status ? 'active' : 'inactive' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div onclick="edit_partner('{{ $partnerapp->id }}')">
                                            <button class="btn btn-success-jade btn-rounded btn-sm" style="color: black">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                </div>
                <div class="pagination-links">
                    @if (count($partnerapps))
                        {{ $partnerapps->appends($_GET)->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .boxaddColor {
            width: 300px;
        }

        .boxaddSize {
            width: 300px;
        }

        .chip {
            display: inline-block;
            font-weight: bold;
            padding: 0.375rem 0.75rem;
            line-height: 0.5;
            border-radius: 0.55rem;
            background-color: #9ca2a9;
        }

        .color-chip {
            display: inline-block !important;
            width: 20px !important;
            height: 20px !important;
            border-radius: 50% !important;
            border: 0.2px solid #9ca2a9 !important;
            text-align: center;
            line-height: 0.5 !important;
            vertical-align: middle;
        }

        .btn-add-chip {
            display: inline-block !important;
            width: 20px !important;
            height: 20px !important;
            border-radius: 50% !important;
            border: 2.2px solid #9ca2a9 !important;
            text-align: center;
            line-height: 1.1 !important;
            padding-left: 1px;
            vertical-align: middle;
            padding-top: 0 !important;
            padding-right: 0 !important;
            padding-bottom: 0 !important;

        }

        .log-add {
            display: flex;
            justify-content: center;
        }
    </style>
@section('scripts')
    <script>
        function edit_partner(id) {
            $.get('./partner-apps/edit/' + id, function(data) {
                $("#body_edit").html(data);
            });
            $('#editIdea').modal('show');
        }

        function add_store() {
            $.get('./partner-apps/add', function(data) {
                $("#body_add").html(data);
            });
            $('#addIdea').modal('show');
        }

        function edit(id) {
            var name_edit = $('input[name="name_edit"]').val();
            var key_edit = $('input[name="key_edit"]').val();
            var secret_edit = $('input[name="secret_edit"]').val();
            var proxy_edit = $('input[name="proxy_edit"]').val();
            var auth_link_edit = $('input[name="auth_link_edit"]').val();
            var seller_edit = $('select[name="seller_edit"]').val();
            var staff_edit = $('select[name="staff_edit"]').val();
            var formData = new FormData();
            formData.append('name_edit', name_edit)
            formData.append('key_edit', key_edit)
            formData.append('secret_edit', secret_edit)
            formData.append('proxy_edit', proxy_edit)
            formData.append('auth_link_edit', auth_link_edit)
            formData.append('seller_edit', seller_edit)
            formData.append('staff_edit', staff_edit)

            $.ajax({
                url: `./partner-apps/edit/${id}`,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (JSON.parse(response).message) {
                        location.reload();
                    }
                },
            });
        }

        function add() {
            var name_add = $('input[name="name_add"]').val();
            var key_add = $('input[name="key_add"]').val();
            var secret_add = $('input[name="secret_add"]').val();
            var auth_link_add = $('input[name="auth_link_add"]').val();
            var proxy_add = $('input[name="proxy_add"]').val();
            var seller_add = $('select[name="seller_add"]').val();
            var staff_add = $('select[name="staff_add"]').val();
            var formData = new FormData();
            formData.append('name_add', name_add)
            formData.append('key_add', key_add)
            formData.append('secret_add', secret_add)
            formData.append('auth_link_add', auth_link_add)
            formData.append('proxy_add', proxy_add)
            formData.append('seller_add', seller_add)
            formData.append('staff_add', staff_add)

            $.ajax({
                url: './partner-apps/add',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Handle the response, e.g., reload the page
                    if (JSON.parse(response).message) {
                        location.reload();
                    }
                },
                error: function(response) {
                    // Handle the error, e.g., show an alert
                    alert(response.responseJSON.message);
                }
            });
        }

        function handleFileChange(event, design_id) {
            const file = event.target.files[0];
            if (file) {
                console.log('Selected file:', file.name);
                // Bạn có thể thực hiện các hành động khác với file tại đây
                var formData = new FormData();

                formData.append('_token', "{{ csrf_token() }}");
                formData.append('design_id', design_id);
                formData.append('file', file);

                $.ajax({
                    url: '',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle the response, e.g., reload the page
                        if (JSON.parse(response).message) {
                            $("#showchat").attr("src", JSON.parse(response).data);
                        }
                    },
                    error: function(response) {
                        // Handle the error, e.g., show an alert
                        alert(response.responseJSON.message);
                    }
                });
            }
        }

        function copyToClipboardAuth(element, text) {
            var tempInput = document.createElement('input');
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            var originalText = element.innerHTML;
            element.innerHTML = 'Copied!';

            setTimeout(function() {
                element.innerHTML = originalText;
            }, 1000);
        }

        function copyToClipboardWebhook(element, text) {
            var tempInput = document.createElement('input');
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            var originalText = element.innerHTML;
            element.innerHTML = 'Copied!';

            setTimeout(function() {
                element.innerHTML = originalText;
            }, 1000);
        }

        $('.partnerapp-list tr').each(function() {
            const $row = $(this);
            const id = $row.find('td:nth-child(6)').data('id');
            console.log(id);
            if (id) {
                $.ajax({
                    url: './partner-apps/check-proxy/' + id,
                    type: 'get',
                    data: {},
                    success: function(response) {
                        if (response.status === 'success') {
                            // Cập nhật trạng thái proxy thành công
                            $(`#partnerapp_${id}`).html(
                                '<button class="btn btn-success btn-sm">live</button>'
                            );
                        } else {
                            // Cập nhật trạng thái proxy thất bại
                            $(`#partnerapp_${id}`).html(
                                '<button class="btn btn-danger btn-sm">die</button>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        $(`#partnerapp_${id}`).html(
                            '<button class="btn btn-danger btn-sm">die</button>'
                        );
                    }
                });
            } else {

            }
        });
    </script>
@stop

@endsection
