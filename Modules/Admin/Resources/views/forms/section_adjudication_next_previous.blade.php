@if ($key === $first)
    <div class="d-flex">
        <button type="button" class="btn btn-primary nexttab ml-auto">Next</button>
    </div>
@elseif($key === $last)
    <div class="d-flex">
        <button type="button" class="btn btn-primary prevtab">Previous</button>
    </div>
    @if ((bool) $subjectId)
        <div class="row">
            <div class="col-md-12">&nbsp;</div>
        </div>
        <div class="row">
            <div class="col-md-11">&nbsp;</div>
            <div class="col-md-1">
                <button type="button" class="btn btn-success float-right"
                    onclick="submitAdjudicationStepForm{{ $stepIdStr }}('{{ $stepIdStr }}', '{{ $stepClsStr }}');"
                    id="submit_adjudication_{{ $stepIdStr }}">Submit</button>
            </div>
        </div>
    @endif
@else
    <div class="d-flex">
        <button type="button" class="btn btn-primary prevtab">Previous</button>
        <button type="button" class="btn btn-primary nexttab ml-auto">Next</button>
    </div>
@endif
