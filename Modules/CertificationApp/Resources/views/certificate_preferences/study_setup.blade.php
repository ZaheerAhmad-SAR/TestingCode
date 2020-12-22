@extends ('layouts.home')
@section('title')
    <title> Study Setup | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('styles')
    <!-- <link rel="stylesheet" href="{{ asset('dist/vendors/tablesaw/tablesaw.css') }}"> -->
    
@stop

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Study Setup</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Certification App</li>
                        <li class="breadcrumb-item">Preferences</li>
                        <li class="breadcrumb-item">Study Setup</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    
                    <!-- <div class="card-body">  
                    </div> -->
                    <!-- card body ends -->
                    <form action="{{ route('preferences.save-study-setup', request()->study_id) }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="tab-content" id="nav-tabContent">
                                
                                <div class="form-group row">
                                    <div class="form-group col-md-12">
                                        <label for="Email">Study Email</label>
                                        <input type="email" class="form-control" name="study_email" id="study_email" required="required" @if($checkStudy != null) value="{{ $checkStudy->study_email}}" @else value="" @endif placeholder="e.g info@example.com">
                                    </div>

                                    <div class="col-md-12">
                                        <label for="Phone">CC Email</label>
                                        <input type="text" class="form-control" name="study_cc_email" id="study_cc_email" required="required" @if($checkStudy != null) value="{{ $checkStudy->study_cc_email}}" @else value="" @endif placeholder="e.g info@example.com,  johndoe@info.com">
                                    </div>

                                </div>
                                <!-- row ends -->

                                 <div class="form-group row">

                                    <div class="col-md-12">
                                        <h6 style="padding-top: 15px; padding-bottom: 15px; text-decoration: underline;">Requirement Certification:
                                        </h6>
                                    </div>
                                    @if($checkStudy != null)

                                    @php
                                        $getAllowedNo = json_decode($checkStudy->allowed_no_transmission);
                                        
                                    @endphp

                                        @foreach($getParentModalities as $key => $modility)
                                        @php
                                            $getId = $modility->id;

                                            $deviceTransmissionNumber = isset($getAllowedNo->device->$getId) ? $getAllowedNo->device->$getId : 0;

                                            $photographerTransmissionNumber = isset($getAllowedNo->photographer->$getId) ? $getAllowedNo->photographer->$getId : 0;
                                        @endphp
                                        <div class="col-md-4" style="margin-bottom: 10px;">
                                            @if($key == 0)
                                            <label for="Phone">Modalities</label>
                                            @endif
                                            <input type="text" class="form-control" name="modility_name[]" id="modility_name" value="{{ $modility->modility_name }}" disabled>
                                        </div>
                                      
                                        <div class="col-md-4">
                                            @if($key == 0)
                                            <label for="Phone">Devices Transmission No.</label>
                                            @endif
                                            <input type="number" class="form-control" name="allowed_no_transmission[device][{{$modility->id}}]" id="allowed_no_transmission" value="{{ $deviceTransmissionNumber }}" min="0" required>
                                        </div>

                                        <div class="col-md-4">
                                            @if($key == 0)
                                            <label for="Phone">Photographer Transmission No.</label>
                                            @endif
                                            <input type="number" class="form-control" name="allowed_no_transmission[photographer][{{$modility->id}}]" id="allowed_no_transmission" value="{{ $photographerTransmissionNumber }}" min="0" required>
                                        </div>
                                        @endforeach

                                    @else

                                        @foreach($getParentModalities as $key => $modility)
                                        <div class="col-md-4" style="margin-bottom: 10px;">
                                            @if($key == 0)
                                            <label for="Phone">Modalities</label>
                                            @endif
                                            <input type="text" class="form-control" name="modility_name[]" id="modility_name" value="{{ $modility->modility_name }}" disabled>
                                        </div>

                                        <div class="col-md-4">
                                            @if($key == 0)
                                            <label for="Phone">Devices Transmission No.</label>
                                            @endif
                                            <input type="number" class="form-control" name="allowed_no_transmission[device][{{$modility->id}}]" id="allowed_no_transmission" value="1" min="0" required>
                                        </div>

                                        <div class="col-md-4">
                                            @if($key == 0)
                                            <label for="Phone">Photographer Transmission No.</label>
                                            @endif
                                            <input type="number" class="form-control" name="allowed_no_transmission[photographer][{{$modility->id}}]" id="allowed_no_transmission" value="2" min="0" required>
                                        </div>
                                        @endforeach

                                    @endif
                                 </div>
                                <!-- row ends -->
                            </div>
                            <!-- tab content -->
                        </div>
                        <!-- modal body ends -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-primary" id="btn-save" value="create"><i class="fa fa-save"></i> Save Changes</button>
                        </div>
                    </form>
                    <!-- form ends -->
                </div>
                <!-- card ends -->
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
    <!-- container ends -->
@endsection

@section('script')

@endsection
