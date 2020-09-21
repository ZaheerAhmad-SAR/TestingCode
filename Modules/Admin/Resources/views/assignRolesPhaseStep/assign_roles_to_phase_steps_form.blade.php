<form id="assign_phase_steps_roles_form" onsubmit="return submitAssignRolesToPhaseStepsForm(event);">
    <div id="exTab1">
        <div class="tab-content clearfix">
            @csrf
            <input type="hidden" id="phase_id" name="phase_id" value="{{$phase->id}}">
            <input type="hidden" id="step_id" name="step_id" value="{{$phaseStep->step_id}}">
            @foreach($roles as $role)
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="roles[]" id="role_{{$role->id}}" value="{{$role->id}}" {{ (in_array($role->id, $phaseStepRolesIdsArray))? 'checked':'' }}>
                    <label class="custom-control-label" for="role_{{$role->id}}">{{$role->name}}</label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-outline-danger" data-dismiss="modal" id="assign_phase_steps_roles-close">
            <i class="fa fa-window-close" aria-hidden="true"></i> Close
        </button>
        <button type="submit" class="btn btn-outline-primary" id="assignRolesToPhaseStepsBtn">
            <i class="fa fa-save"></i> Assign Roles
        </button>
    </div>
</form>