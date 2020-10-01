@extends('layouts.home')
@section('title')
    <title> Roles | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
 <div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">Roles Detail</h4></div>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                   @if(hasPermission(auth()->user(),'roles.create'))
                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#createRole">
                            <i class="fa fa-plus"></i> Add Role
                        </button>
                       @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <h6>System Roles</h6>
                            <table class="table table-bordered">
                            <tr>
                                <th style="width: 45%">Name</th>
                                <th style="width: 45%">Description</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                            @foreach($system_roles as $role)
                                    <tr>
                                <td>{{ucfirst($role->name)}}</td>
                                <td>{{ucfirst($role->description)}}</td>
                                @if(hasPermission(auth()->user(),'roles.edit'))
                                <td>
                                   <div class="d-flex mt-3 mt-md-0 ml-auto">
                                        <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                            <span class="dropdown-item">
                                                <a href="{!! route('roles.edit',encrypt($role->id)) !!}">
                                                    <i class="far fa-edit"></i>&nbsp; Edit
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </table>
                        <h6>Study Roles</h6>
                            <table class="table table-bordered">
                            <tr>
                                <th style="width: 45%">Name</th>
                                <th style="width: 45%">Description</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                            @foreach($study_roles as $role)
                                <tr>
                                    <td>{{ucfirst($role->name)}}</td>
                                    <td>{{ucfirst($role->description)}}</td>
                                    @if(hasPermission(auth()->user(),'roles.edit'))
                                        <td>
                                            <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                            <span class="dropdown-item">
                                                <a href="{!! route('roles.edit',encrypt($role->id)) !!}">
                                                    <i class="far fa-edit"></i>&nbsp; Edit
                                                </a>
                                            </span>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

        </div>
</div>
    <!-- END: Card DATA-->
</div>
<!-- modal code  -->
    <div class="modal fade" tabindex="-1" role="dialog" id="createRole">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Add New Role</p>
                </div>
                <form action="{{route('roles.store')}}" enctype="multipart/form-data" method="POST">
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
                                <div class="form-group row" style="margin-top: 20px;">
                                    <label for="Name" class="col-sm-3 col-form-label">Name</label>
                                    <div class="{!! ($errors->has('name')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                        <input type="text" class="form-control" id="role_name" name="name" value="{{old('name')}}" required>
                                        @error('name')
                                        <span class="text-danger small">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Description" class="col-sm-3 col-form-label">Description</label>
                                    <div class="{!! ($errors->has('description')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                        <textarea class="form-control" name="description" id="description" value="{{old('description')}}" required></textarea>
                                        @error('description')
                                        <span class="text-danger small">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">Role Type <sup>*</sup></div>
                                    <div class="form-group col-md-9">
                                        <input type="radio" name="role_type_name" id="for_system_user" value="system_role" checked> System Role &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="role_type_name" id="for_study_user" value="study_role"> Study Role
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-StudyActivities" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                <div class="form-group row" style="margin-top: 15px;">
                                    <div class="col-md-3">
                                        <label for="Name" style="padding-left: 11px">Adjudication </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="checkbox" name="adjudication_add" id="adjudication_add"> Add
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="adjudication_edit" id="adjudication_edit"> Edit
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="adjudication_view" id="adjudication_view"> View
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="adjudication_delete" id="adjudication_delete"> Delete
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-top: 15px;">
                                    <div class="col-md-3">
                                        <label for="Name" style="padding-left: 11px">Eligibility</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="checkbox" name="eligibility_add" id="eligibility_add"> Add
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="eligibility_edit" id="eligibility_edit"> Edit
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="eligibility_view" id="eligibility_view"> View
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="eligibility_delete" id="eligibility_delete"> Delete
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-top: 15px;">
                                    <div class="col-md-3">
                                        <label for="Name" style="padding-left: 11px">Grading </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="checkbox" name="grading_add" id="grading_add"> Add
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="grading_edit" id="grading_edit"> Edit
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="grading_view" id="grading_view"> View
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="grading_delete" id="grading_delete"> Delete
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-top: 15px;">
                                    <div class="col-md-3">
                                        <label for="Name" style="padding-left: 11px">Quality Control </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="checkbox" name="qualityControl_add" id="qualityControl_add"> Add
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="qualityControl_edit" id="qualityControl_edit"> Edit
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="qualityControl_view" id="qualityControl_view"> View
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="qualityControl_delete" id="qualityControl_delete"> Delete
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-top: 15px;">
                                    <div class="col-md-3">
                                        <label for="Name" style="padding-left: 11px">Queries</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="checkbox" name="queries_add" id="queries_add"> Add
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="queries_edit" id="queries_edit"> Edit
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="queries_view" id="queries_view"> View
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="queries_delete" id="queries_delete"> Delete
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                            </div>
                            <div class="tab-pane fade" id="nav-ManagementActivities" role="tabpanel">
                                    <div class="form-group row" style="margin-top: 10px;">
                                        <div class="col-md-3">
                                            <label for="Name">System Tools</label>
                                        </div>
                                        <div class="col-md-3">
                                                <input type="checkbox" name="system_tools" id="system_tools" > Allow Permission
                                            </div>
                                        <div class="col-md-3">
                                            <label for="Name">Study Tools</label>
                                        </div>
                                        <div class="col-md-3">
                                                <input type="checkbox" name="study_tools" id="study_tools" > Allow Permission
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label for="Name">Data Management</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="checkbox" name="management" id="management"> Allow Permission
                                        </div>
                                        <div class="col-md-3">
                                            <label for="Name">Activity Log</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="checkbox" name="activity_log" id="activity_log"> Allow Permission
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label>Certification</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="checkbox" name="certification" id="certification"> Allow Permission
                                        </div>
                                        <div class="col-md-3">
                                        <label for="Name">Finance</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="checkbox" name="finance" id="finance"> Allow Permission
                                        </div>
                                    </div>
                                    </div>
                        </div>
                            <div class="modal-footer">
                                <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
                            </div>
                        </div>
                    @if(count($errors))
                        <div class="form-group">
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
<!-- modal code  -->
@endsection
@section('styles')
<style>
    div.dt-buttons{
        display: none;
    }
</style>
<link rel="stylesheet" href="{{ asset('dist/vendors/datatable/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/vendors/datatable/buttons/css/buttons.bootstrap4.min.css') }}">
@stop
@section('script')
@stop
