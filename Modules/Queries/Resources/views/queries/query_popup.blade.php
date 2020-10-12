@php

    $users_for_queries =  \App\User::where('id','!=',\auth()->user()->id)->get();
    $roles_for_queries =  \Modules\UserRoles\Entities\Role::where('role_type','=','study_role')->orderBY('name','asc')->get();
 @endphp

<!-- queries modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="queries-modal" aria-labelledby="exampleModalQueries" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title">Add a Queries</p>
            </div>
            <form id="queriesForm" name="queriesForm">
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                            @csrf
                            <label>Current query status: &nbsp; &nbsp;<i style="color: red;" class="fas fa-question-circle"></i> &nbsp;New</label>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-4 col-form-label">Queries Assigned to:</label>
                                <div class="col-sm-8">
                                    <label class="radio-inline  col-form-label"><input type="radio" id="assignQueries" name="assignQueries" value="users"> Users</label> &nbsp;
                                    <label class="radio-inline  col-form-label"><input type="radio" id="assignQueries" name="assignQueries" value="roles" > Roles</label>
                                </div>
                            </div>
                            <div class="form-group row usersInput" style="display: none;">
                                <label for="Name" class="col-sm-2 col-form-label">Users:</label>
                                <div class="col-sm-10">
                                    <select class="form-control multieSelectDropDown" multiple data-allow-clear="1" name="users" id="users">
                                        @foreach($users_for_queries as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row rolesInput" style="display: none;">
                                <label for="Name" class="col-sm-2 col-form-label">Roles:</label>
                                <div class="col-sm-10">
                                    @foreach($roles_for_queries as $role)
                                        <label class="checked-inline  col-form-label"><input type="checkbox" class="ads_Checkbox" id="roles" name="roles" value="{{$role->id}}"> {{$role->name}} </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group row remarksInput" style="display:none;">
                                <label for="Name" class="col-sm-2 col-form-label">Remarks</label>
                                <div class="col-sm-10">
                                    <textarea class="summernote" name="remarks" cols="2" rows="1" id="remarks"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="module_id" id="module_id" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="addqueries-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="button" class="btn btn-outline-primary" id="savequeries"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('styles')
<!-- Queries Model style sheet start -->
<link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2.min.css") }}"/>
<link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2-bootstrap.min.css") }}"/>
<link rel="stylesheet" href="{{ asset("dist/vendors/summernote/summernote-bs4.css") }}">
<!-- Queries Model style sheet end -->
@endpush
@push('script')
<!-- Queries Model scripts start -->
<script src="{{ asset("dist/vendors/select2/js/select2.full.min.js") }}"></script>
<script src="{{ asset("dist/js/select2.script.js") }}"></script>
<script src="{{ asset("dist/vendors/summernote/summernote-bs4.js") }}"></script>
<script src="{{ asset("dist/js/summernote.script.js") }}"></script>

<!-- Queries Model scripts end -->


<script type="text/javascript">

    $('.create-new-queries').click(function () {
        var id = $(this).attr('data-id');
        $('#module_id').val(id);
        $('#queriesForm').trigger("reset");
        $('#queries-modal').modal('show');
        //$('#queries-modal').html("Add Queries");

    });

    $(document).ready(function (){
        $('input[type="radio"]').click(function (){
            if ($(this).attr("value")=="users")
            {
                $("input:checkbox").prop('checked',false);
                $(".usersInput").show();
                $(".remarksInput").show();
                $(".rolesInput").hide();
                $("#remarks").summernote("reset");

            }
            if ($(this).attr("value")=="roles")
            {
                $('.usersInput').css('display','none');
                $(".rolesInput").show();
                $(".remarksInput").show();
                $("#remarks").summernote("reset");
                $(".select2-selection__choice").remove();
            }
        });
    });

    $('#savequeries').click(function (){
        var queryAssignedTo = $("input[name='assignQueries']:checked").val();
        var module_id = $('#module_id').val();
        //console.log(module_id);
        if (queryAssignedTo == 'users')
        {
            var assignedUsers = $('#users').val();
            var assignedRemarks = $('#remarks').val();
        }
        if(queryAssignedTo =='roles')
        {
            var assignedRoles = $('.ads_Checkbox:checked').map(function () {
                return this.value;
            }).get();
            var assignedRemarks = $('#remarks').val();

        }
        $.ajax({
            url:"{{route('queries.store')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'module_id'      :module_id,
                'assignedUsers'  :assignedUsers,
                'assignedRemarks':assignedRemarks,
                'queryAssignedTo':queryAssignedTo,
                'assignedRoles'  :assignedRoles,
            },
            success: function(response)
            {
                var module_id = response[0].module_id;
                console.log(module_id);
                $("#queriesForm")[0].reset();
                $("#remarks").summernote("reset");
                $('#OptionsGroupEditForm').modal('hide');
                window.setTimeout(function () {
                    window.location.reload();
                }, 100);
            }
        });


    });

</script>
@endpush
