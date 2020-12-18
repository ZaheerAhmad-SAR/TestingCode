@php
    $skip_logic_id = '';
	$questions = Modules\Admin\Entities\Question::where('id', $value->id)->with('optionsGroup')->first();
    $skip_logic = Modules\Admin\Entities\SkipLogic::where('question_id', $q_id)->where('option_title',$option)->first();
    if(null !==$skip_logic){
        $skip_logic_id = $skip_logic->id;
    }
    $options_value = explode(',', $questions->optionsGroup->option_value);
    $options_name = explode(',', $questions->optionsGroup->option_name);
@endphp
@if(null !== $questions->optionsGroup)
    @if(count($options_name) >= 2)
        @foreach ($options_name as $key => $value) 
            @php
                $where = array(
                    "option_question_id" =>$questions->id,
                    "skip_logic_id" =>$skip_logic_id,
                    "value" =>$options_value[$key],
                    "type" => 'activate',
                    "option_depend_on_question_type" => 'radio'
                );
            	$if_exists_record = Modules\Admin\Entities\QuestionOption::where($where)->first();
	            if(null !==$if_exists_record && ($if_exists_record->value == $options_value[$key])){
	                $checked = "checked";
	            }else{
	                $checked = "";
	            }
	            if(null !==$if_exists_record){
	                $deactivate_questions_array = explode(',', $if_exists_record->deactivate_questions);
	            }
			@endphp
            <tr>
                <td style="text-align: center;width:15%;">
                   <input type="checkbox" name="activate_options[{{$index}}][]" value="{{$options_value[$key]}}<<=!=>>{{$questions->id}}" class="activate_option_{{$questions->id}}{{$key}}_{{$index}}"  onclick="disabled_opposite('{{$questions->id}}{{$key}}','deactivate_option_','{{$index}}','activate_option_');"  {{$checked}} >
                </td>
                <td colspan="5">{{$value}}</td>
            </tr>
            @push('script_last')
                <script>
                    $(document).ready(function() {
                        @php
                            $id = $questions->id.$key;
                            echo "disabled_opposite('$id','deactivate_option_','$index','activate_option_',);";
                        @endphp
                    })
                </script>
            @endpush
        @endforeach   
        @else
            <tr><td colspan="6">Options Not found</td></tr>
       @endif
@endif
  