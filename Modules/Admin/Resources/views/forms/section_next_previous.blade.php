@if ($key === $first)
    <div class="d-flex">
        @if ((bool) $subjectId && ($formStatus === 'complete' || $formStatus === 'resumable'))
            <button class="btn btn-warning" name="edit_form_button_{{ $stepIdStr }}"
                id="edit_form_button_{{ $stepIdStr }}"
                onclick="openFormForEditing('{{ $stepIdStr }}', '{{ $stepClsStr }}', '{{ $sectionIdStr }}');">
                Edit Form
            </button>
        @endif
        <button type="button" class="btn btn-primary nexttab ml-auto {{ $studyClsStr }} {{ $stepClsStr }} {{ $sectionClsStr }}">Next</button>
    </div>
    <div class="row">
        <div class="col-md-12" id="edit_form_div_{{ $stepIdStr }}" style="display: {{ ($formStatus == 'resumable')? 'block':'none' }};">
            <input type="text" name="edit_reason_text_{{ $stepIdStr }}" id="edit_reason_text_{{ $stepIdStr }}"
                class="form-control-ocap bg-transparent" value="" placeholder="Please put reason to edit form here" />
        </div>
    </div>
@elseif($key === $last)
    <div class="d-flex">
        <button type="button" class="btn btn-primary prevtab">Previous</button>
    </div>
    @if ((bool) $subjectId)
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
                    onclick="submitStepForms{{ $stepIdStr }}('{{ $stepIdStr }}', '{{ $stepClsStr }}');"
                    id="submit_{{ $stepIdStr }}">Submit</button>
            </div>
        </div>
    @endif
@else
    <div class="d-flex">
        <button type="button" class="btn btn-primary prevtab">Previous</button>
        <button type="button" class="btn btn-primary nexttab ml-auto {{ 'next_' . $sectionClsStr }}">Next</button>
    </div>
@endif
