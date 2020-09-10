@extends('layouts.home')
@section('title')
    <title> View Study Details | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    @php
        $studyid = last(request()->segments());
//        session(['current_study'=>$studyid,'study_short_name'=> $study->study_short_name]);
        session(['current_study'=>$studyid,'study_short_name'=> $study->study_short_name]);
         @endphp
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
    </div>

    <div class="row">
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#createSubjects">
                            <i class="fa fa-plus"></i> Add Subject
                        </button>
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
                                <td>{{$subject->subject_id}}</td>
                                <td>{{$subject->enrollment_date}}</td>
                                <td>{{!empty($subject->site_id)?$subject->site_name:'SiteName'}}</td>
                                <td>{{!empty($subject->disease_cohort->name)?$subject->disease_cohort->name:'Not Defined'}}</td>
                                <td>{{!empty($subject->study_eye)?$subject->study_eye:'Not Defined'}}</td>
                                <td>
                                    <div class="d-flex mt-3 mt-md-0 ml-auto">
                                        <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                    <span class="dropdown-item">
                                                        <a href="javascript:void(0)" id="edit-device" data-id="{{ $subject->id }}">
                                                            <i class="far fa-edit"></i>&nbsp; Edit </a>
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

    <div class="modal" tabindex="-1" role="dialog" id="createSubjects">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title" id="deviceCrudModal">Add Subject</h4>
                </div>
                <div  class="modal-body">
                <form action="{{route('subjects.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <input type="hidden" value="{{$study->id}}" name="study_id">
                    <input type="hidden" value="{{$study}}" name="user">
{{--

                    <input type="hidden" value="{{$currentStudy}}" name="study_id">
                    <input type="hidden" value="{{$currentStudy}}" name="user">
--}}
                        <div class="form-group row" style="margin-top: 10px;">
                            <label for="subject_id" class="col-md-2">Subject ID</label>
                            <div class="{!! ($errors->has('subject_id')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <input type="text" class="form-control" name="subject_id" value="{{old('subject_id')}}">
                                @error('subject_id')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                        </div>
                            <label for="study_short_name" class="col-md-2">Enrollment Date</label>
                            <div class="{!! ($errors->has('enrollment_date')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <input type="text" class="form-control" id="enrollment_date" name="enrollment_date" value="{{old('enrollment_date')}}">
                                @error('enrollment_date')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="site_id" class="col-md-2">Site</label>
                            <div class="{!! ($errors->has('site_id')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                <select name="site_id" class="custom-btn btn" style="width: 235px;">
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
                                <select name="study_eye" class="custom-btn btn" style="width: 235px;" >
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
                        <div class="{!! ($errors->has('disease_cohort')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                            <select name="disease_cohort" class="custom-btn btn" style="width: 235px;">
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
                        <button class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn custom-btn blue-color"><i class="fa fa-save blue-color"></i> Save</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
