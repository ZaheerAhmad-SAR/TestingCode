@if($step->is_active == 1)
@php
$showAllQuestions = request('showAllQuestions', 'no');
@endphp

@if ($key == 0 && $first == 0 && $last == 0)
    @include('formsubmission::forms.next_previous.adj_edit_form')
    @include('formsubmission::forms.next_previous.adj_submit_form')
@elseif ($key == $first)
    <div class="d-flex">
        <button type="button"
            class="btn btn-primary nexttab ml-auto {{ $studyClsStr }} {{ $stepClsStr }} {{ $sectionClsStr }}">Next</button>
    </div>
    @include('formsubmission::forms.next_previous.adj_edit_form')
@elseif($key == $last)
    <div class="d-flex">
        <button type="button" class="btn btn-primary prevtab">Previous</button>
    </div>
    @include('formsubmission::forms.next_previous.adj_submit_form')
@else
    <div class="d-flex">
        <button type="button" class="btn btn-primary prevtab">Previous</button>
        <button type="button" class="btn btn-primary nexttab ml-auto {{ 'next_' . $sectionClsStr }}">Next</button>
    </div>
@endif
@endif
