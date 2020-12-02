@php
    $querySubmitedBy = App\User::where('email','=',$query['notification_remarked_id'])->first();
@endphp

<div class="m-2">
    {!! Modules\Queries\Entities\QueryNotification::buildHtmlForQuerySubmitter($querySubmitedBy, $query) !!}

    @foreach ($answers as $answer)
        @php
            //$answerSubmitedBy = App\User::find($answer->queried_remarked_by_id);
            $answerSubmitedBy=Modules\Queries\Entities\QueryNotification::
            where('notification_remarked_id','=',$answer->notification_remarked_id)->get();

        @endphp
        @if($query->notification_remarked_id == $answer->notification_remarked_id)
            {!! Modules\Queries\Entities\QueryNotification::buildHtmlForQuerySubmitter($answerSubmitedBy, $answer) !!}
        @else
            {!! Modules\Queries\Entities\QueryNotification::buildHtmlForQueryAnswer($answerSubmitedBy, $answer) !!}
        @endif

    @endforeach

</div>

<div class="form-group row commentsInput" style="display: none;">
    <label for="Name" class="col-sm-2 col-form-label">Enter your Query</label>
    <div class="col-sm-10">
        <textarea class="form-control" name="reply" id="reply"></textarea>
    </div>
</div>
<div class="form-group row queryAttachments" style="display: none;" >
    <label for="Attachment" class="col-sm-2 col-form-label">Attachment:</label>
    <div class="col-sm-10">
        <input class="form-control" type="file" name="query_file"  id="query_file">
    </div>
</div>

{{--<div class="malwareData">--}}
{{--    <input type="hidden" name="notification_remarked_id" id="notification_remarked_id" value="{{ $query->notification_remarked_id }}">--}}
{{--    <input type="hidden" name="notifications_token" id="notifications_token" value="{{ $query->notifications_token }}">--}}
{{--    <input type="hidden" name="query_id" id="query_id" value="{{ $query->id }}">--}}
{{--    <input type="hidden" name="cc_email" id="cc_email" value="{{ $query->cc_email }}">--}}
{{--    <input type="hidden" name="subject" id="subject" value="{{ $query->subject }}">--}}
{{--</div>--}}


{{--<div class="well">--}}
{{--    <label for=""><strong>Query</strong></label> <br>--}}
{{-- {{$query['email_body']}}--}}
{{--</div>--}}
{{--<hr>--}}
{{--<div class="well">--}}
{{--    @foreach($answers as $answer)--}}
{{--    <label for=""><strong>Response</strong></label> <br>--}}
{{--        {{$answer['email_body']}} <br>--}}
{{--        <hr>--}}
{{--    @endforeach--}}
{{--</div>--}}
