
<link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2.min.css") }}"/>
<link rel="stylesheet" href="{{ asset("dist/vendors/select2/css/select2-bootstrap.min.css") }}"/>

<select name="users" id="users" class="form-control multieSelectDropDown" multiple data-allow-clear="1">
    @foreach($primaryInvestigator as $pi)
    <option value="{{$pi->email}}">{{$pi->first_name. '  '.$pi->last_name }}  (PI)</option>
    @endforeach

    @foreach($coordinators as $coordinator)
        <option value="{{$coordinator->email}}">{{$coordinator->first_name. '  '.$coordinator->last_name }}  (C)</option>
    @endforeach
    @foreach($photographer as $photo)
        <option value="{{$photo->email}}">{{$photo->first_name. '  '.$photo->last_name }}  (P)</option>
    @endforeach
    @foreach($others as $other)
        <option value="{{$other->email}}">{{$other->first_name. '  '.$other->last_name }}  (O)</option>
    @endforeach
</select>

<script src="{{ asset("dist/vendors/select2/js/select2.full.min.js") }}"></script>
<script src="{{ asset("dist/js/select2.script.js") }}"></script>
