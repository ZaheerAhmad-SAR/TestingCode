@extends('layouts.app')
@section('body')
<!-- START: Header-->
<div id="header-fix" class="header fixed-top">
    <div class="site-width">
        <nav class="navbar navbar-expand-lg  p-0">

            <div class="navbar-header  h-100 h4 mb-0 align-self-center logo-bar text-left">
                <a href="{{ url('/') }}" class="horizontal-logo text-left">
                <span class="h4 font-weight-bold align-self-center mb-0 ml-auto">
                 <!-- <img src="{{asset('public/dist/images/Logo.gif')}}" alt="" style="width: 50px;"> -->  OIRRC
                </span>
                </a>
            </div>
            <div class="navbar-header h4 mb-0 text-center h-100 collapse-menu-bar">
                <a href="#" class="sidebarCollapse" id="collapse"><i class="icon-menu"></i></a>
            </div>
            <span class="studyName" style="margin-left: 13px;"> {{session('study_short_name')}}</span>
            <!-- title here  -->
            <!--  -->
            <div class="navbar-right ml-auto h-100">
                <ul class="ml-auto p-0 m-0 list-unstyled d-flex top-icon h-100">
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
                            <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile</a>
                            @foreach(auth()->user()->user_roles as $role)
                                <a href="{{ route('switch_role',$role->role_id) }}"
                                   class="dropdown-item px-2 align-self-center d-flex">
                                    <span class="icon-user mr-2 h6 mb-0">{{ucfirst( $role->role->name)}}</span> Role</a>
                            @endforeach
                            <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-user mr-2 h6 mb-0"></span> View Profile</a>
                            <div class="dropdown-divider"></div>
                            <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-support mr-2 h6  mb-0"></span> Help Center</a>
                            <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-globe mr-2 h6 mb-0"></span> Forum</a>
                            <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-settings mr-2 h6 mb-0"></span> Account Settings</a>
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
<div class="sidebar">
    <div class="site-width">
        <!-- START: Menu-->
        <ul id="side-menu" class="sidebar-menu">
            <li class="dropdown"><a href="#"><i class="icon-home mr-1"></i> Dashboard</a>
                <ul class="@if(is_active('dashboard.index')) {{ 'active' }} @endif">
                    @can('users.dashboard',Auth::user())
                    <li class="nav-item @if(is_active('dashboard.index')) {{ 'active' }} @endif">
                        <a href="{!! route('dashboard.index') !!}">
                            <i class="icon-rocket"></i>
                            Dashboard <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    @endcan
                </ul>
                        <ul class="@if(is_active('studies.index')) {{ 'active' }} @endif">
                        @if(hasPermission(auth()->user(),'studies.index'))
                        <li class="nav-item @if(is_active('studies.index')) {{ ' active' }} @endif">
                            <a href="{!! route('studies.index') !!}">
                                <i class="icon-book-open"></i>
                                Studies
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <li class="dropdown"><!-- <a href="#"><i class="icon-organization mr-1"></i> Study Tools</a> -->
                <ul>
                    <li class="dropdown"><a href="#"><i class="icon-grid"></i>System Tools</a>
                        <ul class="sub-menu">
                            @if(hasPermission(auth()->user(),'users.store'))
                                @if(auth()->user()->role->name == 'admin')
                                <li class="@if(is_active('users.index')) {{ ' active' }} @endif">
                                    <a href="{!! route('users.index') !!}">
                                        System Users
                                    </a>
                                </li>
                            @endif
                            @endif
                            @can('roles.create',Auth::user())
                                <li class="@if(is_active('roles.index')) {{ ' active' }} @endif">
                                    <a href="{!! route('roles.index') !!}">
                                        <i class="fal fa-user-tag"></i>
                                        Roles
                                    </a>
                                </li>
                            @endcan
                            @if(hasPermission(auth()->user(),'sites.create'))
                                <li class="@if(is_active('sites.index')) {{ ' active' }} @endif">
                                    <a href="{!! route('sites.index') !!}">
                                        <i class="fal fa-location-arrow"></i>
                                        Sites
                                    </a>
                                </li>
                            @endif
                            @if(hasPermission(auth()->user(),'devices.index'))
                                <li class="@if(is_active('devices.index')) {{ ' active' }} @endif">
                                    <a href="{!! route('devices.index') !!}">
                                        <i class="fal fa-calculator"></i>
                                        Devices
                                    </a>
                                </li>
                            @endif
                            @if(hasPermission(auth()->user(),'modalities.create'))
                                <li class=" @if(is_active('modalities.index')) {{ ' active' }} @endif">
                                    <a href="{!! route('modalities.index') !!}">
                                        <i class="fal fa-mobile"></i>
                                        Modalities
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="dropdown"><!-- <a href="#"><i class="icon-organization mr-1"></i> Study Tools</a> -->
                <ul>
                    <li class="dropdown"><a href="#"><i class="icon-grid"></i>Study Tools</a>
                        <ul class="sub-menu">
                            @if(auth()->user()->role->name !='admin')
                            @can('users.create',Auth::user())
                                <li class="@if(is_active('users.index')) {{ ' active' }} @endif">
                                    <a href="{!! route('users.index') !!}">
                                        Study  Users
                                    </a>
                                </li>
                            @endcan
                            @endif
                            @if(hasPermission(auth()->user(),'studySite.index'))
                                <li class="@if(is_active('studySite.index')) {{ ' active' }} @endif">
                                    <a  href="{!! route('studySite.index') !!}">
                                        Study Sites
                                    </a>
                                </li>
                            @endif
                            <li class="dropdown"><a href="#"><i class="icon-grid"></i>Study Design</a>

                                <ul class="sub-menu">
                                    <li class="@if(is_active('studyphases.index')) {{ ' active' }} @endif">
                                        <a href="{!! route('study.index') !!}">
                                            Study Structure
                                        </a>
                                    </li>

                                    <li class="@if(is_active('forms.index')) {{ ' active' }} @endif">
                                        <a href="{!! route('forms.index') !!}">
                                            Forms
                                        </a>
                                    </li>
                                    <li class="@if(is_active('optionsGroup.index')) {{ ' active' }} @endif">
                                        <a href="{!! route('optionsGroup.index') !!}">
                                            Option Groups
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="">
                                <a href="#">
                                    Preferences
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class=""><!-- <a href="#"><i class="fas fa-laptop-medical mr-1"></i>Subject Management</a> -->
                @if(!empty(session('current_study')))
                <ul class="@if(is_active('studies.show')) {{ 'active' }} @endif">
                        <li class="nav-item @if(is_active('studies.show')) {{ ' active' }} @endif">
                            <a href="{!! route('studies.show',session('current_study')) !!}">
                                <i class="fas fa-hospital"></i>Subjects
                            </a>
                        </li>
                </ul>
                    @endif
            </li>
            <li class="dropdown"><a href="#"><i class="fas fa-sitemap"></i> Quality Control</a>
                <ul>
                    <li>
                        <a href="#">
                            <i class="fas fa-list"></i> Qc List
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-chart-line"></i> Qc Status
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-file-import"></i> Import XML
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dropdown"><a href="#"><i class="fas fa-database"></i> Grading</a>
                <ul>
                    <li>
                        <a href="#">
                           <i class="fas fa-list"></i> Grading List
                        </a>
                    </li>
                    <li>
                        <a href="#">
                           <i class="fas fa-chart-line"></i> Grading Status
                        </a>
                    </li>
                    <li>
                        <a href="#">
                           <i class="fas fa-chart-line"></i> Adjudication
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <ul>
                    <li class="dropdown"><a href="#"><i class="fas fa-chart-bar"></i>Data Management</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="#">
                                    Overall Data Report
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Data Exports
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <ul>
                    <li class="dropdown"><a href="#"><i class="fab fa-rocketchat"></i>Queries</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="#">
                                    Overall Data Report
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="dropdown"><a href="#"><i class="icon-organization mr-1"></i> Activity Log</a>
                <ul >
                    <li>
                        <a href="#">
                            <i class="fas fa-history"></i>
                            Activities
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- END: Menu-->
        <ol class="breadcrumb bg-transparent align-self-center m-0 p-0 ml-auto">
            <li class="breadcrumb-item"><a href="#">Application</a></li>
            <li class="breadcrumb-item active">Calendar</li>
        </ol>
    </div>
</div>
<!-- END: Main Menu-->

<!-- START: Main Content-->
<main style="min-height: 530px;">
    @yield('content')
</main>
<!-- END: Content-->

<!-- START: Footer-->
<footer class="site-footer">
    2020 © OIRRC
</footer>
<!-- END: Footer-->
@stop

