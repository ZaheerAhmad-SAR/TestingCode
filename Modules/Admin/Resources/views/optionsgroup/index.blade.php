@extends('layouts.home')

@section('title')
    <title> CRFs | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
 <div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">Option Groups</h4></div>
                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Option Group</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->

    <!-- START: Card Data-->
<div class="row">
 <div class="col-12 col-sm-12 mt-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#addOptionGroups">
                        <i class="fa fa-plus"></i> Add Option Groups
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Options Title</th>
                                <th>Defined Options</th>
                                <th style="width: 5%;">Action</th>
                            </tr>
                            @if(!empty($optionsGroup))
                            @foreach($optionsGroup as $option)
                            <tr id="{{ $option->id}}">
                                <td>{{ucfirst($option->option_group_name)}}</td>
                                <td>
                                    @php $optionNames   = explode(",", $option->option_name); @endphp
                                    @php $optionValues = explode(",", $option->option_value); @endphp
                                        @foreach($optionNames as $optionName)

                                        @if(!empty($optionName))

                                            <button type="button" class="btn custom-btn blue-color" style="font-size: 10px;"> {{$optionName}}</button>
                                    @endif
                                    @endforeach
                                </td>
                                <td>
                                   <div class="d-flex mt-3 mt-md-0 ml-auto">
                                        <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                            <span class="dropdown-item"><a href="#" class="editOptions" data-id='{{$option->id}}'><i class="far fa-edit"></i>&nbsp; Edit </a></span>
                                            <span class="dropdown-item"><a href="#" class="deleteOptions" data-id='{{$option->id}}'><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>

        </div>
</div>
    <!-- END: Card DATA-->
</div>
<!-- Modal To add Option Groups -->
<div class="modal fade" tabindex="-1" role="dialog" id="addOptionGroups">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <p class="modal-title">Add Option Group</p>
            </div>
            <form name="OptionsGroupForm" id="OptionsGroupForm">
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                            <div class="form-group row">
                                <div class="col-md-3">Option group name
                                    <sup>*</sup></div>
                                <div class="form-group col-md-9">
                                    <input type="text" class="form-control" id="option_group_name" name="option_group_name" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">Option group description
                                    <sup>*</sup></div>
                                <div class="form-group col-md-9">
                                    <input type="text" class="form-control" id="option_group_description" name="option_group_description" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">Option Layout <sup>*</sup></div>
                                <div class="form-group col-md-9">
                                    <input type="radio" name="option_layout" value="vertical"> Vertical &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="option_layout" value="horizontal" checked> Horizontal
                                </div>
                            </div>
                            <div class="appendDataOptions"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary addOptions pull-right"><i class="fa fa-plus"></i> Add option</button>
                        <button id="optiongroup-close" class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End -->

<!-- Modal To Edit Option Groups -->
<div class="modal fade" tabindex="-1" role="dialog" id="editOptionGroups">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <p class="modal-title">Edit Option Group</p>
            </div>
            <form name="OptionsGroupEditForm" id="OptionsGroupEditForm">
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                                <div class="form-group row">
                                    <div class="col-md-3">Option group name
                                        <sup>*</sup></div>
                                    <div class="form-group col-md-9">
                                        <input type="text" class="form-control" id="option_group_name_edit" name="option_group_name_edit" value="">
                                    </div>
                                    <input type="hidden" class="form-control" id="options_groups_id" name="options_groups_id" value="">
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">Option group description
                                        <sup>*</sup></div>
                                    <div class="form-group col-md-9">
                                        <input type="text" class="form-control" id="option_group_description_edit" name="option_group_description_edit" value="">
                                    </div>
                                </div>
                            <div class="form-group row">
                                <div class="col-md-3">Option Layout <sup>*</sup></div>
                                <div class="form-group col-md-9">
                                    <input type="radio" id="option_layout_edit" name="option_layout_edit" value="vertical"> Vertical &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" id="option_layout_edit" name="option_layout_edit" value="horizontal"> Horizontal
                                </div>
                            </div>
                            <div class="edit_values_row">
                            </div>
                            <div class="appendDataOptions_edit">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-outline-primary addOptions_edit pull-right"><i class="fa fa-plus"></i> Add option</button>
                        <button id="optiongroup-close" class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End -->

@endsection
@section('styles')
<style>
    div.dt-buttons{
        display: none;
    }
