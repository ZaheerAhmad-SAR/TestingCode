<div class="d-flex mt-3 mt-md-0 ml-auto">
<span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
<i class="fas fa-eye" style="margin-top: 12px;" id="question-info-{{ $question->id }}"></i></span>
</div>
@push('script_last')
<script>
$(function () {
  $('#question-info-{{ $question->id }}').popover(
      {
        title:'{!! $question->question_text !!}',
        content:'{!! $question->formFields->text_info !!}',
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
