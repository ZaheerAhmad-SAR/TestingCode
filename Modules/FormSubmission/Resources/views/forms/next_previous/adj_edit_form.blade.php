@php
//echo $adjudicationFormStatusObj->adjudication_status;
@endphp
<div class="d-flex">
    @if ((bool) $subjectId && ($adjudicationFormStatusObj->adjudication_status == 'complete' || $adjudicationFormStatusObj->adjudication_status == 'resumable'))
        <button type="button" class="btn btn-warning" name="adjudication_form_edit_button_{{ $stepIdStr }}"
            id="adjudication_form_edit_button_{{ $stepIdStr }}"
            onclick="openAdjudicationFormForEditing('{{ $stepIdStr }}', '{{ $stepClsStr }}', '{{ $adjudicationFormStatusObj->form_type_id }}', '{{ buildAdjudicationStatusIdClsStr($adjudicationFormStatusObj->id) }}');"
            style="display: {{ $adjudicationFormStatusObj->adjudication_status == 'resumable' ? 'none' : 'block' }};">
            Edit Form
        </button>
    @endif

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    @if($showAllQuestions == 'no')
        <button type="button" class="btn btn-primary" onclick="showAllQuestions();">
            Show all questions
        </button>
    @else
        <button type="button" class="btn btn-primary" onclick="showOnlyAdjudicationRequiredQuestions();">
            show adjudication required questions
        </button>
    @endif

    @include('formsubmission::forms.next_previous.print_form', ['formStatus'=>$adjudicationFormStatusObj->adjudication_status])
</div>
<div class="row">
    <div class="col-md-12" id="adjudication_form_edit_div_{{ $stepIdStr }}"
        style="display: {{ $adjudicationFormStatusObj->adjudication_status == 'resumable' ? 'block' : 'none' }};">
        <div class="form-group">
            <label class="">Reason to edit form :</label>
            <input type="text" name="adjudication_form_edit_reason_text_{{ $stepIdStr }}" id="adjudication_form_edit_reason_text_{{ $stepIdStr }}"
                class="form-control-ocap bg-transparent" value=""
                placeholder="Please put reason to edit form here" />
        </div>
    </div>
</div>
