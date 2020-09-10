@extends ('layouts.home')
@section('title')
    <title> Studies | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Studies List</h4></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">App</li>
                        <li class="breadcrumb-item active"><a href="#">Studies List</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row row-eq-height">
            <div class="col-12 col-lg-2 mt-3 todo-menu-bar flip-menu pr-lg-0">
                <a href="#" class="d-inline-block d-lg-none mt-1 flip-menu-close"><i class="icon-close"></i></a>
                <div class="card border h-100 contact-menu-section">
                    <div class="card-header d-flex justify-content-between align-items-center">

                        <a href="#"  class="bg-primary py-2 px-2 rounded ml-auto text-white w-100 text-center" data-toggle="modal" data-target="#newcontact">
                            <i class="icon-plus align-middle text-white"></i> <span class="d-none d-xl-inline-block">Add New Study</span>
                        </a>
                        <!-- Add Contact -->
                        <div class="modal fade" id="newcontact">
                            <div class="modal-dialog modal-dialog-centered" style="max-width: 1000px;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="icon-plus"></i> Add Study
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                    <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic Info</a>
                                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Users" role="tab" aria-controls="nav-profile" aria-selected="false">Users</a>
                                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Sites" role="tab" aria-controls="nav-profile" aria-selected="false">Sites</a>
                                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Disease" role="tab" aria-controls="nav-profile" aria-selected="false">Disease Cohort</a>
                                    </div>
                                    <form class="add-contact-form">
                                        <div class="modal-body">
                                        <div class="tab-content" id="nav-tabContent">
                                            {{-- Basic Info Tab --}}
                                            <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6" style="margin-top: 10px;">
                                                        <label for="study_short_name" class="col-md-3">Short Name</label>
                                                        <div class="{!! ($errors->has('study_short_name')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                            <input type="text" class="form-control" id="study_short_name" name="study_short_name" value="{{old('study_short_name')}}">
                                                            @error('study_short_name')
                                                            <span class="text-danger small">{{ $message }} </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="study_title" class="col-md-3">Title</label>
                                                        <div class="{!! ($errors->has('study_title')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                            <input type="text" class="form-control" id="study_title" name="study_title" value="{{old('study_title')}}"> @error('email')
                                                            <span class="text-danger small"> {{ $message }} </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                <div class="col-md-6">
                                                    <label for="study_code" class="col-md-3">Study Code</label>
                                                    <div class="{!! ($errors->has('study_code')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                        <input type="text" class="form-control" id="study_code" name="study_code" value="{{old('study_code')}}">
                                                        @error('study_code')
                                                        <span class="text-danger small"> {{ $message }} </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="protocol_number" class="col-md-3">Protocol Number</label>
                                                    <div class="{!! ($errors->has('protocol_number')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                        <input type="text" class="form-control" id="protocol_number" name="protocol_number" value="{{old('protocol_number')}}">
                                                        @error('protocol_number')
                                                        <span class="text-danger small"> {{ $message }} </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="row">
                                                <div class="col-md-6">
                                                    <label for="trial_registry_id" class="col-md-3">Trial Registry ID</label>
                                                    <div class="{!! ($errors->has('trial_registry_id')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                        <input type="text" class="form-control" id="trial_registry_id" name="trial_registry_id" value="{{old('trial_registry_id')}}">
                                                        @error('trial_registry_id')
                                                        <span class="text-danger small"> {{ $message }} </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="study_sponsor" class="col-md-3">Study Sponsor</label>
                                                    <div class="{!! ($errors->has('study_sponsor')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                        <input type="text" class="form-control" id="study_sponsor" name="study_sponsor" value="{{old('study_sponsor')}}">
                                                        @error('study_sponsor')
                                                        <span class="text-danger small"> {{ $message }} </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="row">
                                                <div class="col-md-6">
                                                    <label for="start_date" class="col-md-3">Start Date</label>
                                                    <div class="{!! ($errors->has('start_date')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{old('start_date')}}">
                                                        @error('start_date')
                                                        <span class="text-danger small"> {{ $message }} </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="end_date" class="col-md-3">End Date</label>
                                                    <div class="{!! ($errors->has('end_date')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{old('end_date')}}">
                                                        @error('end_date')
                                                        <span class="text-danger small"> {{ $message }} </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="row">
                                                <div class="col-md-6">
                                                    <label for="description" class="col-md-3">Description</label>
                                                    <div class="{!! ($errors->has('description')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                        <input type="text" class="form-control" id="description" name="description" value="{{old('description')}}">
                                                        @error('description')
                                                        <span class="text-danger small"> {{ $message }} </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            {{-- Users Tab --}}
                                            <div class="tab-pane fade" id="nav-Users" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                                <div class="form-group row" style="margin-top: 10px;">
                                                    <div class="{!! ($errors->has('roles')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                                        <select class="searchable form-control" id="users" multiple="multiple" name="users[]">
                                                            @foreach($users as $user)
                                                                <option value="{{$user->id}} {{!empty($user->id?$user->name:'')}}">{{$user->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('modalities')
                                                    <span class="text-danger small">
                                    {{ $message }}
                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{--Sites tab --}}
                                            <div class="tab-pane fade" id="nav-Sites" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                                <div class="form-group row" style="margin-top: 10px;">
                                                    <div class="{!! ($errors->has('sites')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                        <select class="searchable form-control" id="sites" multiple="multiple" name="sites[]">
                                                            @foreach($sites as $site)
                                                                {{$site}}
                                                                <option value="{{$site->id}}" {{ $site->site_name === $site->site_name? 'selected' : '' }}>{{$site->site_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('sites')
                                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{--Disease tab --}}
                                            <div class="tab-pane fade" id="nav-Disease" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                                <div class="field_wrapper form-group row" style="margin-top: 10px;">
                                                    <label for="disease_cohort" class="col-md-3">Disease Cohort</label>
                                                    <div class="{!! ($errors->has('disease_cohort')) ?'form-group col-md-6 has-error ':'form-group col-md-6' !!}">
                                                        <input type="text" class="form-control" id="disease_cohort" name="disease_cohort[]" value="{{old('disease_cohort')}}">
                                                        @error('disease_cohort')
                                                        <span class="text-danger small">{{ $message }} </span>
                                                        @enderror
                                                    </div>
                                                    <a href="javascript:void(0);" class="add_button" title="Add field"> <i class="fa fa-plus"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                                            <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Add Study</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Edit Contact -->
                        <div class="modal fade" id="editcontact">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="icon-pencil"></i> Edit Contact
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                    <form class="edit-contact-form">
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="contact-name">
                                                        <label for="edit-contact-name" class="col-form-label">Name</label>
                                                        <input type="text" id="edit-contact-name" class="form-control" required="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="contact-email">
                                                        <label for="edit-contact-email" class="col-form-label">Email</label>
                                                        <input type="text" id="edit-contact-email" class="form-control" required="">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="contact-occupation">
                                                        <label for="edit-contact-occupation" class="col-form-label">Occupation</label>
                                                        <input type="text" id="edit-contact-occupation" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="contact-phone">
                                                        <label for="edit-contact-phone" class="col-form-label">Phone</label>
                                                        <input type="text" id="edit-contact-phone" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="contact-location">
                                                        <label for="edit-contact-location" class="col-form-label">Location</label>
                                                        <input type="text" id="edit-contact-location" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden"  id="edit-date">
                                            <button type="submit" class="btn btn-primary add-todo">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                    <ul class="nav flex-column contact-menu">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-contacttype="contact">
                                <i class="icon-list"></i> All Studies
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-contacttype="family-contact">
                                <i class="icon-people"></i> Live Studies
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-contacttype="friend-contact">
                                <i class="icon-user-follow"></i> Development Studies
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="#" data-contacttype="office-contact">
                                <i class="icon-check"></i> Archived Studies
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
            <div class="col-12 col-lg-10 mt-3 pl-lg-0">
                <div class="card border h-100 contact-list-section">
                    <div class="card-header border-bottom p-1 d-flex">
                        <a href="#" class="d-inline-block d-lg-none flip-menu-toggle"><i class="icon-menu"></i></a>
                        <input type="text" class="form-control border-0 p-2 w-100 h-100 contact-search" placeholder="Search ...">
                        <a href="#" class="list-style search-bar-menu border-0 active"><i class="icon-list"></i></a>
                        <a href="#" class="grid-style search-bar-menu"><i class="icon-grid"></i></a>
                    </div>
                    <div class="card-body p-0">

                        <div class="contacts list">
                                @if(count($studies) !=0)
                                    <?php $index= 1 ?>
                                    @foreach($studies as $study)
                                            <div class="contact family-contact">
                                                <div class="contact-content">
                                                    <div class="contact-email">
                                        <p class="mb-0 small">ID: </p>
                                        <p class="user-email">{{$index}}</p>
                                    </div>
                                                    <div class="contact-profile">
                                        <div class="contact-info">
                                            <p class="contact-name mb-0">Short Name : <strong>Study Title</strong>
                                                <br>
                                                <br>Sponsor</p>
                                            <p class="contact-position mb-0 small font-weight-bold text-muted">
                                                <a class="" href="{{ route('studies.show', $study->id) }}">
                                                    {{ucfirst($study->study_short_name)}} : <strong>{{ucfirst($study->study_title)}}</strong>
                                                </a>
                                                <br></p><p style="font-size: 14px; font-style: oblique">Sponsor: <strong>{{ucfirst($study->study_sponsor)}}</strong></p>
                                        </div>
                                    </div>
                                                    <div class="contact-location">
                                        <p class="mb-0 small">Status: </p>
                                        <p class="user-location">Washington</p>
                                    </div>
                                                    <div class="line-h-1 h5">
                                        <p class="mb-0 small">Action: </p>
                                        <a class="text-success edit-contact" href="#" data-toggle="modal" data-target="#edittask"><i class="icon-pencil"></i></a>
                                        <a class="text-danger delete-contact" href="#"><i class="icon-trash"></i></a>
                                    </div>
                                                </div>
                                            </div>
                                            <?php $index++ ?>
                                        @endforeach
                            @elseif(count($studies) == 0)
                                <p>No records</p>
                            @endif


                    </div>
                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
@stop

@section('script')
    <script src="{{ asset("public/dist/js/app.contactlist.js") }}"></script>
@stop
