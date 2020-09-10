@extends('layouts.app')
@section('title')
    <title> Create Site | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
<div class="container"><h1>Site Info  </h1></div>
<div id="exTab1" class="container">
    <ul  class="nav nav-pills">
        <li class="active">
            <a  href="#1a" data-toggle="tab">Site Info</a>
        </li>
        <li><a href="#2a" data-toggle="tab">PI</a>
        </li>
        <li><a href="#3a" data-toggle="tab">Coordinator</a>
        </li>
        <li><a href="#4a" data-toggle="tab">Photographer</a>
        </li>

        <li><a href="#5a" data-toggle="tab">Other</a>
        </li>
    </ul>

    <div class="tab-content clearfix">
        <div class="tab-pane active" id="1a">
            <form action="{{route('sites.store')}}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h2>Create Site</h2>
                            </div>
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="{!! ($errors->has('name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                            <label>Site Name</label>
                                            <input type="input" class="form-control" name="site_name" value="{{old('site_name')}}">
                                            @error('site_name')
                                            <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                            <label>Manager</label>
                                            <input type="text" class="form-control" name="site_manager">{{old('site_manager')}}</input>
                                        </div>
                                        @error('site_manager')
                                        <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                            <label>Address</label>
                                            <input type="text" class="form-control" name="site_address">{{old('site_address')}}</input>
                                        </div>
                                        @error('site_address')
                                        <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                            <label>City</label>
                                            <input type="text" class="form-control" name="site_city">{{old('site_city')}}</input>
                                        </div>
                                        @error('site_city')
                                        <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                            <label>State</label>
                                            <input type="text" class="form-control" name="site_state">{{old('site_state')}}</input>
                                        </div>
                                        @error('site_state')
                                        <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                            <label>Phone</label>
                                            <input type="text" class="form-control" name="site_phone">{{old('site_phone')}}</input>
                                        </div>
                                        @error('site_phone')
                                        <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
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
                                <a href="{!! route('roles.index') !!}" class="btn btn-danger">Cancel</a>
                                <button type="submit" class="btn btn-success">Create</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>

        <div class="tab-pane" id="2a">
           {{-- <a href="{!! route('primaryinvestigator.create') !!}" class="btn btn-primary">Add Primary Investigator</a>--}}
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#p-investigator">Add New</button>
            <div class="row">
            <div class="modal" role="dialog" id="p-investigator">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Add Primary Investigator</h3>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="">
                            <form action="{{route('primaryinvestigator.store')}}" enctype="multipart/form-data" method="POST">
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
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    @if(!empty($pinvestigator)){
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Actions</th>
                        </tr>
                        @foreach($pinvestigator as $investigator)
                            <tr>
                                <td>{{ucfirst($investigator->first_name)}}</td>
                                <td>{{ucfirst($investigator->mid_name)}}</td>
                                <td>{{ucfirst($investigator->last_name)}}</td>
                                <td>{{$investigator->phone}}</td>
                                <td>{{$investigator->email}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    <ul class="icon-list">
                                        <li><a href="{!! route('users.edit',encrypt($user->id)) !!}" ><i class="fal fa-edit"></i></a></li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                        }
                    @else
                        <p> No data avaiable for Primary Investigator</p>
                    @endif
                </div>
            </div>

        </div>
        <div class="tab-pane" id="3a">
           {{-- <a href="{!! route('coordinator.create') !!}" class="btn btn-primary">Add Coordinator</a>--}}
           <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#coordinator">Add New</button>

            <div class="modal" role="dialog" id="coordinator">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Add Coordinator</h3>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="">
                            <form action="{{route('coordinator.store')}}" enctype="multipart/form-data" method="POST">
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
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="table-responsive">
                    @if(!empty($coordinators)){
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Actions</th>
                        </tr>
                        @foreach($coordinators as $coordinator)
                            <tr>
                                <td>{{ucfirst($coordinator->first_name)}}</td>
                                <td>{{ucfirst($coordinator->mid_name)}}</td>
                                <td>{{ucfirst($coordinator->last_name)}}</td>
                                <td>{{$coordinator->phone}}</td>
                                <td>{{$coordinator->email}}</td>
                                <td>
                                    <ul class="icon-list">
                                        <li>{{--<a href="{!! route('users.edit',encrypt($user->id)) !!}" ><i class="fal fa-edit"></i></a>--}}</li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    }
                @else
                        <p> No data avaiable for coordinator</p>

                    @endif

                </div>
            </div>

        </div>
        <div class="tab-pane" id="4a">
            {{--<a href="{!! route('photographers.create') !!}" class="btn btn-primary">Add Primary Investigator</a>--}}
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#photographer">Add New</button>

            <div class="modal" role="dialog" id="photographer">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Add Photographer</h3>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="">
                            <form action="{{route('photographers.store')}}" enctype="multipart/form-data" method="POST">
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
                                <button type="submit" class="btn">Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    @if(!empty($photographers)){
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Actions</th>
                        </tr>
                        @foreach($photographers as $photographer)
                            <tr>
                                <td>{{ucfirst($photographer->first_name)}}</td>
                                <td>{{ucfirst($photographer->mid_name)}}</td>
                                <td>{{ucfirst($photographer->last_name)}}</td>
                                <td>{{$photographer->phone}}</td>
                                <td>{{$photographer->email}}</td>
                                <td>
                                    <ul class="icon-list">
                                        <li><a href="{!! route('users.edit',encrypt($user->id)) !!}" ><i class="fal fa-edit"></i></a></li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    }
                    @else
                        <p> No data avaiable for Photographers</p>


                    @endif
                </div>
            </div>
        </div>
        <div class="tab-pane" id="5a">
            <h3>Form will show here for other</h3>
            <button type="button" class="btn btn-primary">Add New</button>
            <div class="modal" role="dialog" id="p-investigator">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Add Primary Investigator</h3>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-content">
                            <form>
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
                                <button type="submit" class="btn">Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

@endsection
@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    @endsection
