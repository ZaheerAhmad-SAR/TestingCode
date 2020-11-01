<table class="table table-bordered" id="laravel_crud">
                                <thead>
                                    <tr class="table-secondary">
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
                                            <th colspan="{{count($steps)}}" class="border-bottom-0" style="text-align: center;">
                                                    {{$key}}
                                            </th>
                                            @endforeach
                                        @endif
                                    </tr>

                                    @if ($modalitySteps != null)
                                    <tr class="table-secondary">
                                        <th scope="col" colspan="4" class="border-top-0"> </th>
                                        </th>
                                        @foreach($modalitySteps as $steps)
                                        
                                            @foreach($steps as $value)
                                            <th scope="col" class="border-top-0" style="text-align: center;">
                                                  {{$value['form_type']}}
                                            </th>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                    @endif

                                </thead>

                               <!--  <thead>
                                    <tr>
                                        <th>Subject ID</th>
                                        <th>Phase</th>
                                        <th>Visit Date</th>
                                        <th>Site Name</th>
                                        <th>
                                            
                                               <td colspan="2">Tr</td> 
                                           
                                            
                                        </th>
                                        {{--
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
                                        <th colspan="4">
                                        </th>
                                        @foreach($modalitySteps as $steps)
                                        
                                            @foreach($steps as $value)
                                            <th>
                                                  {{$value['form_type']}}
                                            </th>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                    @endif
                                    --}}
                                </thead> -->
                                <tbody>
                                    @if(!$subjects->isEmpty() && request()->has('form_1'))

                                        @foreach($subjects as $key => $subject)
                                        <tr>
                                            <td>
                                               <a href="{{route('subjectFormLoader.showSubjectForm',['study_id' => $subject->study_id, 'subject_id' => $subject->id])}}" class="text-primary font-weight-bold">{{$subject->subject_id}}</a>
                                            </td>
                                            <td>{{$subject->phase_name}}</td>
                                            <td>{{date('Y-m-d', strtotime($subject->visit_date))}}</td>
                                            <td>{{$subject->site_name}}</td>
                                            
                                            @if($subject->form_status != null)
                                                @foreach($subject->form_status as $status)
                                                   
                                                    <td style="text-align: center;">

                                                        <a href="{{route('subjectFormLoader.showSubjectForm',['study_id' => $subject->study_id, 'subject_id' => $subject->id])}}" class="text-primary font-weight-bold">
                                                            
                                                            <?php echo $status; ?>
                                                        
                                                        </a>
                                                         
                                                    </td>

                                                @endforeach
                                            @endif
                                        </tr>
                                        @endforeach

                                    @elseif (!$subjects->isEmpty() && request()->has('form_2'))

                                        @foreach($subjects as $key => $subject)
                                        <tr>
                                            <td>
                                               <a href="{{route('subjectFormLoader.showSubjectForm',['study_id' => $subject->study_id, 'subject_id' => $subject->subj_id])}}" class="text-primary font-weight-bold">{{$subject->subject_id}}</a>
                                            </td>
                                            <td>{{$subject->phase_name}}</td>
                                            <td>{{date('Y-m-d', strtotime($subject->visit_date))}}</td>
                                            <td>{{$subject->site_name}}</td>
                                            
                                            @if($subject->form_status != null)
                                                @foreach($subject->form_status as $status)
                                                   
                                                    <td style="text-align: center;">

                                                        <a href="{{route('subjectFormLoader.showSubjectForm',['study_id' => $subject->study_id, 'subject_id' => $subject->subj_id])}}" class="text-primary font-weight-bold">
                                                            
                                                            <?php echo $status; ?>
                                                        
                                                        </a>
                                                         
                                                    </td>

                                                @endforeach
                                            @endif
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="{{$count}}" style="text-align: center;"> No record found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>