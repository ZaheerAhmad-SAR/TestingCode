
<link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2.min.css") }}"/>
<link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2-bootstrap.min.css") }}"/>

<select name="users" id="users" class="multieSelectDropDown form-control usersList">
    @foreach($records as $record)
    <option value="{{$record->PI_email}}">{{$record->PI_FirstName. '  '.$record->PI_LastName}}  (PI)</option>
     <option value="{{$record->photographer_email}}">{{$record->photographer_full_name}} (P)</option>
    <option value="{{$record->Submitter_email}}">{{$record->Submitter_First_Name. '  '.$record->Submitter_Last_Name}} &nbsp; * <b>S</b></option>
        <div class="hidden_Values">
            <input type="hidden" id="Transmission_Number" name="Transmission_Number" value="{{$record->Transmission_Number}}">
            <input type="hidden" id="StudyI_ID" name="StudyI_ID" value="{{$record->StudyI_ID}}">
            <input type="hidden" id="visitName" name="visitName" value="{{$record->visit_name}}">
            <input type="hidden" id="Subject_ID" name="Subject_ID" value="{{$record->Subject_ID}}">
            @php
                $studyCode = Modules\Admin\Entities\Study::where('study_code', $record->StudyI_ID)->first();
            @endphp
            <input type="hidden" id="studyShortName" name="studyShortName" value="{{$studyCode->study_short_name}}">
        </div>
    @endforeach
</select>

<script src="{{ asset("dist/vendors/select2/js/select2.full.min.js") }}"></script>
<script src="{{ asset("dist/js/select2.script.js") }}"></script>
