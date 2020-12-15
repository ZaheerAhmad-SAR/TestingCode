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
                            <button  onclick="showExportTypesModal();" class="btn btn-warning">Export Data</button>
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
            function showExportTypesModal(){
                $('#export_filters_modal').modal('show');
                loadExportTypes();
            }

            function loadExportTypes(){
                $.ajax({
                    url: "{{route('exportType.loadExportTypes')}}",
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

            function showAddExportTypeModal(){
                loadAddExportTypeForm();
            }

            function loadAddExportTypeForm(){
                $.ajax({
                    url: "{{route('exportType.loadAddExportTypeForm')}}",
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

            function loadEditExportTypeForm(exportTypeId){
                $.ajax({
                    url: "{{route('exportType.loadEditExportTypeForm')}}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "exportTypeId": exportTypeId
                    },
                    success: function(response){
                        $('#export_filter_form_div').empty();
                        $("#export_filter_form_div").html(response);
                    }
                });
            }

            function removeEditExportType(exportTypeId){
                $.ajax({
                    url: "{{route('exportType.removeEditExportType')}}",
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "exportTypeId": exportTypeId
                    },
                    success: function(response){
                        $('#'+exportTypeId).remove();
                    }
                });
            }

            function submitAddExportTypeForm(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('exportType.submitAddExportTypeForm') }}",
                    type: 'POST',
                    data: $("#add_export_type_form").serialize(),
                    success: function(response) {
                        loadExportTypes();
                    }
                });

            }

            function submitEditExportTypeForm(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('exportType.submitEditExportTypeForm') }}",
                    type: 'PUT',
                    data: $("#edit_export_type_form").serialize(),
                    success: function(response) {
                        loadExportTypes();
                    }
                });

            }

            function submitExportFilterForm(visit_ids, form_type_id, modility_id, print_options_values){
                var query = {
                        "visit_ids": visit_ids,
                        "form_type_id": form_type_id,
                        "modility_id": modility_id,
                        "print_options_values": print_options_values,
                        "_token": "{{ csrf_token() }}"
                    }
                var url = "{{ route('formDataExport.export') }}?" + $.param(query);
                window.location = url;
            }

    </script>
    @endpush
