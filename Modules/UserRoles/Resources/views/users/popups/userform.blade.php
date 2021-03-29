<div class="modal-header">
        <p class="modal-title">{{ $add_or_edit }} User</p>
    </div>
    <form action="{{ $route }}" enctype="multipart/form-data" method="POST" class="user-store-form"
        id="user-store-form-5">
        <input type="hidden" name="_method" value="{{ $method }}" />
        @csrf
        <div class="modal-body">
            <p class="alert alert-danger user-store-error" style="display: none;"></p>
            <nav>
                <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                    <a  class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab"
                        aria-controls="nav-home" aria-selected="true">Basic Info</a>
                    <a dusk="nav-roles" class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Modalities"
                        role="tab" aria-controls="nav-profile" aria-selected="false">Roles</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                    <div class="form-group row" style="margin-top: 10px;">
                        <label for="Name" class="col-md-3 showNameField">Name</label>
                        <input type="text" class="form-control col-md-8" required="required" id="name" name="name"
                            value="{{ $user->name }}" {{ $readOnly }} dusk="user-name">
                    </div>
                    <div class="form-group row">
                        <label for="Email" class="col-md-3">Email</label>
                        <input type="email" class="form-control col-md-8" name="email" id="email" required="required"
                            value="{{ $user->email }}" {{ $readOnly }} dusk="user-email">
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-3">Password</label>
                        <input type="password" autocomplete="off" class="form-control col-md-8" required="required" id="password"
                            name="password" {{ $readOnly }}>
                        <span class="col-md-3"></span>
                        <p id="passwordHelpBlock" class="form-text text-muted col-md-9">
                            Your password must be 8 characters long, should contain at-least 1 Uppercase, 1
                            Lowercase, 1 Numeric and 1 special character.
                        </p>
                    </div>
                    <div class="form-group row">
                        <label for="C-Password" class="col-md-3">Confirm Password</label>
                        <input type="password" autocomplete="off" class="form-control col-md-8" required="required"
                            id="password_confirmation" name="password_confirmation"
                            value="" {{ $readOnly }}>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-Modalities" role="tabpanel" aria-labelledby="nav-Validation-tab">
                    @include('admin::assignRoles.assign_roles', ['roles'=>$unassigned_roles,
                    'assigned_roles'=>$assigned_roles ])
                </div>
            </div>
        </div>
    </form>
    <div class="modal-footer">
        <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close"
                aria-hidden="true"></i> Close</button>
        @if (hasPermission(auth()->user(), 'users.store'))
            <button class="btn btn-outline-primary" onclick="{{ $submitFunction }}" dusk="add-user">
                <i class="fa fa-save"></i> Save changes</button>
        @endif
    </div>
