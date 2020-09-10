@extends ('layouts.home')
@section('title')
    <title> Studies | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('script')
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

        $(document).ready(function(){
            var maxField = 8; //Input fields increment limitation
            var x = 1; //Initial field counter is 1
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper
            //Once add button is clicked
            $(addButton).click(function(){
                //Check maximum number of input fields
                if(x < maxField){
                   var fieldHTML = '<div class="form-group row"><div class="col-md-3"><label for="disease_cohort"> ' + (x + 1) +
                        '</label></div><div class="{!! ($errors->has('disease_cohort')) ?'col-md-6 has-error ':'col-md-6' !!}"><input type="text" class="form-control" id="disease_cohort" name="disease_cohort[]" value="{{old('disease_cohort')}}"></div><a href="javascript:void(0);" class="remove_button"><i class="fa fa-minus"></i></a></div>'; //New input field html

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

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#create-new-study').click(function () {
                $('#btn-save').val("create-study");
                $('#studyForm').trigger("reset");
                $('#studyCrudModal').html("New Study");
                $('#study-crud-modal').modal('show');
            });

            $('body').on('click', '#edit-study', function () {
                var study_id = $(this).data('id');
                alert(study_id);
                $.get('studies/'+study_id+'/edit', function (data) {
                    $('#studyCrudModal').html("Edit Study");
                    $('#btn-save').val("edit-study");
                    $('#study-crud-modal').modal('show');
                    $('#study_id').val(data.id);
                    $('#study_short_name').val(data.study_short_name);
                    $('#study_title').val(data.study_title);
                    $('#study_code').val(data.study_code);
                    $('#protocol_number').val(data.protocol_number);
                    $('#trial_registry_id').val(data.trial_registry_id);
                    $('#study_sponsor').val(data.study_sponsor);
                    $('#start_date').val(data.start_date);
                    $('#end_date').val(data.end_date);
                    $('#description').val(data.description);
                    $('#users').val(data.users);
                    $('#sites').val(data.sites);
                    $('#disease_cohort').val(data.disease_cohort);
                })
            });

            $('body').on('click', '.delete-study', function () {
                var study_id = $(this).data("id");
                confirm("Are You sure want to delete !");
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('studies')}}"+'/'+study_id,
                    success: function (data) {
                        $("#study_id_" + study_id).remove();
                        confirm('Deleted Successfully !!');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        });


        $('body').on('click','.changeStudyStatus',function () {
            var elem    =   $(this);
            var url     =   elem.attr('data-url');
            //var value     =   elem.attr('data-value');
            var id     =   elem.attr('data-id');
            $.ajax({
                type:'POST',
                url:url,
                data        :   {
                    '_token'    :   $('meta[name=csrf-token]').attr("content"),
                    id
                },
                success:function(data) {
                    alert('success');
                }
            });
        });




        function replicateStudy()
        {

            $('body').on('click', '.clone-study', function () {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var parent_id = $(this).data("id");
                // alert(parent_id);
                var newPath = "{{URL('studies/cloneStudy')}}";
                //alert(newPath)
                $.ajax({
                    type: "POST",
                    data:{'id':parent_id},
                    url: newPath,
                    success: function (data) {
                        console.log(data);

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        }
        replicateStudy();

        if ($("#studyForm").length > 0) {
            $("#studyForm").validate({

                submitHandler: function(form) {
                    var t;
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');


                    $.ajax({
                        data: $('#studyForm').serialize(),
                        url: "{{ route('studies.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            var device = '<tr id="study_id_' + data.id + '">' +
                                '<td>' + data.id + '</td>' +
                                '<td>' + data.study_short_name + '</td>' +
                                '<td>' + data.study_title + '</td>' +
                                '<td>' + data.study_code + '</td>' +
                                '<td>' + data.protocol_number + '</td>' +
                                '<td>' + data.trial_registry_id + '</td>' +
                                '<td>' + data.study_sponsor + '</td>' +
                                '<td>' + data.start_date + '</td>' +
                                '<td>' + data.end_date + '</td>' +
                                '<td>' + data.description + '</td>' +
                                '<td>' + data.users + '</td>' +
                                '<td>' + data.sites + '</td>' +
                                '<td>' + data.disease_cohort + '</td>' +
                                '<td>' + data.study_title + '</td>';
                            device += '<td><a href="javascript:void(0)" id="edit-study" data-id="' + data.id + '" class="btn btn-info">Edit</a></td>';
                            device += '<td><a href="javascript:void(0)" id="study-status" data-id="' + data.id + '" class="btn btn-info changeStudyStatus"> Status</a></td>';
                            device += '<td><a href="javascript:void(0)" id="clone-study" data-id="' + data.id + '" class="btn btn-info"> Clone</a></td>';
                            device += '<td><a href="javascript:void(0)" id="delete-device" data-id="' + data.id + '" class="btn btn-danger delete-device">Delete</a></td></tr>';

                            if (actionType == "create-study") {
                                $('#study-crud').prepend(device);
                                var t = setTimeout(function(){// wait for -- secs(2)
                                    location.reload();
                                }, 1000);
                            } else {
                                $("#study_id_" + data.id).replaceWith(device);
                            }
                            $('#studyForm').trigger("reset");
                            $('#study-crud-modal').modal('hide');
                            $('#btn-save').html('Save Changes');
                            alert(data.success());
                            if(data.success == true){
                                var t = setTimeout(function(){// wait for -- secs(2)
                                    location.reload();
                                }, 1000);
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#btn-save').html('Save Changes');
                        }
                    });
                }
            })
        }

    </script>
@endsection
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
                                                           <a href="javascript:void(0)" id="edit-study" data-id="{{ $study->id }}">
                                                               <i class="icon-pencil"></i> Edit
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
                                                        <span class="dropdown-item">
                                                             <a href="#" data-id="" class="addModalities">
                                                                <i class="fa fa-object-group" aria-hidden="true"></i> Preferences
                                                             </a>
                                                        </span>
                                                        <span class="dropdown-item">
                                                            <a href="#" data-id="" class="addModalities">
                                                                <i class="fa fa-object-group" aria-hidden="true"></i> Modalities
                                                            </a>
                                                        </span>
                                                    </div>
                                                </div>
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

    {{-- Create Model --}}
    <div class="modal fade" id="createStudy" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deviceCrudModal">Add Study</h4>
                </div>
                <form id="deviceForm" name="deviceForm" class="form-horizontal">
                    <div class="modal-body">
                        <input type="hidden" name="device_id" id="device_id">
                        <nav>
                            <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic Info</a>
                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Users" role="tab" aria-controls="nav-profile" aria-selected="false">Users</a>
                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Sites" role="tab" aria-controls="nav-profile" aria-selected="false">Sites</a>
                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Disease" role="tab" aria-controls="nav-profile" aria-selected="false">Disease Cohort</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            {{-- Basic Info Tab --}}
                            <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                                @csrf
                                <div class="form-group row" style="margin-top: 10px;">
                                    <label for="study_short_name" class="col-md-3">Short Name</label>
                                    <div class="{!! ($errors->has('study_short_name')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="text" class="form-control" id="study_short_name" name="study_short_name" value="{{old('study_short_name')}}">
                                        @error('study_short_name')
                                        <span class="text-danger small">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="study_title" class="col-md-3">Title</label>
                                    <div class="{!! ($errors->has('study_title')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="text" class="form-control" id="study_title" name="study_title" value="{{old('study_title')}}"> @error('email')
                                        <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="study_code" class="col-md-3">Study Code</label>
                                    <div class="{!! ($errors->has('study_code')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="text" class="form-control" id="study_code" name="study_code" value="{{old('study_code')}}">
                                        @error('study_code')
                                        <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="protocol_number" class="col-md-3">Protocol Number</label>
                                    <div class="{!! ($errors->has('protocol_number')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="text" class="form-control" id="protocol_number" name="protocol_number" value="{{old('protocol_number')}}">
                                        @error('protocol_number')
                                        <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="trial_registry_id" class="col-md-3">Trial Registry ID</label>
                                    <div class="{!! ($errors->has('trial_registry_id')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="text" class="form-control" id="trial_registry_id" name="trial_registry_id" value="{{old('trial_registry_id')}}">
                                        @error('trial_registry_id')
                                        <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="study_sponsor" class="col-md-3">Study Sponsor</label>
                                    <div class="{!! ($errors->has('study_sponsor')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="text" class="form-control" id="study_sponsor" name="study_sponsor" value="{{old('study_sponsor')}}">
                                        @error('study_sponsor')
                                        <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="start_date" class="col-md-3">Start Date</label>
                                    <div class="{!! ($errors->has('start_date')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{old('start_date')}}">
                                        @error('start_date')
                                        <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="end_date" class="col-md-3">End Date</label>
                                    <div class="{!! ($errors->has('end_date')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{old('end_date')}}">
                                        @error('end_date')
                                        <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="description" class="col-md-3">Description</label>
                                    <div class="{!! ($errors->has('description')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="text" class="form-control" id="description" name="description" value="{{old('description')}}">
                                        @error('description')
                                        <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
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
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn btn-outline-primary" id="btn-save"><i class="fa fa-save"></i> Save Changes</button>
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


