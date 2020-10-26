<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{asset('images/Logo.gif')}}" class="logo" alt="OCAP">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
