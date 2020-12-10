@extends ('layouts.home')

@section('title')
    <title> Assign Work | {{ config('app.name', 'Laravel') }}</title>
@stop

@section('styles')

    <style type="text/css">

        .select2-container--default
        .select2-selection--single {
            background-color: #fff;
            border: transparent !important;
            border-radius: 4px;
        }
        .select2-selection__rendered {
            font-weight: 400;
            line-height: 1.5;
            color: #495057 !important;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: solid black 1px;
            outline: 0;
        }

        .select2-container--default .select2-selection--multiple {
            background-color: white;
            border: 1px solid #485e9029 !important; 
            border-radius: 4px;
            cursor: text;
        }

        legend {
          /*background-color: gray;
          color: white;*/
          padding: 5px 10px;
        }

    </style>
    

    <!-- date range picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2-bootstrap.min.css') }}"/>
    <!-- sweet alerts -->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/sweetalert/sweetalert.css') }}"/>

@endsection

@section('content')

    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Assign Work</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Assign Work</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <!-- Grading legends -->
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header  justify-content-between align-items-center">
                        <h4 class="card-title">Status legend</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="{{url('images/no_status.png')}}"/>&nbsp;&nbsp;Not Initiated
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/incomplete.png')}}"/>&nbsp;&nbsp;Initiated
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/resumable.png')}}"/>&nbsp;&nbsp;Editing
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/complete.png')}}"/>&nbsp;&nbsp;Complete
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/not_required.png')}}"/>&nbsp;&nbsp;Not Required
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/query.png')}}"/>&nbsp;&nbsp;Query
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 mt-3">
                <div class="card">

                    <div class="form-group col-md-12 mt-3">        

                        <button type="button" class="btn btn-primary assign-work">Assign Work</button>

                        @if (!$subjects->isEmpty())
                        <span style="float: right; margin-top: 3px;" class="badge badge-pill badge-primary">
                            {{ $subjects->count().' out of '.$subjects->total() }}
                        </span>
                        @endif

                    </div>

                     <hr>
                    <!-- Other Filters ends -->

                    <form action="{{route('assign-work')}}" method="get" class="form-1 filter-form">
                        <div class="form-row" style="padding: 10px;">

                            <input type="hidden" name="form_1" value="1" class="form-control">

                            <div class="form-group col-md-3">
                                <label for="inputState">Subject</label>
                                <select id="subject" name="subject" class="form-control filter-form-data">
                                    <option value="">All Subject</option>
                                    @foreach($getFilterSubjects as $filterSubject)
                                    <option @if(request()->subject == $filterSubject->id) selected @endif value="{{ $filterSubject->id }}">{{ $filterSubject->subject_id }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="inputState">Phase</label>
                                <select id="phase" name="phase" class="form-control filter-form-data">
                                    <option value="">All Phase</option>
                                    @foreach($getFilterPhases as $filterPhase)
                                    <option  @if(request()->phase == $filterPhase->id) selected @endif value="{{ $filterPhase->id }}">{{ $filterPhase->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            
                            <div class="form-group col-md-2">
                            
                                <label for="inputState">Site</label>
                                <select id="site" name="site" class="form-control filter-form-data">
                                    <option value="">All Site</option>
                                     @foreach($getFilterSites as $filterSite)
                                     <option @if(request()->site == $filterSite->id) selected @endif value="{{ $filterSite->id }}">{{ $filterSite->site_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                           
                            <div class="form-group col-md-3">
                                <label for="dt">Visit Date</label>
                                <input type="text" name="visit_date" id="visit_date" class="form-control visit_date filter-form-data" value="{{ request()->visit_date }}">
                            </div>

                            <div class="form-group col-md-2 mt-4">        
                               <!--  <button type="button" class="btn btn-primary reset-filter-1">Reset</button> -->
                                <button type="submit" class="btn btn-primary btn-lng">Filter Records</button>
                                <button type="button" class="btn btn-primary reset-filter">Reset</button>

                            </div>

                        </div>
                        <!-- row ends -->
                    </form>

                    <div class="card-body">

                        <div class="table-responsive">
                        <form method="POST" action="{{ route('save-assign-work') }}" class="subject-form">
                            @csrf
                            <table class="table table-bordered" id="laravel_crud">
                                <thead>
                                    <tr class="table-secondary">
                                        <th>Select All 
                                            <input type="checkbox" class="select_all" name="select_all" id="select_all">
                                        </th>
                                        <th>Subject ID</th>
                                        <th>Phase</th>
                                        <th>Visit Date</th>
                                        <th>Site Name</th>

                                        @php
                                            $count = 5;
                                        @endphp

                                        @if ($modalitySteps != null)
                                            @foreach($modalitySteps as $key => $steps)
                                            @php
                                                $count = $count + count($steps);
                                            @endphp
                                            <th colspan="{{count($steps)}}" class="border-bottom-0" style="text-align: center;">
                                                    {{$key}}
                                            </th>
                                            @endforeach
                                        @endif
                                    </tr>

                                    @if ($modalitySteps != null)
                                    <tr class="table-secondary">
                                        <th scope="col" colspan="5" class="border-top-0"> </th>
                                        @foreach($modalitySteps as $steps)
                                        
                                            @foreach($steps as $value)
                                            <th scope="col" class="border-top-0" style="text-align: center;">
                                                  {{$value['form_type']}}
                                            </th>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                    @endif

                                </thead>

                                <tbody>
                                    @if(!$subjects->isEmpty())

                                        @foreach($subjects as $key => $subject)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="check_subject" name="check_subject[{{ $subject->id.'_'.$subject->phase_id }}]" >
                                            </td>
                                            <td>
                                               <span class="text-primary font-weight-bold">
                                                    
                                                    {{$subject->subject_id}}
                                                    
                                                </span>
                                               <input type="hidden" name="subject_id[]" value="{{ $subject->id }}">
                                            </td>
                                            <td>
                                                {{$subject->phase_name}}
                                                <input type="hidden" name="phase_id[]" value="{{ $subject->phase_id }}">
                                            </td>
                                            <td>{{ date('d-M-Y', strtotime($subject->visit_date))}}</td>
                                            <td>{{$subject->site_name}}</td>
                                            
                                            @if($subject->form_status != null)
                                                @foreach($subject->form_status as $status)

                                                   
                                                    <td style="text-align: center; {{$status['color']}}">

                                                        <a href="{{route('subjectFormLoader.showSubjectForm',['study_id' => $subject->study_id, 'subject_id' => $subject->id])}}" class="text-primary font-weight-bold">
                                                            
                                                            <?php echo $status['status']; ?>
                                                        
                                                        </a>
                                                         
                                                    </td>

                                                @endforeach
                                            @endif
                                        </tr>
                                        @endforeach

                                    @else
                                    <tr>
                                        <td colspan="{{$count}}" style="text-align: center;"> No record found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                            {{ $subjects->links() }}

                            <!--Add  Modal -->
                            <div class="modal fade" id="assign-work-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Assign Work</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="form-group">
                                        <label class="modility">Modality</label>
                                        <select class="form-control modility_id" name="modility_id" id="modility_id" required>
                                            <option value="">Select Modality</option>
                                            @foreach($getModilities as $modility)
                                                <option value="{{$modility->id}}">{{$modility->modility_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form_type">Form Type</label>
                                        <select class="form-control form_type_id" name="form_type_id" id="form_type_id" required>
                                            <option value="">Select Form Type</option>
                                            @foreach($getFormType as $formType)
                                                <option value="{{$formType->id}}">{{$formType->form_type}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="users">Users</label>
                                        <Select class="form-control users_id" name="users_id[]" id="users_id" multiple>

                                        </Select>
                                    </div>

                                    <div class="form-group">
                                        <label class="users">Due Date</label>
                                        <input type="date" class="form-control" id="assign_date" name="assign_date" value="" required>
                                    </div>

                                  </div>
                                  <!-- modal body ends -->
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Assign Work</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- Model ends -->
                        </form>
                        <!-- form ends -->
                        </div>
                        <!-- table responsive -->
                    </div>
                </div>
                <!-- Card ends -->
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

<!--Edit Modal -->
    <div class="modal fade" id="edit-assign-work-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update Assign Work</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        {{--  
        <form method="POST" action="{{ route('update-assign-work') }}">
        --}}
            @csrf
              <div class="modal-body">

                <input type="hidden" name="edit_subject_id" class="edit_subject_id" id="edit_subject_id" value="">
                <input type="hidden" name="edit_phase_id" class="edit_phase_id" id="edit_phase_id" value="">

                <div class="form-group">
                    <label class="edit-modility">Modality</label>
                    <select class="form-control edit_modility_id" name="edit_modility_id" id="edit_modility_id" required>
                        <option value="">Select Modality</option>
                        @foreach($getModilities as $modility)
                            <option value="{{$modility->id}}">{{$modility->modility_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="edit_form_type">Form Type</label>
                    <select class="form-control edit_form_type_id" name="edit_form_type_id" id="edit_form_type_id" required>
                        <option value="">Select Form Type</option>
                        @foreach($getFormType as $formType)
                            <option value="{{$formType->id}}">{{$formType->form_type}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="edit_users">Users</label>
                    <Select class="form-control edit_users_id" name="edit_users_id[]" id="edit_users_id" multiple required>

                    </Select>
                </div>

                <div class="form-group">
                    <label class="users">Due Date</label>
                    <input type="date" class="form-control" id="edit_assign_date" name="edit_assign_date" value="" required>
                </div>

              </div>
              modal body ends
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Assign Work</button>
              </div>
              <!-- footer ends -->
        </form>
        </div>
      </div>
    </div> 
    <!-- Model ends -->

@endsection
@section('script')

<!-- date range picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- select2 -->
<script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/select2.script.js') }}"></script>

<!-- sweet alert -->
<script src="{{ asset('public/dist/vendors/sweetalert/sweetalert.min.js') }}"></script>       
<!-- <script src="{{ asset('public/dist/js/sweetalert.script.js') }}"></script> -->

<script type="text/javascript">

    // initialize date range picker
    $('input[name="visit_date"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="visit_date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="visit_date"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $('select[name="subject"]').select2();
    $('select[name="phase"]').select2();
    $('select[name="site"]').select2();

    // initialize select2
    $('.users_id').select2({
        dropdownPosition: 'below'
      
    });

    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change")
        // submit the filter form
        window.location.reload();
    });

    // $('#edit_users_id').select2({
    //     dropdownPosition: 'below'
      
    // });

    //form type on change
    $('.form_type_id').change(function(){

        $.ajax({
            url: '{{ route("get-form-type-users") }}',
            type: 'GET',
            data: {
                'form_type_id': $(this).val(),
            },
            success:function(data) {
                
                if (data.success) {

                    // empty select2
                    $('.users_id').empty().trigger('change');

                    if(data.getUsers != null) {

                        var row;
                        //row += '<option value="">Select User</option>';
                        // get users
                        $.each(data.getUsers, function(key, value) {
                          row += '<option value="'+value.id+'">'+value.name+'</option>';
                        });

                        // append users to drop select2
                        $('.users_id').append(row);
                    } // user check ends
                    else {

                        var row;
                        //row += '<option value="">Select User</option>';
                        // append users to drop select2
                        $('.users_id').append(row);
                    }

                } // success

            } // success call back

        }); // ajax ends
    });

    $('.assign-work').click(function() {
        // any checkbox is checked
        if ($(".check_subject:checked").length > 0) {

            // clear form fields
            $('.subject-form').find("input[type=text], input[type=date], select").val("");
            // empty select2
            $('.users_id').empty().trigger('change');
            
            // show model
            $('#assign-work-model').modal('show');

        } else {
           // alert msg
           alert('No subject selected.');
        }
       
    });

    // select all change function
    $('.select_all').change(function(){
        
        if($(this).is(":checked")) {
            // check all checkboxes
            $(".check_subject").each(function() {
                $(this).prop('checked', true);
            });

        } else {

            // un-check all checkboxes
            $(".check_subject").each(function() {
                $(this).prop('checked', false);
            });

        }
    });

    // select/ unselect select all on checkbox event
    $('.check_subject').change(function () {

        if ($('.check_subject:checked').length == $('.check_subject').length) {
            // CHECK SELECT ALL
            $('.select_all').prop('checked',true);

        } else {
            // uncheck select all
            $('.select_all').prop('checked',false);

        }
    });


    // save assign work form
    $('.subject-form').submit(function(e) {

        e.preventDefault();
        // form data
        var fd = new FormData($(this)[0]);

        if($('#users_id').val() == '') {

            // show message
            swal({
              title: "Are you sure?",
              text: "No user selected for assigning work!",
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: 'btn-danger',
              confirmButtonText: 'Yes, please proceed!',
              cancelButtonText: "No, please cancel!",
              closeOnConfirm: true,
              closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {

                    // check from ajax request modality/form_type/subject/phase
                    $.ajax({
                        url: '{{ route("check-assign-work") }}',
                        type: 'POST',
                        data: fd,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success:function(data) {
                            
                            if (data.success == 0) {

                                // submit the form
                                e.currentTarget.submit();

                            } else {

                                swal({
                                      title: "Are you sure?",
                                      text: "Do you want to overright the existing data?",
                                      type: "warning",
                                      showCancelButton: true,
                                      confirmButtonClass: 'btn-danger',
                                      confirmButtonText: 'Yes, please proceed!',
                                      cancelButtonText: "No, please cancel!",
                                      closeOnConfirm: true,
                                      closeOnCancel: true
                                    },
                                    function(isConfirm) {
                                        if (isConfirm) {

                                            // submit the form
                                            e.currentTarget.submit();

                                        } else {

                                            // close the model
                                            $('#assign-work-model').modal('hide');

                                        }
                                    });
                            } // if/else ends

                        } // success call back function

                    }); // ajax ends
                    
                } else {

                    // close model
                    $('#assign-work-model').modal('hide');
                    
                }
            }); // confirm function ends    
            
        } else {

            // check from ajax request modality/form_type/subject/phase
            $.ajax({
                url: '{{ route("check-assign-work") }}',
                type: 'POST',
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                success:function(data) {

                    if (data.success == 0) {

                        // submit the form
                        e.currentTarget.submit();

                    } else {

                        swal({
                              title: "Are you sure?",
                              text: "Do you want to overright the existing data?",
                              type: "warning",
                              showCancelButton: true,
                              confirmButtonClass: 'btn-danger',
                              confirmButtonText: 'Yes, please proceed!',
                              cancelButtonText: "No, please cancel!",
                              closeOnConfirm: true,
                              closeOnCancel: true
                            },
                            function(isConfirm) {
                                if (isConfirm) {

                                    // submit the form
                                    e.currentTarget.submit();

                                } else {
                                    // close the model
                                    $('#assign-work-model').modal('hide');
                                }
                            });
                    } // if/else ends
                    
                } // success call back function

            }); // ajax ends
        } // user null

    }); //save form

    // function updateSubject(subjectId, phaseId) {

    //     $.ajax({
    //         url: 'put edit url here',
    //         type: 'GET',
    //         data: {
    //             'subject_id': subjectId,
    //             'phase_id' : phaseId
    //         },
    //         success:function(data) {

    //             // clear form fields
    //             $('.subject-form').find("input[type=text], input[type=date], select").val("");
    //             // empty select2
    //             $('.edit_users_id').empty().trigger('change');
    //             // append data to fields
    //             $('#edit_modility_id').val(data.editAssignWork.modility_id);
    //             $('#edit_form_type_id').val(data.editAssignWork.form_type_id);

    //             var formattedDate = new Date(data.editAssignWork.assign_date);
    //             var d = formattedDate.getDate();
    //             var m =  formattedDate.getMonth();
    //             m += 1;  // JavaScript months are 0-11
    //             var y = formattedDate.getFullYear();

    //             $('#edit_assign_date').val(y + "-" + m + "-" + d);

    //             // assign subject/phase_id
    //             $('#edit_subject_id').val(subjectId);
    //             $('#edit_phase_id').val(phaseId);

    //             // append users
    //             var row;
    //             //row += '<option value="">Select User</option>';
    //             // get users
    //             $.each(data.getUsers, function(key, value) {
    //                 row += '<option value="'+value.user_id+'" selected>'+value.name+'</option>';

    //             });

    //             $('.edit_users_id').append(row);


    //             // show model
    //             $('#edit-assign-work-model').modal('show');

    //         } // success call back function

    //     }); // ajax ends
    // }

     //form type on change
    $('.edit_form_type_id').change(function(){

        $.ajax({
            url: '{{ route("get-form-type-users") }}',
            type: 'GET',
            data: {
                'form_type_id': $(this).val(),
            },
            success:function(data) {
                
                if (data.success) {

                    // empty select2
                    $('.edit_users_id').empty().trigger('change');

                    if(data.getUsers != null) {

                        var row;
                        //row += '<option value="">Select User</option>';
                        // get users
                        $.each(data.getUsers, function(key, value) {
                          row += '<option value="'+value.id+'">'+value.name+'</option>';
                        });

                        // append users to drop select2
                        $('.edit_users_id').append(row);
                    } // user check ends
                    else {

                        var row;
                        //row += '<option value="">Select User</option>';
                        // append users to drop select2
                        $('.edit_users_id').append(row);
                    }

                } // success

            } // success call back

        }); // ajax ends
    });

   
</script>
@endsection




