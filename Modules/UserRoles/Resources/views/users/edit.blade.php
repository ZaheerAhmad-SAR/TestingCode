@extends('layouts.home')
@section('title')
    <title> Update User Roles | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Update Users</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/users')}}">Users</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                @if(session()->has('message'))
                    <div class="col-lg-12 success-alert">
                        <div class="alert alert-primary success-msg" role="alert">
                            {{ session()->get('message') }}
                        </div>
                    </div>
                @endif
                <div class="card">
                    <form action="{{route('users.update',$user->id)}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <nav>
                                <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic Info</a>
                                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Modalities" role="tab" aria-controls="nav-profile" aria-selected="false">Roles</a>
                                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-2fa" role="tab" aria-controls="nav-2fa" aria-selected="false">2 Factor Auth</a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                                    @csrf
                                    <div class="form-group row" style="margin-top: 10px;">
                                        <label for="Name" class="col-md-3">Name</label>
                                        <div class="{!! ($errors->has('name')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                            <input type="text" class="form-control" required="required" id="name" name="name" value="{{$user->name}}">
                                            @error('name')
                                            <span class="text-danger small">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Email" class="col-md-3">Email</label>
                                        <div class="{!! ($errors->has('email')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                            <input type="email" class="form-control" name="email" id="email" required="required" value="{{$user->email}}"> @error('email')
                                            <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                <div class="form-group row">
                                        <label for="password" class="col-md-3">Password</label>
                                        <div class="{!! ($errors->has('password')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">
                                            @error('password')
                                            <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="C-Password" class="col-md-3">Confirm Password</label>
                                        <div class="{!! ($errors->has('password_confirmation')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                                            @error('password_confirmation')
                                            <span class="text-danger small">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Modalities" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                    <div class="form-group row" style="margin-top: 10px;">
                                        <div class="{!! ($errors->has('roles')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                            <select class="searchable" id="select-roles" multiple="multiple" name="roles[]">
                                                @foreach($currentRoles as $role)
                                                    <option selected="selected" value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach
                                            @if(!empty($unassignedRoles))
                                                    @foreach($unassignedRoles as $unassigned)
                                                        <option name="to_be_selected"   class="assignRoles" value="{{$unassigned->id}}">{{$unassigned->name}}</option>
                                                     @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('roles')
                                        <span class="text-danger small">
                                    {{ $message }}
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-2fa" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                    <div class="form-group row" style="margin-top: 10px;">
                                        <div class="{!! ($errors->has('roles')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                            <div class="form-group row" style="margin-top: 10px;">
                                                <label for="2fa" class="col-md-3">2 Factor Auth</label>
                                                <div class="{!! ($errors->has('2fa')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                    <select class="form-control dropdown" name="fa" id="2fa">
                                                        <option value="">Select 2 Factor Status</option>
                                                        <option value="enabled" @if(!empty($user->google2fa_secret))selected @endif>Enabled</option>
                                                        <option value="disabled" @if(empty($user->google2fa_secret))selected @endif>Disabled</option>
                                                    </select>
                                                    @error('name')
                                                    <span class="text-danger small">{{ $message }} </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        @error('roles')
                                        <span class="text-danger small">
                                    {{ $message }}
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            @if(hasPermission(auth()->user(),'users.store'))
                                <button type="submit" class="btn btn-outline-primary" id="btn-save" value="create"><i class="fa fa-save"></i> Save Changes</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')

    <style>
        div.dt-buttons{
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.css" integrity="sha512-2sFkW9HTkUJVIu0jTS8AUEsTk8gFAFrPmtAxyzIhbeXHRH8NXhBFnLAMLQpuhHF/dL5+sYoNHWYYX2Hlk+BVHQ==" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('dist/vendors/datatable/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/vendors/datatable/buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#select-roles').multiSelect({
                selectableHeader: "<label for=''>All Roles</label><input type='text' class='form-control' autocomplete='off' placeholder='search here'>",
                selectionHeader: "<label for=''>Assigned Roles</label><input type='text' class='form-control' autocomplete='off' placeholder='search here'>",
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
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js" integrity="sha512-vSyPWqWsSHFHLnMSwxfmicOgfp0JuENoLwzbR+Hf5diwdYTJraf/m+EKrMb4ulTYmb/Ra75YmckeTQ4sHzg2hg==" crossorigin="anonymous"></script>
    <script src="http://loudev.com/js/jquery.quicksearch.js" type="text/javascript"></script>
@endsection
