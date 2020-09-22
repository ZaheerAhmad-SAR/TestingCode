@if ($key === $first)
    <div class="d-flex">
        <button type="button" class="btn btn-primary nexttab ml-auto">Next</button>
    </div>
@elseif($key === $last)
    <div class="d-flex">
        <button type="button" class="btn btn-primary prevtab">Previous</button>
    </div>
@else
    <div class="d-flex">
        <button type="button" class="btn btn-primary prevtab">Previous</button>
        <button type="button" class="btn btn-primary nexttab ml-auto">Next</button>
    </div>
@endif
