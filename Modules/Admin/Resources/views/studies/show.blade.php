@extends('layouts.home')
@section('title')
    <title> View Study Details | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
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
                                <th>Subject ID</th>
                                <th>Enrollment Date</th>
                                <th>Site Name</th>
                                <th>Disease Cohort</th>
                                <th>Study Eye</th>
                                <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach($subjects as $subject)
                                    <tr>
                                        <td class="id" style="display: none;">{{$subject->id}}</td>
                                        <td class="site_id" style="display: none;">{{$subject->site_id}}</td>
                                        <td class="eye" style="display: none;">{{$subject}}</td>
                                        <td class="subject_id"><a href="{{route('showSubjectForm',['study_id'=>$currentStudy->id,'subject_id'=>$subject->id])}}" class="text-primary font-weight-bold">{{$subject->subject_id}}</a>
                                        </td>
                                        <td class="enrol_date">{{$subject->enrollment_date}}</td>
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
   --}}                                                     <a href="javascript:void(0)" id="edit-subject" class="EditSubjects" data-toggle="modal" data-target="#editSubjects"
                                                           data-id="{{ $subject->id }}" data-url="{{route('subjects.edit',$subject->id)}}">
                                                <i class="far fa-edit"></i> Edit</a>
                                                    </span>
                                                    <span class="dropdown-item">
                                                            <a href="{{route('users.destroy',$subject->id)}}" id="delete-device" data-id="{{ $subject->id }}">
                                                            <i class="far fa-edit"></i>&nbsp; Delete </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
                    <form action="{{route('subjects.store')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <input type="hidden" value="{{$study->id}}" name="study_id">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" value="{{$study}}" name="user">
                        <div class="form-group row" style="margin-top: 10px;">
                            <label for="subject_id" class="col-md-2">Subject ID</label>
                            <div class="{!! ($errors->has('subject_id')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <input type="text" class="form-control" id="subject_id" name="subject_id" value="{{old('subject_id')}}">
                                @error('subject_id')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                            <label for="study_short_name" class="col-md-2">Enrollment Date</label>
                            <div class="{!! ($errors->has('enrollment_date')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <input type="date" class="form-control" id="enrollment_date" name="enrollment_date" value="{{old('enrollment_date')}}">
                                @error('enrollment_date')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="site_id" class="col-md-2">Site</label>
                            <div class="{!! ($errors->has('site_id')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <select name="site_id" id="site_id" class="form-control">
                                    <option value="">Select Subject Site</option>
                                    @if(!empty($site_study))
                                        @foreach($site_study as $site)
                                            <option class="dropdown" value="{{$site->id}}">{{$site->site_name}}</option>
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
                                    <option value="od">OD</option>
                                    <option value="os">OS</option>
                                    <option value="ou">OU</option>
                                    <option value="na">NA</option>
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
                            <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save</button>
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
                    <h4 class="modal-title" id="deviceCrudModal">Edit Subject</h4>
                </div>
                <div  class="modal-body">
                    <form action="{{route('subjects.update')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <input type="hidden" value="{{$study->id}}" name="study_id">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" value="{{$study}}" name="user">
                        <div class="form-group row" style="margin-top: 10px;">
                            <label for="subject_id" class="col-md-2">Subject ID</label>
                            <div class="{!! ($errors->has('subject_id')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <input type="text" class="form-control" id="subject_id" name="subject_id" value="{{old('subject_id')}}">
                                @error('subject_id')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                            <label for="study_short_name" class="col-md-2">Enrollment Date</label>
                            <div class="{!! ($errors->has('enrollment_date')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <input type="date" class="form-control" id="enrollment_date" name="enrollment_date" value="{{old('enrollment_date')}}">
                                @error('enrollment_date')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="site_id" class="col-md-2">Site</label>
                            <div class="{!! ($errors->has('site_id')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <select name="site_id" id="site_id" class="form-control">
                                    <option value="">Select Subject Site</option>
                                    @if(!empty($site_study))
                                        @foreach($site_study as $site)
                                            <option class="dropdown" value="{{$site->id}}">{{$site->site_name}}</option>
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
                                    <option value="od">OD</option>
                                    <option value="os">OS</option>
                                    <option value="ou">OU</option>
                                    <option value="na">NA</option>
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
                            <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $('body').on('click','.EditSubject',function () {
            var row = $(this).closest('tr')
                id  = row.find('td.id').text()
                subject_id  = row.find('td.subject_id').text()
                enrol_date = row.find('td.enrol_date').text()
                site_id = row.find('td.site_id').text()
                disease = row.find('td.disease').text()
                study_eye = row.find('td.study_eye').text();
            $('#id').val(id);
            $('#subject_id').val(subject_id);
            $('#enrollment_date').val(enrol_date);
            $('#site_id').val(site_id);
            $('#study_eye').val(study_eye);
            $('#disease_cohort').val(disease);
            $('#createSubjects').modal('show');
        });
    </script>
@endsection
