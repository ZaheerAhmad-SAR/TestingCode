@php
//echo $formStatusObj->form_status;
@endphp
<div class="d-flex">
    @if (
        (bool) $subjectId &&
        ($formStatusObj->form_status == 'complete' || $formStatusObj->form_status == 'resumable') &&
        ($adjudicationFormStatus != 'complete')
    )
        <button type="button" class="btn btn-warning" name="edit_form_button_{{ $stepIdStr }}"
            id="edit_form_button_{{ $stepIdStr }}"
            onclick="openFormForEditing('{{ $stepIdStr }}', '{{ $stepClsStr }}', {{ $formStatusObj->form_type_id }}, '{{ buildGradingStatusIdClsStr($formStatusObj->id) }}');"
            style="display: {{ $formStatusObj->form_status == 'resumable' ? 'none' : 'block' }};">
            Edit Form
        </button>
    @endif
</div>
<div class="row">
    <div class="col-md-12" id="edit_form_div_{{ $stepIdStr }}"
        style="display: {{ $formStatusObj->form_status == 'resumable' ? 'block' : 'none' }};">
        <div class="form-group">
            <label class="">Reason to edit form :</label>
            <input type="text" name="edit_reason_text_{{ $stepIdStr }}" id="edit_reason_text_{{ $stepIdStr }}"
                class="form-control-ocap bg-transparent" value=""
                placeholder="Please put reason to edit form here" />
        </div>
    </div>
</div>
