@extends ('layouts.home')
@section('content')
<div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12 align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">Edit CRFs</h4></div>

                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Forms</li>
                </ol>
            </div>
        </div>
        <div class="col-lg-12 success-alert" style="display: none;">
            <div class="alert alert-primary success-msg" role="alert">
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->

    <!-- START: Card Data-->
    <div class="row">
        <div class="col-lg-10 col-xl-10">
            <div class="row">
                <label for="username" class="col-sm-1 col-form-label">Phases:</label>
                <div class="col-sm-3">
                    <select id="phases" class="form-control" style="background: #fff;">
                        <option value="">---Select Phase---</option>
                        @foreach($phases as $key => $phase)
                        <option value="{{$phase->id}}">{{$phase->name}}</option>
                        @endforeach
                    </select>
                </div>
                <label for="username" class="col-sm-1 col-form-label">Steps:</label>
                <div class="col-sm-3">
                    <select id="steps" class="form-control" style="background: #fff;">
                        <option value="">---Select Steps---</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <a href="{{route('forms.show',1)}}" target="_blank">
                        <button class="btn btn-outline-primary" style="background-color: white;"><i class="far fa-eye"></i> Preview</button>
                    </a>    
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-xl-2">
            <label for="username" class="col-form-label">Fields Types</label>
        </div>
        <div class="col-lg-10 col-xl-10 mb-4 mt-3 pr-lg-0 flip-menu">
            <div class="card border h-100 mail-menu-section display-sections">
                    
            </div>
        </div>
        <div class="col-lg-2 col-xl-2 mb-4 mt-3 pl-lg-0">
            <div class="card border h-100 mail-list-section">
                <div class="card-body p-0">
                    <div class="scrollertodo">
                        @foreach($fields as $key => $value)
                        <div class="border-btm form-fields color-black" data-field-type="{{ $value->field_type }}"  data-field-id="{{ $value->id }}"><i class="{{ $value->icon }}" aria-hidden="true"></i>&nbsp;&nbsp;{{ $value->field_type }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Card DATA-->
</div>
<!-- Modal To add Number -->
    <div class="modal fade" tabindex="-1" role="dialog" id="addField">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Add New Field</p>
                </div>
                <form action="{{route('addQuestions')}}" enctype="multipart/form-data" method="POST" id="formfields">
                @csrf    
                <div class="modal-body">
                    <nav>
                        <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic</a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Validation" role="tab" aria-controls="nav-profile" aria-selected="false">Data validation</a>
                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-Dependencies" role="tab" aria-controls="nav-contact" aria-selected="false">Dependencies</a>
                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-Annotations" role="tab" aria-controls="nav-contact" aria-selected="false">Annotations</a>
                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-Advanced" role="tab" aria-controls="nav-contact" aria-selected="false">Advanced</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                            <div class="py-3 border-bottom border-primary">
                                <span class="text-muted font-w-600">Define Basic Attribute of Question</span><br>
                            </div>
                            <div class="form-group row" style="margin-top: 10px;">
                                <label for="Sections" class="col-sm-3 col-form-label">Sections</label>   
                                <div class="col-sm-9">
                                    <select name="section_id" id="section_id" class="form-control">
                                        <option value="">--- Placement Place ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="C-DISC" class="col-sm-3 col-form-label">C-DISC <sup>*</sup></label>      
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="c_disk" id="c_disk" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="label" class="col-sm-3 col-form-label"> Label <sup>*</sup></label>    
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="question_text" id="question_text" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <lable for='variable' class="col-sm-3 col-form-label">Variable name <sup>*</sup></lable>    
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="variable_name" id="variable_name" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="field" class="col-sm-3 col-form-label">Choose field type:</label>
                                <div class="col-sm-9">
                                    <select name="form_field_type_id" id="question_type" class="form-control">
                                        <option value="">--- Field Type ---</option>
                                        @foreach($fields as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->field_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Required" class="col-sm-3 col-form-label">Required <sup>*</sup></label>    
                                <div class="col-sm-9">
                                    <input type="radio" name="is_required" value="no"> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="is_required" value="yes" checked> Yes
                                </div>
                            </div>
                            <div class="view_to_numeric">
                                <div class="form-group row">
                                    <label for='limits' class="col-sm-3 col-form-label">Lower and upper limits: <sup>*</sup></label>    
                                    <div class="col-sm-4">
                                        <input type="text" name="lower_limit" id="lower_limit" class="form-control" placeholder="Minimum">
                                    </div>  
                                    <div class="col-sm-5">  
                                        <input type="text" name="upper_limit" id="upper_limit" class="form-control" placeholder="Maximum">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Field width <sup>*</sup></label>    
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="field_width" id="field_width" value="">
                                    </div>
                                    <label class="col-sm-3">Measurement unit</label>    
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="measurement_unit" id="measurement_unit" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="optionGroup">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Option group: </label>    
                                    <div class="col-sm-9">
                                        <select name="option_group_id" id="option_group_id" class="form-control">
                                            <option value="">none</option>
                                            @foreach($option_groups as $key => $value)
                                            <option value="{{$value->id}}">{{$value->option_group_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="view_to_textbox">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Field width <sup>*</sup></label>    
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="field_width" id="field_width" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Measurement unit</label>    
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="measurement_unit" id="measurement_unit" value="">
                                    </div>
                                </div>
                            </div>
                             <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Text/info: <sup>*</sup></label>    
                                <div class="col-sm-9">
                                    <textarea name="text_info" id="text_info" cols="2" rows="2" class="form-control" style="height: 50px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-Validation" role="tabpanel" aria-labelledby="nav-Validation-tab">
                            <div class="py-3 border-bottom border-primary">
                                <span class="text-muted font-w-600">Default Validation</span><br>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12" style="margin-top: 10px;"><button type="button" class="btn btn-outline-primary addvalidations"><i class="fa fa-plus"></i> Add Message</button></div>    
                            </div>
                             <div class="appendDatavalidations">
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-Dependencies" role="tabpanel" aria-labelledby="nav-Dependencies-tab">
                            <div class="py-3 border-bottom border-primary">
                                <span class="text-muted font-w-600">Define If Dependencies on any Question</span><br>
                            </div>
                            <div class="form-group row" style="margin-top: 10px;">
                                <div class="col-sm-3">Field is dependent: <sup>*</sup></div>    
                                <div class="col-sm-9">
                                    <input type="radio" name="field_dependent" class="field_dependent" value="no" checked> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="field_dependent" class="field_dependent" value="yes" > Yes
                                </div>
                            </div>    
                            <div class="append_if_yes">
                                
                            </div>
                            </div>
                      
                        <div class="tab-pane fade" id="nav-Annotations" role="tabpanel" aria-labelledby="nav-Annotations-tab">
                            <div class="py-3 border-bottom border-primary">
                                <span class="text-muted font-w-600">Annotations</span><br>
                            </div>
                            <div class="form-group row" style="margin-top: 10px;">
                                <div class="col-sm-12"><button type="button" class="btn btn-outline-primary addannotation"><i class="fa fa-plus"></i> Add annotation</button></div>    
                            </div>
                            <div class="appendannotation">
                                
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-Advanced" role="tabpanel" aria-labelledby="nav-Advanced-tab">
                            <div class="py-3 border-bottom border-primary">
                                <span class="text-muted font-w-600">Click Yes If Need Result in Final Data Exports</span><br>
                            </div>
                            <div class="form-group row" style="margin-top: 10px;">
                                <div class="col-sm-3">Exclude field in data exports: <sup>*</sup></div>    
                                <div class="col-sm-9">
                                    <input type="radio" name="is_exportable_to_xls" value="no"> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="is_exportable_to_xls" value="yes" checked> Yes
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                    <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
                </div>
                </div>
            </form>
            </div>
        </div>
</div>
<!-- End -->

@stop
@section('styles')  

<link rel="stylesheet" href="{{ asset('public/dist/vendors/quill/quill.snow.css') }}" />
@stop
@section('script')  
<script src="{{ asset('public/dist/vendors/quill/quill.min.js') }}"></script> 
<script src="{{ asset('public/dist/js/mail.script.js') }}"></script>  
<script>
       $('.addvalidations').on('click',function(){
           $('.appendDatavalidations').append('<div class="values_row"><div class="form-group row"><div class="col-sm-2">If this field is:</div><div class="col-sm-4"><select name="requiredvalidation_value" id="requiredvalidation_value" class="form-control"><option value="">---Select Value---</option><option value="">One</option><option value="">Two</option><option value="">Three</option></select></div><div class="col-sm-5"><input type="text" placeholder="value" name="value" class="form-control"></div><div class="col-sm-1"><i class="btn btn-outline-danger fa fa-trash remove" style="cursor:pointer;"></i></div></div><div class="form-group row"><div class="col-sm-2"> Show a:</div><div class="col-sm-10"><select name="requiredvalidation_value" id="requiredvalidation_value" class="form-control"><option value="">Exclusion</option><option value="">Error</option><option value="">Warning</option></select></div></div><div class="form-group row"><div class="col-sm-2">Message:</div><div class="col-sm-10"><input type="text" name="validation_message" class="form-control"></div></div></div>');
           return false;
       });
       $('body').on('click','.remove',function(){
            var row = $(this).closest('div.values_row');
            row.remove();
       })
       //
       $('.addannotation').on('click',function(){
           $('.appendannotation').append('<div class="anno_values_row"><div class="form-group row"><div class="col-sm-2">Terminology:</div><div class="col-sm-9"><select name="terminology_value" id="terminology_value" class="form-control"><option value="">---Select Value---</option><option value="">One</option><option value="">Two</option><option value="">Three</option></select></div><div class="col-sm-1"><i class="btn btn-outline-danger fa fa-trash remove_anno" style="cursor:pointer;"></i></div></div><div class="form-group row"><div class="col-sm-2"> Value:</div><div class="col-sm-10"><input type="text" name="annotation_field_value" id="annotation_field_value" class="form-control"></div></div><div class="form-group row"><div class="col-sm-2">Description:</div><div class="col-md-10"><input type="text" name="annotation_message" class="form-control"></div></div></div>');
           return false;
       });
       $('body').on('click','.remove_anno',function(){
            var row = $(this).closest('div.anno_values_row');
            row.remove();
       })
       
       $('.field_dependent').on('change',function(){
            var value = $(this).val();
            if(value =='yes'){
                $('.append_if_yes').append('<div class="form-group row"><div class="col-sm-3">Step of dependency field:</div><div class="col-sm-9"><select name="step_of_dependency" id="step_of_dependency" class="form-control"><option value="">Randomization</option><option value="">Laboratory</option></select></div></div><div class="form-group row"><div class="col-sm-3">depend on field:</div><div class="col-sm-9"><select name="depend_on_field" id="depend_on_field" class="form-control"><option value="">one</option><option value="">two</option></select></div></div><div class="form-group row"><div class="col-sm-3">field operator:</div><div class="col-sm-9"><select name="field_operator" id="field_operator" class="form-control"><option value="">grater than</option><option value="">Less then</option></select></div></div><div class="form-group row"><div class="col-sm-3">field value:</div><div class="col-sm-9"><input type="text" name="field_value" id="field_value" class="form-control"></div>');
            }else{
                $('.append_if_yes').html('');
            }
       });
       $('body').on('click','.form-fields',function(){
            $('#addField').trigger('reset');
            var type = $(this).attr("data-field-type");
            $('#question_type').val(type);
            if(type =='Number'){
                $('.view_to_numeric').css('display', 'block');
                $('.optionGroup').css('display', 'none');
                $('.view_to_textbox').css('display', 'none');
            }else if(type =='Radio' || type =='Dropdown' || type =='checkbox'){
                $('.optionGroup').css('display', 'block')
                $('.view_to_numeric').css('display', 'none');
                $('.view_to_textbox').css('display', 'none');
            }else if(type =='Text'){
                $('.view_to_textbox').css('display', 'block');
                 $('.optionGroup').css('display', 'none')
                $('.view_to_numeric').css('display', 'none');
            }else{
                $('.view_to_numeric').css('display', 'none');
                $('.optionGroup').css('display', 'none');
                $('.view_to_textbox').css('display', 'none');
            }
            $('#addField').modal('show');

       })
       $('body').on('click','.form-fields',function(){
            var id = $(this).attr("data-field-id");
            $('#question_type').val(id);
       })
       $('#phases').on('change',function(){
            var phase_id = $(this).val();
            var options;
            $("#wait").css("display", "block");
            $.ajax({
                url:'forms/step_by_phaseId/'+phase_id,
                type:'post',
                dataType: 'json',
                 data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'phase_id': phase_id
                },
                success:function(response){
                    $.each(response['data'],function(k,v){
                        options += '<option value="'+v.step_id+'" >'+v.form_type+'-'+v.step_name+'</option>';
                    });
                $('#steps').html(options); 
                $('#steps').trigger('change');
                }    
            });
       })
       $('#steps').on('change',function(){
           var step_id = $(this).val();
           var html = '';
           var sections = '';
           $("#wait").css("display", "block");
           $.ajax({
                url:'forms/sections_by_stepId/'+step_id,
                type:'post',
                dataType: 'json',
                 data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'step_id': step_id
                },
                success:function(response){
                    $('.display-sections').html('');
                    $('#section_id').html('');
                    html += '<div id="accordion">';
                    $.each(response['data'],function(k,v){
                        var show = (k ==0) ? 'show' : '';
                        html += '<div class="card"><div class="card-header"><a class="card-link" data-toggle="collapse" href="#collapse_'+v.id+'">'+v.sort_number+'&nbsp;&nbsp;&nbsp;&nbsp;'+v.name+'</a></div><div id="collapse_'+v.id+'" class="collapse '+show+'" data-parent="#accordion"><div class="card-body">';
                        $.ajax({
                            url:'forms/get_allQuestions/'+v.id,
                            type:'post',
                            dataType:'json',
                            data:{
                                "_token": "{{ csrf_token() }}",
                                "_method": 'GET',
                                'id': v.id
                            },
                            success:function(res){
                               $.each(res['data'],function(i,j){
                                
                               })
                            }
                        });
                        html += '</div></div></div>';
                        sections += '<option value="'+v.id+'">'+v.name+'</option>'
                    });
                    html +='</div>';
                    $('.display-sections').html(html);
                    $('#section_id').append(sections);
                    $("#wait").css("display", "none");   
                }    
            });
       })
   </script>     
@stop