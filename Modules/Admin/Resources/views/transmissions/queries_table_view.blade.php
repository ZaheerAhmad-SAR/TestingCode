{{--@php $count = 1; @endphp--}}
@foreach($records as $record)

@php
    $queryUsersIds = Modules\Queries\Entities\QueryNotificationUser::where('query_notification_id',$record->id)->pluck('query_notification_user_id')->toArray();
    $querySubmitedBy = App\User::where('email','like',$record->notification_remarked_id)->first();
    $queryUsersString = implode(',',$queryUsersIds);


 @endphp


<tr>
{{--    <td>{{$count++}}</td>--}}
    <td>{{$record->subject}}</td>
    <td>{{$querySubmitedBy->name}}</td>
    <td>{{$queryUsersString}}</td>
    <td>{{date_format($record->created_at,'M-d-Y')}}</td>
    <td class="checkTransmissionResponse" data-id="{{$record->id}}" data-value=""><span style="cursor: pointer;"><i class="fab fa-rocketchat"></i></span></td>
</tr>
@endforeach


