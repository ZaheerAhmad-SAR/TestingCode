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
<!-- queries-model-question -->
<div class="modal fade" tabindex="-1" role="dialog" id="queries-modal-question" aria-labelledby="exampleModalQuestionQueries" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title">Add Question Queries</p>
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
                                    <label class="radio-inline  col-form-label"><input type="radio" id="assignQuestionQueries" name="assignQuestionQueries" value="user"> Users</label> &nbsp;
                                    <label class="radio-inline  col-form-label"><input type="radio" id="assignQuestionQueries" name="assignQuestionQueries" value="role" > Roles</label>
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
<!-- queries-model-question end -->

<!-- queries-model-form -->
<div class="modal fade" tabindex="-1" role="dialog" id="queries-modal-form" aria-labelledby="exampleModalFormQueries" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title">Add Form Queries</p>
            </div>
            <form id="formForQueries" name="formForQueries" enctype="multipart/form-data" >
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
                                <div class="col-sm-10" id="formQueryUserDropDownList"></div>
                            </div>
                            <div class="form-group row querySubject" style="display: none;">
                                <label for="Name" class="col-sm-2 col-form-label">Subject:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="form_subject" minlength="6" maxlength="50" id="form_subject">
                                </div>
                            </div>

                            <div class="form-group row queryAttachment" style="display: none;">
                                <label for="Name" class="col-sm-2 col-form-label">Attachment:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="file" name="inputFormFile"  id="input_form_file">
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
                                    <textarea class="form-control" name="form_message" id="form_message"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="form_study_id" id="form_study_id" value="">
                            <input type="hidden" name="form_question_id" id="form_question_id" value="">
                            <input type="hidden" name="form_subject_id" id="form_subject_id" value="">
                            <input type="hidden" name="form_study_structures_id" id="form_study_structures_id" value="">
                            <input type="hidden" name="form_phase_steps_id" id="form_phase_steps_id" value="">
                            <input type="hidden" name="form_section_id" id="form_section_id" value="">
                            <input type="hidden" name="form_field_id" id="form_field_id" value="">
                            <input type="hidden" name="form_form_type_id" id="form_form_type_id" value="">
                            <input type="hidden" name="form_modility_id" id="form_modility_id" value="">
                            <input type="hidden" name="form_module"   id="form_module" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="form_queries-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" name="submit" class="btn btn-outline-primary" id="submit"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- queries-model-form end -->


