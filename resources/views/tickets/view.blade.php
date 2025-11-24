@extends('layouts.app')

@section('page-title', __('Ticket#' . $support->order_id))
@section('page-heading', __('Ticket#' . $support->order_id))

@section('breadcrumbs')
<li class="breadcrumb-item active">
    <a href="{{ route('tickets.index')}}">@lang('Tickets')</a>
</li>
<li class="breadcrumb-item">
    {{$support->id ?? ""}}
</li>
@stop
<style>
    .custom-select-status {
        appearance: none;
        /* Remove default arrow icon on Firefox */
        -webkit-appearance: none;
        /* Remove default arrow icon on Chrome and Safari */
        -moz-appearance: none;
        /* Remove default arrow icon on older versions of Firefox */
    }
</style>
@section('content')
<div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="shippingMethodModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chat option</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <!-- Maintenance -->
                <input type="hidden" id="chat_option" value="">
                <span type="button" class="btn btn-primary" onclick="deleteChat()">delete</span>
            </div>
        </div>
    </div>
</div>
<!-- Modal timeline -->
<div class="element-box">
    <div class="card">
        <div class="card-body">
            <h4 class="cart-title p-2"> {{$support->subject}} </h4>
            <div class=" border rounded">
                @include('partials.messages')

                <div class="row">
                    <div class="col-3 p-3 border-right">
                        <div class="p-2">
                            Status <br>
                            @php
                                $colorbtn = "btn-success";
                                if ($support->status == 'New') {
                                    $colorbtn = "btn-success";
                                } else if ($support->status == 'Solved') {
                                    $colorbtn = "btn-info";
                                }
                            @endphp
                            @if(Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Supplier' || Auth::user()->role->name == 'Support')
                                <select name="status" id="status" onchange="changestatus('{{$support->id}}')"
                                    class="custom-select-status btn {{$colorbtn}} btn-sm btn-rounded">
                                    <option class="btn btn-success btn-rounded" value="New" data-color="btn-success"
                                        {{$support->status == 'New' ? 'selected' : ''}}>New</option>
                                    <option class="btn btn-info btn-rounded" value="Solved" data-color="btn-info"
                                        {{$support->status == 'Solved' ? 'selected' : ''}}>Solved</option>
                                </select>
                            @else
                                <option class="btn {{$colorbtn}} btn-rounded">{{$support->status}}</option>

                            @endif
                        </div>
                        <div class="p-2">
                            Create at : <br>
                            <div>{{$support->created_at}}</div>
                        </div>
                        <hr>
                        <!-- <div class="p-2">
                            <div class="btn btn-danger">close ticket</div>
                        </div> -->
                    </div>
                    <div class="col-9 row ">
                        <div class="col-12 p-3 chat " style="height: 600px; overflow-y: auto;">
                            @foreach($supportChats as $supportChat)
                                <div class="clearfix w-60 {{ (Auth::user()->id == $supportChat->user_id) ? 'text-right float-right' : 'text-left float-left' }}"
                                    style="clear: both;">
                                    <div class="p-2">
                                        <h6 style="font-weight: bold;">
                                            {{$supportChat->user->username}}({{$supportChat->user->role->name}})
                                        </h6>
                                    </div>
                                    @if (filter_var($supportChat->message, FILTER_VALIDATE_URL))
                                        <div class="d-flex align-items-center position-relative">
                                            <button
                                                style="display: {{ (Auth::user()->id == $supportChat->user_id) ? '' : 'none'}}; display:{{($supportChat->user->role->name=='Support'&&(Auth::user()->id==80||Auth::user()->id==21||Auth::user()->id==1))?"block!important":""}};"
                                                class="btn btn-danger btn-sm m-2" onclick="actionChat('{{$supportChat->id}}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <figure>
                                                <a class="m-5" href="{{$supportChat->message}}" target="_blank">
                                                    <img src="{{$supportChat->message}}" class="img-thumbnail"
                                                        style="background-color: #ababab !important;" alt="Product Image"
                                                        width="250">
                                                </a>
                                            </figure>
                                        </div>

                                    @else
                                        <div class="d-flex align-items-center position-relative">
                                            <button
                                            style="display: {{ (Auth::user()->id == $supportChat->user_id) ? '' : 'none'}}; display:{{($supportChat->user->role->name=='Support'&&(Auth::user()->id==80||Auth::user()->id==21||Auth::user()->id==1))?"block!important":""}};"
                                            class="btn btn-danger btn-sm m-2" onclick="actionChat('{{$supportChat->id}}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <div class="border p-2 rounded">
                                                {{$supportChat->message}}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="text-muted small p-2">{{$supportChat->created_at}}</div>
                                </div>

                            @endforeach
                        </div>
                        <div class="col-12 align-self-end p-3 border-top">
                            <div class="d-flex align-items-center">
                                <textarea rows="5" class="form-control me-2" name="message" id="message"
                                    placeholder="Enter text here..."></textarea>
                                <div class="d-flex align-items-center">
                                    <label for="file"
                                        class="d-flex align-items-center cursor-pointer btn btn-outline-secondary m-2">
                                        <input class="d-none" type="file" id="file" name="file">
                                        <i class="fa fa-upload"></i>
                                    </label>
                                    <button class="btn btn-primary m-2" id="submit" onclick="send()">
                                        <i class="fa fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .chat::-webkit-scrollbar {
        display: none;
    }
</style>
@section('scripts')
<script>
    function actionChat(id) {

        //show chọn xóa hoặc sửa chat message id 
        $('#chatModal').modal('show');
        $('#chat_option').val(id);
    }
    function deleteChat() {
        //delete chat message id
        var chatid = $('#chat_option').val();
        $.ajax({
            url: '/tickets/delete',
            type: 'GET',
            data: {
                id: chatid
            },
            success: function (response) {
                console.log(response);
                // alert('Add support success');
                $('#chatModal').modal('hide');
                location.reload();
            },
            error: function (response) {
                $('#chatModal').modal('hide');
                location.reload();

            }
        });
    }
    function editChat(id) {

    }
    function send() {
        var message = $("#message").val();
        var file_data = $("#file").prop('files')[0]; // Get the file data

        var form_data = new FormData(); // Create a FormData object
        form_data.append('_csrf', "{{csrf_token()}}");
        form_data.append('file', file_data ?? null);
        form_data.append('support_id', "{{$support->id}}");
        form_data.append('user_id', "{{ Auth::user()->id }}");
        form_data.append('message', message ?? null);

        $.ajax({
            url: '/tickets/addChat',
            type: 'POST',
            data: form_data,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                if (JSON.parse(response).message) {
                    // alert('Add support success');
                    location.reload();
                }
            }
        });
    }
    function changestatus(id) {
        var selectedColor = $("#status").find(':selected').data('color');
        var status = $('select[name="status"]').val();

        // if(status!='cancelled'){
        $("#status").removeClass('btn-success btn-info').addClass(selectedColor);
        console.log(status)
        $.post({
            url: "../changeStatus",
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                status: status,
            },
            success: function (response) {
                location.reload();
                // Handle the response here (e.g., display a success message)
                //if (response) {
                //  alert('Change success');
                //}
            },
        });
        // }

    };
</script>
@stop

@endsection