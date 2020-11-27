<div class="modal fade" tabindex="-1" role="dialog" id="inviteuser">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title">Invite User</p>
            </div>
            <form action="{{route('process_invite')}}" enctype="multipart/form-data" method="POST" id="inviteuser_form_1">
                @csrf
                <div class="modal-body">
                    <p class="alert alert-danger inviteuser_error" style="display: none;"></p>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                            @csrf
                            <div class="form-group row" style="margin-top: 10px;">
                                <div class="col-md-4">Email address</div>
                                <div class="col-md-8">
                                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                </div>
                            </div>
                            <div class="form-group row" style="margin-top: 10px;">
                                <div class="col-md-4">Role </div>
                                <div class="col-md-8">
                                    <select name="roles" id="roles" class="form-control">
                                        <option value="">-- Select Role --</option>
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
                        <button type="submit" class="btn btn-success">Send Invitation</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
