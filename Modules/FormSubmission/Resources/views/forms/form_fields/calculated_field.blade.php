@php
$firstQuestion = \Modules\Admin\Entities\Question::find($question->first_question_id);
$secondQuestion = \Modules\Admin\Entities\Question::find($question->second_question_id);

$firstFieldName = buildFormFieldName($firstQuestion->formFields->variable_name);
$firstQuestionIdStr = buildSafeStr($firstQuestion->id, '');
$firstFieldId = $firstFieldName . '_' . $firstQuestionIdStr;

if(null !== $secondQuestion){
    $secondFieldName = buildFormFieldName($secondQuestion->formFields->variable_name);
    $secondQuestionIdStr = buildSafeStr($secondQuestion->id, '');
    $secondFieldId = $secondFieldName . '_' . $secondQuestionIdStr;
}else{
    $secondFieldName = '';
    $secondQuestionIdStr = '';
    $secondFieldId = '';
}
@endphp
<input type="text" name="{{ $field_name }}" id="{{ $fieldId }}"
    onfocus="calculateField('{{ $firstFieldId }}', '{{ $secondFieldId }}', '{{ $question->operator_calculate }}', '{{ $question->make_decision }}', '{{ $question->calculate_with_costum_val }}', '{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', {{ $step->form_type_id }}, '{{ $field_name }}', '{{ $fieldId }}');"
    value="{{ $answer->answer }}" class="form-control-ocap bg-transparent" {{ $is_required }}>
