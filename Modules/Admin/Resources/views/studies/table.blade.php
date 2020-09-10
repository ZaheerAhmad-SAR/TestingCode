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
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">Studies Listing</h4></div>

                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{route('dashboard.index')}}">Home</a></li>
                    <li class="breadcrumb-item">Table</li>
                    <li class="breadcrumb-item active"><a href="#">Studies Listing</a></li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->

    <!-- START: Card Data-->
    <div class="row">
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-outline-primary" id="create-new-study" data-toggle="modal" data-target="#createStudy">
                        <i class="fa fa-plus"></i> Add Study
                    </button>
                </div>
                <div class="card-body">
                    <table class="tablesaw table-bordered" data-tablesaw-mode="stack">
                        <thead>
                        <tr>
                            <th scope="col" data-tablesaw-priority="persist">ID</th>
                            <th scope="col" data-tablesaw-sortable-default-col data-tablesaw-priority="3">
                                Short Name : <strong>Study Title</strong>
                                <br>
                                <br>Sponsor
                            </th>
                            <th scope="col" data-tablesaw-priority="2" class="tablesaw-stack-block"><div style="width: 100%; min-width: 10em; background-color: red"><canvas width="200" height="20"></canvas></div></th>
                            <th scope="col" data-tablesaw-priority="1"><abbr title="Rotten Tomato Rating">Status</abbr></th>
                            <th scope="col" data-tablesaw-priority="4">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            @if(count($studies) !=0)
                                <?php $index= 1 ?>
                                @foreach($studies as $study)
                                        <td>{{$index}}</td>
                            <td class="title">
                                <a class="" href="{{ route('studies.show', $study->id) }}">
                                    {{ucfirst($study->study_short_name)}} : <strong>{{ucfirst($study->study_title)}}</strong>
                                </a>
                                <br><br><p style="font-size: 14px; font-style: oblique">Sponsor: <strong>{{ucfirst($study->study_sponsor)}}</strong></p>
                            </td>
                            <td class="tablesaw-stack-block"><div style="width: 100%; min-width: 10em; background-color: blue"><canvas width="200" height="20"></canvas></div></td>
                            <td>{{$study->study_status}}</td>
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
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <!-- END: Card DATA-->
        {{-- Create Model --}}
        <div class="modal fade" id="createStudy" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="deviceCrudModal">Add Study</h4>
                    </div>
                    <form id="deviceForm" name="deviceForm" class="form-horizontal" action="{{route('studies.store')}}">
                        <div class="modal-body">
                            <input type="hidden" name="device_id" id="device_id">
                            <nav>
                                <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic Info</a>
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

    </div>
@stop
@section('styles')
    <link rel="stylesheet" href="{{ asset("public/dist/vendors/tablesaw/tablesaw.css") }}">
@stop
@section('script')
    <script src="{{ asset("public/dist/vendors/tablesaw/tablesaw.js") }}"></script>
    <script src="{{ asset("public/dist/vendors/tablesaw/tablesaw-init.js") }}"></script>
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
@stop
@section('script')
    <script type="text/javascript">
$(document).ready(function() {

$('#but-save').on('click', function() {
var name = $('#study_short_name').val();
var title = $('#study_title').val();
var email = $('#email').val();
var phone = $('#phone').val();
var city = $('#city').val();
var password = $('#password').val();
if(name!="" && email!="" && phone!="" && city!=""){
//   $("#butsave").attr("disabled", "disabled");
$.ajax({
url: "/userData",
type: "POST",
data: {
_token: $("#csrf").val(),
type: 1,
name: name,
email: email,
phone: phone,
city: city
},
cache: false,
success: function(dataResult){
console.log(dataResult);
var dataResult = JSON.parse(dataResult);
if(dataResult.statusCode==200){
window.location = "/userData";
}
else if(dataResult.statusCode==201){
alert("Error occured !");
}

}
});
}
else{
alert('Please fill all the field !');
}
});
});
</script>
@endsection

