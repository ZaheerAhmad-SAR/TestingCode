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
                    <p><strong>Receiver</strong> <br> {{$data['receiverEmail']}}</p>

{{--                    <p > {{$data['replyToken']}}</p>--}}

{{--                    href="{{ url('transmissions/verifiedToken',$data['replyToken']) }}"--}}
                    <p style="cursor: pointer;"><a class="openQueryPopUp" data-value="{{$data['replyToken']}}" href="{{ url('transmissions/verifiedToken',$data['replyToken']) }}">Click Here to Reply :</a></p>
                </div>
            </div>
        </div>
    </body>

</html>


