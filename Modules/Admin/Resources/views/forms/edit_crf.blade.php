@push('script')
<script>
// forms fetching validations
$('body').on('click','.form-fields',function(){
        $('#formfields').trigger('reset');
        $('#formfields').attr('action', "{{route('forms.addQuestions')}}");
        var type = $(this).attr("data-field-id");
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
</script>
@endpush
