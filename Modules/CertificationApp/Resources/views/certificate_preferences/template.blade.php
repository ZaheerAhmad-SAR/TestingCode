@extends ('layouts.home')

@section('title')
    <title> Certification Template | {{ config('app.name', 'Laravel') }}</title>
@stop

@section('styles')

    <style type="text/css">

        .required-field {
            color: red;
        }

        .select2-container--default
        .select2-selection--single {
            background-color: #fff;
            border: transparent !important;
            border-radius: 4px;
        }
        .select2-selection__rendered {
            font-weight: 400;
            line-height: 1.5;
            color: #495057 !important;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: solid black 1px;
            outline: 0;
        }

        .select2-container--default .select2-selection--multiple {
            background-color: white;
            border: 1px solid #485e9029 !important; 
            border-radius: 4px;
            cursor: text;
        }

        legend {
          /*background-color: gray;
          color: white;*/
          padding: 5px 10px;
        }

    </style>

    <link rel="stylesheet" href="{{ asset('public/dist/vendors/summernote/summernote-bs4.css') }}"> 
    

    <!-- date range picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2-bootstrap.min.css') }}"/>
    <!-- sweet alerts -->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/sweetalert/sweetalert.css') }}"/>

@endsection

@section('content')

    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Certification Template</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Certification Template</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">

            <div class="col-12 col-sm-12 mt-3">
                <div class="card">

                    <div class="form-group col-md-12 mt-3">        

                        <button type="button" class="btn btn-primary add-template">Add New Template</button>

                        @if (!$getTemplates->isEmpty())
                        <span style="float: right; margin-top: 3px;" class="badge badge-pill badge-primary">
                            {{ $getTemplates->count().' out of '.$getTemplates->total() }}
                        </span>
                        @endif

                    </div>

                     <hr>
                   
                    <div class="card-body">

                        <div class="table-responsive">
                
                            <table class="table table-bordered" id="laravel_crud">
                                <thead>

                                    <tr class="table-secondary">
                                        <th>
                                            Title
                                        </th>
                                        <th>
                                            Created By
                                        </th>
                                        
                                    </tr>

                                </thead>

                                <tbody>
                                    @if(!$getTemplates->isEmpty())

                                        @foreach($getTemplates as $key => $template)
                                        <tr>

                                            <td>

                                                <a href="javascript:void()" onClick="updateTitle('{{$template->template_id}}', '{{$template->title}}', '{{$template->body}}')" style="color: #17a2b8 !important">
                                                    {{ $template->title}}
                                                </a>
                                            </td>
                                            <td> {{ $template->name}} </td>
                            
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5" style="text-align: center;"> No record found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                            {{ $getTemplates->links() }}

                        </form>
                        <!-- form ends -->
                        </div>
                        <!-- table responsive -->
                    </div>
                </div>
                <!-- Card ends -->
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!--Add  Modal -->
    <div class="modal fade" id="template-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Certification Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="{{route('save-certification-template')}}" class="template-form">
                @csrf
                <div class="modal-body">
               
                    <div class="form-group">
                        <label class="title">Title<span class="required-field">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="" required>
                    </div>

                    <div class="form-group">
                        <label class="body">Body<span class="required-field">*</span></label>
                        <textarea class="form-control summernote" id="body" name="body"></textarea>
                        <span class="error-field" style="display: none; color: red;">Please fill body field.</span>
                    </div>

                </div>
            <!-- modal body ends -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Template</button>
                </div>
            </form>
        </div>
      </div>
    </div>
    <!-- Model ends -->

    <!--Add  Modal -->
    <div class="modal fade" id="edit-template-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Certification Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="{{route('update-certification-template')}}" class="edit-template-form">
                @csrf
                <div class="modal-body">

                    <input type="hidden" name="template_id" id="template_id" value="">

                    <div class="form-group">
                        <label class="edit_title">Title<span class="required-field">*</span></label>
                        <input type="text" class="form-control" id="edit_title" name="edit_title" value="" required>
                    </div>

                    <div class="form-group">
                        <label class="edit_body">Body<span class="required-field">*</span></label>
                        <textarea class="form-control edit_summernote" id="edit_body" name="edit_body"></textarea>
                        <span class="edit-error-field" style="display: none; color: red;">Please fill body field.</span>
                    </div>

                </div>
            <!-- modal body ends -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Template</button>
                </div>
            </form>
        </div>
      </div>
    </div>
    <!-- Model ends -->

@endsection
@section('script')

<script src="{{ asset('public/dist/vendors/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('public/dist/js/summernote.script.js') }}"></script>

<!-- <script src="{{ asset('public/dist/js/summernote.script.js') }}"></script> -->

<script type="text/javascript">

    // initialize summer note
    $('.summernote').summernote({
        height: 200,

    });

    $('.add-template').click(function() {
            
        // submit form
        $('#template-modal').modal('show');

    });

    $('.template-form').submit(function(e) {
        
        if($('.summernote').summernote('isEmpty')) {
            // cancel submit
            e.preventDefault(); 
            $('.error-field').css('display', 'block');  
        } else {

            e.currentTarget;
        }
    });

    // initilaize summer note for edit
    $('.edit_summernote').summernote({
        height: 200,
    });

    function updateTitle(templateId, templateTitle, templateBody) {
        // show template
        $('#edit-template-modal').modal('show');

        // asign id
        $('#template_id').val(templateId);
        // assign title
        $('#edit_title').val(templateTitle);
        // assign body
        $('.edit_summernote').summernote('code', templateBody);
    }

    // submit edit form
    $('.edit-template-form').submit(function(e) {
        
        if($('.edit_summernote').summernote('isEmpty')) {
            // cancel submit
            e.preventDefault(); 
            $('.edit-error-field').css('display', 'block');  
        } else {

            e.currentTarget;
        }
    });
   
</script>
@endsection




