<div class="d-flex mt-3 mt-md-0 ml-auto">
<span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
<i class="fas fa-eye" style="margin-top: 12px;" id="question-info-adjudication-{{ $question->id }}"></i></span>
</div>
@php
$text_info = str_replace(array("\n", "\r"), '', html_entity_decode($question->formFields->text_info));
@endphp
@push('script_last')
<script>
$(function () {
  $('#question-info-adjudication-{{ $question->id }}').popover(
      {
        title:'{!! $question->question_text !!}',
        content:'{!! nl2br($text_info) !!}',
        animation:true,
        html:true,
        trigger:'hover',
        container:'body',
        template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
      }
  )
})
</script>
@endpush
