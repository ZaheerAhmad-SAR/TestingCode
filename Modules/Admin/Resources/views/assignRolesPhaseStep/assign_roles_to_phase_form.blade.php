<span class="dropdown-item assign_study_structures_roles" data-phase-id="{{$phase->id}}"><i class="far fa-user"></i>&nbsp; Assign Roles</span>
<!-- assign role to phase modle -->
<div class="modal fade" tabindex="-1" role="dialog" id="assign_study_structures_roles" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title">Assign Roles</p>
            </div>
            <div class="modal-body" id="assignRolesToPhaseMainDiv"></div>
        </div>
    </div>
</div>
<!--  -->


<form id="assign_study_structures_roles_form" onsubmit="return submitAssignRolesToPhaseForm(event);">
    <div id="exTab1">
        <div class="tab-content clearfix">
            @csrf
            <input type="hidden" id="phase_id" name="phase_id" value="{{$phase->id}}">
            @foreach($roles as $role)
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="roles[]" id="role_{{$role->id}}" value="{{$role->id}}" {{ (in_array($role->id, $phaseRolesIdsArray))? 'checked':'' }}>
                    <label class="custom-control-label" for="role_{{$role->id}}">{{$role->name}}</label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-outline-danger" data-dismiss="modal" id="assign_study_structures_roles-close">
            <i class="fa fa-window-close" aria-hidden="true"></i> Close
        </button>
        <button type="submit" class="btn btn-outline-primary" id="assignRolesToPhaseBtn">
            <i class="fa fa-save"></i> Assign Roles
        </button>
    </div>
</form>

@push('script')
<script>
function loadAssignRolesToPhaseForm(phase_id){
    $.ajax({
         url: "{{route('assignRolesPhaseStep.getAssignRolesToPhaseForm')}}",
         type: 'POST',
         data: {
            "_token": "{{ csrf_token() }}",
            'phase_id': phase_id
        },
        success: function(response){
            $('#assignRolesToPhaseMainDiv').empty();
            $("#assignRolesToPhaseMainDiv").html(response);
        }
    });
}
function submitAssignRolesToPhaseForm(e){
    e.preventDefault();
    $.ajax({
         url: "{{route('assignRolesPhaseStep.submitAssignRolesToPhaseForm')}}",
         type: 'POST',
         data: $( "#assign_study_structures_roles_form" ).serialize(),
        success: function(response){
            $('#assignRolesToPhaseMainDiv').empty();
            $("#assignRolesToPhaseMainDiv").html(response);
        }
    });

}
</script>
@endpush
