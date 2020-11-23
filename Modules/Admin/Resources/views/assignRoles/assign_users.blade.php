<div class="form-group row">
    <div class="col-md-5">
        <label>All Admins</label>
    </div>
    <div class="col-md-2">

    </div>
    <div class="col-md-5">
        <label>Assigned Admins</label>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-5">
        <select name="unassigned_users[]" id="select_users" class="searchable form-control" multiple="multiple">
            @foreach($users as $user)
            <option value="{{$user->id}}">{{$user->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="button" id="select_users_rightSelected" class="btn btn-default btn-block"><i class="fas fa-caret-right"></i></button>
        <button type="button" id="select_users_leftSelected" class="btn btn-default btn-block"><i class="fas fa-caret-left"></i></button>
    </div>
    <div class="col-md-5">
        <select name="users[]" id="select_users_to" class="form-control" multiple="multiple">
            @foreach($assigned_users as $assigned_user)
            <option value="{{$assigned_user->id}}">{{$assigned_user->name}}</option>
            @endforeach
        </select>
    </div>
</div>
