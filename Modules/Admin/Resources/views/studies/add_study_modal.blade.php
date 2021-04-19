<div class="modal fade" id="study-crud-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="studyCrudModal"></h4>
            </div>
            <div class="modal-body">
                <form action="{{route('studies.store')}}" name="studyForm" id="studyForm" class="form-horizontal" method="POST">
                    <input name="_method" id="_method" type="hidden" value="POST">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Please fill all required fields!.
                            <br/>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <input type="hidden" name="study_id" id="study_id">
                    <nav>
                        <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic Info</a>
                            <a class="nav-item nav-link" dusk="nav-Disease" id="nav-profile-tab" data-toggle="tab" href="#nav-Disease" role="tab" aria-controls="nav-Disease" aria-selected="false">Disease Cohort</a>
                            <a class="nav-item nav-link" dusk="nav-users" id="nav-profile-tab" data-toggle="tab" href="#nav-users" role="tab" aria-controls="nav-users " aria-selected="false">Study Admin</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        {{-- Basic Info Tab --}}
                        <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                            @csrf
                            <div class="form-group row" style="margin-top: 10px;">
                                <label for="study_title" class="col-md-2">Title</label>
                                <div class="{!! ($errors->has('study_title')) ?'form-group col-md-10 has-error':'form-group col-md-10' !!}">
                                    <input type="hidden" name="study_id" id="studyID" value="">
                                    <input type="text" class="form-control" dusk="study_title" id="study_title" name="study_title" value="{{old('study_title')}}" required> @error('study_title')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6"></div>
                                <div class="col-md-2"></div>
                                <div class="col-md-4">
                                    <span class="space_msg" style="font-size: 9px;color: red;"></span>
                                </div>
                                <label for="study_short_name" class="col-md-2">Short Name </label>
                                <div class="{!! ($errors->has('study_short_name')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="text" class="form-control" dusk="study_short_name" id="study_short_name" name="study_short_name" value="{{old('study_short_name')}}" required>
                                    @error('study_short_name')
                                    <span class="text-danger small">{{ $message }} </span>
                                    @enderror
                                </div>

                                <label for="study_code" class="col-md-2">Study Code</label>

                                <div class="{!! ($errors->has('study_code')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="text" class="form-control variable_name_ques" dusk="study_code" id="study_code" name="study_code" value="{{old('study_code')}}" required>
                                    @error('study_code')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="protocol_number" class="col-md-2">Protocol Number</label>
                                <div class="{!! ($errors->has('protocol_number')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="text" class="form-control" dusk="protocol_number" id="protocol_number" name="protocol_number" value="{{old('protocol_number')}}" required>
                                    @error('protocol_number')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                                <label for="trial_registry_id" class="col-md-2">Trial Registry ID</label>
                                <div class="{!! ($errors->has('trial_registry_id')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="text" class="form-control" dusk="trial_registry_id" id="trial_registry_id" name="trial_registry_id" value="{{old('trial_registry_id')}}" required>
                                    @error('trial_registry_id')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="study_sponsor" class="col-md-2">Study Sponsor</label>
                                <div class="{!! ($errors->has('study_sponsor')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="text" class="form-control" dusk="study_sponsor" id="study_sponsor" name="study_sponsor" value="{{old('study_sponsor')}}" required>
                                    @error('study_sponsor')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                                <label for="start_date" class="col-md-2">Start Date</label>
                                <div class="{!! ($errors->has('start_date')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="date" class="form-control" dusk="start_date" id="start_date" name="start_date" value="{{(\Carbon\Carbon::today())}}" required>
                                    @error('start_date')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="end_date" class="col-md-2">End Date</label>
                                <div class="{!! ($errors->has('end_date')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="date" class="form-control"  dusk="end_date" id="end_date" name="end_date" value="{{old('end_date')}}" required>
                                    @error('end_date')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                                <label for="description" class="col-md-2">Description</label>
                                <div class="{!! ($errors->has('description')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="text" class="form-control" dusk="description" id="description" name="description" value="{{old('description')}}" required>
                                    @error('description')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{--Disease tab --}}
                        <div class="tab-pane fade" id="nav-Disease" role="tabpanel" aria-labelledby="nav-Validation-tab">
                            <div class="form-group row" style="margin-top: 10px;">
                                <div class="col-md-2">
                                    <label for="disease_cohort">Disease Cohort</label>
                                </div>
                                <div class="col-md-6 appendfields">

                                </div>
                                <div class="col-md-4" style="text-align: right">
                                    @if(hasPermission(auth()->user(),'diseaseCohort.create'))
                                        <button class="btn btn-outline-primary add_field" dusk="add_field"><i class="fa fa-plus"></i> Add</button>
                                    @endif
                                    {{-- @if(hasPermission(auth()->user(),'diseaseCohort.create')) --}}
                                    {{-- @endif --}}
                                </div>
                            </div>
                        </div>

                        {{-- Assign Users --}}
                        <div class="tab-pane fade" id="nav-users" role="tabpanel" aria-labelledby="nav-Validation-tab">
                            @include('admin::assignRoles.assign_users', ['users'=>$users, 'assigned_users'=>[], 'errors'=>$errors ])
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            @if(hasPermission(auth()->user(),'studies.store'))
                                <button type="submit" class="btn btn-outline-primary" value="create" dusk="create-study-button"><i class="fa fa-save"></i> Save Changes</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
