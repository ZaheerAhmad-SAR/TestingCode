@extends('layouts.home')
@section('title')
    <title> Update User Profile | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')

    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Update User Profile</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/users')}}">Users</a></li>
                    </ol>
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
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <form action="{{route('users.updateUser',$user->id)}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('GET')
                        <div class="modal-body">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-md-2">
                                    <label for="Name">Title</label>
                                    </div>
                                    <div class="{!! ($errors->has('name')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                        <select class="form-control dropdown" name="title">
                                            <option value="">Select Title</option>
                                            <option value="mr">Mr.</option>
                                            <option value="mrs">Mrs.</option>
                                            <option value="miss">Miss</option>
                                        </select>
                                        @error('name')
                                        <span class="text-danger small">{{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="Name">Name</label>
                                    </div>
                                        <div class="{!! ($errors->has('name')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                            <input type="text" class="form-control" required="required" id="name" name="name" value="{{$user->name}}">
                                            @error('name')
                                            <span class="text-danger small">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                        <label for="Email">Email</label>
                                        </div>
                                        <div class="{!! ($errors->has('email')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                            <input type="email" class="form-control" name="email" id="email" disabled required="required" value="{!! $user->email !!}"> @error('email')
                                            <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <label for="Phone">Phone</label>
                                        </div>
                                        <div class="{!! ($errors->has('phone')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                            <input type="text" class="form-control" name="phone" id="phone" required="required" value="{!! $user->phone !!}"> @error('phone')
                                            <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
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
        $(document).ready(function(){
            var tId;
            tId=setTimeout(function(){
               $(".success-alert").slideUp('slow');
            }, 4000);
        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js" integrity="sha512-vSyPWqWsSHFHLnMSwxfmicOgfp0JuENoLwzbR+Hf5diwdYTJraf/m+EKrMb4ulTYmb/Ra75YmckeTQ4sHzg2hg==" crossorigin="anonymous"></script>
    <script src="http://loudev.com/js/jquery.quicksearch.js" type="text/javascript"></script>
@endsection
