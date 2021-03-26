<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Certification</title>
	<!-- <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> -->
</head>
<style>
@font-face {
    font-family:"lucida";
    src:url("{{storage_path('/fonts/Lucida Blackletter Regular/Lucida Blackletter Regular.ttf')}}");
}
@font-face {
	font-family:"futura";
    src:url("{{storage_path('/fonts/Futura/futura medium bt.ttf')}}");
}
@font-face {
	font-family:"optima";
    src:url("{{storage_path('/fonts/Optima-Font/OPTIMA.TTF')}}");
}
@font-face {
	font-family:"helvetica";
    src:url("{{storage_path('/fonts/Helvetica/Helvetica.tff')}}");
}
@font-face {
	font-family:"cuprum";
    src:url("{{storage_path('/fonts/Cuprum/Cuprum-VariableFont_wght.ttf')}}");
}

/* IE10+ CSS print styles */
@media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
  /* IE10+ CSS print styles go here */
  @page {
    size: auto;    /*auto is the initial value*/
    size: letter portrait;
    /* margin: 0;   this affects the margin in the printer settings
    border: 1px solid;
  	padding: 10px;
  	box-shadow: 5px 10px 8px 10px #888888;   set a border for all printed pages */
  }
}
@media print {
   /* td.td-bg-color {
        background-color: #e9f4f7 !important;
    }*/
}
@page { size: auto;  margin: 0mm; }
body {
	/*background: #f7f7f7; */
	height: 150%;
}

