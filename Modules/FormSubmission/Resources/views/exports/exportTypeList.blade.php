<div class="modal-header ">
    <p class="modal-title">Export Types</p>
    <button  onclick="showAddExportTypeModal();" class="btn btn-success">Add Export Type</button>
</div>
<div class="modal-body">
    <div id="exTab1">
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
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exportTypes as $exportType)
                            <tr id="{{ $exportType->id }}">
                                <td>{{ $exportType->export_type_title }}</td>
                                <td>{{ \Modules\FormSubmission\Entities\ExportType::getPhaseNames($exportType->phase_ids) }}
                                </td>
                                <td>{{ $exportType->formType->form_type }}</td>
                                <td>{{ $exportType->modality->modility_name }}</td>
                                <td>{{ $exportType->titles_values }}</td>
                                <td>
                                    <i class="fas fa-edit" onclick="loadEditExportTypeForm('{{ $exportType->id }}');"></i>
                                    &nbsp;&nbsp;
                                    <i class="fas fa-trash-alt" onclick="removeEditExportType('{{ $exportType->id }}');"></i>
                                </td>
                                <td><button type="button" onclick="submitExportFilterForm('{{ $exportType->phase_ids }}', '{{ $exportType->form_type_id }}', '{{ $exportType->modility_id }}', '{{ $exportType->titles_values }}');" class="btn btn-info">Export Data</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
