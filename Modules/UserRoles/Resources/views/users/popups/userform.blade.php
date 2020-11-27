<div class="modal-body">
    <p class="alert alert-danger user-store-error" style="display: none;"></p>
    <nav>
        <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic Info</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Modalities" role="tab" aria-controls="nav-profile" aria-selected="false">Roles</a>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
            @csrf
            <div class="form-group row" style="margin-top: 10px;">
                <label for="Name" class="col-md-3">Name</label>
                <div class="{!! ($errors->has('name')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                    <input type="text" class="form-control" required="required" id="name" name="name" value="{{old('name')}}">
                    @error('name')
                    <span class="text-danger small">{{ $message }} </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="Email" class="col-md-3">Email</label>
                <div class="{!! ($errors->has('email')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                    <input type="email" class="form-control" name="email" id="email" required="required" value="{{old('email')}}"> @error('email')
                    <span class="text-danger small"> {{ $message }} </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-md-3">Password</label>
                <div class="{!! ($errors->has('password')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                    <input type="password" class="form-control" required="required" id="password" name="password" value="{{old('password')}}">
                    @error('password')
                    <span class="text-danger small"> {{ $message }} </span>
                    @enderror
                    <p id="passwordHelpBlock" class="form-text text-muted">
                        Your password must be 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.
                    </p>
                </div>

            </div>
            <div class="form-group row">
                <label for="C-Password" class="col-md-3">Confirm Password</label>
                <div class="{!! ($errors->has('password_confirmation')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                    <input type="password" class="form-control" required="required" id="password_confirmation" name="password_confirmation" value="{{old('password_confirmation')}}">
                    @error('password_confirmation')
                    <span class="text-danger small">{{ $message }} </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-Modalities" role="tabpanel" aria-labelledby="nav-Validation-tab">
            @include('admin::assignRoles.assign_roles', ['roles'=>$roles, 'assigned_roles'=>[], 'errors'=>$errors ])
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
    @if(hasPermission(auth()->user(),'users.store'))
        <button  class="btn btn-outline-primary" id="userSubmit">
            <i class="fa fa-save"></i>  Save changes</button>
    @endif
</div>
