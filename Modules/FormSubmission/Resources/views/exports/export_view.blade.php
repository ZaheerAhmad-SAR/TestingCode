<table>
    <thead>
        <tr>
            @foreach ($header as $headerCol)
            <th>{!! $headerCol !!}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($body as $bodyCol)
        <tr>
            @foreach ($header as $headerColKey => $headerColVal)
            @php
            $val = '';
            if(isset($bodyCol[$headerColKey])){
                $val = $bodyCol[$headerColKey];
            }
            @endphp
            <td>{!! $val !!}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
