@extends ('layouts.home')
@section('content')
    <input type="hidden" name="isStepActiveField" id="isStepActiveField" value="1" />
    <input type="hidden" name="isThisStepHasDataField" id="isThisStepHasDataField" value="1" />
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12 align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Edit CRFs</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Forms</li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-12 success-alert" style="display: none;">
                <div class="alert alert-primary success-msg" role="alert">
                </div>
            </div>
            @if (session()->has('message'))
                <div class="col-lg-12 success-alert">
                    <div class="alert alert-primary success-msg" role="alert">
                        {{ session()->get('message') }}
                    </div>
                </div>
            @endif
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-lg-10 col-xl-10">
                <div class="row">
                    <label for="username" class="col-sm-1 col-form-label">Phases:</label>
                    <div class="col-sm-3">
                        <select id="phases" name="phases" class="form-control" style="background: #fff;">
                            <option value="">---Select Phase---</option>
                            @foreach ($phases as $key => $phase)
                                <option value="{{ $phase->id }}" @if($phase->id ==Session('filter_phase')) selected @endif>{{ $phase->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="username" class="col-sm-1 col-form-label">Steps:</label>
                    <div class="col-sm-3">
                        <select id="steps" class="form-control" style="background: #fff;">
                            <option value="">---Select Step / Form---</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-outline-primary" onclick="showFormPreview();"
                            style="background-color: white;">
                            <i class="far fa-eye"></i> Preview</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-sm-4" id="activateStepDiv" style="display: none;">
                        <button class="btn btn-outline-primary" onclick="activateStepForm();"
                            style="background-color: white;">
                            <i class="far fa-play-circle"></i> Put in Production Mode</button>
                    </div>
                    <div class="col-sm-4" id="deactivateStepDiv" style="display: none;">
                        <button class="btn btn-outline-primary" onclick="deactivateStepForm();"
                            style="background-color: white;">
                            <i class="far fa-pause-circle"></i> Put in Draft Mode</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xl-2">
                <label for="username" class="col-form-label">Fields Types</label>
            </div>
            <div class="col-lg-10 col-xl-10 mb-4 mt-3 pr-lg-0 flip-menu" style="width:calc(100vh - 140px);">
                <div class="card border h-100 mail-menu-section display-sections">

                </div>
            </div>
            <div class="col-lg-2 col-xl-2 mb-4 mt-3 pl-lg-0">
                <div class="card border h-100 mail-list-section">
                    <div class="card-body p-0">
                        <div class="scrollertodo">
                            @foreach ($fields as $key => $value)
                                @if ($value->field_type == 'Certification')
                                    <div class="border-btm add_certify_list color-black"
                                        data-field-type="{{ $value->field_type }}" data-field-id="{{ $value->id }}"
                                        style="font-size: 12px;padding: 5px;cursor: pointer;"><i class="{{ $value->icon }}"
                                            aria-hidden="true"></i>&nbsp;&nbsp;{{ $value->field_type }}</div>
                                @elseif($value->field_type =='Description')
                                    <div class="border-btm add_discription color-black"
                                        data-field-type="{{ $value->field_type }}"
                                        data-field-id="{{ $value->id }}"
                                        style="font-size: 12px;padding: 5px;cursor: pointer;"><i
                                            class="{{ $value->icon }}"
                                            aria-hidden="true"></i>&nbsp;&nbsp;{{ $value->field_type }}
                                    </div>
                                @elseif($value->field_type =='Calculated')
                                    <div class="border-btm add_calculated_field color-black"
                                        data-field-type="{{ $value->field_type }}"
                                        data-field-id="{{ $value->id }}"
                                        style="font-size: 12px;padding: 5px;cursor: pointer;"><i
                                            class="{{ $value->icon }}"
                                            aria-hidden="true"></i>&nbsp;&nbsp;{{ $value->field_type }}
                                    </div>
                                @else
                                    <div class="border-btm form-fields color-black"
                                        data-field-type="{{ $value->field_type }}" data-field-id="{{ $value->id }}"><i
                                            class="{{ $value->icon }}"
                                            aria-hidden="true"></i>&nbsp;&nbsp;{{ $value->field_type }}</div>
                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
    <!-- Modal To add Number -->
    @include('admin::forms.add_form_field')
    <!-- End -->
    <!-- Modal To add Option Groups -->
    @include('admin::forms.add_option_group')
    <!-- End -->
    <!-- Modal To add Option Groups -->
    @include('admin::forms.change_sort')
    <!-- End -->
    <!-- Modal To add Option Groups -->
    @include('admin::forms.add_description_field')
    </div>
    <!-- End -->
    @include('admin::forms.add_calculated_field')
    @include('admin::forms.add_certification_field')
    @include('admin::forms.add_annotation')
    @include('admin::forms.edit_crf')
    @include('admin::forms.form_checks')
    @include('admin::shared.tinyMCE')
    @endsection

@push('styles')
    <style>
        .custom_fields {
            border-bottom: 1px solid #F6F6F7;
            padding: 10px;
        }

        .float-right {
            float: right;
        }

        .display-none {
            display: none;
        }

        .overlay_container {
            position: relative;

        }

    </style>
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/quill/quill.snow.css') }}" />
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('dist/vendors/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('dist/vendors/select2/css/select2-bootstrap.min.css') }}" />

@endpush
@push('script')
    <script src="{{ asset('public/dist/vendors/quill/quill.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/mail.script.js') }}"></script>
    {{-- Select 2 --}}
    <script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/select2.script.js') }}"></script>
    <script>
        // selct initialize
        // $('select[name="phases"]').select2();

        $(document).ready(function() {
            var tId ='';
            var step_change ='';
            tId = setTimeout(function() {
                $(".success-alert").slideUp('slow');
            }, 4000);
            $('.variable_name_ques').keydown(function(e) {
                if (e.keyCode == 32) {
                    $('.variable_name_ques').css('border', '1px solid red');
                    $('.space_msg').html('Space Not Allowed!!')
                    e.preventDefault();
                } else {
                    $('.variable_name_ques').css('border', '');
                    $('.space_msg').html('');
                    return true;
                }
            })
            @if(session('filter_phase') !='')
                $('#phases').trigger('change');
            @endif

            @if(session('filter_step') !='')
                step_change = setTimeout(function() {
                   $('#steps').trigger('change');
                }, 1000);
            @endif
        })
        $('.addOptions').on('click', function() {
            $('.appendDataOptions').append(
                '<div class="values_row_options"><div class="form-group row"><div class="form-group col-md-6"><input type="text" id="option_name" name="option_name[]" class="form-control" placeholder="Enter option name" style="background:white;" required></div><div class="form-group col-md-4"><input type="number" placeholder="Option value" name="option_value[]" id="option_value" class="form-control" style="background:white;" required></div><div class="form-group col-md-1" style="text-align: right;!important;"><i class="btn btn-outline-danger fa fa-trash remove_option" style="margin-top: 3px;"></i></div></div></div>'
                );
            return false;
        });

        $('body').on('click', '.remove', function() {
            var row = $(this).closest('div.values_row');
            row.remove();
        })
        $('body').on('click', '.remove_option', function() {
            var row = $(this).closest('div.values_row_options');
            row.remove();
        })
        //
        $('.addannotation').on('click', function() {
            $('.appendannotation').append(
                '<div class="anno_values_row"><div class="form-group row" style="margin-top: 10px;"><div class="col-sm-2">Terminology:</div><div class="col-sm-4"><span style="float: right;"><input type="button" value="Fetch" class="btn btn-primary fetch_annotation"></span><span><select name="terminology_id[]" class="form-control terminology_value" style="width: 82%;"><option value="">---Click on fetch to select Annotations---</option></select></span></div><div class="col-sm-1"> Value:</div><div class="col-sm-4"><input type="text" name="value[]" id="annotation_field_value" class="form-control"></div><div class="col-sm-1"><i class="btn btn-outline-danger fa fa-trash remove_anno" style="cursor:pointer;"></i></div></div><div class="form-group row"><div class="col-sm-2">Description:</div><div class="col-md-10"><textarea name="description[]" class="form-control" rows="2"></textarea></div></div></div>'
                );
            return false;
        });

        $('#section_id').on('change', function() {
            $('.field_dependent').trigger('change');
        })
        $('.field_dependent').on('change', function() {
            var value = $(this).val();
            var sec_id = $('#section_id').val();
            var ques_class = $('.select_ques_for_dep');
            if (value == 'yes') {
                $('.append_if_yes').css('display', 'block');
                get_question_section_id(sec_id, ques_class);
            } else {
                $('.append_if_yes').css('display', 'none');
            }
        });
        $('body').on('click', '.fetch_phases', function() {
            var phase_id = '1';
            var row = $(this).closest('div.values_row');
            var phase_class = row.find('select.all_phases');
            get_all_phases(phase_id, phase_class);
        })
        $('body').on('change', '.all_phases', function() {
            var phase_id = $(this).val();
            var row = $(this).closest('div.values_row');
            var step_class = row.find('select.all_forms');
            get_steps_phase_id(phase_id, step_class);
        });
        $('body').on('change', '.all_forms', function() {
            var step_id = $(this).val();
            var row = $(this).closest('div.values_row');
            var section_class = row.find('select.all_sections');
            section_against_step(step_id, section_class);
        });
        $('body').on('change', '.all_sections', function() {
            var sec_id = $(this).val();
            var row = $(this).closest('div.values_row');
            var ques_class = row.find('select.all_questions');
            get_question_section_id(sec_id, ques_class);
        });
        $('body').on('click', '.fetch_sections', function() {
            var step_id = $('#steps').val();
            var row = $(this).closest('div.values_row');
            var section_class = row.find('select.all_sections');
            section_against_step(step_id, section_class);
        })
        $('body').on('click', '.fetch_sections2', function() {
            var step_id = $('#steps').val();
            var row = $(this).closest('div.values_row');
            var section_class = row.find('select.all_sections2');
            section_against_step(step_id, section_class);
        })
        $('body').on('change', '.all_sections2', function() {
            var sec_id = $(this).val();
            var row = $(this).closest('div.values_row');
            var ques_class = row.find('select.all_questions2');
            get_question_section_id(sec_id, ques_class);
        });
        $('body').on('change', '.decision', function() {
            var value = $(this).val();
            var row = $(this).closest('div.values_row');
            var sec_id = row.find('select.all_sections').val();
            var ques_class = row.find('select.decision_question');
            if (value == 'question_value') {
                row.find('.questionValue').css('display', 'block');
                row.find('.customValue').css('display', 'none');
            } else if (value == 'custom_value') {
                row.find('.customValue').css('display', 'block');
                row.find('.questionValue').css('display', 'none');
            }
        });
        $('body').on('change', '.decision2', function() {
            var value = $(this).val();
            var row = $(this).closest('div.values_row');
            var sec_id = row.find('select.all_sections').val();
            var ques_class = row.find('select.decision_question2');
            if (value == 'question_value_sec') {
                row.find('.questionValue2').css('display', 'block');
                row.find('.customValue2').css('display', 'none');
            } else if (value == 'custom_value_sec') {
                row.find('.customValue2').css('display', 'block');
                row.find('.questionValue2').css('display', 'none');
            }
        });
        $('body').on('change', '.operators', function() {
            var value = $(this).val();
            var row = $(this).closest('div.values_row');
            if (value == 'and' || value == 'or') {
                row.find('.third_condition').css('display', 'block');
            } else if (value == '') {
                row.find('.third_condition').css('display', 'none');
            }
        });
        $('#phases').on('change', function() {
            var phase_id = $(this).val()
                step_class = $('select#steps');
            get_steps_phase_id(phase_id, step_class);
        })
        $('#steps').on('change', function() {
            var step_id = $(this).val();
            var phase_id = $('#phases').val();
            var sec_class = $('select.decisionSections');
            var sec_class2 = $('select.decisionSections2');
            var basic_section = $('select.basic_section');
            make_session(step_id,phase_id);
            display_sections(step_id);
            section_against_step(step_id, basic_section);
        });
        $('.decisionSections').on('change', function() {
            var sec_id = $(this).val();
            var ques_class = $('select.decision_question');
            get_question_section_id(sec_id, ques_class);
        });
        $('.decisionSections2').on('change', function() {
            var sec_id = $(this).val();
            var ques_class = $('select.decision_question2');
            get_question_section_id(sec_id, ques_class);
        });

        $('#adj_status').on('change', function() {
            var value = $(this).val();
            if (value == 'yes') {
                $('.show_if_required').css('display', 'block');
            } else {
                $('.show_if_required').css('display', 'none');
                $('.show_if_custom_percent').css('display', 'none');
            }
        })
        $('#decision_based_on').on('change', function() {
            var value = $(this).val();
            if (value == 'custom' || value == 'percentage') {
                $('.show_if_custom_percent').css('display', 'block');
            } else {
                $('.show_if_custom_percent').css('display', '');
            }
        })
        $(document).ready(function() {
            
            $('body').on('click', '.delete_ques', function() {
                checkIsStepHasData();
                if (checkIsStepActive() == false) {
                    var row = $(this).closest('div.custom_fields');
                    var tId;
                    var question_id = row.find('input.question_id').val();
                    if (confirm('Are you sure to delete ?')) {
                        $.ajax({
                            url: 'forms/delete/' + question_id,
                            type: 'post',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": 'DELETE',
                                'questionId': question_id,
                            },
                            dataType: 'json',
                            success: function(res) {
                                row.remove();
                                $('.success-msg').html('');
                                $('.success-msg').html('Operation Done!')
                                $('.success-alert').slideDown('slow');
                                tId = setTimeout(function() {
                                    $(".success-alert").slideUp('slow');
                                }, 4000);
                            }
                        })
                    }
                } else {
                    showStepDeActivationAlert();
                }
            })
            
            
        })

        function applychecks() {
            var route = <?php echo '"'.url('forms/skip_logic').
            '";'; ?>;        window.open(route + '/' + id);
        }

        /**************************************************************/

        /// get steps
        function get_steps_phase_id(id, step_class) {
            step_class.html('');
            // var options = '<option value="">---Select Step / Form---</option>';
            var url_route = "{{ URL('forms/step_by_phaseId') }}"
            url_route = url_route + "/" + id;

            $.ajax({
                url: url_route,
                type: 'post',
                dataType: 'html',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'phase_id': id
                },
                success: function(response) {
                    step_class.append(response);
                }
            });
        }
        // get sections
        function get_section_step_id(id, section_class) {
            section_class.html('');
            var options = '<option value="">---Form / Sections---</option>';
            $.ajax({
                url: 'forms/sections_by_stepId/' + id,
                type: 'post',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'step_id': id
                },
                success: function(response) {
                    $.each(response['data'], function(k, v) {
                        options += '<option value="' + v.id + '" >' + v.name + '</option>';
                    });
                    section_class.append(options);
                }
            });
        }
        // for new route
        /// get phases or visits
        function get_all_phases(id, phase_class) {
            phase_class.html('');
            var options = '<option value="">---Select Phase / visits---</option>';
            $.ajax({
                url: 'forms/get_phases/' + id,
                type: 'post',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'id': id
                },
                success: function(response) {
                    $.each(response['data'], function(k, v) {
                        options += '<option value="' + v.id + '" >' + v.name + '</option>';
                    });
                    phase_class.append(options);
                }
            });
        }
        // get Question
        function get_question_section_id(id, div_class) {
            div_class.html('');
            var options = '';
            $.ajax({
                url: 'forms/get_Questions/' + id,
                type: 'post',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'id': id
                },
                success: function(response) {
                    $('.select_ques_for_dep').html('');
                    options += '<option value="">---Select Question---</option>';
                    $.each(response['data'], function(k, v) {
                        options += '<option value="' + v.id + '" >' + v.question_text + '</option>';
                    });
                    div_class.append(options);
                }
            });
        }


        // get sections for dropdown
        function section_against_step(id, section_class) {
            section_class.html('');
            var options = '<option value="">---Form / Sections---</option>';
            $.ajax({
                url: 'forms/sections_against_step/' + id,
                type: 'post',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'step_id': id
                },
                success: function(response) {
                    $.each(response['data'], function(k, v) {
                        options += '<option value="' + v.id + '" >' + v.name + '</option>';
                    });
                    section_class.append(options);
                }
            });
        }
        // for new route end

        /**************************************************************/
        function make_session(step_id,phase_id)
        {
            $.ajax({
                url: "{{ route('forms.makeFilterSession') }}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'step_id': step_id,
                    'phase_id': phase_id,
                },
                dataType: 'json',
                success: function(res) {
                }
            });
        }
    </script>
@endpush
