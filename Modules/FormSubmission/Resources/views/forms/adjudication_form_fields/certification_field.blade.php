@php
if ($ques_value->certification_type == 'devices') {
    $options = DB::connection('mysql2')->table('certify_device')->select('certify_device.*', DB::Raw('GROUP_CONCAT(trans_no SEPARATOR ",") as transmissions'), DB::Raw('GROUP_CONCAT(c_id SEPARATOR ",") as IDs'), DB::Raw('GROUP_CONCAT(status SEPARATOR ",") as statuses'), DB::Raw('GROUP_CONCAT(certification_officerName SEPARATOR ",") as certification_officerNames'))->groupBy('certify_device.device_categ')->where('certify_device.study_id', session('study_code'))->get();
} else {
    $options = DB::connection('mysql2')->table('photographer_data')->select('photographer_data.*', DB::Raw('CONCAT(first_name, " ", last_name) as photographer_name'), DB::Raw('GROUP_CONCAT(transmission_number SEPARATOR ",") as transmissions'), DB::Raw('GROUP_CONCAT(id SEPARATOR ",") as IDs'), DB::Raw('GROUP_CONCAT(status SEPARATOR ",") as statuses'), DB::Raw('GROUP_CONCAT(certification_officerName SEPARATOR ",") as certification_officerNames'))->groupBy('photographer_name')->where('photographer_data.study_id', session('study_code'))->get();
}
@endphp
<select name="{{ $field_name }}" id="{{ $fieldId }}"
    onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', {{ $step->form_type_id }}, '{{ $field_name }}', '{{ $fieldId }}');"
    class="form-control-ocap bg-transparent {{ $skipLogicQuestionIdStr }}"  {{ $is_required }}>
    @foreach ($options as $option_name => $option_value)
        <option value="{{ $option_value }}" {{ $answer->answer == $option_value ? 'selected' : '' }}>
            {{ $option_name }}
        </option>
    @endforeach
</select>