<div class="modal fade" tabindex="-1" role="dialog" id="close-question-table-modal" aria-labelledby="exampleModalQueries" aria-hidden="true">
    <div class="modal-dialog  modal-lg modal-dialog-centered" role="document" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title">All Close Question Queries</p>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="example" class="display table dataTable table-striped table-bordered" >
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Query Subject</th>
                            <th>Submited By</th>
                            <th>Assigned To</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th>History</th>
                        </tr>
                        </thead>
                        <tbody class="queriesclosequestionList"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="close-form-query-table-modal" aria-labelledby="exampleModalQueries" aria-hidden="true">
    <div class="modal-dialog  modal-lg modal-dialog-centered" role="document" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title">All Close Form Queries</p>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="example" class="display table dataTable table-striped table-bordered" >
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Query Subject</th>
                            <th>Submited By</th>
                            <th>Assigned To</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th>History</th>
                        </tr>
                        </thead>
                        <tbody class="queriesCloseFormList"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="show-question-table-modal" aria-labelledby="exampleModalQueries" aria-hidden="true">
    <div class="modal-dialog  modal-lg modal-dialog-centered" role="document" style="max-width: 1000px;">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title">All Question Queries</p>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="example" class="display table dataTable table-striped table-bordered" >
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Query Subject</th>
                            <th>Submited By</th>
                            <th>Assigned To</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody class="queriesquestionList"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="show-form-table-modal" aria-labelledby="exampleModalQueries" aria-hidden="true">
    <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title">All Form Queries</p>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="example" class="display table dataTable table-striped table-bordered" >
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Query Subject</th>
                            <th>Submited By</th>
                            <th>Assigned To</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody class="queriesFormList"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="question-history_modal" aria-labelledby="exampleModalQueries" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width: 1000px;" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <p class="modal-title"> Question History</p>
                <span class="queryCurrentStatus text-center"></span>
            </div>
            <div class="modal-body">
                <form id="historyCloseQuestionForm" name="historyCloseQuestionForm">
                    <div class="tab-content clearfix">
                        @csrf
                        <div class="replyInput"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="addqueries-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="form-history_modal" aria-labelledby="exampleModalQueries" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width: 1000px;" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <p class="modal-title"> Form Query History</p>
                <span class="queryFormCurrentStatus text-center"></span>
            </div>
            <div class="modal-body">
                <form id="historyCloseForm" name="historyCloseForm">
                    <div class="tab-content clearfix">
                        @csrf
                        <div class="historyCloseQueryListDetails"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="addqueries-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="reply-question-modal" aria-labelledby="exampleModalQueries" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width: 1000px;" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <p class="modal-title"> Question Details</p>
                <span class="queryQuestionCurrentStatus text-center"></span>
            </div>
            <div class="modal-body">
                <form id="replyQuestionForm" name="replyQuestionForm" enctype="multipart/form-data">
                        @csrf
                        <div class="replyInput"></div>
                    <div class="form-group row questionTextarea" style="display: none;">
                        <label for="question" class="col-sm-2 col-form-label">Enter your Query</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="message_query_for_reply" id="message_query_for_reply"></textarea>
                        </div>
                    </div>
                    <div class="form-group row questionFile" style="display: none;" >
                        <label for="Attachment" class="col-sm-2 col-form-label">Attachment:</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="file" name="question_file"  id="question_file">
                        </div>
                    </div>

                    <div class="form-group row queryStatus" style="display:none;">
                        <label for="Status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="query_status" name="query_status">
                                <option value="open">open</option>
                                <option value="confirmed" >Confirmed</option>
                                <option value="unconfirmed">UnConfirmed</option>
                                <option value="in progress">Inprogress</option>
                                <option value="close">close</option>
                            </select>
                        </div>
                    </div>

                        <div class="col-sm-12">
                            <div class="replyQuestionButton" style="text-align: right;">
                                    <span style="cursor: pointer;"><i class="fa fa-reply"></i> &nbsp; reply </span>
                            </div>
                        </div>

                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="addqueries-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn btn-outline-primary" id="replyquestion"><i class="fa fa-save"></i> Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="reply-form-modal" aria-labelledby="exampleModalQueries" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width: 1000px;" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <p class="modal-title"> Form Details</p>
                <span class="queryCurrentStatus text-center"></span>
            </div>
            <div class="modal-body">
                <form id="replyFormQueryForm" name="replyFormQueryForm">
                    <div class="tab-content clearfix">
                        @csrf
                        <div class="formInput"></div>
                        <div class="col-sm-12">
                            <div class="replyFormButton" style="text-align: right;">
                                    <span style="cursor: pointer;">
                                        <i class="fa fa-reply"></i> &nbsp; reply
                                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="addqueries-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit"  name="submit" class="btn btn-outline-primary" id="submit"><i class="fa fa-save"></i> Send</button>
                    </div>
                </form>
            </div>
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
            $('#queries-modal-question').modal('show');
            loadUserDropDownList(study_id);

        }
    function loadUserDropDownList(study_id) {
        $.ajax({
            url:"{{route('queries.usersDropDownListQuestion')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'study_id'      :study_id,
            },
            success: function(response)
            {
                $('#usersSelectOptionList').html('');
                $('#usersSelectOptionList').html(response);
            }
        });
    }





    function openPopupForFormModal(study_id, subject_id,
                                study_structures_id, phase_steps_id,
                                section_id, question_id, field_id,
                                form_type_id, modility_id, module){
        $('#form_study_id').val(study_id);
        $('#form_question_id').val(question_id);
        $('#form_phase_steps_id').val(phase_steps_id);
        $('#form_section_id').val(section_id);
        $('#form_subject_id').val(subject_id);
        $('#form_study_structures_id').val(study_structures_id);
        $('#form_field_id').val(field_id);
        $('#form_modility_id').val(modility_id);
        $('#form_module').val(module);
        $('#form_form_type_id').val(form_type_id);
        $('#queries-modal-form').modal('show');
        loadUserDropDownListForQueryForm(study_id);

    }

    function loadUserDropDownListForQueryForm(study_id) {
        $.ajax({
            url:"{{route('queries.usersDropDownListForm')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'study_id'      :study_id,
            },
            success: function(response)
            {

                $('#formQueryUserDropDownList').html(response);
            }
        });
    }

        function getAllQuestionQueryData(study_id, subject_id,
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
            openQuestionTableView(question_id);
        }


        function showCloseQuestionQueries(study_id, subject_id,
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
            openCloseQuestionTableView(question_id);
            //$('#reply-modal').modal('show');
            //showQuestions(question_id);
        }


        function showCloseFormQueries(study_id, subject_id,
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
            openCloseFormTableView(phase_steps_id);
            //$('#reply-modal').modal('show');
            //showQuestions(question_id);
        }


        function getAllFormQueryData(study_id, subject_id,
          study_structures_id, phase_steps_id,
          section_id, question_id, field_id,
          form_type_id, modility_id, module){
            console.log(phase_steps_id);
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
            openFormTableView(phase_steps_id);
        }


    $('.showAllQuestionQueries').click(function () {
        $('#show-question-table-modal').modal('show');
    });

    $('.showCloseQuestionPopUp').click(function () {
        $('#close-question-table-modal').modal('show');
    });

    $('.showCloseFormQueryPopUp').click(function () {
        $('#close-form-query-table-modal').modal('show');
    });


    $('.showAllFormQueries').click(function () {
        $('#show-form-table-modal').modal('show');
    });


    function openQuestionTableView(question_id) {
        $.ajax({
            url:"{{route('queries.loadAllQuestionById')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'question_id'      :question_id,
            },
            success: function(response)
            {
                $('.queriesquestionList').html('');
                $('.queriesquestionList').html(response);
            }
        });
    }

    function openCloseQuestionTableView(question_id) {
        $.ajax({
            url:"{{route('queries.loadAllCloseQuestionById')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'question_id'      :question_id,
            },
            success: function(response)
            {
                $('.queriesclosequestionList').html('');
                $('.queriesclosequestionList').html(response);
            }
        });
    }

    function openCloseFormTableView(phase_steps_id) {
        $.ajax({
            url:"{{route('queries.loadAllCloseFormPhaseById')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'phase_steps_id'      :phase_steps_id,
            },
            success: function(response)
            {
                $('.queriesCloseFormList').html('');
                $('.queriesCloseFormList').html(response);
            }
        });
    }


    function openFormTableView(phase_steps_id) {
        $.ajax({
            url:"{{route('queries.loadFormByPhaseId')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'phase_steps_id' :phase_steps_id,
            },
            success: function(response)
            {
                $('.queriesFormList').html('');
                $('.queriesFormList').html(response);
            }
        });
    }



    $('body').on('click', '.replyFormQuery', function () {
        var query_id = $(this).attr('data-id');
        $('#reply-form-modal').modal('show');
        showForm(query_id);
        $('#show-form-table-modal').modal('hide');
    });

    function showForm(query_id) {
        $.ajax({
            url:"{{route('queries.showFormByQueryId')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'query_id'      :query_id,
            },
            success: function(response)
            {
                $('.formInput').html('');
                $('.formInput').html(response);
                var formStatusInput = $( "#formStatusInput option:selected" ).text();
                $('.queryCurrentStatus').text('Status: '+formStatusInput);
                $('.replyClick').css('display','');
            }
        });
    }

    $('body').on('click', '.replyQuestionQuery', function () {
        var query_id           = $(this).attr('data-id');
        var questionStatusValue = $(this).attr('data-value');
        if (questionStatusValue == 'close')
        {
            $('.queryQuestionCurrentStatus').html('');
            $('.queryQuestionCurrentStatus').text('Status: '+questionStatusValue);
            $('#query_status').val(questionStatusValue);
            $('#reply-question-modal').modal('show');
            $('#show-question-table-modal').modal('hide');
            $('.replyQuestionButton').css('display','none');
            $('#replyquestion').hide();
            showQuestions(query_id);
        }
        else
        {
            $('.queryQuestionCurrentStatus').html('');
            $('.queryQuestionCurrentStatus').text('Status: '+questionStatusValue);
            $('#query_status').val(questionStatusValue);
            $('#reply-question-modal').modal('show');
            $('#show-question-table-modal').modal('hide');
            $('.replyQuestionButton').css('display','');
            $('#replyquestion').show();
            showQuestions(query_id);
        }


    });

    $('body').on('click', '.historyCloseQuestionQueries', function () {
        var query_id     = $(this).attr('data-id');
        $('#question-history_modal').modal('show');
        showCloseQuestions(query_id);
        $('#close-question-table-modal').modal('hide');
    });

    $('body').on('click', '.historyCloseFormQueries', function () {
        var query_id          = $(this).attr('data-id');
        var FormCurrentStatus = $(this).attr('data-value');

        $('.queryFormCurrentStatus').html('');
        $('.queryFormCurrentStatus').text('Status: '+FormCurrentStatus);
        $('#form-history_modal').modal('show');
        showCloseFormQueriesById(query_id);
        $('#close-form-query-table-modal').modal('hide');
    });


    function showQuestions(query_id) {
        $.ajax({
            url:"{{route('queries.showQuestionsById')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'query_id'      :query_id,
            },
            success: function(response)
            {
                //$('.replyInput').html('');
                $('.replyInput').html(response);
                var query_status = $( "#query_status option:selected" ).text();
                $('.queryQuestionCurrentStatus').text('Status: '+query_status);
                $('.replyClick').css('display','');
            }
        });
    }

    function showCloseQuestions(query_id) {
        $.ajax({
            url:"{{route('queries.showQuestionsById')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'query_id'      :query_id,
            },
            success: function(response)
            {
                $('.replyInput').html('');
                $('.replyInput').html(response);
                var query_status = $( "#query_status option:selected" ).text();
                $('.queryCurrentStatus').text('Status: '+query_status);
                $('.replyClick').css('display','');
            }
        });
    }

    function showCloseFormQueriesById(query_id) {

        $.ajax({
            url:"{{route('queries.showFormByQueryId')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'query_id'      :query_id,
            },
            success: function(response)
            {
                $('.historyCloseQueryListDetails').html('');
                $('.historyCloseQueryListDetails').html(response);
            }
        });
    }


        $("#replyQuestionForm").on('submit', function(e) {
            e.preventDefault();
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });
        var message_query_for_reply = $("#message_query_for_reply").val();
        var query_id                = $("#query_id").val();
        var query_url               = $("#query_url").val();
        var query_type              = $("#query_type").val();
        var query_status            = $("#query_status").val();
        var subject_question        = $("#subject_question").val();
        var query_level_question    = $("#query_level_question").val();

        var study_id                = $("#study_id").val();
        var subject_id              = $("#subject_id").val();
        var phase_steps_id          = $("#phase_steps_id").val();
        var section_id              = $("#section_id").val();
        var question_id             = $("#question_id").val();
        var field_id                = $("#field_id").val();
        var form_type_id            = $("#form_type_id").val();
        var modility_id             = $("#modility_id").val();
        var module_name             = $("#module_name").val();
        var study_structures_id     = $("#study_structures_id").val();


        var formData = new FormData();
        formData.append('message_query_for_reply', message_query_for_reply);
        formData.append('query_id', query_id);
        formData.append('query_url', query_url);
        formData.append('query_type', query_type);
        formData.append('query_status', query_status);
        formData.append('subject_question', subject_question);
        formData.append('query_level_question', query_level_question);

        formData.append('study_id', study_id);
        formData.append('subject_id', subject_id);
        formData.append('phase_steps_id', phase_steps_id);
        formData.append('section_id', section_id);
        formData.append('question_id', question_id);
        formData.append('field_id', field_id);
        formData.append('form_type_id', form_type_id);
        formData.append('modility_id', modility_id);
        formData.append('module_name', module_name);
        formData.append('study_structures_id', study_structures_id);

        // Attach file
        formData.append("question_file", $("#question_file")[0].files[0]);
        $.ajax({
            url:"{{route('queries.queryQuestionReply')}}",
            type: "POST",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            success: function (results)
            {
                var query_id = results[0].parent_query_id;
                showQuestions(query_id);
                //$('.replyClick').css('display','');
                $('.replyQuestionButton').css('display','');
                $('.questionTextarea').css('display','none');
                $('#message_query_for_reply').val('');
                $('#question_file').val('');
                $('.questionFile').css('display','none');
                $('.queryStatus').css('display','none');

            },
            error: function (results) {
                console.error('Error:', results);
            }
        });
    });

    $('body').on('click', '.replyQuestionButton', function () {
        $('.replyQuestionButton').css('display','none');
        $('.questionTextarea').css('display','');
        $('.questionFile').css('display','');
        $('.queryStatus').css('display','');

    });

    $('body').on('click', '.replyFormButton', function () {
        $('.replyFormButton').css('display','none');
        $('.formQueryTextarea').css('display','');
        $('.formQueryFile').css('display','');
        $('.formQueryStatus').css('display','');

    });


    $("#queriesQuestionForm").on('submit', function(e) {
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
        var queryAssignedTo     = $("input[name='assignQuestionQueries']:checked").val();
        var message             = $('#message').val();
        var query_url           =  document.URL;
        var query_subject_form  = $("#query_subject_form").val();
        if (queryAssignedTo == 'user')
        {
            var assignedUsers = $('#question_users').val();
            //console.log(assignedUsers);
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
            url:"{{route('queries.storeQuestionQueries')}}",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            success: function(response)
            {
                //console.log(response);
                $("#queriesQuestionForm")[0].reset();
                $("#usersSelectOptionList").html('');
                $('#queries-modal-question').modal('hide');
                //location.reload();
                {{--$.ajax({--}}
                {{--    type: 'POST',--}}
                {{--    url: "{{route('notifications.countUserNotification')}}"--}}
                {{--});--}}
            }
        });

    });

    function countUserNotification()
    {
        $.ajax({
            type: 'POST',
            url:"{{route('notifications.countUserNotification')}}",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            success: function(response)
            {
                //console.log(response);
            }
        });
    }

    // $('#queries-modal-question').on('hidden.bs.modal', function () {
    //     $(this).find('form').trigger('reset');
    // })

    $('#reply-question-modal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    })


    $("#formForQueries").on('submit', function(e) {

        e.preventDefault();
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        var form_study_id       = $("#form_study_id").val();
        var form_question_id    = $("#form_question_id").val();
        var form_phase_steps_id = $("#form_phase_steps_id").val();
        var form_section_id     = $("#form_section_id").val();
        var form_subject_id     = $("#form_subject_id").val();
        var form_field_id       = $("#form_field_id").val();
        var form_module         = $("#form_module").val();
        var form_modility_id    = $("#form_modility_id").val();
        var form_form_type_id   = $("#form_form_type_id").val();

        var queryAssignedTo     = $("input[name='assignQueries']:checked").val();
        var form_study_structures_id = $("#form_study_structures_id").val();
        var form_message        = $('#form_message').val();
        var form_query_url      =  document.URL;
        var form_subject        = $("#form_subject").val();
        if (queryAssignedTo == 'user')
        {
            var assignedUsers = $('#form_users').val();
            //console.log(assignedUsers);
        }
        if(queryAssignedTo =='role')
        {
            var assignedRolesForm  = $('.assignedRolesForForm:checked').map(function () {
                return this.value;
            }).get();
        }
        var formData = new FormData();

        formData.append('form_study_id', form_study_id);
        formData.append('form_question_id', form_question_id);
        formData.append('form_phase_steps_id', form_phase_steps_id);
        formData.append('form_section_id', form_section_id);
        formData.append('form_subject_id', form_subject_id);
        formData.append('form_study_structures_id', form_study_structures_id);
        formData.append('form_field_id', form_field_id);
        formData.append('form_module', form_module);
        formData.append('form_modility_id', form_modility_id);
        formData.append('form_form_type_id', form_form_type_id);


        formData.append('assignedRolesForm', assignedRolesForm);
        formData.append('form_query_url', form_query_url);
        formData.append('assignedUsers', assignedUsers);
        formData.append('form_subject', form_subject);
        formData.append('queryAssignedTo', queryAssignedTo);
        formData.append('form_message', form_message);
        // Attach file name = form_file
        formData.append("inputFormFile", $("#input_form_file")[0].files[0]);
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
                //console.log(response);
                $("#formForQueries")[0].reset();
                $("#formQueryUserDropDownList").html('');
                $('#queries-modal-form').modal('hide');
                location.reload();
                // window.setTimeout(function () {
                //     window.location.reload();
                // }, 100);
            }
        });

    });

    $("#replyFormQueryForm").on('submit', function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        var studyStructuresIdInput = $("#studyStructuresIdInput").val();
        var studyIdInput           = $("#studyIdInput").val();
        var subjectIdInput         = $("#subjectIdInput").val();
        var phaseStepsIdInput      = $("#phaseStepsIdInput").val();
        var sectionIdInput         = $("#sectionIdInput").val();
        var questionIdInput        = $("#questionIdInput").val();
        var fieldIdInput           = $("#fieldIdInput").val();
        var formTypeIdInput        = $("#formTypeIdInput").val();
        var modilityIdInput        = $("#modilityIdInput").val();
        var moduleNameInput        = $("#moduleNameInput").val();
        var queryTypeInput         = $("#queryTypeInput").val();
        var queryIdInput           = $("#queryIdInput").val();
        var queryUrlInput          = $("#queryUrlInput").val();
        var subjectFormInput       = $("#subjectFormInput").val();
        var formReply              = $("#formReply").val();
        var formStatusInput        = $("#formStatusInput").val();
        var queryLeveFormInput     = $("#queryLeveFormInput").val();

        var formData = new FormData();

        formData.append('studyStructuresIdInput', studyStructuresIdInput);
        formData.append('studyIdInput', studyIdInput);
        formData.append('subjectIdInput', subjectIdInput);
        formData.append('phaseStepsIdInput', phaseStepsIdInput);
        formData.append('sectionIdInput', sectionIdInput);
        formData.append('questionIdInput', questionIdInput);
        formData.append('fieldIdInput', fieldIdInput);
        formData.append('formTypeIdInput', formTypeIdInput);
        formData.append('modilityIdInput', modilityIdInput);
        formData.append('moduleNameInput', moduleNameInput);
        formData.append('queryTypeInput',  queryTypeInput);
        formData.append('queryIdInput',    queryIdInput);
        formData.append('queryUrlInput',    queryUrlInput);
        formData.append('subjectFormInput', subjectFormInput);
        formData.append('formReply',formReply);
        formData.append('formStatusInput', formStatusInput);
        formData.append('queryLeveFormInput', queryLeveFormInput);

        // Attach file name = form_file
        formData.append("formFileInput", $("#formFileInput")[0].files[0]);
        $.ajax({
            type: 'POST',
            url:"{{route('queries.replyFormQueries')}}",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            success: function(results)
            {
                //console.log(results);
                $('.replyFormButton').css('display','');
                var query_id = results[0].parent_query_id;
                showForm(query_id);
            }
        });

    });






    $('#queries-modal-question').on('hidden.bs.modal', function () {
        $(this).find("input,textarea,select").val('').end();
        // setTimeout(function() {
        //     window.location.reload();
        // }, 100);
        //location.reload();
        $("#usersSelectOptionList").html('');
    });

    function getAllStudyData(study_id) {
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

    function loadQueryPopUpHtml(study_id) {
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
