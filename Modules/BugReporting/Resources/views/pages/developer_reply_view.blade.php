@php $querySubmitedBy = App\User::find($query->bug_reporter_by_id);@endphp

<div class="m-2">
    {!! Modules\BugReporting\Entities\BugReport::buildHtmlForQuerySubmitter($querySubmitedBy, $query) !!}

    @foreach ($answers as $answer)
        @php
        $answerSubmitedBy = App\User::find($answer->bug_reporter_by_id);
        @endphp
        @if($query->bug_reporter_by_id == $answer->bug_reporter_by_id)
            {!! Modules\BugReporting\Entities\BugReport::buildHtmlForQuerySubmitter($answerSubmitedBy, $answer) !!}
        @else
            {!! Modules\BugReporting\Entities\BugReport::buildHtmlForQueryAnswer($answerSubmitedBy, $answer) !!}
        @endif

    @endforeach

</div>




{{--<div class="malwareData">--}}

{{--    <input type="hidden" name="study_id" id="study_id" value="{{ $query->study_id }}">--}}
{{--    <input type="hidden" name="subject_id" id="subject_id" value="{{ $query->subject_id }}">--}}
{{--    <input type="hidden" name="phase_steps_id" id="phase_steps_id" value="{{ $query->phase_steps_id }}">--}}
{{--    <input type="hidden" name="section_id" id="section_id" value="{{ $query->section_id }}">--}}
{{--    <input type="hidden" name="question_id" id="question_id" value="{{ $query->question_id }}">--}}
{{--    <input type="hidden" name="field_id" id="field_id" value="{{ $query->field_id }}">--}}
{{--    <input type="hidden" name="form_type_id" id="form_type_id" value="{{ $query->form_type_id }}">--}}
{{--    <input type="hidden" name="modility_id" id="modility_id" value="{{ $query->modility_id }}">--}}
{{--    <input type="hidden" name="module_name" id="module_name" value="{{ $query->module_name }}">--}}
{{--    <input type="hidden" name="study_structures_id" id="study_structures_id" value="{{ $query->study_structures_id }}">--}}

{{--    <input type="hidden" name="query_type" id="query_type" value="{{ $query->query_type }}">--}}
{{--    <input type="hidden" name="query_id" id="query_id" value="{{ $query->id }}">--}}
{{--    <input type="hidden" name="query_url" id="query_url" value="{{ $query->query_url }}">--}}
{{--    <input type="hidden" name="subject_question" id="subject_question" value="{{ $query->query_subject }}">--}}
{{--    <input type="hidden" name="query_level_question" id="query_level_question" value="{{ $query->query_level }}">--}}
{{--</div>--}}

