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
    <div class="col-md-6"></div>
    <div class="col-md-6 text-right">
        @if(\Modules\Queries\Entities\Query::formHasQuery($queryParams)>0)

            <div class="formQueries">
        <span class="ml-3" style="cursor: pointer;">
             @php
                 $dataStr = '';
                 if(isset($queryParams)){
                     $dataStr = "'" . implode("', '", $queryParams)."'";

                 }
             @endphp
            <i class="fas fa-question-circle showAllFormQueries"  onclick="getAllFormQueryData({{$dataStr}});"  style="color: red;
    position: absolute;
    right: 24%;
    bottom: 0%;
    margin-bottom: 8px;"></i>
        </span>
            </div>

        @elseif(\Modules\Queries\Entities\Query::formStatusHasClose($queryParams))

        <div class="closeForm">
        <span class="ml-3" style="cursor: pointer;">
            @php
                $dataStr = '';
                if(isset($queryParams)){
                    $dataStr = "'" . implode("', '", $queryParams)."'";
                }

            //echo('false');
            @endphp
            <i class="fas fa-check-circle showCloseFormQueryPopUp" onclick="showCloseFormQueries({{ $dataStr }});"  style="position: absolute;
    right: 24%;
    bottom: 0%;
    margin-bottom: 8px; color: green;"></i>

        </span>
                </div>
        @else
        @endif
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Queries</button>
            <div class="dropdown-menu p-0">
            <a class="dropdown-item" href="javascript:void(0);" onclick="openPopupForFormModal({{ $dataStr }});">
                <i class="fas fa-question-circle" aria-hidden="true"></i> Form Query
            </a>
        </div>
    </div>
</div>
