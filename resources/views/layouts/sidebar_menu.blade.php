<style>
    .badge {
        line-height: normal !important;
    }
    .scroll-bar{
        height: calc(100vh - 140px);
        overflow-y: scroll;
    }
</style>
<div class="sidebar">
    <div class="site-width">
        <!-- START: Menu  style="height: calc(100vh - 140px);overflow-y: scroll;"-->
        <ul id="side-menu" class="sidebar-menu">
            <li class="dropdown">
                <ul>
                    <li class="dropdown"><a href="#"><i class="fas fa-home"></i> Dashboard</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ route('dashboard.index','-')}}">
                                    <i class="fas fa-list"></i> System Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('finance.index')}}">
                                    <i class="icon-layers"></i> Finance Dashboard
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
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
                                    Exit {{session('study_short_name')}} Study
                                </a>
                            </li>
                        @endif
                </ul>
            </li>
            @if(hasPermission(auth()->user(),'systemtools.index'))
            <li class="dropdown">
                <ul>
                        <li class="dropdown"><a href="#"><i class="fas fa-tools"></i>System Tools</a>
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
                        <li class="dropdown" dusk='study_tools'><a href="#"><i class="fas fa-tools"></i>Study Tools</a>
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
                                    <li class="dropdown" dusk="study_design"><a href="#"><i class="icon-grid"></i>Study Design</a>
                                        <ul class="sub-menu">
                                            @if(hasPermission(auth()->user(),'study.index'))
                                                <li class="@if(is_active('study.index')) {{ ' active' }} @endif">
                                                    <a href="{!! route('study.index') !!}" dusk="study_structure">
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
                    <li class="dropdown"><a href="#"><i class="fas fa-file-contract"></i>Transmissions</a>
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
                                    <i class="fas fa-user-tag"></i>Subjects
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
                            <li class="dropdown"><a href="#"><i class="fas fa-file-image"></i> Quality Control</a>
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

                                    {{--
                                    <li>
                                        <a href="{{route('grading.status')}}">
                                            <i class="fas fa-chart-line"></i> Grading Status
                                        </a>
                                    </li>
                                    --}}

                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif

            <!-- show grading status -->
            @if(hasPermission(auth()->user(),'studytools.index') && hasPermission(auth()->user(),'grading.index'))

                @if(!empty(session('current_study')))
                <li class="">
                    <ul class="@if(is_active('grading.status')) {{ 'active' }} @endif">
                        <li class="nav-item @if(is_active('grading.status')) {{ ' active' }} @endif">
                            <a href="{{route('grading.status')}}">
                                <i class="fas fa-chart-line"></i> Grading Status
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

            @endif

            @if(hasPermission(auth()->user(),'adjudication.create') && hasPermission(auth()->user(),'adjudication.edit'))

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
            @if(hasPermission(auth()->user(),'certification.index'))
                @if(session('current_study'))
                <li class="dropdown">
                    <ul>
                        <li class="dropdown"><a href="#"><i class="fas fa-certificate"></i> Certification Data</a>
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
                                    <li>
                                        <a href="{{route('subjectFormLoader.lock-data')}}">
                                            Data Lock
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
                            @php $count = Modules\Queries\Entities\QueryUser::where('user_id','=',\auth()->user()->id)->count() @endphp
                            <li class="nav-item"><a href="{{route('queries.index')}}"><i class="fab fa-rocketchat"></i>Queries <span class="badge badge-dark">{{$count}}</span></a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif


            @if(hasPermission(auth()->user(),'bug-reporting.index'))
{{--                @if(session('current_study'))--}}
            <li class="dropdown">
                <ul>
                    <li class="nav-item"><a href="{{route('bug-reports.index')}}"><i class="fas fa-bug"></i>Bug Reports <span class="badge badge-danger">{{Modules\BugReporting\Entities\BugReport::where('parent_bug_id','like', 0)->where('status','open')->count() }}</span></a>
                    </li>
                </ul>
            </li>
{{--                @endif--}}
            @endif

            @if(hasPermission(auth()->user(),'certification-photographer.index'))
            <li class="dropdown">
                <ul>
                    <li class="dropdown"><a href="#"><i class="fas fa-certificate"></i> Certification App</a>
                        <ul class="sub-menu">

                            <li  class="@if(is_active('certification-photographer')) {{ ' active' }} @endif">
                                <a href="{{route('certification-photographer.index')}}" style="font-size: 12px;">
                                    <i class="fas fa-list"></i>Photographer Certification
                                </a>
                            </li>

                            <li class="@if(is_active('certification-device')) {{ ' active' }} @endif">
                                <a href="{{route('certification-device.index')}}" style="font-size: 12px;">
                                    <i class="fas fa-list"></i>Device Certification
                                </a>
                            </li>

                            <li  class="@if(is_active('certified-photographer')) {{ ' active' }} @endif">
                                <a href="{{route('certified-photographer')}}" style="font-size: 12px;">
                                    <i class="fas fa-list"></i>Certified Photographers
                                </a>
                            </li>

                            <li class="@if(is_active('certified-device')) {{ ' active' }} @endif">
                                <a href="{{route('certified-device')}}" style="font-size: 12px;">
                                    <i class="fas fa-list"></i> Certified Devices
                                </a>
                            </li>

                            @if(hasPermission(auth()->user(),'certification-preferences.index'))
                            <li  class="@if(is_active('certification-preferences')) {{ ' active' }} @endif">
                                <a href="{{route('certification-preferences.index')}}" style="font-size: 12px;">
                                    <i class="fas fa-list"></i> Preferences
                                </a>
                            </li>
                            @endif

                            <li  class="@if(is_active('certification-template')) {{ ' active' }} @endif">
                                <a href="{{route('certification-template')}}" style="font-size: 12px;">
                                    <i class="fas fa-list"></i> Template
                                </a>
                            </li>

                        </ul>
                    </li>
                </ul>
            </li>
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
                            <li>
                                <a href="{{route('trail_logs.usersActivities')}}">
                                    <i class="fas fa-users"></i>
                                    Users Activities
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            @elseif(hasPermission(auth()->user(),'trail_logs.list'))

                @if(session('current_study'))
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
                @endif



            @endif

            <li class="dropdown">
                <ul>
                    <li class="dropdown"><a href="#"><i class="fas fa-info"></i>TAT Reports</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{route('reports.index')}}">
                                    <i class="fas fa-history"></i>
                                    Visit Completion Report
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- END: Menu-->

    <div class="btn-group dropup" style="margin-left: 15px;">
     <button type="button" class="btn btn-primary dropdown-toggle position-fixed" data-toggle="dropdown" style="bottom: 10px;">  <i class="icon-question"></i> Support</button>
        <div class="dropdown-menu">
            @if(hasPermission(auth()->user(),'bug-reporting.create'))
            <a href="javascript:void(0);" class="dropdown-item"  data-toggle="modal" data-target="#reportabugmodel"><i class="fa fa-plus"></i>   Report a Bug</a>
            <div class="dropdown-divider"></div>
            @endif
            <a href="#" class="dropdown-item"><i class="fa fa-plus"></i>  User Manual</a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item"> <i class="fa fa-plus"></i>  OCAP v2021.{{  TagReleasenumber() }}</a>
        </div>
    </div>


    </div>

