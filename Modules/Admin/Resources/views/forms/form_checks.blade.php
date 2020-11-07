@push('script')
    <script>
        function showFormPreview() {
            var route = <?php echo '"'.url('forms/show').'";'; ?>;
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

            var htmlStr = `<div class="values_row">
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-sm-1"> Rule:</div>
                                    <div class="col-sm-4 validationRuleDivCls">

                                    </div>
                                    <div class="form-group col-md-1" style="text-align: right;!important;">
                                        <i class="btn btn-outline-danger fa fa-trash remove" style="margin-top: 3px;"></i>
                                    </div>
                                </div>
                            </div>`;
            $('.appendDatavalidations').append(htmlStr);
            updateRulesDropDown();
            return false;
        });

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
                    $('.appendDatavalidations').append(responseHtml);
                }
            });
        }

        function updateRulesDropDown() {
            var selectStr = '<select name="validation_rules[]" class="form-control validationRuleDdCls">';
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
    </script>
@endpush
