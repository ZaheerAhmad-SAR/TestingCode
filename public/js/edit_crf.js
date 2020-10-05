// forms fetching validations
$('body').on('click','.form-fields',function(){
        $('#formfields').trigger('reset');
        var type = $(this).attr("data-field-type");
        $('#formfields').attr('action', "{{route('addQuestions')}}");
        $('#question_type').val(type);
        if(type =='Number'){
            $('.view_to_numeric').css('display', 'block');
            $('.view_to_textbox_and_number').css('display', 'block');
            $('.optionGroup').css('display', 'none');
            $('.view_to_textbox').css('display', 'none');
        }else if(type =='Radio' || type =='Dropdown' || type =='Checkbox'){
            $('.optionGroup').css('display', 'block')
            $('.view_to_numeric').css('display', 'none');
            $('.view_to_textbox_and_number').css('display', 'none');
            $('.view_to_textbox').css('display', 'none');
        }else if(type =='Text'){
            $('.view_to_textbox_and_number').css('display', 'block');
             $('.optionGroup').css('display', 'none')
            $('.view_to_numeric').css('display', 'none');
            $('.view_to_textbox').css('display', 'none');
        }else{
            $('.view_to_numeric').css('display', 'none');
            $('.optionGroup').css('display', 'none');
            $('.view_to_textbox_and_number').css('display', 'none');
        }
        $('#addField').modal('show');

   })
$('#question_type').on('change',function(){
    var type = $('#question_type option:selected').text();
    if(type =='Number'){
        $('.view_to_numeric').css('display', 'block');
        $('.view_to_textbox_and_number').css('display', 'block');
        $('.optionGroup').css('display', 'none');
        $('.view_to_textbox').css('display', 'none');
    }else if(type =='Radio' || type =='Dropdown' || type =='Checkbox'){
        $('.optionGroup').css('display', 'block')
        $('.view_to_numeric').css('display', 'none');
        $('.view_to_textbox_and_number').css('display', 'none');
        $('.view_to_textbox').css('display', 'none');
    }else if(type =='Text'){
        $('.view_to_textbox_and_number').css('display', 'block');
         $('.optionGroup').css('display', 'none')
        $('.view_to_numeric').css('display', 'none');
    }else{
        $('.view_to_numeric').css('display', 'none');
        $('.optionGroup').css('display', 'none');
        $('.view_to_textbox_and_number').css('display', 'none');
    }
});

/// update sort and delete Questions

$('.updateSort').on('click',function(){
    var questionId = $('#questionId').val();
    var sort_value = $('#up_question_sort').val();
    $.ajax({
        url:'forms/changeSort/'+questionId,
        type: 'post',
        data:{
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'questionId':questionId,
            'sort_value':sort_value
        },
        dataType:'json',
        success:function(res){
            $('#question-sort-close').click();
            var step_id = $('#steps').val()
                tId;
            display_sections(step_id);
            $('.success-msg').html('');
            $('.success-msg').html('Question Sort Number Updated!')
            $('.success-alert').slideDown('slow');
            tId=setTimeout(function(){
                $(".success-alert").slideUp('slow');
            }, 4000);
        }
    })
})
/// get phases or visits
function get_all_phases(id,phase_class){
    phase_class.html('');
    var options = '<option value="">---Select Phase / visits---</option>';
    $.ajax({
        url:'forms/get_phases/'+id,
        type:'post',
        dataType: 'json',
         data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'id': id
        },
        success:function(response){
            $.each(response['data'],function(k,v){
                options += '<option value="'+v.id+'" >'+v.name+'</option>';
            });
            phase_class.append(options);
        } 
    });
}
/// get steps
function get_steps_phase_id(id,step_class){
    step_class.html('');
    var options = '<option value="">---Select Step / Form---</option>';
    $.ajax({
        url:'forms/step_by_phaseId/'+id,
        type:'post',
        dataType: 'json',
         data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'phase_id': id
        },
        success:function(response){
            $.each(response['data'],function(k,v){
                options += '<option value="'+v.step_id+'" >'+v.form_type+'-'+v.step_name+'</option>';
            });
            step_class.append(options);
        } 
    });
}

// get sections
function get_section_step_id(id,section_class){
   section_class.html(''); 
   var options = '<option value="">---Form / Sections---</option>';
   $.ajax({
        url:'forms/sections_by_stepId/'+id,
        type:'post',
        dataType: 'json',
         data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'step_id': id
        },
        success:function(response){
             $.each(response['data'],function(k,v){
                options += '<option value="'+v.id+'" >'+v.name+'</option>';
            });
            section_class.append(options); 
        }
    });    
}

// get Question

function get_question_section_id(id,div_class){
    div_class.html(''); 
    var options = '<option value="">---Select Question---</option>';
    $.ajax({
        url:'forms/get_Questions/'+id,
        type:'post',
        dataType:'json',
        data:{
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'id': id
        },
        success:function(response){
            $.each(response['data'],function(k,v){
                options += '<option value="'+v.id+'" >'+v.question_text+'</option>';
            });
            div_class.append(options); 
        }
    });    
}

// get all annotations



function get_all_annotations(id,div_class){
    div_class.html(''); 
    var options = '<option value="">---Select Annotation---</option>';
    $.ajax({
        url:'annotation/get_allAnnotations/'+id,
        type:'post',
        dataType:'json',
        data:{
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'id': id
        },
        success:function(response){
            $.each(response['data'],function(k,v){
                options += '<option value="'+v.id+'" >'+v.label+'</option>';
            });
            div_class.append(options); 
        }
    });    
}

// get sections for dropdown
function section_against_step(id,section_class){
   section_class.html(''); 
   var options = '<option value="">---Form / Sections---</option>';
   $.ajax({
        url:'forms/sections_against_step/'+id,
        type:'post',
        dataType: 'json',
         data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'step_id': id
        },
        success:function(response){
             $.each(response['data'],function(k,v){
                options += '<option value="'+v.id+'" >'+v.name+'</option>';
            });
            section_class.append(options); 
        }
    });    
}