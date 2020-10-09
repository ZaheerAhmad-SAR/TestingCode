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
            @if(session()->has('message'))
                <div class="col-lg-12 success-alert">
                    <div class="alert alert-primary success-msg" role="alert">
                        {{ session()->get('message') }}
                    </div>
                </div>
            @endif
        </div>
        <!-- END: Breadcrumbs-->
        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        @if(hasPermission(auth()->user(),'studies.create'))
                        <button type="button" class="btn btn-outline-primary" id="create-new-study" data-toggle="modal" data-target="#createStudy">
                            <i class="fa fa-plus"></i> Add Study
                        </button>
                            @endif
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
                            {!! dd($studies) !!}
                            @if(count($studies) !=0)
                                <?php $index= 1; ?>
                                @foreach($studies as $study)
                                    <tr id="study_id_{{ $study->id }}">
                                        <td>{{$index}}</td>
                                        <td class="studyID" style="display: none">{{ $study->id }}</td>
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
                                                        <a href="javascript:void(0)" id="change-status" data-target-id="{{$study->id}}" data-toggle="modal" data-target="#change_status">
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
                                                    @include('queries::queries.query_popup_span',['study_id'=>$study->id])
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

    <!-- START: Modal-->
    <div class="modal fade" id="study-crud-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="studyCrudModal"></h4>
            </div>
            <div class="modal-body">
                <form action="{{route('studies.store')}}" name="studyForm" id="studyForm" class="form-horizontal" method="POST">
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
                                    <input type="hidden" name="study_id" id="studyID" value="">
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
                    <form action="{{route('studies.studyStatus')}}" name="changestatus" class="" method="post">
                    @csrf
                        @if(!empty($study))
                        <input type="hidden" value="{{$study->id}}" id="study_ID" name="study_ID">
                        @endif
                        <div class="form-group row">
                            <div class="col-md-3">Status</div>
                            <div class="col-md-6">
                                <select class="form-control dropdown" name="status" id="status">
                                    <option value="">Select Status</option>
                                    <option value="Archived">Archive</option>
                                    <option value="Development">Development</option>
                                    <option value="Live">Live</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary" value="create"><i class="fa fa-save"></i> Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('queries::queries.query_popup')
@endsection
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.css" integrity="sha512-2sFkW9HTkUJVIu0jTS8AUEsTk8gFAFrPmtAxyzIhbeXHRH8NXhBFnLAMLQpuhHF/dL5+sYoNHWYYX2Hlk+BVHQ==" crossorigin="anonymous" />
@endsection
@section('script')
    <script src="http://loudev.com/js/jquery.quicksearch.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js" integrity="sha512-vSyPWqWsSHFHLnMSwxfmicOgfp0JuENoLwzbR+Hf5diwdYTJraf/m+EKrMb4ulTYmb/Ra75YmckeTQ4sHzg2hg==" crossorigin="anonymous"></script>

<script type="text/javascript">
 $(document).ready(function(){
       $('#change_status').on('show.bs.modal',function (e) {
        var id = $(e.relatedTarget).data('target-id');
        $('#study_ID').val(id);
       })
    })
</script>
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
                    '    <input type="text" class="form-control" name="disease_cohort_name[]" value="" style="width: 90%;display: inline;">' + '&nbsp;<i class="btn btn-outline-danger fas fa-trash-alt remove_field"></i></div>');
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
            $('#studyForm').attr('action', "{{route('studies.store')}}");
            $('#study-crud-modal').modal('show');
        });


        $('body').on('click', '#edit-study', function () {
            $('#studyForm').attr('action', "{{route('studies.update_studies')}}");
            var study_id = $(this).data('id');
           var edit_study = $.get('studies/'+study_id+'/edit', function (data) {
                $('#studyCrudModal').html("Edit study");
                $('#btn-save').val("edit-study");
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
                $('#studyID').val(data.id);
                var html = '';
                $('.appendfields').html('');
                $.each(data.disease_cohort,function (index, value) {
                    html += '<div class="disease_row" style="margin-top:10px;">' +
                        '<input type="text" class="form-control" value="'+value.name+'" style="width: 90%;display: inline;" name="disease_cohort_name[]">' + '&nbsp;<i class="btn btn-outline-danger fas fa-trash-alt remove_field"></i></div>';
                });
                $('.appendfields').append(html);
                var user = '';
                $('.appendusers').html('');

                $.each(data.users,function (index, value) {

                    user += '<option selected="selected" value=" '+value.id+' " >'+value.name+'</option>';

                });
               $('.appendusers').html(user);

                var user_id = [];

               $.each(data.users,function (index, value) {
                   var id = value.id;
                    user_id.push(id);
               });
               $('#select-users').multiSelect('deselect_all');
               $('#select-users').multiSelect('select',user_id);
               $('#study-crud-modal').modal('show');
           })
        });

        $('body').on('click', '#delete-study', function () {
            var study_id = $(this).data("id");
            confirm("Are You sure want to delete !");
            $.ajax({
                type: "DELETE",
                url: "{{ url('studies')}}"+'/'+study_id,
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
            confirm("Are You sure want to Clone !");
            $.ajax({
                type: "POST",
                data:{'id':parent_id},
                url: newPath,
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

</script>

@endsection
