@foreach($results as $result)
    <tr id="{{$result['id']}}">
        <td>{{$result['device_name']}}</td>
        <td>{{$result['device_serial_no']}}</td>
        <td><i style="color: #EA4335;" class="fa fa-trash deletedevice" data-id="{{$result['id']}}"></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" data-id="{{$result['id']}}" class="icon-pencil editdevice"></i></td>
    </tr>
@endforeach
