@extends ('layouts.home')

@section('title')
    <title> Grading List | {{ config('app.name', 'Laravel') }}</title>
@stop

@section('styles')

    <style type="text/css">
        /*.table{table-layout: fixed;}*/

        .select2-container--default
        .select2-selection--single {
            background-color: #fff;
             border: transparent !important;
            border-radius: 4px;
        }
        .select2-selection__rendered {
            font-weight: 400;
            line-height: 1.5;
            color: #495057 !important;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
    </style>
    <!-- date range picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2-bootstrap.min.css') }}"/>
@endsection

@section('content')

    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Grading List</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Grading List</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <!-- Grading legends -->
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header  justify-content-between align-items-center">
                        <h4 class="card-title">Grading legend</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="{{url('images/no_status.png')}}"/>&nbsp;&nbsp;Not Initiated
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/incomplete.png')}}"/>&nbsp;&nbsp;Initiated
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/resumable.png')}}"/>&nbsp;&nbsp;Editing
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/complete.png')}}"/>&nbsp;&nbsp;Complete
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/not_required.png')}}"/>&nbsp;&nbsp;Not Required
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/query.png')}}"/>&nbsp;&nbsp;Query
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">

                  
                    <div class="card-body">

                        <div class="table-responsive">

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
                                    @if(!$subjects->isEmpty())
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
                                                    @php
                                                        $imageStr = '';

                                                        if ($status == 'complete') {
                                                            $imageStr .= '<img src="' . url('images/complete.png') . '" title="complete" />';
                                                        } elseif ($status == 'incomplete') {
                                                            $imageStr .= '<img src="' . url('images/incomplete.png') . '" title="incomplete"/>';
                                                        } elseif ($status == 'resumable') {
                                                            $imageStr .= '<img src="' . url('images/resumable.png') . '" title="resumable"/>';
                                                        } elseif ($status == 'no_status') {
                                                            $imageStr .= '<img src="' . url('images/no_status.png') . '" title="no_status"/>';
                                                        } elseif ($status == 'adjudication') {
                                                            $imageStr .= '<img src="' . url('images/adjudication.png') . '" title="adjudication"/>';
                                                        } elseif ($status == 'notrequired') {
                                                            $imageStr .= '<img src="' . url('images/not_required.png') . '" title="notrequired"/>';
                                                        }
                                                    @endphp
                                                    <td style="text-align: center;"><?php echo $imageStr ?></td>

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

                            {{$subjects->links()}}

                        </div>
                    </div>
                </div>
                <!-- Card ends -->
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

@endsection
@section('script')

<!-- date range picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- select2 -->
<script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/select2.script.js') }}"></script>

<script type="text/javascript">


</script>
@endsection




