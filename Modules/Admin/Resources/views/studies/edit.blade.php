@extends('layouts.app')
@section('title')
    <title> Update Study | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <form action="{{route('studies.update',$study->id)}}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h2>Update Study</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="{!! ($errors->has('study_short_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Study Short Name</label>
                                    <input type="text" class="form-control" name="study_short_name" value="{{$study->study_short_name}}">
                                    @error('study_short_name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('study_title')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Study Title</label>
                                    <input type="text" class="form-control" name="study_title" value="{{$study->study_title}}">
                                    @error('study_title')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('study_code')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Study Code</label>
                                    <input type="text" class="form-control" name="study_code" value="{{$study->study_code}}">
                                    @error('study_code')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('protocol_number')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Protocol Number</label>
                                    <input type="text" class="form-control" name="protocol_number" value="{{$study->protocol_number}}">
                                    @error('protocol_number')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('trial_registry_id')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Trial Registry ID</label>
                                    <input type="text" class="form-control" name="trial_registry_id" value="{{$study->trial_registry_id}}">
                                    @error('trial_registry_id')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Start Date</label>
                                    <input type="date" class="form-control" name="start_date" value="{{$study->start_date}}">
                                    @error('name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>End Date</label>
                                    <input type="date" class="form-control" name="end_date" value="{{$study->end_date}}">
                                    @error('name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description">{{$study->description}}</textarea>
                                </div>
                                @error('description')
                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row radio">
                            <div class="col-md-3">
                                <div class="{!! ($errors->has('study_phase')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Study Phase</label>
                                    <br>
                                    <span><input type="radio" class="form-control" name="study_phase" value="0"> Phase 0</span>
                                    <br>
                                    <span><input type="radio" class="form-control" name="study_phase" value="1"> Phase 1</span>
                                    <br>
                                    <span><input type="radio" class="form-control" name="study_phase" value="2"> Phase 2</span>
                                    <br>
                                    <span><input type="radio" class="form-control" name="study_phase" value="3"> Phase 3</span>
                                    @error('study_phase')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <a href="{!! route('studies.index') !!}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal" tabindex="-1" role="dialog" id="createStudy">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="width: inherit; top: auto!important;">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="custom-modal-header gray-background color-black">
                    <p class="modal-title">Add New Study</p>
                </div>
                <form action="{{route('studies.store')}}" enctype="multipart/form-data" method="POST" id="add_study_2">
                    <div class="custom-modal-body">
                        <ul  class="nav nav-pills btn">
                            <li>
                                <a  href="#1a" data-toggle="tab" class="active">Info</a>
                            </li>
                            <li>
                                <a href="#2a" data-toggle="tab">Users</a>
                            </li>
                            <li>
                                <a href="#3a" data-toggle="tab">Sites</a>
                            </li>
                            <li>
                                <a  href="#4a" data-toggle="tab" class="">Disease Cohort</a>
                            </li>

                        </ul>
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                <div class="tab-pane active" id="1a">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="study_short_name" class="col-md-3">Short Name</label>
                                            <div class="{!! ($errors->has('study_short_name')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="study_short_name" value="{{old('study_short_name')}}">
                                                @error('study_short_name')
                                                <span class="text-danger small">{{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="study_title" class="col-md-3">Title</label>
                                            <div class="{!! ($errors->has('study_title')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="study_title" value="{{old('study_title')}}"> @error('email')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="study_code" class="col-md-3">Study Code</label>
                                            <div class="{!! ($errors->has('study_code')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="study_code" value="{{old('study_code')}}">
                                                @error('study_code')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="protocol_number" class="col-md-3">Protocol Numbersdffffffffffff</label>
                                            <div class="{!! ($errors->has('protocol_number')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="protocol_number" value="{{old('protocol_number')}}">
                                                @error('protocol_number')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="trial_registry_id" class="col-md-3">Trial Registry ID</label>
                                            <div class="{!! ($errors->has('trial_registry_id')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="trial_registry_id" value="{{old('trial_registry_id')}}">
                                                @error('trial_registry_id')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="study_sponsor" class="col-md-3">Study Sponsor</label>
                                            <div class="{!! ($errors->has('study_sponsor')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="study_sponsor" value="{{old('study_sponsor')}}">
                                                @error('study_sponsor')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="start_date" class="col-md-3">Start Date</label>
                                            <div class="{!! ($errors->has('start_date')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="date" class="form-control" name="start_date" value="{{old('start_date')}}">
                                                @error('start_date')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="end_date" class="col-md-3">End Date</label>
                                            <div class="{!! ($errors->has('end_date')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="date" class="form-control" name="end_date" value="{{old('end_date')}}">
                                                @error('end_date')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="description" class="col-md-3">Description</label>
                                            <div class="{!! ($errors->has('description')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="description" value="{{old('description')}}">
                                                @error('description')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="2a">
                                    @include('admin::assignRoles.assign_users', ['users'=>$users, 'assigned_users'=>[], 'errors'=>$errors ])
                                </div>
                                <div class="tab-pane" id="3a">
                                    <div class="form-group">
                                        <div class="{!! ($errors->has('sites')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                            <select class="searchable" id="select-sites" multiple="multiple" name="sites[]">
                                                @foreach($sites as $site)
                                                    {{$site}}
                                                    <option value="{{$site->id}}">{{$site->site_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('sites')
                                        <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="tab-pane" id="4a">
                                    <div class="row field_wrapper">
                                        <div class="col-md-6">
                                            <label for="disease_cohort" class="col-md-3">Disease Cohort</label>
                                            <div class="{!! ($errors->has('disease_cohort')) ?'form-group col-md-6 has-error ':'form-group col-md-6' !!}">
                                                <input type="text" class="form-control" id="disease_cohort" name="disease_cohort[]" value="{{old('disease_cohort')}}">
                                                @error('disease_cohort')
                                                <span class="text-danger small">{{ $message }} </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <a href="javascript:void(0);" class="add_button" title="Add field"> <i class="fa fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
                                        <button type="submit" class="btn custom-btn blue-color"><i class="fa fa-save blue-color"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script type="text/javascript">
    $('#study_add_2').submit(function(e){
        $('#select_users_to option').prop('selected', true);
    });
        $(document).ready(function() {
		        $('#select_users').multiselect({
                    search: {
                        left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                        right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                    },
                    fireSearch: function(value) {
                        return value.length > 1;
                    }
                });
	        });
        </script>
        @endsection
