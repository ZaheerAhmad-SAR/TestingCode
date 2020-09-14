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
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#createRole">
                        <i class="fa fa-plus"></i> Add Role
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <th>User Role</th>
                                <th style="width: 5%;">Action</th>
                            </tr>
                            @foreach($roles as $role)
                                    <tr>
                                <td>{{ucfirst($role->name)}}</td>
                                <td>{{ucfirst($role->description)}}</td>
                                <td>
                                   <div class="d-flex mt-3 mt-md-0 ml-auto">
                                        <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                            <span class="dropdown-item"><a href="{!! route('roles.edit',encrypt($role->id)) !!}"><i class="far fa-edit"></i>&nbsp; Edit </a></span>
                                        </div>
                                    </div>
                                </td>
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
                                <div class="form-group row" style="margin-top: 10px;">
                                    <label for="Name" class="col-sm-3 col-form-label">Name</label>
                                    <div class="{!! ($errors->has('name')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                        <input type="text" class="form-control" name="name" value="{{old('name')}}">
                                        @error('name')
                                        <span class="text-danger small">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Description" class="col-sm-3 col-form-label">Description</label>
                                    <div class="{!! ($errors->has('description')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                        <textarea class="form-control" name="description" value="{{old('description')}}"></textarea>
                                        @error('description')
                                        <span class="text-danger small">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-StudyActivities" role="tabpanel" aria-labelledby="nav-Validation-tab">
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
                                        <input type="radio" name="q_c" id="certification_no" value="no"> No
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Name" class="col-sm-3">Finance</label>
                                    <div class="col-md-3">
                                        <input type="radio" name="q_c" id="finance_yes" value="yes" checked="checked"> Yes
                                        <input type="radio" name="q_c" id="finance_no" value="no"> No
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
<link rel="stylesheet" href="{{ asset('public/dist/vendors/datatable/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/datatable/buttons/css/buttons.bootstrap4.min.css') }}">
@stop
@section('script')
<script src="{{ asset('public/dist/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('public/dist/js/datatable.script.js') }}"></script>

@stop
