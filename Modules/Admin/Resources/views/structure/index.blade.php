@extends ('layouts.home')
@section('content')
<div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12 align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">Visits & Modalities Sections</h4></div>

                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Study Structure</li>
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
        <div class="eagle-divider"></div>
        <div class="col-lg-4 col-xl-4">
            Phases <button class="custom-btn blue-color" id="add_phase"> <i class="fa fa-plus blue-color"></i> add</button>
        </div>
        <div class="col-lg-8  col-xl-8">
            Steps <button class="custom-btn blue-color" id="add_steps"><i class="fa fa-plus blue-color"></i> add</button>
        </div>
        <div class="col-lg-4 col-xl-4 mb-4 mt-3 pr-lg-0 flip-menu">
            <a href="#" class="d-inline-block d-lg-none mt-1 flip-menu-close"><i class="icon-close"></i></a>
            <div class="card border h-100 mail-menu-section ">
                <ul class="list-unstyled inbox-nav  mb-0 mt-2 mail-menu" id="phases-group">
                    @foreach($phases as $key => $phase)
                    <li class="nav-item mail-item" style="border-bottom: 1px solid #F6F6F7;">
                        <div class="d-flex align-self-center align-middle">
                            <div class="mail-content d-md-flex w-100">
                                <a href="#" data-mailtype="tab_{{$phase->id}}" class="nav-link @if ($key === 0) active @endif">
                                    <span class="mail-user"> {{$phase->position}}. {{$phase->name}}</span> 
                                </a> 
                                <input type="hidden" class="phase_id" value="{{$phase->id}}">
                                <input type="hidden" class="phase_study_id" value="{{$phase->study_id}}">
                                <input type="hidden" class="phase_name" value="{{$phase->name}}">
                                <input type="hidden" class="phase_position" value="{{$phase->position}}">
                                <input type="hidden" class="phase_duration" value="{{$phase->duration}}">    
                                <div class="d-flex mt-3 mt-md-0 ml-auto">
                                    <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                        <span class="dropdown-item edit_phase"><i class="far fa-edit"></i>&nbsp; Edit</span>
                                        <span class="dropdown-item"><i class="far fa-clone"></i>&nbsp; Clone</span>
                                        <span class="dropdown-item deletePhase"><i class="far fa-trash-alt"></i>&nbsp; Delete</span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-lg-8 col-xl-8 mb-4 mt-3 pl-lg-0">
            <div class="card border h-100 mail-list-section">
                <div class="card-body p-0">
                    <div class="scrollertodo">
                        <ul class="mail-app list-unstyled " id="steps-group">
                            @foreach($phases as $key => $step)
                            @foreach($step->phases as $step_value)
                            <li class="py-3 px-2 mail-item tab_{{$step_value->phase_id}}" style="@if ($key === 0) display: block;  @endif">
                                <input type="hidden" class="step_id" value="{{$step_value->step_id}}">
                                <input type="hidden" class="step_phase_id" value="{{$step_value->phase_id}}">
                                <input type="hidden" class="form_type" value="{{$step_value->form_type}}">
                                <input type="hidden" class="step_name" value="{{$step_value->step_name}}">
                                <input type="hidden" class="step_position" value="{{$step_value->step_position}}">
                                <input type="hidden" class="step_description" value="{{$step_value->step_description}}">
                                <input type="hidden" class="graders_number" value="{{$step_value->graders_number}}">
                                <input type="hidden" class="q_c" value="{{$step_value->q_c}}">
                                <input type="hidden" class="eligibility" value="{{$step_value->eligibility}}">
                                <div class="d-flex align-self-center align-middle">
                                    <div class="mail-content d-md-flex w-100">
                                        <span class="mail-user">{{$step_value->form_type}} - {{$step_value->step_name}}</span> 
                                        <p class="mail-subject">{{$step_value->step_description}}.</p> 
                                        <div class="d-flex mt-3 mt-md-0 ml-auto">
                                            <div class="ml-md-auto mr-3 dot primary"></div>
                                            <p class="ml-auto mail-date mb-0">{{$step_value->created_at}}</p>
                                            <a href="#" class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i></a>
                                            <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                <span class="dropdown-item edit_steps"><i class="far fa-edit"></i>&nbsp; Edit</span>
                                                <span class="dropdown-item addsection"><i class="far fa-file-code"></i>&nbsp; Add Section</span>
                                                <span class="dropdown-item"><i class="far fa-clone"></i>&nbsp; Clone</span>
                                                <span class="dropdown-item deleteStep"><i class="far fa-trash-alt"></i>&nbsp; Delete</span> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Card DATA-->