h1, h2, h3,p {     
	margin-top: 10px;margin-bottom: 10px;
}
</style>
<!-- <body style="background: #f7f7f7; height: 700px; width:1040px; border: 2px solid red; padding-left: 10px;"> -->
<body style="background-image: url('{{ asset('public/certification_pdf/Device_Certificate_Background.png')}}'); background-repeat: no-repeat; background-position: left 50px;">
	<div style="width: 100%;">
		<div style="width: 90%;margin:0 auto;">
			<p style="text-align: center;font-family: lucida;font-size: 30px;">Ocular Imaging Research and Reading Center</p>
			<p style="text-align: center;font-family: lucida;font-size: 21px;">Device Certificate</p>
			<br>
			<table style="margin:0 auto;font-size: 16px;">
				<tbody>
					<tr>
						<td style="font-family: cuprum; font-size: 14px;text-align: right;">Certificate ID:</td>
						<td style="font-family: cuprum; font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							{{ $generateCertificate->certificate_id}}
						</td>
					</tr>
					<tr>
						<td style="font-family: cuprum; font-size: 14px;text-align: right;">Transmission Number(s):</td>
						<td style="font-family: cuprum; font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						@if($generateCertificate->transmissions != '')
							@php
								$transID = implode(', ',json_decode($generateCertificate->transmissions));
							@endphp		
								{{ $transID }}
						@endif
						</td>
					</tr>
					<tr>
						<td style="font-family: cuprum; font-size: 14px;text-align: right;">Issue Date:</td>
						<td style="font-family: cuprum; font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							{{ date('M d, Y', strtotime($generateCertificate->issue_date)) }}
						</td>
					</tr>
					<tr>
						<td style="font-family: cuprum; font-size: 14px;text-align: right;">Valid until:</td>
						<td style="font-family: cuprum; font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							{{ date('M d, Y', strtotime($generateCertificate->expiry_date)) }}
						</td>
					</tr>
				</tbody>
			</table>
			<br>
			<p style="font-family:DejaVu Sans; font-size: 16px;text-align: center;color: #4d659a;">
				{{ $getStudy->study_title }}
			</p>
			<div style="min-height: 15px;width: 100%"></div>
			<p style="text-align: center;font-size: 16px;color: #4d659a;font-family:DejaVu Sans;">
				<span style="color: red;font-weight: 600;">Sponsor:</span> {{ $getStudy->study_sponsor }}
			</p>
			<br>
			<table style="font-size: 16px;">
				<tbody>
					<tr>
						<td style="font-family: helvetica; font-size: 15px;">Site:</td>
						<td style="font-family: helvetica; font-size: 15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							{{ $getSite->site_name }}
						</td>
					</tr>
					<tr>
						<td style="font-family: helvetica; font-size: 15px;">Address:</td>
						<td style="font-family: helvetica; font-size: 15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							{{ $getSite->site_address != '' ? $getSite->site_address.', ' : '' }} {{ $getSite->site_city != '' ? $getSite->site_city.', ' : '' }} {{ $getSite->site_state != '' ? $getSite->site_state.', ' : '' }} {{ $getSite->site_country != '' ? $getSite->site_country : '' }}
						</td>
					</tr>
					<tr>
						<td style="font-family: helvetica; font-size: 15px;">Site Code:</td>
						<td style="font-family: helvetica; font-size: 15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							 {{ $getSite->site_code }}
						</td>
					</tr>
				</tbody>
			</table>
			<table style="font-size: 16px;margin: 0 auto;border: 1px solid #84adf9;width: 98%;">
				<tbody>
					<tr>
						<td class="td-bg-color" colspan="2" style="text-align: center;border-bottom: 1px solid #84adf9;">
							Device Information
						</td>
					</tr>
					<tr>
						<td class="td-bg-color" style="font-family: cuprum; font-size: 14px;text-align: right;border-bottom: 1px solid #84adf9;border-right: 1px solid #84adf9;">Device Modality</td>
						<td style="font-family: cuprum; font-size: 14px;text-align: center;border-bottom: 1px solid #84adf9;">
							{{$getModality->modility_name}}
						</td>
					</tr>
					<tr>
						<td class="td-bg-color" style="font-family: cuprum; font-size: 14px;text-align: right;border-bottom: 1px solid #84adf9;border-right: 1px solid #84adf9;">Device Manufacture</td>
						<td style="font-family: cuprum; font-size: 14px;text-align: center;border-bottom: 1px solid #84adf9;">
							{{$getDevice->device_manufacturer}}
						</td>
					</tr>
					<tr>
						<td class="td-bg-color" style="font-family: cuprum; font-size: 14px;text-align: right;border-bottom: 1px solid #84adf9;border-right: 1px solid #84adf9;">Device Model</td>
						<td style="font-family: cuprum; font-size: 14px;text-align: center;border-bottom: 1px solid #84adf9;">
							{{$getDevice->device_model}}
						</td>
					</tr>
					<tr>
						<td class="td-bg-color" style="font-family: cuprum; font-size: 14px;text-align: right;border-bottom: 1px solid #84adf9;border-right: 1px solid #84adf9;">Device Serial Number</td>
						<td style="font-family: cuprum; font-size: 14px;text-align: center;border-bottom: 1px solid #84adf9;">
							{{$generateCertificate->device_serial_no}}
						</td>
					</tr>
					<tr>
						<td class="td-bg-color" style="font-family: cuprum; font-size: 14px;text-align: right;border-right: 1px solid #84adf9;">Software Version</td>
						<td style="font-family: cuprum; font-size: 14px;text-align: center;">{{$generateCertificate->device_software_version}}</td>
					</tr>
				</tbody>
			</table>
			<p style="font-family: cuprum; font-size: 14px;text-align: justify; text-justify: inter-word;">
				@if($generateCertificate->transmissions != '')
					@php
						$transID = implode(', ',json_decode($generateCertificate->transmissions));
					@endphp
				@else
					@php
						$transID = '';
					@endphp
				@endif
				The sample images submitted to the OIRRC (transmission # {{ $transID }}) relevant to the abovementioned device are compliant with requirements and standards of <strong>{{$getStudy->study_short_name}}</strong> imaging manual. The Ocular Imaging Research and Reading Center (OIRRC) is hereby issuing a certificate for the above-mentioned device for the use to obtain images in the <strong>{{$getStudy->study_short_name}}</strong> study.
			</p>
			<!-- <div style="min-height: 15px;width: 100%"></div> -->
			<p style="font-family: cuprum; font-size: 14px;text-align: justify;text-justify: inter-word;">
				This certificate is valid for <strong>4 years</strong> from the time it is issued provided that no major modifications, upgrades, updates, or repairs are made during that period. It is the responsibility of the PI and the study team at the Study Site to maintain the integrity of the certification status of the above- mentioned device. Please, communicate with the OIRRC regarding such modifications or other conditions that can possibly affect or compromise the certification status of the device.
			</p>
			<p style="font-family: helvetica; font-size: 16px;">
				Certification Officer
			</p>
			<br>
			@if (File::exists(storage_path('user_signature/'.md5(\Auth::user()->id).'.png')))
				
				<!-- <img src="{{ route('user-signature', encrypt(\Auth::user()->id.'.png')) }}" style="width:150px;"> -->
				
				<img src="{{ storage_path('user_signature/'.md5(\Auth::user()->id).'.png') }}" style="width:150px;">
			@endif
			<div style="min-height: 15px;width: 100%"></div>
			<p style="font-family: helvetica; font-size: 16px;">
				<strong>{{ date('m/d/Y') }}</strong>
			</p>
			<p style="font-family: helvetica; font-size: 16px;">
				{{ \Auth::user()->name }}
			</p>
		</div>
	</div>
</body>
</html>