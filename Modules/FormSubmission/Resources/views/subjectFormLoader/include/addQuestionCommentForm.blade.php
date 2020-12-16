<form id="addQuestionCommentForm" onsubmit="submitAddQuestionCommentForm(event);">
    <div id="exTab1">
        <div class="tab-content clearfix">
            @csrf
            <input type="hidden" name="commentById" id="commentById" value="{{ auth()->user()->id }}">
            <input type="hidden" name="studyId" id="studyId" value="{{$studyId}}">
            <input type="hidden" name="subjectId" id="subjectId" value="{{$subjectId}}">
            <input type="hidden" name="phaseId" id="phaseId" value="{{$phaseId}}">
            <input type="hidden" name="stepId" id="stepId" value="{{$stepId}}">
            <input type="hidden" name="sectionId" id="sectionId" value="{{$sectionId}}">
            <input type="hidden" name="questionId" id="questionId" value="{{$questionId}}">
            <div class="form-group">
                <label class="">Comment</label>
                <textarea name="question_comment" class="form-control-ocap bg-transparent" required></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-outline-danger" data-dismiss="modal" id="addQuestionCommentFormClose">
            <i class="fa fa-window-close" aria-hidden="true"></i> Close
        </button>
        <button type="submit" class="btn btn-outline-primary" id="addQuestionCommentFormBtn">
            <i class="fa fa-save"></i> Add Comment
        </button>
    </div>
</form>
