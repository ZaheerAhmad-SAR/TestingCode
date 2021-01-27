<div class="modal fade" tabindex="-1" role="dialog" id="addField">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="min-width: 1130px;">
            <div class="modal-content" style="min-height: 560px;">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Add New Field</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('forms.addQuestions') }}" enctype="multipart/form-data" method="POST"
                    id="formfields">
                    @csrf
                    <div class="modal-body">
                        <nav>
                            <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-Basic-tab" data-toggle="tab" href="#nav-Basic"
                                    role="tab" aria-controls="nav-home" aria-selected="true">Basic</a>
                                <a class="nav-item nav-link" id="nav-Validation-tab" data-toggle="tab"
                                    href="#nav-Validation" role="tab" aria-controls="nav-profile" aria-selected="false">Data
                                    validation</a>
                                <a class="nav-item nav-link" id="nav-Dependencies-tab" data-toggle="tab"
                                    href="#nav-Dependencies" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Dependencies</a>
                                <a class="nav-item nav-link" id="nav-Annotations-tab" data-toggle="tab"
                                    href="#nav-Annotations" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Annotations</a>
                                <a class="nav-item nav-link" id="nav-Advanced-tab" data-toggle="tab"
                                    href="#nav-Adjudication" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Adjudication</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel"
                                aria-labelledby="nav-Basic-tab">
                                <div class="py-3 border-bottom border-primary">
                                    <span class="text-muted font-w-600">Define Basic Attribute of Question</span><br>

                                </div>
                                <div class="form-group row" style="margin-top: 10px;">
                                    <label for="Sorting" class="col-sm-2 col-form-label">Sort Number / Position</label>
                                    <div class="col-sm-4">
                                        <input type="hidden" name="id" id="questionId_hide" value="">
                                        <input type="hidden" name="field_id" id="form_field_id" value="">
                                        <input type="Number" name="question_sort" id="question_sort" class="form-control"
                                            placeholder="Sort Number / Placement Place">
                                    </div>
                                    <label for="Sections" class="col-sm-2 col-form-label">Sections</label>
                                    <div class="col-sm-4">
                                        <select name="section_id" id="section_id" class="form-control basic_section" required>
                                            <option value="">Choose Phase/Visit && Step/Form-Type</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="C-DISC" class="col-sm-2 col-form-label">C-DISC </label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="c_disk" id="c_disk" value="">
                                    </div>
                                    <label for="label" class="col-sm-2 col-form-label"> Label </label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="question_text" id="question_text"
                                            value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <lable for='variable' class="col-sm-2 col-form-label">Variable name </lable>
                                    <div class="col-sm-4">
                                        <p class="space_msg" style="font-size: 9px;color: red;"></p>
                                        <input type="text" class="form-control variable_name_ques" name="variable_name"
                                            id="variable_name" onchange="check_if_name_exists(this)" onpaste="return false"
                                            oncut="return false" oncopy="return false" required>
                                    </div>
                                    <label for="field" class="col-sm-2 col-form-label">Choose field type:</label>
                                    <div class="col-sm-4">
                                        <p></p>
                                        <select name="form_field_type_id" id="question_type" class="form-control">
                                            <option value="">--- Field Type ---</option>
                                            @foreach ($fields as $key => $value)
                                                @if ($value->field_type == 'Certification' || $value->field_type == 'Description' || $value->field_type == 'Calculated')
                                                @else
                                                    <option value="{{ $value->id }}">{{ $value->field_type }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Required" class="col-sm-2 col-form-label view_to_numeric">Lower Limit
                                        </label>
                                    <div class="col-sm-2 view_to_numeric">
                                        <input type="number" name="lower_limit" id="lower_limit_num" class="form-control"
                                            placeholder="Minimum limits">
                                    </div>
                                    <label for="Upper Limit" class="col-sm-2 col-form-label view_to_numeric">Upper
                                        Limit</label>
                                    <div class="col-sm-2 view_to_numeric">
                                        <input type="number" name="upper_limit" id="upper_limit_num" class="form-control"
                                            placeholder="Maximum limits">
                                    </div>
                                    <label for="Upper Limit" class="col-sm-2 col-form-label view_to_numeric">Decimal
                                        Point</label>
                                    <div class="col-sm-2 view_to_numeric">
                                        <input type="number" name="decimal_point" id="decimal_point_num"
                                            class="form-control" placeholder="Decimal Point">
                                    </div>
                                </div>
                                <div class="view_to_textbox_and_number">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Field width </label>
                                        <div class="col-sm-4">
                                            <input type="number" class="form-control" name="field_width"
                                                id="field_width_text" value="">
                                        </div>
                                        <label class="col-sm-2 col-form-label">Measurement unit</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="measurement_unit"
                                                id="measurement_unit_text" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="optionGroup">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Option group: </label>
                                        <div class="col-sm-8">
                                            <select name="option_group_id" id="option_group_id" class="form-control">
                                                <option value="">None</option>
                                                @foreach ($option_groups as $key => $value)
                                                    <option value="{{ $value->id }}">{{ $value->option_group_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                                data-target="#addOptionGroups">
                                                <i class="fa fa-plus"></i> Add Option Groups
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Required" class="col-sm-2 col-form-label">Required </label>
                                    <div class="col-sm-2">
                                        <input type="radio" name="is_required" id="required_no" value="no"> No
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="is_required" id="required_yes" value="yes" checked> Yes
                                    </div>
                                    <div class="col-sm-2">Exports: </div>
                                    <div class="col-sm-2">
                                        <input type="radio" name="is_exportable_to_xls" id="is_exportable_to_xls_no"
                                            value="no"> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="is_exportable_to_xls" id="is_exportable_to_xls_yes"
                                            value="yes" checked> Yes
                                    </div>
                                    <div class="col-sm-2">Show to Grader: </div>
                                <div class="col-sm-2">
                                    <input type="radio" name="is_show_to_grader" id="is_show_to_grader_no" value="no" checked> No
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="is_show_to_grader" id="is_show_to_grader_yes" value="yes"> Yes
                                </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Text/info: </label>
                                    <div class="col-sm-10">
                                        <textarea name="text_info" id="text_info_add" cols="2" rows="1"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-Validation" role="tabpanel"
                                aria-labelledby="nav-Validation-tab">
                                <div class="py-3 border-bottom border-primary">
                                    <span class="text-muted font-w-600">Default Validation</span><br>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12" style="margin-top: 10px;padding-left: 0px;"><button type="button"
                                            class="btn btn-outline-primary addvalidations"><i class="fa fa-plus"></i> Add Validation Rule</button></div>
                                </div>
                                <div class="appendDatavalidations">

                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-Dependencies" role="tabpanel"
                                aria-labelledby="nav-Dependencies-tab">
                                <div class="py-3 border-bottom border-primary">
                                    <span class="text-muted font-w-600">Define If Dependencies on any Question</span><br>
                                </div>
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-sm-2">Field is dependent: </div>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="dependency_id" id="dependency_id">
                                        <input type="radio" name="q_d_status" class="field_dependent"
                                            id="field_dependent_no" value="no" checked> No
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="q_d_status" class="field_dependent"
                                            id="field_dependent_yes" value="yes"> Yes
                                    </div>
                                </div>
                                <div class="append_if_yes" style="display: none;">
                                    <div class="form-group row">
                                        <div class="col-sm-2"> Questions:</div>
                                        <div class="col-sm-4">
                                            <select name="dep_on_question_id" class="form-control select_ques_for_dep"
                                                id="select_ques_for_dep">
                                                <option value="">---Select Question---</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2"> field operator:</div>
                                        <div class="col-sm-4">
                                            <select name="dependency_opertaor" id="dependency_operator" class="form-control">
                                                <option value="">---Select---</option>
                                                <option value="==">Equal</option>
                                                <option value=">=">Greater OR Equal</option>
                                                <option value="<=">Less OR Equal</option>
                                                <option value="!=">Not Equal</option>
                                                <option value=">">Greater Than</option>
                                                <option value="<">Less</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-2">Value:</div>
                                        <div class="col-sm-4">
                                            <input type="text" name="dependency_custom_value" id="dependency_custom_value"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-Annotations" role="tabpanel"
                                aria-labelledby="nav-Annotations-tab">
                                <div class="py-3 border-bottom border-primary">
                                    <span class="text-muted font-w-600">Annotations</span><br>
                                </div>
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-sm-12">
                                        <button type="button"
                                            class="btn btn-outline-primary addannotation"><i class="fa fa-plus"></i> Add
                                            annotation
                                        </button>
                                        <button type="button"
                                            class="btn btn-outline-primary add_new_annotation"><i class="fa fa-plus"></i> Add New
                                        </button>
                                    </div>
                                </div>
                                <div class="appendannotation">

                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-Adjudication" role="tabpanel"
                                aria-labelledby="nav-Advanced-tab">
                                <div class="py-3 border-bottom border-primary">
                                    <span class="text-muted font-w-600">Set Up Adjudication Status On Current
                                        Question</span><br>
                                </div>
                                <div class="form-group row" style="margin-top: 10px">
                                    <input type="hidden" name="adj_id" id="adj_id">
                                    <div class="col-sm-2">Adjudication</div>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="adj_status" id="adj_status">
                                            <option value="no">Not Required </option>
                                            <option value="yes"> Required </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2 display-none show_if_required">Based ON</div>
                                    <div class="col-sm-4 display-none show_if_required">
                                        <select class="form-control" name="decision_based_on" id="decision_based_on">
                                            <option value="">---Decision---</option>
                                            <option value="any_change">Any Change</option>
                                            <option value="custom">Custom</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="show_if_custom_percent display-none">
                                    <div class="form-group row">
                                        <div class="col-sm-2"> Operator:</div>
                                        <div class="col-sm-4">
                                            <select class="form-control" name="opertaor" id="adj_operator">
                                                <option value="">---Select---</option>
                                                <option value=">=">Greater OR Equal</option>
                                                <option value="<=">Less OR Equal</option>
                                                <option value=">">Greater Than</option>
                                                <option value="<">Less Than</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">Value:</div>
                                        <div class="col-sm-4">
                                            <input type="text" name="custom_value" id="adj_custom_value"
                                                class="form-control"
                                                placeholder="Define custom or percentage value for adjudication">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close"
                                    aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save
                                Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('script')
<script type="text/javascript">
    $(document).ready(function(){
        // update question
            $('body').on('click', '.Edit_ques,.cloneQuestion', function() {
                checkIsStepHasData();
                if (checkIsStepActive() == false) {
                    $('#formfields').trigger('reset');
                    var row = $(this).closest('div.custom_fields')
                    tId = ''
                    type = ''
                    type = $(this).attr('data-type');

                    ques_id = row.find('input.question_id').val()
                    ques_type = row.find('input.question_type').val()
                    ques_type_id = row.find('input.question_type_id').val()
                    question_sort = row.find('input.question_sort').val()
                    section_id = row.find('input.section_id').val()
                    option_group_id = row.find('input.option_group_id').val()
                    c_disk = row.find('input.c_disk').val()
                    question_text = row.find('input.question_text').val()
                    variable_name = row.find('input.variable_name').val()
                    formFields_id = row.find('input.formFields_id').val()
                    text_info = row.find('input.text_info').val()
                    is_required = row.find('input.is_required').val()
                    is_exportable_to_xls = row.find('input.is_exportable_to_xls').val()
                    is_show_to_grader = row.find('input.is_show_to_grader').val()
                    measurement_unit = row.find('input.measurement_unit').val()
                    field_width = row.find('input.field_width').val()
                    upper_limit = row.find('input.upper_limit').val()
                    decimal_point = row.find('input.decimal_point').val()
                    lower_limit = row.find('input.lower_limit').val()
                    dependency_id = row.find('input.dependency_id').val()
                    dependency_status = row.find('input.dependency_status').val()
                    dependency_question = row.find('input.dependency_question').val()
                    dependency_operator = row.find('input.dependency_operator').val()
                    dependency_custom_value = row.find('input.dependency_custom_value').val()
                    dependency_question_class = $('select.select_ques_for_dep')
                    adj_status = row.find('input.adj_status').val()
                    adj_decision_based = row.find('input.adj_decision_based').val()
                    adj_operator = row.find('input.adj_operator').val()
                    adj_custom_value = row.find('input.adj_custom_value').val()
                    adj_id = row.find('input.adj_id').val();
                    // alert(dependency_question);
                    $('#questionId_hide').val(ques_id);
                    $('#question_type').val(ques_type_id);
                    $('#question_type').trigger('change');
                    $('#section_id').val(section_id);
                    $('#option_group_id').val(option_group_id);
                    $('#c_disk').val(c_disk);
                    $('#question_text').val(question_text);
                    if(type == 'clone'){
                        $('#variable_name').val('');
                        $('#question_sort').val('');
                    }else{
                        $('#variable_name').val(variable_name);
                        $('#question_sort').val(question_sort);
                    }
                    $('#form_field_id').val(formFields_id);
                    tinymce.get('text_info_add').setContent(text_info);
                    if (ques_type == 'Number') {
                        $('#measurement_unit_text').val(measurement_unit);
                        $('#field_width_text').val(field_width);
                        $('#lower_limit_num').val(lower_limit);
                        $('#upper_limit_num').val(upper_limit);
                        $('#decimal_point_num').val(decimal_point);
                    } else {
                        $('#measurement_unit_text').val(measurement_unit);
                        $('#field_width_text').val(field_width);
                    }
                    if (is_required == 'yes') {
                        $('#required_yes').prop('checked', true);
                    } else {
                        $('#required_no').prop('checked', true);
                    }
                    if (is_exportable_to_xls == 'yes') {
                        $('#is_exportable_to_xls_yes').prop('checked', true);
                    } else {
                        $('#is_exportable_to_xls_no').prop('checked', true);
                    }
                    if (is_show_to_grader == 'yes') {
                        $('#is_show_to_grader_yes').prop('checked', true);
                    } else {
                        $('#is_show_to_grader_no').prop('checked', true);
                    }
                    $('#dependency_id').val(dependency_id);
                    if (dependency_status == 'yes') {
                        $('#field_dependent_yes').prop('checked', true);
                        // $('.field_dependent').trigger('change');
                        $('.append_if_yes').css('display', 'block');
                    } else {
                        $('#field_dependent_no').prop('checked', true);
                        $('.append_if_yes').css('display', 'none');
                    }
                    get_question_section_id(section_id, dependency_question_class);
                    $('#dependency_operator').val(dependency_operator);
                    $('#dependency_custom_value').val(dependency_custom_value);
                    tId = setTimeout(function() {
                        $('#select_ques_for_dep').val(dependency_question);
                    }, 3000);
                    $('#adj_id').val(adj_id);
                    $('#adj_status').val(adj_status);
                    $('#adj_status').trigger('change');
                    $('#decision_based_on').val(adj_decision_based)
                    $('#decision_based_on').trigger('change');
                    $('#adj_operator').val(adj_operator);
                    $('#adj_custom_value').val(adj_custom_value);
                    if(type == 'clone'){
                        $('.modal-title').html('Clone Question')
                        $('#formfields').attr('action', "{{ route('forms.addQuestions') }}");
                    }else{
                        $('.modal-title').html('Update Question')
                        $('#formfields').attr('action', "{{ route('forms.updateQuestion') }}");
                    }
                    $('#addField').modal('show');
                    loadValidationRulesByQuestionId(ques_id);
                } else {
                    showStepDeActivationAlert();
                }
            })
    })
</script>
@endpush