<form id="assignPhaseToSubjectForm" onsubmit="return submitAssignPhaseToSubjectForm(event);">
    <div id="exTab1">
        <div class="tab-content clearfix">
            @csrf
            <input type="hidden" id="subject_id" name="subject_id" value="{{$subject->id}}">
            <div class="form-group">
                <label class="">Please select Phase</label>
            <select name="phase_id" id="phase_id"  class="form-control-ocap bg-transparent" required>
                @foreach($visitPhases as $phase)
                    <option value="{{$phase->id}}">{{$phase->name}}</option>
                @endforeach
            </select>
            </div>

            <div class="form-group">
                <label class="">Visit date</label>
                <input type="date" name="visit_date" id="visit_date" value="" class="form-control-ocap bg-transparent" required>
            </div>
            <div class="form-group">
                <label class="">Is out of window?</label>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" name="is_out_of_window" value="0" checked class="custom-control-input">
                    <label class="custom-control-label" for="customCheck1">No</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" name="is_out_of_window" value="1" class="custom-control-input">
                    <label class="custom-control-label" for="customCheck1">Yes</label>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-outline-danger" data-dismiss="modal" id="assignPhaseToSubjectClose">
            <i class="fa fa-window-close" aria-hidden="true"></i> Close
        </button>
        <button type="submit" class="btn btn-outline-primary" id="assignPhseToSubjectBtn">
            <i class="fa fa-save"></i> Activate Visit
        </button>
    </div>
</form>
