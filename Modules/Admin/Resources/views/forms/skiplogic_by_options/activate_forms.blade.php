@php
    $activate_forms_array = [];
    $where = array(
        "question_id" =>$q_id,
        "option_title" =>$option,
        "option_value" =>$options_value[$index]
    );
    $if_exists_record = Modules\Admin\Entities\skipLogic::where($where)->first();
    if(null !==$if_exists_record){
        $activate_forms_array = explode(',', $if_exists_record->activate_forms);
    }
     $all_study_steps = Modules\Admin\Entities\PhaseSteps::where('phase_id', $phase_id)->get();
@endphp
{{-- Steps data loading Start here  --}}
<div class="col-12 col-sm-6 mt-3">
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-bordered" style="margin-bottom:0px;">
                    <thead>
                        <tr>
                            <th style="width: 15%">Expand</th>
                            <th colspan="5">Activate Modality,Sections,Question</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
{{-- Steps data loading end here  --}}
@foreach ($all_study_steps as $key => $value)
        @php
        if(in_array($value->step_id, $activate_forms_array)){ $checked = 'checked'; }else{ $checked = ''; }
        @endphp
            <div class="card">
                <div class="card-body" style="padding: 0;">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom:0px;background-color: #1E3D73;color: white;">
                        <tbody>
                            <tr>
                                <td class="step_id" style="display: none;">{{$value->step_id}}</td>
                                <td style="text-align: center;width: 15%">
                                  <div class="btn-group btn-group-sm" role="group">
                                    <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" id="ac_form_{{$value->step_id}}" data-target=".row-{{$value->step_id}}-ac-{{$index}}" style="font-size: 20px;"></i>
                                  </div>
                                </td>
                                <td colspan="5"> <input type="checkbox" name="activate_forms[{{$index}}][]" value="{{$value->step_id}}" {{$checked}} class="activate_step_{{$value->step_id}}_{{$index}}" onclick="disabled_opposite('{{$value->step_id}}','deactivate_step_','{{$index}}','activate_step_');"> &nbsp;&nbsp;{{$value->step_name}}({{$value->formType->form_type}}-{{$value->modility->modility_name}})</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            @push('script_last')
                <script>
                    $(document).ready(function() {
                        @php
                            echo "disabled_opposite('$value->step_id','deactivate_step_','$index','activate_step_',);";
                        @endphp
                    })
                </script>
            @endpush
        <div class="card collapse row-{{$value->step_id}}-ac-{{$index}} sections_list_{{$value->step_id}}_{{$index}}">
        {{-- Load Sections here for activate --}}
           @include('admin::forms.skiplogic_by_options.activate_sections') 
        {{-- Load Sections here for activate end here --}}
        </div>
@endforeach 
</div>
            