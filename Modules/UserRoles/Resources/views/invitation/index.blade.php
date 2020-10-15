@extends('layouts.home')
@section('title')
    <title> Invite User | {{ config('app.name', 'Laravel') }}</title>
@endsection
@section('content')

    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Invite User</h4></div>
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
                    <form action="{{ route('invitation.invite') }}" method="post">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="form-group row" style="margin-top: 10px">
                                    <div class="col-md-2">
                                        <label for="avatar" class="">Enter Email</label>
                                    </div>
                                    <div class="{!! ($errors->has('email')) ?'form-group col-md-4 has-error':'form-group col-md-4' !!}">
                                    <input type="email" name="email" class="form-control" required="required"/>
                                        @error('email')
                                        <span class="text-danger small">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-primary">Send invite</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
