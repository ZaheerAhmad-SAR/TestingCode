 <!-- Modal To add Option Groups -->
    <div class="modal fade" tabindex="-1" role="dialog" id="listModal">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="min-width: 1130px;">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title"></p>
                </div>
                <form action="{{ route('forms.addQuestions') }}" enctype="multipart/form-data" method="POST"
                    id="form_certify">
                    @csrf
                    <nav>
                        <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-Basic-tab" data-toggle="tab" href="#nav-Basic"
                                role="tab" aria-controls="nav-home" aria-selected="true">Basic</a>
                            <a class="nav-item nav-link" id="nav-Dependencies-tab" data-toggle="tab"
                                href="#nav-Dependencies" role="tab" aria-controls="nav-contact"
                                aria-selected="false">Dependencies</a>
                        </div>
                    </nav>
                    <div class="modal-body">
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                <div class="form-group row" style="margin-top: 10px;">
                                    <input type="hidden" name="id" id="question_id_cert" value="">
                                    <input type="hidden" name="field_id" id="form_field_id_cert" value="">
                                    <input type="hidden" name="form_field_type_id" id="question_type_cert" value="">
                                    <label for="Sorting" class="col-sm-2 col-form-label">Sort Number / Position</label>
                                    <div class="col-sm-4">
                                        <input type="Number" name="question_sort" id="question_sort_cert" class="form-control"
                                            placeholder="Sort Number / Placement Place">
                                    </div>
                                    <label for="Sections" class="col-sm-2 col-form-label">Sections</label>
                                    <div class="col-sm-4">
                                        <select name="section_id" id="section_id_cert" class="form-control basic_section" required>
                                            <option value="">Choose Phase/Visit && Step/Form-Type</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="C-DISC" class="col-sm-2 col-form-label">C-DISC </label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="c_disk" id="c_disk_cert" value="">
                                    </div>
                                    <label for="label" class="col-sm-2 col-form-label"> Label </label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="question_text" id="question_text_cert" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for='variable' class="col-sm-2 col-form-label">Variable name </label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control variable_name variable_name_ques" name="variable_name" id="variable_name_cert" value="" onchange="check_if_name_exists(this)" required>
                                    </div>
                                    <label for="list" class="col-sm-2 col-form-label"> List type </label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="certification_type" id="certification_type">
                                            <option value="">---Select---</option>
                                            <option value="photographers">Photographer List</option>
                                            <option value="devices">Device List</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Required" class="col-sm-2 col-form-label">Required </label>
                                    <div class="col-sm-2">
                                        <input type="radio" name="is_required" id="is_required_no_cert" value="no"> No
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="is_required" value="yes" id="is_required_yes_cert" checked> Yes
                                    </div>
                                    <div class="col-sm-2">Exports: </div>
                                    <div class="col-sm-2">
                                        <input type="radio" name="is_exportable_to_xls" id="is_exportable_to_xls_no_cert" value="no"> No
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="is_exportable_to_xls" value="yes" id="is_exportable_to_xls_yes_cert" checked> Yes
                                    </div>
                                    <div class="col-sm-2">Show to Grader: </div>
                                <div class="col-sm-2">
                                    <input type="radio" name="is_show_to_grader" id="is_show_to_grader_no_cert" value="no"> No
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="is_show_to_grader" id="is_show_to_grader_yes_cert" value="yes" checked> Yes
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="question-sort-close" class="btn btn-outline-danger" data-dismiss="modal"><i
                                class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save
                            Changes</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    <!-- End -->
@push('script')
<script type="text/javascript">
    $('.add_certify_list').on('click', function() {
        checkIsStepHasData();
        if (checkIsStepActive() == false) {
            $('#form_certify').trigger('reset');
            $('.modal-title').html('Add Certification list');
            $('#form_certify').attr('action', "{{ route('forms.addQuestions') }}");
            var id = $(this).attr("data-field-id");
            $('#question_type_cert').val(id);
            $('#listModal').modal('show');
        } else {
            showStepDeActivationAlert();
        }
    })
    $('body').on('click', '.edit_certify', function() {

                $('#form_certify').trigger('reset');
                $('.modal-title').html('Update Certification List');
                $('#form_certify').attr('action', "{{ route('forms.updateQuestion') }}");
                var row = $(this).closest('div.custom_fields')
                tId = ''
                ques_id = row.find('input.question_id').val()
                ques_type_id = row.find('input.question_type_id').val()
                question_sort = row.find('input.question_sort').val()
                section_id = row.find('input.section_id').val()
                c_disk = row.find('input.c_disk').val()
                question_text = row.find('input.question_text').val()
                variable_name = row.find('input.variable_name').val()
                formFields_id = row.find('input.formFields_id').val()
                text_info = row.find('input.text_info').val()
                first_question_id = row.find('input.first_question_id').val()
                operator_certulate = row.find('input.operator_certulate').val()
                second_question_id = row.find('input.second_question_id').val()
                is_required = row.find('input.is_required').val()
                is_exportable_to_xls = row.find('input.is_exportable_to_xls').val()
                is_show_to_grader = row.find('input.is_show_to_grader').val()
                make_decision = row.find('input.make_decision').val()
                certification_type = row.find('input.certification_type').val()
                calculate_with_costum_val = row.find('input.calculate_with_costum_val').val()
                step_id = $('#steps').val();
                $('#question_id_cert').val(ques_id);
                $('#question_sort_cert').val(question_sort);
                $('#question_type_cert').val(ques_type_id);
                $('#section_id_cert').val(section_id);
                $('#c_disk_cert').val(c_disk);
                $('#question_text_cert').val(question_text);
                $('#variable_name_cert').val(variable_name);
                $('#form_field_id_cert').val(formFields_id);
                $('#operator_certulate_cert').val(operator_certulate);
                $('#make_decision').val(make_decision);
                $('#certification_type').val(certification_type);
                $('#make_decision').trigger('change');
                $('#calculate_with_costum_val').val(calculate_with_costum_val);
                //tinymce.get('text_info_add_cert').setContent(text_info);
                if (is_required == 'yes') {
                    $('#is_required_yes_cert').prop('checked', true);
                } else {
                    $('#is_required_no_cert').prop('checked', true);
                }
                if (is_exportable_to_xls == 'yes') {
                    $('#is_exportable_to_xls_yes_cert').prop('checked', true);
                } else {
                    $('#is_exportable_to_xls_no_cert').prop('checked', true);
                }
                if (is_show_to_grader == 'yes') {
                    $('#is_show_to_grader_yes_cert').prop('checked', true);
                } else {
                    $('#is_show_to_grader_no_cert').prop('checked', true);
                }
                tId = setTimeout(function() {
                 $('#first_question_id_cert').val(first_question_id);
                 $('#second_question_id_cert').val(second_question_id);
                }, 2000);
                $('#listModal').modal('show');

                loadValidationRulesByQuestionId(ques_id);

        })
</script>
@endpush
