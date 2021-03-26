<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Certification</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
<style>
@font-face {
    font-family:"lucida";
    src:url("{{storage_path('fonts/Lucida Blackletter Regular/Lucida Blackletter Regular.ttf')}}");
}
@font-face {
	font-family:"futura";
    src:url("{{storage_path('fonts/Futura/futura medium bt.ttf')}}");
}
@font-face {
	font-family:"optima";
    src:url("{{storage_path('fonts/Optima-Font/OPTIMA.TTF')}}");
}
@font-face {
	font-family:"helvetica";
    src:url("{{storage_path('fonts/Helvetica/Helvetica.tff')}}");
}
@font-face {
	font-family:"arial";
    src:url("{{storage_path('fonts/Arial/arial.ttf')}}");
}
@font-face {
	font-family:"tahoma";
    src:url("{{storage_path('fonts/Tahoma/tahoma.ttf')}}");
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

body {
	/*background: #f7f7f7; */
	height: 150%;
}

h1, h2, h3,p {     
	margin-top: 10px; margin-bottom: 10px;
}
</style>
<!-- <body style="background: #f7f7f7; height: 700px; width:1040px; border: 2px solid red; padding-left: 10px;"> -->
<body style="background-image: url('{{ asset('public/certification_pdf/Certificate_Background_5.png')}}'); background-repeat: no-repeat;">
	<table align="center" style="width: 100%;">
		<tr>
			<td align="center">&nbsp;</td>
		</tr>
		<tr>
			<td align="center">&nbsp;</td>
		</tr>
		<tr>
			<td align="center">&nbsp;</td>
		</tr>
		<!-- <tr>
			<td align="center">
				<img src="{{ asset('public/certification_pdf/occap_email_logo.png')}}" style="width: 25%; padding-bottom: 5px;"> 
			</td>
		</tr> -->
		<tr>
			<td align="center" style="">
				<p style="font-family: lucida; font-size: 30px;">Ocular Imaging Research and Reading Center</p>
				<p style="font-family: lucida; font-size: 21px;">@if($generateCertificate->transmission_type == 'device_transmission') Device @else Photographer @endif Certificate</p>
				<p style="font-family: futura; font-size: 27px;">{{ $generateCertificate->certificate_for }}</p>
				<p style="font-family: optima; font-size: 16px;">Imaging certificate is hereby granted to</p>
				<p style="text-align: center; font-family: arial; font-size: 40px;">{{ strtoupper($getPhotographer->first_name).' '.strtoupper($getPhotographer->last_name) }}</p>
				<p style="font-family: helvetica; font-size: 19px;">Site: {{ $getSite->site_name }}</p>
				<p style="font-family: helvetica; font-size: 15px;">Address: {{ $getSite->site_address != '' ? $getSite->site_address.', ' : '' }} {{ $getSite->site_city != '' ? $getSite->site_city.', ' : '' }} {{ $getSite->site_state != '' ? $getSite->site_state.', ' : '' }} {{ $getSite->site_country != '' ? $getSite->site_country : '' }}</p>
				<p style="font-family: helvetica; font-size: 15px;">Site Code: {{ $getSite->site_code }}</p>
				<p style="font-family:DejaVu Sans; font-size: 16px; padding: 5px;">For submitting the images as required by the Image Acquisition Protocol for the<br> {{ $getStudy->study_title }}
				</p>
				<p style="color:red; font-size: 37px; font-family: tahoma">{{ strtoupper($getStudy->study_short_name) }}</p>
				<!-- <h3 style="color: blue;font-family: play">{{ $getStudy->study_sponsor }}</h3> -->
				<br>
				<br>
			</td>
		</tr>

		<tr>
			<td style="padding-left: 50px;">
				<p style="font-family: optima; font-size: 16px;">Certification Officer</p>
				@if (File::exists(storage_path('user_signature/'.md5(\Auth::user()->id).'.png')))
					
					<!-- <img src="{{ route('user-signature', encrypt(\Auth::user()->id.'.png')) }}" style="width:150px;"> -->
					
					<img src="{{ storage_path('user_signature/'.md5(\Auth::user()->id).'.png') }}" style="width:150px;">
				@endif
			</td>
			<!-- <td align="right"><p style="font-family: play;">&nbsp;</p><br><br></td> -->
		</tr>
		<tr>
			<td style="padding-left: 50px;">
				<span style="float: right; padding-right: 30px; margin-top: -20px;">
					<p style="font-family: optima; font-size: 16px;">
						Issue Date: {{ date('M d, Y', strtotime($generateCertificate->issue_date)) }} <br>
						Valid until: {{ date('M d, Y', strtotime($generateCertificate->expiry_date)) }} <br>
						Certificate ID: {{ $generateCertificate->certificate_id}} <br>
						@if($generateCertificate->certificate_type == 'original' && $generateCertificate->transmissions != '')
							@php
								$transID = implode(', ',json_decode($generateCertificate->transmissions));
							@endphp		
								Transmission ID: {{ $transID }}
						@endif
					</p>
				</span>
				<span>
					<p style="font-family: optima; font-size: 16px;">
						{{ date('M d, Y') }} 
						<br><br>
						{{ \Auth::user()->name }}
					</p>
				</span>
			</td>
		</tr>
	</table>
</body>
</html>