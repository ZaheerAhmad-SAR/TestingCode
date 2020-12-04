@if($fieldType != 'Description')
<div class="d-flex mt-3 mt-md-0 ml-auto">
    <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
    <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
        @include('queries::queries.query_popup_span_form', ['queryParams'=>$queryParams, 'study_id'=> (isset($study) && null !== $study)? $study->id:''])
    </div>
    @if(\Modules\Queries\Entities\Query::questionHasQuery($queryParams))
        <div class="showQueries">
        <span class="ml-3" style="cursor: pointer;">
            @php
                $dataStr = '';
                if(isset($queryParams)){
                    $dataStr = "'" . implode("', '", $queryParams)."'";
                }
            @endphp
            <i class="fas fa-question-circle showAllQuestionQueries" onclick="getAllQuestionQueryData({{ $dataStr }});"  style="margin-top: 12px; position: absolute;left: 0;"></i>
        </span>
        </div>
        @endif
</div>
@endif
