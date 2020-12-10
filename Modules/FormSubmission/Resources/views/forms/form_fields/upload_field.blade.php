<input type="file" name="{{ $field_name }}[]" id="{{ $fieldId.'_'.$stepIdStr }}"
                onchange="validateAndUploadFiles('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', {{ $step->form_type_id }}, '{{ $field_name }}', '{{ $fieldId }}');"
                class="form-control-ocap bg-transparent {{ $skipLogicQuestionIdStr }}" {{ $is_required }} multiple>
<div id="{{ 'file_upload_files_div_' . $fieldId }}">
    @php
    $filesArray = explode('<<|!|>>', $answer->answer);
    $linkStr = '';
    foreach ($filesArray as $file) {
        if($file != ''){
            $linkStr .= '<a href="' . url('/') . $file . '" target="_blank">' . url('/') . $file . '</a><br>';
        }
    }
    echo $linkStr;
    @endphp
</div>
