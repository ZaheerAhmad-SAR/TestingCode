@php
$dataStr = '';
if(isset($queryParams)){
    foreach($queryParams as $paramKey => $paramValue){
        $dataStr .= ' data-'.$paramKey.'="'.$paramValue.'"';
    }
}
@endphp
<span class="dropdown-item">
    <a href="javascript:void(0)" {!! $dataStr !!} data-id="{{ $study_id }}" class="create-new-queries">
        <i class="fas fa-question-circle" aria-hidden="true">
        </i> Queries</a>
</span>
