<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="template language" name="keywords">
    <meta content="Tamerlan Soziev" name="author">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ url('assets/img/windycustomlogo_blue.png') }}" />
    <title>@yield('page-title') - {{ setting('app_name') }}</title>

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- App js -->
    {!! HTML::script('assets/js/plugin.js') !!}

    @yield('header-scripts')

    @hook('auth:styles')
</head>

<body class="auth">

    @yield('content')

    <!-- JAVASCRIPT -->
    {!! HTML::script('assets/js/libs/jquery/jquery.min.js') !!}
    {!! HTML::script('assets/js/libs/bootstrap/js/bootstrap.bundle.min.js') !!}
    {!! HTML::script('assets/js/libs/metismenu/metisMenu.min.js') !!}
    {!! HTML::script('assets/js/libs/simplebar/simplebar.min.js') !!}
    {!! HTML::script('assets/js/libs/node-waves/waves.min.js') !!}

    <!-- App js -->
    {!! HTML::script('assets/js/app.js') !!}

    {!! HTML::script('assets/js/vendor.js') !!}

    @yield('scripts')
    @hook('auth:scripts')
</body>

</html>
