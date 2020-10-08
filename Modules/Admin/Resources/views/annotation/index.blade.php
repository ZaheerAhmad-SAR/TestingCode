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
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">Annotations</h4></div>
                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Annotations</li>
                </ol>
            </div>
        </div>
        <div class="col-lg-12 success-alert" style="display: none;">
            <div class="alert alert-primary success-msg" role="alert">
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->

    <!-- START: Card Data-->
<div class="row">
 <div class="col-12 col-sm-12 mt-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @if(hasPermission(auth()->user(),'optionsGroup.create'))
                    <button type="button" class="btn btn-outline-primary add_annotation">
                        <i class="fa fa-plus"></i> Add Annotation
                    </button>
                        @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Title</th>
                                <th style="width: 5%;">Action</th>
                            </tr>
                          @foreach($annotation as $key => $value)
                            <tr>
                                <td class="annotation_id" style="display: none;">{{$value->id}}</td>
                                <td class="annotation_label">{{$value->label}}</td>
                                <td>
                                   <div class="d-flex mt-3 mt-md-0 ml-auto">
                                        <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                            <span class="dropdown-item"><a href="#" class="editAnnotation"><i class="far fa-edit"></i>&nbsp; Edit </a></span>
                                            <span class="dropdown-item"><a href="#" class="deleteAnnotation"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

        </div>
</div>
    <!-- END: Card DATA-->
</div>
<!-- Modal To add Option Groups -->
<div class="modal fade" tabindex="-1" role="dialog" id="addAnnotation">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <p class="modal-title">Add New Annotation</p>
            </div>
            <form action="{{route('annotation.store')}}" id="Annotation_form" method="post">
                @csrf
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                            <div class="form-group row">
                                <div class="col-md-3">Annotation
                                    <sup>*</sup></div>
                                <div class="form-group col-md-9">
                                    <input type="hidden" name="annotation_id" id="annotation_id">
                                    <input type="text" class="form-control" id="annotation_name" name="annotation_name" value="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="optiongroup-close" class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
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
<script type="text/javascript">
    $('.add_annotation').on('click',function(){
        $('#Annotation_form').trigger('reset');
        $('.modal-title').html('Add New Annotation');
        $('#Annotation_form').attr('action', "{{route('annotation.store')}}");
        $('#addAnnotation').modal('show');
    })
    $('body').on('click','.editAnnotation',function(){
        $('#Annotation_form').trigger('reset');
        $('.modal-title').html('Update Annotation');
        var row = $(this).closest('tr')
            id = row.find('td.annotation_id').text()
            label = row.find('td.annotation_label').text();

        $('#annotation_id').val(id);
        $('#annotation_name').val(label);
        $('#Annotation_form').attr('action', '{{route('annotation.updateAnnotation')}}');
        $('#addAnnotation').modal('show');
    })
     $('body').on('click','.deleteAnnotation',function(){
        var row = $(this).closest('tr');
        var id = row.find('td.annotation_id').text();
        if (confirm("Are you sure to delete?")) {
            $.ajax({
                url: 'annotation/delete/'+id,
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'DELETE',
                    'id': id
                    },
                success:function(res){
                    row.remove();
                    $('.success-msg').html('Operation Done!')
                    $('.success-alert').slideDown('slow');
                    tId=setTimeout(function(){
                        $(".success-alert").slideUp('slow');
                    }, 3000);
                }
            })
        }
    })
</script>
@stop
