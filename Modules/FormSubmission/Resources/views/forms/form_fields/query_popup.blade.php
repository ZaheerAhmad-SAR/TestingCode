@if($fieldType != 'Description')
<div class="d-flex mt-3 mt-md-0 ml-auto">
    <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
    <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
        @include('queries::queries.query_popup_span_form', ['queryParams'=>$queryParams, 'study_id'=> (isset($study) && null !== $study)? $study->id:''])
        <span class="dropdown-item">
            <a href="javascript:void(0);" onclick="loadAddQuestionCommentForm('{{ $studyId }}', '{{ $subjectId }}', '{{ $phase->id }}', '{{ $step->step_id }}', '{{ $section->id }}', '{{ $question->id }}');">
                <i class="fas fa-comments" aria-hidden="true">
                </i> Add Comment</a>
        </span>
    </div>
    @if(\Modules\Queries\Entities\Query::questionHasQueryDemo($queryParams))
        <div class="showQueries">
        <span class="ml-3" style="cursor: pointer;">
            @php
                $dataStr = '';
                if(isset($queryParams)){
                    $dataStr = "'" . implode("', '", $queryParams)."'";
                }
            @endphp
            <i class="fas fa-question-circle showAllQuestionQueries" onclick="getAllQuestionQueryData({{ $dataStr }});"  style="margin-top: 12px; position: absolute;left: 10px; color: red;"></i>
        </span>
        </div>
    @elseif(\Modules\Queries\Entities\Query::questionStatusHasClose($queryParams))
        <div class="closeQuestion">
        <span class="ml-3" style="cursor: pointer;">
            @php
                $dataStr = '';
                if(isset($queryParams)){
                    $dataStr = "'" . implode("', '", $queryParams)."'";
                }

            //echo('false');
            @endphp
            <i class="fas fa-check-circle showCloseQuestionPopUp" onclick="showCloseQuestionQueries({{ $dataStr }});"  style="margin-top: 12px; position: absolute;left: 5px; color: green;"></i>

        </span>
        </div>
        @else
        @endif
{{--    @endif--}}

</div>
@endif
