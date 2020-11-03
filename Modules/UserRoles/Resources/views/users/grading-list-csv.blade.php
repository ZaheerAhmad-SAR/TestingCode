<table>
    <thead>
        <tr>
            <th>Subject ID</th>
            <th>Phase</th>
            <th>Visit Date</th>
            <th>Site Name</th>
            @php
                $count = 4;
            @endphp

            @if ($modalitySteps != null)
                @foreach($modalitySteps as $key => $steps)
                @php
                    $count = $count + count($steps);
                @endphp
                <th colspan="{{count($steps)}}">
                        {{$key}}
                </th>
                @endforeach
            @endif
        </tr>

        @if ($modalitySteps != null)
        <tr>
            <th colspan="4"> </th>
            @foreach($modalitySteps as $steps)
            
                @foreach($steps as $value)
                <th>
                      {{$value['form_type']}}
                </th>
                @endforeach
            @endforeach
        </tr>
        @endif

    </thead>

    <tbody>
        @if(!$subjects->isEmpty())

            @foreach($subjects as $key => $subject)
            <tr>
                <td>
                   {{$subject->subject_id}}
                </td>
                <td>{{$subject->phase_name}}</td>
                <td>{{date('Y-m-d', strtotime($subject->visit_date))}}</td>
                <td>{{$subject->site_name}}</td>
                
                @if($subject->form_status != null)
                    @foreach($subject->form_status as $status)
                       
                    <td>{{ $status }}</td>

                    @endforeach
                @endif
            </tr>
            @endforeach

        @else
        <tr>
            <td colspan="{{$count}}"> No record found.</td>
        </tr>
        @endif
    </tbody>
</table>