</div>
<!-- phase modle -->
<div class="modal fade" tabindex="-1" role="dialog" id="addphase" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title">Add a phase</p>
            </div>
            <!-- action="{{route('study.store')}}" -->
            <form enctype="multipart/form-data" method="POST" id="add_edit_phase">
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                                @csrf
                            <input type="hidden" id="phase_id" name="id">
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Position</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="position" id="phase_position" value="" placeholder="Enter Sort Number">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" id="phase_name" value="" placeholder="Phase Title">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Duration in months</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="duration" id="phase_duration" value=""  placeholder="Duration in months">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="addphase-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="button" class="btn btn-outline-primary" id="savePhase"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- add steps agains phases -->
<!-- phase modle action="{{route('steps.save')}}" -->

<div class="modal fade" tabindex="-1" role="dialog" id="addsteps" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Add a step</p>
                </div>
                <form  enctype="multipart/form-data" method="POST" id="add_edit_steps">
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                                @csrf
                            <input type="hidden" id="step_id" name="step_id">
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Step Position</label>
                                <div class="col-md-9">
                                    <select name="phase_id" id="step_phase_id" class="form-control">
                                        @foreach($phases as $key => $phase)
                                        <option value="{{$phase->id}}">{{$phase->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Form Type</label>
                                <div class="col-md-9">
                                    <select name="form_type" id="form_type" class="form-control" required>
                                       <option value="">---Select Form Type---</option>
                                       <option value="qc">QC</option>
                                       <option value="grading">Grading</option>
                                       <option value="eligibility">Eligibility</option>
                                       <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Step Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="step_name" id="step_name" placeholder="Step Name">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Step Description</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="step_description" id="step_description"  placeholder="Description">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Number of Graders</label>
                                <div class="col-md-9">
                                    <select name="graders_number" id="graders_number" class="form-control">
                                        <option value="">---Select Numbers of Graders--</option>
                                        <option value="0">Null (0)</option>
                                        <option value="1">One (1)</option>
                                        <option value="2">Two (2)</option>
                                        <option value="3">Three (3)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3">QC</label>
                                <div class="col-md-3">
                                    <input type="radio" name="q_c" id="q_c_yes" value="yes" checked="checked"> Yes
                                    <input type="radio" name="q_c" id="q_c_no" value="no"> No
                                </div>
                                <label for="Name" class="col-sm-3">Eligibility</label>
                                <div class="col-md-3">
                                    <input type="radio" name="eligibility" id="eligibility_yes" value="yes" checked="checked"> Yes
                                    <input type="radio" name="eligibility" id="eligibility_no" value="no"> No
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="addstep-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="button" class="btn btn-outline-primary" id="saveSteps"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
</div>
<!--  -->
<!-- Add Sections {{route('sections.store')}} -->
<div class="modal fade" tabindex="-1" role="dialog" id="addsection" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title"></p>
                </div>
                <div class="col-lg-12 success-alert-sec" style="display: none; margin-top: 10px;">
                    <div class="success-msg-sec alert-primary success-msg text-center" role="alert" style="font-weight: bold;">
                    </div>
                </div>
                <form  enctype="multipart/form-data" method="POST" id="sec-form">
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                                @csrf
                            <input type="hidden" id="section_id" name="section_id">
                            <input type="hidden" id="step_id_for_section" name="step_id">
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="sec_name" id="sec_name" placeholder="Section Name">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Description</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="sec_description" id="sec_description"  placeholder="Section Title Description">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Sort # / Position</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="sort_num" id="sort_num" placeholder="Sort Number">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-warning reset_to_add_sec"><i class="fas fa-undo-alt" aria-hidden="true"></i> Reset</button>
                        <button class="btn btn-outline-danger" id="addsection-close" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="button" class="btn btn-outline-primary" id="Save_section"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                    <div class="col-lg-12">
                        <div class="table-wrapper-scroll-y my-custom-scrollbar">
                            <table class="table table-bordered table-striped mb-0">
                                <thead>
                                  <tr>
                                    <th>Sort #</th>
                                    <th>Section Name</th>
                                    <th>Description</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody id="sectionTable">
                                  
                                </tbody>
                            </table>
                        </div>    
                    </div>
                </div>
            </form>
            </div>
        </div>
</div>
<!--  -->

@stop
@section('styles')  
<style>
    .d-flex > span > i {
    cursor: pointer;

    }
    .mail-menu li a {
        font-weight: 600 !important;
    }
    .dropdown-menu span{
        cursor: pointer;
    }
</style>
<link rel="stylesheet" href="{{ asset('public/dist/vendors/quill/quill.snow.css') }}" />
@stop
@section('script')  
<script src="{{ asset('public/dist/vendors/quill/quill.min.js') }}"></script> 
<script src="{{ asset('public/dist/js/mail.script.js') }}"></script>  
<script>
        $(document).ready(function(){
            // load add model for Phases addsteps
            $('#add_phase').on('click',function(){
                $('.modal-title').html('Add a Phase');
                $('#add_edit_phase').trigger('reset');
                $('#phase_id').val('');
                $('#addphase').modal('show');
            })
            // Save Phase
            $('#savePhase').on('click',function(){
                var APP_URL = {!! json_encode(url('/')) !!}
                var id = $('input#phase_id').val();
                var name = $('input#phase_name').val();
                var position = $('input#phase_position').val();
                var duration = $('input#phase_duration').val();
              
                if(name =='' || position =='' || duration ==''){
                    alert('Please fill all the required fields');
                }else{
                    $.ajax({
                        url: (id == '') ? 'study' : 'study/update',   
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'id': id,
                            'name': name,
                            'position': position,
                            'duration': duration
                            },
                        success: function(response){
                            $("#addphase-close").click();
                            $.ajax({
                                url:'study_phases',
                                dataType: 'json',
                                success:function(res){
                                   var len = 0;
                                   $('#phases-group').empty(); // Empty list
                                   if(res['data'] != null){
                                      len = res['data'].length;
                                   }
                                   if(len > 0){
                                      for(var i=0; i<len; i++){
                                         var tId;
                                         var id = res['data'][i].id;
                                         var name = res['data'][i].name;
                                         var position = res['data'][i].position;
                                         var study_id = res['data'][i].study_id;
                                         var duration = res['data'][i].duration;
                                         var created_at = res['data'][i].created_at;
                                         if(i ==0){
                                            var active = 'active';
                                            }else{
                                                var active = '';
                                          }
                                         var tr_str = "<li class='nav-item mail-item' style='border-bottom: 1px solid #F6F6F7;'><div class='d-flex align-self-center align-middle'>"
                                            +"<div class='mail-content d-md-flex w-100'><a data-mailtype='tab_"+id+"' class='"+active+"'><span class='mail-user'> "+position+" . "+name+" </span></a>"
                                            +"<input type='hidden' class='phase_id' value='"+id+"'><input type='hidden' class='phase_name' value='"+name+"'><input type='hidden' class='phase_position' value='"+position+"'><input type='hidden' class='phase_duration' value='"+duration+"'>"
                                            +"<div class='d-flex mt-3 mt-md-0 ml-auto'><span class='ml-3' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='cursor: pointer;'><i class='fas fa-cog' style='margin-top: 12px;'></i></span><div class='dropdown-menu p-0 m-0 dropdown-menu-right'><span class='dropdown-item edit_phase'><i class='far fa-edit'></i>&nbsp; Edit</span><span class='dropdown-item'><i class='far fa-clone'></i>&nbsp; Clone</span><span class='dropdown-item deletePhase'><i class='far fa-trash-alt'></i>&nbsp; Delete</span></div></div></div></div>"
                                            +"</li>";
                                         $("#phases-group").append(tr_str);
                                         $('#add_edit_phase').trigger('reset');
                                         $('.success-msg').html('');
                                         $('.success-msg').html('Operation Done!')
                                         $('.success-alert').slideDown('slow');
                                         tId=setTimeout(function(){
                                            $(".success-alert").slideUp('slow');        
                                         }, 3000);
                                      }
                                   }
                                }
                            })
                        }  
                    });
                }
            })

            $('body').on('click','.edit_phase',function(){
                $('.modal-title').html('Edit a Phase');
                $('#addphase').trigger('reset');
                $('#addphase').modal('show');
                var row = $(this).closest('li.nav-item');
                var id = row.find('input.phase_id').val();
                var study_id = row.find('input.phase_study_id').val();
                var name = row.find('input.phase_name').val();
                var position = row.find('input.phase_position').val();
                var duration = row.find('input.phase_duration').val();
                $('#phase_id').val(id);
                $('#phase_position').val(position);
                $('#phase_name').val(name);
                $('#phase_duration').val(duration);
            })
            // delete Phase
            $('body').on('click','.deletePhase',function(){
                var row = $(this).closest('li.nav-item');
                var id = row.find('input.phase_id').val();
                var tId;
                if (confirm("Are you sure to delete?")) {
                    $.ajax({
                        url: 'study/'+id,   
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": 'DELETE',
                            'id': id
                            },
                        success:function(res){
                            row.remove();
                            $('.success-msg').html('Operation Done!')
                            $('.success-alert').slideDown('slow');
                            tId=setTimeout(function(){
                                $(".success-alert").slideUp('slow');        
                            }, 3000);
                        }    
                    })
                }    
            })
            // load add model for steps
            $('#add_steps').on('click',function(){
                $('.modal-title').html('Add a steps');
                $('#add_edit_steps').trigger('reset');
                $('#step_id').val('');
                $('#addsteps').modal('show');
            })
            // Save Steps
            $('#saveSteps').on('click',function(){
                var APP_URL = {!! json_encode(url('/')) !!}
                var step_id = $('input#step_id').val();
                var phase_id = $('select#step_phase_id').val();
                var form_type = $('select#form_type').val();
                var step_name = $('input#step_name').val();
                var step_description = $('input#step_description').val();
                var graders_number = $('select#graders_number').val();
                var q_c = $("input[name='q_c']:checked").val();
                var eligibility = $("input[name='eligibility']:checked").val();
                if(phase_id =='' || step_name =='' || step_description ==''){
                    alert('Please fill all the required fields');
                }else{
                    $.ajax({
                        url: (step_id == '') ? 'steps/store_steps' : 'steps/updateSteps',   
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'step_id': step_id,
                            'phase_id': phase_id,
                            'form_type': form_type,
                            'step_name': step_name,
                            'step_description': step_description,
                            'graders_number': graders_number,
                            'q_c': q_c,
                            'eligibility': eligibility
                            },
                        success: function(response){
                            $("#addstep-close").click();
                            $.ajax({
                                url:'study_phases',
                                dataType: 'json',
                                success:function(res){
                                   var len = 0;
                                   $('#steps-group').empty(); // Empty list
                                   if(res['data'] != null){
                                      len = res['data'].length;
                                   }
                                   
                                   if(len > 0){
                                      for(var i=0; i<len; i++){
                                        var tId;
                                        var tr_str = '';
                                        var id = res['data'][i].id;
                                        if(i ==0){
                                            var active = 'display:block';
                                        }else{
                                            var active = '';
                                        }
                                        tr_str += '';
                                        if(res['data'][i].phases.length != 0){
                                            $.each(res['data'][i].phases,function(k,v){
                                            tr_str += "<li class='py-3 px-2 mail-item tab_"+id+"' style='"+active+"'>"
                                                +'<input type="hidden" class="step_id" value="'+v.step_id+'"><input type="hidden" class="step_phase_id" value="'+v.phase_id+'"><input type="hidden" class="step_name" value="'+v.step_name+'"><input type="hidden" class="form_type" value="'+v.form_type+'"><input type="hidden" class="step_position" value="'+v.step_name+'"><input type="hidden" class="step_description" value="'+v.step_description+'"><input type="hidden" class="graders_number" value="'+v.graders_number+'"><input type="hidden" class="q_c" value="'+v.q_c+'"><input type="hidden" class="eligibility" value="'+v.eligibility+'">'
                                                +"<div class='d-flex align-self-center align-middle'><div class='mail-content d-md-flex w-100'><span class='mail-user'>"+v.form_type+" - "+v.step_name+"</span><p class='mail-subject'>"+v.step_description+".</p><div class='d-flex mt-3 mt-md-0 ml-auto'><div class='ml-md-auto mr-3 dot primary'></div><p class='ml-auto mail-date mb-0'>"+v.created_at+"</p><a class='ml-3' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fas fa-cog'></i></a><div class='dropdown-menu p-0 m-0 dropdown-menu-right'><span class='dropdown-item edit_steps'><i class='far fa-edit'></i>&nbsp; Edit</span><span class='dropdown-item addsection'><i class='far fa-file-code'></i>&nbsp; Add Section</span><span class='dropdown-item'><i class='far fa-clone'></i>&nbsp; Clone</span><span class='dropdown-item deleteStep'><i class='far fa-trash-alt'></i>&nbsp; Delete</span></div></div></div></div>";
                                                tr_str  += "</li>";
                                            });
                                        }else{

                                        }    
                                        tr_str  += "";
                                        $("#steps-group").append(tr_str);
                                      }
                                     //$("#steps-group").append(tr_str);
                                     $('#add_edit_steps').trigger('reset');
                                     $('.success-msg').html('');
                                     $('.success-msg').html('Operation Done!')
                                     $('.success-alert').slideDown('slow');
                                     tId=setTimeout(function(){
                                        $(".success-alert").slideUp('slow');        
                                     }, 3000);
                                   }
                                }
                            })
                        }  
                    });
                }
            })
            // load steps modal for edit
            $('body').on('click','.edit_steps',function(){

                $('.modal-title').html('Edit a Step');
                $('#addsteps').trigger('reset');
                $('#addsteps').modal('show');
                var row = $(this).closest('li.mail-item');
                var id = row.find('input.step_id').val();
                var phase_id = row.find('input.step_phase_id').val();
                var form_type = row.find('input.form_type').val();
                var name = row.find('input.step_name').val();
                var position = row.find('input.step_position').val();
                var description = row.find('input.step_description').val();
                var graders_number = row.find('input.graders_number').val();
                var q_c = row.find('input.q_c').val();
                var eligibility = row.find('input.eligibility').val();
                $('#step_id').val(id);
                $('#step_phase_id').val(phase_id);
                $('#form_type').val(form_type);
                $('#step_name').val(name);
                $('#step_position').val(position);
                $('#step_description').val(description);
                $('#graders_number').val(graders_number);
                if(q_c =='yes'){ $('#q_c_yes').prop( "checked", true );}else{ $('#q_c_no').prop( "checked", true ); }
                if(eligibility =='yes'){ $('#eligibility_yes').prop( "checked", true );}else{ $('#eligibility_no').prop( "checked", true ); }
            })
            // load modal to add section
            // delete Step deleteStep
            $('body').on('click','.deleteStep',function(){
                var row = $(this).closest('li.list-group-item');
                var step_id = row.find('input.step_id').val();
                var tId;
                if (confirm("Are you sure to delete?")) {
                    $.ajax({
                        url: 'steps/delete_steps/'+step_id, 
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": 'DELETE',
                            'step_id': step_id
                            },
                        success:function(res){
                            row.remove();
                            $('.success-msg').html('Operation Done!')
                            $('.success-alert').slideDown('slow');
                            tId=setTimeout(function(){
                                $(".success-alert").slideUp('slow');        
                            }, 3000);
                        }    
                    })
                }    
            })
            // Save sections against Steps Save_section {{route('sections.store')}}
            $('#Save_section').on('click',function(){
                var APP_URL = {!! json_encode(url('/')) !!} 
                var tId;
                var section_id = $('input#section_id').val();
                var step_id = $('input#step_id_for_section').val();
                var sec_name = $('input#sec_name').val();
                var sec_description = $('input#sec_description').val();
                var sort_num = $('input#sort_num').val();
                if(sec_name =='' || sec_description =='' || sort_num ==''){
                    alert('Please fill all the required fields');
                }else{
                    $.ajax({
                        url: (section_id == '') ? 'sections' : 'section/update', 
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'section_id': section_id,
                            'step_id': step_id,
                            'sec_name': sec_name,
                            'sec_description': sec_description,
                            'sort_num': sort_num
                            },
                        success: function(response){
                            Sections(step_id);
                            $('.modal-title').html('Add a section');
                            $('#section_id').val('');
                            $('#sec-form').trigger('reset');
                            $('.success-msg-sec').html('');
                            $('.success-msg-sec').html('Operation Done!')
                            $('.success-alert-sec').slideDown('slow');
                            tId=setTimeout(function(){
                              $(".success-alert-sec").slideUp('slow');        
                            }, 3000);
                        }  
                    });
                }
            })
            //end
            $('body').on('click','.addsection',function(){
                $('#addsection').trigger('reset');
                $('#sec_name,#sec_description,#sort_num').val('');
                $('.modal-title').html('Add a section');
                $('#addsection').modal('show');
                var row = $(this).closest('li.mail-item');
                var id = row.find('input.step_id').val();
                $('#step_id_for_section').val(id);
                Sections(id);
            })
            $('body').on('click','.edit_sec',function(){
                $('#addsection').trigger('reset');
                $('.modal-title').html('Edit a section');
                var APP_URL = {!! json_encode(url('/')) !!}
                var row = $(this).closest('tr');
                var id = row.find('td.sec_id').text();
                var sec_name = row.find('td.sec_name').text();
                var sec_desc = row.find('td.sec_desc').text();
                var sort_numb = row.find('td.sort_numb').text();
                $('#section_id').val(id);
                $('#sec_name').val(sec_name);
                $('#sec_description').val(sec_desc);
                $('#sort_num').val(sort_numb);
                $('#sec-form').prop('action', APP_URL+'/section/update');
            });
            $('.reset_to_add_sec').on('click',function(){
                $('.modal-title').html('Add a section');
                $('#sec_name,#sec_description,#sort_num,#section_id').val('');
                var APP_URL = {!! json_encode(url('/')) !!}
                $('#sec-form').prop('action', APP_URL+'/sections');
                return false;
            })
            $('body').on('click','.delete_sec', function(){
                var row = $(this).closest('tr');
                var id = row.find('td.sec_id').text();
                var tId;
                if (confirm("Are you sure to delete?")) {
                   $.ajax({
                        url: 'sections/'+id,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'id': id
                        },
                    success: function(res){
                        row.remove();
                        $('.success-msg-sec').html('Operation Done!')
                        $('.success-alert-sec').slideDown('slow');
                        tId=setTimeout(function(){
                          $(".success-alert-sec").slideUp('slow');        
                        }, 3000);
                    }    
                   })
                }   
            });

        })
    function Sections(id){
        $.ajax({
             url: 'section',
             type: 'POST',
             dataType: 'json',
             data: {
                "_token": "{{ csrf_token() }}",
                'id': id
            }, 
             success: function(response){
               var len = 0;
               $('#sectionTable').empty(); // Empty <tbody>
               if(response['data'] != null){
                  len = response['data'].length;
               }
               if(len > 0){
                  for(var i=0; i<len; i++){
                     var id = response['data'][i].id;
                     var name = response['data'][i].name;
                     var description = response['data'][i].description;
                     var sort_number = response['data'][i].sort_number;
                     var created_at = response['data'][i].created_at;
                     var tr_str = "<tr>" +
                       "<td class='sec_id' style='display:none;'>" + id + "</td>" +
                       "<td class='sort_numb'>" + sort_number + "</td>" +
                       "<td class='sec_name'>" + name + "</td>" +
                       "<td class='sec_desc'>" + description + "</td>" +
                       "<td>" + created_at + "</td>" +
                       "<td><span><i class='far fa-edit edit_sec' style='color: #34A853;'></i></span>&nbsp;&nbsp;<span><i class='far fa-trash-alt delete_sec' style='color: #EA4335;'></i></span></td>" +
                     "</tr>";

                     $("#sectionTable").append(tr_str);
                  }
               }else{
                  var tr_str = "<tr>" +
                      "<td align='center' colspan='5'>No record found.</td>" +
                  "</tr>";

                  $("#sectionTable").append(tr_str);
               }
            }
        });
    }
    </script>      
@stop