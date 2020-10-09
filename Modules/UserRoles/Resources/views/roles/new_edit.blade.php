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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Update Role</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/roles')}}">Role</a></li>
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
                                        <div class="form-group row" style="margin-top: 20px;">
                                            <label for="Name" class="col-sm-3 col-form-label">Name</label>
                                            <div class="{!! ($errors->has('name')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                                <input type="text" class="form-control" name="name" value="{{$role->name}}">
                                                @error('name')
                                                <span class="text-danger small">{{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Description" class="col-sm-3 col-form-label">Description</label>
                                            <div class="{!! ($errors->has('description')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                                <input type="text" class="form-control" name="description" value="{{$role->description}}">
                                                @error('description')
                                                <span class="text-danger small">{{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3">Role Type <sup>*</sup></div>
                                            <div class="form-group col-md-9">
                                                <input type="radio" name="role_type" value="system_role"
                                                @if($role->role_type == 'system_role') checked="checked" @endif> System Role &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="role_type" value="study_role"
                                                       @if($role->role_type == 'study_role') checked="checked" @endif> Study Role
                                            </div>
                                        </div>                                    </div>

                                    <div class="tab-pane fade" id="nav-StudyActivities" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                        <div class="form-group row" style="margin-top: 15px;">
                                            <div class="col-md-3">
                                                <label for="Name" style="padding-left: 11px">Dashboard </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="dashboard_add" id="dashboard_add" checked> Add
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="dashboard_edit" id="dashboard_edit" checked> Edit
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="dashboard_view" id="dashboard_view" checked> View
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin-top: 15px;">
                                            <div class="col-md-3">
                                                <label for="Name" style="padding-left: 11px">Adjudication </label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                            <input type="checkbox" name="adjudication_add" id="adjudication_add"
                                                            <?php if($permission->name == 'adjudication.store') {?> checked <?php } } ?> > Add
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="adjudication_edit" id="adjudication_edit"
                                                               <?php if($permission->name == 'adjudication.edit') {?> checked <?php } } ?> > Edit
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="adjudication_view" id="adjudication_view"
                                                               <?php if($permission->name == 'adjudication.index') {?> checked <?php } } ?> > View
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="adjudication_delete" id="adjudication_delete"
                                                               <?php if($permission->name == 'adjudication.destroy') {?> checked <?php } } ?>
                                                        > Delete
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
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="eligibility_add" id="eligibility_add"
                                                               <?php if($permission->name == 'eligibility.store') {?> checked <?php } } ?>
                                                            > Add
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="eligibility_edit" id="eligibility_edit"
                                                               <?php if($permission->name == 'eligibility.edit') {?> checked <?php } } ?>
                                                        > Edit
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="eligibility_view" id="eligibility_view"
                                                               <?php if($permission->name == 'eligibility.index') {?> checked <?php } } ?>
                                                        > View
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="eligibility_delete" id="eligibility_delete"
                                                               <?php if($permission->name == 'eligibility.destroy') {?> checked <?php } } ?>
                                                        > Delete
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
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="grading_add" id="grading_add"
                                                               <?php if($permission->name == 'grading.create') {?> checked <?php } } ?>
                                                        > Add
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="grading_edit" id="grading_edit"
                                                               <?php if($permission->name == 'grading.edit') {?> checked <?php } } ?>
                                                        > Edit
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="grading_view" id="grading_view"
                                                               <?php if($permission->name == 'grading.index') {?> checked <?php } } ?>
                                                        > View
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="grading_delete" id="grading_delete"
                                                               <?php if($permission->name == 'grading.destroy') {?> checked <?php } } ?>
                                                        > Delete
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
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="qualityControl_add" id="qualityControl_add"
                                                               <?php if($permission->name == 'qualitycontrol.create') {?> checked <?php } } ?>
                                                        > Add
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="qualityControl_edit" id="qualityControl_edit"
                                                               <?php if($permission->name == 'qualitycontrol.edit') {?> checked <?php } } ?>
                                                        > Edit
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="qualityControl_view" id="qualityControl_view"
                                                               <?php if($permission->name == 'qualitycontrol.index') {?> checked <?php } } ?>
                                                        > View
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="qualityControl_delete" id="qualityControl_delete"
                                                               <?php if($permission->name == 'qualitycontrol.destroy') {?> checked <?php } } ?>
                                                        > Delete
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
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="queries_add" id="queries_add"
                                                               <?php if($permission->name == 'queries.create') {?> checked <?php } } ?>
                                                        > Add
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="queries_edit" id="queries_edit"
                                                               <?php if($permission->name == 'queries.edit') {?> checked <?php } } ?>
                                                        > Edit
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="queries_view" id="queries_view"
                                                               <?php if($permission->name == 'queries.index') {?> checked <?php } } ?>
                                                        > View
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="queries_delete" id="queries_delete"
                                                               <?php if($permission->name == 'queries.destroy') {?> checked <?php } } ?>
                                                        > Delete
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
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="study_add" id="study_add"
                                                               <?php if($permission->name == 'studies.create') {?> checked <?php } } ?>
                                                        > Add
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="study_edit" id="study_edit"
                                                               <?php if($permission->name == 'studies.edit') {?> checked <?php } } ?>
                                                        > Edit
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="study_view" id="study_view"
                                                               <?php if($permission->name == 'studies.index') {?> checked <?php } } ?>
                                                        > View
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="study_delete" id="study_delete"
                                                               <?php if($permission->name == 'studies.destroy') {?> checked <?php } } ?>
                                                        > Delete
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
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="subjects_add" id="subjects_add"
                                                               <?php if($permission->name == 'subjects.create') {?> checked <?php } } ?>
                                                        > Add
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="subjects_edit" id="subjects_edit"
                                                               <?php if($permission->name == 'subjects.edit') {?> checked <?php } } ?>
                                                        > Edit
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="subjects_view" id="subjects_view"
                                                               <?php if($permission->name == 'subjects.index') {?> checked <?php } } ?>
                                                        > View
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php foreach($permissions as $permission) { ?>
                                                        <input type="checkbox" name="subjects_delete" id="subjects_delete"
                                                               <?php if($permission->name == 'subjects.destroy') {?> checked <?php } } ?>
                                                        > Delete
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
                                                <?php foreach($permissions as $permission) { ?>
                                                <input type="checkbox" name="system_tools" id="system_tools"
                                                       <?php if($permission->name == 'systemtools.index') {?> checked <?php } } ?>
                                                > Permission Allowed
                                            </div>
                                            <div class="col-md-3">
                                                <label for="Name">Study Tools</label>
                                            </div>
                                            <div class="col-md-3">
                                                <?php foreach($permissions as $permission) { ?>
                                                <input type="checkbox" name="study_tools" id="study_tools"
                                                       <?php if($permission->name == 'studytools.index') {?> checked <?php } } ?>
                                                > Permission Allowed
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="Name">Data Management</label>
                                            </div>
                                            <div class="col-md-3">
                                                <?php foreach($permissions as $permission) { ?>
                                                <input type="checkbox" name="management" id="management"
                                                       <?php if($permission->name == 'data_management.index') {?> checked <?php } } ?>
                                                > Permission Allowed
                                            </div>
                                            <div class="col-md-3">
                                                <label for="Name">Activity Log</label>
                                            </div>
                                            <div class="col-md-3">
                                                <?php foreach($permissions as $permission) { ?>
                                                <input type="checkbox" name="activity_log" id="activity_log"
                                                       <?php if($permission->name == 'activitylog.index') {?> checked <?php } } ?>
                                                > Permission Allowed
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label>Certification</label>
                                            </div>
                                            <div class="col-md-3">
                                                <?php foreach($permissions as $permission) { ?>
                                                <input type="checkbox" name="certification" id="certification"
                                                       <?php if($permission->name == 'certification.index') {?> checked <?php } } ?>
                                                > Permission Allowed
                                            </div>
                                            <div class="col-md-3">
                                                <label for="Name">Finance</label>
                                            </div>
                                            <div class="col-md-3">
                                                <?php foreach($permissions as $permission) { ?>
                                                <input type="checkbox" name="finance" id="finance"
                                                       <?php if($permission->name == 'finance.index') {?> checked <?php } } ?>
                                                > Permission Allowed
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                   {{-- <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>--}}
                                    <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
                                </div>
                            </div>
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
