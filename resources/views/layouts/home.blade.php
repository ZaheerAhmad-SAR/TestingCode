@extends('layouts.app')
@section('body')
    <!-- START: Header-->
    <div id="header-fix" class="header fixed-top">
        <div class="site-width">
            <nav class="navbar navbar-expand-lg  p-0">

                <div class="navbar-header  h-100 h4 mb-0 align-self-center logo-bar text-left">
                    <a href="{{ url('/') }}" class="horizontal-logo text-left">
                <span class="h4 font-weight-bold align-self-center mb-0 ml-auto">
                    OIRRC
                 {{-- <img src="{{asset('public/dist/images/Logo.gif')}}" alt="OIRRC" style="width: 50px;"> --}}
                </span>
                    </a>
                </div>
                <div class="navbar-header h4 mb-0 text-center h-100 collapse-menu-bar">
                    <a href="#" class="sidebarCollapse" id="collapse"><i class="icon-menu"></i></a>
                </div>
                <!-- title here  -->
                <!--  -->
                <div class="navbar-right ml-auto h-100">
                    <ul class="ml-auto p-0 m-0 list-unstyled d-flex top-icon h-100">
                        <li style="margin-top: 5px;">
                            <span>Logged User: {!! auth()->user()->name !!}</span>
                        <span>
                            @if(!empty(session('current_study')))
                            <strong>Current Study:</strong> {{session('study_short_name')}}
                                @endif
                        </span>
                        </li>
                        <li class="d-inline-block align-self-center  d-block d-lg-none">
                            <a href="#" class="nav-link mobilesearch" data-toggle="dropdown" aria-expanded="false"><i class="icon-magnifier h4"></i>
                            </a>
                        </li>
                        <li class="dropdown user-profile align-self-center d-inline-block">
                            <a href="#" class="nav-link py-0" data-toggle="dropdown" aria-expanded="false">
                                <div class="media">
                                    <img src="{{asset('public/dist/images/author.jpg')}}" alt="" class="d-flex img-fluid rounded-circle" width="29">
                                </div>
                            </a>
                            <div class="dropdown-menu border dropdown-menu-right p-0">
                                <a href="{{route('users.updateProfile')}}" class="dropdown-item px-2 align-self-center d-flex">
                                    <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile
                                </a>
                             {{--    @foreach(auth()->user()->user_roles as $role)
                                <a href="#" class="dropdown-item px-2 align-self-center d-flex" data-toggle="modal" data-target="#editProfile">
                                    <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile</a>
                                @foreach(auth()->user()->user_roles as $role)

                                    <a href="{{ route('switch_role',$role->role_id) }}"
                                       class="dropdown-item px-2 align-self-center d-flex">
                                        <span class="icon-user mr-2 h6 mb-0">{{ucfirst( $role->role->name)}}</span> Role</a>
                                @endforeach --}}
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

    <!-- END: Header-->

    <!-- START: Main Menu-->
    @include('layouts/sidebar_menu')
    <!-- END: Main Menu-->

    <!-- START: Main Content-->
    <main style="min-height: 500px;">
        @yield('content')
    </main>
    <!-- END: Content-->

    <!-- START: Footer-->
    <footer class="site-footer">
        2020 © OIRRC
    </footer>
    <!-- END: Footer-->
@stop

