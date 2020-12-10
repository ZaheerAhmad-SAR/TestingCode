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
            {{ showMessage() }}
        </div>
        <!-- END: Breadcrumbs-->
        <!-- START: Card Data-->
        <form action="{{route('forms.apply_skip_logic')}}" enctype="multipart/form-data" method="POST">
            @csrf
            {{-- {{dd(request('id'))}} --}}
            @php
                $check_value = '';
                $options_value = explode(',', $options->optionsGroup->option_value);
                $options_name = explode(',', $options->optionsGroup->option_name);
            @endphp
            <input type="hidden" name="question_id" value="{{request('id')}}">
            @foreach($options_name as $key => $value)
            <div class="row">
               <div class="col-12 col-sm-12 mt-3">
                   <div class="card">
                       <div class="card-body">
                            <input type="hidden" name="option_title[]" value="{{$value}}">
                            @foreach($options->skiplogic as $logic)
                                @if(!empty($logic->option_value))
                                   <?php $check_value = $logic->option_value; break; ?>
                                @endif
                            @endforeach()
                            <input type="checkbox" name="option_value[]" onclick="git_steps_for_checks({{$options_value[$key]}})" value="{{$options_value[$key]}}" @if($check_value == $options_value[$key]) checked="checked" @endif> &nbsp; {{$value}}
                            (Perform Action Click)
                       </div>
                   </div>
               </div>
            </div>
            <div class="row append_data_{{$options_value[$key]}}">

            </div>
            @push('script_last')
             <script>
                 $(document).ready(function() {
                 @php
                 if ($check_value == $options_value[$key]) {
                 echo "git_steps_for_checks($options_value[$key])";
                 } else {

                 }
                 @endphp
                 });
             </script>
            @endpush
            @endforeach
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
            </div>
        </form>
        <!-- END: Card DATA-->
    </div>
@endsection
@include('admin::forms.edit_crf')
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
            });
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
            });
        })
        function git_steps_for_checks(id){
           var url = "{{ url('forms/steps_to_skip') }}";
           var append_class = '.append_data_'+id;
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'html',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    $(append_class).slideDown('600');
                    $(append_class).html(response);
                }
            });
        }
        function activate_checks(id,append_class){
            var url = "{{ url('forms/sections_for_skip_logic') }}"
                url = url+'/'+id
                row = $('.'+append_class+id).parent('div.current_div_ac');
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
                        row.find('.'+append_class+id).html(response);
                    }
                });
        }
        function deactivate_checks(id,append_class){
            var url = "{{ url('forms/sections_for_skip_logic_deactivate') }}"
                url = url+'/'+id
                // row = thiss.parent('div.current_div');
                row = $('.'+append_class+id).parent('div.current_div_de');
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
                    row.find('.'+append_class+id).html(response);
                }
            });
        }
    </script>
    @push('script_last')
 <script>
    $(document).ready(function() {
        $()
    });
 </script>
 @endpush
@endsection
