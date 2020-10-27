@extends ('layouts.home')
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12 align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Edit CRFs</h4>
                    </div>

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
            @if(session()->has('message'))
                <div class="col-lg-12 success-alert">
                    <div class="alert alert-primary success-msg" role="alert">
                        {{ session()->get('message') }}
                    </div>
                </div>
            @endif
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-lg-10 col-xl-10">
                <div class="row">
                    <label for="username" class="col-sm-1 col-form-label">Phases:</label>
                    <div class="col-sm-3">
                        <select id="phases" name="phases" class="form-control" style="background: #fff;">
                            <option value="">---Select Phase---</option>
                            @foreach ($phases as $key => $phase)
                                <option value="{{ $phase->id }}">{{ $phase->name }}</option>
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
                        <button class="btn btn-outline-primary" onclick="showFormPreview();"
                            style="background-color: white;">
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
                            @foreach ($fields as $key => $value)
                                @if($value->field_type =='Certification')
                                <div class="border-btm add_certify_list color-black" data-field-type="{{ $value->field_type }}"
                                    data-field-id="{{ $value->id }}" style="font-size: 12px;padding: 5px;cursor: pointer;"><i class="{{ $value->icon }}"
                                        aria-hidden="true" ></i>&nbsp;&nbsp;{{ $value->field_type }}</div>
                                @else
                                <div class="border-btm form-fields color-black" data-field-type="{{ $value->field_type }}"
                                    data-field-id="{{ $value->id }}"><i class="{{ $value->icon }}"
                                        aria-hidden="true"></i>&nbsp;&nbsp;{{ $value->field_type }}</div>
                                @endif
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
                <form action="{{ route('forms.addQuestions') }}" enctype="multipart/form-data" method="POST" id="formfields">
                    @csrf
                    <div class="modal-body">
                        <nav>
                            <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-Basic-tab" data-toggle="tab" href="#nav-Basic"
                                    role="tab" aria-controls="nav-home" aria-selected="true">Basic</a>
                                <a class="nav-item nav-link" id="nav-Validation-tab" data-toggle="tab"
                                    href="#nav-Validation" role="tab" aria-controls="nav-profile" aria-selected="false">Data
                                    validation</a>
                                <a class="nav-item nav-link" id="nav-Dependencies-tab" data-toggle="tab"
                                    href="#nav-Dependencies" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Dependencies</a>
                                <a class="nav-item nav-link" id="nav-Annotations-tab" data-toggle="tab"
                                    href="#nav-Annotations" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Annotations</a>
                                <a class="nav-item nav-link" id="nav-Advanced-tab" data-toggle="tab"
                                    href="#nav-Adjudication" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Adjudication</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel"
                                aria-labelledby="nav-Basic-tab">
                                <div class="py-3 border-bottom border-primary">
                                    <span class="text-muted font-w-600">Define Basic Attribute of Question</span><br>

                                </div>
                                <div class="form-group row" style="margin-top: 10px;">
                                    <label for="Sorting" class="col-sm-2 col-form-label">Sort Number / Position</label>
                                    <div class="col-sm-4">
                                        <input type="hidden" name="id" id="questionId_hide" value="">
                                        <input type="hidden" name="field_id" id="form_field_id" value="">
                                        <input type="Number" name="question_sort" id="question_sort" class="form-control"
                                            placeholder="Sort Number / Placement Place">
                                    </div>
                                    <label for="Sections" class="col-sm-2 col-form-label">Sections</label>
                                    <div class="col-sm-4">
                                        <select name="section_id" id="section_id" class="form-control basic_section">
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
                                        <input type="text" class="form-control" name="question_text" id="question_text"
                                            value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <lable for='variable' class="col-sm-2 col-form-label">Variable name <sup>*</sup></lable>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="variable_name" id="variable_name"
                                            value="">
                                    </div>
                                    <label for="field" class="col-sm-2 col-form-label">Choose field type:</label>
                                    <div class="col-sm-4">
                                        <select name="form_field_type_id" id="question_type" class="form-control">
                                            <option value="">--- Field Type ---</option>
                                            @foreach ($fields as $key => $value)
                                                <option value="{{ $value->id }}">{{ $value->field_type }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Required" class="col-sm-2 col-form-label view_to_numeric">Upper Limit
                                        <sup>*</sup></label>
                                    <div class="col-sm-4 view_to_numeric">
                                        <input type="number" name="lower_limit" id="lower_limit_num" class="form-control"
                                            placeholder="Minimum limits">
                                    </div>
                                    <label for="Upper Limit" class="col-sm-2 col-form-label view_to_numeric">Lower Limit</label>
                                    <div class="col-sm-4 view_to_numeric">
                                        <input type="number" name="upper_limit" id="upper_limit_num" class="form-control"
                                            placeholder="Maximum limits">
                                    </div>
                                </div>
                                <div class="view_to_textbox_and_number">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Field width <sup>*</sup></label>
                                        <div class="col-sm-4">
                                            <input type="number" class="form-control" name="field_width" id="field_width_text"
                                                value="">
                                        </div>
                                        <label class="col-sm-2 col-form-label">Measurement unit</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="measurement_unit"
                                                id="measurement_unit_text" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="optionGroup">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Option group: </label>
                                        <div class="col-sm-8">
                                            <select name="option_group_id" id="option_group_id" class="form-control">
                                                <option value="">None</option>
                                                @foreach ($option_groups as $key => $value)
                                                    <option value="{{ $value->id }}">{{ $value->option_group_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                                data-target="#addOptionGroups">
                                                <i class="fa fa-plus"></i> Add Option Groups
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Required" class="col-sm-2 col-form-label">Required <sup>*</sup></label>
                                    <div class="col-sm-4">
                                        <input type="radio" name="is_required" id="required_yes" value="no"> No
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="is_required" id="required_no" value="yes" checked> Yes
                                    </div>
                                    <div class="col-sm-2">Exports: <sup>*</sup></div>
                                    <div class="col-sm-4">
                                        <input type="radio" name="is_exportable_to_xls" id="is_exportable_to_xls_no" value="no"> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="is_exportable_to_xls" id="is_exportable_to_xls_yes"  value="yes" checked> Yes
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Text/info: <sup>*</sup></label>
                                    <div class="col-sm-10">
                                        <textarea name="text_info" id="text_info" cols="2" rows="1" class="summernote"
                                            style="height: 50px;"></textarea>
                                    </div>
                                </div>
                            </div>

<!--
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
                -->
                <div class="tab-pane fade" id="nav-Validation" role="tabpanel" aria-labelledby="nav-Validation-tab">
                    <div class="py-3 border-bottom border-primary">
                        <span class="text-muted font-w-600">Default Validation</span><br>
                    </div>
                   {{--  <div class="form-group">
                        <div class="col-sm-12" style="margin-top: 10px;padding-left: 0px;"><button type="button" class="btn btn-outline-primary addvalidations"><i class="fa fa-plus"></i> Add Message</button></div>
                    </div> --}}
                    <div class="values_row">
                        <div class="form-group row" style="margin-top: 10px;">
                            <div class="col-sm-2"> Take Decision:</div>
                            <div class="col-sm-4">
                                <select class="form-control decision"><option value="">---Based ON---</option><option value="question_value">Question Value</option><option value="custom_value">Custom Value</option></select>
                            </div>
                            <div class="col-sm-2"> Operator:</div>
                            <div class="col-sm-4">
                                <select class="form-control">
                                    <option value="">---Select---</option>
                                    <option value="=">Equal</option>
                                    <option value=">=">Greater OR Equal</option>
                                    <option value="<=">Less OR Equal</option>
                                    <option value="!=">Not Equal</option>
                                    <option value=">">Greater Then</option>
                                    <option value="<">Less</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 questionValue" style="display: none;">Section:</div>
                            <div class="col-sm-4 questionValue" style="display: none;">
                                <select class="form-control decisionSections">
                                    <option value="">---Section---</option>
                                </select>
                            </div>
                            <div class="col-sm-2 questionValue" style="display: none;">With:</div>
                            <div class="col-sm-4 questionValue" style="display: none;">
                                <select class="form-control decision_question">
                                    <option value="">---Select Question---</option>
                                </select>
                            </div>
                            <div class="col-sm-2 customValue" style="display: none;">Decision Value:</div>
                            <div class="col-sm-4 customValue" style="display: none;">
                                <input type="text" name="custom_value" class="form-control custom_value" placeholder="Define Value">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"> Condition:</div>
                            <div class="col-sm-10">
                                <select class="form-control operators">
                                    <option value="">Select if third conditon as well</option>
                                    <option value="and">AND</option>
                                    <option value="or">OR</option>
                                </select>
                            </div>
                        </div>
                        <div class="third_condition" style="display: none;">
                        <div class="form-group row">
                            <div class="col-sm-2"> Take Decision:</div>
                            <div class="col-sm-4">
                                <select class="form-control decision2">
                                    <option value="">---Based ON---</option>
                                    <option value="question_value_sec">Question Value</option>
                                    <option value="custom_value_sec">Custom Value</option>
                                </select>
                            </div>
                            <div class="col-sm-2"> Operator:</div>
                            <div class="col-sm-4">
                                <select class="form-control">
                                    <option value="">---Select---</option>
                                    <option value="=">Equal</option>
                                    <option value=">=">Greater OR Equal</option>
                                    <option value="<=">Less OR Equal</option>
                                    <option value="!=">Not Equal</option>
                                    <option value=">">Greater Then</option>
                                    <option value="<">Less</option>
                                </select>
                            </div>
                        </div>
                         <div class="form-group row">
                            <div class="col-sm-2 questionValue2" style="display: none;">Section:</div>
                            <div class="col-sm-4 questionValue2" style="display: none;">
                                <select class="form-control decisionSections2">
                                    <option value="">---Section---</option>
                                </select>
                            </div>
                            <div class="col-sm-2 questionValue2" style="display: none;">With:</div>
                            <div class="col-sm-4 questionValue2" style="display: none;">
                                <select class="form-control decision_question2">
                                    <option value="">---Select Question---</option>
                                </select>
                            </div>
                            <div class="col-sm-2 customValue2" style="display: none;">Decision Value:</div>
                            <div class="col-sm-4 customValue2" style="display: none;">
                                <input type="text" name="custom_value" class="form-control custom_value" placeholder="Define Value">
                            </div>
                        </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2"> Show a:</div>
                            <div class="col-sm-4">
                                <select name="requiredvalidation_value" id="requiredvalidation_value" class="form-control">
                                    <option value="">Exclusion</option>
                                    <option value="">Error</option>
                                    <option value="">Warning</option>
                                </select>
                            </div>
                            <div class="col-sm-2">Message:</div>
                            <div class="col-sm-4">
                                <textarea name="validation_message" class="form-control" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="appendDatavalidations values_row">
                    </div>

                </div>
                    <div class="tab-pane fade" id="nav-Dependencies" role="tabpanel" aria-labelledby="nav-Dependencies-tab">
                        <div class="py-3 border-bottom border-primary">
                            <span class="text-muted font-w-600">Define If Dependencies on any Question</span><br>
                        </div>
                        <div class="form-group row" style="margin-top: 10px;">
                            <div class="col-sm-2">Field is dependent: <sup>*</sup></div>
                            <div class="col-sm-10">
                                <input type="hidden" name="dependency_id" id="dependency_id">
                                <input type="radio" name="q_d_status" class="field_dependent" id="field_dependent_no" value="no" checked> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="q_d_status" class="field_dependent" id="field_dependent_yes" value="yes" > Yes
                            </div>
                        </div>
                        <div class="append_if_yes" style="display: none;">
                            <div class="form-group row">
                                <div class="col-sm-2"> Questions:</div>
                                <div class="col-sm-4">
                                    <select name="dep_on_question_id" class="form-control select_ques_for_dep" id="select_ques_for_dep">
                                        <option value="">---Select Question---</option>
                                    </select>
                                </div>
                                <div class="col-sm-2"> field operator:</div>
                                <div class="col-sm-4">
                                    <select name="opertaor" id="dependency_operator" class="form-control" name="dep_operator">
                                        <option value="">---Select---</option>
                                        <option value="=">Equal</option>
                                        <option value=">=">Greater OR Equal</option>
                                        <option value="<=">Less OR Equal</option>
                                        <option value="!=">Not Equal</option>
                                        <option value=">">Greater Then</option>
                                        <option value="<">Less</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2">Value:</div>
                                <div class="col-sm-4">
                                    <input type="text" name="custom_value" id="dependency_custom_value" class="form-control">
                                </div>
                            </div>
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

                        <div class="tab-pane fade" id="nav-Adjudication" role="tabpanel"
                            aria-labelledby="nav-Advanced-tab">
                            <div class="py-3 border-bottom border-primary">
                                <span class="text-muted font-w-600">Set Up Adjudication Status On Current Question</span><br>
                            </div>
                            <div class="form-group row" style="margin-top: 10px">
                                <input type="hidden" name="adj_id" id="adj_id">
                                <div class="col-sm-2">Adjudication</div>
                                <div class="col-sm-4">
                                    <select class="form-control" name="adj_status" id="adj_status">
                                        <option value="no">Not Required </option>
                                        <option value="yes"> Required </option>
                                    </select>
                                </div>
                                <div class="col-sm-2 display-none show_if_required">Based ON</div>
                                <div class="col-sm-4 display-none show_if_required">
                                    <select class="form-control" name="decision_based_on" id="decision_based_on">
                                        <option value="">---Decision---</option>
                                        <option value="any_change">Any Change</option>
                                        <option value="custom">Custom Value</option>
                                        <option value="percentage">Percentage Value</option>
                                    </select>
                                </div>
                            </div>
                            <div class="show_if_custom_percent display-none">
                                <div class="form-group row">
                                    <div class="col-sm-2"> Operator:</div>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="opertaor" id="adj_operator">
                                            <option value="">---Select---</option>
                                            <option value=">=">Greater OR Equal</option>
                                            <option value="<=">Less OR Equal</option>
                                            <option value=">">Greater Then</option>
                                            <option value="<">Less Then</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">Value:</div>
                                    <div class="col-sm-4">
                                        <input type="text" name="custom_value" id="adj_custom_value" class="form-control" placeholder="Define custom or percentage value for adjudication">
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close"
                                    aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save
                                Changes</button>
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
                                        <input type="text" class="form-control" id="option_group_name"
                                            name="option_group_name" value="" placeholder="Enter option group name"
                                            style="background: white;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" id="option_group_description"
                                            name="option_group_description" value="" placeholder="Option group description"
                                            style="background: white;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">Option Layout <sup>*</sup></div>
                                    <div class="form-group col-md-9">
                                        <input type="radio" name="option_layout" value="vertical"> Vertical
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="option_layout" value="horizontal" checked> Horizontal
                                    </div>
                                </div>
                                <div class="appendDataOptions"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary addOptions pull-right"><i
                                    class="fa fa-plus"></i> Add option</button>
                            <button id="optiongroup-close" class="btn btn-outline-danger" data-dismiss="modal"><i
                                    class="fa fa-window-close" aria-hidden="true"></i> Close</button>
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
                                        <input type="hidden" class="form-control" id="questionId" name="questionId"
                                            value="">
                                        <input type="text" class="form-control" id="up_question_sort" name="question_sort"
                                            value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="question-sort-close" class="btn btn-outline-danger" data-dismiss="modal"><i
                                    class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="button" class="btn btn-outline-primary updateSort"><i class="fa fa-save"></i> Save
                                Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End -->
    <!-- Modal To add Option Groups -->
    <div class="modal fade" tabindex="-1" role="dialog" id="listModal">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="min-width: 1130px;">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title"></p>
                </div>
                <form action="{{ route('forms.addQuestions') }}" enctype="multipart/form-data" method="POST" id="form_certify">
                    @csrf
                    <div class="modal-body">
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                <div class="form-group row" style="margin-top: 10px;">
                                    <input type="hidden" name="form_field_type_id" value="10">
                                    <label for="Sorting" class="col-sm-2 col-form-label">Sort Number / Position</label>
                                    <div class="col-sm-4">
                                        <input type="Number" name="question_sort" class="form-control"
                                            placeholder="Sort Number / Placement Place">
                                    </div>
                                    <label for="Sections" class="col-sm-2 col-form-label">Sections</label>
                                    <div class="col-sm-4">
                                        <select name="section_id" class="form-control basic_section">
                                            <option value="">Choose Phase/Visit && Step/Form-Type</option>
                                        </select>
                                    </div>
                                </div>    
                                <div class="form-group row">
                                    <label for="C-DISC" class="col-sm-2 col-form-label">C-DISC <sup>*</sup></label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="c_disk" value="">
                                    </div>
                                    <label for="label" class="col-sm-2 col-form-label"> Label <sup>*</sup></label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="question_text" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <lable for='variable' class="col-sm-2 col-form-label">Variable name <sup>*</sup></lable>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="variable_name" value="">
                                    </div>
                                    <label for="list" class="col-sm-2 col-form-label"> List type </label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="certification_type">
                                            <option value="">---Select---</option>
                                            <option value="photographers">Photographer List</option>
                                            <option value="devices">Device List</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Required" class="col-sm-2 col-form-label">Required <sup>*</sup></label>
                                    <div class="col-sm-4">
                                        <input type="radio" name="is_required" value="no"> No
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="is_required" value="yes" checked> Yes
                                    </div>
                                    <div class="col-sm-2">Exports: <sup>*</sup></div>
                                    <div class="col-sm-4">
                                        <input type="radio" name="is_exportable_to_xls" value="no"> No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="is_exportable_to_xls" value="yes" checked> Yes
                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="question-sort-close" class="btn btn-outline-danger" data-dismiss="modal"><i
                                    class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save
                                Changes</button>
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
    .display-none{
        display: none;
    }
</style>
<link rel="stylesheet" href="{{ asset('public/dist/vendors/quill/quill.snow.css') }}" />
<link rel="stylesheet" href="{{ asset("public/dist/vendors/summernote/summernote-bs4.css") }}">
{{-- Select 2 --}}
<link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2.min.css") }}"/>
<link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2-bootstrap.min.css") }}"/>

@endsection
@section('script')
<script src="{{ asset("public/dist/vendors/summernote/summernote-bs4.js") }}"></script>
<script src="{{ asset("public/dist/js/summernote.script.js") }}"></script>
<script src="{{ asset('public/dist/vendors/quill/quill.min.js') }}"></script>
<script src="{{ asset('public/dist/js/mail.script.js') }}"></script>
<script src="{{ asset('public/js/edit_crf.js') }}"></script>
{{-- Select 2 --}}
<script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/select2.script.js') }}"></script>
<script>
// selct initialize
// $('select[name="phases"]').select2();

$(document).ready(function(){
    var tId;
    tId=setTimeout(function(){
       $(".success-alert").slideUp('slow');
    }, 4000);
})
$('.addOptions').on('click',function(){
   $('.appendDataOptions').append('<div class="values_row_options"><div class="form-group row"><div class="form-group col-md-6"><input type="text" id="option_name" name="option_name[]" class="form-control" placeholder="Enter option name" style="background:white;"></div><div class="form-group col-md-4"><input type="number" placeholder="Option value" name="option_value[]" id="option_value" class="form-control" style="background:white;"></div><div class="form-group col-md-1" style="text-align: right;!important;"><i class="btn btn-outline-danger fa fa-trash remove_option" style="margin-top: 3px;"></i></div></div></div>');
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
   $('.appendannotation').append('<div class="anno_values_row"><div class="form-group row" style="margin-top: 10px;"><div class="col-sm-2">Terminology:</div><div class="col-sm-4"><span style="float: right;"><input type="button" value="Fetch" class="btn btn-primary fetch_annotation"></span><span><select name="terminology_id[]" class="form-control terminology_value" style="width: 82%;"><option value="">---Click on fetch to select Annotations---</option></select></span></div><div class="col-sm-1"> Value:</div><div class="col-sm-4"><input type="text" name="value[]" id="annotation_field_value" class="form-control"></div><div class="col-sm-1"><i class="btn btn-outline-danger fa fa-trash remove_anno" style="cursor:pointer;"></i></div></div><div class="form-group row"><div class="col-sm-2">Description:</div><div class="col-md-10"><textarea name="description[]" class="form-control" rows="2"></textarea></div></div></div>');
   return false;
});
$('body').on('click','.remove_anno',function(){
    var row = $(this).closest('div.anno_values_row');
    row.remove();
})
$('body').on('click','.fetch_annotation',function(){
    var study_id = '{{ session("current_study") }}';
    var row = $(this).closest('div.anno_values_row');
    var anno_class = row.find('select.terminology_value');
    get_all_annotations(study_id,anno_class);
})
$('#section_id').on('change',function(){
    $('.field_dependent').trigger('change');
})
$('.field_dependent').on('change',function(){
    var value = $(this).val();
    var sec_id = $('#section_id').val();
    var ques_class = $('.select_ques_for_dep');
    if(value =='yes'){
        $('.append_if_yes').css('display','block');
        get_question_section_id(sec_id,ques_class);
    }else{
        $('.append_if_yes').css('display','none');
    }
});

$('body').on('click','.form-fields',function(){
    $('#formfields').trigger('reset');
    $('.modal-title').html('Add New Question');
    $('#formfields').attr('action', "{{route('forms.addQuestions')}}");
    var id = $(this).attr("data-field-id");
    $('#question_type').val(id);
})
$('.add_certify_list').on('click',function(){
   $('#form_certify').trigger('reset');
   $('.modal-title').html('Add Certification list');
   $('#form_certify').attr('action', "{{route('forms.addQuestions')}}");
   var id = $(this).attr("data-field-id");
   $('#question_type').val(id);
   $('#listModal').modal('show');
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
    section_against_step(step_id,section_class);
});
$('body').on('change','.all_sections',function(){
    var sec_id = $(this).val();
    var row = $(this).closest('div.values_row');
    var ques_class = row.find('select.all_questions');
    get_question_section_id(sec_id,ques_class);
});
$('body').on('click','.fetch_sections',function(){
    var step_id = $('#steps').val();
    var row = $(this).closest('div.values_row');
    var section_class = row.find('select.all_sections');
    section_against_step(step_id,section_class);
})
$('body').on('click','.fetch_sections2',function(){
    var step_id = $('#steps').val();
    var row = $(this).closest('div.values_row');
    var section_class = row.find('select.all_sections2');
    section_against_step(step_id,section_class);
})
 $('body').on('change','.all_sections2',function(){
    var sec_id = $(this).val();
    var row = $(this).closest('div.values_row');
    var ques_class = row.find('select.all_questions2');
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
    }else if(value == 'custom_value'){
        row.find('.customValue').css('display', 'block');
        row.find('.questionValue').css('display', 'none');
    }
});
$('body').on('change','.decision2',function(){
    var value = $(this).val();
    var row = $(this).closest('div.values_row');
    var sec_id = row.find('select.all_sections').val();
    var ques_class = row.find('select.decision_question2');
    if(value == 'question_value_sec'){
        row.find('.questionValue2').css('display', 'block');
        row.find('.customValue2').css('display', 'none');
    }else if(value == 'custom_value_sec'){
        row.find('.customValue2').css('display', 'block');
        row.find('.questionValue2').css('display', 'none');
    }
});
$('body').on('change','.operators',function(){
    var value = $(this).val();
    var row = $(this).closest('div.values_row');
    if(value == 'and' || value == 'or'){
        row.find('.third_condition').css('display', 'block');
    }else if(value == ''){
         row.find('.third_condition').css('display', 'none');
    }
});
$('#phases').on('change',function(){
    var phase_id = $(this).val();
    var step_class = $('select#steps');
    get_steps_phase_id(phase_id,step_class);
})
$('#steps').on('change',function(){
    var step_id = $(this).val();
    var sec_class = $('select.decisionSections');
    var sec_class2 = $('select.decisionSections2');
    var basic_section = $('select.basic_section');
    display_sections(step_id);
    section_against_step(step_id,basic_section);
});
$('.decisionSections').on('change',function(){
    var sec_id = $(this).val();
    var ques_class = $('select.decision_question');
    get_question_section_id(sec_id,ques_class);
});
$('.decisionSections2').on('change',function(){
    var sec_id = $(this).val();
    var ques_class = $('select.decision_question2');
    get_question_section_id(sec_id,ques_class);
});

$('#adj_status').on('change',function(){
    var value = $(this).val();
    if(value =='yes'){
        $('.show_if_required').css('display', 'block');
    }else{
        $('.show_if_required').css('display', 'none');
        $('.show_if_custom_percent').css('display', 'none');
    }
})
$('#decision_based_on').on('change',function(){
    var value = $(this).val();
    if(value =='custom' || value =='percentage'){
        $('.show_if_custom_percent').css('display', 'block');
    }else{
        $('.show_if_custom_percent').css('display', '');
    }
})
/// update Question sort
$(document).ready(function() {
    $('body').on('click', '.change_ques_sort', function() {
        var row = $(this).closest('div.custom_fields');
        var question_id = row.find('input.question_id').val();
        var question_sort = row.find('input.question_sort').val();
        $('#questionId').val(question_id);
        $('#up_question_sort').val(question_sort);
        $('#ChangeQuestionSort').modal('show');
    })
    $('body').on('click', '.delete_ques', function() {
        var row = $(this).closest('div.custom_fields');
        var tId;
        var question_id = row.find('input.question_id').val();
        if (confirm('Are you sure to delete ?')) {
            $.ajax({
                url: 'forms/delete/' + question_id,
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'DELETE',
                    'questionId': question_id,
                },
                dataType: 'json',
                success: function(res) {
                    row.remove();
                    $('.success-msg').html('');
                     $('.success-msg').html('Operation Done!')
                     $('.success-alert').slideDown('slow');
                     tId=setTimeout(function(){
                        $(".success-alert").slideUp('slow');
                     }, 4000);
                }
            })
        }
    })
    // update question
    $('body').on('click', '.Edit_ques', function() {
        $('#formfields').trigger('reset');
        $('.modal-title').html('Update Question')
        $('#formfields').attr('action', "{{ route('forms.updateQuestion') }}");
        var row = $(this).closest('div.custom_fields')
            tId = ''
            ques_id = row.find('input.question_id').val()
            ques_type = row.find('input.question_type').val()
            ques_type_id = row.find('input.question_type_id').val()
            question_sort = row.find('input.question_sort').val()
            section_id = row.find('input.section_id').val()
            option_group_id = row.find('input.option_group_id').val()
            c_disk = row.find('input.c_disk').val()
            question_text = row.find('input.question_text').val()
            variable_name = row.find('input.variable_name').val()
            formFields_id = row.find('input.formFields_id').val()
            text_info = row.find('input.text_info').val()
            is_required = row.find('input.is_required').val()
            is_exportable_to_xls = row.find('input.is_exportable_to_xls').val()
            measurement_unit = row.find('input.measurement_unit').val()
            field_width = row.find('input.field_width').val()
            upper_limit = row.find('input.upper_limit').val()
            lower_limit = row.find('input.lower_limit').val()
            dependency_id = row.find('input.dependency_id').val()
            dependency_status = row.find('input.dependency_status').val()
            dependency_question = row.find('input.dependency_question').val()
            dependency_operator = row.find('input.dependency_operator').val()
            dependency_custom_value = row.find('input.dependency_custom_value').val()
            dependency_question_class = $('select.select_ques_for_dep')
            adj_status = row.find('input.adj_status').val()
            adj_decision_based = row.find('input.adj_decision_based').val()
            adj_operator = row.find('input.adj_operator').val()
            adj_custom_value = row.find('input.adj_custom_value').val()
            adj_id = row.find('input.adj_id').val();
        $('#questionId_hide').val(ques_id);
        $('#question_type').val(ques_type_id);
        $('#question_type').trigger('change');
        $('#question_sort').val(question_sort);
        $('#section_id').val(section_id);
        $('#option_group_id').val(option_group_id);
        $('#c_disk').val(c_disk);
        $('#question_text').val(question_text);
        $('#variable_name').val(variable_name);
        $('#form_field_id').val(formFields_id);
        // $('#text_info').val();
        $(".summernote").summernote("code", text_info);
        if (ques_type == 'Number') {
            $('#measurement_unit_text').val(measurement_unit);
            $('#field_width_text').val(field_width);
            $('#lower_limit_num').val(lower_limit);
            $('#upper_limit_num').val(upper_limit);
        } else {
            $('#measurement_unit_text').val(measurement_unit);
            $('#field_width_text').val(field_width);
        }
        if (is_required == 'yes') {
            $('#required_yes').prop('checked', true);
        } else {
            $('#required_no').prop('checked', true);
        }
        if (is_exportable_to_xls == 'yes') {
            $('#is_exportable_to_xls_yes').prop('checked', true);
        } else {
            $('#is_exportable_to_xls_no').prop('checked', true);
        }
        $('#dependency_id').val(dependency_id);
        if(dependency_status =='yes'){
            $('#field_dependent_yes').prop('checked',true);
            $('.field_dependent').trigger('change');
        }else{
            $('#field_dependent_no').prop('checked',true);
            $('.field_dependent').trigger('change');
        }
        get_question_section_id(section_id,dependency_question_class);
        $('#dependency_operator').val(dependency_operator);
        $('#dependency_custom_value').val(dependency_custom_value);
        tId=setTimeout(function(){
            $('#select_ques_for_dep').val(dependency_question);
        }, 2000);
        $('#adj_id').val(adj_id);
        $('#adj_status').val(adj_status);
        $('#adj_status').trigger('change');
        $('#decision_based_on').val(adj_decision_based)
        $('#decision_based_on').trigger('change');
        $('#adj_operator').val(adj_operator);
        $('#adj_custom_value').val(adj_custom_value);
        $('#addField').modal('show');
    })

})
function display_sections(step_id) {
    var html = '';
    var sections = '';
    $("#wait").css("display", "block");
    $.ajax({
        url: 'forms/sections_by_stepId/' + step_id,
        type: 'post',
        dataType: 'html',
        data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'step_id': step_id
        },
        success: function(response) {
            $('.display-sections').html(response);
            $('select[name="question_list"]').select2();
        }
    });
}

