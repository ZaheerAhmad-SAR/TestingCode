@if($fieldType != 'Description')
@php
$text_info = str_replace(array("\n", "\r"), '', html_entity_decode($question->formFields->text_info));
@endphp

<div class="d-flex mt-3 mt-md-0 ml-auto">
<span class="ml-3" style="cursor: pointer;">
<i class="fas fa-eye" style="margin-top: 12px;" onclick="showModal{{ $questionIdStr }}();"></i></span>
@if(Modules\Admin\Entities\QuestionComments::hasComments($studyId, $subjectId, $phase->id, $step->step_id, $section->id, $question->id))
<span class="ml-3" style="cursor: pointer;">
    <i class="fas fa-comments" style="margin-top: 12px;" onclick="loadQuestionCommentPopup('{{ $studyId }}', '{{ $subjectId }}', '{{ $phase->id }}', '{{ $step->step_id }}', '{{ $section->id }}', '{{ $question->id}}');"></i></span>
@endif
</div>
@push('popup_modals')
<!-- Modal -->
<div class="modal fade" id="question-info-{{ $question->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-lg-custom modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{!! $question->question_text !!}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            {!! nl2br($text_info) !!}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<!-- Modal End -->
@endpush
@push('script')
<script>
    function showModal{{ $questionIdStr }}(){
        $('#question-info-{{ $question->id }}').modal('show')
    }
</script>
@endpush
@endif
