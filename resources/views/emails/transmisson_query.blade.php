<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>OCAP</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">


    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="title m-b-md">
                   <h4>Welcome to OCAP by OIRRC</h4>
{{--                    {{dd($data)}}--}}
                    <p>Transmission Number: {{$data['Transmission_Number']}}</p>
                    <p>Visit Name: {{$data['visit_name']}}</p>
                    <p>Study ID: {{$data['StudyI_ID']}}</p>
                    <p>Subject ID: {{$data['Subject_ID']}}</p>
                    <p>{{$data['remarks']}}</p>
                </div>
            </div>
        </div>
    </body>
</html>