</div>
    <!-- Modal To add Option Groups -->
    <div class="modal fade" tabindex="-1" role="dialog" id="reportabugmodel">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Report a Bug</p>
                </div>
                <form name="bugReportingForm" id="bugReportingForm">
                    <div class="modal-body">
                            <div class="tab-content clearfix">

                                <div class="form-group row">
                                    <div class="col-md-3">Short Title</div>
                                    <div class="form-group col-md-9">
                                        <input type="text" name="shortTitle" id="shortTitle" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3">Enter Your Message</div>
                                    <div class="form-group col-md-9">
                                        <textarea class="form-control" name="yourMessage" id="yourMessage"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3">Attach a File</div>
                                    <div class="form-group col-md-9">
                                        <input type="file" class="form-control" id="attachFile" name="attachFile">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Name" class="col-md-3 col-form-label">Severity/Priority</label>
                                    <div class="col-md-9">
                                        <label class="radio-inline  col-form-label"><input type="radio" id="severity" name="severity" value="low"> Low</label> &nbsp;
                                        <label class="radio-inline  col-form-label"><input type="radio" id="severity" name="severity" value="medium"> Medium</label>
                                        <label class="radio-inline  col-form-label"><input type="radio" id="severity" name="severity" value="high"> High</label>
                                    </div>
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button id="bug-close-btn" class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End -->
<script src="{{ asset('public/dist/vendors/jquery/jquery-3.3.1.min.js') }}"></script>
{{--  style="height: calc(100vh - 140px);overflow-y: scroll;" --}}
 <script type="text/javascript">
    $(".sidebar").hover(function () {
        $('#side-menu').toggleClass("scroll-bar");
    });
 </script>   
 <script type="text/javascript">

        $("#bugReportingForm").on('submit', function(e) {

            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var shortTitle  = $("#shortTitle").val();
            var yourMessage = $("#yourMessage").val();
            var query_url   =  document.URL;
            var severity  = $("input[name='severity']:checked").val();
            console.log(severity);
            var formData = new FormData();
            formData.append('shortTitle', shortTitle);
            formData.append('yourMessage', yourMessage);
            formData.append('query_url', query_url);
            formData.append('severity', severity);

            // Attach file
            formData.append("attachFile", $("#attachFile")[0].files[0]);

            $.ajax({
                url: "{{route('bug-reports.store')}}",
                type: "POST",
                data: formData,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                success: function (results) {
                    console.log(results);
                    $('#bugReportingForm').trigger("reset");
                    location.reload();
                },
                error: function (results) {
                    console.log('Error:', results);
                }
            });
        });
    </script>

