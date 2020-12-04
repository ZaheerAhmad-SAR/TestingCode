<div class="sidebar">
    <div class="site-width">
        <!-- START: Menu-->
        <ul id="side-menu" class="sidebar-menu" style="height: calc(100vh - 140px);overflow-y: scroll;">
            <li class="dropdown"><a href="#"><i class="icon-home mr-1"></i> Dashboard</a>
                <ul class="@if(is_active('dashboard.index')) {{ 'active' }} @endif">
                  {{--  @if(hasPermission(auth()->user(),'dashboard.index'))--}}
                        <li class="nav-item @if(is_active('dashboard.index')) {{ 'active' }} @endif">
                            <a href="{{ url('/dashboard') }}">
                                <i class="icon-rocket"></i>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                    {{--@endif--}}
                </ul>
                <ul class="@if(is_active('studies.index')) {{ 'active' }} @endif">
                    @if(
                        (hasPermission(auth()->user(),'studies.index') &&
                        empty(session('current_study'))) ||
                        isThisUserSuperAdmin(auth()->user(),'studies.index')
                        )
                    <li class="nav-item @if(is_active('studies.index')) {{ ' active' }} @endif">
                        <a href="{!! route('studies.index') !!}">
                            <i class="icon-book-open"></i>
                            Studies
                        </a>
                    </li>
                       @else
                            <li class="nav-item @if(is_active('studies.index')) {{ ' active' }} @endif">
                                <a href="{!! route('studies.index') !!}">
                                    <i class="icon-book-open"></i>
                                    Exit {{session('study_short_name')}},<strong>Study:</strong>
                                </a>
                            </li>
                        @endif
                </ul>
            </li>
            @if(hasPermission(auth()->user(),'systemtools.index'))
            <li class="dropdown">
                <ul>
                        <li class="dropdown"><a href="#"><i class="icon-grid"></i>System Tools</a>
                            <ul class="sub-menu">
                                @if(hasPermission(auth()->user(),'users.index'))
                                    <li class="@if(is_active('users.index')) {{ ' active' }} @endif">
                                        <a href="{!! route('users.index') !!}">
                                            System Users
                                        </a>
                                    </li>
                                @endif
                                @if(hasPermission(auth()->user(),'roles.index'))
                                    <li class="@if(is_active('roles.index')) {{ ' active' }} @endif">
                                        <a href="{!! route('roles.index') !!}">
                                            <i class="fal fa-user-tag"></i>
                                            Roles
                                        </a>
                                    </li>
                                @endif
                                @if(hasPermission(auth()->user(),'sites.index'))
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
                                @if(hasPermission(auth()->user(),'modalities.index'))
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
            @endif
            @if(hasPermission(auth()->user(),'studytools.index'))
                @if(session('current_study'))
                <li class="dropdown">
                    <ul>
                        <li class="dropdown"><a href="#"><i class="icon-grid"></i>Study Tools</a>
                            <ul class="sub-menu">
                                @if(hasPermission(auth()->user(),'studyusers.index'))
                                    <li class="@if(is_active('studyusers.index')) {{ ' active' }} @endif">
                                        <a href="{!! route('studyusers.index') !!}">
                                            Study  Users
                                        </a>
                                    </li>
                                @endif
                               {{-- @if(hasPermission(auth()->user(),'studyRoles.index'))
                                    <li class="@if(is_active('studyRoles.index')) {{ ' active' }} @endif">
                                        <a href="{!! route('studyRoles.index') !!}">
                                            Study  Roles
                                        </a>
                                    </li>
                                @endif--}}
                                @if(hasPermission(auth()->user(),'studySite.index'))
                                    <li class="@if(is_active('studySite.index')) {{ ' active' }} @endif">
                                        <a  href="{!! route('studySite.index') !!}">
                                            Study Sites
                                        </a>
                                    </li>
                                @endif
                                @if(hasPermission(auth()->user(),'studydesign.index'))
                                    <li class="dropdown"><a href="#"><i class="icon-grid"></i>Study Design</a>
                                        <ul class="sub-menu">
                                            @if(hasPermission(auth()->user(),'study.index'))
                                                <li class="@if(is_active('study.index')) {{ ' active' }} @endif">
                                                    <a href="{!! route('study.index') !!}">
                                                        Study Structure
                                                    </a>
                                                </li>
                                            @endif
                                            @if(hasPermission(auth()->user(),'forms.index'))
                                                <li class="@if(is_active('forms.index')) {{ ' active' }} @endif">
                                                    <a href="{!! route('forms.index') !!}">
                                                        Forms
                                                    </a>
                                                </li>
                                            @endif
                                            @if(hasPermission(auth()->user(),'study.index'))
                                                <li class="@if(is_active('optionsGroup.index')) {{ ' active' }} @endif">
                                                    <a href="{!! route('optionsGroup.index') !!}">
                                                        Option Groups
                                                    </a>
                                                </li>
                                            @endif
                                            @if(hasPermission(auth()->user(),'annotation.index'))
                                                <li class="@if(is_active('annotation.index')) {{ ' active' }} @endif">
                                                    <a  href="{!! route('annotation.index') !!}">
                                                        Annotations
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                                <li class="">
                                    <a href="{!! route('preference.list') !!}">
                                        Preferences
                                    </a>
                                </li>

                            </ul>
                        </li>
                </ul>
                </li>
            @endif
            @endif

            <!-- //////////////////////////////// Transmission //////////////////////// -->

            @if(hasPermission(auth()->user(),'systemtools.index'))
            <li class="dropdown">
                <ul>
                    <li class="dropdown"><a href="#"><i class="icon-grid"></i>Transmissions</a>
                        <ul class="sub-menu">

                            @if(!empty(session('current_study')))
                            <li class="@if(is_active('transmissions.study-transmissions')) {{ ' active' }} @endif">
                                <a href="{!! route('transmissions.study-transmissions') !!}">
                                    Study Transmissions
                                </a>
                            </li>
                            @endif


                            <li class="@if(is_active('transmissions.index')) {{ ' active' }} @endif">
                                <a href="{!! route('transmissions.index') !!}">
                                    System Transmissions
                                </a>
                            </li>

                        </ul>
                    </li>
                </ul>
            </li>

            @elseif(hasPermission(auth()->user(),'qualitycontrol.create') && hasPermission(auth()->user(),'qualitycontrol.edit'))

            @if(!empty(session('current_study')))
            <li class="dropdown">
                <ul>
                    <li class="dropdown"><a href="#"><i class="icon-grid"></i>Transmissions</a>
                        <ul class="sub-menu">


                            <li class="@if(is_active('transmissions.study-transmissions')) {{ ' active' }} @endif">
                                <a href="{!! route('transmissions.study-transmissions') !!}">
                                    Study Transmissions
                                </a>
                            </li>

                        </ul>
                    </li>
                </ul>
            </li>
            @endif

            @endif

            <!-- //////////////////////////////// Transmissions ////////////////// -->
            @if(hasPermission(auth()->user(),'subjects.index'))
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
            @endif

            @if(hasPermission(auth()->user(),'studytools.index'))
                @if(!empty(session('current_study')))
                <li class="">
                    <ul class="@if(is_active('assign-work')) {{ 'active' }} @endif">
                        <li class="nav-item @if(is_active('assign-work')) {{ ' active' }} @endif">
                            <a href="{!! route('assign-work') !!}">
                                <i class="fas fa-hospital"></i>Assign Work
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            @endif

            @if(hasPermission(auth()->user(),'qualitycontrol.create') && hasPermission(auth()->user(),'qualitycontrol.edit'))

                @if(session('current_study'))
                    <li class="dropdown">
                        <ul>
                            <li class="dropdown"><a href="#"><i class="fas fa-sitemap"></i> Quality Control</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('qualitycontrol.index')}}">
                                            <i class="fas fa-list"></i> QC List
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('qualitycontrol.qc-work-list')}}">
                                            <i class="fas fa-list"></i> QC Work List
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif

            @if(hasPermission(auth()->user(),'grading.create') && hasPermission(auth()->user(),'grading.edit'))

                @if(session('current_study'))
                    <li class="dropdown">
                        <ul>
                            <li class="dropdown"><a href="#"><i class="fas fa-sitemap"></i> Grading</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{route('grading.index')}}">
                                            <i class="fas fa-list"></i> Grading List
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('gradingcontrol.grading-work-list')}}">
                                            <i class="fas fa-list"></i> Grading Work List
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{route('grading.status')}}">
                                            <i class="fas fa-chart-line"></i> Grading Status
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif

            @if(hasPermission(auth()->user(),'adjudication.index'))
                @if(session('current_study'))
                    <li class="dropdown">
                        <ul>
                            <li class="dropdown"><a href="#"><i class="fas fa-database"></i> Adjudication</a>
                               <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('adjudication.index')}}">
                                            <i class="fas fa-list"></i> Adjudication List
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('adjudicationcontroller.adjudication-work-list')}}">
                                            <i class="fas fa-list"></i> Adjudication Work List
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif
            @if(session('current_study'))
            <li class="dropdown">
                <ul>
                    <li class="dropdown"><a href="#"><i class="fas fa-database"></i> Certification Data</a>
                        <ul class="sub-menu">
                            <li  class="@if(is_active('photographer.index')) {{ ' active' }} @endif">
                                <a href="{{route('photographer.index')}}">
                                    <i class="fas fa-list"></i> Photographers List
                                </a>
                            </li>
                            <li class="@if(is_active('devices_certify.index')) {{ ' active' }} @endif">
                                <a href="{{route('devices_certify.index')}}">
                                    <i class="fas fa-list"></i> Devices List
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endif
            @if(hasPermission(auth()->user(),'data_management.index'))
                @if(session('current_study'))
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
                                        <a href="{{route('formDataExport.index')}}">
                                            Data Exports
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif
            @if(hasPermission(auth()->user(),'queries.index'))
                @if(session('current_study'))
                    <li class="dropdown">
                        <ul>
                            <li class="dropdown"><a href="#"><i class="fab fa-rocketchat"></i>Queries</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{route('queries.index')}}">
                                            Overall Data
                                        </a>
                                    </li>

        {{--                            @if(hasPermission(auth()->user(),'queries.index'))--}}
        {{--                            <li>--}}
        {{--                                <a href="{{route('queries.chatindex')}}">--}}
        {{--                                    Chat App--}}
        {{--                                </a>--}}
        {{--                            </li>--}}
        {{--                                @endif--}}
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif

                @if(hasPermission(auth()->user(),'systemtools.index') && hasPermission(auth()->user(),'trail_logs.list'))
                <li class="dropdown">
                    <ul>
                        <li class="dropdown"><a href="#"><i class="icon-organization mr-1"></i> Audit Trail</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{route('trail_logs.list')}}">
                                        <i class="fas fa-history"></i>
                                        Audit Trail
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                @elseif(hasPermission(auth()->user(),'trail_logs.list'))

                    <li class="dropdown"><a href="#"><i class="icon-organization mr-1"></i> Audit Trail</a>
                        <ul >

                            <li>
                                <a href="{{route('trail_logs.list')}}">
                                    <i class="fas fa-history"></i>
                                    Audit Trail
                                </a>
                            </li>
                        </ul>
                    </li>

                @endif
        </ul>
        <!-- END: Menu-->
        <ol class="breadcrumb bg-transparent align-self-center m-0 p-0 ml-auto">
            <li class="breadcrumb-item"><a href="#">Application</a></li>
            <li class="breadcrumb-item active">Calendar</li>
        </ol>
    </div>
</div>
