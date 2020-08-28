@extends('layouts.app')

@section('title')
    <title> Sites | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h2>Sites</h2>
                    </div>
                    <div class="pull-right btn-group">
                        {{--                        <a href="{!! route('sites.create') !!}" class="btn btn-success">Create Site</a>--}}
                        <button type="button" class="btn btn-primary fa fa-plus" data-toggle="modal" data-target="#createsite">Add Site
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            @foreach($sites as $site)
                                @if(!empty($site))
                                    <tr>
                                        <td>{{$site->id}}</td>
                                        <td>{{ucfirst($site->site_name)}}</td>
                                        <td>{{ucfirst($site->site_address)}}</td>
                                        <td>{{ucfirst($site->site_phone)}}</td>
                                        <td>{{$site->site_email}}</td>
                                        <td>
                                            <ul class="icon-list">
                                                <li><i class="fa fa-cogs" data-toggle="modal" data-target="#editsite"></i></li>
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
        <div class="modal" tabindex="-1" role="dialog" id="createsite">
            <div class="modal-lg">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="alert alert-danger" style="display:none"></div>
                        <div class="modal-header popover-header">

                            <h3 class="modal-title">Add New Site</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="exTab1" class="modal-body">
                                <ul  class="nav nav-pills btn">
                                    <li class="active">
                                        <a  href="#1a" data-toggle="tab" class="btn nav-tabs">Site Info</a>
                                    </li>
                                    <li><a href="#2a" data-toggle="tab" class="btn nav-tabs">PI</a>
                                    </li>
                                    <li><a href="#3a" data-toggle="tab" class="btn nav-tabs">Coordinator</a>
                                    </li>
                                    <li><a href="#4a" data-toggle="tab" class="btn nav-tabs">Photographer</a>
                                    </li>

                                    <li><a href="#5a" data-toggle="tab" class="btn nav-tabs">Other</a>
                                    </li>
                                </ul>
                                <div class="tab-content clearfix">
                                    <div class="tab-pane active" id="1a">
                                        <form action="{{route('sites.store')}}" enctype="multipart/form-data" method="POST">
                                            @csrf
                                            <div class="col-lg-12">
                                                <div class="panel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="{!! ($errors->has('site_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                                    <label>Site Name</label>
                                                                    <input type="input" class="form-control" name="site_name" value="{{old('site_name')}}">
                                                                    @error('site_name')
                                                                    <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="{!! ($errors->has('site_address')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                    <label>Address</label>
                                                                    <input type="text" class="form-control" name="site_address">{{old('site_address')}}</input>
                                                                </div>
                                                                @error('site_address')
                                                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="{!! ($errors->has('site_city')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                    <label>City</label>
                                                                    <input type="text" class="form-control" name="site_city">{{old('site_city')}}</input>
                                                                </div>
                                                                @error('site_city')
                                                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="{!! ($errors->has('site_state')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                    <label>State</label>
                                                                    <input type="text" class="form-control" name="site_state">{{old('site_state')}}</input>
                                                                </div>
                                                                @error('site_state')
                                                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="{!! ($errors->has('site_phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                    <label>Phone</label>
                                                                    <input type="text" class="form-control" name="site_phone">{{old('site_phone')}}</input>
                                                                </div>
                                                                @error('site_phone')
                                                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                                @enderror
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="pull-right">
                                                        <button type="submit" class="btn btn-primary" >Save</button>
                                                        <button type="button" id="nextBtn" class="btn btn-primary" href="#2a" data-toggle="tab">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="2a">
                                        <form action="{{route('primaryinvestigator.store')}}" name="add_pi" id="add_pi" enctype="multipart/form-data" method="POST">
                                            <div class="addNewForm">


                                                <div class="row">
                                                    <h3>Add Primary Investigator</h3>
                                                </div>

                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <input type="hidden" name="site_id" value="">
                                                <div id="field">
                                                    <div id="field0">
                                                        <!-- Text input-->
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label" for="action_id">First Name</label>
                                                            <div class="col-md-5">
                                                                <input id="first_name" name="first_name" type="text" placeholder="" class="form-control input-md">

                                                            </div>
                                                        </div>
                                                        <br><br>
                                                        <!-- Text input-->
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label" for="middle name">Middle Name</label>
                                                            <div class="col-md-5">
                                                                <input id="mid_name" name="mid_name" type="text" placeholder="" class="form-control input-md">

                                                            </div>
                                                        </div>
                                                        <br><br>
                                                        
                                                    </div>
                                                </div>
                                                <!-- Button -->
                                                <div class="form-group">
                                                    <div class="col-md-4">
                                                        <button id="add-more" name="add-more" class="btn btn-primary">Add More</button>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" id="send_form1" class="btn btn-primary">Save</button>
                                                    <button class="btn btn-primary" type="button"  href="#">Next</button>                                        </div>
                                            </div>

                                        </form>
                                    </div>
                                    <div class="tab-pane" id="3a">
                                        <form action="{{route('coordinator.store')}}" name="c_form" id="c_form" enctype="multipart/form-data" method="POST">
                                            <div class="row">
                                                <h3>Add Coordinator</h3>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('first_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>First Name</label>
                                                            <input type="input" class="form-control" id="c_first_name" name="c_first_name" value="{{old('first_name')}}">
                                                            @error('first_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('mid_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Middle Name</label>
                                                            <input type="input" class="form-control" id="c_mid_name" name="c_mid_name" value="{{old('mid_name')}}">
                                                            @error('mid_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('last_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Last Name</label>
                                                            <input type="input" class="form-control" id="c_last_name" name="c_last_name" value="{{old('last_name')}}">
                                                            @error('last_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('c_phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Phone</label>
                                                            <input type="input" class="form-control" id="c_phone" name="c_phone" value="{{old('phone')}}">
                                                            @error('phone')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('email')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Email</label>
                                                            <input type="email" class="form-control" id="c_email" name="c_email" value="{{old('email')}}">
                                                            @error('email')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" id="coordinator" class="btn btn-primary">Save</button>
                                                <button class="btn btn-primary" type="button"  href="#">Next</button>                                        </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="4a">
                                        <form action="{{route('photographers.store')}}" name="p_form" id="p_form" enctype="multipart/form-data" method="POST">
                                            @csrf
                                            <div class="row">
                                                <h3>Add Photographer</h3>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('first_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>First Name</label>
                                                            <input type="input" class="form-control" id="p_first_name" name="first_name" value="{{old('first_name')}}">
                                                            @error('first_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('mid_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Middle Name</label>
                                                            <input type="input" class="form-control" id="p_mid_name" name="mid_name" value="{{old('mid_name')}}">
                                                            @error('mid_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('last_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Last Name</label>
                                                            <input type="input" class="form-control" id="p_last_name" name="last_name" value="{{old('last_name')}}">
                                                            @error('last_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Phone</label>
                                                            <input type="input" class="form-control" id="p_phone" name="phone" value="{{old('phone')}}">
                                                            @error('phone')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('email')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Email</label>
                                                            <input type="email" class="form-control" id="p_email" name="email" value="{{old('email')}}">
                                                            @error('email')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary" id="coordinator">Save</button>
                                                <button class="btn btn-primary" type="button"  href="#">Next</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="5a">
                                        <form action="{{--{{route('others.store')}}--}}" enctype="multipart/form-data" method="POST">
                                            <div class="row">
                                                <h3>Add Other Staff</h3>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('first_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>First Name</label>
                                                            <input type="input" class="form-control" id="first_name" name="first_name" value="{{old('first_name')}}">
                                                            @error('first_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('mid_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Middle Name</label>
                                                            <input type="input" class="form-control" id="mid_name" name="mid_name" value="{{old('mid_name')}}">
                                                            @error('mid_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('last_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Last Name</label>
                                                            <input type="input" class="form-control" id="last_name" name="last_name" value="{{old('last_name')}}">
                                                            @error('last_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Phone</label>
                                                            <input type="input" class="form-control" id="phone" name="phone" value="{{old('phone')}}">
                                                            @error('phone')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('email')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Email</label>
                                                            <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}">
                                                            @error('email')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary" id="others">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog" id="editsite">
            <div class="modal-lg">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="alert alert-danger" style="display:none"></div>
                        <div class="modal-header popover-header">

                            <h3 class="modal-title">Add New Site</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="exTab1" class="modal-body">
                                <ul  class="nav nav-pills">
                                    <li class="active">
                                        <a  href="#1b" data-toggle="tab" class="btn nav-tabs">Site Info</a>
                                    </li>
                                    <li><a href="#2b" data-toggle="tab" class="btn nav-tabs">PI</a>
                                    </li>
                                    <li><a href="#3b" data-toggle="tab" class="btn nav-tabs">Coordinator</a>
                                    </li>
                                    <li><a href="#4b" data-toggle="tab" class="btn nav-tabs">Photographer</a>
                                    </li>

                                    <li><a href="#5b" data-toggle="tab" class="btn nav-tabs">Other</a>
                                    </li>
                                </ul>
                                <div class="tab-content clearfix">
                                    <div class="tab-pane active" id="1b">
                                        <form action="{{route('sites.store')}}" enctype="multipart/form-data" method="POST">
                                            @csrf
                                            <div class="col-lg-12">
                                                <div class="panel">
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="{!! ($errors->has('site_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                                    <label>Site Name</label>
                                                                    <input type="input" class="form-control" name="site_name" value="{{old('site_name')}}">
                                                                    @error('site_name')
                                                                    <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="{!! ($errors->has('site_manager')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                    <label>Manager</label>
                                                                    <input type="text" class="form-control" name="site_manager">{{old('site_manager')}}</input>
                                                                </div>
                                                                @error('site_manager')
                                                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="{!! ($errors->has('site_address')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                    <label>Address</label>
                                                                    <input type="text" class="form-control" name="site_address">{{old('site_address')}}</input>
                                                                </div>
                                                                @error('site_address')
                                                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="{!! ($errors->has('site_city')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                    <label>City</label>
                                                                    <input type="text" class="form-control" name="site_city">{{old('site_city')}}</input>
                                                                </div>
                                                                @error('site_city')
                                                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="{!! ($errors->has('site_state')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                    <label>State</label>
                                                                    <input type="text" class="form-control" name="site_state">{{old('site_state')}}</input>
                                                                </div>
                                                                @error('site_state')
                                                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="{!! ($errors->has('site_phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                    <label>Phone</label>
                                                                    <input type="text" class="form-control" name="site_phone">{{old('site_phone')}}</input>
                                                                </div>
                                                                @error('site_phone')
                                                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="{!! ($errors->has('site_email')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                                    <label>Email</label>
                                                                    <input type="email" class="form-control" name="site_email">{{old('site_email')}}</input>
                                                                </div>
                                                                @error('description')
                                                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="pull-right">
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                        <button class="btn btn-primary" type="button"  href="#">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="2b">
                                        <form action="{{route('primaryinvestigator.store')}}" name="add_pi" id="add_pi" enctype="multipart/form-data" method="POST">
                                            @csrf
                                            <div class="row">
                                                <h3>Add Primary Investigator</h3>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <input type="hidden" name="_token" value="">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('first_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>First Name</label>
                                                            <input type="input" class="form-control" name="first_name" value="{{old('first_name')}}">
                                                            @error('first_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('mid_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Middle Name</label>
                                                            <input type="input" class="form-control" name="mid_name" value="{{old('mid_name')}}">
                                                            @error('mid_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('last_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Last Name</label>
                                                            <input type="input" class="form-control" name="last_name" value="{{old('last_name')}}">
                                                            @error('last_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Phone</label>
                                                            <input type="input" class="form-control" name="phone" value="{{old('phone')}}">
                                                            @error('phone')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('email')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Email</label>
                                                            <input type="email" class="form-control" name="email" value="{{old('email')}}">
                                                            @error('email')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" id="send_form1" class="btn btn-primary">Save</button>
                                                <button class="btn btn-primary" type="button"  href="#">Next</button>                                        </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="3b">
                                        <form action="{{route('primaryinvestigator.store')}}" enctype="multipart/form-data" method="POST">
                                            @csrf
                                            <div class="row">
                                                <h3>Add Coordinator</h3>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('first_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>First Name</label>
                                                            <input type="input" class="form-control" name="first_name" value="{{old('first_name')}}">
                                                            @error('first_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('mid_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Middle Name</label>
                                                            <input type="input" class="form-control" name="mid_name" value="{{old('mid_name')}}">
                                                            @error('mid_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('last_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Last Name</label>
                                                            <input type="input" class="form-control" name="last_name" value="{{old('last_name')}}">
                                                            @error('last_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Phone</label>
                                                            <input type="input" class="form-control" name="phone" value="{{old('phone')}}">
                                                            @error('phone')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('email')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Email</label>
                                                            <input type="email" class="form-control" name="email" value="{{old('email')}}">
                                                            @error('email')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <button class="btn btn-primary" type="button"  href="#">Next</button>                                        </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="4b">
                                        <form action="{{route('primaryinvestigator.store')}}" enctype="multipart/form-data" method="POST">
                                            @csrf
                                            <div class="row">
                                                <h3>Add Photographer</h3>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('first_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>First Name</label>
                                                            <input type="input" class="form-control" name="first_name" value="{{old('first_name')}}">
                                                            @error('first_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('mid_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Middle Name</label>
                                                            <input type="input" class="form-control" name="mid_name" value="{{old('mid_name')}}">
                                                            @error('mid_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('last_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Last Name</label>
                                                            <input type="input" class="form-control" name="last_name" value="{{old('last_name')}}">
                                                            @error('last_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Phone</label>
                                                            <input type="input" class="form-control" name="phone" value="{{old('phone')}}">
                                                            @error('phone')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('email')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Email</label>
                                                            <input type="email" class="form-control" name="email" value="{{old('email')}}">
                                                            @error('email')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <button class="btn btn-primary" type="button"  href="#">Next</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="5a">
                                        <form action="{{route('primaryinvestigator.store')}}" enctype="multipart/form-data" method="POST">
                                            @csrf
                                            <div class="row">
                                                <h3>Add Other</h3>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('first_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>First Name</label>
                                                            <input type="input" class="form-control" name="first_name" value="{{old('first_name')}}">
                                                            @error('first_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('mid_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Middle Name</label>
                                                            <input type="input" class="form-control" name="mid_name" value="{{old('mid_name')}}">
                                                            @error('mid_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('last_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Last Name</label>
                                                            <input type="input" class="form-control" name="last_name" value="{{old('last_name')}}">
                                                            @error('last_name')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('phone')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Phone</label>
                                                            <input type="input" class="form-control" name="phone" value="{{old('phone')}}">
                                                            @error('phone')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="{!! ($errors->has('email')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                                            <label>Email</label>
                                                            <input type="email" class="form-control" name="email" value="{{old('email')}}">
                                                            @error('email')
                                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>
        jQuery(document).ready(function(){
            jQuery('#send_form1').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {

                    }
                });
                jQuery.ajax({
                    url: "{{ url('/primaryinvestigator') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        first_name: jQuery('#first_name').val(),
                        mid_name: jQuery('#mid_name').val(),
                        last_name: jQuery('#last_name').val(),
                        phone: jQuery('#phone').val(),
                        email: jQuery('#email').val()
                    },
                    success: function(result){
                        console.log(result);
                    }});
            });
        });

        jQuery(document).ready(function(){
            jQuery('#coordinator').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {

                    }
                });
                jQuery.ajax({
                    url: "{{ url('/coordinator') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        first_name: jQuery('#c_first_name').val(),
                        mid_name: jQuery('#c_mid_name').val(),
                        last_name: jQuery('#c_last_name').val(),
                        phone: jQuery('#c_phone').val(),
                        email: jQuery('#c_email').val()
                    },
                    success: function(result){
                        console.log(result);
                    }});
            });
        });

        jQuery(document).ready(function(){
            jQuery('#photographers').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {

                    }
                });
                jQuery.ajax({
                    url: "{{ url('/photographers') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        first_name: jQuery('#p_first_name').val(),
                        mid_name: jQuery('#p_mid_name').val(),
                        last_name: jQuery('#p_last_name').val(),
                        phone: jQuery('#p_phone').val(),
                        email: jQuery('#p_email').val()
                    },
                    success: function(result){
                        console.log(result);
                    }});
            });
        });

        jQuery(document).ready(function(){
            jQuery('#others').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {

                    }
                });
                jQuery.ajax({
                    url: "{{ url('/coordinator') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        first_name: jQuery('#first_name').val(),
                        mid_name: jQuery('#mid_name').val(),
                        last_name: jQuery('#last_name').val(),
                        phone: jQuery('#phone').val(),
                        email: jQuery('#email').val()
                    },
                    success: function(result){
                        console.log(result);
                    }});
            });
        });


        jQuery(document).ready(function () {

            jQuery("div[id^='1a']").each(function () {

                var currentModal = $(this);
                console.log(currentModal);

            //click next
                currentModal.find('.btn-next').click(function () {
                    currentModal.modal('hide');
                    currentModal.closest("div[id^='1a']").nextAll("div[id^='1a']").first().modal('show');
                });

        $(document).ready(function(){
            $('#nextBtn').click(function(){
                alert('next');
                $('.tab-pane').next('li').find('a').trigger('click');

            });


        $(document).ready(function () {
            //@naresh action dynamic childs
            var next = 0;
            $("#add-more").click(function(e){
                e.preventDefault();
                var addto = "#field" + next;
                var addRemove = "#field" + (next);
                next = next + 1;
                var newIn = ' <div id="field'+ next +'" name="field'+ next +'"><!-- Text input-->' +
                    '<div class="form-group"> ' +
                    '<label class="col-md-4 control-label" for="first_name">First Name</label>' +
                    ' <div class="col-md-5"> <input id="first_name" name="first_name" type="text" placeholder="" class="form-control input-md"> </div></div><br><br> <!-- Text input--><div class="form-group"> <label class="col-md-4 control-label" for="mid_name">Middle Name</label> <div class="col-md-5"> <input id="mid_name" name="mid_name" type="text" placeholder="" class="form-control input-md"> </div></div><br><br></div>';
                var newInput = $(newIn);
                var removeBtn = '<button id="remove' + (next - 1) + '" class="btn btn-danger remove-me" >Remove</button></div></div><div id="field">';
                var removeButton = $(removeBtn);
                $(addto).after(newInput);
                $(addRemove).after(removeButton);
                $("#field" + next).attr('data-source',$(addto).attr('data-source'));
                $("#count").val(next);

                $('.remove-me').click(function(e){
                    e.preventDefault();
                    var fieldNum = this.id.charAt(this.id.length-1);
                    var fieldID = "#field" + fieldNum;
                    $(this).remove();
                    $(fieldID).remove();
                });
            });

        });

            $('.btnPrevious').click(function(){
                $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });
        });

    </script>
@endsection
