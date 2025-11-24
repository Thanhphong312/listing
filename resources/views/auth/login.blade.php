@extends('layouts.auth')

@section('page-title', trans('Login'))

@section('content')

    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary-subtle">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h4 class="text-primary">@lang('Welcome global24 !')</h4>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ url('assets/img/profile-img.png') }}" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>

                        <div class="card-body pt-0">
                            <div class="auth-logo">
                                <a href="" class="auth-logo-light">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ url('assets/img/logo.jpg') }}" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>

                                <a href="" class="auth-logo-dark">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ url('assets/img/logo.jpg') }}" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <div class="p-2">
                                @include('partials.messages')

                                <form class="form-horizontal" role="form" action="<?= url('login') ?>" method="POST"
                                    id="login-form" autocomplete="off">

                                    <input type="hidden" value="<?= csrf_token() ?>" name="_token">

                                    @if (Request::has('to'))
                                        <input type="hidden" value="{{ Request::get('to') }}" name="to">
                                    @endif

                                    <div class="mb-3">
                                        <label for="username" class="form-label">@lang('Username')</label>
                                        <input type="text" name="username" id="username" class="form-control"
                                            placeholder="@lang('Enter username')" value="{{ old('username') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">@lang('Password')</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" name="password" id="password" class="form-control"
                                                placeholder="@lang('Enter password')" aria-label="Password"
                                                aria-describedby="password-addon">
                                            <button class="btn btn-light " type="button" id="password-addon"><i
                                                    class="mdi mdi-eye-outline"></i></button>
                                        </div>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="remember" id="remember"
                                            value="1">
                                        <label class="form-check-label" for="remember">
                                            @lang('Remember me')
                                        </label>
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light"
                                            id="btn-login">@lang('Log In')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    {!! HTML::script('assets/js/as/login.js') !!}
    {!! JsValidator::formRequest('Vanguard\Http\Requests\Auth\LoginRequest', '#login-form') !!}
@stop
