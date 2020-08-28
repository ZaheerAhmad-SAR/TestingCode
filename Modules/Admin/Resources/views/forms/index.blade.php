@extends ('layouts.home')
@section('content')
<div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12 align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">View CRFs & Adding Questions</h4></div>

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
                    <a href="{{route('forms.show',1)}}">
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
                        <div class="border-btm form-fields color-black" data-field-type="numeric"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;&nbsp;Number</div>
                        <div class="border-btm form-fields color-black" data-field-type="radio"><i class="fa fa-bullseye" aria-hidden="true"></i>&nbsp;&nbsp;Radio buttons</div>
                        <div class="border-btm form-fields color-black" data-field-type="dropdown"><i class="far fa-caret-square-down" aria-hidden="true"></i>&nbsp;&nbsp;Dropdown</div>
                        <div class="border-btm form-fields color-black" data-field-type="checkbox"><i class="fa fa-check-square" aria-hidden="true"></i>&nbsp;&nbsp;checkbox</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fa fa-calendar-alt" aria-hidden="true"></i>&nbsp;&nbsp;Date</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fa fa-calendar-alt" aria-hidden="true"></i>&nbsp;&nbsp;Year</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fa fa-clock" aria-hidden="true"></i>&nbsp;&nbsp;Time</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fa fa-calculator" aria-hidden="true"></i>&nbsp;&nbsp;Calculation</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fas fa-sliders-h" aria-hidden="true"></i>&nbsp;&nbsp;Slider</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fa fa-server" aria-hidden="true"></i>&nbsp;&nbsp;Summary</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fa fa-qrcode" aria-hidden="true"></i>&nbsp;&nbsp;QR Code</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fab fa-cloudscale" aria-hidden="true"></i>&nbsp;&nbsp;Repeated Measure</div>
                        <div class="border-btm form-fields color-black" data-field-type="text"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;&nbsp;Text</div>
                        <div class="border-btm form-fields color-black" data-field-type="textarea"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;&nbsp;Text (multiline)</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fa fa-list" aria-hidden="true"></i>&nbsp;&nbsp;Randomization </div>
                        <div class="border-btm form-fields color-black" data-field-type="file"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;&nbsp;Upload file</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fa fa-list" aria-hidden="true"></i>&nbsp;&nbsp;Image</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fa fa-list" aria-hidden="true"></i>&nbsp;&nbsp;Grid</div>
                        <div class="border-btm form-fields color-black" data-field-type="dateTime"><i class="fa fa-calendar-alt" aria-hidden="true"></i>&nbsp;&nbsp;Date & Time</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fa fa-calendar-alt" aria-hidden="true"></i>&nbsp;&nbsp;Number & Date</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fa fa-list" aria-hidden="true"></i>&nbsp;&nbsp;Add Report Button</div>
                        <div class="border-btm form-fields color-black" data-field-type=""><i class="fa fa-list" aria-hidden="true"></i>&nbsp;&nbsp;Add Survey Button</div>
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
                                    <select name="section_id" id="selected-sections" class="form-control">
                                        <option value="">--- Placement Place ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="C-DISC" class="col-sm-3 col-form-label">C-DISC <sup>*</sup></label>      
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="c_disc" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="label" class="col-sm-3 col-form-label"> Label <sup>*</sup></label>    
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="question_label" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <lable for='variable' class="col-sm-3 col-form-label">Variable name <sup>*</sup></lable>    
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="Variable_name" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="field" class="col-sm-3 col-form-label">Choose field type:</label>
                                <div class="col-sm-9">
                                    <select name="question_type" id="question_type" class="form-control">
                                        <option value="">--- Field Type ---</option>
                                        <option value="numeric">Number</option>
                                        <option value="radio">Radio</option>
                                        <option value="dropdown">Dropdown</option>
                                        <option value="checkbox">checkbox</option>
                                        <option value="dateTime">Date & Time</option>
                                        <option value="text">Text</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="file">Upload file</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Required" class="col-sm-3 col-form-label">Required <sup>*</sup></label>    
                                <div class="col-sm-9">
                                    <input type="radio" name="required" value="no"> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="required" value="yes" checked> Yes
                                </div>
                            </div>
                            <div class="view_to_numeric">
                                <div class="form-group row">
                                    <label for='limits' class="col-sm-3 col-form-label">Lower and upper limits: <sup>*</sup></label>    
                                    <div class="col-sm-4">
                                        <input type="text" name="minimum" class="form-control" placeholder="Minimum">
                                    </div>  
                                    <div class="col-sm-5">  
                                        <input type="text" name="maximum" class="form-control" placeholder="Maximum">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Field width <sup>*</sup></label>    
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="field_width" value="">
                                    </div>
                                    <label class="col-sm-3">Measurement unit</label>    
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="measurement" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="optionGroup">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Option group: </label>    
                                    <div class="col-sm-9">
                                        <select name="option_group" id="option_group" class="form-control">
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
                                        <input type="text" class="form-control" name="field_width" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Measurement unit</label>    
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="measurement" value="">
                                    </div>
                                </div>
                            </div>
                             <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Text/info: <sup>*</sup></label>    
                                <div class="col-sm-9">
                                    <textarea name="question_info" id="question_info" cols="2" rows="2" class="form-control" style="height: 50px;"></textarea>
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
                                    <input type="radio" name="exports" value="no"> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="exports" value="yes" checked> Yes
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
<!-- add steps agains phases -->
<!-- phase modle action="{{route('steps.save')}}" -->
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
            if(type =='numeric'){
                $('.view_to_numeric').css('display', 'block');
                $('.optionGroup').css('display', 'none');
                $('.view_to_textbox').css('display', 'none');
            }else if(type =='radio' || type =='dropdown' || type =='checkbox'){
                $('.optionGroup').css('display', 'block')
                $('.view_to_numeric').css('display', 'none');
                $('.view_to_textbox').css('display', 'none');
            }else if(type =='text'){
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
                        options += '<option value="'+v.step_id+'" >'+v.step_name+'</option>';
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
                    $('#selected-sections').html('');
                    html += '<div id="accordion">';
                    $.each(response['data'],function(k,v){
                        var show = (k ==0) ? 'show' : '';
                        html += '<div class="card"><div class="card-header"><a class="card-link" data-toggle="collapse" href="#collapse_'+v.id+'">'+v.sort_number+'&nbsp;&nbsp;&nbsp;&nbsp;'+v.name+'</a></div><div id="collapse_'+v.id+'" class="collapse '+show+'" data-parent="#accordion"><div class="card-body">Lorem ipsum dolor sit amet,</div></div></div>';
                        sections += '<option value="'+v.id+'">'+v.name+'</option>'
                    });
                    html +='</div>';
                    $('.display-sections').html(html);
                    $('#selected-sections').append(sections);
                    $("#wait").css("display", "none");   
                }    
            });
       })
   </script>     
@stop