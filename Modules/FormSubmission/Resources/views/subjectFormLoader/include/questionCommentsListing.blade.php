<div class="row">
    <div class="col-12"></div>
</div>
@foreach ($questionComments as $questionComment)
@php
$commentedBy = App\User::find($questionComment->comment_by_id);
$question_comment = nl2br($questionComment->question_comment);
@endphp
<div class="row">
<div class="col-4">{{ $commentedBy->name }}</div><div class="col-8"><textarea class="form-control" readonly>{{ $question_comment }}</textarea></div>
</div>
@endforeach
<div class="modal-footer">
    <button class="btn btn-outline-danger" data-dismiss="modal" id="addQuestionCommentFormClose">
        <i class="fa fa-window-close" aria-hidden="true"></i> Close
    </button>
    <button type="button"
            onclick="loadAddQuestionCommentForm('{{ $studyId }}', '{{ $subjectId }}', '{{ $phaseId }}', '{{ $stepId }}', '{{ $sectionId }}', '{{ $questionId }}');"
            class="btn btn-primary"><i class="fa fa-save"></i> Add new comment
    </button>
</div>
