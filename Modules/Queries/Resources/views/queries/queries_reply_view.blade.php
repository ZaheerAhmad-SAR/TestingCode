@php $querySubmitedBy = App\User::find($query->queried_remarked_by_id);@endphp

<div class="m-2">
    <div class="row text-left">
        <div class="col-md-1">
            <img class="mr-3" style="width: 25px; height: 25px; border-radius: 50%;"
                src="{{ url($querySubmitedBy->profile_image) }}" />
        </div>
        <div class="col-md-11">
            <b class="mt-0">{{ ucfirst($querySubmitedBy->name) }} <i class="fas fa-circle"
                    style="color: lightgreen; font-size:8px;"></i> <br></b>
            {{ strip_tags($query->messages) }} <br>
            <p style="padding: 10px">{{ date_format($query->created_at, 'jS-Y-h:i A') }}</p>
        </div>
    </div>

    @foreach ($answers as $answer)
        @php
        $answerSubmitedBy = App\User::find($answer->queried_remarked_by_id);
        $text_right_left = ($query->queried_remarked_by_id == $answer->queried_remarked_by_id) ? 'text-left' : 'text-right';
        @endphp
        <div class="row {{ $text_right_left }}">
            <div class="col-md-11">
                <b class="mt-0">{{ ucfirst($answerSubmitedBy->name) }} <i class="fas fa-circle"
                        style="color: lightgreen; font-size:8px;"></i> <br></b>
                {{ strip_tags($answer->messages) }} <br>
                <p style="padding: 10px">{{ date_format($answer->created_at, 'jS-Y-h:i A') }}</p>
            </div>
            <div class="col-md-1">
                <img class="mr-3" style="width: 25px; height: 25px; border-radius: 50%;"
                    src="{{ url((string)$answerSubmitedBy->profile_image) }}" />
            </div>
        </div>
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
