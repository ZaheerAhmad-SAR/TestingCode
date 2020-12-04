@extends ('layouts.home')
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Data Exports</h4>
                    </div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="">Data Exports</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header  justify-content-between align-items-center">
                        <h4 class="card-title">Export Data</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                            <button  onclick="showExportFilterModal();" class="btn btn-warning">Export Data</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Card DATA-->
        </div>
        @include('formsubmission::exports.export_filter_modal')
    @stop
    @push('script')
    <script>
        function showExportFilterModal(){
                $('#export_filters_modal').modal('show');
                loadExportFilterForm();
            }

            function loadExportFilterForm(){
                $.ajax({
                    url: "{{route('formDataExport.loadExportFilterForm')}}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response){
                        $('#export_filter_form_div').empty();
                        $("#export_filter_form_div").html(response);
                    }
                });
            }
            function submitExportFilterForm(e){
                e.preventDefault();
                var query = {
                        visit_id: $('#export_visit_id').val(),
                        form_type_id: $('#export_form_type_id').val(),
                        modility_id: $('#export_modility_id').val(),
                        "_token": "{{ csrf_token() }}"
                    }
                var url = "{{ route('formDataExport.export') }}?" + $.param(query);
                window.location = url;
            }
    </script>
    @endpush
