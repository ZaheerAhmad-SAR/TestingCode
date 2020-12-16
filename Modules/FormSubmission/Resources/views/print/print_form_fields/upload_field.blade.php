@php
    $filesArray = explode('<<|!|>>', $answer->answer);
    $linkStr = '';
    foreach ($filesArray as $file) {
        if($file != ''){
            $linkStr .= '<div id="'.$file.'">' . $file . '</div>';
        }
    }
@endphp
<div id="{{ $fieldId }}" class="form-control-ocap bg-transparent">{!! $linkStr !!}</div>
