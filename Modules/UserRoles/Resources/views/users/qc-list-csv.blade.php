<table>
    <thead>
        <tr>
            <th><b> Subject ID </b></th>
            <th><b> Phase </b></th>
            <th><b> Visit Date </b></th>
            <th><b> Site Name </b></th>

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
                    <b>  {{$key}}  </b>
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
                    <b>  {{$value['form_type']}}  </b>
                </th>
                @endforeach
            @endforeach
        </tr>
        @endif

        <!-- for showing user name and status row below -->
        @if ($modalitySteps != null)
        <tr>
            <th colspan="4"> </th>
            @foreach($modalitySteps as $steps)
            
                @foreach($steps as $value)
                <th><b> User Name </b></th>
                <th><b> Status </b></th>
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
                <td>{{ date('d-M-Y', strtotime($subject->visit_date))}}</td>
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





