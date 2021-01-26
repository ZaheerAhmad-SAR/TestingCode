 <div class="modal fade" tabindex="-1" role="dialog" id="ChangeQuestionSort">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Update Question Sort Number</p>
                </div>
                <form name="QuestionSort" id="QuestionSortForm">
                    <div class="modal-body">
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                <div class="form-group row">
                                    <div class="form-group col-md-12">
                                        <input type="hidden" class="form-control" id="questionId" name="questionId"
                                            value="">
                                        <input type="text" class="form-control" id="up_question_sort" name="question_sort"
                                            value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="question-sort-close" class="btn btn-outline-danger" data-dismiss="modal"><i
                                    class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="button" class="btn btn-outline-primary updateSort"><i class="fa fa-save"></i> Save
                                Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('body').on('click', '.change_ques_sort', function() {
            var row = $(this).closest('div.custom_fields');
            var question_id = row.find('input.question_id').val();
            var question_sort = row.find('input.question_sort').val();
            $('#questionId').val(question_id);
            $('#up_question_sort').val(question_sort);
            $('#ChangeQuestionSort').modal('show');
        })

        // Change Sort Number

        $('.updateSort').on('click', function() {
            var questionId = $('#questionId').val();
            var sort_value = $('#up_question_sort').val();
            $.ajax({
                url: 'forms/changeSort/' + questionId,
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'questionId': questionId,
                    'sort_value': sort_value
                },
                dataType: 'json',
                success: function(res) {
                    $('#question-sort-close').click();
                    var step_id = $('#steps').val()
                    tId;
                    display_sections(step_id);
                    $('.success-msg').html('');
                    $('.success-msg').html('Question Sort Number Updated!')
                    $('.success-alert').slideDown('slow');
                    tId = setTimeout(function() {
                        $(".success-alert").slideUp('slow');
                    }, 4000);
                }
            })
        })
    })
</script>
@endpush