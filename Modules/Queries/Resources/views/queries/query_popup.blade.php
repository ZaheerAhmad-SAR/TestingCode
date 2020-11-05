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
                            <div id="studyData">
                            </div>

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
<!-- queries model end -->
<!-- queries-model-form -->
<div class="modal fade" tabindex="-1" role="dialog" id="queries-modal-form" aria-labelledby="exampleModalQueries" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title">Add Form Queries</p>
            </div>
            <form id="queriesQuestionForm" name="queriesQuestionForm" enctype="multipart/form-data" >
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
                                <div class="col-sm-10" id="usersSelectOptionList"></div>
                            </div>
                            <div class="form-group row querySubject" style="display: none;">
                                <label for="Name" class="col-sm-2 col-form-label">Subject:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="query_subject_form" minlength="6" maxlength="50" id="query_subject_form">
                                </div>
                            </div>

                            <div class="form-group row queryAttachment" style="display: none;">
                                <label for="Name" class="col-sm-2 col-form-label">Attachment:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="file" name="queryFileForm"  id="query_file_form">
                                </div>
                            </div>
                            <div class="form-group row rolesInput" style="display: none;">
                                <label for="Name" class="col-sm-2 col-form-label">Roles:</label>
                                <div class="col-sm-10">
                                    @foreach($roles_for_queries as $role)
                                        <label class="checked-inline  col-form-label"><input type="checkbox" class="assignedRolesForForm" id="roles" name="roles" value="{{$role->id}}"> {{$role->name}} </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group row remarksInput" style="display:none;">
                                <label for="Name" class="col-sm-2 col-form-label">Remarks</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="message" id="message"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="study_id" id="study_id" value="">
                            <input type="hidden" name="question_id" id="question_id" value="">
                            <input type="hidden" name="subject_id" id="subject_id" value="">
                            <input type="hidden" name="study_structures_id" id="study_structures_id" value="">
                            <input type="hidden" name="phase_steps_id" id="phase_steps_id" value="">
                            <input type="hidden" name="section_id" id="section_id" value="">
                            <input type="hidden" name="field_id" id="field_id" value="">
                            <input type="hidden" name="form_type_id" id="form_type_id" value="">
                            <input type="hidden" name="modility_id" id="modility_id" value="">
                            <input type="hidden" name="module"   id="module" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="addqueriesform-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" name="submit" class="btn btn-outline-primary" id="submit"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- queries-model-form end -->
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
        var study_id  = $(this).attr('data-id');
        $('#module_id').val(study_id);
        $('#queries-modal').modal('show');
        loadQueryPopUpHtml(study_id);
        getAllStudyData(study_id);
    });
    function openFormQueryPopup(study_id, subject_id,
          study_structures_id, phase_steps_id,
          section_id, question_id, field_id,
          form_type_id, modility_id, module){
            $('#study_id').val(study_id);
            $('#question_id').val(question_id);
            $('#phase_steps_id').val(phase_steps_id);
            $('#section_id').val(section_id);
            $('#subject_id').val(subject_id);
            $('#study_structures_id').val(study_structures_id);
            $('#field_id').val(field_id);
            $('#modility_id').val(modility_id);
            $('#module').val(module);
            $('#form_type_id').val(form_type_id);
            $('#queries-modal-form').modal('show');
            loadUserDropDownList(study_id);

        }

    $("#queriesQuestionForm").on('submit', function(e)
    {
        e.preventDefault();
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        var study_id            = $("#study_id").val();
        var question_id         = $("#question_id").val();
        var phase_steps_id      = $("#phase_steps_id").val();
        var section_id          = $("#section_id").val();
        var subject_id          = $("#subject_id").val();
        var study_structures_id = $("#study_structures_id").val();
        var field_id            = $("#field_id").val();
        var module              = $("#module").val();
        var modility_id         = $("#modility_id").val();
        var form_type_id        = $("#form_type_id").val();

        var queryAssignedTo     = $("input[name='assignQueries']:checked").val();
        var message             = $('#message').val();
        var query_url           =  document.URL;
        var query_subject_form  = $("#query_subject_form").val();
        if (queryAssignedTo == 'user')
        {
            var assignedUsers = $('#users').val();
        }
        if(queryAssignedTo =='role')
        {
            var assignedRolesForm  = $('.assignedRolesForForm:checked').map(function () {
                return this.value;
            }).get();
        }
        var formData = new FormData();

        formData.append('study_id', study_id);
        formData.append('question_id', question_id);
        formData.append('phase_steps_id', phase_steps_id);
        formData.append('section_id', section_id);
        formData.append('subject_id', subject_id);
        formData.append('study_structures_id', study_structures_id);
        formData.append('field_id', field_id);
        formData.append('form_type_id', form_type_id);
        formData.append('module', module);
        formData.append('modility_id', modility_id);
        formData.append('form_type_id', form_type_id);


        formData.append('assignedRolesForm', assignedRolesForm);
        formData.append('query_url', query_url);
        formData.append('assignedUsers', assignedUsers);
        formData.append('query_subject_form', query_subject_form);
        formData.append('queryAssignedTo', queryAssignedTo);
        formData.append('message', message);
        // Attach file name = queryFileForm
        formData.append("queryFileForm", $("#query_file_form")[0].files[0]);
        $.ajax({
            type: 'POST',
            url:"{{route('queries.storeFormQueries')}}",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            success: function(response)
            {
                console.log(response);
                $("#queriesQuestionForm")[0].reset();
                $('#queries-modal-form').modal('hide');
                // window.setTimeout(function () {
                //     window.location.reload();
                // }, 100);
            }
        });

    });


    $('#queries-modal-form').on('hidden.bs.modal', function () {
        $(this).find("input,textarea,select").val('').end();
    });

    function getAllStudyData(study_id)
    {
        $.ajax({
            url:"{{route('queries.getStudyDataByStudyId')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'study_id'      :study_id,
            },
            success: function(response)
            {
                $('#studyData').html(response);
            }
        });
    }

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
                $('#usersDropDown').html(response);
            }
        });
    }

    function loadUserDropDownList(study_id)
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
                $('#usersSelectOptionList').html(response);
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
            }
            if ($(this).attr("value")=="role")
            {
                $('.usersInput').css('display','none');
                $('.querySubject').css('display','');
                $(".rolesInput").css('display','');
                $(".queryAttachment").css('display','');
                $(".remarksInput").css('display','');
                $(".select2-selection__choice").remove();
            }
        });
    });

    $("#queriesForm").on('submit', function(e){
        e.preventDefault();
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        var queryAssignedTo  = $("input[name='assignQueries']:checked").val();
        var module_id        = $('#module_id').val();
        var querySectionData = $('#querySectionData').val();
        var assignedRemarks  = $('#remarks').val();
        var query_url        =  document.URL;
        var query_subject    = $("#query_subject").val();
        if (queryAssignedTo == 'user')
        {
            var assignedUsers = $('#users').val();
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
        formData.append('querySectionData', querySectionData);
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
