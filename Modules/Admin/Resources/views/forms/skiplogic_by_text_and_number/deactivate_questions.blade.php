@php
    $questions = Modules\Admin\Entities\Question::select('*')->where('section_id', $value->id)->orderBy('question_sort', 'asc')->get();
@endphp
@if(count($questions) > 0)
@foreach ($questions as $key => $value)
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive ">
                <table class="table table-bordered" style="margin-bottom:0px;background-color: #17A2B8;color:#fff;">
                    <tbody>
                        <tr>
                            <td class="sec_id" style="display: none;">{{$value->id}}</td>
                            <td style="text-align: center;width:15%;">
                                <div class="btn-group btn-group-sm" role="group">
                                    <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-{{$value->id}}-de-{{$index}}" style="font-size: 20px;color:white;" id="de_question_{{$value->id}}"></i>
                                </div>
                            </td>
                            <td colspan="5"> 
                                <input type="checkbox" name="deactivate_questions[{{$index}}][]" value="{{$value->id}}" class="deactivate_question_{{$value->id}}_{{$index}}"  onclick="disabled_opposite('{{$value->id}}','activate_question_','{{$index}}','deactivate_question_');"> &nbsp;&nbsp;{{$value->question_text}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body collapse row-{{$value->id}}-de-{{$index}} " style="padding: 0;">
            <table class="table table-bordered" style="margin-bottom:0px;">
                <tbody class="de_options_list_{{$value->id}}_{{$index}}">
                </tbody>
                    {{-- Load options here for activate start --}}
                    @include('admin::forms.skiplogic_by_text_and_number.deactivate_options')
                    {{-- Load options here for activate end --}}
            </table> 
        </div>
@endforeach
@else
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive ">
            <table class="table table-bordered" style="margin-bottom:0px;background-color: #17A2B8;color:#fff;">
                <tbody>
                    <tr><td colspan="6">Questions Not found</td></tr>
                </tbody>
            </table>
        </div>
    </div>
@endif