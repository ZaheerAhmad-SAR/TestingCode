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
                                <label for="Name" class="col-sm-4 col-form-label">Users:</label>
                                <div class="col-sm-8">
                                    <select class="form-control multieSelectDropDown" multiple data-allow-clear="1" name="users" id="users">
                                        @foreach($users_for_queries as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row rolesInput" style="display: none;">
                                <label for="Name" class="col-sm-4 col-form-label">Roles:</label>
                                <div class="col-sm-8">
                                    @foreach($roles_for_queries as $role)
                                        <label class="checked-inline  col-form-label"><input type="checkbox" class="ads_Checkbox" id="roles" name="roles" value="{{$role->id}}"> {{$role->name}} </label>
                                    @endforeach
                                </div>
                            </div>
                            {{--                                <div class="form-group row statusInput">--}}
                            {{--                                    <label for="Name" class="col-sm-4 col-form-label">Change status to:</label>--}}
                            {{--                                    <div class="col-sm-8">--}}
                            {{--                                        <select class="form-control" name="queries_status" id="queries_status">--}}
                            {{--                                            <option value="">Open</option>--}}
                            {{--                                            <option value="">Unconfirmed</option>--}}
                            {{--                                            <option value="">Confirmed</option>--}}
                            {{--                                            <option value="">Resolved</option>--}}
                            {{--                                            <option value="">Closed</option>--}}
                            {{--                                        </select>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            <div class="form-group row remarksInput" style="display:none;">
                                <label for="Name" class="col-sm-4 col-form-label">Remarks</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="remarks" rows="2" id="remarks"></textarea>
                                </div>
                            </div>
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
<!-- Queries Model style sheet end -->
@endpush
@push('script')
<!-- Queries Model scripts start -->
<script src="{{ asset("dist/vendors/select2/js/select2.full.min.js") }}"></script>
<script src="{{ asset("dist/js/select2.script.js") }}"></script>

<!-- Queries Model scripts end -->


<script type="text/javascript">

    $('#create-new-queries').click(function () {
        // $('#btn-save').val("create-study");
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
                $('#remarks').val('');

            }
            if ($(this).attr("value")=="roles")
            {
                $('.usersInput').css('display','none');
                $(".rolesInput").show();
                $(".remarksInput").show();
                $('#remarks').val('');
            }
        });
    });

    $('#savequeries').click(function (){
        var queryAssignedTo = $("input[name='assignQueries']:checked").val();
        if (queryAssignedTo == 'users')
        {
            var assignedUsers = $('#users').val();
            var assignedRemarks = $('#remarks').val();
            console.log(assignedUsers);
            console.log(assignedRemarks);
        }
        if(queryAssignedTo =='roles')
        {
            var assignedRoles = $("input[name='roles']:checked").map(function() {
                return this.value;
            }).get().join(',');
            var assignedRemarks = $('#remarks').val();
            console.log(assignedRoles);
            console.log(assignedRemarks);
        }
    });

</script>
@endpush
