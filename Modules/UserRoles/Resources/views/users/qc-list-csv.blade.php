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
                        $colspan = 0;
                    @endphp
                    
                    @foreach($steps as $value)

                        @php
                            $count = $count + 2;
                            $colspan = $colspan + 2;
                        @endphp
                    
                    @endforeach
                
                <th colspan="{{ $colspan }}">
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
                <th colspan="2">
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
                    
                        @if ($status != null)
                            <!-- explode on Pipe  -->
                            @php
                                $trimStatusPipe = rtrim($status, '|');
                                $explodedStatus = explode('-', $trimStatusPipe);
                            @endphp

                            @foreach($explodedStatus as $explodeStatus)  
                                    <td>{{ $explodeStatus }}</td>
                            @endforeach

                        @endif

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





