@push('script')
<script>
// forms fetching validations
$('body').on('click','.form-fields',function(){
    checkIsStepHasData();
    if(checkIsStepActive() == false){
        $('#formfields').trigger('reset');
        $('#formfields').attr('action', "{{route('forms.addQuestions')}}");
        setFieldType($(this).attr("data-field-id"));
        $('#addField').modal('show');
        filterRulesByQuestionType();
    }else{
            showStepDeActivationAlert();
        }

   })
$('#question_type').on('change',function(){
    setFieldType($('#question_type option:selected').val());
});

function setFieldType(type){
    //alert(type);
        $('#question_type').val(type);
        /*
        1 = Number
        2 = Radio
        3 = Dropdown
        4 = Checkbox
        5 = Text
        6 = Textarea
        */
        if(type == 1){
            $('.view_to_numeric').css('display', 'block');
            $('.view_to_textbox_and_number').css('display', 'block');
            $('.optionGroup').css('display', 'none');
            $('.view_to_textbox').css('display', 'none');
        }else if(type == 2 || type == 3 || type == 4){
            $('.optionGroup').css('display', 'block')
            $('.view_to_numeric').css('display', 'none');
            $('.view_to_textbox_and_number').css('display', 'none');
            $('.view_to_textbox').css('display', 'none');
        }else if(type == 5){
            $('.view_to_textbox_and_number').css('display', 'block');
            $('.optionGroup').css('display', 'none')
            $('.view_to_numeric').css('display', 'none');
        }else{
            $('.view_to_numeric').css('display', 'none');
            $('.optionGroup').css('display', 'none');
            $('.view_to_textbox_and_number').css('display', 'none');
        }
}
</script>
@endpush
