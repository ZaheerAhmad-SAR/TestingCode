@extends('layouts.app')
@section('body')
    <!-- START: Header-->
{{--    @php dd(session()->all()); @endphp--}}
    @if (!empty(auth()->user()))
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script>
            window.Laravel = { csrfToken:'{{csrf_token()}}'};
        </script>

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

                <div class="navbar-right ml-auto h-100 updateListItems">
                    <ul class="ml-auto p-0 m-0 list-unstyled d-flex top-icon h-100">
                        <li style="margin-top: 5px;">
                        <span>
                            @if(!empty(session('current_study')))
                            <strong>Current Study:</strong> {{session('study_short_name')}}
                                @endif
                        </span>
                        </li>

                        <li class="dropdown align-self-center d-inline-block receivedata">
                            <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false">
                                <i class="icon-bell h4"></i>
                                      @php
                                         $count =  \Modules\Queries\Entities\AppNotification::where('user_id','=', auth()->user()->id)
                                         ->where('is_read','no')->count();

                                      @endphp

{{--                                <div id="app">--}}
{{--                                        <notification></notification>--}}
{{--                                </div>--}}
{{--                                <script src="{{asset('js/app.js')}}"></script>--}}

                                @if($count > 0 )
                                    <span class="badge badge-pill badge-danger updateCounter"style="height: 20px;top: 12px;" data-value="{{auth()->user()->id}}">{{$count}}</span>
                                @endif

                            </a>

                            <ul class="dropdown-menu dropdown-menu-right border  py-0 receivedata">

                                @php
                                    $userQueries =  \Modules\Queries\Entities\AppNotification::where('user_id','=', auth()->user()->id)
                                    ->where('is_read','no')
                                      //->orderBy('created_at', 'DESC')
                                      //->groupBy('question_id')
                                     //->distinct('user_id')
                                    ->get();


                                @endphp

                                @if(!empty($userQueries))
                                @foreach($userQueries as $record)
                                   @if($record->notifications_type =='query')

                                    @php
                                    $userData ='';
                                    $answers = '';
                                    $query = '';

                                    $query = \Modules\Queries\Entities\Query::where('id','=',$record->queryorbugid)
                                    //->where('query_status','open')
                                    //->orderBy('created_at', 'DESC')
                                    //->groupBy('parent_query_id')
                                    //->distinct('parent_query_id')
                                    ->first();


                                    $studyData = '';
                                    if ($query!==null)
                                    {
                                        $studyData = Modules\Admin\Entities\Study::where('id',$query->study_id)->first();
                                    }

                                    $userData  = App\User::where('id',$record->notification_create_by_user_id)->first();
                                    @endphp


                                            @if($query->query_condition == 'new')
                                            <li>
                                                <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0 currentNotificationId appRedirectPage"   data-study_id="{{$studyData->id}}" data-study_short_name="{{$studyData->study_short_name}}" data-study_code="{{$studyData->study_code}}"  data-query_url="{{$query->query_url}}" data-id="{{$query->study_id}}" href="javascript:void(0);" data-value="{{$query->id}}">
                                                    <div class="media">
                                                        <img src="{{asset('dist/images/author.jpg')}}" alt="" class="d-flex mr-3 img-fluid rounded-circle">
                                                        <div class="media-body">

                                                            <p class="mb-0 text-primary">

                                                                <b> {{$studyData->study_short_name}} : New Query By {{$userData->name}} </b>
                                                            </p>

                                                            {{Carbon\Carbon::parse($record->created_at)->diffForHumans()}}
                                                        </div>

                                                    </div>
                                                </a>
                                            </li>
                                            @endif
                                            @if($query->query_condition == 'reply')
                                                <li>
                                                    @php

                                                        $userReplyBy  = App\User::where('id',$query->queried_remarked_by_id)->first();

                                                    @endphp
                                                    <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0 currentNotificationId appRedirectPage"   data-study_id="{{$studyData->id}}" data-study_short_name="{{$studyData->study_short_name}}" data-study_code="{{$studyData->study_code}}"  data-query_url="{{$query->query_url}}" data-id="{{$query->study_id}}" href="javascript:void(0);" data-value="{{$query->id}}">
                                                        <div class="media">
                                                            <img src="{{asset('dist/images/author.jpg')}}" alt="" class="d-flex mr-3 img-fluid rounded-circle">
                                                            <div class="media-body">

                                                                <p class="mb-0 text-primary">

                                                                    <b> {{$studyData->study_short_name}} : Query Reply By {{$userReplyBy->name}} </b>
                                                                </p>

                                                                {{Carbon\Carbon::parse($record->created_at)->diffForHumans()}}
                                                            </div>

                                                        </div>
                                                    </a>
                                                </li>
                                            @endif
                                        @else
                                            @php
                                                $bugs = '';

                                                $bugs = Modules\BugReporting\Entities\BugReport::where('id',$record->queryorbugid)->first();
                                                 if ($bugs !== null)
                                                 {
                                                      $studyData = Modules\Admin\Entities\Study::where('study_short_name',$bugs->study_name)->first();

                                                 }

                                                  $userData  = App\User::where('id',$record->notification_create_by_user_id)->first();

                                            $answers = Modules\BugReporting\Entities\BugReport::where('parent_bug_id','=',$record->queryorbugid)->orWhereNull('parent_bug_id')
                                            ->where('status','open')->get();

                                            @endphp

                                            <li>
                                                @if($studyData!==null)
                                                    <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0 currentNotificationBugId"
                                                       data-study_id="{{$studyData->id}}"  data-study_short_name="{{$studyData->study_short_name}}"
                                                       data-study_code="{{$studyData->study_code}}"
                                                       data-query_url="{{$bugs->bug_url}}"  data-id ="{{$studyData->id}}" href="javascript:void(0);" data-value="{{$bugs->id}}">
                                                        @else

                                                            <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0"
                                                               href="{{$bugs->bug_url}}">
                                                       @endif




                                                    <div class="media">
                                                        <img src="{{asset('dist/images/author.jpg')}}" alt="" class="d-flex mr-3 img-fluid rounded-circle">
                                                        <div class="media-body">

                                                            <p class="mb-0 text-primary">

                                                                <b> @if($studyData!==null) {{$studyData->study_short_name}} : @else  @endif  @if($answers->isEmpty())  New Bug By  @else Reply By  @endif {{$userData->name}} </b>
                                                            </p>

                                                            {{Carbon\Carbon::parse($bugs->created_at)->diffForHumans()}}
                                                        </div>

                                                    </div>
                                                </a>
                                            </li>

                                        @endif


                                @endforeach
                                @endif

                                <tr>
                                    @if(Modules\Queries\Entities\AppNotification::showMarkAllReadDiv() > 0)
                                        <td class="align-top"><a class="markAllRead"  href="javascript:void(0);"><span><i class="fas fa-check"></i></span> &nbsp;Mark All Read</a></td>
                                    @endif
                                    <td class="align-top"><a href="{{route('notifications.index')}}"><span><i class="fas fa-book-open"></i></span> &nbsp; All Notifications</a></td>

                                 </tr>
                            </ul>
                        </li>


                        <li class="d-inline-block align-self-center  d-block d-lg-none">
                            <a href="#" class="nav-link mobilesearch" data-toggle="dropdown" aria-expanded="false"><i class="icon-magnifier h4"></i>
                            </a>
                        </li>
                        <li class="dropdown user-profile align-self-center d-inline-block">
                            <a href="#" id="dropdownLogout" class="nav-link py-0" data-toggle="dropdown" aria-expanded="false">
                                <div class="media">
                                    @if(!empty(auth()->user()->image))
                                       <img src="{{ asset('/images/'.auth()->user()->image) }}" style="width: 40px;border-radius: 50%;">
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
                                <a href="{{route('home.user-preferences')}}" class="dropdown-item px-2 align-self-center d-flex">
                                    <i class="fas fa-user"></i>&nbsp; User Preferences
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

{{--        <script src="{{asset('js/app.js')}}"></script>--}}




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

