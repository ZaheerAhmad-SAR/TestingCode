@extends('layouts.app')

@section('title')
    <title> Devices | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    {{--Create Model--}}
    <div class="modal" tabindex="-1" role="dialog" id="createdevices">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="width: inherit; top: auto!important;">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="custom-modal-header gray-background color-black">
                    <p class="modal-title">Add New Device</p>
                </div>
                <form id="postForm" name="postForm" enctype="multipart/form-data" method="POST">
                    <div class="custom-modal-body">
                        <ul  class="nav nav-pills btn">
                            <li>
                                <a  href="#1a" data-toggle="tab" class="active">Info</a>
                            </li>
                            <li>
                                <a href="#2a" data-toggle="tab">Modalities</a>
                            </li>
                        </ul>
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                <div class="tab-pane active" id="1a">
                                    @csrf
                                    <div class="form-group">
                                        <label for="device_name" class="col-md-3">Name</label>
                                        <div class="{!! ($errors->has('device_name')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                            <input type="text" class="form-control" name="device_name" value="{{old('device_name')}}">
                                            @error('device_name')
                                            <span class="text-danger small">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="device_model" class="col-md-3">Device Model</label>
                                        <div class="{!! ($errors->has('device_model')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                            <input type="device_model" class="form-control" name="device_model" value="{{old('device_model')}}"> @error('email')
                                            <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="device_manufacturer" class="col-md-3">Manufacturer</label>
                                        <div class="{!! ($errors->has('device_manufacturer')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                            <input type="device_manufacturer" class="form-control" name="device_manufacturer" value="{{old('device_manufacturer')}}">
                                            @error('device_manufacturer')
                                            <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="2a">
                                    <div class="form-group">
                                        <div class="{!! ($errors->has('roles')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                            <select class="searchable" id="select-modality" multiple="multiple" name="modalities[]">
                                                @foreach($modilities as $modality)
                                                    <option value="{{$modality->id}}">{{$modality->modility_name}}</option>
                                                @endforeach
                                            </select>
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
                            <button class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
                            <button type="submit" id="btn-save" value="create" class="btn custom-btn blue-color"><i class="fa fa-save blue-color"></i> Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--Index Content--}}
    <div class="row">
        <div class="col-lg-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h2>Devices</h2>
                    </div>
                    <div class="pull-right btn-group">
                        {{--<a href="{!! route('studies.create') !!}" class="btn btn-success">Create Study</a>--}}
                        <button type="button" class="btn custom-btn blue-color" data-toggle="modal" data-target="#createdevices">Add Device
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table myTable" id="device_table">
                            <tr>
                                <th>Name</th>
                                <th>Manufacturer</th>
                                <th>Model</th>
                                <th>Action</th>
                            </tr>
                            @foreach($devices as $device)
                                <tr>
                                    <td>{{ucfirst($device->device_name)}}</td>
                                    <td>{{ucfirst($device->device_manufacturer)}}</td>
                                    <td>{{ucfirst($device->device_model)}}</td>
                                    <td>
                                        <ul class="icon-list">
                                            <li>
                                            <a href="javascript:void(0){{ !empty($device->id)?$device->id:'' }}" id="edit" data-toggle="modal"
                                               data-target="#editdevices" data-id="{{ $device->id }}" class="btn btn-sm">
                                                <i class="fal fa-edit"></i></a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Edit Model--}}
   <div class="modal" tabindex="-1" role="dialog" id="editdevices">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="width: inherit; top: auto!important;">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="custom-modal-header gray-background color-black">
                    <p class="modal-title">Edit Device</p>
                </div>
                {{--<form action="{{route('devices.update', $device->id)}}" enctype="multipart/form-data" method="POST" id="editForm">
                   @csrf
                    {{method_field('PUT')}}

                    <div class="custom-modal-body">
                        <ul  class="nav nav-pills btn">
                            <li>
                                <a  href="#1a" data-toggle="tab" class="active">Info</a>
                            </li>
                            <li>
                                <a href="#2a" data-toggle="tab">Modalities</a>
                            </li>
                        </ul>
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                <div class="tab-pane active" id="1a">
                                    <div class="form-group">
                                        <label for="device_name" class="col-md-3">Name</label>
                                        <div class="{!! ($errors->has('device_name')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                            <input type="text" class="form-control" name="device_name" id="device_name" value="{{old('device_name')}}">
                                            @error('device_name')
                                            <span class="text-danger small">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="device_model" class="col-md-3">Device Model</label>
                                        <div class="{!! ($errors->has('device_model')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                            <input type="device_model" class="form-control" name="device_model" id="device_model" value="{{old('device_model')}}"> @error('email')
                                            <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="device_manufacturer" class="col-md-3">Manufacturer</label>
                                        <div class="{!! ($errors->has('device_manufacturer')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                            <input type="device_manufacturer" class="form-control" name="device_manufacturer" id="device_manufacturer" value="{{old('device_manufacturer')}}">
                                            @error('device_manufacturer')
                                            <span class="text-danger small"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="2a">
                                    <div class="form-group">
                                        <div class="{!! ($errors->has('roles')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                            <select class="searchable" id="select-modality" multiple="multiple" name="modalities[]">
                                                @foreach($modilities as $modality)
                                                    <option value="{{$modality->id}}">{{$modality->modility_name}}</option>
                                                @endforeach
                                            </select>
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
                            <button class="btn custom-btn blue-color " data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn custom-btn blue-color btn-save"><i class="fa fa-save blue-color"></i> Update</button>
                        </div>
                    </div>
                </form>--}}
                <p>Delete & Edit is in Progress</p>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script src="http://loudev.com/js/jquery.quicksearch.js" type="text/javascript"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            //$('#select-modality').multiSelect({ keepOrder: true });

            $('.searchable').multiSelect({
                selectableHeader: " <input type='text' class='search-input' autocomplete='on' placeholder='Select Modalities'> <br> <br>",
                selectionHeader: "<input type='text' class='search-input' autocomplete='on' placeholder='Unselect Modalities'> <br><br>",
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
            jQuery('body').on('click', '.open-modal', function () {
                var device_id = $(this).val();
                $.get('devices/' + device_id, function (data) {
                    jQuery('#link_id').val(data.id);
                    jQuery('#device_name').val(data.device_name);
                    jQuery('#btn-save').val("update");
                    jQuery('#linkEditorModal').modal('show');
                })
        });
    </script>
   @endsection


