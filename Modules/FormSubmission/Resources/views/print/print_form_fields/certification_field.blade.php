@php
if ($question->certification_type == 'devices') {
    $list = DB::connection('mysql2')->table('certify_device')->select('certify_device.*', DB::Raw('GROUP_CONCAT(trans_no SEPARATOR ",") as transmissions'), DB::Raw('GROUP_CONCAT(c_id SEPARATOR ",") as IDs'), DB::Raw('GROUP_CONCAT(status SEPARATOR ",") as statuses'), DB::Raw('GROUP_CONCAT(certification_officerName SEPARATOR ",") as certification_officerNames'))->groupBy('certify_device.device_categ')->where('certify_device.study_id', session('study_code'))->get();
    foreach ($list as $key => $item) {
        $value = $item->device_sn . ' && ' . $item->device_model . ' && ' . $item->device_categ;
        $options[$value] = $value;
    }
} else {
    $list = DB::connection('mysql2')->table('photographer_data')->select('photographer_data.*', DB::Raw('CONCAT(first_name, " ", last_name) as photographer_name'), DB::Raw('GROUP_CONCAT(transmission_number SEPARATOR ",") as transmissions'), DB::Raw('GROUP_CONCAT(id SEPARATOR ",") as IDs'), DB::Raw('GROUP_CONCAT(status SEPARATOR ",") as statuses'), DB::Raw('GROUP_CONCAT(certification_officerName SEPARATOR ",") as certification_officerNames'))->groupBy('photographer_name')->where('photographer_data.study_id', session('study_code'))->get();
    foreach ($list as $key => $item) {
        $name = $item->first_name . ' ' . $item->last_name;
        $value = $name . ' && ' . $item->imaging_modality_req;
        $options[$value] = $value;
    }
}
@endphp
<div id="{{ $fieldId }}" class="form-control-ocap bg-transparent">
@foreach ($options as $option_name => $option_value)
    @if($answer->answer == $option_value)
    {{ $option_name }}<br>
    @endif
@endforeach
</div>
