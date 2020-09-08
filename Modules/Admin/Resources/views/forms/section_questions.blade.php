<div class="form">
    @foreach ($section->questions as $question)

    @if($question->form_field_type->field_type === 'Radio')
    @php
    $option_names = [];
    $option_values = [];
    $optionGroup = $question->optionsGroup;
    $option_values = explode(',', $optionGroup->option_value);
    $option_names = explode(',', $optionGroup->option_name);
    $options = array_combine ( $option_names , $option_values );
    @endphp
    <div class="form-group">
        <label class="">{{$question->question_text}}</label>
        @foreach($options as $option_name=>$option_value)
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" name="{{$optionGroup->option_group_name.'_'.$question->id}}"
                value="{{$option_value}}" class="custom-control-input">
            <label class="custom-control-label" for="customCheck1">{{$option_name}}</label>
        </div>
        @endforeach
        <small class="form-text">{{$question->formFields->text_info}}</small>
    </div>
    @endif

    @if($question->form_field_type->field_type === 'Checkbox')
    @php
    $option_names = [];
    $option_values = [];
    $optionGroup = $question->optionsGroup;
    $option_values = explode(',', $optionGroup->option_value);
    $option_names = explode(',', $optionGroup->option_name);
    $options = array_combine ( $option_names , $option_values );
    @endphp
    <div class="form-group">
        <label class="">{{$question->question_text}}</label>
        @foreach($options as $option_name=>$option_value)
        <div class="custom-control custom-checkbox custom-control-inline">
            <input type="checkbox" name="{{$optionGroup->option_group_name.'_'.$question->id}}"
                value="{{$option_value}}" class="custom-control-input">
            <label class="custom-control-label" for="customCheck1">{{$option_name}}</label>
        </div>        
        @endforeach
        <small class="form-text">{{$question->formFields->text_info}}</small>
    </div>
    @endif

    @if($question->form_field_type->field_type === 'Dropdown')
    @php
    $option_names = [];
    $option_values = [];
    $optionGroup = $question->optionsGroup;
    $option_values = explode(',', $optionGroup->option_value);
    $option_names = explode(',', $optionGroup->option_name);
    $options = array_combine ( $option_names , $option_values );
    @endphp
    <div class="form-group">
        <label class="">{{$question->question_text}}</label>
        <select name="{{$optionGroup->option_group_name.'_'.$optionGroup->question_id}}"
            class="form-control-ocap bg-transparent">
            @foreach($options as $option_name=>$option_value)
            <option value="{{$option_value}}">{{$option_name}}
            <option>
                @endforeach
        </select>
        <small class="form-text">{{$question->formFields->text_info}}</small>
    </div>
    @endif

    @if($question->form_field_type->field_type === 'Text')
    <div class="form-group">
        <label class="">{{$question->question_text}}</label>
        <input type="text" name="{{$question->variable_name}}" value="" class="form-control-ocap bg-transparent">
        <small class="form-text">{{$question->formFields->text_info}}</small>
    </div>
    @endif

    @if($question->form_field_type->field_type === 'Textarea')
    <div class="form-group">
        <label class="">{{$question->question_text}}</label>
        <textarea name="{{$question->variable_name}}" class="form-control-ocap bg-transparent"></textarea>
        <small class="form-text">{{$question->formFields->text_info}}</small>
    </div>
    @endif

    @if($question->form_field_type->field_type === 'Number')
    <div class="form-group">
        <label class="">{{$question->question_text}}</label>
        <input type="number" name="{{$question->variable_name}}" value="" class="form-control-ocap bg-transparent">
        <small class="form-text">{{$question->formFields->text_info}}</small>
    </div>
    @endif

    @if($question->form_field_type->field_type === 'Date & Time')
    <div class="form-group">
        <label class="">{{$question->question_text}}</label>
        <input type="date" name="{{$question->variable_name}}" value="" class="form-control-ocap bg-transparent">
        <small class="form-text">{{$question->formFields->text_info}}</small>
    </div>
    @endif

    @if($question->form_field_type->field_type === 'Upload')
    <div class="form-group">
        <label class="">{{$question->question_text}}</label>
        <input type="file" name="{{$question->variable_name}}" class="form-control-ocap bg-transparent">
        <small class="form-text">{{$question->formFields->text_info}}</small>
    </div>
    @endif
    @endforeach
    <button type="button" class="btn float-right btn-primary nexttab">Next</button>
</div>