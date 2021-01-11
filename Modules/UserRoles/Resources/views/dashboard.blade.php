@extends('layouts.home')
@section('title')
    <title> Dashboard | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
<div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto">

                    <h4 class="mb-0">{{-- {{ ucfirst(auth()->user()->name) }} --}} &nbsp;&nbsp; System Dashboard</h4>
                </div>

                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->
    <!-- START: Card Data-->
    <div class="col-12  col-lg-12 mt-3">
        <div class="card">
            <div class="card-header  justify-content-between align-items-center">
                <h6 class="card-title"> Visit Progress </h6>
            </div>
            <div class="card-body table-responsive p-0">

                <table class="table font-w-600 mb-0">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Not Initiated</th>
                            <th>Initiated</th>
                            <th>Editing</th>
                            <th>Complete</th>
                            <th>Not Required</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Qc</td>
                            <td><span class="badge badge-pill badge-dark p-2 mb-1">1</span></td>
                            <td><span class="badge badge-pill badge-light p-2 mb-1">2</span></td>
                            <td><span class="badge badge-pill badge-warning p-2 mb-1">3</span></td>
                            <td><span class="badge badge-pill badge-success p-2 mb-1">4</span></td>
                            <td><span class="badge badge-pill badge-danger p-2 mb-1">5</span></td>
                        </tr>
                        <tr>
                            <td>Eligibility</td>
                            <td><span class="badge badge-pill badge-dark p-2 mb-1">1</span></td>
                            <td><span class="badge badge-pill badge-light p-2 mb-1">2</span></td>
                            <td><span class="badge badge-pill badge-warning p-2 mb-1">3</span></td>
                            <td><span class="badge badge-pill badge-success p-2 mb-1">4</span></td>
                            <td><span class="badge badge-pill badge-danger p-2 mb-1">5</span></td>
                        </tr>
                        <tr>
                            <td>Grader 1</td>
                            <td><span class="badge badge-pill badge-dark p-2 mb-1">1</span></td>
                            <td><span class="badge badge-pill badge-light p-2 mb-1">2</span></td>
                            <td><span class="badge badge-pill badge-warning p-2 mb-1">3</span></td>
                            <td><span class="badge badge-pill badge-success p-2 mb-1">4</span></td>
                            <td><span class="badge badge-pill badge-danger p-2 mb-1">5</span></td>
                        </tr>
                        <tr>
                            <td>Grader 2</td>
                            <td><span class="badge badge-pill badge-dark p-2 mb-1">1</span></td>
                            <td><span class="badge badge-pill badge-light p-2 mb-1">2</span></td>
                            <td><span class="badge badge-pill badge-warning p-2 mb-1">3</span></td>
                            <td><span class="badge badge-pill badge-success p-2 mb-1">4</span></td>
                            <td><span class="badge badge-pill badge-danger p-2 mb-1">5</span></td>
                        </tr>
                        <tr>
                            <td>Adjudication</td>
                            <td><span class="badge badge-pill badge-dark p-2 mb-1">1</span></td>
                            <td><span class="badge badge-pill badge-light p-2 mb-1">2</span></td>
                            <td><span class="badge badge-pill badge-warning p-2 mb-1">3</span></td>
                            <td><span class="badge badge-pill badge-success p-2 mb-1">4</span></td>
                            <td><span class="badge badge-pill badge-danger p-2 mb-1">5</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    <!-- END: Card DATA-->
</div>
@stop
@section('styles')
<style type="text/css">
    .badge{
        line-height: 0.4 !important;
    }
</style>
<link rel="stylesheet"  href="{{ asset('public/dist/vendors/chartjs/Chart.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/morris/morris.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/weather-icons/css/pe-icon-set-weather.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/chartjs/Chart.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/starrr/starrr.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/ionicons/css/ionicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.css') }}">
@stop
@section('script')
<script src="{{ asset('public/dist/vendors/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/morris/morris.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/starrr/starrr.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.canvaswrapper.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.colorhelpers.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.saturated.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.browser.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.drawSeries.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.uiConstants.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.legend.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('public/dist/vendors/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-jvectormap/jquery-jvectormap-world-mill.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-jvectormap/jquery-jvectormap-de-merc.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-jvectormap/jquery-jvectormap-us-aea.js') }}"></script>
<script src="{{ asset('public/dist/vendors/apexcharts/apexcharts.js') }}"></script>
<script  src="{{ asset('public/dist/vendors/lineprogressbar/jquery.lineProgressbar.js') }}"></script>
<script  src="{{ asset('public/dist/vendors/lineprogressbar/jquery.barfiller.js') }}"></script>

<script src="{{ asset('public/dist/js/home.script.js') }}"></script>
@stop
