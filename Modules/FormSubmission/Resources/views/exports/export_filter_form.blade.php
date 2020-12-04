<form action="{{ route('formDataExport.export') }}" method="POST" target="_blank" id="export_filter_form">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="visit_ids">Visits</label>
            <select name="visit_id" id="export_visit_id" class="form-control" required multiple>
                <option value="">Select...</option>
                @foreach ($visits as $visit)
                    <option value="{{ $visit->id }}">{{ $visit->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="form_type_id">Form type</label>
            <select name="form_type_id" id="export_form_type_id" class="form-control">
                <option value="">Select...</option>
                @foreach ($formTypes as $formType)
                    <option value="{{ $formType->id }}">{{ $formType->form_type }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="modility_id">Modality</label>
            <select name="modility_id" id="export_modility_id" class="form-control">
                <option value="">Select...</option>
                @foreach ($modalities as $modality)
                    <option value="{{ $modality->id }}">{{ $modality->modility_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <button type="button" onclick="submitExportFilterForm(event);" class="btn btn-primary">Export data</button>
</form>
