@php
$dataStr = '';
if(isset($queryParams)){
    $dataStr = "'" . implode("', '", $queryParams)."'";
}
@endphp
<span class="dropdown-item">
    <a href="javascript:void(0);" onclick="openFormQueryPopup({{ $dataStr }});">
        <i class="fas fa-question-circle" aria-hidden="true">
        </i> Queries</a>
</span>
