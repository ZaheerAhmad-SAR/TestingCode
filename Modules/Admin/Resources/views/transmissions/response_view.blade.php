

<div class="well">
    <label for=""><strong>Query</strong></label> <br>
 {{$query['email_body']}}
</div>
<hr>
<div class="well">
    <label for=""><strong>Response</strong></label> <br>
    @foreach($answers as $answer)
 {{$answer['email_body']}}
    @endforeach
</div>
