
<link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2.min.css") }}"/>
<link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2-bootstrap.min.css") }}"/>

<select name="users" id="users" class="multieSelectDropDown usersList" multiple data-allow-clear="1">
    @foreach($records as $record)
    <option value="{{$record->PI_email}}">{{$record->PI_FirstName. '  '.$record->PI_LastName}}  (PI)</option>
     <option value="{{$record->photographer_email}}">{{$record->photographer_full_name}} (P)</option>
    <option value="{{$record->Submitter_email}}">{{$record->Submitter_First_Name. '  '.$record->Submitter_Last_Name}}  (SN)</option>
    @endforeach
</select>

<script src="{{ asset("dist/vendors/select2/js/select2.full.min.js") }}"></script>
<script src="{{ asset("dist/js/select2.script.js") }}"></script>
