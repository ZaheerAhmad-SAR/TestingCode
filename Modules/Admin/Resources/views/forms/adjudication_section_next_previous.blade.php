@php
$showAllQuestions = request('showAllQuestions', 'no');
@endphp
@if ($key === $first)
    <div class="d-flex">
        @if ((bool) $subjectId && ($adjudicationFormStatusObj->adjudication_status === 'complete' || $adjudicationFormStatusObj->adjudication_status === 'resumable'))
            <button type="button" class="btn btn-warning" name="adjudication_form_edit_button_{{ $stepIdStr }}"
                id="adjudication_form_edit_button_{{ $stepIdStr }}"
                onclick="openAdjudicationFormForEditing('{{ $stepIdStr }}', '{{ $stepClsStr }}', '{{ $adjudicationFormStatusObj->form_type_id }}', '{{ buildAdjudicationStatusIdClsStr($adjudicationFormStatusObj->id) }}');"
                style="display: {{ $adjudicationFormStatusObj->adjudication_status == 'resumable' ? 'none' : 'block' }};">
                Edit Form
            </button>
        @endif

        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @if($showAllQuestions === 'no')
            <button type="button" class="btn btn-primary" onclick="showAllQuestions();">
                Show all questions
            </button>
        @else
            <button type="button" class="btn btn-primary" onclick="showOnlyAdjudicationRequiredQuestions();">
                show adjudication required questions
            </button>
        @endif

        <button type="button"
            class="btn btn-primary nexttab ml-auto {{ $studyClsStr }} {{ $stepClsStr }} {{ $sectionClsStr }}">Next</button>
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
                    <input type="checkbox" class="custom-control-input" name="adjudication_form_terms_cond_{{ $stepIdStr }}"
                        id="adjudication_form_terms_cond_{{ $stepIdStr }}" value="accepted">
                    <label class="custom-control-label checkbox-primary" for="primary">I
                        acknowledge that the information submitted in this form is true and
                        correct to the best of my knowledge.</label>
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-success float-right"
                    onclick="submitStepAdjudicationForm{{ $stepIdStr }}('{{ $stepIdStr }}', '{{ $stepClsStr }}');"
                    id="adjudication_form_submit_{{ $stepIdStr }}">Submit</button>
            </div>
        </div>
    @endif
@else
    <div class="d-flex">
        <button type="button" class="btn btn-primary prevtab">Previous</button>
        <button type="button" class="btn btn-primary nexttab ml-auto {{ 'next_' . $sectionClsStr }}">Next</button>
    </div>
@endif
