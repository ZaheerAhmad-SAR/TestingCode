@extends('layouts.home')

@section('title')
    <title> Update User Roles | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Update User Roles</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Role</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <form action="{{route('roles.update',$role->id)}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('PATCH')
                            <div class="modal-body">
                                <nav>
                                    <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic Info</a>
                                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-StudyActivities" role="tab" aria-controls="nav-profile" aria-selected="false">Study Activities</a>
                                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-ManagementActivities" role="tab" aria-controls="nav-profile" aria-selected="false">Management Activities</a>
                                    </div>
                                </nav>

                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                                        @csrf
                                        <div class="form-group row" style="margin-top: 10px;">
                                            <label for="Name" class="col-sm-3">Name</label>
                                            <div class="{!! ($errors->has('name')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                                <input type="text" class="form-control" name="name" value="{{$role->name}}">
                                                @error('name')
                                                <span class="text-danger small">
                                {{ $message }}
                            </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Description" class="col-sm-3">Description</label>
                                            <div class="{!! ($errors->has('description')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                                <textarea class="form-control" name="description">{{$role->description}}</textarea>
                                            </div>
                                            @error('description')
                                            <span class="text-danger small">
                                {{ $message }}
                        </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-StudyActivities" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                        <div class="form-group row" style="margin-top: 15px;">
                                            <div class="col-md-3">
                                                <label for="Name" style="padding-left: 11px">Studies </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="study_add" id="study_add"> Add

                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="study_edit" id="study_edit"> Edit
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="study_view" id="study_view"> View
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="study_delete" id="study_delete"> Delete
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin-top: 15px;">
                                            <div class="col-md-3">
                                                <label for="Name" style="padding-left: 11px">Subjects </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="subjects_add" id="subjects_add"> Add
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="subjects_edit" id="subjects_edit"> Edit
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="subjects_view" id="subjects_view"> View
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="subjects_delete" id="subjects_delete"> Delete
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin-top: 15px;">
                                            <div class="col-md-3">
                                                <label for="Name" style="padding-left: 11px">Sites </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row" >
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="sites_add" id="sites_add"> Add
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="sites_edit" id="sites_edit"> Edit
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="sites_view" id="sites_view"> View
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="sites_delete" id="sites_delete"> Delete
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin-top: 15px;">
                                            <div class="col-md-3">
                                                <label for="Name" style="padding-left: 11px">Devices </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="devices_add" id="devices_add"> Add
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="devices_edit" id="devices_edit"> Edit
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="devices_view" id="devices_view"> View
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="devices_delete" id="devices_delete"> Delete
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="nav-StudyActiviti" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                        <div class="form-group row"style='padding:5px;'>
                                            <div class="col-sm-3">
                                                <label>Study Tools</label>
                                            </div>
                                            @foreach ($permissions as $permission)
                                                <div class="col-sm-3">
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" name="permission[]" value="{{ $permission->id }}">{{ $permission->name }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @error('permission')
                                            <span class="text-danger small">
                                            {{ $message }}
                                        </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-ManagementActivities" role="tabpanel">
                                        <div class="form-group row" style="margin-top: 10px;">
                                            <label for="Name" class="col-sm-3">System Tools</label>
                                            <div class="col-md-3">
                                                <input type="radio" name="system_tools" id="system_tool_yes" value="yes" checked="checked"> Yes
                                                <input type="radio" name="system_tools" id="system_tool_no" value="no"> No
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Name" class="col-sm-3">Study Tools</label>
                                            <div class="col-md-3">
                                                <input type="radio" name="study_tools" id="study_tool_yes" value="yes" checked="checked"> Yes
                                                <input type="radio" name="study_tools" id="study_tool_no" value="no"> No
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Name" class="col-sm-3">Data Management</label>
                                            <div class="col-md-3">
                                                <input type="radio" name="management" id="management_yes" value="yes" checked="checked"> Yes
                                                <input type="radio" name="management" id="management_no" value="no"> No
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Name" class="col-sm-3">Activity Log</label>
                                            <div class="col-md-3">
                                                <input type="radio" name="log" id="log_yes" value="yes" checked="checked"> Yes
                                                <input type="radio" name="log" id="log_no" value="no"> No
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Name" class="col-sm-3">Certification</label>
                                            <div class="col-md-3">
                                                <input type="radio" name="certification" id="certification_yes" value="yes" checked="checked"> Yes
                                                <input type="radio" name="certification" id="certification_no" value="no"> No
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Name" class="col-sm-3">Finance</label>
                                            <div class="col-md-3">
                                                <input type="radio" name="finance" id="finance_yes" value="yes" checked="checked"> Yes
                                                <input type="radio" name="finance" id="finance_no" value="no"> No
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                                    <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </form>
                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

@endsection
@section('styles')

@stop
@section('script')


@stop
