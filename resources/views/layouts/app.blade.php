<!DOCTYPE html>
<html lang="en">
    <!-- START: Head-->
    <head>
        <meta charset="UTF-8">
        <title>OCAP</title>
    <link rel="shortcut icon" href="{{ url('dist/images/favicon.ico')}}" />
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @yield('title')
        <!-- START: Template CSS-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.css" integrity="sha512-2sFkW9HTkUJVIu0jTS8AUEsTk8gFAFrPmtAxyzIhbeXHRH8NXhBFnLAMLQpuhHF/dL5+sYoNHWYYX2Hlk+BVHQ==" crossorigin="anonymous" />
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
        <link rel="stylesheet" href="{{ asset('public/dist/css/multi-select.css') }}">
        <link rel="stylesheet" href="{{ asset('public/dist/vendors/fontawesome/css/all.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('public/css/fstdropdown.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('public/css/sweetalert.css') }}"/>

        @stack('styles')
        <!-- END: Custom CSS-->
        <style>
            #settingbutton{
                display: none;
            }
            .fstselected{
                font-size: 12px !important;
            }
        </style>
    </head>
   <!--  <div  id="wait" style="display:none;width:69px;height:89px;position:absolute;top:40%;left:50%;z-index: 9999;"><img src="{{url('old-style/images/loader.gif')}}" width="64" height="64" /><br></div> -->
<body class="@yield('class')">
    <!-- START: Pre Loader-->
        <div class="se-pre-con">
            <div class="loader"></div>
        </div>
    <!-- END: Pre Loader-->
    @yield('body')

     <!-- START: Back to top-->
        <a href="#" class="scrollup text-center">
            <i class="icon-arrow-up"></i>
        </a>
    <!-- END: Back to top-->

    <!-- START: Template JS-->

    <script src="{{ asset('public/dist/vendors/jquery/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('public/dist/vendors/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('public/dist/vendors/moment/moment.js') }}"></script>
        <script src="{{ asset('public/dist/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('public/dist/vendors/slimscroll/jquery.slimscroll.min.js') }}"></script>

        <!-- END: Template JS-->
        <!-- START: APP JS-->
        <script src="{{ asset('public/dist/js/app.js') }}"></script>
        <script src="{{ asset('public/dist/js/jquery.multi-select.js') }}"></script>
        <script src="{{ asset('public/js/fstdropdown.min.js') }}"></script>
        <script src="{{ asset('public/js/sweetalert.min.js') }}"></script>
        <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>

        <!-- END: APP JS-->

        <!-- START: Page JS-->
        @yield('script')
        @stack('script')
        @stack('script_mid')
        @stack('script_last')
        <script>
            $(document).ready(function(){
                $('[data-toggle="popover"]').popover();
            });
            $(function () {
                var duration = 10000;
                setTimeout(function () { $('#myalert').hide(); }, duration);
            });
        </script>
        <!-- END: Page JS-->
    </body>
</html>
