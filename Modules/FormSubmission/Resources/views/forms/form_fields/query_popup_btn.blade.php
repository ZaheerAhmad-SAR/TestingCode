@php
$dataStr = '';
if(isset($queryParams)){
    $dataStr = "'" . implode("', '", $queryParams)."'";
}
@endphp
<div class="row">
    <div class="col-md-12">&nbsp;</div>
</div>
<div class="row">
    <div class="col-md-10"></div>
    <div class="col-md-2 text-right btn-group mb-3">
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Queries</button>
            <div class="dropdown-menu p-0">
            <a class="dropdown-item" href="javascript:void(0);" onclick="openFormQueryPopup({{ $dataStr }});">
                <i class="fas fa-question-circle" aria-hidden="true"></i> Queries
            </a>
        </div>
    </div>
</div>