function applychecks(){
    var route = <?php echo '"'.url('forms/skip_logic').'";';?>;
    window.open(route + '/' + id);
}
function showFormPreview() {
    var route = <?php echo '"'.url('forms/show').'";';?>;
    var phase_id = $('#phases').val();
    var step_id = $('#steps').val();
    window.open(route + '/' + phase_id + '/' + step_id);
}
// Add New Option Group
function addOptionsGroup() {
    $("#OptionsGroupForm").submit(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        $.ajax({
            data: $('#OptionsGroupForm').serialize(),
            url: "{{ route('optionsGroup.store') }}",
            type: "POST",
            dataType: 'json',
            success: function(results) {
                fetch_options();
                $('#OptionsGroupForm').trigger("reset");
                $("#optiongroup-close").click();
            },
            error: function(results) {
                console.log('Error:', results);
            }
        });
    });
}
addOptionsGroup();
function fetch_options() {
    $('#option_group_id').html('');
    var options = '<option value="">None</option>';
    $.ajax({
        url: "{{ route('getall_options') }}",
        type: "post",
        dataType: 'json',
        success: function(res) {
            $.each(res['data'], function(k, v) {
                options += '<option value="' + v.id + '">' + v.option_group_name + '</option>'
            })
            $('#option_group_id').append(options);
        }
    });
}
/**************************************************************/
var validationRules =new Array;
$('#question_type').on('change',function(){
    filterRulesByQuestionType();
});
$('body').on('click','.form-fields',function(){
        filterRulesByQuestionType();
});
function filterRulesByQuestionType(){
    var questionType = $('#question_type :selected').text();
    $.ajax({
                url: '{{route('validationRule.filterRulesDataValidation')}}',
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'questionType': questionType,
                },
                success: function(data) {
                    var opts = $.parseJSON(data);
                    while (validationRules.length) {
                        validationRules.pop();
                    }
                    $.each(opts, function(i, d) {
                        validationRules.push({"id": i,"title": d});
                    });
                    updateRulesDropDown();
                }
            });
}
$('.addvalidations').on('click',function(){

    var htmlStr = `<div class="values_row">
                        <div class="form-group row" style="margin-top: 10px;">
                            <div class="col-sm-1"> Rule:</div>
                            <div class="col-sm-4 validationRuleDivCls">

                            </div>
                            <div class="form-group col-md-1" style="text-align: right;!important;">
                                <i class="btn btn-outline-danger fa fa-trash remove" style="margin-top: 3px;"></i>
                            </div>
                        </div>
                    </div>`;
    $('.appendDatavalidations').append(htmlStr);
    updateRulesDropDown();
   return false;
});
/// update sort and delete Questions

