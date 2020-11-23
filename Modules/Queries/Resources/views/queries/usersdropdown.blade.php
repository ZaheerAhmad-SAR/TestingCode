<link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2.min.css") }}"/>
<link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2-bootstrap.min.css") }}"/>

<select class="form-control multieSelectDropDown" multiple data-allow-clear="1" name="users" id="users">
    @foreach($studyusers as $user)
        <option value="{{$user->id}}">{{$user->name}}</option>
    @endforeach
</select>
<script src="{{ asset("dist/vendors/select2/js/select2.full.min.js") }}"></script>
<script src="{{ asset("dist/js/select2.script.js") }}"></script>

