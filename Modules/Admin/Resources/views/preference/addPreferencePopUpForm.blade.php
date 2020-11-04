<form id="preferenceForm" onsubmit="return submitAddPreferenceForm(event);">
    <div id="exTab1">
        <div class="tab-content clearfix">
            @csrf
            <input type="hidden" id="study_id" name="study_id" value="{{$studyId}}">
            <div class="form-group">
                <label class="">Title</label>
            <input type="text" name="preference_title" id="preference_title" value="{{ old('preference_title', $preference->preference_title) }}" class="form-control-ocap bg-transparent" required>
            </div>
            <div class="form-group">
                <label class="">Value</label>
                <input type="text" name="preference_value" id="preference_value" value="{{ old('preference_value', $preference->preference_value) }}" class="form-control-ocap bg-transparent" required>
            </div>

            <div class="form-group">
                <label class="">Is-selectable?</label>
                @php
                $yesChecked = '';
                $noChecked = '';
                if(old('is_selectable', $preference->is_selectable) == 'yes'){
                    $yesChecked = 'checked="checked"';
                }else{
                    $noChecked = 'checked="checked"';
                }
                @endphp
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" name="is_selectable" id="is_selectable_yes" value="yes" {{ $yesChecked }} class="custom-control-input">
                    <label class="custom-control-label" for="is_selectable_yes">Yes</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" name="is_selectable" id="is_selectable_no" value="no" {{ $noChecked }} class="custom-control-input">
                    <label class="custom-control-label" for="is_selectable_no">No</label>
                </div>
            </div>
            <div class="form-group">
                <label class="">Options</label>
                <textarea name="preference_options" id="preference_options" class="form-control-ocap bg-transparent">{{ old('preference_options', $preference->preference_options) }}</textarea>
                <small class="form-text">Options should be PIPE SIGN - ( | ) seperated.</small>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-outline-danger" data-dismiss="modal" id="addPreferenceClose">
            <i class="fa fa-window-close" aria-hidden="true"></i> Close
        </button>
        <button type="submit" class="btn btn-outline-primary" id="addPreferenceBtn">
            <i class="fa fa-save"></i> Add Preference
        </button>
    </div>
</form>
