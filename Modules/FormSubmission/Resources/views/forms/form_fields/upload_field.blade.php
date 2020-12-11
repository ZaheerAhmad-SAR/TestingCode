@php
    $filesArray = explode('<<|!|>>', $answer->answer);
    $linkStr = '';
    foreach ($filesArray as $file) {
        if($file != ''){
            $linkStr .= '<div id="'.$file.'"><a href="' . url('/') . '/form_files/' . $file . '" target="_blank">' . $file . '</a>&nbsp;&nbsp;<img onclick="deleteFormUploadFile(\''.$answer->id.'\', \''.$file.'\');" src="'. asset('images/remove.png').'"/></div>';
        }
    }
@endphp
<code>Max upload file size : {{ return_bytes(ini_get('upload_max_filesize'))/(1024*1024) }}</code>
<input type="file" name="{{ $field_name }}[]" id="{{ $fieldId.'_'.$stepIdStr }}"
                onchange="validateAndUploadFiles('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', {{ $step->form_type_id }}, '{{ $field_name }}', '{{ $fieldId }}');"
                class="form-control-ocap bg-transparent {{ $skipLogicQuestionIdStr }}" {{ $is_required }} multiple>
<div id="{{ 'file_upload_files_div_' . $fieldId }}">{!! $linkStr !!}</div>
