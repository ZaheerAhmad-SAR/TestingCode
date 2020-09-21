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
                        <option value="">---Select Step / Form---</option>
                    </select>
                </div>
                <div class="col-sm-2">
                <button class="btn btn-outline-primary" onclick="showFormPreview();" style="background-color: white;">
                    <i class="far fa-eye"></i> Preview</button>
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
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="min-width: 1130px;">
        <div class="modal-content" style="min-height: 560px;">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <p class="modal-title">Add New Field</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{route('addQuestions')}}" enctype="multipart/form-data" method="POST" id="formfields">
            @csrf    
            <div class="modal-body">
                <nav>
                    <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-Basic-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic</a>
                        <a class="nav-item nav-link" id="nav-Validation-tab" data-toggle="tab" href="#nav-Validation" role="tab" aria-controls="nav-profile" aria-selected="false">Data validation</a>
                        <a class="nav-item nav-link" id="nav-Dependencies-tab" data-toggle="tab" href="#nav-Dependencies" role="tab" aria-controls="nav-contact" aria-selected="false">Dependencies</a>
                        <a class="nav-item nav-link" id="nav-Annotations-tab" data-toggle="tab" href="#nav-Annotations" role="tab" aria-controls="nav-contact" aria-selected="false">Annotations</a>
                        <a class="nav-item nav-link" id="nav-Advanced-tab" data-toggle="tab" href="#nav-Advanced" role="tab" aria-controls="nav-contact" aria-selected="false">Advanced</a>
                        <a class="nav-item nav-link" id="nav-Advanced-tab" data-toggle="tab" href="#nav-Adjudication" role="tab" aria-controls="nav-contact" aria-selected="false">Adjudication</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                        <div class="py-3 border-bottom border-primary">
                            <span class="text-muted font-w-600">Define Basic Attribute of Question</span><br>

                        </div>
                        <div class="form-group row" style="margin-top: 10px;">
                            <label for="Sorting" class="col-sm-2 col-form-label">Sort Number / Position</label>   
                            <div class="col-sm-4">
                                <input type="hidden" name="question_id" id="questionId_hide" value="">
                                <input type="Number" name="question_sort" id="question_sort" class="form-control" placeholder="Sort Number / Placement Place">
                            </div>
                            <label for="Sections" class="col-sm-2 col-form-label">Sections</label>   
                            <div class="col-sm-4">
                                <select name="section_id" id="section_id" class="form-control">
                                    <option value="">Choose Phase/Visit && Step/Form-Type</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="C-DISC" class="col-sm-2 col-form-label">C-DISC <sup>*</sup></label>      
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="c_disk" id="c_disk" value="">
                            </div>
                            <label for="label" class="col-sm-2 col-form-label"> Label <sup>*</sup></label>    
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="question_text" id="question_text" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <lable for='variable' class="col-sm-2 col-form-label">Variable name <sup>*</sup></lable>    
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="variable_name" id="variable_name" value="">
                            </div>
                            <label for="field" class="col-sm-2 col-form-label">Choose field type:</label>
                            <div class="col-sm-4">
                                <select name="form_field_type_id" id="question_type" class="form-control">
                                    <option value="">--- Field Type ---</option>
                                    @foreach($fields as $key => $value)
                                    <option value="{{ $value->id }}">{{ $value->field_type }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="Required" class="col-sm-2 col-form-label">Required <sup>*</sup></label>    
                            <div class="col-sm-4">
                                <input type="radio" name="is_required" id="required_yes" value="no"> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="is_required" id="required_no" value="yes" checked> Yes
                            </div>
                            <label for="Required" class="col-sm-2 col-form-label view_to_numeric">Uper $ Lower <sup>*</sup></label>
                            <div class="col-sm-2 view_to_numeric">
                                <input type="number" name="lower_limit" id="lower_limit_num" class="form-control" placeholder="Minimum limits">
                            </div>  
                            <div class="col-sm-2 view_to_numeric">  
                                <input type="number" name="upper_limit" id="upper_limit_num" class="form-control" placeholder="Maximum limits">
                            </div>
                        </div>
                        <div class="view_to_textbox_and_number">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Field width <sup>*</sup></label>    
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="field_width" id="field_width_text" value="">
                                </div>
                                <label class="col-sm-2 col-form-label">Measurement unit</label>    
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="measurement_unit" id="measurement_unit_text" value="">
                                </div>
                            </div>
                        </div>    
                        <div class="optionGroup">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Option group: </label>    
                                <div class="col-sm-8">
                                    <select name="option_group_id" id="option_group_id" class="form-control">
                                        <option value="">None</option>
                                        @foreach($option_groups as $key => $value)
                                        <option value="{{$value->id}}">{{$value->option_group_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#addOptionGroups">
                                        <i class="fa fa-plus"></i> Add Option Groups
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Text/info: <sup>*</sup></label>    
                            <div class="col-sm-10">
                                <textarea name="text_info" id="text_info" cols="2" rows="2" class="form-control" style="height: 50px;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-Validation" role="tabpanel" aria-labelledby="nav-Validation-tab">
                        <div class="py-3 border-bottom border-primary">
                            <span class="text-muted font-w-600">Default Validation</span><br>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin-top: 10px;padding-left: 0px;"><button type="button" class="btn btn-outline-primary addvalidations"><i class="fa fa-plus"></i> Add Message</button></div>    
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
                                <input type="radio" name="is_exportable_to_xls" id="is_exportable_to_xls_no" value="no"> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="is_exportable_to_xls" id="is_exportable_to_xls_yes"  value="yes" checked> Yes
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-Adjudication" role="tabpanel" aria-labelledby="nav-Advanced-tab">
                        <div class="py-3 border-bottom border-primary">
                            <span class="text-muted font-w-600">Set Up Adjudication </span><br>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin-top: 10px;padding-left: 0px;"><button type="button" class="btn btn-outline-primary defineAdjudication"><i class="fa fa-plus"></i> Define Adjudication</button></div>    
                        </div>
                        <div class="appendAdj">
                            
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
<!-- Modal To add Option Groups -->
<div class="modal fade" tabindex="-1" role="dialog" id="addOptionGroups">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content" style="background-color: #F6F6F7;">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header" style="background: #1E3D73;color: white;">
                <p class="modal-title">Add Option Group</p>
            </div>
            <form name="OptionsGroupForm" id="OptionsGroupForm">
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                            <div class="form-group row">
                                <div class="form-group col-md-12">
                                    <input type="text" class="form-control" id="option_group_name" name="option_group_name" value="" placeholder="Enter option group name" style="background: white;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="form-group col-md-12">
                                    <input type="text" class="form-control" id="option_group_description" name="option_group_description" value="" placeholder="Option group description" style="background: white;">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">Option Layout <sup>*</sup></div>
                                <div class="form-group col-md-9">
                                    <input type="radio" name="option_layout" value="vertical"> Vertical &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="option_layout" value="horizontal" checked> Horizontal
                                </div>
                            </div>
                            <div class="appendDataOptions"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary addOptions pull-right"><i class="fa fa-plus"></i> Add option</button>
                        <button id="optiongroup-close" class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End -->
<!-- Modal To add Option Groups -->
<div class="modal fade" tabindex="-1" role="dialog" id="ChangeQuestionSort">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <p class="modal-title">Update Question Sort Number</p>
            </div>
            <form name="QuestionSort" id="QuestionSortForm">
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                            <div class="form-group row">
                                <div class="form-group col-md-12">
                                    <input type="hidden" class="form-control" id="questionId" name="questionId" value="">
                                    <input type="text" class="form-control" id="up_question_sort" name="question_sort" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="question-sort-close" class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="button" class="btn btn-outline-primary updateSort"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End -->
@endsection
@section('styles')
<style>
    .custom_fields{
        border-bottom: 1px solid #F6F6F7;
        padding: 10px;
    }
    .float-right{
        float: right;
    }
</style>
<link rel="stylesheet" href="{{ asset('public/dist/vendors/quill/quill.snow.css') }}" />
@endsection
@section('script')  
<script src="{{ asset('public/dist/vendors/quill/quill.min.js') }}"></script> 
<script src="{{ asset('public/dist/js/mail.script.js') }}"></script>
<script src="{{ asset('public/js/edit_crf.js') }}"></script>  

<script>
   $('.addOptions').on('click',function(){
       $('.appendDataOptions').append('<div class="values_row_options"><div class="form-group row"><div class="form-group col-md-6"><input type="text" id="option_name" name="option_name[]" class="form-control" placeholder="Enter option name" style="background:white;"></div><div class="form-group col-md-4"><input type="number" placeholder="Option value" name="option_value[]" id="option_value" class="form-control" style="background:white;"></div><div class="form-group col-md-1" style="text-align: right;!important;"><i class="btn btn-outline-danger fa fa-trash remove_option" style="margin-top: 3px;"></i></div></div></div>');
       return false;
   });
   $('.addvalidations').on('click',function(){
       $('.appendDatavalidations').append('<div class="values_row"><div class="form-group row" style="margin-top: 10px;"><div class="col-sm-2">Click on Fetch get to phases </div><div class="col-sm-4"><span style="float: right;"><input type="button" value="Fetch" class="btn btn-primary fetch_phases"></span><span><select class="form-control all_phases" style="width: 82%;"><option value="">--- Select Phase---</option></select></span></div><div class="col-sm-2">Steps / Visits</div><div class="col-sm-3"><select class="form-control all_forms"><option value="">---Select Step / Form---</option></select></div><div class="col-sm-1"><i class="btn btn-outline-danger fa fa-trash remove" style="cursor:pointer;float:right;margin-top:3px;"></i></div></div><div class="form-group row"><div class="col-sm-2"> Sections:</div><div class="col-sm-4"><select class="form-control all_sections"><option value="">---Forms / Sections---</option></select></div><div class="col-sm-2"> Questions:</div><div class="col-sm-4"><select class="form-control all_questions"><option value="">---Select Question---</option></select></div></div><div class="form-group row"><div class="col-sm-2"> Take Decision:</div><div class="col-sm-4"><select class="form-control decision"><option value="">---Based ON---</option><option value="question_value">Question Value</option><option value="custom_value">Custom Value</option></select> </div><div class="col-sm-2"> Condition:</div><div class="col-sm-4"><select class="form-control decision"><option value="and">AND</option><option value="or">OR</option></select></div></div><div class="form-group row"><div class="col-sm-2"> Operator:</div><div class="col-sm-4"><select class="form-control decision"><option value="">---Select---</option><option value="=">Equal</option><option value=">=">Greater OR Equal</option><option value="<=">Less OR Equal</option><option value="!=">Not Equal</option><option value=">">Greater Then</option><option value="<">Less</option></select></div><div class="col-sm-2 questionValue" style="display: none;">With:</div><div class="col-sm-4 questionValue" style="display: none;"><select class="form-control decision_question"><option value="">---Select Question---</option> </select></div><div class="col-sm-2 customValue" style="display: none;">Decision Value:</div><div class="col-sm-4 customValue" style="display: none;"><input type="text" name="custom_value" class="form-control custom_value" placeholder="Define Value"></div></div><div class="form-group row"><div class="col-sm-2"> Show a:</div><div class="col-sm-4"><select name="requiredvalidation_value" id="requiredvalidation_value" class="form-control"><option value="">Exclusion</option><option value="">Error</option><option value="">Warning</option></select> </div><div class="col-sm-2">Message:</div><div class="col-sm-4"><textarea name="validation_message" class="form-control" rows="1"></textarea></div></div></div>');
           
       return false;
   });
   $('.defineAdjudication').on('click',function(){
       $('.appendAdj').append('<div class="values_row"><div class="form-group row" style="margin-top: 10px;"><div class="col-sm-2">Click on Fetch get to phases </div><div class="col-sm-4"><span style="float: right;"><input type="button" value="Fetch" class="btn btn-primary fetch_phases"></span><span><select class="form-control all_phases" style="width: 82%;"><option value="">--- Select Phase---</option></select></span></div><div class="col-sm-2">Steps / Visits</div><div class="col-sm-3"><select class="form-control all_forms"><option value="">---Select Step / Form---</option></select></div><div class="col-sm-1"><i class="btn btn-outline-danger fa fa-trash remove" style="cursor:pointer;float:right;margin-top:3px;"></i></div></div><div class="form-group row"><div class="col-sm-2"> Sections:</div><div class="col-sm-4"><select class="form-control all_sections"><option value="">---Forms / Sections---</option></select></div><div class="col-sm-2"> Questions:</div><div class="col-sm-4"><select class="form-control all_questions"><option value="">---Select Question---</option></select></div></div><div class="form-group row"><div class="col-sm-2"> Take Decision:</div><div class="col-sm-4"><select class="form-control decision"><option value="">---Based ON---</option><option value="question_value">Question Value</option><option value="custom_value">Custom Value</option></select> </div><div class="col-sm-2"> Condition:</div><div class="col-sm-4"><select class="form-control decision"><option value="and">AND</option><option value="or">OR</option></select></div></div><div class="form-group row"><div class="col-sm-2"> Operator:</div><div class="col-sm-4"><select class="form-control decision"><option value="">---Select---</option><option value="=">Equal</option><option value=">=">Greater OR Equal</option><option value="<=">Less OR Equal</option><option value="!=">Not Equal</option><option value=">">Greater Then</option><option value="<">Less</option></select></div><div class="col-sm-2 questionValue" style="display: none;">With:</div><div class="col-sm-4 questionValue" style="display: none;"><select class="form-control decision_question"><option value="">---Select Question---</option> </select></div><div class="col-sm-2 customValue" style="display: none;">Decision Value:</div><div class="col-sm-4 customValue" style="display: none;"><input type="text" name="custom_value" class="form-control custom_value" placeholder="Define Value"></div></div><div class="form-group row"><div class="col-sm-2"> Show a:</div><div class="col-sm-4"><select name="requiredvalidation_value" id="requiredvalidation_value" class="form-control"><option value="">Exclusion</option><option value="">Error</option><option value="">Warning</option></select> </div><div class="col-sm-2">Message:</div><div class="col-sm-4"><textarea name="validation_message" class="form-control" rows="1"></textarea></div></div></div>');
           
       return false;
   });
   
   $('body').on('click','.remove',function(){
        var row = $(this).closest('div.values_row');
        row.remove();
   })
   $('body').on('click','.remove_option',function(){
        var row = $(this).closest('div.values_row_options');
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
            $('.append_if_yes').append('<div class="values_row"><div class="form-group row" style="margin-top: 10px;"><div class="col-sm-2">Click on Fetch get to phases </div><div class="col-sm-4">   <span style="float: right;"><input type="button" value="Fetch" class="btn btn-primary fetch_phases"></span><span><select class="form-control all_phases" style="width: 82%;"><option value="">--- Select Phase---</option></select></span></div><div class="col-sm-2">Steps / Visits</div><div class="col-sm-3"><select class="form-control all_forms"><option value="">---Select Step / Form---</option></select></div><div class="col-sm-1"><i class="btn btn-outline-danger fa fa-trash remove" style="cursor:pointer;float:right;margin-top:3px;"></i></div></div><div class="form-group row"><div class="col-sm-2"> Sections:</div><div class="col-sm-4"><select class="form-control all_sections"><option value="">---Forms / Sections---</option></select></div><div class="col-sm-2"> Questions:</div><div class="col-sm-4"><select class="form-control all_questions"><option value="">---Select Question---</option></select></div></div><div class="form-group row"><div class="col-sm-2"> field operator:</div><div class="col-sm-4"><select class="form-control" name="dep_operator"><option value="">---Select---</option><option value="=">Equal</option><option value=">=">Greater OR Equal</option><option value="<=">Less OR Equal</option><option value="!=">Not Equal</option><option value=">">Greater Then</option><option value="<">Less</option></select></div><div class="col-sm-2">Value:</div><div class="col-sm-4"><input type="text" name="dep_value" class="form-control"></div></div></div>');
        }else{
            $('.append_if_yes').html('');
        }
   });
   
   $('body').on('click','.form-fields',function(){
        $('#formfields').trigger('reset');
        $('#formfields').attr('action', "{{route('addQuestions')}}");
        var id = $(this).attr("data-field-id");
        $('#question_type').val(id);
   })
   $('body').on('click','.fetch_phases',function(){
        var phase_id = '1';
        var row = $(this).closest('div.values_row');
        var phase_class = row.find('select.all_phases');
        get_all_phases(phase_id,phase_class);
   })
   $('body').on('change','.all_phases',function(){
        var phase_id = $(this).val();
        var row = $(this).closest('div.values_row');
        var step_class = row.find('select.all_forms');
        get_steps_phase_id(phase_id,step_class);
   });
   $('body').on('change','.all_forms',function(){
        var step_id = $(this).val();
        var row = $(this).closest('div.values_row');
        var section_class = row.find('select.all_sections');
        get_section_step_id(step_id,section_class);
   });
   $('body').on('change','.all_sections',function(){
        var sec_id = $(this).val();
        var row = $(this).closest('div.values_row');
        var ques_class = row.find('select.all_questions');
        get_question_section_id(sec_id,ques_class);
   });
   $('body').on('change','.decision',function(){
        var value = $(this).val();
        var row = $(this).closest('div.values_row');
        var sec_id = row.find('select.all_sections').val();
        var ques_class = row.find('select.decision_question');
        if(value == 'question_value'){
            row.find('.questionValue').css('display', 'block');
            row.find('.customValue').css('display', 'none');
            get_question_section_id(sec_id,ques_class);
        }else if(value == 'custom_value'){
            row.find('.customValue').css('display', 'block');
            row.find('.questionValue').css('display', 'none');
        }
   })
   $('#phases').on('change',function(){
        var phase_id = $(this).val();
        var step_class = $('select#steps');
        get_steps_phase_id(phase_id,step_class);
        $('#steps').trigger('change');
   })
    $('#steps').on('change',function(){
        var step_id = $(this).val();
        display_sections(step_id);
    })
    /// update Question sort
    $(document).ready(function(){
        $('body').on('click','.change_ques_sort',function(){
            var row = $(this).closest('div.custom_fields');
            var question_id = row.find('input.question_id').val();
            var question_sort = row.find('input.question_sort').val();
            $('#questionId').val(question_id);
            $('#up_question_sort').val(question_sort);
            $('#ChangeQuestionSort').modal('show');
        })
        $('body').on('click', '.delete_ques',function(){
            var row = $(this).closest('div.custom_fields');
            var question_id = row.find('input.question_id').val();
            if(confirm('Are you sure to delete ?')){
                $.ajax({
                    url:'forms/delete/'+question_id,
                    type: 'post',
                    data:{
                        "_token": "{{ csrf_token() }}",
                        "_method": 'DELETE',
                        'questionId':question_id,
                    },
                    dataType:'json',
                    success:function(res){
                       row.remove();
                    }
                })
            }
        })
        // update question 
        $('body').on('click', '.Edit_ques',function(){
            $('#formfields').trigger('reset');
            $('#formfields').attr('action', "{{route('updateQuestion')}}");
            var row = $(this).closest('div.custom_fields');
            var ques_id = row.find('input.question_id').val();
            var ques_type = row.find('input.question_type').val();
            var ques_type_id = row.find('input.question_type_id').val();
            var question_sort = row.find('input.question_sort').val();
            var section_id = row.find('input.section_id').val();
            var option_group_id = row.find('input.option_group_id').val();
            var c_disk = row.find('input.c_disk').val();
            var question_text = row.find('input.question_text').val();
            var variable_name = row.find('input.variable_name').val();
            var text_info = row.find('input.text_info').val();
            var is_required = row.find('input.is_required').val();
            var is_exportable_to_xls = row.find('input.is_exportable_to_xls').val();
            var measurement_unit = row.find('input.measurement_unit').val();
            var field_width = row.find('input.field_width').val();
            var upper_limit = row.find('input.upper_limit').val();
            var lower_limit = row.find('input.lower_limit').val();
            $('#questionId_hide').val(ques_id);
            $('#question_type').val(ques_type_id);
            $('#question_type').trigger('change');
            $('#question_sort').val(question_sort);
            $('#section_id').val(section_id);
            $('#option_group_id').val(option_group_id);
            $('#c_disk').val(c_disk);
            $('#question_text').val(question_text);
            $('#variable_name').val(variable_name);
            $('#text_info').val(text_info);
            if(ques_type =='Number'){
                $('#measurement_unit_num').val(measurement_unit);
                $('#field_width_num').val(field_width);
                $('#lower_limit_num').val(lower_limit);
                $('#upper_limit_num').val(upper_limit);
            }else{
                $('#measurement_unit_text').val(measurement_unit);
                $('#field_width_text').val(field_width);
            }
            if(is_required =='yes'){
                $('#required_yes').prop('checked',true);
            }else{
                $('#required_no').prop('checked',true);
            }
            if(is_exportable_to_xls =='yes'){
                $('#is_exportable_to_xls_yes').prop('checked',true);
            }else{
                $('#is_exportable_to_xls_no').prop('checked',true);
            }
            $('#addField').modal('show');
        })
    })

    function display_sections(step_id)
    {
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
                    html += '<div class="card"><div class="card-header"><a class="card-link" data-toggle="collapse" href="#collapse_'+v.id+'">'+v.sort_number+'&nbsp;&nbsp;&nbsp;&nbsp;'+v.name+'</a></div><div id="collapse_'+v.id+'" class="collapse '+show+'" data-parent="#accordion"><div class="card-body questions_'+v.id+'">';
                    getQuestions(v.id);
                    html += '</div></div></div>';
                    sections += '<option value="'+v.id+'">'+v.name+'</option>'
                });
                html +='</div>';
                $('.display-sections').html(html);
                $('#section_id').append(sections);
                $("#wait").css("display", "none");
            }
        });
    }
    // display question against form
    function getQuestions(id){
        var html2 = '';
        $.ajax({
            url:'forms/get_allQuestions/'+id,
            type:'post',
            dataType:'json',
            data:{
                "_token": "{{ csrf_token() }}",
                "_method": 'GET',
                'id': id
            },
            success:function(res){
               $('questions_'+id).html('');
               $.each(res['data'],function(i,j){
                    console.log(j);
                    if(j.option_layout ==='vertical'){
                        var br = '<br>';
                    }else{
                        var br = '';
                    }
                    if(j['form_field_type'].field_type ==='Radio'){
                       var options = [];
                       var optionsvalues = [];
                       optionsvalues = j.option_value.split(',');
                       options = j.option_name.split(",");
                       html2 += '<div class="form-group row custom_fields"><input type="hidden" class="question_id" value="'+j.question_id+'"><input type="hidden" class="question_sort" value="'+j.question_sort+'"><input type="hidden" class="question_type_id" value="'+j['form_field_type'].id+'"><input type="hidden" class="section_id" value="'+id+'"><input type="hidden" class="option_group_id" value="'+j.option_group_id+'"><input type="hidden" class="c_disk" value="'+j.c_disk+'"><input type="hidden" class="question_text" value="'+j.question_text+'"><input type="hidden" class="variable_name" value="'+j.variable_name+'"><input type="hidden" class="text_info" value="'+j.text_info+'"><input type="hidden" class="is_required" value="'+j.is_required+'"><input type="hidden" class="is_exportable_to_xls" value="'+j.is_exportable_to_xls+'"><div class="col-sm-4">'+j.question_sort+'. '+j.question_text+'</div><div class="col-sm-6">';
                       $.each(options, function(k,v){
                            html2 += '<input type="radio" name="'+j.option_group_name+'_'+j.question_id+'" value="'+optionsvalues[k]+'"> &nbsp;'+v+'&nbsp;'+br ;
                       })
                       html2 += '</div><div class="col-sm-2"><div class="d-flex mt-3 mt-md-0 ml-auto float-right"><span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span><div class="dropdown-menu p-0 m-0 dropdown-menu-right"><span class="dropdown-item Edit_ques"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span><span class="dropdown-item delete_ques"><a href="#"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span><span class="dropdown-item change_ques_sort"><a href="#"><i class="fas fa-arrows-alt"></i>&nbsp; Change Sort # </a></span></div></div></div></div>';
                    }else if(j['form_field_type'].field_type ==='Text'){
                       html2 += '<div class="form-group row custom_fields"><input type="hidden" class="question_id" value="'+j.question_id+'"><input type="hidden" class="question_sort" value="'+j.question_sort+'"><input type="hidden" class="question_type_id" value="'+j['form_field_type'].id+'"><input type="hidden" class="section_id" value="'+id+'"><input type="hidden" class="option_group_id" value="'+j.option_group_id+'"><input type="hidden" class="c_disk" value="'+j.c_disk+'"><input type="hidden" class="question_text" value="'+j.question_text+'"><input type="hidden" class="variable_name" value="'+j.variable_name+'"><input type="hidden" class="text_info" value="'+j.text_info+'"><input type="hidden" class="is_required" value="'+j.is_required+'"><input type="hidden" class="is_exportable_to_xls" value="'+j.is_exportable_to_xls+'"><input type="hidden" class="field_width" value="'+j.field_width+'"><input type="hidden" class="measurement_unit" value="'+j.measurement_unit+'"><input type="hidden" class="lower_limit" value="'+j.lower_limit+'"><input type="hidden" class="upper_limit" value="'+j.upper_limit+'"><input type="hidden" class="question_type" value="'+j['form_field_type'].field_type+'"><div class="col-sm-4">'+j.question_sort+'. '+j.question_text+'</div>';
                       html2 += '<div class="col-sm-6"> <input type="text" name="'+j.variable_name+'" value="" class="form-control"></div><div class="col-sm-2"><div class="d-flex mt-3 mt-md-0 ml-auto float-right"><span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span><div class="dropdown-menu p-0 m-0 dropdown-menu-right"><span class="dropdown-item Edit_ques"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span><span class="dropdown-item delete_ques"><a href="#"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span><span class="dropdown-item change_ques_sort"><a href="#"><i class="fas fa-arrows-alt"></i>&nbsp; Change Sort # </a></span></div></div></div></div>';
                    }else if(j['form_field_type'].field_type ==='Number'){
                       html2 += '<div class="form-group row custom_fields"><input type="hidden" class="question_id" value="'+j.question_id+'"><input type="hidden" class="question_sort" value="'+j.question_sort+'"><input type="hidden" class="question_type_id" value="'+j['form_field_type'].id+'"><input type="hidden" class="section_id" value="'+id+'"><input type="hidden" class="option_group_id" value="'+j.option_group_id+'"><input type="hidden" class="c_disk" value="'+j.c_disk+'"><input type="hidden" class="question_text" value="'+j.question_text+'"><input type="hidden" class="variable_name" value="'+j.variable_name+'"><input type="hidden" class="text_info" value="'+j.text_info+'"><input type="hidden" class="is_required" value="'+j.is_required+'"><input type="hidden" class="is_exportable_to_xls" value="'+j.is_exportable_to_xls+'"><input type="hidden" class="field_width" value="'+j.field_width+'"><input type="hidden" class="measurement_unit" value="'+j.measurement_unit+'"><input type="hidden" class="lower_limit" value="'+j.lower_limit+'"><input type="hidden" class="upper_limit" value="'+j.upper_limit+'"><input type="hidden" class="question_type" value="'+j['form_field_type'].field_type+'"><div class="col-sm-4">'+j.question_sort+'. '+j.question_text+'</div>';
                       html2 += '<div class="col-sm-6"> <input type="number" name="'+j.variable_name+'" value="" class="form-control"></div><div class="col-sm-2"><div class="d-flex mt-3 mt-md-0 ml-auto float-right"><span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span><div class="dropdown-menu p-0 m-0 dropdown-menu-right"><span class="dropdown-item Edit_ques"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span><span class="dropdown-item delete_ques"><a href="#"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span><span class="dropdown-item change_ques_sort"><a href="#"><i class="fas fa-arrows-alt"></i>&nbsp; Change Sort # </a></span></div></div></div></div>';
                    }else if(j['form_field_type'].field_type ==='Dropdown'){
                       var optionsvalues = [];
                       optionsvalues = j.option_value.split(',');
                       var options = [];
                       options = j.option_name.split(","); 
                       html2 += '<div class="form-group row custom_fields"><input type="hidden" class="question_id" value="'+j.question_id+'"><input type="hidden" class="question_sort" value="'+j.question_sort+'"><input type="hidden" class="question_type_id" value="'+j['form_field_type'].id+'"><input type="hidden" class="section_id" value="'+id+'"><input type="hidden" class="option_group_id" value="'+j.option_group_id+'"><input type="hidden" class="c_disk" value="'+j.c_disk+'"><input type="hidden" class="question_text" value="'+j.question_text+'"><input type="hidden" class="variable_name" value="'+j.variable_name+'"><input type="hidden" class="text_info" value="'+j.text_info+'"><input type="hidden" class="is_required" value="'+j.is_required+'"><input type="hidden" class="is_exportable_to_xls" value="'+j.is_exportable_to_xls+'"><div class="col-sm-4">'+j.question_sort+'. '+j.question_text+'</div><div class="col-sm-6"><select name="'+j.option_group_name+'" class="form-control">';

                       $.each(options, function(k,v){
                            if(k !=''){
                                html2 += '<option value="'+optionsvalues[k]+'">'+v+'<option>';
                            }
                       })
                       html2 += '</select></div><div class="col-sm-2"><div class="d-flex mt-3 mt-md-0 ml-auto float-right"><span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span><div class="dropdown-menu p-0 m-0 dropdown-menu-right"><span class="dropdown-item Edit_ques"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span><span class="dropdown-item delete_ques"><a href="#"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span><span class="dropdown-item change_ques_sort"><a href="#"><i class="fas fa-arrows-alt"></i>&nbsp; Change Sort # </a></span></div></div></div></div>';
                    }else if(j['form_field_type'].field_type ==='Checkbox'){
                       var optionsvalues = [];
                       optionsvalues = j.option_value.split(',');
                       var options = [];
                       options = j.option_name.split(",");
                       html2 += '<div class="form-group row custom_fields"><input type="hidden" class="question_id" value="'+j.question_id+'"><input type="hidden" class="question_sort" value="'+j.question_sort+'"><input type="hidden" class="question_type_id" value="'+j['form_field_type'].id+'"><input type="hidden" class="section_id" value="'+id+'"><input type="hidden" class="option_group_id" value="'+j.option_group_id+'"><input type="hidden" class="c_disk" value="'+j.c_disk+'"><input type="hidden" class="question_text" value="'+j.question_text+'"><input type="hidden" class="variable_name" value="'+j.variable_name+'"><input type="hidden" class="text_info" value="'+j.text_info+'"><input type="hidden" class="is_required" value="'+j.is_required+'"><input type="hidden" class="is_exportable_to_xls" value="'+j.is_exportable_to_xls+'"><div class="col-sm-4">'+j.question_sort+'. '+j.question_text+'</div><div class="col-sm-6">';
                       $.each(options, function(k,v){
                            html2 += '<input type="checkbox" name="'+j.option_group_name+'" value="'+optionsvalues[k]+'"> &nbsp;'+v+'&nbsp; ';
                       })
                       html2 += '</div><div class="col-sm-2"><div class="d-flex mt-3 mt-md-0 ml-auto float-right"><span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span><div class="dropdown-menu p-0 m-0 dropdown-menu-right"><span class="dropdown-item Edit_ques"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span><span class="dropdown-item delete_ques"><a href="#"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span><span class="dropdown-item change_ques_sort"><a href="#"><i class="fas fa-arrows-alt"></i>&nbsp; Change Sort # </a></span></div></div></div></div>';
                    }else if(j['form_field_type'].field_type ==='Textarea'){
                       html2 += '<div class="form-group row custom_fields"><input type="hidden" class="question_id" value="'+j.question_id+'"><input type="hidden" class="question_sort" value="'+j.question_sort+'"><input type="hidden" class="question_type_id" value="'+j['form_field_type'].id+'"><input type="hidden" class="section_id" value="'+id+'"><input type="hidden" class="option_group_id" value="'+j.option_group_id+'"><input type="hidden" class="c_disk" value="'+j.c_disk+'"><input type="hidden" class="question_text" value="'+j.question_text+'"><input type="hidden" class="variable_name" value="'+j.variable_name+'"><input type="hidden" class="text_info" value="'+j.text_info+'"><input type="hidden" class="is_required" value="'+j.is_required+'"><input type="hidden" class="is_exportable_to_xls" value="'+j.is_exportable_to_xls+'"><div class="col-sm-4">'+j.question_sort+'. '+j.question_text+'</div>';
                       html2 += '<div class="col-sm-6"> <textarea name="'+j.variable_name+'" value="" class="form-control"></textarea></div><div class="col-sm-2"><div class="d-flex mt-3 mt-md-0 ml-auto float-right"><span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span><div class="dropdown-menu p-0 m-0 dropdown-menu-right"><span class="dropdown-item Edit_ques"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span><span class="dropdown-item delete_ques"><a href="#"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span><span class="dropdown-item change_ques_sort"><a href="#"><i class="fas fa-arrows-alt"></i>&nbsp; Change Sort # </a></span></div></div></div></div>'; 
                    }else if(j['form_field_type'].field_type ==='Date & Time'){
                       html2 += '<div class="form-group row custom_fields"><input type="hidden" class="question_id" value="'+j.question_id+'"><input type="hidden" class="question_sort" value="'+j.question_sort+'"><input type="hidden" class="question_type_id" value="'+j['form_field_type'].id+'"><input type="hidden" class="section_id" value="'+id+'"><input type="hidden" class="option_group_id" value="'+j.option_group_id+'"><input type="hidden" class="c_disk" value="'+j.c_disk+'"><input type="hidden" class="question_text" value="'+j.question_text+'"><input type="hidden" class="variable_name" value="'+j.variable_name+'"><input type="hidden" class="text_info" value="'+j.text_info+'"><input type="hidden" class="is_required" value="'+j.is_required+'"><input type="hidden" class="is_exportable_to_xls" value="'+j.is_exportable_to_xls+'"><div class="col-sm-4">'+j.question_sort+'. '+j.question_text+'</div>';
                       html2 += '<div class="col-sm-6"> <input type="date" name="'+j.variable_name+'" value="" class="form-control"></div><div class="col-sm-2"><div class="d-flex mt-3 mt-md-0 ml-auto float-right"><span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span><div class="dropdown-menu p-0 m-0 dropdown-menu-right"><span class="dropdown-item Edit_ques"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span><span class="dropdown-item delete_ques"><a href="#"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span><span class="dropdown-item change_ques_sort"><a href="#"><i class="fas fa-arrows-alt"></i>&nbsp; Change Sort # </a></span></div></div></div></div>'; 
                    }else if(j['form_field_type'].field_type ==='Upload'){
                       html2 += '<div class="form-group row custom_fields"><input type="hidden" class="question_id" value="'+j.question_id+'"><input type="hidden" class="question_sort" value="'+j.question_sort+'"><input type="hidden" class="question_type_id" value="'+j['form_field_type'].id+'"><input type="hidden" class="section_id" value="'+id+'"><input type="hidden" class="option_group_id" value="'+j.option_group_id+'"><input type="hidden" class="c_disk" value="'+j.c_disk+'"><input type="hidden" class="question_text" value="'+j.question_text+'"><input type="hidden" class="variable_name" value="'+j.variable_name+'"><input type="hidden" class="text_info" value="'+j.text_info+'"><input type="hidden" class="is_required" value="'+j.is_required+'"><input type="hidden" class="is_exportable_to_xls" value="'+j.is_exportable_to_xls+'"><div class="col-sm-4">'+j.question_sort+'. '+j.question_text+'</div>';
                       html2 += '<div class="col-sm-6"> <input type="file" name="'+j.variable_name+'" value="" class="form-control"></div><div class="col-sm-2"><div class="d-flex mt-3 mt-md-0 ml-auto float-right"><span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span><div class="dropdown-menu p-0 m-0 dropdown-menu-right"><span class="dropdown-item Edit_ques"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span><span class="dropdown-item delete_ques"><a href="#"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span><span class="dropdown-item change_ques_sort"><a href="#"><i class="fas fa-arrows-alt"></i>&nbsp; Change Sort # </a></span></div></div></div></div>'; 
                    }
               });
               $('.questions_'+id).append(html2);
            }
        });

    }

    function showFormPreview(){
           var route = '{{url('forms/show')}}';
           var phase_id = $('#phases').val();
           var step_id = $('#steps').val();
           window.open(route+'/'+phase_id+'/'+step_id);
    }
  // Add New Option Group
   function addOptionsGroup()
   {
       $("#OptionsGroupForm").submit(function(e) {
           $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
           });
           e.preventDefault();
           $.ajax({
               data: $('#OptionsGroupForm').serialize(),
               url: "{{route('optionsGroup.store')}}",
               type: "POST",
               dataType: 'json',
               success: function (results) {
                fetch_options();
                $('#OptionsGroupForm').trigger("reset");
                $("#optiongroup-close").click();
               },
               error: function (results) {
                   console.log('Error:', results);
               }
           });
       });
   }
   addOptionsGroup();
   function fetch_options(){
        $('#option_group_id').html('');
        var options = '<option value="">None</option>';
        $.ajax({
            url:"{{route('getall_options')}}",
            type:"post",
            dataType:'json',
            success: function(res){
                $.each(res['data'], function(k,v){
                    options += '<option value="'+v.id+'">'+v.option_group_name+'</option>'
                })
            $('#option_group_id').append(options);
            }
        });
   }
   </script> 

@endsection