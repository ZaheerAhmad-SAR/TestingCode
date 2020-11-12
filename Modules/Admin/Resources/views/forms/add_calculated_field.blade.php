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
                                    <input type="hidden" name="form_field_type_id" value="10">
                                    <label for="Sorting" class="col-sm-2 col-form-label">Sort Number / Position</label>
                                    <div class="col-sm-4">
                                        <input type="Number" name="question_sort" class="form-control"
                                            placeholder="Sort Number / Placement Place">
                                    </div>
                                    <label for="Sections" class="col-sm-2 col-form-label">Sections</label>
                                    <div class="col-sm-4">
                                        <select name="section_id" class="form-control basic_section">
                                            <option value="">Choose Phase/Visit && Step/Form-Type</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="C-DISC" class="col-sm-2 col-form-label">C-DISC <sup>*</sup></label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="c_disk" value="">
                                    </div>
                                    <label for="label" class="col-sm-2 col-form-label"> Label <sup>*</sup></label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="question_text" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for='variable' class="col-sm-2 col-form-label">Variable name <sup>*</sup></label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control variable_name" name="variable_name" value="">
                                    </div>
                                    <label for="list" class="col-sm-2 col-form-label"> First Question </label>
                                    <div class="col-sm-4">
                                        <select class="form-control calculate_first_question" name="calculate_first_question">
                                            <option value="">---Select---</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for='variable' class="col-sm-2 col-form-label">Action <sup>*</sup></label>
                                    <div class="col-sm-4">
                                        <select class="form-control calculate_first_question" name="calculate_second_question">
                                            <option value="">---Operator---</option>
                                            <option value="+">Add</option>
                                            <option value="+">Subtract</option>
                                        </select>
                                    </div>
                                    <label for="list" class="col-sm-2 col-form-label"> Second Question </label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="second_question">
                                            <option value="">---Select---</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Required" class="col-sm-2 col-form-label">Required <sup>*</sup></label>
                                    <div class="col-sm-4">
                                        <input type="radio" name="is_required" value="no"> No
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="is_required" value="yes" checked> Yes
                                    </div>
                                    <div class="col-sm-2">Exports: <sup>*</sup></div>
                                    <div class="col-sm-4">
                                        <input type="radio" name="is_exportable_to_xls" value="no"> No
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="is_exportable_to_xls" value="yes" checked> Yes
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
                $('#question_type').val(id);
                $('#calculate_Modal').modal('show');
                load_questions_in_dropdowns(step_id);
            } else {
                showStepDeActivationAlert();
            }
        })
        function load_questions_in_dropdowns(step_id){
            $.ajax({
                    url: 'forms/delete/' + question_id,
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": 'DELETE',
                        'questionId': question_id,
                    },
                    dataType: 'json',
                    success: function(res) {
                        row.remove();
                        $('.success-msg').html('');
                        $('.success-msg').html('Operation Done!')
                        $('.success-alert').slideDown('slow');
                        tId = setTimeout(function() {
                            $(".success-alert").slideUp('slow');
                        }, 4000);
                    }
            });
        }
    </script>
@endpush