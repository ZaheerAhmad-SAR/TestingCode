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
                   <blockquote> Welcome to OCAP by OIRRC </blockquote>
                    <p> <strong>Study Short Name :</strong> {{$data['studyShortName']}}</p>
                    <p><strong>Transmission Number:</strong> {{$data['Transmission_Number']}}</p>
                    <p> <strong>Visit Name:</strong> {{$data['visit_name']}}</p>
                    <p> <strong>Study ID:</strong> {{$data['StudyI_ID']}}</p>
                    <p><strong>Subject ID:</strong> {{$data['Subject_ID']}}</p>
                    <p><strong>Remarks</strong> <br> {{$data['remarks']}}</p>

                    <p>Click Here to Reply : {{$data['replyToken']}}</p>
{{--                    <div class="table-responsive">--}}
{{--                        <table id="example" class="display table dataTable table-striped table-bordered" >--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th>Study Short Name</th>--}}
{{--                                <th>Transmission Number</th>--}}
{{--                                <th>Visit Name</th>--}}
{{--                                <th>Study ID</th>--}}
{{--                                <th>Subject ID</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td>{{$data['studyShortName']}}</td>--}}
{{--                                    <td>{{$data['Transmission_Number']}}</td>--}}
{{--                                    <td>{{$data['visit_name']}}</td>--}}
{{--                                    <td>{{$data['StudyI_ID']}}</td>--}}
{{--                                    <td>{{$data['Subject_ID']}}</td>--}}
{{--                                </tr>--}}

{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                    <p><strong>Remarks</strong> <br> {{$data['remarks']}}</p>--}}
                </div>
            </div>
        </div>
    </body>
</html>
