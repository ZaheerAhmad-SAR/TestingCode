@extends ('layouts.home')

@section('title')
    <title> Adjudication List | {{ config('app.name', 'Laravel') }}</title>
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

        legend {
          /*background-color: gray;
          color: white;*/
          padding: 5px 10px;
        }
    </style>

     <!-- hide form on the basis of request -->
    @if (request()->has('form_1'))
        <style>
            .form-2{
                display: none;
            }
        </style>
    @elseif (request()->has('form_2'))
        <style>
            .form-1{
                display: none;
            }
        </style>
    @else
        <style>
            .form-2{
                display: none;
            }
        </style>
    @endif

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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Adjudication List</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Adjudication List</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <!-- Adjudication legends -->
            
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">

                    <div class="form-group col-md-12 mt-3">        
                        <button type="button" class="btn btn-primary other-filters">Other Filters</button>
                        <button type="button" class="btn btn-primary reset-filter">Reset</button>

                        @if (!$subjects->isEmpty())
                        <span style="float: right; margin-top: 3px;" class="badge badge-pill badge-primary">
                            {{ $subjects->count().' out of '.$subjects->total() }}
                        </span>
                        @endif
                        
                    </div>
                    <hr>
                    <!-- Other Filters ends -->

                    <form action="{{route('adjudication.index')}}" method="get" class="form-1 filter-form">
                        <div class="form-row" style="padding: 10px;">

                            <input type="hidden" name="form_1" value="1" class="form-control">

                            <div class="form-group col-md-3">
                                <label for="inputState">Subject</label>
                                <select id="subject" name="subject" class="form-control filter-form-data">
                                    <option value="">All Subject</option>
                                    @foreach($getFilterSubjects as $filterSubject)
                                    <option @if(request()->subject == $filterSubject->id) selected @endif value="{{ $filterSubject->id }}">{{ $filterSubject->subject_id }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="inputState">Phase</label>
                                <select id="phase" name="phase" class="form-control filter-form-data">
                                    <option value="">All Phase</option>
                                    @foreach($getFilterPhases as $filterPhase)
                                    <option  @if(request()->phase == $filterPhase->id) selected @endif value="{{ $filterPhase->id }}">{{ $filterPhase->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            
                            <div class="form-group col-md-2">
                            
                                <label for="inputState">Site</label>
                                <select id="site" name="site" class="form-control filter-form-data">
                                    <option value="">All Site</option>
                                     @foreach($getFilterSites as $filterSite)
                                     <option @if(request()->site == $filterSite->id) selected @endif value="{{ $filterSite->id }}">{{ $filterSite->site_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                           
                            <div class="form-group col-md-3">
                                <label for="dt">Visit Date</label>
                                <input type="text" name="visit_date" id="visit_date" class="form-control visit_date filter-form-data" value="{{ request()->visit_date }}">
                            </div>

                            <div class="form-group col-md-2 mt-4">        
                               <!--  <button type="button" class="btn btn-primary reset-filter-1">Reset</button> -->
                                <button type="submit" class="btn btn-primary btn-lng">Filter Records</button>
                            </div>

                        </div>
                        <!-- row ends -->
                    </form>

                    <!-- ----------------- Form Two Starts ------------------------ -->
                    <form action="{{route('adjudication.index')}}" method="get" class="form-2 filter-form">
                        <div class="form-row" style="padding: 10px;">
                            
                            <input type="hidden" name="form_2" value="2" class="form-control">

                            <div class="form-group col-md-2">
                                <label for="inputState">Subject</label>
                                <select id="subject" name="subject" class="form-control filter-form-data">
                                    <option value="">All Subject</option>
                                    @foreach($getFilterSubjects as $filterSubject)
                                    <option @if(request()->subject == $filterSubject->id) selected @endif value="{{ $filterSubject->id }}">{{ $filterSubject->subject_id }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="inputState">Phase</label>
                                <select id="phase" name="phase" class="form-control filter-form-data">
                                    <option value="">All Phase</option>
                                    @foreach($getFilterPhases as $filterPhase)
                                    <option  @if(request()->phase == $filterPhase->id) selected @endif value="{{ $filterPhase->id }}">{{ $filterPhase->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label for="inputState">Modality</label>
                                <select id="modility" name="modility" class="form-control filter-form-data">
                                    <option value="">All Modality</option>
                                     @foreach($getFilterModilities as $filterModality)
                                     <option @if(request()->modility == $filterModality->id) selected @endif value="{{ $filterModality->id }}">{{ $filterModality->modility_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group col-md-3">
                            
                                <label for="inputState">Status</label>
                                <select id="form_status" name="form_status" class="form-control filter-form-data">
                                    <option value="">All Status</option>
                                     @foreach($getFilterFormStatus as $filter => $filterStatus)
                                     <option @if(request()->form_status == $filter) selected @endif value="{{ $filter }}">{{ $filterStatus }}</option>
                                    @endforeach
                                </select>
                            </div>
                   
                            <div class="form-group col-md-2 mt-4">        
                                <!-- <button type="button" class="btn btn-primary reset-filter-2">Reset</button> -->
                                <button type="submit" class="btn btn-primary btn-lng">Filter Records</button>
                            </div>

                        </div>
                        <!-- row ends -->
                    </form>

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
                            @if(!$subjects->isEmpty() && request()->has('form_1'))

                                {{$subjects->appends(['form_1' => \Request::get('form_1'), 'subject' => \Request::get('subject'), 'phase' => \Request::get('phase'), 'site' => \Request::get('site'), 'visit_date' => \Request::get('visit_date')])->links()}}
                            
                            @elseif(!$subjects->isEmpty() && request()->has('form_2'))

                             {{$subjects->appends(['form_2' => \Request::get('form_2'), 'subject' => \Request::get('subject'), 'phase' => \Request::get('phase'), 'modility' => \Request::get('modility'), 'form_status' => \Request::get('form_status')])->links()}}

                            @endif
                           
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

    // initialize date range picker
    $('input[name="visit_date"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="visit_date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="visit_date"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $('select[name="subject"]').select2();
    $('select[name="phase"]').select2();
    $('select[name="site"]').select2();

    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change")
        // submit the filter form
        window.location.reload();
    });


    // toggle form filters
    $('.other-filters').on('click', function(){
        $('.form-1, .form-2').toggle();
    });

</script>
@endsection




