<div class="form-group">
    <label class="">{{$question->question_text}}</label>
    <textarea name="field_{{$question->id}}" class="form-control-ocap bg-transparent"></textarea>
    <small class="form-text">{{$question->formFields->text_info}}</small>
</div>