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
        <link rel="stylesheet" href="{{ asset('public/css/jquery-confirm.min.css') }}"/>

        @stack('styles')
        <!-- END: Custom CSS-->
        <style>
            #settingbutton{
                display: none;
            }
            .fstselected{
                font-size: 12px !important;
            }
            .modal-lg-custom {
                min-width: auto;
                max-width: fit-content;
            }
            /*For loader*/
            .loader {
                  border: 8px solid #f3f3f3;
                  border-radius: 50%;
                  border-top: 8px solid #4C75F2;
                  border-right: 8px solid #A8A8A8;
                  border-bottom: 8px solid #A8A8A8;
                  border-left: 8px solid #A8A8A8;
                  width: 120px;
                  height: 120px;
                  -webkit-animation: spin 2s linear infinite;
                  animation: spin 2s linear infinite;
                  position: fixed;
                }

                @-webkit-keyframes spin {
                  0% { -webkit-transform: rotate(0deg); }
                  100% { -webkit-transform: rotate(360deg); }
                }

                @keyframes spin {
                  0% { transform: rotate(0deg); }
                  100% { transform: rotate(360deg); }
                }
            /*For loader*/
            .dropdown-item{
                cursor: pointer;
            }
            .float-mrg{
                float: right;
                margin-top: 4px;
                cursor: pointer;
            }
        </style>
    </head>

<body class="@yield('class')">
    <!-- START: Pre Loader-->
        <div class="se-pre-con">
            <div class="loader"></div>
        </div>
    <!-- END: Pre Loader-->
    @yield('body')

    @stack('popup_modals')
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
        <script src="{{ asset('public/js/jquery-confirm.min.js') }}"></script>
        <script src="{{ asset('public/js/multiselect.js') }}"></script>

        <!-- END: APP JS-->

        <!-- START: Page JS-->
        @yield('script')
        @stack('script')
        @stack('script_mid')
        @stack('script_last')
        @stack('script_skip_logic')
        <script>
            $(document).ready(function(){
                $('[data-toggle="popover"]').popover();
            });
            $(function () {
                var duration = 10000;
                setTimeout(function () { $('#myalert').hide(); }, duration);
            });
            $('body').on('click','.reset-filter',function(){
                ('.filter-form').find('input[type=text], input[type=date],select').val('');
            });
        </script>
        <!-- END: Page JS-->
    </body>
</html>
<div class="loader" style="display: none;"></div>
