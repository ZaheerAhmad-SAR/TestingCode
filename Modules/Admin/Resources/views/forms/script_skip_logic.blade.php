@push('script')
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
    <script type="text/javascript" id="activate_deactivate_steps">
    </script>
    <script type="text/javascript" id="activate_checks">
    </script>
    <script type="text/javascript" id="deactivate_checks">
    </script>
    <script type="text/javascript" id="question_for_activate">
    </script>
    <script type="text/javascript" id="question_for_deactivate">
    </script>
    <script type="text/javascript" id="options_for_activate">
    </script>
    <script type="text/javascript" id="options_for_deactivate">
    </script>
    
<script type="text/javascript">
    function git_steps_for_checks(id,index,q_id,title){
        $('.loader').css('display','block');
        var url = "{{ url('skiplogic/steps_to_skip') }}";
        var append_class = '.append_data_'+id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                "index": index,
                "question_id": q_id,
                "option_title": title,
                "option_value": id
            },
            success: function(response) {
                $(append_class).html(response.html_str);
                $('#activate_deactivate_steps').html(response.function_str);
                eval(document.getElementById("activate_deactivate_steps").innerHTML);
                $('.loader').css('display','none');
            }
        });
    }
    function git_steps_for_checks_deactivate_cohort(id,index,q_id,title){
        $('.loader').css('display','block');
        var url = "{{ url('skiplogic/skip_via_cohort') }}";
        var append_class = '.append_data_'+id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                "index": index,
                "question_id": q_id,
                "option_title": title,
                "option_value": id
            },
            success: function(response) {
                $(append_class).html(response.html_str);
                $('#activate_deactivate_steps').html(response.function_str);
                eval(document.getElementById("activate_deactivate_steps").innerHTML);
                $('.loader').css('display','none');
            }
        });
    }
    function activate_checks(id,append_class,index,q_id,option_value,option_title){
        $('.loader').css('display','block');
        var url = "{{ url('skiplogic/sections_for_skip_logic') }}"
            url = url+'/'+id;
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'step_id': id,
                    'index': index,
                    "question_id": q_id,
                    "option_title": option_title,
                    "option_value": option_value
                },
                success: function(response) {
                    $("."+append_class+id+'_'+index).html(response.html_str);
                    $('#activate_checks').html(response.function_str);
                    eval(document.getElementById("activate_checks").innerHTML);
                    $('.loader').css('display','none');
                }
            });
    }
    function deactivate_checks(id,append_class,index,q_id,option_value,option_title){
        $('.loader').css('display','block');
        var url = "{{ url('skiplogic/sections_for_skip_logic_deactivate') }}"
            url = url+'/'+id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'GET',
                'step_id': id,
                'index': index,
                "question_id": q_id,
                "option_title": option_title,
                "option_value": option_value
            },
            success: function(response) {
                $("."+append_class+id+'_'+index).html(response.html_str);
                $('#deactivate_checks').html(response.function_str);
                eval(document.getElementById("deactivate_checks").innerHTML);
                $('.loader').css('display','none');
            }
        });
    }
    function question_for_activate(id,append_class,index,q_id,option_value,option_title)
    {
        $('.loader').css('display','block');
        var url = "{{ url('skiplogic/questions_for_skip_logic') }}"
            url = url+'/'+id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'GET',
                'sec_id': id,
                'index': index,
                "question_id": q_id,
                "option_title": option_title,
                "option_value": option_value
            },
            success: function(response) {
                $("."+append_class+id+'_'+index).html(response.html_str);
                $('#question_for_activate').html(response.function_str);
                eval(document.getElementById("question_for_activate").innerHTML);
                $('.loader').css('display','none');
            }
        })
    }
    function question_for_deactivate(id,append_class,index,q_id,option_value,option_title)
    {
        $('.loader').css('display','block');
        var url = "{{ url('skiplogic/questions_for_skip_logic_deactivate') }}"
            url = url+'/'+id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'GET',
                'sec_id': id,
                'index': index,
                "question_id": q_id,
                "option_title": option_title,
                "option_value": option_value
            },
            success: function(response) {
                $("."+append_class+id+'_'+index).html(response.html_str);
                $('#question_for_deactivate').html(response.function_str);
                eval(document.getElementById("question_for_deactivate").innerHTML);
                $('.loader').css('display','none');
            }
        })
    }
    function question_options_activate(id,append_class,index,q_id,option_value,option_title){
        $('.loader').css('display','block');
        var url = "{{ url('skiplogic/options_for_skip_logic_activate') }}"
            url = url+'/'+id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'GET',
                'id': id,
                'index': index,
                "question_id": q_id,
                "option_title": option_title,
                "option_value": option_value
            },
            success: function(response){
                $("."+append_class+id+'_'+index).html(response.html_str);
                $('#options_for_activate').html(response.function_str);
                eval(document.getElementById("options_for_activate").innerHTML);
                $('.loader').css('display','none');
            }
        })
    }
    function question_options_deactivate(id,append_class,index,q_id,option_value,option_title){
        $('.loader').css('display','block');
       var url = "{{ url('skiplogic/options_for_skip_logic_deactivate') }}"
            url = url+'/'+id;
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'GET',
                'id': id,
                'index': index,
                "question_id": q_id,
                "option_title": option_title,
                "option_value": option_value
            },
            success: function(response){
                $("."+append_class+id+'_'+index).html(response.html_str);
                $('#options_for_deactivate').html(response.function_str);
                eval(document.getElementById("options_for_deactivate").innerHTML);
                $('.loader').css('display','none');
            }
        })
    }
</script>
@endpush