@extends('layouts.app')
@section('body')
    <!-- START: Header-->
{{--    @php dd(session()->all()); @endphp--}}
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
{{--                @if(empty(auth()->user()->google2fa_secret))--}}
{{--                    <div class="" style="margin-top: 15px;padding: 9px 62px 14px 0px;" >--}}
{{--                        @php--}}
{{--                        $style = (empty(auth()->user()->google2fa_secret))? 'style="display:none;"':'style="margin-top:20px"';--}}
{{--                        @endphp--}}
{{--                        <div class="alert alert-warning alert-dismissible" {{ $style }} >--}}
{{--                            <a type="submit" class="btn btn-outline-info" href="{{route('users.updateProfile')}}" >Enable now</a>--}}
{{--                            <strong>Warning!</strong> Google 2-Factor Auth is disabled, turn it on.--}}
{{--                            <button class="close" data-dismiss="alert">&times;</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
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
                                @foreach($userQueries as $record)
                                   @if($record->notifications_type=='query')

                                    @php
                                    $userData ='';
                                    $answers = '';
                                    $query = '';

                                    $answers = \Modules\Queries\Entities\Query::where('parent_query_id','=',$record->queryorbugid)->orWhereNull('parent_query_id')
                                    ->where('query_status','open')->get();

                                    $query = \Modules\Queries\Entities\Query::where('id','=',$record->queryorbugid)
                                    ->where('query_status','open')
                                    ->where('parent_query_id',0)
                                    ->first();
                                    $studyData = '';
                                    if ($query!==null)
                                    {
                                        $studyData = Modules\Admin\Entities\Study::where('id',$query->study_id)->first();
                                    }

                                    $userData  = App\User::where('id',$record->notification_create_by_user_id)->first();
                                    @endphp
                                            <li>

                                                <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0 currentNotificationId appRedirectPage"   data-study_id="{{$studyData->id}}" data-study_short_name="{{$studyData->study_short_name}}" data-study_code="{{$studyData->study_code}}"  data-query_url="{{$query->query_url}}" data-id="{{$query->study_id}}" href="javascript:void(0);" data-value="{{$query->id}}">
                                                    <div class="media">
                                                        <img src="{{asset('dist/images/author.jpg')}}" alt="" class="d-flex mr-3 img-fluid rounded-circle">
                                                        <div class="media-body">

                                                            <p class="mb-0 text-primary">

                                                                <b> {{$studyData->study_short_name}} : @if($answers->isEmpty())  New Query By  @else Reply By  @endif {{$userData->name}} </b>
                                                            </p>

                                                            {{Carbon\Carbon::parse($record->created_at)->diffForHumans()}}
                                                        </div>

                                                    </div>
                                                </a>
                                            </li>
                                        @else
                                            @php
                                                $bugs = Modules\BugReporting\Entities\BugReport::where('id',$record->queryorbugid)->first();

                                                 $studyData = Modules\Admin\Entities\Study::where('study_short_name',$bugs->study_name)->first();
                                                  $userData  = App\User::where('id',$record->notification_create_by_user_id)->first();

                                            $answers = Modules\BugReporting\Entities\BugReport::where('parent_bug_id','=',$record->queryorbugid)->orWhereNull('parent_bug_id')
                                            ->where('status','open')->get();

                                            @endphp

                                            <li>

                                                <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0 currentNotificationId appRedirectPage"   data-study_id="{{$studyData->id}}" data-study_short_name="{{$studyData->study_short_name}}" data-study_code="{{$studyData->study_code}}"  data-query_url="{{$bugs->bug_url}}" data-id="{{$studyData->id}}" href="javascript:void(0);" data-value="{{$bugs->id}}">
                                                    <div class="media">
                                                        <img src="{{asset('dist/images/author.jpg')}}" alt="" class="d-flex mr-3 img-fluid rounded-circle">
                                                        <div class="media-body">

                                                            <p class="mb-0 text-primary">

                                                                <b> {{$studyData->study_short_name}} : @if($answers->isEmpty())  New Bug By  @else Reply By  @endif {{$userData->name}} </b>
                                                            </p>

                                                            {{Carbon\Carbon::parse($record->created_at)->diffForHumans()}}
                                                        </div>

                                                    </div>
                                                </a>
                                            </li>
                                        @endif


                                @endforeach
                                @endif

                                <tr>
                                                 @if($count > 0 )
                                    &nbsp; &nbsp;<td class="align-baseline"><a class="markAllRead" href="javascript:void(0);"><span><i class="fas fa-check"></i></span> &nbsp;Mark All Read</a></td> &nbsp; &nbsp; &nbsp;
{{--                                                @else--}}
{{--                                        &nbsp; &nbsp;<td class="align-baseline"><a class="noNewNotification" href="javascript:void(0);"><span><i class="fas fa-check"></i></span> &nbsp;No New Notification</a></td> &nbsp; &nbsp; &nbsp;--}}
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
                            <a href="#" class="nav-link py-0" data-toggle="dropdown" aria-expanded="false">
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
            var query_url        = $(this).attr('data-query_url');
            var study_id         = $(this).attr('data-study_id');
            var study_code       = $(this).attr('data-study_code');
            var study_short_name = $(this).attr('data-study_short_name');
            updateNotificationToRead(currentNotificationId,query_url,study_id,study_code,study_short_name);
        });

        function updateNotificationToRead(currentNotificationId,query_url,study_id,study_code,study_short_name)
        {
            $.ajax({
                url:"{{route('notifications.update')}}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'currentNotificationId' :currentNotificationId,
                    'query_url' :query_url,
                    'study_id' :study_id,
                    'study_code' :study_code,
                    'study_short_name' :study_short_name,
                },
                success: function(response)
                {
                    console.log(response);
                    if (response.success)
                    {
                        var urlPath = response.success;
                        window.location.href = urlPath;
                    }
                }
            });
        }

        $('.markAllRead').click(function () {

            $.ajax({
                url:"{{route('notifications.markAllNotificationToRead')}}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST'
                },
                success: function(response)
                {
                    console.log(response);
                    location.reload();
                }
            });
        });


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

