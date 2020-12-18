 @php
    $activate_questions_array = [];
        $where = array(
            "question_id" =>$q_id,
            "option_value" =>$options_value[$index]
        );
    $if_exists_record = Modules\Admin\Entities\skipLogic::where($where)->first();
    if(null !==$if_exists_record){
        $activate_questions_array = explode(',', $if_exists_record->activate_questions);
    }
    $questions = Modules\Admin\Entities\Question::select('*')->where('section_id', $value->id)->orderBy('question_sort', 'asc')->get();
    $options_ac_contents = '';
@endphp
@if(count($questions) > 0)
@foreach ($questions as $key => $value)
    @php
    if(in_array($value->id, $activate_questions_array)){ $checked = 'checked'; }else{ $checked = ''; }
    @endphp
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive ">
            <table class="table table-bordered" style="margin-bottom:0px;background-color: #17A2B8;color:#fff;">
                <tbody>
                    <tr>
                        <td class="sec_id" style="display: none;">'.$value->id.'</td>
                        <td style="text-align: center;width:15%;">
                            <div class="btn-group btn-group-sm" role="group">
                                <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-{{$value->id}}-ac-{{$index}}" style="font-size: 20px;color:white;" id="ac_question_{{$value->id}}">
                                </i>
                            </div>
                        </td>
                        <td colspan="5"> 
                            <input type="checkbox" name="activate_questions[{{$index}}][]" value="{{$value->id}}" class="activate_question_{{$value->id}}_{{$index}}"  onclick="disabled_opposite('{{$value->id}}','deactivate_question_','{{$index}}','activate_question_');" {{$checked}}> &nbsp;&nbsp;{{$value->question_text}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
     @push('script_last')
        <script>
            $(document).ready(function() {
                @php
                    echo "disabled_opposite('$value->id','deactivate_question_','$index','activate_question_',);";
                @endphp
            })
        </script>
    @endpush
    <div class="card-body collapse row-{{$value->id}}-ac-{{$index}} " style="padding: 0;">
        <table class="table table-bordered" style="margin-bottom:0px;">
            <tbody class="ac_options_list_{{$value->id}}_{{$index}}">
            </tbody>
                {{-- Load options here for activate start --}}
                @include('admin::forms.skiplogic_by_options.activate_options')
                {{-- Load options here for activate end --}}
        </table> 
    </div>
@endforeach
@else
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive ">
            <table class="table table-bordered" style="margin-bottom:0px;background-color: #F64E60;color:black;">
                <tbody>
                    <tr><td colspan="6">Questions Not found</td></tr>
                </tbody>
            </table>
        </div>
    </div>
@endif