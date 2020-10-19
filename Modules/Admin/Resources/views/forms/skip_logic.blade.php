@extends ('layouts.home')
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12 align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Skip Logic</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Skip Logic</li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-12 success-alert" style="display: none;">
                <div class="alert alert-primary success-msg" role="alert">
                </div>
            </div>
            @if(session()->has('message'))
                <div class="col-lg-12 success-alert">
                    <div class="alert alert-primary success-msg" role="alert">
                        {{ session()->get('message') }}
                    </div>
                </div>
            @endif
        </div>
        <!-- END: Breadcrumbs-->
        <!-- START: Card Data-->
        <form action="{{route('forms.apply_skip_logic')}}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row">
            @php
                $options_value = explode(',', $options->optionsGroup->option_value);
                $options_name = explode(',', $options->optionsGroup->option_name);
            @endphp
           
            @foreach($options_name as $key => $value)
               <div class="col-12 col-sm-12 mt-3">
                   <div class="card">
                       <div class="card-body">
                            <input type="hidden" name="option_title[]" value="{{$value}}">
                            <input type="checkbox" name="option_value[]" value="{{$options_value[$key]}}"> &nbsp; {{$value}} 
                            (Perform Action Click)
                       </div>
                   </div>
               </div>
               <div class="col-12 col-sm-6 mt-3">
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%">Expand</th>
                                            <th colspan="5">Activate Modality,Sections,Question</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div> 
                    </div> 
                    @foreach($all_study_steps->studySteps as $value)
                            <div class="card">
                                <div class="card-body" style="padding: 0;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;"> 
                                        <tbody>
                                            <tr>
                                                <td class="step_id" style="display: none;">{{$value->step_id}}</td>
                                                <td style="text-align: center;width: 15%">
                                                  <div class="btn-group btn-group-sm" role="group">
                                                    <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon get_sec_ac" title="Log Details" data-toggle="collapse" data-target=".row-{{$value->step_id}}-ac" style="font-size: 20px; color: #1e3d73;"></i>
                                                  </div>
                                                </td>
                                                <td colspan="5"> <input type="checkbox" name="activate_forms[{{$key}}][]" value="{{$value->step_id}}"> &nbsp;&nbsp; {{ $value->step_name }}</td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </div>
                            </div>
                        </div>
                        <div class="card collapse row-{{$value->step_id}}-ac sections_list_{{$value->step_id}}">
                          
                                    
                        </div>
                        @endforeach    
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%">Expand</th>
                                            <th colspan="5">Deactivate Modality,Sections,Question</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div> 
                    </div>       
                    @foreach($all_study_steps->studySteps as $value)
                            <div class="card">
                                <div class="card-body" style="padding: 0;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;"> 
                                        <tbody>
                                            <tr>
                                                <td class="step_id" style="display: none;">{{$value->step_id}}</td>
                                                <td style="text-align: center;width: 15%">
                                                  <div class="btn-group btn-group-sm" role="group">
                                                    <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" onclick="get_section_to_deactivate('{{$value->step_id}}', '.de_sections_list_{{$value->step_id}}')" data-toggle="collapse" data-target=".row-{{$value->step_id}}-de" style="font-size: 20px; color: #1e3d73;"></i>
                                                  </div>
                                                </td>
                                                <td colspan="5"><input type="checkbox" name="deactivate_forms[{{$key}}][]" value="{{$value->step_id}}"> &nbsp;&nbsp; {{ $value->step_name }}</td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </div>
                            </div>
                        </div>
                        <div class="card collapse row-{{$value->step_id}}-de de_sections_list_{{$value->step_id}}">
                            
                        </div>             
                    @endforeach
                </div>
                   
                @endforeach
                   
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
            </div>
        </form> 
        <!-- END: Card DATA-->
    </div>
@endsection
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
        <link rel="stylesheet" href="{{ asset('public/dist/vendors/quill/quill.snow.css') }}" />
        <!-- select2 -->
        <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2-bootstrap.min.css') }}"/>
    @endsection
    @section('script')
    <script src="{{ asset('public/dist/vendors/quill/quill.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/mail.script.js') }}"></script>
    <script src="{{ asset('public/js/edit_crf.js') }}"></script>
    <!-- select2 -->
    <script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/select2.script.js') }}"></script>
    <script type="text/javascript">
        // toggle class for showing details
        $('.detail-icon').click(function(e){
            $(this).toggleClass("fa-chevron-circle-right fa-chevron-circle-down");
        });

        // reset filter form
        $('.reset-filter').click(function(){
            // reset values
            $('.filter-form').trigger("reset");
            $('.filter-form-data').val("").trigger("change")
            // submit the filter form
            $('.filter-form').submit();
        });

       
        // selct initialize
        $('.user_name').select2();
        $('select[name="event_section"]').select2();
        $('select[name="event_study"]').select2();

    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('body').on('click','.get_sec_ac',function(){
                var row = $(this).closest('tr');
                    step_id = row.find('td.step_id').text()
                    append_class = '.sections_list_'+step_id
                    url = "{{ url('forms/sections_for_skip_logic') }}"
                    url = url+'/'+step_id;
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'html',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": 'GET',
                        'step_id': step_id
                    },
                    success: function(response) {
                        $(append_class).html(response);
                    }
                });    
            })
            $('body').on('click','.get_ques_ac',function(){
                var row = $(this).closest('tr');
                    sec_id = row.find('td.sec_id').text()
                    append_class = '.ac_questions_list_'+sec_id
                    url = "{{ url('forms/questions_for_skip_logic') }}"
                    url = url+'/'+sec_id
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'html',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": 'GET',
                        'sec_id': sec_id
                    },
                    success: function(response) {
                        $(append_class).html(response);
                    }
                })
            })
            // for deactivate question getting 
            $('body').on('click','.get_ques_de',function(){
                var row = $(this).closest('tr');
                    sec_id = row.find('td.sec_id').text()
                    append_class = '.de_questions_list_'+sec_id
                    url = "{{ url('forms/questions_for_skip_logic_deactivate') }}"
                    url = url+'/'+sec_id
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'html',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": 'GET',
                        'sec_id': sec_id
                    },
                    success: function(response) {
                        $(append_class).html(response);
                    }
                })
            })
        })
        function get_section_to_deactivate(id,append_class){
            var url = "{{ url('forms/sections_for_skip_logic_deactivate') }}"
                url = url+'/'+id;
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'html',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'step_id': id
                },
                success: function(response) {
                    $(append_class).html(response);
                }
            }); 
        }
    </script>
@endsection
