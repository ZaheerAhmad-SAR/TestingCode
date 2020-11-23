@if (($isPreview === false))
<div class="row">
    <div class="col-md-12">&nbsp;</div>
</div>
<div class="row">
    <div class="col-md-11">
        <div class="custom-control custom-checkbox custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="terms_cond_{{ $stepIdStr }}"
                id="terms_cond_{{ $stepIdStr }}" value="accepted">
            <label class="custom-control-label checkbox-primary" for="primary">I
                acknowledge that the information submitted in this form is true and
                correct to the best of my knowledge.</label>
        </div>
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-success float-right"
            onclick="submitStepForm{{ $stepIdStr }}('{{ $stepIdStr }}', '{{ $stepClsStr }}');"
            id="submit_{{ $stepIdStr }}">Submit</button>
    </div>
</div>

@php
$queryParams = [
                    'study_id'=>$studyId,
                    'subject_id'=>$subjectId,
                    'study_structures_id'=>$phase->id,
                    'phase_steps_id'=>$step->step_id,
                    'section_id'=>'',
                    'question_id'=>'',
                    'field_id'=>'',
                    'form_type_id'=>$step->form_type_id,
                    'modility_id'=>$step->modility_id,
                    'module'=>$step->formType->form_type.' Form',
            ];
@endphp
@include('formsubmission::forms.form_fields.query_popup_btn', ['queryParams'=>$queryParams])
@endif
