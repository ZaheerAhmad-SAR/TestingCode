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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Studies Detail</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Studies</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-primary" id="create-new-study" data-toggle="modal" data-target="#createStudy">
                            <i class="fa fa-plus"></i> Add Study
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Short Name : <strong>Study Title</strong>
                                        <br>
                                        <br>Sponsor</th>
                                    <th>Action</th>
                                </tr>
                                @if(count($studies) !=0)
                                    <?php $index= 1 ?>
                                    @foreach($studies as $study)
                                        <tr>
                                            <td>{{$index}}</td>
                                            <td hidden="hidden" name="{{$study->study_short_name}}"class="studyID">{{$study->id}}</td>
                                            <td class="status">{{$study->study_status}}</td>
                                            <td class="fa-box">
                                                <a class="" href="{{ route('studies.show', $study->id) }}">
                                                    {{ucfirst($study->study_short_name)}} : <strong>{{ucfirst($study->study_title)}}</strong>
                                                </a>
                                                <br><br><p style="font-size: 14px; font-style: oblique">Sponsor: <strong>{{ucfirst($study->study_sponsor)}}</strong></p>
                                            </td>
                                            <td>
                                                <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                    <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                    <span class="dropdown-item">
                                                        <a href="{{route('studies.changeStatus',$study->id)}}" data-id="{{$study->id}}"  class="studyStatus" data-toggle="modal" data-target="#changeStatus-{{$study->id}}">
                                                            <i class="fa fa-file-plus"></i> Change Status
                                                        </a>
                                                    </span>
                                                        <span class="dropdown-item">
                                                            <a href="{{route('studies.edit',$study->id)}}" data-toggle="modal" class="editStudy" at="">
                                                                <i class="icon-pencil" aria-hidden="true"></i> Edit
                                                            </a>
                                                        </span>
                                                        <span class="dropdown-item">
                                                            <a href="#">
                                                                <i class="fa fa-clone" aria-hidden="true"></i> Clone </a>
                                                        </span>
                                                        <span class="dropdown-item">
                                                            <a href="#" data-id="" class="deleteParent">
                                                                <i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
                                                        </span>
                                                    </div>
                                                </div>
                                        {{--<li>
                                            <a href="#">
                                            <i class="fal fa-file-edit"></i>
                                                <select class="studyStatus"  name="Status" >
                                            <option>Select Status</option>
                                            <option value="Live">Live</option>
                                            <option value="Development" >Development</option>
                                            <option value="Archived">Archived</option>
                                            --}}{{--<option value="{{!empty($study->study_status)?$study->study_status:'dev'}}" >Development</option>--}}{{--
                                        </select>
                                            </a>
                                        </li>--}}
                                        <li>
                                            <a href="#" data-id="" class="addModalities">
                                                <i class="fa fa-object-group" aria-hidden="true"></i> Preferences
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" data-id="" class="addModalities">
                                                <i class="fa fa-object-group" aria-hidden="true"></i> Modalities
                                            </a>
                                        </li>
                                    </ul>
                                                </li>
                                            </ul>
                                        </span>
                                    </td>
                                        </tr>
                                        <?php $index++ ?>
                                    @endforeach
                                @elseif(count($studies) == 0)
                                    <p>No records</p>
                                @endif
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="createStudy">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="width: inherit; top: auto!important;">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="custom-modal-header gray-background color-black">
                    <p class="modal-title">Add New Study</p>
                </div>
                <form action="{{route('studies.store')}}" enctype="multipart/form-data" method="POST">
                    <div class="custom-modal-body">
                        <ul  class="nav nav-pills btn">
                            <li>
                                <a  href="#1a" data-toggle="tab" class="active">Info</a>
                            </li>
                            <li>
                                <a href="#2a" data-toggle="tab">Users</a>
                            </li>
                            <li>
                                <a href="#3a" data-toggle="tab">Sites</a>
                            </li>
                            <li>
                                <a  href="#4a" data-toggle="tab" class="">Disease Cohort</a>
                            </li>

                        </ul>
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                <div class="tab-pane active" id="1a">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="study_short_name" class="col-md-3">Short Name</label>
                                            <div class="{!! ($errors->has('study_short_name')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="study_short_name" value="{{old('study_short_name')}}">
                                                @error('study_short_name')
                                                <span class="text-danger small">{{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="study_title" class="col-md-3">Title</label>
                                            <div class="{!! ($errors->has('study_title')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="study_title" value="{{old('study_title')}}"> @error('email')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="study_code" class="col-md-3">Study Code</label>
                                            <div class="{!! ($errors->has('study_code')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="study_code" value="{{old('study_code')}}">
                                                @error('study_code')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="protocol_number" class="col-md-3">Protocol Number</label>
                                            <div class="{!! ($errors->has('protocol_number')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="protocol_number" value="{{old('protocol_number')}}">
                                                @error('protocol_number')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="trial_registry_id" class="col-md-3">Trial Registry ID</label>
                                            <div class="{!! ($errors->has('trial_registry_id')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="trial_registry_id" value="{{old('trial_registry_id')}}">
                                                @error('trial_registry_id')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="study_sponsor" class="col-md-3">Study Sponsor</label>
                                            <div class="{!! ($errors->has('study_sponsor')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="study_sponsor" value="{{old('study_sponsor')}}">
                                                @error('study_sponsor')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="start_date" class="col-md-3">Start Date</label>
                                            <div class="{!! ($errors->has('start_date')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="date" class="form-control" name="start_date" value="{{old('start_date')}}">
                                                @error('start_date')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="end_date" class="col-md-3">End Date</label>
                                            <div class="{!! ($errors->has('end_date')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="date" class="form-control" name="end_date" value="{{old('end_date')}}">
                                                @error('end_date')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="description" class="col-md-3">Description</label>
                                            <div class="{!! ($errors->has('description')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                                <input type="text" class="form-control" name="description" value="{{old('description')}}">
                                                @error('description')
                                                <span class="text-danger small"> {{ $message }} </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="2a">
                                    <div class="form-group">
                                        <div class="{!! ($errors->has('users')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                            <select class="searchable" id="select-users" multiple="multiple" name="users[]">
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('users')
                                        <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="tab-pane" id="3a">
                                    <div class="form-group">
                                        <div class="{!! ($errors->has('sites')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                            <select class="searchable" id="select-sites" multiple="multiple" name="sites[]">
                                                @foreach($sites as $site)
                                                    {{$site}}
                                                    <option value="{{$site->id}}">{{$site->site_name}}</option>
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
                                <div class="tab-pane" id="4a">
                                    <div class="row field_wrapper">
                                        <div class="col-md-6">
                                            <label for="disease_cohort" class="col-md-3">Disease Cohort</label>
                                            <div class="{!! ($errors->has('disease_cohort')) ?'form-group col-md-6 has-error ':'form-group col-md-6' !!}">
                                                <input type="text" class="form-control" id="disease_cohort" name="disease_cohort[]" value="{{old('disease_cohort')}}">
                                                @error('disease_cohort')
                                                <span class="text-danger small">{{ $message }} </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <a href="javascript:void(0);" class="add_button" title="Add field"> <i class="fa fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
                                        <button type="submit" class="btn custom-btn blue-color"><i class="fa fa-save blue-color"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ChangeStatus Modal--}}
    <div class="modal" tabindex="-1" role="dialog" id="changeStatus_Modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="width: inherit; top: auto!important;">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="custom-modal-header gray-background color-black">
                    <p class="modal-title">Change Status</p>
                </div>
                <form action="{{route('study.studyStatus')}}" enctype="multipart/form-data" method="POST">
                    <div class="custom-modal-body">
                        @csrf
                        <input type="hidden" name="study_id", value="{{$study->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="col-md-3">Study Status</h4>
                                <div class="col-md-9">
                                    <input type="radio" name="study_status" value="live"> Live
                                    <br>
                                    <input type="radio" name="study_status" value="development"> Development
                                    <br>
                                    <input type="radio" name="study_status" value="archived"> Archived
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn custom-btn blue-color"><i class="fa fa-save blue-color"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Edit Modal--}}
    <div class="modal" tabindex="-1" role="dialog" id="editStudy_Modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="width: inherit; top: auto!important;">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="custom-modal-header gray-background color-black">
                    <p class="modal-title">Edit Study</p>
                </div>
                <form action="{{route('studies.update',$study->id)}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="custom-modal-body">
                        <ul  class="nav nav-pills btn">
                            <li>
                                <a  href="#edit-1a" data-toggle="tab" class="active">Info</a>
                            </li>
                            <li>
                                <a href="#edit-2a" data-toggle="tab">Users</a>
                            </li>
                            <li>
                                <a href="#edit-3a" data-toggle="tab">Sites</a>
                            </li>
                            <li>
                                <a  href="#edit-4a" data-toggle="tab" class="">Disease Cohort</a>
                            </li>

                        </ul>
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                <div class="tab-pane active" id="edit-1a">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('study_short_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                <label>Study Short Name</label>
                                                <input type="text" class="form-control" name="study_short_name" value="{{$study->study_short_name}}">
                                                @error('study_short_name')
                                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('study_title')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                <label>Study Title</label>
                                                <input type="text" class="form-control" name="study_title" value="{{$study->study_title}}">
                                                @error('study_title')
                                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('study_code')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                <label>Study Code</label>
                                                <input type="text" class="form-control" name="study_code" value="{{$study->study_code}}">
                                                @error('study_code')
                                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('protocol_number')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                <label>Protocol Number</label>
                                                <input type="text" class="form-control" name="protocol_number" value="{{$study->protocol_number}}">
                                                @error('protocol_number')
                                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('trial_registry_id')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                <label>Trial Registry ID</label>
                                                <input type="text" class="form-control" name="trial_registry_id" value="{{$study->trial_registry_id}}">
                                                @error('trial_registry_id')
                                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('start_date')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                <label>Start Date</label>
                                                <input type="date" class="form-control" name="start_date" value="{{$study->start_date}}">
                                                @error('start_date')
                                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('end_date')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                <label>End Date</label>
                                                <input type="date" class="form-control" name="end_date" value="{{$study->end_date}}">
                                                @error('end_date')
                                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                                <label>Description</label>
                                                <textarea class="form-control" name="description">{{$study->description}}</textarea>
                                            </div>
                                            @error('description')
                                            <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="edit-2a">
                                    <div class="form-group">
                                        <div class="{!! ($errors->has('users')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                            <select class="searchable" id="edit-select-users" multiple="multiple" name="editusers[]">
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('users')
                                        <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="tab-pane" id="edit-3a">
                                    <div class="form-group">
                                        <div class="{!! ($errors->has('users')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                            <select class="searchable" id="edit-select-sites" multiple="multiple" name="editsites[]">
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('users')
                                        <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="tab-pane" id="edit-4a">
                                    <div class="pull-right">
                                        <a href="{!! route('studies.index') !!}" class="btn btn-danger">Cancel</a>
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="http://loudev.com/js/jquery.quicksearch.js" type="text/javascript"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            $('#select-users').multiSelect({
                selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search'>",
                selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search'>",
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
        $(document).ready(function() {
            $('body').on('click','.editStudy',function () {
                $('#editStudy_Modal').modal('show');
                var row = $(this).closest('tr');
                var id = row.find('td.studyID').text();
                alert(id);
            })
        });
        $(document).ready(function() {

            $('#select-sites').multiSelect({
                selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search'>",
                selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search'>",
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

        $(document).on('click','.studyStatus',function () {
            $('#changeStatus_Modal').modal('show');
            var row = $(this).closest('tr');
            var ID= $(this).attr("data-id")
            var id = row.find('td.studyID').text();
            var status = row.find('select.studyStatus').val();
            var t;
            $.ajax({
                url:'{{url('studies/studyStatus')}}',
                type:'POST',
                data:{
                    '_token':"{{csrf_token()}}",
                    'study_id':id,
                    'status':status
                },
                success:function () {
                    console.log(data);
                }
            })
        });
        /*$('body').on('change','.studyStatus',function(){
           var row = $(this).closest('tr');
           var id = row.find('td.studyID').text();
            var status = row.find('select.studyStatus').val();
            alter(id,status);
            var t;
           $.ajax({
               url:'studyStatus',
               type:'POST',
               dataType:'json',
               data:{'_token':"{{csrf_token()}}",'id':id,'status':status},
               success:function (res) {
                    if(res.success == true){
                        var t = setTimeout(function(){// wait for -- secs(2)
                            location.reload();
                        }, 1000);
                    }
               }
           })
        });*/

        $(document).ready(function(){
            var maxField = 8; //Input fields increment limitation
            var x = 1; //Initial field counter is 1
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper
            //Once add button is clicked
            $(addButton).click(function(){
                //Check maximum number of input fields
                if(x < maxField){
                    var fieldHTML = '<div class="col-md-6"><label for="disease_cohort" class="col-md-3">' + (x + 1) +
                        '</label><div class="{!! ($errors->has('disease_cohort')) ?'form-group col-md-6 has-error ':'form-group col-md-6' !!}"><input type="text" class="form-control" id="disease_cohort" name="disease_cohort[]" value="{{old('disease_cohort')}}"></div><a href="javascript:void(0);" class="remove_button"><i class="fa fa-minus"></i></a></div>'; //New input field html

                    x++; //Increment field counter
                    $(wrapper).append(fieldHTML); //Add field html
                }
            });

            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x--; //Decrement field counter
            });
        });

    </script>
@endsection
