<div class="modal fade" tabindex="-1" role="dialog" id="assignUser">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <p class="modal-title">Add User</p>
            </div>
            <form action="{{route('users.assignUsers')}}" enctype="multipart/form-data" method="POST" id="assignuser_form_1">
                <div class="modal-body">
                    <p class="alert alert-danger assignuser_error" style="display: none;"></p>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                            @csrf
                            <div class="form-group row" style="margin-top: 10px;">
                                <div class="col-md-4">Select User</div>
                                <div class="col-md-8">
                                    <select class="form-control dropdown" name="study_user">
                                        <option value=""> Select User</option>
                                        @foreach($studyusers as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" style="margin-top: 10px;">
                                <div class="col-md-4">Select Role</div>
                                <div class="col-md-8">
                                    <select class="form-control dropdown" name="user_role">
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                    @if(hasPermission(auth()->user(),'users.store'))
                        <button type="submit" class="btn btn-outline-primary" id="btn-save" value="create"><i class="fa fa-save"></i> Save Changes</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
