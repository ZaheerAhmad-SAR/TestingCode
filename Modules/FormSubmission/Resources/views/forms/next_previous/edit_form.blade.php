@php
//echo $formStatusObj->form_status;
@endphp
<div class="d-flex">
    @if (
        (bool) $subjectId &&
        ($formStatusObj->form_status == 'complete' || $formStatusObj->form_status == 'resumable') &&
        ($adjudicationFormStatus != 'complete') &&
        ($isPreview === false)
    )
        <button type="button" class="btn btn-warning" name="edit_form_button_{{ $stepIdStr }}"
            id="edit_form_button_{{ $stepIdStr }}"
            onclick="openFormForEditing('{{ $stepIdStr }}', '{{ $stepClsStr }}', {{ $formStatusObj->form_type_id }}, '{{ buildGradingStatusIdClsStr($formStatusObj->id) }}');"
            style="display: {{ $formStatusObj->form_status == 'resumable' ? 'none' : 'block' }};">
            Edit Form
        </button>
    @endif

    @if(canManageData(['create', 'store', 'edit', 'update']) && ($isPreview === false))
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <button type="button" class="btn btn-danger" name="lock_data_button_{{ $stepIdStr }}"
            id="lock_data_button_{{ $stepIdStr }}"
            onclick="lockFormData('{{ $stepIdStr }}');"
            style="display: {{ $formStatusObj->is_data_locked == 0 ? 'block' : 'none' }};">
            Lock Data
        </button>
    <button type="button" class="btn btn-danger" name="unlock_data_button_{{ $stepIdStr }}"
            id="unlock_data_button_{{ $stepIdStr }}"
            onclick="unlockFormData('{{ $stepIdStr }}');"
            style="display: {{ $formStatusObj->is_data_locked == 1 ? 'block' : 'none' }};">
            UnLock Data
        </button>
    @endif

    @include('formsubmission::forms.next_previous.print_form')

</div>
@if($isPreview === false)
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
@endif
