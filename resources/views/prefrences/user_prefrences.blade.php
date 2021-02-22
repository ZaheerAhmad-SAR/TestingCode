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
                    <h4 class="mb-0" style="text-decoration: underline;">
                    {{\Auth()->user()->name}} Prefrences
                    </h4>
                </div>
                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Prefrences</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->
    <!-- START: Card Data-->
    <div class="row">
        <div class="col-12 col-lg-12  mt-3">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <h4 class="card-title">Preferences list </h4>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body py-5">
                        <div class="row">
                            <div class="col-12">
                                <form action="{{route('home.UpdateUserPrefrences')}}" method="post">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail4">Default Theme</label><br><br>
                                            <input type="radio" name="default_theme" id="inputEmail4" value="light" @if($user_preferences->default_theme =='light') checked @endif> Light &nbsp;&nbsp;&nbsp;&nbsp; 
                                            <input type="radio" name="default_theme" id="inputEmail4" value="semi-dark" @if($user_preferences->default_theme =='semi-dark') checked @endif> Semi-Dark &nbsp;&nbsp;&nbsp;&nbsp; 
                                            <input type="radio" name="default_theme" id="inputEmail4" value="dark" @if($user_preferences->default_theme =='dark') checked @endif> Dark &nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="page">Default Pagination</label>
                                            <select class="form-control" name="default_pagination" id="default_pagination">
                                                <option value="10" @if($user_preferences->default_pagination =='10') selected @endif>10</option>
                                                <option value="20" @if($user_preferences->default_pagination =='20') selected @endif>20</option>
                                                <option value="50" @if($user_preferences->default_pagination =='50') selected @endif>50</option>
                                                <option value="100" @if($user_preferences->default_pagination =='100') selected @endif>100</option>
                                                <option value="200" @if($user_preferences->default_pagination =='200') selected @endif>200</option>
                                                <option value="500" @if($user_preferences->default_pagination =='500') selected @endif>500</option>
                                                <option value="1000" @if($user_preferences->default_pagination =='100') selected @endif>1000</option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="form-row" style="float: right;">
                                        <button type="submit" class="btn btn-outline-primary"> <i class="fa fa-save"></i> Update Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        
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
.detail-icon{
cursor: pointer;
}
td {
text-align: center;
}
th {
text-align: center;
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
<script type="text/javascript">
$('.detail-icon').click(function(e){
$(this).toggleClass("fa-chevron-circle-right fa-chevron-circle-down");
});
</script>
<script src="{{ asset('public/dist/vendors/chartjs/Chart.min.js') }}"></script>
@stop