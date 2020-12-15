<div class="modal-header ">
    <p class="modal-title">Edit Export Type</p>
</div>
<div class="modal-body">
    <form onsubmit="submitEditExportTypeForm(event);" id="edit_export_type_form">
        @csrf
        <input type="hidden" name="exportTypeId" value="{{ $exportType->id }}" />
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="export_type_title">Title</label>
                <input type="text" name="export_type_title" class="form-control" value="{{ $exportType->export_type_title }}" required />
            </div>
            <div class="form-group col-md-4">
                <label for="phase_ids">Visits</label>
                <select name="phase_ids[]" class="form-control" required multiple>
                    <option value="">Select...</option>
                    @foreach ($phases as $phase)
                    @php
                    $selected = (in_array($phase->id, $selectedPhaseIds))? 'selected="selected"':'';
                    @endphp
                        <option value="{{ $phase->id }}" {{ $selected }}>{{ $phase->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="form_type_id">Form type</label>
                <select name="form_type_id" class="form-control">
                    <option value="">Select...</option>
                    @foreach ($formTypes as $formType)
                    @php
                    $selected = ($formType->id == $exportType->form_type_id)? 'selected="selected"':'';
                    @endphp
                        <option value="{{ $formType->id }}" {{ $selected }}>{{ $formType->form_type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="modility_id">Modality</label>
                <select name="modility_id" class="form-control">
                    <option value="">Select...</option>
                    @foreach ($modalities as $modality)
                    @php
                    $selected = ($modality->id == $exportType->modility_id)? 'selected="selected"':'';
                    @endphp
                        <option value="{{ $modality->id }}" {{ $selected }}>{{ $modality->modility_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="titles_values">Export Option Titles or Values</label>
                <select name="titles_values" class="form-control">
                    <option value="">Select...</option>
                    <option value="Option Titles" {{ ($exportType->titles_values == 'Option Titles')? 'selected="selected"':'' }}>Option Titles</option>
                    <option value="Option Values" {{ ($exportType->titles_values == 'Option Values')? 'selected="selected"':'' }}>Option Values</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Export Type</button>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
