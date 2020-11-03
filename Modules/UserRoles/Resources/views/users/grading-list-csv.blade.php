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

                        @if ($value['form_type'] != 'Grading')
                            @php
                                $count = $count + 2;
                                $colspan = $colspan + 2;
                            @endphp
                        @else
                            @php
                                $count = $count + 6;
                                $colspan = $colspan + 6;
                            @endphp
                        @endif

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
                <th @if( $value['form_type'] == 'Grading') colspan="6" @else colspan="2" @endif>
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
                    @foreach($subject->form_status as $form => $status)

                    @if ($form != null)
                        @php
                            $explodedForm = explode('_', $form);
                        @endphp

                        @if($explodedForm[1] == 'Grading')
                            
                            @if ($status != null)
                                <!-- explode on Pipe  -->
                                @php
                                    $trimStatusPipe = rtrim($status, '|');
                                    $explodedStatus = explode('|', $trimStatusPipe);
                                @endphp

                                @foreach($explodedStatus as $explodeStatus)
                                    
                                    <!-- explode on dash -->
                                    @php
                                    $explodeOnDash = explode('-', $explodeStatus);
                                    @endphp

                                    @foreach($explodeOnDash as $onDashStatus)
                                        <td>{{ $onDashStatus }}</td>
                                    @endforeach

                                @endforeach

                            @endif

                        @else

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
                        @endif

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