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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <button  onclick="showAddExportTypeModal();" class="btn btn-success">Add Export Type</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header  justify-content-between align-items-center">
                        <h4 class="card-title">Export Types</h4>
                    </div>
                    <div class="card-body">
                            <div class="tab-content clearfix">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Title</th>
                                                <th scope="col">Phases</th>
                                                <th scope="col">Form Type</th>
                                                <th scope="col">Modality</th>
                                                <th scope="col">Title / Value</th>
                                                <th scope="col">Last exported by</th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($exportTypes as $exportType)
                                            @php
                                            $exportTypeUsage = Modules\FormSubmission\Entities\ExportTypeUsage::where('export_type_id', $exportType->id)->orderBy('created_at', 'desc')->first();
                                            $exportedByName = '';
                                            $exportedAt = '';
                                            if(null !== $exportTypeUsage){
                                                $dataExportedBy = App\User::find($exportTypeUsage->data_exported_by_id);
                                                $exportedByName = $dataExportedBy->name;
                                                $exportedAt = $exportTypeUsage->created_at->format('m-d-Y h:i:s');
                                            }

                                            @endphp
                                                <tr id="{{ $exportType->id }}">
                                                    <td>{{ $exportType->export_type_title }}</td>
                                                    <td>{{ \Modules\FormSubmission\Entities\ExportType::getPhaseNames($exportType->phase_ids) }}
                                                    </td>
                                                    <td>{{ $exportType->formType->form_type }}</td>
                                                    <td>{{ $exportType->modality->modility_name }}</td>
                                                    <td>{{ $exportType->titles_values }}</td>
                                                    <td>{{ $exportedByName }}<br>{{ $exportedAt }}</td>
                                                    <td>
                                                        <i class="fas fa-edit" onclick="loadEditExportTypeForm('{{ $exportType->id }}');"></i>
                                                        &nbsp;&nbsp;
                                                        <i class="fas fa-trash-alt" onclick="removeEditExportType('{{ $exportType->id }}');"></i>
                                                    </td>
                                                    <td><button type="button" onclick="submitExportFilterForm('{{ $exportType->id }}', '{{ $exportType->phase_ids }}', '{{ $exportType->form_type_id }}', '{{ $exportType->modility_id }}', '{{ $exportType->titles_values }}');" class="btn btn-info">Export Data</button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $exportTypes->links() }}
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
                $('#export_filters_modal').modal('show');
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
                $('#export_filters_modal').modal('show');
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
                var result = confirm("Are you sure?");
                if (result) {
                    $.ajax({
                        url: "{{route('exportType.removeEditExportType')}}",
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "exportTypeId": exportTypeId
                        },
                        success: function(response){
                            reloadPage(1);
                        }
                    });
                }
            }

            function submitAddExportTypeForm(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('exportType.submitAddExportTypeForm') }}",
                    type: 'POST',
                    data: $("#add_export_type_form").serialize(),
                    success: function(response) {
                        reloadPage(1);
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
                        reloadPage(1);
                    }
                });

            }

            function submitExportFilterForm(export_type_id, visit_ids, form_type_id, modility_id, print_options_values){
                var query = {
                        "export_type_id": export_type_id,
                        "visit_ids": visit_ids,
                        "form_type_id": form_type_id,
                        "modility_id": modility_id,
                        "print_options_values": print_options_values,
                        "_token": "{{ csrf_token() }}"
                    }
                var url = "{{ route('formDataExport.export') }}?" + $.param(query);
                window.location = url;
            }

            function reloadPage(waitSeconds) {
                var seconds = waitSeconds * 1000;
                setTimeout(function() {
                    location.reload();
                }, seconds);
            }

    </script>
    @endpush
