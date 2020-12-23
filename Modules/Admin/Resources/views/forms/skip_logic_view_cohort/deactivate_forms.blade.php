@php
    $deactivate_forms_array = [];
    $where = array(
        "phase_id" =>$phase_id,
        "cohort_name" =>$value->name,
        "cohort_id" =>$value->id
    );
    $if_exists_record = Modules\Admin\Entities\CohortSkipLogic::where($where)->first();
    if(null !==$if_exists_record){
        $deactivate_forms_array = explode(',', $if_exists_record->deactivate_forms);
    }
    $all_study_steps = Modules\Admin\Entities\PhaseSteps::where('phase_id', $phase_id)->get();
@endphp
<div class="col-12 col-sm-12 mt-3">
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-bordered" style="margin-bottom:0px;">
                    <thead>
                        <tr>
                            <th style="width: 15%">Expand</th>
                            <th colspan="5">Deactivate Modality,Sections,Question</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div> 
    @foreach ($all_study_steps as $key => $value)
    @php    
    if(in_array($value->step_id, $deactivate_forms_array)){ $checked = 'checked'; }else{ $checked = ''; }
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
                                <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-{{$value->step_id}}-de-{{$index}}" id="de_form_{{$value->step_id}}" style="font-size: 20px;" ></i>
                              </div>
                            </td>
                            <td colspan="5"><input type="checkbox" name="deactivate_forms[{{$index}}][]" value="{{$value->step_id}}" {{$checked}}> &nbsp;&nbsp; {{$value->step_name}}({{$value->formType->form_type}}-{{$value->modility->modility_name}})</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card collapse row-{{$value->step_id}}-de-{{$index}} de_sections_list_{{$value->step_id}}_{{$index}}">
        {{-- Load Sections here for deactivate --}}
          @include('admin::forms.skip_logic_view_cohort.deactivate_sections')  
        {{-- Load Sections here for deactivate end here --}}
    </div>

    @endforeach
</div>