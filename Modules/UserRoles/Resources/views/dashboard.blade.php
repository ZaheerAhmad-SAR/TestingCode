@extends('layouts.home')
@section('title')
    <title> Dashboard | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
<div class="container-fluid site-width">
     @php 
       $where_study = ''; 
       $users = App\User::all();
       $studyid = Request('studyId', '-');
       if($studyid =='-'){
        $where_study = array('deleted_at' => null);
       }else{
        $where_study = array('study_id' => $studyid);
       }
     @endphp
    <div id="settings" class="">
        <a href="#" id="settingbutton" class="setting"> 
            <h5 class="mb-0"><i class="icon-settings"></i></h5>
        </a>
        <div class="sidbarchat p-3" style="overflow: auto;">
            <h5 class="mb-0">Active Users</h5>
            <hr/>
            @foreach($users as $user)
                @php
                    $time = new DateTime($user->online_at);
                    $time = $time->format('H:i');
                @endphp
                <div class="media" style="display: block;">
                    <img src="{{(asset('public/images/download.png'))}}" style="width: 30px; height: 30px; border-radius: 50%;">
                    @if($user->working_status =='offline')
                    <i class="fas fa-circle" style="position: absolute;left: 36px; color: red;"></i>
                    @else
                    <i class="fas fa-circle" style="position: absolute;left: 36px; color: green;"></i>
                    @endif
                    <span data-toggle="tooltip" data-placement="bottom" title="{{$user->name}}"> {{substr($user->name, 0, 15)}}... </span>
                    <span style="font-size:9px; float: right;margin-top: 25px;">
                    @if($user->working_status =='offline')
                        {{Carbon\Carbon::parse($user->offline_at)->diffForHumans()}}
                    @else
                        {{Carbon\Carbon::parse($user->online_at)->diffForHumans()}} 
                    @endif
                    </span>
                </div>
                
                <hr/>
            @endforeach
        </div>
    </div>
    <!-- START: Breadcrumbs-->
    <div class="row">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto">
                    <h4 class="mb-0">System Dashboard</h4>
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
        @include('userroles::main_statistics_dashboard')
    {{-- graph --}}
        @include('userroles::line_graph_dashboard')
    {{-- graph --}}
        @include('userroles::visits_progress_dashboard')
    {{--  --}}
        @include('userroles::assigned_statistics_dashboard')
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
<script type="text/javascript">
    $(document).ready(function(){
        $('.filter-by-study').on('change',function(){
            let studyId = $(this).val();
            var url = '';
            if (studyId === '') {
                var url = "{{ url('/') }}/dashboard/-";
            }else{
                var url = "{{ url('/') }}/dashboard/" + studyId;
            }
            let title = 'new title';
            if (typeof(history.pushState) != "undefined") {
                let obj = {
                    Title: title,
                    Url: url
                };
                history.pushState(obj, obj.Title, obj.Url);
            } else {
                alert("Browser does not support HTML5.");
            }
            reloadPage(0);
        })
    })
    function reloadPage(waitSeconds) {
        var seconds = waitSeconds * 1000;
        //console.log('wait : ' + seconds);
        setTimeout(function() {
            location.reload();
        }, seconds);
    }
</script>
@stop
