<div class="form-group">
    <label class="">{{$question->question_text}}</label>
    <input type="date" name="{{$question->variable_name}}" value="{{$answer->answer}}" class="form-control-ocap bg-transparent">
    <small class="form-text">{{$question->formFields->text_info}}</small>
</div>
