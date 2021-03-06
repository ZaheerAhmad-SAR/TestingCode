@if($step->is_active == 1 || $isPreview == true)
@if (count($section->questions))
@php
$showAllQuestions = request('showAllQuestions', 'no');
$getGradersIdsArray = [
    'subject_id' => $subjectId,
    'study_id' => $studyId,
    'study_structures_id' => $phase->id,
    'phase_steps_id' => $step->step_id,
    'form_type_id' => $step->form_type_id,
];
$graderIdsArray = \Modules\FormSubmission\Entities\FormStatus::getAllGraderIds($getGradersIdsArray);
$colNum = (12/count($graderIdsArray));
/**************************/
$getAdjudicationRequiredQuestionIdsArray = [
    'subject_id' => $subjectId,
    'study_id' => $studyId,
    'study_structures_id' => $phase->id,
    'phase_steps_id' => $step->step_id,
];
$adjudicationRequiredQuestionIdsArray = \Modules\FormSubmission\Entities\QuestionAdjudicationRequired::getAdjudicationRequiredQuestionsArray($getAdjudicationRequiredQuestionIdsArray);
@endphp
<fieldset id="fieldset_adjudication_{{ $stepIdStr }}" class="{{ $adjStepClsStr }}  {{ $skipLogicStepIdStr }} {{ $skipLogicSectionIdStr }} {{ $sectionClsStr }}">
    <div class="card p-2 mb-1">
        <input type="hidden" name="sectionId[]" value="{{ $section->id }}" />
            @foreach ($section->questions as $question)
            @php
            $fieldType = $question->form_field_type->field_type;
            if (($fieldType == 'Upload') || ($fieldType == 'Date & Time') || ($fieldType == 'Description')){
                continue;
            }
            if ($showAllQuestions == 'no'){
                if ((!in_array($question->id, $adjudicationRequiredQuestionIdsArray))){
                    continue;
                }
            }
            $showAverageIcon = false;
            if ($fieldType == 'Number' || $fieldType == 'Calculated') {
                $showAverageIcon = true;
            }

            $field_name = buildFormFieldName($question->formFields->variable_name);
            $questionIdStr = buildSafeStr($question->id, '');
            $skipLogicQuestionIdStr = buildSafeStr($question->id, 'skip_logic_');
            $fieldId = $field_name . '_' . $questionIdStr;
            $getFinalAnswerArray = [
                'study_id'=>$studyId,
                'subject_id'=>$subjectId,
                'study_structures_id'=>$phase->id,
                'phase_steps_id'=>$step->step_id,
                'section_id'=>$section->id,
                'question_id'=>$question->id,
                'field_id'=>$question->formfields->id,
            ];
            $finalAnswer = \Modules\FormSubmission\Entities\FinalAnswer::getFinalAnswer($getFinalAnswerArray);

            $is_required = ($question->formFields->is_required == 'yes')? 'required':'';
            $is_required_star = ($question->formFields->is_required == 'yes')? '<span class="text text-danger">*</span>':'';
            @endphp
            <div class="form-group adjudication-border"  id="adjudication_question_row_{{$questionIdStr}}">
                <label class="">{{ $question->question_text }} {!! $is_required_star !!}</label>
                <div class="row">
                    <div class="col-10">
                        <div class="row">
                            <div class="col-12">
                                @include('formsubmission::forms.main_form_fields.main_form_field_checks', ['fieldType'=>$fieldType, 'question'=> $question, 'field_name'=> $field_name,
                                'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $finalAnswer])
                            </div>
                            <div class="col-12"><hr class="hr-line"></div>

                @foreach ($graderIdsArray as $graderId)
                @php
                $grader = App\User::find($graderId);
                $getAnswerArray = [
                    'form_filled_by_user_id'=>$graderId,
                    'study_id'=>$studyId,
                    'subject_id'=>$subjectId,
                    'study_structures_id'=>$phase->id,
                    'phase_steps_id'=>$step->step_id,
                    'section_id'=>$section->id,
                    'question_id'=>$question->id,
                    'field_id'=>$question->formfields->id
                ];
                $answer = $question->getAnswer($getAnswerArray);
                $questionIdStr = buildSafeStr($question->id, '');
                $graderIdStr = buildSafeStr($graderId, '');

                $grader_field_name = $field_name . '_' . $graderIdStr;
                $grader_field_id = $field_name . '_' . $graderIdStr . '_' . $questionIdStr;

                $is_required = ($question->formFields->is_required == 'yes')? 'required':'';
                $is_required_star = ($question->formFields->is_required == 'yes')? '<span class="text text-danger">*</span>':'';
                @endphp
                <div class="col-{{$colNum}}">
                    <label class="">{{ $grader->name }} {!! $is_required_star !!}</label>
                    @include('formsubmission::forms.adjudication_form_fields.adjudication_form_field_checks', ['fieldType'=>$fieldType, 'field_name'=> $grader_field_name, 'questionIdStr'=>
                    $questionIdStr, 'copyToFieldId'=> $fieldId, 'fieldId'=> $grader_field_id, 'answer'=> $answer, 'is_required'=> $is_required])
                </div>
                @endforeach
                </div>
            </div>
            <div class="col-1">@include('formsubmission::forms.adjudication_form_fields.info_popup', ['question'=>$question])</div>
            @php
            $queryParams = [
                    'study_id'=>$studyId,
                    'subject_id'=>$subjectId,
                    'study_structures_id'=>$phase->id,
                    'phase_steps_id'=>$step->step_id,
                    'section_id'=>$section->id,
                    'question_id'=>$question->id,
                    'field_id'=>$question->formfields->id,
                    'form_type_id'=>$step->form_type_id,
                    'modility_id'=>$step->modility_id,
                    'module'=>'Adjudication Form',
            ];
            @endphp
            <div class="col-1">@include('formsubmission::forms.adjudication_form_fields.query_popup', ['queryParams'=>$queryParams, 'showAverageIcon'=>$showAverageIcon, 'fieldType'=>$fieldType, 'field_name'=> $grader_field_name, 'questionIdStr'=>
                $questionIdStr, 'copyToFieldId'=> $fieldId, 'fieldId'=> $grader_field_id, 'answer'=> $answer])</div>
        </div>
    </div>
            @endforeach
    </div>
</fieldset>
@endif
@else
<div class="alert alert-danger" role="alert">Form is in draft mode</div>
@endif
