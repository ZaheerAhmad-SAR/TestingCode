<!DOCTYPE html>
<html lang="en">
    <!-- START: Head-->
    <head>
        <meta charset="UTF-8">
        <title>404 Page</title>
        <link rel="shortcut icon" href="dist/images/favicon.ico" />
        <meta name="viewport" content="width=device-width,initial-scale=1"> 

        <!-- START: Template CSS-->
        <link rel="stylesheet" href="{{ asset('public/dist/vendors/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/dist/vendors/jquery-ui/jquery-ui.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/dist/vendors/jquery-ui/jquery-ui.theme.min.css') }}">
       
        <!-- END Template CSS--> 

        <!-- START: Page CSS-->
        @yield('styles')
        <!-- END: Page CSS-->

        <!-- START: Custom CSS-->
        <link rel="stylesheet" href="{{ asset('public/dist/css/main.css') }}">
        <!-- END: Custom CSS-->
    </head>
    <!-- END Head-->
    <body class="@yield('class')">
        <!-- START: Pre Loader-->
        <div class="se-pre-con">
            <div class="loader"></div>
        </div>
        <!-- END: Pre Loader-->

        <div class="container">
            <div class="row vh-100 justify-content-between align-items-center">
                <div class="col-12">
                    <div  class="lockscreen  mt-5 mb-5">
                        <div class="jumbotron mb-0 text-center theme-background rounded">
                            <h1 class="display-3 font-weight-bold"> 404</h1>
                            <h5><i class="ion ion-alert pr-2"></i>Oops! Something went wrong</h5>
                            <p>The page you are looking for is not found, please try after some time or go back to home</p>
                            <a href="{{route('dashboard.index')}}" class="btn btn-primary">Go To Home</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

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
        <!-- END: Template JS-->
        <!-- START: APP JS-->
        <script src="{{ asset('public/dist/js/app.js') }}"></script>
        <!-- END: APP JS-->

        <!-- START: Page JS-->
        @yield('script')
        <!-- END: Page JS-->
    </body>
</html>