@php
    $skip_logic_id = '';
	$questions = Modules\Admin\Entities\Question::where('id', $value->id)->with('optionsGroup')->first();
    $skip_logic = Modules\Admin\Entities\CohortSkipLogic::where('phase_id', $phase_id)->where('cohort_id',$diseaseid)->first();
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
                    "phase_id" =>$phase_id,
                    "option_question_id" =>$questions->id,
                    "cohort_skiplogic_id" =>$skip_logic_id,
                    "value" =>$options_value[$key]
                );
            	$if_exists_record = Modules\Admin\Entities\CohortSkipLogicOption::where($where)->first();
                
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
               <input type="checkbox" name="deactivate_options[{{$index}}][]" value="{{$options_value[$key]}}<<=!=>>{{$questions->id}}" class="deactivate_option_{{$questions->id}}{{$key}}_{{$index}}" {{$checked}}>
            </td>
            <td colspan="5">{{$value}}</td>
        </tr>
        @endforeach   
        @else
            <tr><td colspan="6">Options Not found</td></tr>
       @endif
@endif
  