@extends('layouts.home')
@section('title')
    <title> View Subject Details | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    @php $old_values = ''; @endphp
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Subjects Details</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Studies</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->
        <div class="card">
            <div class="card-body">
                <form action="{{route('studies.show',session('current_study'))}}" method="get" class="filter-form">
                    @csrf
                    <input type="hidden" name="sort_by_field" id="sort_by_field" value="{{ getOldValue($old_values,'sort_by_field') }}">
                    <input type="hidden" name="sort_by_field_name" id="sort_by_field_name" value="{{ getOldValue($old_values,'sort_by_field_name') }}">
                    <div class="form-row" style="padding: 10px;">
                        <div class="form-group col-md-4">
                            <input type="text" name="subject_id" class="form-control" placeholder="Subject ID" value="{{ getOldValue($old_values,'subject_id')}}">
                        </div>
                         <div class="form-group col-md-4">
                            @php
                                $old_site ='';
                                $old_site =  getOldValue($old_values,'site_id');
                            @endphp
                            <select name="site_id" class="form-control" >
                                <option value="">Select Site</option>
                                @if(!empty($site_study))
                                    @foreach($site_study as $site)
                                        <option class="dropdown" value="{{$site->id}}" @if($old_site == $site->id) selected @endif>{{$site->site_name}}--{{$site->site_code}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="date" class="form-control" name="enrollment_date" placeholder="Enrollment Date" value="{{ getOldValue($old_values,'enrollment_date')}}">
                        </div>
                        <div class="form-group col-md-4">
                            @php
                                $old_cohort ='';
                                $old_cohort =  getOldValue($old_values,'disease_cohort');
                            @endphp
                            <select name="disease_cohort" class="form-control">
                                <option value="">Select Disease Cohort</option>
                                @if(!empty($diseaseCohort))
                                    {!! $diseaseCohort !!}
                                    @foreach($diseaseCohort as $disease)
                                        <option class="dropdown" value="{{$disease->id}}"  @if($old_cohort == $disease->id) selected @endif>{{$disease->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            @php
                                $old_eye ='';
                                $old_eye =  getOldValue($old_values,'study_eye');
                            @endphp
                            <select name="study_eye" class="form-control">
                                <option value="">Select Study Eye</option>
                                <option value="OD" @if($old_eye == 'OD') selected @endif>OD</option>
                                <option value="OS" @if($old_eye == 'OS') selected @endif>OS</option>
                                <option value="OU" @if($old_eye == 'OU') selected @endif>OU</option>
                                <option value="NA" @if($old_eye == 'NA') selected @endif>NA</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4" style="text-align: right;">
                            <button class="btn btn-outline-warning reset-filter"><i class="fas fa-undo-alt" aria-hidden="true"></i> Reset</button>
                            <button type="submit" class="btn btn-primary submit-filter"><i class="fas fa-filter" aria-hidden="true"></i> Filter</button>
                        </div>
                    </div>    
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        @if(hasPermission(auth()->user(),'subjects.create'))
                            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#createSubjects">
                                <i class="fa fa-plus"></i> Add Subject
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive list">
                            <table class="table">
                                <thead>
                                <th onclick="changeSort('subject_id');">Subject ID <i class="fas fa-sort float-mrg"></i></th>
                                <th onclick="changeSort('enrollment_date');">Enrollment Date <i class="fas fa-sort float-mrg"></i></th>
                                <th onclick="changeSort('site_name');">Site Name <i class="fas fa-sort float-mrg"></i></th>
                                <th>Disease Cohort </th>
                                <th onclick="changeSort('study_eye');">Study Eye <i class="fas fa-sort float-mrg"></i></th>
                                <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach($subjects as $subject)
                                    <tr id="subject_id_{{ $subject->id }}">
                                        <td class="id" style="display: none;">{{$subject->id}}</td>
                                        <td class="site_id" style="display: none;">{{$subject->site_id}}</td>
                                        <td class="edit_study_eye" style="display: none;">{{$subject->study_eye}}</td>
                                        <td class="edit_disease_cohort" style="display: none;">{{$subject->disease_cohort_id}}</td>
                                        <td class="eye" style="display: none;">{{$subject}}</td>
                                        <td class="subject_id"><a href="{{route('subjectFormLoader.showSubjectForm',['study_id'=>$currentStudy->id,'subject_id'=>$subject->id])}}" class="text-primary font-weight-bold">{{$subject->subject_id}}</a>
                                        </td>
                                        <td class="enrol_date">{{ date_format( new DateTime($subject->enrollment_date) , 'M-d-Y')}}</td>
                                        <td class="site_name">{{!empty($subject->site_name)?$subject->site_name:'SiteName'}}</td>
                                        <td class="disease">{{!empty($subject->disease_cohort->name)?$subject->disease_cohort->name:'Not Defined'}}</td>
                                        <td class="study_eye">{{!empty($subject->study_eye)?$subject->study_eye:'Not Defined'}}</td>
                                        <td>
                                            <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                    <span class="dropdown-item">
   {{--                                                     <a href="javascript:void(0)" id="edit-subject" data-toggle="modal" data-id="{{ $subject->id }}" data-target="#editSubject">
                                                            <i class="far fa-edit"></i>&nbsp; Edit </a>
   --}}
                                                    <a href="javascript:void(0)" id="edit-subject" class="EditSubjects" data-id="{{ $subject->id }}" data-url="{{route('subjects.update', $subject->id)}}">
                                                        <i class="far fa-edit"></i> Edit</a>
                                                        </span>
                                                        <span class="dropdown-item">
                                                            <a href="javascript:void(0)" data-href="{{route('subjects.destroy',$subject->id)}}" id="delete-subject" data-id="{{ $subject->id }}">
                                                            <i class="fa fa-trash"></i>&nbsp; Delete
                                                            </a>
                                                        </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            
                            {{ $subjects->appends(['sort_by_field_name' => \Request::get('sort_by_field_name'), 'sort_by_field' => \Request::get('sort_by_field'), 'subject_id' => \Request::get('subject_id'), 'site_id' => \Request::get('site_id'), 'date' => \Request::get('date'), 'disease_cohort' => \Request::get('disease_cohort'), 'study_eye' => \Request::get('study_eye')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="createSubjects">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title" id="deviceCrudModal">Add Subject</h4>
                </div>
                <div  class="modal-body">
                    <form action="{{route('subjects.store')}}" enctype="multipart/form-data" method="POST" class="create-subject-form">
                        @csrf
                        <input type="hidden" value="{{$study->id}}" name="study_id">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" value="{{$study}}" name="users">
                        <div class="form-group row" style="margin-top: 10px;">
                            <div class="col-md-2"></div>
                            <div class="col-md-10">
                                <p class="msg" style="color: red;font-size: 11px;"></p>
                            </div>
                            <label for="subject_id" class="col-md-2">Subject ID</label>
                            <div class="{!! ($errors->has('subject_id')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <input type="text" class="form-control" id="subject_id" name="subject_id" value="{{old('subject_id')}}" onchange="check_if_subject_exists(this)" required>
                                @error('subject_id')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                            <label for="study_short_name" class="col-md-2">Enrollment Date</label>
                            <div class="{!! ($errors->has('enrollment_date')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <input type="date" class="form-control" id="enrollment_date" name="enrollment_date" value="{{old('enrollment_date')}}" required>
                                @error('enrollment_date')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="site_id" class="col-md-2">Site</label>
                            <div class="{!! ($errors->has('site_id')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <select name="site_id" id="site_id" class="form-control" required>
                                    <option value="">Select Subject Site</option>
                                    @if(!empty($site_study))

                                        @foreach($site_study as $site)
                                            <option class="dropdown" value="{{$site->id}}">{{$site->site_name}}-{{$site->site_code}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('site_id')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                            <label for="study_eye" class="col-md-2">Study Eye</label>
                            <div class="{!! ($errors->has('study_eye')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <select name="study_eye" id="study_eye" class="form-control">
                                    <option value="">Select Study Eye</option>
                                    <option value="OD">OD</option>
                                    <option value="OS">OS</option>
                                    <option value="OU">OU</option>
                                    <option value="NA">NA</option>
                                </select>
                                @error('subject_site')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="site_id" class="col-md-2">Disease Cohort</label>
                            <div class="{!! ($errors->has('disease_cohort')) ?'form-group col-md-10 has-error':'form-group col-md-10' !!}">
                                <select name="disease_cohort" id="disease_cohort" class="form-control">
                                    <option value="">Select Subject Disease Cohort</option>
                                    @if(!empty($diseaseCohort))
                                        {!! $diseaseCohort !!}
                                        @foreach($diseaseCohort as $disease)
                                            <option class="dropdown" value="{{$disease->id}}">{{$disease->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('disease_cohort')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary create-subject-btn"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="editSubjects">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title" id="">Edit Subject</h4>
                </div>
                <div  class="modal-body">
                    <form method="POST" enctype="multipart/form-data" class="edit-subject-form">
                        @method('PUT')
                        @csrf


                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="form-group row" style="margin-top: 10px;">

                            <label for="subject_id" class="col-md-2">Subject ID</label>
                            <div class="{!! ($errors->has('subject_id')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <input type="text" class="form-control" id="edit_subject_id" name="subject_id" value="{{old('subject_id')}}">
                                @error('subject_id')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                            <label for="study_short_name" class="col-md-2">Enrollment Date</label>
                            <div class="{!! ($errors->has('enrollment_date')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <input type="date" class="form-control" id="edit_enrollment_date" name="enrollment_date" value="{{old('enrollment_date')}}">
                                @error('enrollment_date')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="site_id" class="col-md-2">Site</label>
                            <div class="{!! ($errors->has('site_id')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <select name="site_id" id="edit_site_id" class="form-control">
                                    <option value="">Select Subject Site</option>
                                    @if(!empty($site_study))
                                        @foreach($site_study as $site)
                                            <option class="dropdown" value="{{$site->id}}">{{$site->site_name}}--{{$site->site_code}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('site_id')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                            <label for="study_eye" class="col-md-2">Study Eye</label>
                            <div class="{!! ($errors->has('study_eye')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <select name="study_eye" id="edit_study_eye" class="form-control">
                                    <option value="">Select Study Eye</option>
                                    <option value="OD">OD</option>
                                    <option value="OS">OS</option>
                                    <option value="OU">OU</option>
                                    <option value="NA">NA</option>
                                </select>
                                @error('subject_site')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="site_id" class="col-md-2">Disease Cohort</label>
                            <div class="{!! ($errors->has('disease_cohort')) ?'form-group col-md-10 has-error':'form-group col-md-10' !!}">
                                <select name="disease_cohort" id="edit_disease_cohort" class="form-control">
                                    <option value="">Select Subject Disease Cohort</option>
                                    @if(!empty($diseaseCohort))
                                        {!! $diseaseCohort !!}
                                        @foreach($diseaseCohort as $disease)
                                            <option class="dropdown" value="{{$disease->id}}">{{$disease->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('disease_cohort')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary edit-subject-btn"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $('body').on('click','.EditSubjects',function () {
            var row = $(this).closest('tr')
                id  = row.find('td.id').text()
                subject_id  = row.find('td.subject_id').text()
                enrol_date = row.find('td.enrol_date').text()
                site_id = row.find('td.site_id').text()
                disease = row.find('td.edit_disease_cohort').text()
                study_eye = row.find('td.edit_study_eye').text();

                console.log($(this).attr('data-url'));

            $('#edit_id').val(id);
            $('#edit_subject_id').val(subject_id);
            $('#edit_enrollment_date').val(enrol_date);
            $('#edit_site_id').val(site_id);
            $('#edit_study_eye').val(study_eye);
            $('#edit_disease_cohort').val(disease);
            // assign action attribute
            $('.edit-subject-form').attr('action', $(this).attr('data-url'));
            $('#editSubjects').modal('show');
        });

        // add form submit
        $('.create-subject-btn').click(function(e){
            e.preventDefault();
            // hide message
            $('.subject-message').remove();
            var editID = '';
            var subjectID = $('#createSubjects').find($('#subject_id')).val();
            var type = 'add';
            var message = '<span class="subject-message" style="color: red;">Subject ID is not unique.</span>';

            $.ajax({
                type: "GET",
                url: "{{route('subjects.check-subject')}}",
                data: {
                    edit_id : editID,
                    subject_id: subjectID,
                    type: type
                },
                success: function(data) {
                    if (data == 'success') {
                        // submit form
                        $('.create-subject-form').submit();

                    } else if (data == 'error') {

                        $('#createSubjects').find($('#subject_id')).after(message);
                    }
                } // success ends
            });

        });

        // edit form submit
        $('.edit-subject-btn').click(function(e){
            e.preventDefault();
            // hide message
            $('.edit-subject-message').remove();
            var editID = $('#editSubjects').find($('#edit_id')).val();
            var subjectID = $('#editSubjects').find($('#edit_subject_id')).val();
            var type = 'update';
            var message = '<span class="edit-subject-message" style="color: red;">Subject ID is not unique.</span>';

            $.ajax({
                type: "GET",
                url: "{{route('subjects.check-subject')}}",
                data: {
                    edit_id : editID,
                    subject_id: subjectID,
                    type: type
                },
                success: function(data) {
                    if (data == 'success') {
                        // submit form
                        $('.edit-subject-form').submit();

                    } else if (data == 'error') {
                        $('#editSubjects').find($('#edit_subject_id')).after(message);
                    }
                } // success ends
            });
        });

        $('body').on('click', '#delete-subject', function () {
                var subject_id = $(this).data("id");
                if(confirm("Are You sure want to delete !")) {
                    $.ajax({
                        type: "DELETE",
                        data:{
                            'subject_id': subject_id,
                            '_token': '{{ csrf_token() }}',
                        },
                        url: $(this).data("href"),
                        success: function (data) {

                            if(data.success == null){ // if true (1)

                                $("#subject_id_" + subject_id).slideUp(500).delay(5000);
                                    setTimeout(function(){// wait for 5 secs(2)
                                        location.reload(); // then reload the page.(3)
                                    }, 100);

                            } // if ends

                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    }); // ajax

                } // confirm

        });
        // check for duplicate subject entry
        function check_if_subject_exists(selectObject)
        {
            var value = selectObject.value
               url_route = "{{ URL('subjects/check_variable') }}";

           $.ajax({
                url: url_route,
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'id': value
                },
                success: function(response) {
                    if (response == 'subject_found') {
                        $('.msg').html('Subject already exists!');
                        $('#subject_id').val('');
                        $('#subject_id').focus();
                    } else {
                        $('.msg').html('');
                    }
                }
            });
        }
        function changeSort(field_name){
            var sort_by_field = $('#sort_by_field').val();
            if(sort_by_field =='' || sort_by_field =='ASC'){
               $('#sort_by_field').val('DESC');
               $('#sort_by_field_name').val(field_name);
            }else if(sort_by_field =='DESC'){
               $('#sort_by_field').val('ASC'); 
               $('#sort_by_field_name').val(field_name); 
            }
            $('.filter-form').submit();
        }
    </script>
@endsection
