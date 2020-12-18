@php
    $deactivate_sections_array = [];
    $where = array(
        "question_id" =>$q_id,
        "option_value" =>$options_value[$index]
    );
    $if_exists_record = Modules\Admin\Entities\skipLogic::where($where)->first();
    if(null !== $if_exists_record){
        $deactivate_sections_array = explode(',', $if_exists_record->deactivate_sections);
    }
    $section = Modules\Admin\Entities\Section::select('*')->where('phase_steps_id', $value->step_id)->orderBy('sort_number', 'asc')->get();
@endphp
@if(count($section) > 0)
@foreach ($section as $key => $value)
    @php
    if(in_array($value->id, $deactivate_sections_array)){ $checked = 'checked'; }else{ $checked = ''; }
    @endphp
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive ">
            <table class="table table-bordered" style="margin-bottom:0px;background-color: #EFEFEF;color: black;">
                <tbody>
                    <tr class="">
                        <td class="sec_id" style="display: none;">{{$value->id}}</td>
                        <td style="text-align: center;width:15%;">
                            <div class="btn-group btn-group-sm" role="group">
                                <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" id="de_section_{{$value->id}}" data-target=".row-{{$value->id}}-de-{{$index}}" style="font-size: 20px; color: #1e3d73;"></i>
                            </div>
                        </td>
                        <td colspan="5">
                           <input type="checkbox" name="deactivate_sections[{{$index}}][]" value="{{$value->id}}"  class="deactivate_section_{{$value->id}}_{{$index}}"  onclick="disabled_opposite('{{$value->id}}','activate_section_','{{$index}}','deactivate_section_')" {{$checked}}> &nbsp;&nbsp;{{$value->name}}
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
                    echo "disabled_opposite('$value->id','activate_section_','$index','deactivate_section_');";
                @endphp
            })
        </script>
    @endpush
    <div class="card-body collapse row-{{$value->id}}-de-{{$index}} de_questions_list_{{$value->id}}_{{$index}}" style="padding: 0;">
        {{-- Load Questions here for activate --}}
        @include('admin::forms.skiplogic_by_options.deactivate_questions')
        {{-- Load Questions end here for activate --}}
    </div>
@endforeach
@else
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive ">
            <table class="table table-bordered" style="margin-bottom:0px;background-color: #EFEFEF;color: black;">
                <tbody>
                    <tr><td colspan="6">Sections Not found</td></tr>
                </tbody>
            </table>
        </div>
    </div>
@endif