</style>
<link rel="stylesheet" href="{{ asset('public/dist/vendors/datatable/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/datatable/buttons/css/buttons.bootstrap4.min.css') }}">
@stop
@section('script')
<script src="{{ asset('public/dist/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('public/dist/js/datatable.script.js') }}"></script>
<script>

       $('.addOptions').on('click',function(){
           $('.appendDataOptions').append('<div class="values_row"><div class="form-group row"><div class="col-md-3">Option Name:</div><div class="form-group col-md-4"><input type="text" id="option_name" name="option_name[]" class="form-control"></div><div class="form-group col-md-4"><input type="number" placeholder="value" name="option_value[]" id="option_value" class="form-control"></div><div class="form-group col-md-1" style="text-align: right;!important;"><i class="btn btn-outline-danger fa fa-trash remove" style="margin-top: 3px;"></i></div></div></div>');
           return false;
       });


       $('.addOptions_edit').on('click',function(){
           $('.appendDataOptions_edit').append('<div class="edit_values_row"><div class="form-group row"><div class="col-md-3">Option Name:</div><div class="form-group col-md-4"><input type="text" id="option_name_edit" name="option_name_edit[]" class="form-control"></div><div class="form-group col-md-4"><input type="number" placeholder="value" name="option_value_edit[]" id="option_value_edit" class="form-control"></div><div class="form-group col-md-1" style="text-align: right;!important;"><i class="btn btn-outline-danger fa fa-trash edit_remove" style="margin-top: 3px;"></i></div></div> </div>');
           return false;
       });

       $('body').on('click','.remove',function(){
            var row = $(this).closest('div.values_row');
            row.remove();
       });

       $('body').on('click','.edit_remove',function(){
            var row = $(this).closest('div.edit_values_row');
            row.remove();
       });


       $('body').on('click','.remove_anno',function(){
            var row = $(this).closest('div.anno_values_row');
            row.remove();
       })


       // Add New Option Group
       function addOptionsGroup()
       {

           $("#OptionsGroupForm").submit(function(e) {
               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               });
               e.preventDefault();
               $.ajax({
                   data: $('#OptionsGroupForm').serialize(),
                   url: "{{route('optionsGroup.store')}}",
                   type: "POST",
                   dataType: 'json',
                   success: function (results) {
                       window.setTimeout(function () {
                           location.href = '{{ route('optionsGroup.index') }}';
                       }, 100);
                       $('#OptionsGroupForm').trigger("reset");
                   },
                   error: function (results) {
                       console.log('Error:', results);
                   }
               });
           });
       }
       addOptionsGroup();

       function editOptionGroup() {

           $('body').on('click', '.editOptions', function (e) {
               $('#OptionsGroupEditForm').trigger('reset');
               $('#editOptionGroups').modal('show');
               var id =($(this).attr("data-id"));
               //var url = "{{URL('/ocap/optionsGroup')}}";
               var url = "{{URL('optionsGroup')}}";
               var newPath = url+ "/"+ id+"/edit/";
               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               });
               $.ajax({
                   type:"GET",
                   dataType: 'html',
                   url:newPath,
                   success : function(results) {
                       var parsedata = JSON.parse(results)[0];
                       $('#options_groups_id').val(parsedata.id);
                       $('#option_group_name_edit').val(parsedata.option_group_name);
                       $('#option_group_description_edit').val(parsedata.option_group_description);
                       if (parsedata.option_layout =='vertical')
                       {
                           $("input[name=option_layout_edit][value=" + parsedata.option_layout + "]").prop('checked', true);
                       }
                       if (parsedata.option_layout =='horizontal')
                       {
                           $("input[name=option_layout_edit][value=" + parsedata.option_layout + "]").prop('checked', true);
                       }
                       var optionName       = parsedata.option_name;
                       var optionNameArray  = optionName.split(',');
                       var optionValue      = parsedata.option_value;
                       var optionValueArray = optionValue.split(',');
                       var i;
                       for (i = 0; i < optionValueArray.length; i++) {
                       }
                       $.each(optionNameArray, function(index, value)
                       {
                       $('.appendDataOptions_edit').append('<div class="form-group">\n' +
                           '     <div class="col-md-3">Option Name:</div>\n' +
                           '      <div class="form-group col-md-4">\n' +
                           '       <input type="text" id="option_name_edit" name="option_name_edit[]"  value='+value+' class="form-control">\n' +
                           '       </div>\n' +
                           '        <div class="form-group col-md-4">\n' +
                           '        <input type="number" value='+optionValueArray[index]+' name="option_value_edit[]" id="option_value_edit" class="form-control">\n' +
                           '         </div>\n' +
                           '   ');
                       });
                   }
               });
           });
       }

       editOptionGroup();


       /// Update Child Modility Function


       function updateOptionGroup ()
       {
           $("#OptionsGroupEditForm").submit(function(e) {
               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               });
               e.preventDefault();

               $.ajax({
                   data: $('#OptionsGroupEditForm').serialize(),
                   url: "{{ route('updateOptionsGroup') }}",
                   type: "POST",
                   dataType: 'json',
                   success: function (data) {
                       $('#OptionsGroupEditForm').modal('hide');
                       {{--window.setTimeout(function () {--}}
                       {{--    location.href = '{{ route('optionsGroup.index') }}';--}}
                       {{--}, 100);--}}

                   },
                   error: function (data) {
                       console.log('Error:', data);
                   }
               });
           });
       }
       updateOptionGroup();
   //  Options Delete function
   function  destroyOptions ()
   {
       $('body').on('click', '.deleteOptions', function () {
           var options_id = $(this).data("id");
           var url = "{{URL('optionsGroup')}}";
           var newPath = url+ "/"+ options_id+"/destroy/";
           $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
           });
           if( confirm("Are You sure want to delete !") ==true)
           {
               $.ajax({
                   type: "GET",
                   url: newPath,
                   success: function (data) {
                       // $('#'+options_id).remove();
                       window.setTimeout(function () {
                           location.href = '{{ route('optionsGroup.index') }}';
                       }, 100);
                   },
                   error: function (data) {
                       console.log('Error:', data);
                   }
               });
           }
       });
   }
   destroyOptions();
   </script>
@stop
