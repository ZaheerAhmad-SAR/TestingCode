@extends('layouts.app')

@section('title')
    <title> Sites | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')

    <style type="text/css">

        .required:after {
            content:" *";
            color: red;
        }

    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h2>Sites</h2>
                    </div>
                    <div class="pull-right btn-group">
                        <button type="button" class="btn custom-btn blue-color" data-toggle="modal"
                                data-target="#createsite"> <i class="fa fa-plus blue-color"></i>Add Site
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Country</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                            @foreach($sites as $site)
                                @if(!empty($site))
                                    <tr>
                                        <td>{{ucfirst($site->site_code)}}</td>
                                        <td>{{ucfirst($site->site_name)}}</td>
                                        <td>{{ucfirst($site->site_address)}}</td>
                                        <td>{{ucfirst($site->site_state)}}</td>
                                        <td>{{ucfirst($site->site_city)}}</td>
                                        <td>{{ucfirst($site->site_country)}}</td>
                                        <td>{{ucfirst($site->site_phone)}}</td>
                                        <td>
                                            <ul class="icon-list">
                                                <li><i class="fa fa-cogs" data-toggle="modal"
                                                       data-target="#editsite"></i></li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endif

                            @endforeach
                        </table>
                    </div>
                    {{$sites->links()}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="createsite">
            <div class="modal-dialog modal-lg" role="document" style="margin-top: 0;margin-bottom: 0;display: flex;">
                <div class="modal-content">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="custom-modal-header gray-background color-black">
                        <p class="modal-title">Add New Site</p>
                    </div>
                        <div id="exTab1" class="modal-body">

                            <!-- Nav tabs -->
                            <ul class="nav nav-pills nav-fill" role="tablist">
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="#siteInfo" aria-controls="siteInfo" role="tab" data-toggle="tab">Site Info</a></li>
                                <li role="presentation" class="nav-item"><a href="#primaryInvestigator" class="nav-link addTabs" aria-controls="primaryInvestigator" role="tab" >Primary Investigator</a></li>
                                <li role="presentation" class="nav-item"><a href="#coordinator" class="nav-link addTabs" aria-controls="coordinator" role="tab" >Coordinator</a></li>
                                <li role="presentation" class="nav-item"><a href="#photographer" class="nav-link addTabs" aria-controls="photographer" role="tab">Photographer</a></li>
                                <li role="presentation" class="nav-item"><a href="#others" class="nav-link addTabs" aria-controls="others" role="tab">Others</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content clearfix">
                                <div role="tabpanel" class="tab-pane active" id="siteInfo">
                                    <form name="siteInfoForm" id="siteInfoForm">
                                        @csrf
                                        <div class="col-lg-12">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="{!! ($errors->has('site_code')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                                <label class="required">Site Code</label>
                                                                <input type="input" class="form-control"
                                                                       name="site_code" id="site_code"
                                                                       value="{{old('site_code')}}" required/>
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
                                                                       name="site_name"
                                                                       value="{{old('site_name')}}" required/>
                                                                @error('site_name')
                                                                <span class="input-danger small">
                                                                    {{ $message }}
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="{!! ($errors->has('site_address')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                <label class="required">Address</label>
                                                                <input type="text" class="form-control"
                                                                       name="site_address" value="{{old('site_address')}}" required/>
                                                            </div>
                                                            @error('site_address')
                                                            <span class="input-danger small">
                                                            {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div
                                                                class="{!! ($errors->has('site_country')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                <label class="required">Country</label>
                                                                <input type="text" class="form-control" id="site_country"
                                                                       name="site_country" value="{{old('site_country')}}" required />
                                                            </div>
                                                            @error('site_country')
                                                            <span class="input-danger small">
                                                            {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="{!! ($errors->has('site_state')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                <label class="required">State</label>
                                                                <input type="text" class="form-control"
                                                                       name="site_state" value="{{old('site_state')}}" required/>
                                                            </div>
                                                            @error('site_state')
                                                            <span class="input-danger small">
                                                            {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="{!! ($errors->has('site_city')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                <label class="required">City</label>
                                                                <input type="text" class="form-control"
                                                                       name="site_city" value="{{old('site_city')}}"/>
                                                            </div>
                                                            @error('site_city')
                                                            <span class="input-danger small">
                                                            {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="{!! ($errors->has('site_phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                <label class="required">Phone</label>
                                                                <input type="text" class="form-control"
                                                                       name="site_phone" value="{{old('site_phone')}}" required/>
                                                            </div>
                                                            @error('site_phone')
                                                            <span class="input-danger small">
                                                                {{ $message }}
                                                                    </span>
                                                            @enderror
                                                        </div>

                                                        <div id="locationField">
                                                            <input id="autocomplete"
                                                                   placeholder="Enter your address"
                                                                   onFocus="geolocate()"
                                                                   type="text"/>
                                                        </div>

                                                        <table id="address">
                                                            <tr>
                                                                <td class="label">Street address</td>
                                                                <td class="slimField"><input class="field" id="street_number" disabled="true"/></td>
                                                                <td class="wideField" colspan="2"><input class="field" id="route" disabled="true"/></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="label">City</td>
                                                                <td class="wideField" colspan="3"><input class="field" id="locality" disabled="true"/></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="label">State</td>
                                                                <td class="slimField"><input class="field" id="administrative_area_level_1" disabled="true"/></td>
                                                                <td class="label">Zip code</td>
                                                                <td class="wideField"><input class="field" id="postal_code" disabled="true"/></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="label">Country</td>
                                                                <td class="wideField" colspan="3"><input class="field" id="country" disabled="true"/></td>
                                                            </tr>
                                                        </table>


                                                    </div>
                                                    <div class="modal-footer">

                                                        <button type="submit" id="btn_site_info" class="btn custom-btn blue-color"><i class="fa fa-save blue-color"></i> Save</button>
                                                        <button class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
                                                    </div>
                                                </div>
                                        </div>
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="primaryInvestigator">
                                    <form name="primaryInvestigatorForm" id="primaryInvestigatorForm">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="site_id" value="{{!empty($site->id)}}">
                                        <div class="row">
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
                                                <div class="pull-right">
                                                    <button type="submit" id="pi_button" class="btn custom-btn blue-color"><i class="fa fa-save blue-color"></i> Save</button>
                                                    <input type="hidden" name="pi_submit_actions" id="pi_submit_actions" value="Add">
                                                    <input type="hidden" name="pi_id" id="pi_id" value="">
                                                    <input type="hidden" name="site_id" id="site_id" value="">
                                                    <button type="button"  id="rest_pi_button" class="btn custom-btn blue-color"><i class="fa fa-refresh blue-color" aria-hidden="true"></i> Reset</button>
                                                    <button class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
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
                                        <input type="hidden" name="site_id" value="{{!empty($site->id)}}">
                                        <div class="row">
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
                                                <div class="pull-right">
                                                    <button type="submit" id="c_button" class="btn custom-btn blue-color"><i class="fa fa-save blue-color"></i>Save</button>
                                                    <input type="hidden" name="c_submit_actions" id="c_submit_actions" value="Add">
                                                    <input type="hidden" name="c_id" id="c_id" value="">
                                                    <input type="hidden" name="c_site_id" id="c_site_id" value= "">
                                                    <button type="button"  id="reset_c_button" class="btn custom-btn blue-color"><i class="fa fa-refresh blue-color" aria-hidden="true"></i>Reset</button>
                                                    <button class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
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
                                    <form action="#" name="photographerForm" id="photographerForm"
                                          enctype="multipart/form-data" method="POST">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="site_id" value="">
                                        <div class="row">
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
                                                <div class="pull-right">
                                                    <button type="submit" id="photographer_button" class="btn custom-btn blue-color"><i class="fa fa-save blue-color"></i>Save</button>

                                                    <input type="hidden" name="photographer_submit_actions" id="photographer_submit_actions" value="Add">
                                                    <input type="hidden" name="photo_id" id="photo_id" value="">
                                                    <input type="hidden" name="photographer_site_id" id="photographer_site_id" value= "">
                                                    <button type="button"  id="reset_photographer_button" class="btn custom-btn blue-color"><i class="fa fa-refresh blue-color" aria-hidden="true"></i>Reset</button>
                                                    <button class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>

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
                                        <input type="hidden" name="site_id" value="">
                                        <div class="row">
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
                                                <div class="pull-right">
                                                    <button type="submit" id="others_button" class="btn custom-btn blue-color"><i class="fa fa-save blue-color"></i>Save</button>

                                                    <input type="hidden" name="others_submit_actions" id="others_submit_actions" value="Add">
                                                    <input type="hidden" name="others_id" id="others_id" value="">
                                                    <input type="hidden" name="others_site_id" id="others_site_id" value= "">
                                                    <button type="button"  id="reset_others_button" class="btn custom-btn blue-color"><i class="fa fa-refresh blue-color" aria-hidden="true"></i>Reset</button>
                                                    <button class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
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
@section('scripts')



    <script type="text/javascript">

        var placeSearch, autocomplete;

        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            country: 'long_name',
            postal_code: 'short_name'
        };

        function initAutocomplete() {
            // Create the autocomplete object, restricting the search predictions to
            // geographical location types.
            autocomplete = new google.maps.places.Autocomplete(
                document.getElementById('autocomplete'), {types: ['geocode']});

            // Avoid paying for data that you don't need by restricting the set of
            // place fields that are returned to just the address components.
            autocomplete.setFields(['address_component']);

            // When the user selects an address from the drop-down, populate the
            // address fields in the form.
            autocomplete.addListener('place_changed', fillInAddress);
        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();

            for (var component in componentForm) {
                document.getElementById(component).value = '';
                document.getElementById(component).disabled = false;
            }

            // Get each component of the address from the place details,
            // and then fill-in the corresponding field on the form.
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    document.getElementById(addressType).value = val;
                }
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
                    var circle = new google.maps.Circle(
                        {center: geolocation, radius: position.coords.accuracy});
                    autocomplete.setBounds(circle.getBounds());
                });
            }
        }





        // Add New Primary Investigator
        function addPrimaryInvestigator()
        {
                $("#primaryInvestigatorForm").submit(function(e) {

                var first_name   = $('#pi_first_name').val();
                var p_mid_name   = $('#pi_mid_name').val();
                var p_last_name  = $('#pi_last_name').val();
                var p_phone      = $('#pi_phone').val();
                var p_email      = $('#pi_email').val();
                var pi_id = $('#pi_id').val();
                var pi_submit_actions = $('#pi_submit_actions').val();
                if(pi_submit_actions == 'Add')
                {
                    var action_url = '{{ route('primaryinvestigator.store') }}';
                }
                else
                {
                    var action_url = '{{ route('updatePrimaryinvestigator') }}';
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
                        var primary_investigator_id = results[0].id;

                        var html    =   '';

                        if(pi_submit_actions == 'Add') {

                            html    += '<tr id='+primary_investigator_id+'>\n'+
                                '<td>'+first_name + '   '.repeat(4)+p_last_name+'</td>\n'+
                                '<td>'+p_phone+'</td>\n' +
                                '<td>'+p_email+'</td>\n' +
                                '<td><i style="color: #EA4335;" class="fa fa-trash deleteprimaryinvestigator" data-id ='+primary_investigator_id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editprimaryinvestigator" data-id ='+primary_investigator_id+'></i>'+
                                '</td>\n' +
                                '</tr>';

                            $('.primaryInvestigatorTableAppend tbody').prepend(html);
                        }
                        else{
                            $.each(results, function(index,row)
                            {
                                //console.log(results[0].index);
                                html    += '<tr id='+row.id+'>\n'+
                                    '<td>'+row.first_name + '  '.repeat(4)+row.last_name+'</td>\n'+
                                    '<td>'+row.phone+'</td>\n' +
                                    '<td>'+row.email+'</td>\n' +
                                    '<td><i style="color: #EA4335;" class="fa fa-trash deleteprimaryinvestigator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editprimaryinvestigator" data-id ='+row.id+'></i>'+
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


        // Primary investigator Delete function
        function primaryinvestigatorDestroy()
        {
            $('body').on('click', '.deleteprimaryinvestigator', function () {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var primary_investigator_id = $(this).data("id");


                var url = "{{URL('primaryinvestigator')}}";
                var newPath = url+ "/"+ primary_investigator_id+"/destroy/";
                if( confirm("Are You sure want to delete !") ==true)
                {
                    $.ajax({
                        type: "GET",
                        url: newPath,
                        success: function (data) {
                            //console.log(data);
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


        function resetprimaryinvestigatorForm()
        {
            $("#rest_pi_button").click(function(){
                $("#pi_submit_actions").attr('value', 'Add');
                $("#primaryInvestigatorForm").trigger("reset");
            });
        }

        resetprimaryinvestigatorForm();


        function resetcoordinatorForm()
        {
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






        //// show Primary Investigator function

        function showPrimaryInvestigator()
        {
            $('body').on('click', '.editprimaryinvestigator', function (e) {


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var id =($(this).attr("data-id"));

                var url = "{{URL('/ocap/primaryinvestigator')}}";

                //var url = "{{URL('primaryinvestigator')}}";

                var newPath = url+ "/"+ id+"/edit/";

                console.log(newPath);

                $.ajax({
                    type:"GET",
                    dataType: 'html',
                    url:newPath,
                    success : function(results) {
                        var parsedata = JSON.parse(results)[0];
                        $('#pi_id').val(parsedata.id);
                        $('#site_id').val(parsedata.site_id);
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

                var photographer_first_name = $('#photographer_first_name').val();
                var photographer_mid_name   = $('#photographer_mid_name').val();
                var photographer_last_name  = $('#photographer_last_name').val();
                var photographer_phone      = $('#photographer_phone').val();
                var photographer_email      = $('#photographer_email').val();

                var photo_id = $('#photo_id').val();
                var photographer_submit_actions = $('#photographer_submit_actions').val();

                if(photographer_submit_actions == 'Add')
                {
                    var action_url = '{{ route('photographers.store') }}';
                }
                else
                {
                    var action_url = '{{ route('updatePhotographers') }}';
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
                                '<td><i style="color: #EA4335;"  class="fa fa-trash deletePhotographer" data-id = '+photographer_id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editPhotographer" data-id = '+photographer_id+'></i></td>\n' +
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
                                    '<td><i style="color: #EA4335;" class="fa fa-trash deletePhotographer" data-id = '+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editPhotographer" data-id = '+row.id+'></i></td>\n' +
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


                var url = "{{URL('/ocap/photographers')}}";

                //var url = "{{URL('photographers')}}";

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

                if(c_submit_actions == 'Add')
                {
                    var action_url = '{{ route('coordinator.store') }}';
                }
                else
                {
                    var action_url = '{{ route('updateCoordinator') }}';
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
                        var coordinator_id = results[0].id;
                        console.log(coordinator_id);
                        var html    =   '';

                        if(c_submit_actions == 'Add') {

                            html    += '<tr id= '+coordinator_id+'>\n' +
                                '<td>'+c_first_name + '   '.repeat(4)+c_last_name+'</td>\n'+
                                '<td>'+c_phone+'</td>\n' +
                                '<td>'+c_email+'</td>\n' +
                                '<td><i style="color: #EA4335;" class="fa fa-trash deleteCoordinator" data-id ='+coordinator_id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editCoordinator" data-id ='+coordinator_id+'></i></td>\n' +
                                '</tr>';

                            $('.CtableAppend tbody').prepend(html);

                        }
                        else{
                            $.each(results, function(index,row)
                            {
                                console.log(results[0].index);
                                html    += '<tr id= '+row.id+'>\n' +
                                    '<td>'+row.first_name + '   '.repeat(4)+row.last_name+'</td>\n'+
                                    '<td>'+row.phone+'</td>\n' +
                                    '<td>'+row.email+'</td>\n' +
                                    '<td><i style="color: #EA4335;" class="fa fa-trash deleteCoordinator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editCoordinator" data-id ='+row.id+'></i></td>\n' +
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




        //// show Coordinator function

        function showCoordinator()
        {
            $('body').on('click', '.editCoordinator', function (e) {


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var id =($(this).attr("data-id"));

                var url = "{{URL('/ocap/coordinator')}}";

                //var url = "{{URL('coordinator')}}";

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


        //// showOthers function

        function showOthers()
        {
            $('body').on('click', '.editOthers', function (e) {


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var id =($(this).attr("data-id"));

                var url = "{{URL('/ocap/others')}}";

                //var url = "{{URL('others')}}";

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


                var others_id = $('#others_id').val();
                var others_submit_actions = $('#others_submit_actions').val();

                if(others_submit_actions == 'Add')
                {
                    var action_url = '{{ route('others.store') }}';
                }
                else
                {
                    var action_url = '{{ route('updateOthers') }}';
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
                                '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + others_id + '></i>&nbsp;&nbsp;<i style="color: #34A853;" data-id = ' + others_id + ' class="fa fa-pencil-square-o editOthers"></i></td>\n' +
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
                                    '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + row.id + '></i>&nbsp;&nbsp;<i style="color: #34A853;" data-id = ' + row.id + ' class="fa fa-pencil-square-o editOthers"></i></td>\n' +
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


        // Add New Site Info
        function addSiteInfo()
        {

                $("#siteInfoForm").submit(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();
                $.ajax({
                    data: $('#siteInfoForm').serialize(),
                    url: "{{ route('sites.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (results) {


                        //$('#siteInfoForm').trigger("reset");
                        //$('#btn_site_info').prop('disabled', true);
                        $("#siteInfoForm :input").prop("disabled", true);

                        $('.addTabs').attr("data-toggle","tab"); // Add data-toggle tab after insert
                    },
                    error: function (results) {
                        console.log('Error:', results);
                        //$('#saveChild').html('Save Changes');
                    }
                });
            });
        }
        addSiteInfo();
        // End of primary Investigator


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


                var url = "{{URL('coordinator/')}}";
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


                var url = "{{URL('photographers/')}}";
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


                var url = "{{URL('others/')}}";
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

    </script>

{{--    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVE5dSFV8x1TuETkg7zOJIItC3CsyjYts&libraries=places&callback=initAutocomplete"--}}
{{--            defer></script>--}}

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEELbGoxVU_nvp6ayr2roHHnjN3hM_uec&libraries=places&callback=initAutocomplete"
            defer></script>



@endsection
