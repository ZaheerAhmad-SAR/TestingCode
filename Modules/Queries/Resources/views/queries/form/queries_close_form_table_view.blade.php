@php $count = 1; @endphp
@foreach($records as $record)

@php
    $queryUsersIds = Modules\Queries\Entities\QueryUser::where('query_id',$record->id)->pluck('user_id')->toArray();
    $queryUsersNames = App\User::whereIn('id',$queryUsersIds)->pluck('name')->toArray();
    //dd($queryUsersNames);
    $querySubmitedBy = App\User::find($record->queried_remarked_by_id);
    $queryUsersString = implode(',',$queryUsersNames);

 @endphp
<tr>
    <td data-id="{{$record->id}}">{{$count++}}</td>
    <td>{{$record->query_subject}}</td>
    <td>{{$querySubmitedBy->name}}</td>
    <td>{{$queryUsersString}}</td>
    <td>{{date_format($record->created_at,'M-d-Y')}}</td>
    <td>{{$record->query_status}}</td>
    <td class="historyCloseFormQueries" data-id="{{$record->id}}" data-value="{{$record->query_status}}"><span style="cursor: pointer;"><i class="fab fa-rocketchat"></i></span></td>
</tr>
@endforeach


