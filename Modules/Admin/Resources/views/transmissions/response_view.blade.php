@php
    $querySubmitedBy = App\User::where('email','=',$query['notification_remarked_id'])->first();

@endphp

<div class="m-2">
    {!! Modules\Queries\Entities\QueryNotification::buildHtmlForQuerySubmitter($querySubmitedBy, $query) !!}

    @foreach ($answers as $answer)
        @php
            //$answerSubmitedBy = App\User::find($answer->queried_remarked_by_id);
            $answerSubmitedBy=Modules\Queries\Entities\QueryNotification::
            where('notification_remarked_id','=',$answer->notification_remarked_id)->first();


        @endphp
        @if($query->notification_remarked_id == $answer->notification_remarked_id)
{{--            {{dd($answerSubmitedBy)}}--}}
            {!! Modules\Queries\Entities\QueryNotification::buildHtmlForQuerySubmitter($answerSubmitedBy, $answer) !!}
        @else
            {!! Modules\Queries\Entities\QueryNotification::buildHtmlForQueryAnswer($answerSubmitedBy, $answer) !!}
        @endif

    @endforeach

</div>

<div class="form-group row replyResponseInput" style="display: none;">
    <label for="Name" class="col-sm-2 col-form-label">Enter your Query</label>
    <div class="col-sm-10">
        <textarea class="form-control" name="reply_response" id="reply_response"></textarea>
    </div>
</div>
<div class="form-group row responseAttachmentInput" style="display: none;" >
    <label for="Attachment" class="col-sm-2 col-form-label">Attachment:</label>
    <div class="col-sm-10">
        <input class="form-control" type="file" name="responseAttachment"  id="attachment_R">
    </div>
</div>

@php
    $toUsersEmailAddress = Modules\Queries\Entities\QueryNotificationUser::where('query_notification_id','=',$query->id)->first();

 @endphp
<div class="malwareData">
    <input type="hidden" name="notification_remarked_id" id="notification_remarked_id" value="{{ $query->notification_remarked_id }}">

    <input type="hidden" name="mailToUserAddress" id="mailToUserAddress" value="{{ $toUsersEmailAddress->query_notification_user_id }}">
{{--    <input type="hidden" name="notifications_token" id="notifications_token" value="{{ $query->notifications_token }}">--}}
    <input type="hidden" name="query_id_response" id="query_id_response" value="{{ $query->id }}">

    <input type="hidden" name="cc_email_response" id="cc_email_response" value="{{ $query->cc_email }}">
    <input type="hidden" name="email_subject_response" id="email_subject_response" value="{{ $query->subject }}">
    <input type="hidden" name="study_id_response" id="study_id_response" value="{{ $query->study_id }}">
    <input type="hidden" name="subject_id_response" id="subject_id_response" value="{{ $query->subject_id }}">
    <input type="hidden" name="transmission_number_response" id="transmission_number_response" value="{{ $query->transmission_number }}">
    <input type="hidden" name="vist_name_response" id="vist_name_response" value="{{ $query->vist_name }}">
    <input type="hidden" name="study_short_name_response" id="study_short_name_response" value="{{ $query->study_short_name }}">
    <input type="hidden" name="site_name_response" id="site_name_response" value="{{ $query->site_name }}">
</div>
