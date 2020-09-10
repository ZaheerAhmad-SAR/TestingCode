@extends('layouts.home')

@section('title')
    <title> Devices | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">App Contact List</h4></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Devices</li>
                        <li class="breadcrumb-item active"><a href="#">Contact List</a></li>
                    </ol>
                </div>

                <a href="javascript:void(0)" class="bg-primary py-2 px-2 rounded ml-auto text-white w-100 text-center"
                        id="create-new-device" data-toggle="modal" data-target="#createdevices">
                    <i class="icon-plus align-middle text-white"></i> Add Device
                </a>
                </div>
            </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
                        <!-- Add Contact -->
                        <div class="modal fade" id="createdevices">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="icon-pencil"></i> Add Contact
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i class="icon-close"></i>
                                        </button>
                                    </div>
                                    <form class="add-contact-form">
                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="contact-name">
                                                        <label for="contact-name" class="col-form-label">Name</label>
                                                        <input type="text" id="contact-name" class="form-control" required="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="contact-email">
                                                        <label for="contact-email" class="col-form-label">Email</label>
                                                        <input type="text" id="contact-email" class="form-control" required="">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="contact-occupation">
                                                        <label for="contact-occupation" class="col-form-label">Occupation</label>
                                                        <input type="text" id="contact-occupation" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="contact-phone">
                                                        <label for="contact-phone" class="col-form-label">Phone</label>
                                                        <input type="text" id="contact-phone" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="contact-location">
                                                        <label for="contact-location" class="col-form-label">Location</label>
                                                        <input type="text" id="contact-location" class="form-control">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary add-todo">Add Contact</button>
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

            <div class="col-12 mt-3 pl-lg-0">
                <div class="card border h-100 contact-list-section">
                    <div class="card-header border-bottom p-1 d-flex">
                        <a href="#" class="d-inline-block d-lg-none flip-menu-toggle"><i class="icon-menu"></i></a>
                        <input type="text" class="form-control border-0 p-2 w-100 h-100 contact-search" placeholder="Search ...">
                        <a href="#" class="list-style search-bar-menu border-0 active"><i class="icon-list"></i></a>
                        <a href="#" class="grid-style search-bar-menu"><i class="icon-grid"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <div class="card-body p-0">
                            <div class="contacts list">
                                <div class="contact family-contact">
                                    <div class="contact-content">
                                        <table class="table table-bordered dataTable" id="laravel_crud">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Model</th>
                                                <th>Manufacturer</th>
                                                <td colspan="2">Action</td>
                                            </tr>
                                            </thead>
                                            <tbody id="devices-crud">
                                            @foreach($devices as $device)
                                                <tr id="device_id_{{ $device->id }}">
                                                    <td>{{ $device->device_name }}</td>
                                                    <td>{{ $device->device_model }}</td>
                                                    <td>{{ $device->device_manufacturer }}</td>
                                                    <td>
                                                        <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                            <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                                            <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                    <span class="dropdown-item">
                                                        <a href="javascript:void(0)" id="edit-device" data-id="{{ $device->id }}">
                                                            <i class="far fa-edit"></i>&nbsp; Edit </a>
                                                    </span>
                                                                <span class="dropdown-item">
                                                        <a href="javascript:void(0)" id="delete-device" data-id="{{ $device->id }}">
                                                            <i class="fa fa-trash delete-device"> Delete </i>
                                                        </a>
                                                    </span>
                                                                <span>
                                                    <a class="text-success edit-contact" href="#" data-toggle="modal" data-target="#edittask"><i class="icon-pencil"></i></a>
                                        <a class="text-danger delete-contact" href="#"><i class="icon-trash"></i></a>
                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                        {{ $devices->links() }}
                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <!-- END: Card DATA-->
    </div>
@stop

@section('script')
    <script src="{{ asset("dist/js/app.devicelist.js") }}"></script>
@stop
