@php $querySubmitedBy = App\User::find($query->queried_remarked_by_id);@endphp

<div class="m-2">
    {!! Modules\Queries\Entities\Query::buildHtmlForQuerySubmitter($querySubmitedBy, $query) !!}

    @foreach ($answers as $answer)
        @php
        $answerSubmitedBy = App\User::find($answer->queried_remarked_by_id);
        @endphp
        @if($query->queried_remarked_by_id == $answer->queried_remarked_by_id)
            {!! Modules\Queries\Entities\Query::buildHtmlForQuerySubmitter($querySubmitedBy, $answer) !!}
        @else
            {!! Modules\Queries\Entities\Query::buildHtmlForQueryAnswer($querySubmitedBy, $answer) !!}
        @endif

    @endforeach

</div>





{{-- <div class="form-group row">--}}
    {{-- <label for="Name"
        class="col-sm-2 col-form-label">Status</label>--}}
    {{-- <div class="col-sm-10">--}}
        {{-- <select class="form-control" id="query_status"
            name="query_status">--}}
            {{-- <option value="open">open</option>--}}
            {{-- <option value="close">close</option>--}}
            {{-- </select>--}}
        {{-- </div>--}}
    {{-- </div>--}}
<div class="malwareData">
    <input type="hidden" name="module_id" id="module_id" value="{{ $query->module_id }}">
    <input type="hidden" name="query_type" id="query_type" value="{{ $query->query_type }}">
    <input type="hidden" name="query_id" id="query_id" value="{{ $query->id }}">
    <input type="hidden" name="query_url" id="query_url" value="{{ $query->query_url }}">
    <input type="hidden" name="query_subject" id="query_subject" value="{{ $query->query_subject }}">
</div>

<script src="{{ asset('dist/vendors/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('dist/js/summernote.script.js') }}"></script>
