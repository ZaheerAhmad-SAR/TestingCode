<!DOCTYPE html>
<html lang="en">
<!-- START: Head-->

<head>
    <meta charset="UTF-8">
    <title>OCAP</title>
    <link rel="shortcut icon" href="{{ url('dist/images/favicon.ico') }}" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')
    <!-- START: Template CSS-->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/jquery-ui/jquery-ui.theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <!-- END Template CSS-->
    <!-- START: Page CSS-->
    @yield('styles')
    <!-- END: Page CSS-->
    <!-- START: Custom CSS-->

    <link rel="stylesheet" href="{{ asset('public/dist/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('public/dist/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/fontawesome/css/all.min.css') }}" />

    @stack('styles')
    <!-- END: Custom CSS-->
</head>

<body class="@yield('class')">
    @yield('body')
    <script src="{{ asset('public/dist/vendors/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/slimscroll/jquery.slimscroll.min.js') }}"></script>

    @yield('script')
    @stack('script')
    @stack('script_mid')
    @stack('script_last')
    @stack('script_skip_logic')
</body>

</html>
