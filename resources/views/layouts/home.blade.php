@extends('layouts.app')
@section('body')
    <!-- START: Header-->
    @if (!empty(auth()->user()))
    <div id="header-fix" class="header fixed-top">
        <div class="site-width">

            <nav class="navbar navbar-expand-lg  p-0">

                <div class="navbar-header  h-100 h4 mb-0 align-self-center logo-bar text-left">
                    <a href="{{ url('/dashboard') }}" class="horizontal-logo text-left">
                <span class="h4 font-weight-bold align-self-center mb-0 ml-auto">
                    OIRRC
                </span>
                    </a>
                </div>
                <div class="navbar-header h4 mb-0 text-center h-100 collapse-menu-bar">

                    <a href="#" class="sidebarCollapse" id="collapse"><i class="icon-menu"></i></a>
                </div>
                @if(empty(auth()->user()->google2fa_secret))
                    <div class="" style="margin-top: 15px;padding: 9px 62px 14px 0px;" >
                        @php
                        $style = (empty(auth()->user()->google2fa_secret))? 'style="display:none;"':'style="margin-top:20px"';
                        @endphp
                        <div class="alert alert-warning alert-dismissible" {{ $style }} >
                            <a type="submit" class="btn btn-outline-info" href="{{route('users.updateProfile')}}" >Enable now</a>
                            <strong>Warning!</strong> Google 2-Factor Auth is disabled, turn it on.
                            <button class="close" data-dismiss="alert">&times;</button>
                        </div>
                    </div>
            @endif
                <!-- title here  -->
                <!--  -->
                <div class="navbar-right ml-auto h-100">
                    <ul class="ml-auto p-0 m-0 list-unstyled d-flex top-icon h-100">
                        <li style="margin-top: 5px;">
                        <span>
                            @if(!empty(session('current_study')))
                            <strong>Current Study:</strong> {{session('study_short_name')}}
                                @endif
                        </span>
                        </li>
                        @if(session('current_study'))
                        <li class="dropdown align-self-center d-inline-block">
                            <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false">
                                <i class="icon-bell h4"></i>
                                @php $count =  \Modules\Queries\Entities\AppNotification::where('user_id','=', auth()->user()->id)->where('is_read','no')->count(); @endphp

                                @if($count > 0)
                                    <span class="badge badge-pill badge-danger" style="height: 20px;top: 12px;"> {{$count}}</span>
                                @endif

                            </a>
                            <ul class="dropdown-menu dropdown-menu-right border   py-0">

                                @php
                                    $userQueries =  \Modules\Queries\Entities\AppNotification::where('user_id','=', auth()->user()->id)->where('is_read','no')->get();
                                @endphp

                                @if(!empty($userQueries))
                                @foreach($userQueries as $str)
                                    @php

                                    $userData ='';

                                    $result = '';
                                    $result      = \Modules\Queries\Entities\Query::where('id','=',$str->query_id)->where('query_status','open')->first();
                                    $userData  = App\User::where('id',$result->queried_remarked_by_id)->first();

                                    @endphp
                                <li>

                                    @php
                                        $studyName = Modules\Admin\Entities\Study::where('id',$result->study_id)->first();
                                         //session([ 'current_study' => $studyName->study_short_name]);

                                    @endphp
                                    <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0 currentNotificationId" data-id="{{$result->study_id}}" href="{{$result->query_url .'?studyid='.$result->study_id }}" target="_blank" data-value="{{$result->id}}">
                                        <div class="media">
                                            <img src="{{asset('dist/images/author.jpg')}}" alt="" class="d-flex mr-3 img-fluid rounded-circle">
                                            <div class="media-body">

                                                <p class="mb-0 text-primary "> Study:  <b> {{ $studyName->study_short_name}}</b> have new query by <b>{{$userData->name}}</b> </p>
                                                {{ date_format($result->created_at,'d-M-Y')}}
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                                @endif
                                @if($count > 1)
                                <li><a class="dropdown-item text-center py-2" href="{{route('queries.show')}}"> Read All Message <i class="icon-arrow-right pl-2 small"></i></a></li>
                                @endif

                            </ul>
                        </li>
                        @endif



                        <li class="d-inline-block align-self-center  d-block d-lg-none">
                            <a href="#" class="nav-link mobilesearch" data-toggle="dropdown" aria-expanded="false"><i class="icon-magnifier h4"></i>
                            </a>
                        </li>
                        <li class="dropdown user-profile align-self-center d-inline-block">
                            <a href="#" class="nav-link py-0" data-toggle="dropdown" aria-expanded="false">
                                <div class="media">
                                    @if(!empty(auth()->user()->image))
                                       <img src="{{ asset(auth()->user()->image) }}" style="width: 40px; height: 40px; border-radius: 50%;">
                                    @else
                                       <img src="{{(asset('public/images/download.png'))}}" style="width: 40px; height: 40px; border-radius: 50%;">
                                        @endif
                                </div>
                            </a>
                            <div class="dropdown-menu border dropdown-menu-right p-0">
                                <a href="#" class="dropdown-item px-2 align-self-center d-flex"><span><strong>Logged User: {!! auth()->user()->name !!}</strong></span></a>
                                <a href="{{route('users.updateProfile')}}" class="dropdown-item px-2 align-self-center d-flex">
                                    <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item px-2 text-danger align-self-center d-flex">
                                    <span class="icon-logout mr-2 h6  mb-0"></span> Sign Out</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <script src="{{ asset('public/dist/vendors/jquery/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript">


        $('.currentNotificationId').click(function () {
            var currentNotificationId  = $(this).attr('data-value');
            var studyIDOfCurrentNotification = $(this).attr('data-id');

            updateNotificationToRead(currentNotificationId,studyIDOfCurrentNotification);
        });

        function updateNotificationToRead(currentNotificationId,studyIDOfCurrentNotification)
        {
            $.ajax({
                url:"{{route('queries.update')}}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'currentNotificationId' :currentNotificationId,
                    'studyIDOfCurrentNotification' :studyIDOfCurrentNotification,
                },
                success: function(response)
                {
                      console.log(response);
                      location.reload();
                }
            });
        }


    </script>
    <!-- END: Header-->
    <!-- START: Main Menu-->
    @include('layouts/sidebar_menu')
    <!-- END: Main Menu-->
    @endif
    <!-- START: Main Content-->
    <main style="min-height: calc(100vh - 140px);">
        @yield('content')

    </main>
    <!-- END: Content-->

    <!-- START: Footer-->
    <footer class="site-footer">

        2021 © OIRRC
    </footer>
    <!-- END: Footer-->
@stop

