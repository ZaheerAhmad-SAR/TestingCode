<div class="modal fade" tabindex="-1" role="dialog" id="calculate_Modal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="min-width: 1130px;">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <p class="modal-title"></p>
            </div>
            <form action="{{ route('forms.addQuestions') }}" enctype="multipart/form-data" method="POST"
                id="form_calculate">
                @csrf
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                            <div class="form-group row" style="margin-top: 10px;">
                                <input type="hidden" name="id" id="question_id_calc" value="">
                                <input type="hidden" name="field_id" id="form_field_id_calc" value="">
                                <input type="hidden" name="form_field_type_id" id="question_type_calc" value="">
                                <label for="Sorting" class="col-sm-2 col-form-label">Sort Number / Position</label>
                                <div class="col-sm-4">
                                    <input type="Number" name="question_sort" id="question_sort_calc" class="form-control"
                                        placeholder="Sort Number / Placement Place">
                                </div>
                                <label for="Sections" class="col-sm-1 col-form-label">Sections</label>
                                <div class="col-sm-5">
                                    <select name="section_id" id="section_id_calc" class="form-control basic_section" required>
                                        <option value="">Choose Phase/Visit && Step/Form-Type</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="C-DISC" class="col-sm-2 col-form-label">C-DISC </label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="c_disk" id="c_disk_calc" value="">
                                </div>
                                <label for="label" class="col-sm-1 col-form-label"> Label </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="question_text" id="question_text_calc" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for='variable' class="col-sm-2 col-form-label">Variable name </label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control variable_name variable_name_ques" name="variable_name" id="variable_name_calc" value="" onchange="check_if_name_exists(this)" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Required" class="col-sm-2 col-form-label">Required </label>
                                <div class="col-sm-4">
                                    <input type="radio" name="is_required" id="is_required_no_calc" value="no"> No
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="is_required" id="is_required_yes_calc" value="yes" checked> Yes
                                </div>
                                <div class="col-sm-1">Exports: </div>
                                <div class="col-sm-5">
                                    <input type="radio" name="is_exportable_to_xls" id="is_exportable_to_xls_no_calc" value="no"> No
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="is_exportable_to_xls" id="is_exportable_to_xls_yes_calc" value="yes" checked> Yes
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Legends: </label>
                                <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-4"><code>Variable Name : [variable_name]</code></div>
                                            <div class="col-4"><code>Square root : sqrt(25)</code></div>
                                            <div class="col-4"><code>Power : pow(4, 3)</code></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2"><code>Add : +</code></div>
                                            <div class="col-2"><code>Subtract : -</code></div>
                                            <div class="col-2"><code>Multiply : *</code></div>
                                            <div class="col-2"><code>Divide : /</code></div>
                                            <div class="col-2"><code>Remainder : %</code></div>
                                        </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Available Variables: </label>
                                <div class="col-sm-10">
                                        <div class="row" id="available_variable_names"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Example: </label>
                                <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-12"><code>[variable_1] / (sqrt([variable_2]) * 2.141516)</code></div>
                                        </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Calculation Formula: </label>
                                <div class="col-sm-10">
                                    <textarea name="custom_formula" id="custom_formula" rows="5" cols="80"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Text/info: </label>
                                <div class="col-sm-10">
                                    <textarea name="text_info" id="text_info_add_calc"></textarea>
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
        </form>
        </div>
    </div>
</div>
@push('script')
<script>
    $('.add_calculated_field').on('click', function() {
        checkIsStepHasData();
        if (checkIsStepActive() == false) {
            $('#form_calculate').trigger('reset');
            $('.modal-title').html('Add Auto Calculation Of Two Questions');
            $('#form_calculate').attr('action', "{{ route('forms.addQuestions') }}");
            var id = $(this).attr("data-field-id");
            var step_id = $('#steps').val();
            $('#question_type_calc').val(id);
            $("#text_info_add_calc").val('');
            tinymce.get('text_info_add_calc').setContent('');
            $('#calculate_Modal').modal('show');
            show_available_variable_names(step_id);
        } else {
            showStepDeActivationAlert();
        }
    })
    // update question of calculated field
    $('body').on('click', '.edit_calculated_field', function() {
        checkIsStepHasData();
        if (checkIsStepActive() == false) {
            $('#form_calculate').trigger('reset');
            $('.modal-title').html('Update Auto Calculation Of Two Questions');
            $('#form_calculate').attr('action', "{{ route('forms.updateQuestion') }}");
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
            is_required = row.find('input.is_required').val()
            is_exportable_to_xls = row.find('input.is_exportable_to_xls').val()
            custom_formula = row.find('input.custom_formula').val()
            step_id = $('#steps').val();
            show_available_variable_names(step_id);
            $('#question_id_calc').val(ques_id);
            $('#question_sort_calc').val(question_sort);
            $('#question_type_calc').val(ques_type_id);
            $('#section_id_calc').val(section_id);
            $('#c_disk_calc').val(c_disk);
            $('#question_text_calc').val(question_text);
            $('#variable_name_calc').val(variable_name);
            $('#form_field_id_calc').val(formFields_id);
            $('#custom_formula').val(custom_formula);
            tinymce.get('text_info_add_calc').setContent(text_info);
            if (is_required == 'yes') {
                $('#is_required_yes_calc').prop('checked', true);
            } else {
                $('#is_required_no_calc').prop('checked', true);
            }
            if (is_exportable_to_xls == 'yes') {
                $('#is_exportable_to_xls_yes_calc').prop('checked', true);
            } else {
                $('#is_exportable_to_xls_no_calc').prop('checked', true);
            }
            $('#calculate_Modal').modal('show');
        }else {
            showStepDeActivationAlert();
        }
    })
    function show_available_variable_names(step_id){
        $.ajax({
                url: 'forms/show_available_variable_names/' + step_id,
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'step_id': step_id,
                },
                dataType: 'html',
                success: function(res) {
                   $('#available_variable_names').html(res);
                }
        });
    }
</script>
@endpush
