@extends('layouts.app')
@section('title')
    <title> View Study Details | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h4>Subjects Listing</h4>
                    </div>
                </div>
            </div>
            <ul  class="nav nav-pills btn">
            </ul>
            <div id="exTab1">
                <div class="tab-content clearfix">
                    <div class="tab-pane active" id="1a">
                        <div class="pull-right">
                            <button type="button" class="btn custom-btn blue-color" data-toggle="modal" data-target="#createSubjects">
                                <i class="fa fa-plus blue-color"></i> Add Subject
                            </button>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                            <th>Subject ID</th>
                            <th>Enrollment Date</th>
                            <th>Site</th>
                            <th>Study ID</th>
                            <th>Actions</th>
                            </thead>
                            <tbody>
                            @foreach($subjects as $subject)
                            <tr>
                                <td>{{$subject->subject_id}}</td>
                                <td>{{$subject->enrollment_date}}</td>
                                <td>{{!empty($subject->site_id)?$subject->site_name:'SiteName'}}</td>
                                <td>{{!empty($subject->study_id)?$subject->study_id:'Study ID'}}</td>
                                <td>
                                    <ul>
                                        <li><i class="fas fa-edit"></i>
                                        </li>
                                        <li><i class="fas fa-trash"></i>
                                        </li>
                                    </ul>
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
            <div class="modal-content" style="width: inherit; top: auto!important;">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="custom-modal-header gray-background color-black">
                    <p class="modal-title">Add New Subject</p>
                </div>
                <div  class="modal-body">
                <form action="{{route('subjects.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <input type="hidden" value="{{$study->id}}" name="study_id">
                    <input type="hidden" value="{{$study}}" name="user">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="subject_id" class="col-md-4">Subject ID</label>
                            <div class="{!! ($errors->has('subject_id')) ?'form-group col-md-8 has-error':'form-group col-md-8' !!}">
                                <input type="text" class="form-control" name="subject_id" value="{{old('subject_id')}}">
                                @error('subject_id')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="enrollment_date" class="col-md-4">Enrollment Date</label>
                            <div class="{!! ($errors->has('enrollment_date')) ?'form-group col-md-8 has-error':'form-group col-md-8' !!}">
                                <input type="date" class="form-control" name="enrollment_date" value="{{old('enrollment_date')}}">
                                @error('enrollment_date')
                                <span class="text-danger small">{{ $message }} </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="site_id" class="col-md-4">Site</label>
                            <div class="{!! ($errors->has('site_id')) ?'form-group col-md-8 has-error':'form-group col-md-8' !!}">
                                <select name="site_id" class="custom-btn btn">
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
                        </div>

                        <div class="col-md-6">
                            <label for="site_id" class="col-md-4">Disease Cohort</label>
                            <div class="{!! ($errors->has('disease_cohort')) ?'form-group col-md-8 has-error':'form-group col-md-8' !!}">
                                <select name="disease_cohort" class="custom-btn btn">
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

                        <div class="col-md-6">
                            <label for="study_eye" class="col-md-4">Study Eye</label>
                            <div class="{!! ($errors->has('study_eye')) ?'form-group col-md-8 has-error':'form-group col-md-8' !!}">
                            <select name="study_eye" class="custom-btn btn" >
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
