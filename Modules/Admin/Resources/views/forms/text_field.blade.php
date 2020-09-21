<div class="form-group">
    <label class="">{{$question->question_text}}</label>
    <input type="text" name="field_{{$question->id}}" value="{{$answer->answer}}" class="form-control-ocap bg-transparent">
    <small class="form-text">{{$question->formFields->text_info}}</small>
</div>
