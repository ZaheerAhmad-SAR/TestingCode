<div class="modal fade" tabindex="-1" role="dialog" id="descriptionModal">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="min-width: 1130px;">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title"></p>
                </div>
                <form action="{{ route('forms.addQuestions') }}" enctype="multipart/form-data" method="POST"
                    id="form_description">
                    @csrf
                    <div class="modal-body">
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                <div class="form-group row" style="margin-top: 10px;">
                                    <input type="hidden" name="form_field_type_id" value="11">
                                    <input type="hidden" name="id" id="questionId_hide_des" value="">
                                    <input type="hidden" name="field_id" id="form_field_id_des" value="">
                                    <label for="Sorting" class="col-sm-2 col-form-label">Sort Number / Position</label>
                                    <div class="col-sm-4">
                                        <input type="Number" name="question_sort" id="question_sort_de" class="form-control"
                                            placeholder="Sort Number / Placement Place">
                                    </div>
                                    <label for="Sections" class="col-sm-2 col-form-label">Sections</label>
                                    <div class="col-sm-4">
                                        <select name="section_id" id="section_id_de" class="form-control basic_section" required>
                                            <option value="">Choose Phase/Visit && Step/Form-Type</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="label" class="col-sm-2 col-form-label"> Description </label>
                                    <div class="col-sm-12">
                                        <textarea name="text_info" id="text_info_de"></textarea>
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
@push('script')
<script type="text/javascript">
    $(document).ready(function(){
        // Add Description Field
        $('.add_discription').on('click', function() {
            $('#form_description').trigger('reset');
            $('.modal-title').html('Add Description');
            $('#form_description').attr('action', "{{ route('forms.addQuestions') }}");
            var id = $(this).attr("data-field-id");
            $('#question_type').val(id);
            $('#descriptionModal').modal('show');
        })
        // Clone Description Field
        $('body').on('click','.clone_desc', function() {
            $('#form_description').trigger('reset');
            $('.modal-title').html('Clone Description');
            $('#form_description').attr('action', "{{ route('forms.addQuestions') }}");
            var row = $(this).closest('div.custom_fields')
            tId = ''
            ques_id = row.find('input.question_id').val()
            question_sort = row.find('input.question_sort').val()
            formFields_id = row.find('input.formFields_id').val()
            section_id = row.find('input.section_id').val()
            text_info = row.find('input.text_info').val();
            tinymce.get('text_info_de').setContent(text_info);
            var id = $(this).attr("data-field-id");
            $('#question_type').val(id);
            $('#question_sort_de').val(question_sort);
            $('#questionId_hide_des').val(ques_id);
            $('#form_field_id_des').val(formFields_id);
            $('#section_id_de').val(section_id);
            $('#descriptionModal').modal('show');
        })
        // update Description
        $('body').on('click', '.edit_desc', function() {
            $('#form_description').trigger('reset');
            $('.modal-title').html('Update Description');
            $('#form_description').attr('action', "{{ route('forms.updateQuestion') }}");
            var row = $(this).closest('div.custom_fields')
            tId = ''
            ques_id = row.find('input.question_id').val()
            question_sort = row.find('input.question_sort').val()
            formFields_id = row.find('input.formFields_id').val()
            section_id = row.find('input.section_id').val()
            text_info = row.find('input.text_info').val();
            tinymce.get('text_info_de').setContent(text_info);
            $('#question_sort_de').val(question_sort);
            $('#questionId_hide_des').val(ques_id);
            $('#form_field_id_des').val(formFields_id);
            $('#section_id_de').val(section_id);
            $('#descriptionModal').modal('show');
        });
    })
</script>
@endpush