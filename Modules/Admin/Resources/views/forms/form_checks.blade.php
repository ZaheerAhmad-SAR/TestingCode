@push('script')
    <script>
        function showFormPreview() {
            var route = "{{ url('forms/show') }}";
            var phase_id = $('#phases').val();
            var step_id = $('#steps').val();
            window.open(route + '/' + phase_id + '/' + step_id);
        }

        var validationRules = new Array;
        $('#question_type').on('change', function() {
            filterRulesByQuestionType();
        });

        function filterRulesByQuestionType() {
            var questionType = $('#question_type :selected').text();
            $.ajax({
                url: "{{ route('validationRule.filterRulesDataValidation') }}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'questionType': questionType,
                },
                success: function(data) {
                    var opts = $.parseJSON(data);
                    while (validationRules.length) {
                        validationRules.pop();
                    }
                    $.each(opts, function(i, d) {
                        validationRules.push({
                            "id": i,
                            "title": d
                        });
                    });
                    updateRulesDropDown();
                }
            });
        }

        $('.addvalidations').on('click', function() {

            var parameter_1 = $('#lower_limit_num').val();
            var parameter_2 = $('#upper_limit_num').val();
            var htmlStr = `<div class="values_row">
                                    <div class="row">
                                        <div class="col-sm-2"> Rule:</div>
                                        <div class="col-sm-2 validationRuleDivCls">

                                        </div>
                                        <div class="col-sm-2"> Parameter-1:</div>
                                        <div class="col-sm-2"> <input type="number" name="parameter_1[]" value="`+parameter_1+`" class="form-control" required></div>
                                        <div class="col-sm-2"> Parameter-2:</div>
                                        <div class="col-sm-2"> <input type="number" name="parameter_2[]" value="`+parameter_2+`" class="form-control" required></div>
                                    </div>
                                    <div class="row"><div class="col-sm-12">&nbsp;</div></div>
                                    <div class="row">
                                        <div class="col-sm-2"> Message Type:</div>
                                        <div class="col-sm-2">
                                            <select name="message_type[]" class="form-control" required>
                                                <option value="">--Select--</option>
                                                <option value="warning">Warning</option>
                                                <option value="error">Error</option>
                                                <option value="info">Info</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2"> Message:</div>
                                        <div class="col-sm-4"> <input type="text" name="message[]" class="form-control" required></div>
                                        <div class="col-sm-1"> <input type="text" name="sort_order[]" value="999" class="form-control" required></div>
                                        <div class="form-group col-md-1" style="text-align: right;!important;">
                                            <i class="btn btn-outline-danger fa fa-trash remove" style="margin-top: 3px;"></i>
                                        </div>
                                    </div>
                                    <div class="row"><div class="col-sm-12"><hr class="hr"></div></div>
                                </div>`;
            $('.appendDatavalidations').append(htmlStr);
            updateRulesDropDown();
            return false;
        });

        function getNumParams(selectObject) {
            ruleId = $(selectObject).val();
            $.ajax({
                url: "{{ route('validationRule.getNumParams') }}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'ruleId': ruleId
                },
                success: function(numParams) {
                    if(numParams == 0){
                        $(selectObject).parent().next().hide();
                        $(selectObject).parent().next().next().hide();
                        $(selectObject).parent().next().next().next().hide();
                        $(selectObject).parent().next().next().next().next().hide();
                    }
                    if(numParams == 1 || numParams == 'unlimited'){
                        $(selectObject).parent().next().next().next().hide();
                        $(selectObject).parent().next().next().next().next().hide();
                    }
                }
            });

        }

        function loadValidationRulesByQuestionId(questionId) {
            $.ajax({
                url: "{{ route('validationRule.getQuestionValidationRules') }}",
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'questionId': questionId,
                },
                success: function(responseHtml) {
                    $('.appendDatavalidations').html('');
                    $('.appendDatavalidations').append(responseHtml);
                }
            });
        }

        function updateRulesDropDown() {
            var selectStr = '<select name="validation_rules[]" class="form-control validationRuleDdCls" onchange="getNumParams(this);">';
            for (var i = 0; i < validationRules.length; i++) {
                var opt = validationRules[i];
                selectStr += '<option value="' + opt.id + '">' + opt.title + '</option>';
            }
            selectStr += '</select>';
            $('.validationRuleDivCls').html(selectStr);
        }

        function isThisStepHasData(stepId) {
            url_route = "{{ URL('forms/isThisStepHasData') }}"
            url_route = url_route;
            $.ajax({
                url: url_route,
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'stepId': stepId
                },
                success: function(response) {
                    $('#isThisStepHasDataField').val(response);
                }
            });

        }

        function checkIsStepHasData() {
            var hasData = $('#isThisStepHasDataField').val();
            if (hasData == 1) {
                alert('Please be noticed; This form has data!'); //return true;
            } else {
                //return false;
            }
        }

        function showStepDeActivationAlert() {
            alert('Please put the step in draft mode first!');
        }

        function display_sections(step_id) {
            var html = '';
            var sections = '';
            $("#wait").css("display", "block");
            $.ajax({
                url: 'forms/sections_by_stepId/' + step_id,
                type: 'post',
                dataType: 'html',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'step_id': step_id
                },
                success: function(response) {
                    $('.display-sections').html(response);
                    $('select[name="question_list"]').select2();
                    getStepVersion();
                    isStepActive(step_id);
                    isThisStepHasData(step_id);
                }
            });
        }

        function isStepActive(step_id) {
            $("#wait").css("display", "block");
            $.ajax({
                url: 'forms/isStepActive/' + step_id,
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'step_id': step_id
                },
                success: function(response) {
                    $('#isStepActiveField').val(response);
                    if (response == 1) {
                        $('#activateStepDiv').hide();
                        $('#deactivateStepDiv').show();
                    } else {
                        $('#deactivateStepDiv').hide();
                        $('#activateStepDiv').show();
                    }
                }
            });
        }

        function checkIsStepActive() {
            var is_active = $('#isStepActiveField').val();
            if (is_active == 1) {
                return true;
            } else {
                return false;
            }
        }

        function activateStepForm() {
            var step_id = $('#steps').val();
            var confirmation = 'draft_mode';
            $.confirm({
                columnClass: 'col-md-12',
                title: 'Default values confirmation!',
                content: 'User data entries found for the form. Please select the appropriate option below.',
                buttons: {
                    putDefaultData: {
                        text: 'Put form in production mode with default data',
                        btnClass: 'btn-green',
                        keys: ['enter', 'shift'],
                        action: function() {
                            confirmation = 'default_data_and_production_mode';
                            submitStepActivationForm(step_id, confirmation);
                        }
                    },
                    doNotPutDefaultData: {
                        text: 'Put form in production mode only',
                        btnClass: 'btn-blue',
                        keys: ['enter', 'shift'],
                        action: function() {
                            confirmation = 'production_mode_only';
                            submitStepActivationForm(step_id, confirmation);
                        }
                    },
                    remainInDraftMode: {
                        text: 'Remain in draft mode',
                        btnClass: 'btn-red',
                        keys: ['enter', 'shift'],
                        action: function() {
                            confirmation = 'draft_mode';
                        }
                    }
                }
            });

        }

        function submitStepActivationForm(step_id, confirmation) {
            $.ajax({
                url: 'steps/activate_step/' + step_id,
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'step_id': step_id,
                    'default_data_option': confirmation
                },
                success: function(res) {
                    getStepVersion();
                    $('#isStepActiveField').val(1);
                    $('#activateStepDiv').hide();
                    $('#deactivateStepDiv').show();
                }
            });
        }

        function deactivateStepForm() {
            var step_id = $('#steps').val();
            $.ajax({
                url: 'steps/deActivate_step/' + step_id,
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'step_id': step_id
                },
                success: function(res) {
                    $('#isStepActiveField').val(0);
                    $('#deactivateStepDiv').hide();
                    $('#activateStepDiv').show();
                }
            });
        }

        function getStepVersion() {
            var step_id = $('#steps').val();
            $.ajax({
                url: 'forms/getStepVersion/' + step_id,
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'step_id': step_id
                },
                success: function(res) {
                    var htmlStr = '<label class="form-version">Form version : ' + res + '</label>';
                    $('#formVersionDiv').html(htmlStr);
                }
            })
        }

        function activateStep(step_id) {
            var confirmation = 'draft_mode';
            $.confirm({
                columnClass: 'col-md-12',
                title: 'Default values confirmation!',
                content: 'User data entries found for the form. Please select the appropriate option below.',
                buttons: {
                    putDefaultData: {
                        text: 'Put form in production mode with default data',
                        btnClass: 'btn-green',
                        keys: ['enter', 'shift'],
                        action: function() {
                            confirmation = 'default_data_and_production_mode';
                            submitActivateStepRequest(step_id, confirmation);
                        }
                    },
                    doNotPutDefaultData: {
                        text: 'Put form in production mode only',
                        btnClass: 'btn-blue',
                        keys: ['enter', 'shift'],
                        action: function() {
                            confirmation = 'production_mode_only';
                            submitActivateStepRequest(step_id, confirmation);
                        }
                    },
                    remainInDraftMode: {
                        text: 'Remain in draft mode',
                        btnClass: 'btn-red',
                        keys: ['enter', 'shift'],
                        action: function() {
                            confirmation = 'draft_mode';
                        }
                    }
                }
            });

        }

        function submitActivateStepRequest(step_id, confirmation) {
            $.ajax({
                url: 'steps/activate_step/' + step_id,
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'step_id': step_id,
                    'default_data_option': confirmation
                },
                success: function(res) {
                    var spanHtml = '<span class="dropdown-item inActivateStep" onclick="deActivateStep(\'' +
                        step_id + '\');"><i class="far fa-pause-circle"></i>&nbsp; Put In Draft Mode</span>';
                    $('#activeStatusDiv_' + step_id).html(spanHtml);
                    getStepVersionStructurePage(step_id);
                }
            });
        }

        function deActivateStep(step_id) {
            $.ajax({
                url: 'steps/deActivate_step/' + step_id,
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'step_id': step_id
                },
                success: function(res) {
                    var spanHtml = '<span class="dropdown-item activateStep" onclick="activateStep(\'' +
                        step_id +
                        '\');"><i class="far fa-play-circle"></i>&nbsp; Put In Production Mode</span>';
                    $('#activeStatusDiv_' + step_id).html(spanHtml);
                    getStepVersionStructurePage(step_id);
                }
            })
        }

        function getStepVersionStructurePage(step_id) {
            $.ajax({
                url: 'forms/getStepVersion/' + step_id,
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'step_id': step_id
                },
                success: function(res) {
                    $('#formVersionSpan_' + step_id).html(res);
                }
            })
        }

    </script>
@endpush

@push('styles')
    <style>
        .form-version {
            text-decoration: underline;
            color: #47546D;
            font-size: 16px;
            font-weight: bold;
            padding: 15px;
        }
        .hr{
            border-bottom: 1px #dc3545 dotted;
        }

    </style>
@endpush
