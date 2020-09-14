<div class="sidebar">
    <div class="site-width">
        <!-- START: Menu-->
        <ul id="side-menu" class="sidebar-menu" style="height: 560px;overflow-y: scroll;">
            <li class="dropdown"><a href="#"><i class="icon-home mr-1"></i> Dashboard</a>
                <ul class="@if(is_active('dashboard.index')) {{ 'active' }} @endif">
                    @if(hasPermission(auth()->user(),'dashboard.index'))
                        <li class="nav-item @if(is_active('dashboard.index')) {{ 'active' }} @endif">
                            <a href="{!! route('dashboard.index') !!}">
                                <i class="icon-rocket"></i>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                    @endif
                </ul>
                <ul class="@if(is_active('studies.index')) {{ 'active' }} @endif">
                    <li class="nav-item @if(is_active('studies.index')) {{ ' active' }} @endif">
                        <a href="{!! route('studies.index') !!}">
                            <i class="icon-book-open"></i>
                            Studies
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dropdown"><!-- <a href="#"><i class="icon-organization mr-1"></i> Study Tools</a> -->
                <ul>
                    @if(hasPermission(auth()->user(),'systemtools.index'))
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
                    @endif
                </ul>
            </li>
            <li class="dropdown"><!-- <a href="#"><i class="icon-organization mr-1"></i> Study Tools</a> -->
                <ul>
                    @if(hasPermission(auth()->user(),'studytools.index'))
                        <li class="dropdown"><a href="#"><i class="icon-grid"></i>Study Tools</a>
                            <ul class="sub-menu">
                                @if(hasPermission(auth()->user(),'users.index'))
                                    <li class="@if(is_active('users.index')) {{ ' active' }} @endif">
                                        <a href="{!! route('users.index') !!}">
                                            Study  Users
                                        </a>
                                    </li>
                                @endif
                                @if(hasPermission(auth()->user(),'studyrole.index'))
                                    <li class="@if(is_active('studyrole.index')) {{ ' active' }} @endif">
                                        <a href="{!! route('studyrole.index') !!}">
                                            Study  Roles
                                        </a>
                                    </li>
                                @endif
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
                                            @if(hasPermission(auth()->user(),'studyphases.index'))
                                                <li class="@if(is_active('studyphases.index')) {{ ' active' }} @endif">
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
                                            @if(hasPermission(auth()->user(),'studyphases.index'))
                                                <li class="@if(is_active('optionsGroup.index')) {{ ' active' }} @endif">
                                                    <a href="{!! route('optionsGroup.index') !!}">
                                                        Option Groups
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                                <li class="">
                                    <a href="#">
                                        Preferences
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </li>
            <li class=""><!-- <a href="#"><i class="fas fa-laptop-medical mr-1"></i>Subject Management</a> -->
                @if(!empty(session('current_study')))
                    <ul class="@if(is_active('studies.show')) {{ 'active' }} @endif">
                        @if(hasPermission(auth()->user(),'subjects.index'))
                            <li class="nav-item @if(is_active('studies.show')) {{ ' active' }} @endif">
                                <a href="{!! route('studies.show',session('current_study')) !!}">
                                    <i class="fas fa-hospital"></i>Subjects
                                </a>
                            </li>
                        @endif
                    </ul>
                @endif
            </li>
            @if(hasPermission(auth()->user(),'qualitycontrol.index'))
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
            @endif
            @if(hasPermission(auth()->user(),'grading.index'))
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
            @endif
            @if(hasPermission(auth()->user(),'qualitycontrol.index'))
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
            @endif
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
            @if(hasPermission(auth()->user(),'qualitycontrol.index'))
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
            @endif
        </ul>
        <!-- END: Menu-->
        <ol class="breadcrumb bg-transparent align-self-center m-0 p-0 ml-auto">
            <li class="breadcrumb-item"><a href="#">Application</a></li>
            <li class="breadcrumb-item active">Calendar</li>
        </ol>
    </div>
</div>