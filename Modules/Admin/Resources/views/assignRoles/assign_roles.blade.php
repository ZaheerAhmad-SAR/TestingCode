<div class="form-group row" style="margin-top: 10px;">
    <label for="device_manufacturer" class="col-sm-3">Select Roles</label>
    <div class="{!! ($errors->has('roles')) ?'col-sm-9 has-error':'col-sm-9' !!}"></div>
    @error('roles')
    <span class="text-danger small">
        {{ $message }}
    </span>
    @enderror
</div>
<div class="form-group row">
    <div class="col-md-5">
        <select name="unassigned_roles[]" id="select_roles" class="searchable form-control" multiple="multiple">
            @foreach($roles as $role)
            <option value="{{$role->id}}">{{$role->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="button" id="select_roles_rightSelected" class="btn btn-default btn-block"><i class="fas fa-caret-right"></i></button>
        <button type="button" id="select_roles_leftSelected" class="btn btn-default btn-block"><i class="fas fa-caret-left"></i></button>
    </div>
    <div class="col-md-5">
        <select name="roles[]" id="select_roles_to" class="form-control" multiple="multiple">
            @foreach($assigned_roles as $assigned_role)
            <option value="{{$assigned_role->id}}">{{$assigned_role->name}}</option>
            @endforeach
        </select>
    </div>
</div>