$('.updateSort').on('click',function(){
    var questionId = $('#questionId').val();
    var sort_value = $('#up_question_sort').val();
    $.ajax({
        url:'forms/changeSort/'+questionId,
        type: 'post',
        data:{
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'questionId':questionId,
            'sort_value':sort_value
        },
        dataType:'json',
        success:function(res){
            $('#question-sort-close').click();
            var step_id = $('#steps').val()
                tId;
            display_sections(step_id);
            $('.success-msg').html('');
            $('.success-msg').html('Question Sort Number Updated!')
            $('.success-alert').slideDown('slow');
            tId=setTimeout(function(){
                $(".success-alert").slideUp('slow');
            }, 4000);
        }
    })
})

/// get steps
function get_steps_phase_id(id,step_class){
    step_class.html('');
    var options = '<option value="">---Select Step / Form---</option>';
    var url_route = "{{ URL('forms/step_by_phaseId') }}"
        url_route = url_route+"/"+id;

    $.ajax({
        url:url_route,
        type:'post',
        dataType: 'json',
         data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'phase_id': id
        },
        success:function(response){
            $.each(response['data'],function(k,v){
                options += '<option value="'+v.step_id+'" >'+v.form_type+'-'+v.step_name+'</option>';
            });
            step_class.append(options);
        }
    });
}
// get sections
function get_section_step_id(id,section_class){
   section_class.html('');
   var options = '<option value="">---Form / Sections---</option>';
   $.ajax({
        url:'forms/sections_by_stepId/'+id,
        type:'post',
        dataType: 'json',
         data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'step_id': id
        },
        success:function(response){
             $.each(response['data'],function(k,v){
                options += '<option value="'+v.id+'" >'+v.name+'</option>';
            });
            section_class.append(options);
        }
    });
}
// for new route
/// get phases or visits
function get_all_phases(id,phase_class){
    phase_class.html('');
    var options = '<option value="">---Select Phase / visits---</option>';
    $.ajax({
        url:'forms/get_phases/'+id,
        type:'post',
        dataType: 'json',
         data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'id': id
        },
        success:function(response){
            $.each(response['data'],function(k,v){
                options += '<option value="'+v.id+'" >'+v.name+'</option>';
            });
            phase_class.append(options);
        }
    });
}
// get Question
function get_question_section_id(id,div_class){
    div_class.html('');
    var options = '<option value="">---Select Question---</option>';
    $.ajax({
        url:'forms/get_Questions/'+id,
        type:'post',
        dataType:'json',
        data:{
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'id': id
        },
        success:function(response){
            $.each(response['data'],function(k,v){
                options += '<option value="'+v.id+'" >'+v.question_text+'</option>';
            });
            div_class.append(options);
        }
    });
}
// get all annotations
function get_all_annotations(id,div_class){
    div_class.html('');
    var options = '<option value="">---Select Annotation---</option>';
    $.ajax({
        url:'annotation/get_allAnnotations/'+id,
        type:'post',
        dataType:'json',
        data:{
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'id': id
        },
        success:function(response){
            $.each(response['data'],function(k,v){
                options += '<option value="'+v.id+'" >'+v.label+'</option>';
            });
            div_class.append(options);
        }
    });
}

// get sections for dropdown
function section_against_step(id,section_class){
   section_class.html('');
   var options = '<option value="">---Form / Sections---</option>';
   $.ajax({
        url:'forms/sections_against_step/'+id,
        type:'post',
        dataType: 'json',
         data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'GET',
            'step_id': id
        },
        success:function(response){
             $.each(response['data'],function(k,v){
                options += '<option value="'+v.id+'" >'+v.name+'</option>';
            });
            section_class.append(options);
        }
    });
}
// for new route end
function updateRulesDropDown(){
    var selectStr = '<select name="validation_rules[]" class="form-control validationRuleDdCls">';
    for(var i = 0; i < validationRules.length; i++) {
        var opt = validationRules[i];
        selectStr += '<option value="'+opt.id+'">'+opt.title+'</option>';
    }
    selectStr += '</select>';
    $('.validationRuleDivCls').html(selectStr);
}
/**************************************************************/
</script>

@endsection
