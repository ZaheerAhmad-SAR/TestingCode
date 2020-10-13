<select class="form-control multieSelectDropDown" multiple data-allow-clear="1" name="users" id="users">
    @foreach($studyusers as $user)
        <option value="{{$user->id}}">{{$user->name}}</option>
    @endforeach
</select>
