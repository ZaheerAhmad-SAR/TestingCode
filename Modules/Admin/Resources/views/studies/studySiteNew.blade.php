@extends('layouts.home')

@section('title')
    <title> Study Sites | {{ config('app.name', 'Laravel') }}</title>
@stop

@section('content')

    <style type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
        .required:after {
            content:" *";
            color: red;
        }

        .pac-container {
            z-index: 10000 !important;
        }
        .select2-results__option[aria-selected=true] {
            display: none;
        }
        span.select2-selection__clear {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2-bootstrap.min.css") }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.css" integrity="sha512-2sFkW9HTkUJVIu0jTS8AUEsTk8gFAFrPmtAxyzIhbeXHRH8NXhBFnLAMLQpuhHF/dL5+sYoNHWYYX2Hlk+BVHQ==" crossorigin="anonymous" />
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Study Sites</h4></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active"><a href="#">Study Sites</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header  justify-content-between align-items-center">
                        @if(hasPermission(auth()->user(),'studySite.update'))
                            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#assignSites">
                                <i class="fa fa-plus"></i> Assign Sites
                            </button>
                        @endif
                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#siteModal">
                            <i class="fa fa-plus"></i> Add Site
                        </button>
                            </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display table dataTable table-striped table-bordered editable-table" >
                                <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Principal Investigator</th>
                                    <th>Coordinator</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Country</th>
                                    <th>Study Site ID</th>
                                </tr>
                                </thead>
                                <tbody>
                                <input type="hidden" value="" id="global_site_id">

                                @if(!empty($sites))
                                    @foreach($sites as $site)
                                        <tr>
                                            <td>{{ucfirst($site->site_code)}}</td>
                                            <td>{{ucfirst($site->site_name)}}</td>
                                            <td>
                                            <Select class="form-control" value="{{old('primaryInvestigator')}}" id="primaryInvestigator_{{$site->id}}" name="primaryInvestigator_{{$site->id}}" onchange="updatePI('{{$site->id}}');">
                                                    <option>--Select PI--</option>
                                                    @foreach($site->pi as $key => $pi)
                                                    @php $pi_records = explode('/',$pi); @endphp
                                                        <option value="{{$pi_records[0]}}" {{$pi_records[0]==$site->primaryInvestigator_id ? 'selected="selected"': ''}}>{{$pi_records[1]}}</option>
                                                    @endforeach
                                                </Select>
                                                <input type="hidden" id="table_site_study_id_{{$site->id}}" name="table_site_study_id_{{$site->id}}" value="{{$site->id}}">
                                            </td>
                                            <td>
                                            <Select class="coordinatorsData multieSelectDropDown" data-id="{{$site->id}}" name="coordinators_{{$site->id}}" id="coordinators_{{$site->id}}" multiple data-allow-clear="1" onchange="assignCoordinator('{{$site->id}}');">
                                            @php
                                            $studySite = Modules\Admin\Entities\StudySite::find($site->id);
                                            $siteCoordinatorsIdsArray = $studySite->siteStudyCoordinatorIds();
                                            $coordinators = Modules\Admin\Entities\Coordinator::where('site_id',$site->site_id)->get();
                                            @endphp
                                                    @foreach($coordinators as $coordinator)
                                                        <option value="{{$coordinator->id}}" {{in_array($coordinator->id,$siteCoordinatorsIdsArray) ? 'selected': ''}} >{{$coordinator->first_name}}</option>

                                                    @endforeach
                                                </Select>
                                            </td>

                                            <td>{{ucfirst($site->site_city)}}</td>
                                            <td>{{ucfirst($site->site_state)}}</td>
                                            <td>{{ucfirst($site->site_country)}}</td>
                                            <td class="site_id" style="display: none"> {{$site->id}} </td>
                                            <td class="studySitetableData addRowItem" data-id="{{$site->id}}" data-value="{{$site->study_site_id}}" name="studySiteId" id="studySiteId">{{$site->study_site_id}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Card DATA-->
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="assignSites">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="modal-header">
                        <p class="modal-title">Assign New Sites</p>
                    </div>
                    <div class="col-lg-12 success-alert-sec" style="display: none; margin-top: 10px;">
                        <div class="success-msg-sec alert-primary success-msg text-center" role="alert" style="font-weight: bold;">
                        </div>
                    </div>
                    <form id="studySiteForm" name="studySiteForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="{!! ($errors->has('sites')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <select class="searchable" id="select-sites" multiple="multiple" name="sites[]">
                                        @foreach($sites as $site)
                                            <option  selected="selected" value="{{$site->site_id}}">{{ $site->site_code .'  '.$site->site_name}}</option>
                                        @endforeach

                                        @if(!empty($unassignSites))
                                            @foreach($unassignSites as $unassignSite)
                                                <option name="to_be_selected"   class="assignSites"  value="{{$unassignSite->id}}">{{$unassignSite->site_code.'  '.$unassignSite->site_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('sites')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close redirectPage" aria-hidden="true"></i> Close</button>
                                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="siteModal">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="modal-header">
                        <p class="modal-title">Add New Site</p>
                        <input type="hidden" name="site_id" id="site_id" value="">
                    </div>
                    <div class="modal-body">

                        <!-- Nav tabs -->
                        <nav>
                            <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" href="#siteInfo" aria-controls="siteInfo" role="tab" data-toggle="tab">Site Info</a>
                                <a href="#primaryInvestigator" class="nav-item nav-link addTabs" aria-controls="primaryInvestigator" role="tab" >Primary Investigator</a>
                                <a href="#coordinator" class="nav-item nav-link addTabs" aria-controls="coordinator" role="tab" >Coordinator</a>
                                <a href="#photographer" class="nav-item nav-link addTabs" aria-controls="photographer" role="tab">Photographer</a>
                                <a href="#others" class="nav-item nav-link addTabs" aria-controls="others" role="tab">Others</a>
                            </div>
                        </nav>
                        <!-- Tab panes -->
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="siteInfo" role="tabpanel" aria-labelledby="nav-Basic-tab">
                                <div class="col-lg-12 success-alert-sec" style="display: none; margin-top: 10px;">
                                    <div class="success-msg-sec alert-primary success-msg text-center" role="alert" style="font-weight: bold;">
                                    </div>
                                </div>
                                <form name="siteInfoForm" id="siteInfoForm">
                                    <input type="hidden" name="lastSiteId" id="lastSiteId" value="">
                                    @csrf
                                    <div class="col-lg-12">
                                        <div class="panel-body">
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-md-6">
                                                    <div class="{!! ($errors->has('site_code')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                        <label class="required">Site Code</label>
                                                        <input type="input" class="form-control"
                                                               name="site_code" id="site_code"
                                                               value="{{old('site_code')}}" required/>
                                                        <p id="site_code_uniqe"></p>
                                                        @error('site_code')
                                                        <span class="input-danger small">
                                                        {{ $message }}
                                                                </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="{!! ($errors->has('site_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                        <label class="required">Site Name</label>
                                                        <input type="input" class="form-control"
                                                               name="site_name" id="site_name"
                                                               value="{{old('site_name')}}" required/>
                                                        @error('site_name')
                                                        <span class="input-danger small">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="{!! ($errors->has('autocomplete')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                        <label>Find address</label>
                                                        <input id="autocomplete" autocomplete="disabled"  class="form-control"
                                                               placeholder="Find Address"
                                                               onFocus="geolocate()"
                                                               type="text"/>
                                                    </div>
                                                    @error('autocomplete')
                                                    <span class="input-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="{!! ($errors->has('fullAddr')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                        <label class="required">Street Address</label>
                                                        <input id="fullAddr" name="fullAddr" autocomplete="disabled" class="form-control" type="text"/>
                                                    </div>
                                                    @error('autocomplete')
                                                    <span class="input-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="{!! ($errors->has('locality')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                        <label class="required">City</label>
                                                        <input type="text" class="form-control" id="locality" name="locality" value="{{old('locality')}}"/>
                                                    </div>
                                                    @error('locality')
                                                    <span class="input-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <input type="hidden" id="street_number">
                                                <input type="hidden" id="route">
                                                <div class="col-md-6">
                                                    <div class="{!! ($errors->has('administrative_area_level_1')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                        <label class="required">State</label>
                                                        <input type="text" class="form-control" id="administrative_area_level_1" name="administrative_area_level_1" value="{{old('administrative_area_level_1')}}" required/>
                                                    </div>
                                                    @error('administrative_area_level_1')
                                                    <span class="input-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="{!! ($errors->has('postal_code')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                        <label>Zip Code</label>
                                                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{old('postal_code')}}"/>
                                                    </div>
                                                    @error('postal_code')
                                                    <span class="input-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="{!! ($errors->has('country')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                        <label class="required">Country</label>
                                                        <input type="text" class="form-control" id="country"  name="country" value="{{old('country')}}" required />
                                                    </div>
                                                    @error('country')
                                                    <span class="input-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="{!! ($errors->has('site_phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                        <label class="required">Phone</label>
                                                        <input type="text" class="form-control" id="site_phone" name="site_phone" value="{{old('site_phone')}}" required/>
                                                    </div>
                                                    @error('site_phone')
                                                    <span class="input-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                @if(hasPermission(auth()->user(),'sites.store'))
                                                    <button type="submit" id="btn_site_info" class="btn btn-outline-primary"><i class="fa fa-save changeText"></i> Save</button>
                                                    <input type="hidden" name="sites_submit_actions" id="sites_submit_actions" value="Add">
                                                @endif
                                                <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close redirectPage" aria-hidden="true"></i> Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="primaryInvestigator">
                                <form name="primaryInvestigatorForm" id="primaryInvestigatorForm">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="site_id" value="">
                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('first_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label class="required">First Name</label>
                                                <input type="input" class="form-control" id="pi_first_name"
                                                       name="pi_first_name" value="{{old('pi_first_name')}}" required/>
                                                @error('pi_first_name')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div
                                                class="{!! ($errors->has('mid_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label>Middle Name</label>
                                                <input type="input" class="form-control" id="pi_mid_name"
                                                       name="pi_mid_name" value="{{old('pi_mid_name')}}"/>
                                                @error('mid_name')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('last_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label class="required">Last Name</label>
                                                <input type="input" class="form-control" id="pi_last_name"
                                                       name="pi_last_name" value="{{old('pi_last_name')}}" required/>
                                                @error('last_name')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div
                                                class="{!! ($errors->has('phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label>Phone</label>
                                                <input type="input" class="form-control" id="pi_phone"
                                                       name="pi_phone" value="{{old('pi_phone')}}"/>
                                                @error('phone')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('email')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label class="required">Email</label>
                                                <input type="email" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" class="form-control" id="pi_email"
                                                       name="pi_email" value="{{old('pi_email')}}" required/>
                                                @error('email')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <input type="hidden" id="primaryInvestigatorJsonData" value=''>

                                        <div class="col-md-12">
                                            <div class="pull-right" style="text-align: right;">
                                                <button type="submit" id="pi_button" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save</button>
                                                <input type="hidden" name="pi_submit_actions" id="pi_submit_actions" value="Add">
                                                <input type="hidden" name="pi_id" id="pi_id" value="">
                                                <input type="hidden" name="pi_site_id" id="pi_site_id" value="">
                                                <button type="button"  id="rest_pi_button" class="btn btn-outline-warning"><i class="fas fa-undo-alt" aria-hidden="true"></i> Reset</button>
                                                <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                                            </div>
                                            <br>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="table-1" class="table table-bordered primaryInvestigatorTableAppend">
                                                <thead>
                                                <tr>
                                                    <th>Full Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="coordinator">
                                <form  name="coordinatorForm" id="coordinatorForm">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="site_id" value="">
                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('c_first_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label class="required">First Name</label>
                                                <input type="input" class="form-control" id="c_first_name"
                                                       name="c_first_name" value="{{old('c_first_name')}}" required/>
                                                @error('c_first_name')
                                                <span class="input-danger small">
                                                            {{ $message }}
                                                            </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div
                                                class="{!! ($errors->has('c_mid_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label>Middle Name</label>
                                                <input type="input" class="form-control" id="c_mid_name"
                                                       name="c_mid_name" value="{{old('c_mid_name')}}"/>
                                                @error('c_mid_name')
                                                <span class="input-danger small">
                                                                {{ $message }}
                                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('last_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label class="required">Last Name</label>
                                                <input type="input" class="form-control" id="c_last_name"
                                                       name="c_last_name" value="{{old('c_last_name')}}" required/>
                                                @error('c_last_name')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                            </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div
                                                class="{!! ($errors->has('c_phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label>Phone</label>
                                                <input type="input" class="form-control" id="c_phone"
                                                       name="c_phone" value="{{old('c_phone')}}"/>
                                                @error('c_phone')
                                                <span class="input-danger small">
                                                            {{ $message }}
                                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('c_email')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label class="required">Email</label>
                                                <input type="email" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" class="form-control" id="c_email"
                                                       name="c_email" value="{{old('email')}}" required/>
                                                @error('c_email')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                            </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <input type="hidden" id="CoordinatorjsonData" value=''>

                                        <div class="col-md-12">
                                            <div class="pull-right" style="text-align: right;">
                                                <button type="submit" id="c_button" class="btn btn-outline-primary"><i class="fa fa-save"></i>Save</button>
                                                <input type="hidden" name="c_submit_actions" id="c_submit_actions" value="Add">
                                                <input type="hidden" name="c_id" id="c_id" value="">
                                                <input type="hidden" name="c_site_id" id="c_site_id" value= "">
                                                <button type="button"  id="reset_c_button" class="btn btn-outline-warning"><i class="fas fa-undo-alt" aria-hidden="true"></i>Reset</button>
                                                <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                                            </div>
                                            <br>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered CtableAppend">
                                                <thead>
                                                <tr>
                                                    <th>Full Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="photographer">
                                <form  name="photographerForm" id="photographerForm"
                                       enctype="multipart/form-data" method="POST">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="site_id"  value="">
                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('photographer_first_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label class="required">First Name</label>
                                                <input type="input" class="form-control" id="photographer_first_name"
                                                       name="photographer_first_name" value="{{old('photographer_first_name')}}" required/>
                                                @error('photographer_first_name')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div
                                                class="{!! ($errors->has('photographer_mid_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label>Middle Name</label>
                                                <input type="input" class="form-control" id="photographer_mid_name"
                                                       name="photographer_mid_name" value="{{old('photographer_mid_name')}}"/>
                                                @error('photographer_mid_name')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div
                                                class="{!! ($errors->has('photographer_last_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label class="required">Last Name</label>
                                                <input type="input" class="form-control" id="photographer_last_name"
                                                       name="photographer_last_name" value="{{old('photographer_last_name')}}" required/>
                                                @error('photographer_last_name')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div
                                                class="{!! ($errors->has('photographer_phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label>Phone</label>
                                                <input type="input" class="form-control" id="photographer_phone"
                                                       name="photographer_phone" value="{{old('photographer_phone')}}"/>
                                                @error('photographer_phone')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div
                                                class="{!! ($errors->has('photographer_email')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label class="required">Email</label>
                                                <input type="email" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" class="form-control" id="photographer_email"
                                                       name="photographer_email" value="{{old('photographer_email')}}" required/>
                                                @error('photographer_email')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <input type="hidden" id="photographerjsonData" value=''>

                                        <div class="col-md-12">
                                            <div class="pull-right" style="text-align: right;">
                                                <button type="submit" id="photographer_button" class="btn btn-outline-primary"><i class="fa fa-save"></i>Save</button>

                                                <input type="hidden" name="photographer_submit_actions" id="photographer_submit_actions" value="Add">
                                                <input type="hidden" name="photo_id" id="photo_id" value="">
                                                <input type="hidden" name="photographer_site_id" id="photographer_site_id" value= "">
                                                <button type="button"  id="reset_photographer_button" class="btn btn-outline-warning"><i class="fas fa-undo-alt" aria-hidden="true"></i>Reset</button>
                                                <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>

                                            </div>
                                            <br>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered photographertableAppend">
                                                <thead>
                                                <tr>
                                                    <th>Full Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="others">
                                <form  name="othersForm" id="othersForm">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="site_id"  value="">
                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('others_first_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label class="required">First Name </label>
                                                <input type="input" class="form-control" id="others_first_name"
                                                       name="others_first_name" value="{{old('others_first_name')}}" required/>
                                                @error('others_first_name')
                                                <span class="input-danger small">
                                                            {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div
                                                class="{!! ($errors->has('others_mid_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label>Middle Name</label>
                                                <input type="input" class="form-control" id="others_mid_name"
                                                       name="others_mid_name" value="{{old('others_mid_name')}}"/>
                                                @error('others_mid_name')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('others_last_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label class="required">Last Name</label>
                                                <input type="input" class="form-control" id="others_last_name"
                                                       name="others_last_name" value="{{old('others_last_name')}}" required/>
                                                @error('others_last_name')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div
                                                class="{!! ($errors->has('others_phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label>Phone</label>
                                                <input type="input" class="form-control" id="others_phone"
                                                       name="others_phone" value="{{old('others_phone')}}"/>
                                                @error('others_phone')
                                                <span class="input-danger small">
                                                        {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('others_email')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                <label class="required">Email</label>
                                                <input type="email" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" class="form-control" id="others_email"
                                                       name="others_email" value="{{old('others_email')}}" required/>
                                                @error('others_email')
                                                <span class="input-danger small">
                                                            {{ $message }}
                                                        </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <input type="hidden" id="jsonDataOthers" value=''>

                                        <div class="col-md-12">
                                            <div class="pull-right" style="text-align: right;">
                                                <button type="submit" id="others_button" class="btn btn-outline-primary"><i class="fa fa-save"></i>Save</button>
                                                <input type="hidden" name="others_submit_actions" id="others_submit_actions" value="Add">
                                                <input type="hidden" name="others_id" id="others_id" value="">
                                                <input type="hidden" name="others_site_id" id="others_site_id" value= "">
                                                <button type="button"  id="reset_others_button" class="btn btn-outline-warning"><i class="fas fa-undo-alt" aria-hidden="true"></i>Reset</button>
                                                <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close " aria-hidden="true"></i> Close</button>
                                            </div>
                                            <br>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered otherstableAppend">
                                                <thead>
                                                <tr>
                                                    <th>Full Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection
        @section('styles')
            <link rel="stylesheet" href="{{ asset("dist/vendors/datatable/css/dataTables.bootstrap4.min.css") }}">
            <link rel="stylesheet" href="{{ asset("dist/vendors/datatable/buttons/css/buttons.bootstrap4.min.css") }}">
        @endsection
        @section('script')
            <script src="{{ asset("dist/vendors/datatable/js/jquery.dataTables.min.js") }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js" integrity="sha512-vSyPWqWsSHFHLnMSwxfmicOgfp0JuENoLwzbR+Hf5diwdYTJraf/m+EKrMb4ulTYmb/Ra75YmckeTQ4sHzg2hg==" crossorigin="anonymous"></script>
            <script src="{{ asset("dist/vendors/datatable/js/dataTables.bootstrap4.min.js") }}"></script>
            <script src="{{ asset("dist/vendors/datatable/editor/mindmup-editabletable.js") }}"></script>
            <script src="{{ asset("dist/vendors/datatable/editor/numeric-input-example.js") }}"></script>
            <script src="{{ asset("dist/js/datatableedit.script.js") }}"></script>
            <script src="http://loudev.com/js/jquery.quicksearch.js" type="text/javascript"></script>
            <script src="{{ asset("dist/vendors/select2/js/select2.full.min.js") }}"></script>
            <script src="{{ asset("dist/js/select2.script.js") }}"></script>
            <script type="text/javascript">
                ///  Delete  Specific Row function

                $('body').on('click','.studySitetableData',function (){
                    $('#global_site_id').val('')
                    var id = $(this).attr('data-id');
                    $('#global_site_id').val(id);
                })

                $('body').on('focusout','.studySitetableDataaaa',function() {
                    var text_val = $(this).val();
                    var site_id = $('#global_site_id').val();

                    $.ajax({
                        url: "{{route('updateStudySiteId')}}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'text_val':text_val,'site_id':site_id
                        },
                        success:function(results){
                            console.log(results);
                            window.setTimeout(function () {
                                location.href = '{{ route('studySite.index') }}';
                            }, 10);
                        }
                    });

                });

                $("#studySiteForm").submit(function(e) {
                    var html = '';
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    e.preventDefault();
                    $.ajax({
                        data: $('#studySiteForm').serialize(),
                        url: "{{route('studySite.update')}}",
                        type: "POST",
                        dataType: 'json',
                        success: function (results) {
                            $('#select-sites').html('');
                            html +='<ul class="ms-list" tabindex="-1">';
                            $.each(results,function(i,v){
                                console.log(results);
                                if(i ==0){
                                    var selected = 'selected';
                                }
                                html += '<li selected="'+selected+'" class="ms-elem-selection ms-selected ms-hover" id="'+v.site_id+'-selection" style=""><span>+v.site_name+</span></li>';
                            });
                            html +='</ul>';
                            $('#select-sites').append(html);
                            $('.success-msg-sec').html('');
                            $('.success-msg-sec').html('Operation Done!')
                            $('.success-alert-sec').slideDown('slow');
                            tId=setTimeout(function(){
                                $(".success-alert-sec").slideUp('slow');
                            }, 3000);
                            window.setTimeout(function () {
                                location.href = '{{ route('studySite.index') }}';
                            }, 100);

                        },
                        error: function (results) {
                            console.log('Error:', results);
                        }
                    });
                });

                $(document).ready(function() {

                    $('#select-sites').multiSelect({
                        selectableHeader: "<label for=''>All Sites</label><input type='text' class='form-control' autocomplete='off' placeholder='search here'>",
                        selectionHeader: "<label for=''>Assigned Sites</label><input type='text' class='form-control' autocomplete='off' placeholder='search here'>",
                        afterInit: function(ms){
                            var that = this,
                                $selectableSearch = that.$selectableUl.prev(),
                                $selectionSearch = that.$selectionUl.prev(),
                                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                                .on('keydown', function(e){
                                    if (e.which === 40){
                                        that.$selectableUl.focus();
                                        return false;
                                    }
                                });

                            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                                .on('keydown', function(e){
                                    if (e.which == 40){
                                        that.$selectionUl.focus();
                                        return false;
                                    }
                                });
                        },
                        afterSelect: function(){
                            this.qs1.cache();
                            this.qs2.cache();
                        },
                        afterDeselect: function(){
                            this.qs1.cache();
                            this.qs2.cache();
                        }
                    });
                });

                // override the select2 open event
                $('.coordinatorsData').on('select2:unselect', function (e) {
                    // get values of selected option
                    var studySiteId = $(this).attr('data-id');
                    var coordinator_id = e.params.data.id;

                    // get the pop up selection
                    var pop_up_selection = $('.select2-results__options');

                    if (coordinator_id != null ) {
                        // hide the selected values
                        pop_up_selection.find("li[aria-selected=true]").hide();

                    } else {
                        // show all the selection values
                        pop_up_selection.find("li[aria-selected=true]").show();
                    }

                    $.ajax({
                        url: "{{route('deleteCO')}}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'coordinator_id':coordinator_id,'studySiteId':studySiteId
                        },
                        success:function(results){
                            console.log(results);
                            window.setTimeout(function () {
                                location.href = '{{ route('studySite.index') }}';
                            }, 10);
                        }
                    });

                });


                function assignCoordinator(site_id){
                        var coordinators_id = $('#coordinators_'+site_id).val();
                        var table_site_study_id = $("#table_site_study_id_"+site_id).val();

                 $.ajax({
                        url: "{{route('insertCO')}}",
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'coordinators_id':coordinators_id,'table_site_study_id':table_site_study_id
                        },
                        success:function(results){
                            console.log(results);
                            window.setTimeout(function () {
                                location.href = '{{ route('studySite.index') }}';
                            }, 10);
                        }
                    });

                }

                function updatePI(site_id){
                    var pi_id_value = $("#primaryInvestigator_"+site_id).val();
                    var table_site_study_id = $("#table_site_study_id_"+site_id).val();

                        $.ajax({
                            url: "{{route('updatePI')}}",
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                'pi_id_value':pi_id_value,'table_site_study_id':table_site_study_id
                            },
                            success:function(results){
                                window.setTimeout(function () {
                                    location.href = '{{ route('studySite.index') }}';
                                }, 10);
                            }
                        });
                }

                var placeSearch, autocomplete;

                var componentForm = {
                    street_number: 'short_name',
                    route: 'long_name',
                    locality: 'long_name',
                    administrative_area_level_1: 'short_name',
                    country: 'long_name',
                    postal_code: 'short_name',

                };

                function initAutocomplete() {
                    // Create the autocomplete object, restricting the search predictions to
                    // geographical location types.
                    autocomplete = new google.maps.places.Autocomplete(
                        document.getElementById('autocomplete'), {types: ['geocode']});
                    autocomplete.addListener('place_changed', fillInAddress);
                }

                // [START region_fillform]
                function fillInAddress() {
                    // Get the place details from the autocomplete object.
                    var place = autocomplete.getPlace();

                    for (var component in componentForm) {
                        document.getElementById(component).value = '';
                        document.getElementById(component).disabled = false;
                    }

                    // Get each component of the address from the place details
                    // and fill the corresponding field on the form.
                    var fullAddress =[];
                    for (var i = 0; i < place.address_components.length; i++) {
                        var addressType = place.address_components[i].types[0];
                        if (componentForm[addressType]) {
                            var val = place.address_components[i][componentForm[addressType]];
                            document.getElementById(addressType).value = val;
                        }
                        if (addressType == "street_number") {
                            fullAddress[0] = val;
                        } else if (addressType == "route") {
                            fullAddress[1] = val;
                        }
                    }
                    document.getElementById('fullAddr').value = fullAddress.join(" ");
                    if (document.getElementById('fullAddr').value !== "") {
                        document.getElementById('fullAddr').disabled = false;
                    }
                }

                // Bias the autocomplete object to the user's geographical location,
                // as supplied by the browser's 'navigator.geolocation' object.
                function geolocate() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var geolocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };
                            var circle = new google.maps.Circle({
                                center: geolocation,
                                radius: position.coords.accuracy
                            });
                            autocomplete.setBounds(circle.getBounds());
                        });
                    }
                }

                // Add New Primary Investigator
                function addPrimaryInvestigator(){
                    $("#primaryInvestigatorForm").submit(function(e) {
                        var first_name        = $('#pi_first_name').val();
                        var p_mid_name        = $('#pi_mid_name').val();
                        var p_last_name       = $('#pi_last_name').val();
                        var p_phone           = $('#pi_phone').val();
                        var p_email           = $('#pi_email').val();
                        var pi_id             = $('#pi_id').val();
                        var pi_submit_actions = $('#pi_submit_actions').val();
                        $('#primaryInvestigatorForm').find($('input[name="site_id"]').val($('#site_id').val()));
                        if(pi_submit_actions  == 'Add')
                        {
                            var action_url = "{{ route('primaryinvestigator.store') }}";
                        }
                        else
                        {
                            var action_url = "{{ route('updatePrimaryinvestigator') }}";
                        }
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            }
                        });
                        e.preventDefault();
                        $.ajax({
                            data: $('#primaryInvestigatorForm').serialize(),
                            url: action_url,
                            type: "POST",
                            dataType: 'json',
                            success: function (results) {
                                //console.log(results);
                                var primary_investigator_id = results.id;
                                var html    =   '';

                                if(pi_submit_actions == 'Add') {

                                    html    += '<tr id='+primary_investigator_id+'>\n'+
                                        '<td>'+first_name + '   '.repeat(4)+p_last_name+'</td>\n'+
                                        '<td>'+p_phone+'</td>\n' +
                                        '<td>'+p_email+'</td>\n' +
                                        '<td><i style="color: #EA4335;" class="fa fa-trash deleteprimaryinvestigator" data-id ='+primary_investigator_id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editprimaryinvestigator" data-id ='+primary_investigator_id+'></i>'+
                                        '</td>\n' +
                                        '</tr>';

                                    $('.primaryInvestigatorTableAppend tbody').prepend(html);
                                }
                                else{

                                    $.each(results, function(index,row)
                                    {
                                        //console.log(results);
                                        html    += '<tr id='+row.id+'>\n'+
                                            '<td>'+row.first_name + '  '.repeat(4)+row.last_name+'</td>\n'+
                                            '<td>'+row.phone+'</td>\n' +
                                            '<td>'+row.email+'</td>\n' +
                                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteprimaryinvestigator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editprimaryinvestigator" data-id ='+row.id+'></i>'+
                                            '</td>\n' +
                                            '</tr>';
                                    });

                                    $('.primaryInvestigatorTableAppend tbody').html(html);

                                }

                                $('#primaryInvestigatorForm').trigger("reset");
                            },
                            error: function (results) {
                                console.log('Error:', results);
                                //$('#saveChild').html('Save Changes');
                            }
                        });
                    });
                }
                addPrimaryInvestigator();
                // End of primary Investigator

                // Primary Investigator Delete function
                function primaryinvestigatorDestroy(){
                    $('body').on('click', '.deleteprimaryinvestigator', function () {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        var primary_investigator_id = $(this).data("id");
                        var url = "{{URL('/primaryinvestigator')}}";
                        var newPath = url+ "/"+ primary_investigator_id+"/destroy/";
                        if( confirm("Are You sure want to delete !") ==true)
                        {
                            $.ajax({
                                type: "GET",
                                url: newPath,
                                success: function (data) {
                                    $('#'+primary_investigator_id).remove();
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    });
                }
                primaryinvestigatorDestroy();

                function resetprimaryinvestigatorForm() {
                    $("#rest_pi_button").click(function(){
                        $("#pi_submit_actions").attr('value', 'Add');
                        $("#primaryInvestigatorForm").trigger("reset");
                    });
                }
                resetprimaryinvestigatorForm();

                function resetcoordinatorForm() {
                    $("#reset_c_button").click(function(){
                        $("#c_submit_actions").attr('value', 'Add');
                        $("#coordinatorForm").trigger("reset");
                    });
                }
                resetcoordinatorForm();

                function resetphotographerForm()
                {
                    $("#reset_photographer_button").click(function(){
                        $("#photographer_submit_actions").attr('value', 'Add');
                        $("#photographerForm").trigger("reset");
                    });
                }
                resetphotographerForm();

                function resetothersForm()
                {
                    $("#reset_others_button").click(function(){
                        $("#others_submit_actions").attr('value', 'Add');
                        $("#othersForm").trigger("reset");
                    });
                }

                resetothersForm();
                //// show Coordinator function

                function showCoordinator() {
                    $('body').on('click', '.editCoordinator', function (e) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        var id =($(this).attr("data-id"));
                        var url = "{{URL('/coordinator')}}";
                        var newPath = url+ "/"+ id+"/edit/";

                        $.ajax({
                            type:"GET",
                            dataType: 'html',
                            url:newPath,
                            success : function(results) {
                                var parsedata = JSON.parse(results)[0];
                                console.log(parsedata);
                                $('#c_id').val(parsedata.id);
                                $('#c_site_id').val(parsedata.site_id);
                                $('#c_submit_actions').val('Edit');
                                $('#c_first_name').val(parsedata.first_name);
                                $('#c_mid_name').val(parsedata.mid_name);
                                $('#c_last_name').val(parsedata.last_name);
                                $('#c_phone').val(parsedata.phone);
                                $('#c_email').val(parsedata.email);
                            }
                        });
                    });
                }
                showCoordinator();


                //// show Primary Investigator function
                function showPrimaryInvestigator() {
                    $('body').on('click', '.editprimaryinvestigator', function (e) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        var id =($(this).attr("data-id"));
                        var url = "{{URL('/primaryinvestigator')}}";
                        var newPath = url+ "/"+ id+"/edit/";
                        $.ajax({
                            type:"GET",
                            dataType: 'html',
                            url:newPath,
                            success : function(results) {
                                var parsedata = JSON.parse(results)[0];
                                console.log(parsedata);
                                $('#pi_id').val(parsedata.id);
                                $('#pi_site_id').val(parsedata.site_id);
                                $('#pi_submit_actions').val('Edit');
                                $('#pi_first_name').val(parsedata.first_name);
                                $('#pi_mid_name').val(parsedata.mid_name);
                                $('#pi_last_name').val(parsedata.last_name);
                                $('#pi_phone').val(parsedata.phone);
                                $('#pi_email').val(parsedata.email);
                            }
                        });
                    });
                }
                showPrimaryInvestigator();
                // Add New Photographer
                function addPhotographer()
                {
                    $("#photographerForm").submit(function(e) {
                        var photographer_first_name     = $('#photographer_first_name').val();
                        var photographer_mid_name       = $('#photographer_mid_name').val();
                        var photographer_last_name      = $('#photographer_last_name').val();
                        var photographer_phone          = $('#photographer_phone').val();
                        var photographer_email          = $('#photographer_email').val();
                        var photo_id                    = $('#photo_id').val();
                        var photographer_submit_actions = $('#photographer_submit_actions').val();
                        $('#photographerForm').find($('input[name="site_id"]').val($('#site_id').val()));
                        if(photographer_submit_actions  == 'Add')
                        {
                            var action_url = "{{ route('photographers.store') }}";
                        }
                        else
                        {
                            var action_url = "{{ route('updatePhotographers') }}";
                        }
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        e.preventDefault();
                        $.ajax({
                            data: $('#photographerForm').serialize(),
                            url: action_url,
                            type: "POST",
                            dataType: 'json',
                            success: function (results) {

                                var photographer_id = results[0].id;
                                var html    =   '';
                                if(photographer_submit_actions == 'Add')
                                {
                                    html    += '<tr id='+photographer_id+'>\n' +
                                        '<td>'+photographer_first_name + '   '.repeat(4)+photographer_last_name+'</td>\n'+
                                        '<td>'+photographer_phone+'</td>\n' +
                                        '<td>'+photographer_email+'</td>\n' +
                                        '<td><i style="color: #EA4335;"  class="fa fa-trash deletePhotographer" data-id = '+photographer_id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editPhotographer" data-id = '+photographer_id+'></i></td>\n' +
                                        '</tr>';
                                    $('.photographertableAppend tbody').prepend(html);

                                }
                                else
                                {
                                    $.each(results, function(index,row)
                                    {
                                        //console.log(results[0].index);
                                        html    += '<tr id='+row.id+'>\n' +
                                            '<td>'+row.first_name+ '   '.repeat(4)+row.last_name+'</td>\n'+
                                            '<td>'+row.phone+'</td>\n' +
                                            '<td>'+row.email+'</td>\n' +
                                            '<td><i style="color: #EA4335;" class="fa fa-trash deletePhotographer" data-id = '+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editPhotographer" data-id = '+row.id+'></i></td>\n' +
                                            '</tr>';
                                    });
                                    $('.photographertableAppend tbody').html(html);
                                }
                                $('#photographerForm').trigger("reset");
                            },
                            error: function (results) {
                                console.log('Error:', results);
                                //$('#saveChild').html('Save Changes');
                            }
                        });
                    });
                }
                addPhotographer();
                // End of Photographer
                ///////////////////////


                //// show Photographer function

                function showPhotographer()
                {
                    $('body').on('click', '.editPhotographer', function (e) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        var id =($(this).attr("data-id"));
                        var url = "{{URL('/photographers')}}";;
                        var newPath = url+ "/"+ id+"/edit/";
                        $.ajax({
                            type:"GET",
                            dataType: 'html',
                            url:newPath,
                            success : function(results) {
                                var parsedata = JSON.parse(results)[0];
                                $('#photo_id').val(parsedata.id);
                                $('#photographer_site_id').val(parsedata.site_id);
                                $('#photographer_submit_actions').val('Edit');
                                $('#photographer_first_name').val(parsedata.first_name);
                                $('#photographer_mid_name').val(parsedata.mid_name);
                                $('#photographer_last_name').val(parsedata.last_name);
                                $('#photographer_phone').val(parsedata.phone);
                                $('#photographer_email').val(parsedata.email);
                            }
                        });
                    });
                }
                showPhotographer();

                // Add New Coordinator
                function addCoordinator()
                {
                    $("#coordinatorForm").submit(function(e) {
                        var c_first_name   = $('#c_first_name').val();
                        var c_mid_name   = $('#c_mid_name').val();
                        var c_last_name  = $('#c_last_name').val();
                        var c_phone      = $('#c_phone').val();
                        var c_email      = $('#c_email').val();
                        var c_id = $('#c_id').val();
                        var c_submit_actions = $('#c_submit_actions').val();
                        $('#coordinatorForm').find($('input[name="site_id"]').val($('#site_id').val()));

                        if(c_submit_actions == 'Add')
                        {
                            var action_url = "{{ route('coordinator.store') }}";
                        }
                        else
                        {
                            var action_url = "{{ route('updateCoordinator') }}";
                        }

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        e.preventDefault();
                        $.ajax({
                            data: $('#coordinatorForm').serialize(),
                            url: action_url,
                            type: "POST",
                            dataType: 'json',
                            success: function (results) {
                                //console.log(results);
                                var coordinator_id = results[0].id;
                                var html    =   '';
                                if(c_submit_actions == 'Add')
                                {
                                    html    += '<tr id= '+coordinator_id+'>\n' +
                                        '<td>'+c_first_name + '   '.repeat(4)+c_last_name+'</td>\n'+
                                        '<td>'+c_phone+'</td>\n' +
                                        '<td>'+c_email+'</td>\n' +
                                        '<td><i style="color: #EA4335;" class="fa fa-trash deleteCoordinator" data-id ='+coordinator_id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editCoordinator" data-id ='+coordinator_id+'></i></td>\n' +
                                        '</tr>';
                                    $('.CtableAppend tbody').prepend(html);
                                }
                                else
                                {
                                    $.each(results, function(index,row)
                                    {
                                        console.log(results[0].index);
                                        html    += '<tr id= '+row.id+'>\n' +
                                            '<td>'+row.first_name + '   '.repeat(4)+row.last_name+'</td>\n'+
                                            '<td>'+row.phone+'</td>\n' +
                                            '<td>'+row.email+'</td>\n' +
                                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteCoordinator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editCoordinator" data-id ='+row.id+'></i></td>\n' +
                                            '</tr>';
                                    });
                                    $('.CtableAppend tbody').html(html);

                                }
                                $('#coordinatorForm').trigger("reset");
                            },
                            error: function (results) {
                                console.log('Error:', results);
                                //$('#saveChild').html('Save Changes');
                            }
                        });
                    });
                }
                addCoordinator();
                // End of Coordinator

                function showOthers()
                {
                    $('body').on('click', '.editOthers', function (e) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        var id =($(this).attr("data-id"));
                        var url = "{{URL('/others')}}";
                        var newPath = url+ "/"+ id+"/edit/";

                        $.ajax({
                            type:"GET",
                            dataType: 'html',
                            url:newPath,
                            success : function(results) {
                                var parsedata = JSON.parse(results)[0];
                                console.log(parsedata);
                                $('#others_id').val(parsedata.id);
                                $('#others_site_id').val(parsedata.site_id);
                                $('#others_submit_actions').val('Edit');
                                $('#others_first_name').val(parsedata.first_name);
                                $('#others_mid_name').val(parsedata.mid_name);
                                $('#others_last_name').val(parsedata.last_name);
                                $('#others_phone').val(parsedata.phone);
                                $('#others_email').val(parsedata.email);
                            }
                        });
                    });
                }

                showOthers();

                // Add New Others
                function addOthers()
                {
                    $("#othersForm").submit(function(e) {
                        var others_first_name = $('#others_first_name').val();
                        var others_mid_name   = $('#others_mid_name').val();
                        var others_last_name  = $('#others_last_name').val();
                        var others_phone      = $('#others_phone').val();
                        var others_email      = $('#others_email').val();
                        var others_id         = $('#others_id').val();
                        var others_submit_actions = $('#others_submit_actions').val();
                        $('#othersForm').find($('input[name="site_id"]').val($('#site_id').val()));
                        if(others_submit_actions == 'Add')
                        {
                            var action_url = "{{ route('others.store') }}";
                        }
                        else
                        {
                            var action_url = "{{ route('updateOthers') }}";
                        }
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        e.preventDefault();
                        $.ajax({
                            data: $('#othersForm').serialize(),
                            url: action_url,
                            type: "POST",
                            dataType: 'json',
                            success: function (results) {
                                var others_id = results[0].id;

                                var html    =   '';

                                if(others_submit_actions == 'Add')
                                {
                                    html += '<tr id=' + others_id + '>\n' +
                                        '<td>' + others_first_name + '   '.repeat(4) + others_last_name + '</td>\n' +
                                        '<td>' + others_phone + '</td>\n' +
                                        '<td>' + others_email + '</td>\n' +
                                        '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + others_id + '></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" data-id = ' + others_id + ' class="icon-pencil editOthers"></i></td>\n' +
                                        '</tr>';

                                    $('.otherstableAppend tbody').prepend(html);

                                }
                                else
                                {

                                    $.each(results, function(index,row)
                                    {
                                        //console.log(results[0].index);
                                        html += '<tr id=' + row.id + '>\n' +
                                            '<td>' + row.first_name + '   '.repeat(4) + row.last_name + '</td>\n' +
                                            '<td>' + row.phone + '</td>\n' +
                                            '<td>' + row.email + '</td>\n' +
                                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + row.id + '></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" data-id = ' + row.id + ' class="icon-pencil editOthers"></i></td>\n' +
                                            '</tr>';
                                    });

                                    $('.otherstableAppend tbody').html(html);

                                }

                                $('#othersForm').trigger("reset");
                            },
                            error: function (results) {
                                console.log('Error:', results);
                                //$('#saveChild').html('Save Changes');
                            }
                        });
                    });
                }
                addOthers();
                // End of Others


                $("#siteInfoForm").submit(function(e) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    e.preventDefault();

                    var sites_submit_actions = $('#sites_submit_actions').val();
                    if(sites_submit_actions == 'Add')
                    {
                        var action_url = "{{ route('sites.store') }}";
                    }
                    else
                    {
                        var action_url = "{{ route('updateSites') }}";
                    }
                    $.ajax({
                        data: $('#siteInfoForm').serialize(),
                        url: action_url,
                        type: "POST",
                        dataType: 'json',
                        success: function (results) {
                            if (results.success)
                            {
                                $('.success-msg-sec').html('');
                                $('.success-msg-sec').html(results.success)
                                $('.success-alert-sec').slideDown('slow');
                                tId=setTimeout(function(){
                                    $(".success-alert-sec").slideUp('slow');
                                }, 3000);
                                $("#siteInfoForm :input").prop("disabled", true);
                                $('.addTabs').attr("data-toggle","tab"); // Add data-toggle tab after inserts
                                // $('#primaryInvestigatorForm').find($('input[name="site_id"]').val(results.site_id));
                                $('#site_id').val(results.site_id);
                            }
                        },
                        error: function (results) {
                            console.log('Error:', results);
                            //$('#saveChild').html('Save Changes');
                        }
                    });
                });



                function checkIfSiteCodeExist()
                {
                    $('#site_code').focus(function () {
                        var siteCode = $('#site_code').val();
                    });
                }

                checkIfSiteCodeExist();

                $('body').on('click', '.editsiterecord', function (e) {
                    $('.modal-title').text('Edit Site');
                    $("#sites_submit_actions").attr('value', 'Edit');
                    var id =($(this).attr("data-id"));
                    var url = "{{URL('/sites')}}";
                    var newPath = url+ "/"+ id+"/edit/";

                    var pi_url = "{{URL('/primaryinvestigator')}}";
                    var new_pi_url = pi_url+ "/"+ id+"/showSiteId/";

                    var co_url = "{{URL('/coordinator')}}";
                    var new_co_url = co_url+ "/"+ id+"/showCoordinatorBySiteId/";

                    var ph_url = "{{URL('/photographers')}}";
                    var new_ph_url = ph_url+ "/"+ id+"/showPhotographerBySiteId/";

                    var other_url = "{{URL('/others')}}";
                    var new_other_url = other_url+ "/"+ id+"/showOtherBySiteId/";

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type:"GET",
                        dataType: 'html',
                        url:newPath,
                        success : function(results) {
                            console.log(results);
                            $('.addTabs').attr("data-toggle","tab"); // Add data-toggle tab after insert
                            var parsedata = JSON.parse(results)[0];
                            console.log(parsedata);
                            $('#site_id').val(parsedata.id);
                            $('#lastSiteId').val(parsedata.id);
                            $('#site_code').val(parsedata.site_code);
                            $('#site_name').val(parsedata.site_name);
                            $('#fullAddr').val(parsedata.site_address);
                            $('#locality').val(parsedata.site_city);
                            $('#administrative_area_level_1').val(parsedata.site_state);
                            $('#site_phone').val(parsedata.site_phone);
                            $('#country').val(parsedata.site_country);
                            //$('#sites_submit_actions').val('Edit');
                            $.ajax({
                                type:"GET",
                                dataType: 'html',
                                url:new_pi_url,
                                success : function(results) {
                                    //console.log(results);
                                    var parsedata = JSON.parse(results)[0];
                                    var html    =   '';
                                    $.each(parsedata, function(index,row)
                                    {
                                        //console.log(parsedata);
                                        html    += '<tr id='+row.id+'>\n'+
                                            '<td>'+row.first_name+ '  '.repeat(4)+row.last_name+'</td>\n'+
                                            '<td>'+row.phone+'</td>\n' +
                                            '<td>'+row.email+'</td>\n' +
                                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteprimaryinvestigator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editprimaryinvestigator" data-id ='+row.id+'></i>'+
                                            '</td>\n' +
                                            '</tr>';
                                    });
                                    $('.primaryInvestigatorTableAppend tbody').html('');
                                    $('.primaryInvestigatorTableAppend tbody').html(html);
                                    $.ajax({
                                        data: $('#coordinatorForm').serialize(),
                                        url: new_co_url,
                                        type: "GET",
                                        dataType: 'html',
                                        success: function (results) {
                                            //console.log(results);
                                            var parsedata = JSON.parse(results)[0];
                                            var html    =   '';
                                            $.each(parsedata, function(index,row)
                                            {
                                                html    += '<tr id= '+row.id+'>\n' +
                                                    '<td>'+row.first_name + '   '.repeat(4)+row.last_name+'</td>\n'+
                                                    '<td>'+row.phone+'</td>\n' +
                                                    '<td>'+row.email+'</td>\n' +
                                                    '<td><i style="color: #EA4335;" class="fa fa-trash deleteCoordinator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editCoordinator" data-id ='+row.id+'></i></td>\n' +
                                                    '</tr>';
                                            });
                                            $('.CtableAppend tbody').html('');
                                            $('.CtableAppend tbody').html(html);
                                            $.ajax({
                                                data: $('#photographerForm').serialize(),
                                                url: new_ph_url,
                                                type: "GET",
                                                dataType: 'html',
                                                success: function (results) {
                                                    //$('.photographertableAppend tbody tr').remove();
                                                    var parsedata = JSON.parse(results)[0];
                                                    var html    =   '';
                                                    $.each(parsedata, function(index,row)
                                                    {
                                                        //console.log(results[0].index);
                                                        html    += '<tr id='+row.id+'>\n' +
                                                            '<td>'+row.first_name+ '   '.repeat(4)+row.last_name+'</td>\n'+
                                                            '<td>'+row.phone+'</td>\n' +
                                                            '<td>'+row.email+'</td>\n' +
                                                            '<td><i style="color: #EA4335;" class="fa fa-trash deletePhotographer" data-id = '+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editPhotographer" data-id = '+row.id+'></i></td>\n' +
                                                            '</tr>';
                                                    });
                                                    $('.photographertableAppend tbody').html('');
                                                    $('.photographertableAppend tbody').html(html);
                                                    $('#photographerForm').trigger("reset");
                                                    $.ajax({
                                                        type:"GET",
                                                        dataType: 'html',
                                                        url:new_other_url,
                                                        success : function(results) {
                                                            //$('.otherstableAppend tbody tr').remove();
                                                            var parsedata = JSON.parse(results)[0];
                                                            var html    =   '';
                                                            $.each(parsedata, function(index,row)
                                                            {
                                                                html += '<tr id=' + row.id + '>\n' +
                                                                    '<td>' + row.first_name + '   '.repeat(4) + row.last_name + '</td>\n' +
                                                                    '<td>' + row.phone + '</td>\n' +
                                                                    '<td>' + row.email + '</td>\n' +
                                                                    '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + row.id + '></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" data-id = ' + row.id + ' class="icon-pencil editOthers"></i></td>\n' +
                                                                    '</tr>';
                                                            });

                                                            $('.otherstableAppend tbody').html('');
                                                            $('.otherstableAppend tbody').html(html);

                                                        }
                                                    });
                                                },
                                            });
                                        },
                                        error: function (results) {
                                            console.log('Error:', results);
                                        }
                                    });
                                }
                            });
                        }
                    });

                });

                //  Coordinator Delete function
                function  coordinatorDestroy ()
                {
                    $('body').on('click', '.deleteCoordinator', function () {

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        var coordinator_id = $(this).data("id");


                        var url = "{{URL('/coordinator/')}}";
                        var newPath = url+ "/"+ coordinator_id+"/destroy/";
                        if( confirm("Are You sure want to delete !") ==true)
                        {
                            $.ajax({
                                type: "GET",
                                url: newPath,
                                success: function (data) {
                                    $('#'+coordinator_id).remove();
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    });
                }
                coordinatorDestroy();



                //  Photographer Delete function

                function  photographerDestroy ()
                {
                    $('body').on('click', '.deletePhotographer', function () {

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        var photographer_id = $(this).data("id");


                        var url = "{{URL('/photographers/')}}";
                        var newPath = url+ "/"+ photographer_id+"/destroy/";
                        if( confirm("Are You sure want to delete !") ==true)
                        {
                            $.ajax({
                                type: "GET",
                                url: newPath,
                                success: function (data) {
                                    $('#'+photographer_id).remove();
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    });
                }
                photographerDestroy();


                //  Delete Others function

                function  othersDestroy ()
                {
                    $('body').on('click', '.deleteOthers', function () {

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        var others_id = $(this).data("id");


                        var url = "{{URL('/others/')}}";
                        var newPath = url+ "/"+ others_id+"/destroy/";
                        if( confirm("Are You sure want to delete !") ==true)
                        {
                            $.ajax({
                                type: "GET",
                                url: newPath,
                                success: function (data) {
                                    $('#'+others_id).remove();
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    });
                }
                othersDestroy();




                ///  Options Delete function
                function  sitesDestroy ()
                {
                    $('body').on('click','.deletesiterecord',function(){
                        var id = $(this).data('id');
                        if (confirm("Are you sure to delete?")) {
                            $.ajax({
                                url: 'sites/destroy/'+id,
                                type: 'POST',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "_method": 'DELETE',
                                    'id': id
                                },
                                success:function(result){
                                    console.log(result);
                                    window.setTimeout(function () {
                                        location.href = "{{ route('sites.index') }}";
                                    }, 100);
                                }
                            })
                        }
                    });
                }
                sitesDestroy();

                ///  Delete  Specific Row function

            </script>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEELbGoxVU_nvp6ayr2roHHnjN3hM_uec&libraries=places&callback=initAutocomplete" defer></script>
@endsection

