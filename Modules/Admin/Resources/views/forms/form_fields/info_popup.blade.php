<div class="d-flex mt-3 mt-md-0 ml-auto">
<span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-eye" style="margin-top: 12px;" data-container="body" data-toggle="popover" data-trigger="hover" data-html="true" title="{{ $question }}" data-content="{!! $text_info !!}"></i></span>
</div>
@push('script_last')
<script>
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>
@endpush
