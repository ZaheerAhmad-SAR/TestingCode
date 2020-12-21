@php
	$questions = Modules\Admin\Entities\Question::where('id', $value->id)->with('optionsGroup')->first();
    $options_value = explode(',', $questions->optionsGroup->option_value);
    $options_name = explode(',', $questions->optionsGroup->option_name);
@endphp
@if(null !== $questions->optionsGroup)
    @if(count($options_name) >= 2)
        @foreach ($options_name as $key => $value) 
            <tr>
                <td style="text-align: center;width:15%;">
                   <input type="checkbox" name="activate_options[{{$index}}][]" value="{{$options_value[$key]}}<<=!=>>{{$questions->id}}" class="activate_option_{{$questions->id}}{{$key}}_{{$index}}"  onclick="disabled_opposite('{{$questions->id}}{{$key}}','deactivate_option_','{{$index}}','activate_option_');" >
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
  