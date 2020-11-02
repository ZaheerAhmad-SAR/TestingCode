@php


   $roles_for_queries =  \Modules\UserRoles\Entities\Role::where('role_type','=','study_role')->orderBY('name','asc')->get();
 @endphp

<!-- queries modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="queries-modal" aria-labelledby="exampleModalQueries" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title">Add a Queries</p>
            </div>
            <form id="queriesForm" name="queriesForm" enctype="multipart/form-data" >
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                            @csrf
                            <label class="col-form-label">Status: <i style="color: red; margin-left: 87px;" class="fas fa-question-circle"></i> New</label>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-2 col-form-label">Assigned to:</label>
                                <div class="col-sm-10">
                                    <label class="radio-inline  col-form-label"><input type="radio" id="assignQueries" name="assignQueries" value="user"> Users</label> &nbsp;
                                    <label class="radio-inline  col-form-label"><input type="radio" id="assignQueries" name="assignQueries" value="role" > Roles</label>
                                </div>
                            </div>
                            <div class="form-group row usersInput" style="display: none;">
                                <label for="Name" class="col-sm-2 col-form-label">Users:</label>
                                <div class="col-sm-10" id="usersDropDown"></div>
                            </div>
                            <div class="form-group row querySubject" style="display: none;">
                                <label for="Name" class="col-sm-2 col-form-label">Subject:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="query_subject" minlength="6" maxlength="50" id="query_subject">
                                </div>
                            </div>

                            <div class="form-group row queryAttachment" style="display: none;">
                                <label for="Name" class="col-sm-2 col-form-label">Attachment:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="file" name="query_file"  id="query_file">
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
                                    <textarea class="form-control" name="remarks" id="remarks"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="module_id" id="module_id" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="addqueries-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" name="submit" class="btn btn-outline-primary" id="submit"><i class="fa fa-save"></i> Save Changes</button>
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

    $('.create-new-queries').click(function () {

        var study_id = $(this).attr('data-study_id');
        var subject_id = $(this).attr('data-subject_id');
        var study_structures_id = $(this).attr('data-study_structures_id');
        var phase_steps_id = $(this).attr('data-phase_steps_id');
        var section_id = $(this).attr('data-section_id');
        var question_id = $(this).attr('data-question_id');
        var field_id = $(this).attr('data-field_id');
        var form_type_id = $(this).attr('data-form_type_id');
        var module = $(this).attr('data-module');
        var modility_id = $(this).attr('data-modility_id');

        var study_id = $(this).attr('data-id');
        var moduleId = $('#module_id').val(study_id);
        $('#queries-modal').modal('show');
        loadQueryPopUpHtml(study_id);
    });

    function loadQueryPopUpHtml(study_id)
    {
        $.ajax({
            url:"{{route('queries.loadHtml')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'study_id'      :study_id,
            },
            success: function(response)
            {
                console.log(response);
                $('#usersDropDown').html(response);
            }
        });
    }
    $(document).ready(function (){
        $('input[type="radio"]').click(function (){
            if ($(this).attr("value")=="user")
            {
                $("input:checkbox").prop('checked',false);
                $(".usersInput").show('fast');
                $(".remarksInput").show('fast');
                $(".querySubject").show('fast');
                $(".queryAttachment").show('fast');
                $(".rolesInput").hide();
                //$("#remarks").summernote("reset");
            }
            if ($(this).attr("value")=="role")
            {
                $('.usersInput').css('display','none');
                $('.querySubject').css('display','');
                $(".rolesInput").css('display','');
                $(".queryAttachment").css('display','');
                $(".remarksInput").css('display','');
                //$("#remarks").summernote("reset");
                $(".select2-selection__choice").remove();
            }
        });
    });

    $("#queriesForm").on('submit', function(e){
        e.preventDefault();
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        var queryAssignedTo = $("input[name='assignQueries']:checked").val();
        var module_id       = $('#module_id').val();
        var assignedRemarks = $('#remarks').val();
        var query_url       =  document.URL;
        var documentUrl     = query_url.split('/');
        var querySection    = documentUrl.pop() || documentUrl.pop();
        var query_subject   = $("#query_subject").val();
        if (queryAssignedTo == 'user')
        {
            var assignedUsers = $('#users').val();
            console.log(assignedUsers)
        }
        if(queryAssignedTo =='role')
        {
            var assignedRoles = $('.ads_Checkbox:checked').map(function () {
                return this.value;
            }).get();

        }
        var formData = new FormData();
        formData.append('module_id', module_id);
        formData.append('assignedRoles', assignedRoles);
        formData.append('query_url', query_url);
        formData.append('assignedUsers', assignedUsers);
        formData.append('query_subject', query_subject);
        formData.append('queryAssignedTo', queryAssignedTo);
        formData.append('assignedRemarks', assignedRemarks);
        formData.append('querySection', querySection);
        // Attach file
        formData.append('query_file', $('input[type=file]')[0].files[0]);

        $.ajax({
            type: 'POST',
            url:"{{route('queries.store')}}",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            success: function(response)
            {
                console.log(response);
                $("#queriesForm")[0].reset();
                //$("#remarks").summernote("reset");
                $('#queries-modal').modal('hide');
                window.setTimeout(function () {
                    window.location.reload();
                }, 100);
            }
        });

    });

</script>
@endpush
