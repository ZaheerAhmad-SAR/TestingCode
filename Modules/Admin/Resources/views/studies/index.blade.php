@extends ('layouts.home')
@section('title')
    <title> Studies | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.css" integrity="sha512-2sFkW9HTkUJVIu0jTS8AUEsTk8gFAFrPmtAxyzIhbeXHRH8NXhBFnLAMLQpuhHF/dL5+sYoNHWYYX2Hlk+BVHQ==" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset("dist/vendors/tablesaw/tablesaw.css") }}">
    @stop

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Studies Listing</h4></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('dashboard.index')}}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Studies Listing</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->
        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="col-md-3">
                        @if(hasPermission(auth()->user(),'studies.create'))
                        <button type="button" class="btn btn-outline-primary" id="create-new-study" data-toggle="modal" data-target="#createStudy">
                            <i class="fa fa-plus"></i> Add Study
                        </button>
                        </div>
                            @endif
                            <div class="col-md-9 align-items-left" style="padding: 0px 0px 0px 95px;">
                                <button class="btn" disabled style="background:#17a2b8; color:white ">QC</button>
                                <button class="btn" disabled style="background:green; color:white">Grader</button>
                                <button class="btn" disabled style="background:red; color:white">Adjudication</button>
                            </div>
                    </div>
                    <div class="card-body">
                        <table class="tablesaw table-bordered" data-tablesaw-mode="stack" id="studies_crud">
                            <thead>
                            <tr>
                                <th scope="col" data-tablesaw-priority="persist">ID</th>
                                <th scope="col" data-tablesaw-sortable-default-col data-tablesaw-priority="3">
                                    Short Name : <strong>Study Title</strong>
                                    <br>
                                    <br>Sponsor
                                </th>
                                <th scope="col" data-tablesaw-priority="2" class="tablesaw-stack-block">Progress bar</th>
                                <th scope="col" data-tablesaw-priority="1">Status</th>
                                <th scope="col" data-tablesaw-priority="1">Study Admin</th>
                                <th scope="col" data-tablesaw-priority="4">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($studies) !=0)
                                <?php $index= 1; ?>
                                @foreach($studies as $study)
                                    <tr id="study_id_{{ $study->id }}">
                                        <td>{{$index}}</td>
                                        <td class="title">
                                            <a class="" href="{{ route('studies.show', $study->id) }}">
                                                {{ucfirst($study->study_short_name)}} : <strong>{{ucfirst($study->study_title)}}</strong>
                                            </a>
                                            <br><br><p style="font-size: 14px; font-style: oblique">Sponsor: <strong>{{ucfirst($study->study_sponsor)}}</strong></p>
                                        </td>
                                        <td class="tablesaw-stack-block">
                                            <p></p>
                                            <div class="card">
                                                <div class="card-body p-0">
                                                    <div  class="barfiller" data-color="#17a2b8">
                                                        <div class="tipWrap">
                                                 <span class="tip rounded info">
                                                     <span class="tip-arrow"></span>
                                                    </span>
                                                        </div>
                                                        <span class="fill" data-percentage="{{rand(10,100)}}" style="color: red"></span>
                                                    </div>
                                                </div>
                                            </div>
                                           <br>
                                            <div class="card">
                                                <div class="card-body p-0">
                                                    <div  class="barfiller" data-color="green">
                                                        <div class="tipWrap">
                                                 <span class="tip rounded info" style="background: green !important;">
                                                     <span class="tip-arrow"></span>
                                                    </span>
                                                        </div>
                                                        <span class="fill" data-percentage="{{rand(10,100)}}"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="card">
                                                <div class="card-body p-0">
                                                    <div  class="barfiller" data-color="red">
                                                        <div class="tipWrap">
                                                 <span class="tip rounded info" style="background: red !important;">
                                                     <span class="tip-arrow"></span>
                                                    </span>
                                                        </div>
                                                        <span class="fill" data-percentage="{{rand(10,100)}}"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>{{$study->study_status}}</td>
                                        <td>
                                            @if(!empty($study->users))
                                               @foreach($study->users as $user)
                                                    <strong>{!! ucfirst($user->name) !!},</strong>
                                                @endforeach
                                                @else
                                                <strong>{!! ucfirst($study->name) !!}</strong>
                                            @endif
                                        </td>
                                        @if(hasPermission(auth()->user(),'studies.edit'))
                                        <td>
                                            <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                    <span class="dropdown-item">
                                                        <a href="javascript:void(0)" id="change-status" data-id="{{$study->id}}" data-toggle="modal" data-target="#change_status-{{$study->id}}">
                                                            <i class="icon-action-redo"></i> Change Status
                                                        </a>
                                                    </span>
                                                    <span class="dropdown-item">
                                                           <a href="javascript:void(0)" id="edit-study" data-id="{{ $study->id }}">
                                                               <i class="icon-pencil"></i> Edit
                                                           </a>
                                                    </span>
                                                    <span class="dropdown-item">
                                                        <a href="javascript:void(0)" id="clone-study" class="clone-study" data-id="{{$study->id}}">
                                                <i class="fa fa-clone"></i> Clone
                                            </a>
                                                        </span>
                                                    <span class="dropdown-item">
                                                            <a href="#" data-id="{{$study->id}}" id="delete-study">
                                                                <i class="fa fa-trash"  aria-hidden="true">
                                                                </i> Delete</a>
                                                    </span>
                                                    <span class="dropdown-item">
                                                            <a href="#" data-id="{{$study->id}}" id="create-new-queries">
                                                                <i class="fas fa-question-circle"  aria-hidden="true">
                                                                </i> Queries</a>
                                                    </span>
                                                    <span class="dropdown-item">
                                                             <a href="#" class="addModalities">
                                                                <i class="fa fa-object-group" aria-hidden="true"></i> Preferences
                                                             </a>
                                                        </span>
                                                    <span class="dropdown-item">
                                                            <a href="#" data-id="" class="addModalities">
                                                                <i class="fa fa-object-group" aria-hidden="true"></i> Modalities
                                                            </a>
                                                        </span>
                                                </div>
                                            </div>
                                        </td>
                                            @else
                                        <td></td>
                                        @endif
                                    </tr>
                                    <?php $index++; ?>
                                @endforeach
                            @elseif(count($studies) == 0)
                                <p>No records</p>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!-- phase modle -->
    <div class="modal fade" tabindex="-1" role="dialog" id="queries-modal" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header ">
                    <p class="modal-title">Add a Queries</p>
                </div>
                <form id="queriesForm" name="queriesForm">
                    <div class="modal-body">
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                @csrf
                                <label>Current query status: &nbsp; &nbsp;<i style="color: red;" class="fas fa-question-circle"></i> &nbsp;New</label>
                                <div class="form-group row">
                                    <label for="Name" class="col-sm-4 col-form-label">Queries Assigned to:</label>
                                    <div class="col-sm-8">
                                        <label class="radio-inline  col-form-label"><input type="radio" id="assignQueries" name="assignQueries" value="users" checked> Users</label> &nbsp;
                                        <label class="radio-inline  col-form-label"><input type="radio" id="assignQueries" name="assignQueries" value="roles" > Roles</label>
                                    </div>
                                </div>
                                <div class="form-group row usersInput">
                                    <label for="Name" class="col-sm-4 col-form-label">Users:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="users" id="users">
                                            <option value="">Saqib</option>
                                            <option value="">Abid</option>
                                            <option value="">Zaheer</option>
                                            <option value="">Zeeshan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row rolesInput" style="display: none;">
                                    <label for="Name" class="col-sm-4 col-form-label">Roles:</label>
                                    <div class="col-sm-8">

                                        <label class="checked-inline  col-form-label"><input type="checkbox" id="roles" name="roles" value="users"> Adjudication</label> &nbsp;
                                        <label class="checked-inline  col-form-label"><input type="checkbox" id="roles" name="roles" value="roles" > Grader</label>
                                        <label class="checked-inline  col-form-label"><input type="checkbox" id="roles" name="roles" value="roles" > QC</label>
{{--                                        <select class="form-control" name="roles" id="roles">--}}
{{--                                            <option value="">Adjudication</option>--}}
{{--                                            <option value="">Grader</option>--}}
{{--                                            <option value="">QC</option>--}}
{{--                                            <option value="">Project Manager</option>--}}
{{--                                        </select>--}}
                                    </div>
                                </div>
                                <div class="form-group row statusInput">
                                    <label for="Name" class="col-sm-4 col-form-label">Change status to:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="queries_status" id="queries_status">
                                            <option value="">Open</option>
                                            <option value="">Unconfirmed</option>
                                            <option value="">Confirmed</option>
                                            <option value="">Resolved</option>
                                            <option value="">Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row remarksInput">
                                    <label for="Name" class="col-sm-4 col-form-label">Remarks</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" name="remarks" rows="2" id="remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal" id="addphase-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="button" class="btn btn-outline-primary" id="savePhase"><i class="fa fa-save"></i> Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- START: Modal-->
    <div class="modal fade" id="study-crud-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="studyCrudModal"></h4>
            </div>
            <div class="modal-body">
                <form id="studyForm" name="studyForm" class="form-horizontal">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Please fill all required fields!.
                            <br/>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <input type="hidden" name="study_id" id="study_id">
                    <nav>
                        <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic Info</a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Disease" role="tab" aria-controls="nav-profile" aria-selected="false">Disease Cohort</a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-users" role="tab" aria-controls="nav-profile" aria-selected="false">Study Admin</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        {{-- Basic Info Tab --}}
                        <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                            @csrf
                            <div class="form-group row" style="margin-top: 10px;">
                                <label for="study_title" class="col-md-2">Title</label>
                                <div class="{!! ($errors->has('study_title')) ?'form-group col-md-10 has-error':'form-group col-md-10' !!}">
                                    <input type="text" class="form-control" id="study_title" name="study_title" value="{{old('study_title')}}"> @error('email')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="study_short_name" class="col-md-2">Short Name</label>
                                <div class="{!! ($errors->has('study_short_name')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="text" class="form-control" id="study_short_name" name="study_short_name" value="{{old('study_short_name')}}">
                                    @error('study_short_name')
                                    <span class="text-danger small">{{ $message }} </span>
                                    @enderror
                                </div>

                                <label for="study_code" class="col-md-2">Study Code</label>
                                <div class="{!! ($errors->has('study_code')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="text" class="form-control" id="study_code" name="study_code" value="{{old('study_code')}}">
                                    @error('study_code')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="protocol_number" class="col-md-2">Protocol Number</label>
                                <div class="{!! ($errors->has('protocol_number')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="text" class="form-control" id="protocol_number" name="protocol_number" value="{{old('protocol_number')}}">
                                    @error('protocol_number')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                                <label for="trial_registry_id" class="col-md-2">Trial Registry ID</label>
                                <div class="{!! ($errors->has('trial_registry_id')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="text" class="form-control" id="trial_registry_id" name="trial_registry_id" value="{{old('trial_registry_id')}}">
                                    @error('trial_registry_id')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="study_sponsor" class="col-md-2">Study Sponsor</label>
                                <div class="{!! ($errors->has('study_sponsor')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="text" class="form-control" id="study_sponsor" name="study_sponsor" value="{{old('study_sponsor')}}">
                                    @error('study_sponsor')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                                <label for="start_date" class="col-md-2">Start Date</label>
                                <div class="{!! ($errors->has('start_date')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{(\Carbon\Carbon::today())}}">
                                    @error('start_date')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="end_date" class="col-md-2">End Date</label>
                                <div class="{!! ($errors->has('end_date')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{old('end_date')}}">
                                    @error('end_date')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                                <label for="description" class="col-md-2">Description</label>
                                <div class="{!! ($errors->has('description')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="text" class="form-control" id="description" name="description" value="{{old('description')}}">
                                    @error('description')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{--Disease tab --}}
                        <div class="tab-pane fade" id="nav-Disease" role="tabpanel" aria-labelledby="nav-Validation-tab">
                            <div class="form-group row" style="margin-top: 10px;">
                                <div class="col-md-2">
                                    <label for="disease_cohort">Disease Cohort</label>
                                </div>
                                <div class="col-md-7 appendfields">
                                    <input type="text" class="form-control" id="disease_cohort" name="disease_cohort[]" value="{{old('disease_cohort')}}" style="width: 90%;">
                                </div>
                                <div class="col-md-3" style="text-align: right">
                                    @if(hasPermission(auth()->user(),'diseaseCohort.create'))
                                    <button class="btn btn-outline-primary add_field"><i class="fa fa-plus"></i> Add New</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Assign Users --}}
                        <div class="tab-pane fade" id="nav-users" role="tabpanel" aria-labelledby="nav-Validation-tab">
                            <div class="form-group row" style="margin-top: 10px;">
                                <label for="study_users" class="col-sm-3"></label>
                                <div class="{!! ($errors->has('users')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                    <select class="searchable" id="select-users" multiple="multiple" name="users[]">
                                       @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>

                                @error('users')
                                <span class="text-danger small">
                                    {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        @if(hasPermission(auth()->user(),'studies.store'))
                        <button type="submit" class="btn btn-outline-primary" value="create"><i class="fa fa-save"></i> Save Changes</button>
                            @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- cloneStudy -->
    <div class="modal fade" id="clone-study-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="dialog">
            <div class="modal-content" style="width: inherit; top: auto!important;">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <h4 class="modal-title" id="Clone-Study-Modal"></h4>
                </div>
                <div class="modal-body">
                    <form id="cloneForm" name="cloneForm" class="form-horizontal">
                        <input type="hidden" name="clone_id" id="clone_id">
                        <div class="custom-modal-body">
                            <div class="modal-footer">
                                <button class="btn custom-btn blue-color" data-dismiss="modal">
                                    <i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
                                <button type="submit" class="btn custom-btn blue-color">
                                    <i class="fa fa-save blue-color"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="change_status" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="studyCrudModal">Change Status</h4>
                </div>
                <div class="modal-body">
                    <form action="{{route('studies.cloneStudy')}}" name="changestatus" class="">
                    @csrf
                        <input type="hidden" class="" value="{{$study->id}}">
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary" value="create"><i class="fa fa-save"></i> Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.css" integrity="sha512-2sFkW9HTkUJVIu0jTS8AUEsTk8gFAFrPmtAxyzIhbeXHRH8NXhBFnLAMLQpuhHF/dL5+sYoNHWYYX2Hlk+BVHQ==" crossorigin="anonymous" />
@endsection
@section('script')
    <script src="http://loudev.com/js/jquery.quicksearch.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js" integrity="sha512-vSyPWqWsSHFHLnMSwxfmicOgfp0JuENoLwzbR+Hf5diwdYTJraf/m+EKrMb4ulTYmb/Ra75YmckeTQ4sHzg2hg==" crossorigin="anonymous"></script>
    <script type="text/javascript">
        // run callbacks
        $('#select-users').multiSelect({
            selectableHeader: "<label for=''>All Admins</label><input type='text' class='form-control' autocomplete='off' placeholder='search here'>",
            selectionHeader: "<label for=''>Assigned Admins</label><input type='text' class='form-control appendusers' autocomplete='off' placeholder='search here'>",
        });
    </script>
    <script src="{{ asset('dist/js/jquery.validate.min.js') }}"></script>
    <script  src="{{ asset('dist/vendors/lineprogressbar/jquery.lineProgressbar.js') }}"></script>
    <script  src="{{ asset('dist/vendors/lineprogressbar/jquery.barfiller.js') }}"></script>
    <script src="{{ asset('dist/js/home.script.js') }}"></script>
    <script>
    $(document).ready(function(){
            $('.add_field').on('click',function (e) {
                e.preventDefault();
                $('.appendfields').append('<div class="disease_row" style="margin-top:10px;">' +
                    '    <input type="text" class="form-control" id="disease_cohort" name="disease_cohort[]" value="" style="width: 90%;display: inline;">' + '&nbsp;<i class="btn btn-outline-danger fas fa-trash-alt remove_field"></i></div>');
            })
            $('body').on('click','.remove_field',function () {
                var row = $(this).closest('div.disease_row');
                row.remove();
            })
        });
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#create-new-study').click(function () {
            $('#btn-save').val("create-study");
            $('#studyForm').trigger("reset");
            $('#studyCrudModal').html("Add Study");
            $('#study-crud-modal').modal('show');
        });

        $('#create-new-queries').click(function () {
            // $('#btn-save').val("create-study");
            $('#queriesForm').trigger("reset");
            $('#queries-modal').modal('show');
            //$('#queries-modal').html("Add Queries");

        });


        $('body').on('click', '#edit-study', function () {
            var study_id = $(this).data('id');
           var edit_study = $.get('studies/'+study_id+'/edit', function (data) {
                $('#studyCrudModal').html("Edit study");
                $('#btn-save').val("edit-study");
                $('#study-crud-modal').modal('show');
                $('#study_id').val(data.id);
                $('#study_short_name').val(data.study_short_name);
                $('#study_title').val(data.study_title);
                $('#study_code').val(data.study_code);
                $('#protocol_number').val(data.protocol_number);
                $('#trial_registry_id').val(data.trial_registry_id);
                $('#study_sponsor').val(data.study_sponsor);
                $('#start_date').val(data.start_date);
                $('#end_date').val(data.end_date);
                $('#description').val(data.description);
                $('#disease_cohort').val(data.disease_cohort);
                $('#users').val(data.users);
                var html = '';
                $('.appendfields').html('');
                $.each(data.disease_cohort,function (index, value) {
                    html += '<div class="disease_row" style="margin-top:10px;">' +
                        '<input type="hidden" class="form-control" name="disease_cohort[]" value="'+value.id+'" style="width: 90%;display: inline;"><input type="text" id="disease_cohort" class="form-control" value="'+value.name+'" style="width: 90%;display: inline;" name="disease_cohort_name[]">' + '&nbsp;<i class="btn btn-outline-danger fas fa-trash-alt remove_field"></i></div>';
                });
                $('.appendfields').append(html);
                var user = '';
                $('.appendusers').html('');
                var user_id = [];

               $.each(data.users,function (index, value) {
                   var id = value.id;
                    user_id.push(id);
               });
               $('#select-users').multiSelect('deselect_all');
               $('#select-users').multiSelect('select',user_id);
           })
        });

        $('body').on('click', '#delete-study', function () {
            var study_id = $(this).data("id");
            $.ajax({
                type: "DELETE",
                url: "{{ url('studies')}}"+'/'+study_id,
                beforeSend:function(){
                    return confirm("Are You sure want to delete !");
                },
                success: function (data) {
                    $("#study_id_" + study_id).remove();
                    if(data.success == true){ // if true (1)
                        setTimeout(function(){// wait for 5 secs(2)
                            location.reload(); // then reload the page.(3)
                        }, 100);
                    }
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

        $('body').on('click', '.clone-study', function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var parent_id = $(this).data("id");
            var newPath = "{{URL('studies/cloneStudy')}}";
            //alert(newPath)


            $.ajax({
                type: "POST",
                data:{'id':parent_id},
                url: newPath,
                beforeSend:function(){
                    return confirm("Are You sure want to Clone !");
                },
                success: function (data) {
                    console.log(data);
                    location.reload();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

    });

    if ($("#studyForm").length > 0) {
        $("#studyForm").validate({
            submitHandler: function(form) {
                var actionType = $('#btn-save').val();
                $('#btn-save').html('Sending..');
                $.ajax({
                    data: $('#studyForm').serialize(),
                    url: "{{ route('studies.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        var study = '<tr id="study_id_' + data.id + '"><td>' + data.id + '</td>' +
                            '<td>' + data.study_title + '</td>' +
                            '<td>' + data.study_short_name + '</td>' +
                            '<td>' + data.study_code + '</td>' +
                            '<td>' + data.protocol_number + '</td>' +
                            '<td>' + data.trial_registry_id + '</td>' +
                            '<td>' + data.study_sponsor + '</td>' +
                            '<td>' + data.start_date + '</td>' +
                            '<td>' + data.end_date + '</td>' +
                            '<td>' + data.description + '</td>' +
                            '<td>' + data.disease_cohort + '</td>';
                        study += '<td><a href="javascript:void(0)" id="edit-study" data-id="' + data.id + '" class="btn btn-info">Edit</a></td>';
                        study += '<td><a href="javascript:void(0)" id="clone-study" data-id="' + data.id + '" class="btn btn-info"> Clone</a></td>';
                        study += '<td><a href="javascript:void(0)" id="delete-study" data-id="' + data.id + '" class="btn btn-danger delete-study">Delete</a></td></tr>';


                        if (actionType == "create-study") {
                            $('#studys-crud').prepend(study);
                            location.reload();
                        } else {
                            $("#study_id_" + data.id).replaceWith(study);
                            location.reload();
                        }

                        $('#studyForm').trigger("reset");
                        $('#study-crud-modal').modal('hide');
                        $('#btn-save').html('Save Changes');

                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#btn-save').html('Save Changes');
                    }
                });
            }
        })
    }

    $(document).ready(function (){
       $('input[type="radio"]').click(function (){
           if ($(this).is(':checked'))
           {
            $(".usersInput").show();
            $(".rolesInput").hide();
           }
           if ($(this).attr("value")=="roles")
           {
            $('.usersInput').css('display','none');
            $(".rolesInput").show();
            $(".statusInput").show();
            $(".remarksInput").show();
           }
       });
    });

</script>

@endsection
