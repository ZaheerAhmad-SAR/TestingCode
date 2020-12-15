<div class="modal-header ">
    <p class="modal-title">Add Export Type</p>
</div>
<div class="modal-body">
    <form onsubmit="submitAddExportTypeForm(event);" id="add_export_type_form">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="export_type_title">Title</label>
                <input type="text" name="export_type_title" class="form-control" required />
            </div>
            <div class="form-group col-md-4">
                <label for="phase_ids">Visits</label>
                <select name="phase_ids[]" class="form-control" required multiple>
                    <option value="">Select...</option>
                    @foreach ($phases as $phase)
                        <option value="{{ $phase->id }}">{{ $phase->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="form_type_id">Form type</label>
                <select name="form_type_id" class="form-control">
                    <option value="">Select...</option>
                    @foreach ($formTypes as $formType)
                        <option value="{{ $formType->id }}">{{ $formType->form_type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="modility_id">Modality</label>
                <select name="modility_id" class="form-control">
                    <option value="">Select...</option>
                    @foreach ($modalities as $modality)
                        <option value="{{ $modality->id }}">{{ $modality->modility_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="titles_values">Export Option Titles or Values</label>
                <select name="titles_values" class="form-control">
                    <option value="">Select...</option>
                    <option value="Option Titles">Option Titles</option>
                    <option value="Option Values">Option Values</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Export Type</button>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
