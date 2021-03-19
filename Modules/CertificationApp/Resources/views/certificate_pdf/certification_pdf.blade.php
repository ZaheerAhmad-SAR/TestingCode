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
	margin-top: 10px;margin-bottom: 10px;
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
				<h3 style="font-family: lucida;">Ocular Imaging Research and Reading Center</h3>
				<h3 style="font-family: lucida;">@if($generateCertificate->transmission_type == 'device_transmission') Device @else Photographer @endif Certificate</h3>
				<h2 style="font-family: play">{{ $generateCertificate->certificate_for }}</h2>
				<p style="font-family: play">Imaging certificate is hereby granted to</p>
				<h1 style="font-size: 3em; text-align: center;font-family: play">{{ $getPhotographer->first_name.' '.$getPhotographer->last_name }}</h1>
				<h3 style="font-family: play">Site: {{ $getSite->site_name }}</h3>
				<h3 style="font-family: play">Address: {{ $getSite->site_address != '' ? $getSite->site_address.', ' : '' }} {{ $getSite->site_city != '' ? $getSite->site_city.', ' : '' }} {{ $getSite->site_state != '' ? $getSite->site_state.', ' : '' }} {{ $getSite->site_country != '' ? $getSite->site_country : '' }}</h3>
				<h3 style="font-family: play">Site Code: {{ $getSite->site_code }}</h3>
				<p style="font-family: play">For submitting the images as required by the Image Acquisition Protocol for the</p>
				<h3 style="text-align: center;font-family: play">{{ $getStudy->study_title }}</h3>
				<h1 style="color:red;font-family: play">{{ $getStudy->study_short_name }}</h1>
				<!-- <h3 style="color: blue;font-family: play">{{ $getStudy->study_sponsor }}</h3> -->
				<br>
				<br>
				<br>
				<br>
			</td>
		</tr>

		<tr>
			<td style="padding-left: 50px;">
				<p style="font-family: play;">Certification Officer</p>
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
					Issue Date: {{ date('M d, Y', strtotime($generateCertificate->issue_date)) }} <br>
					Valid until: {{ date('M d, Y', strtotime($generateCertificate->expiry_date)) }} <br>
					Certificate ID: {{ $generateCertificate->certificate_id}} <br>
					@if($generateCertificate->certificate_type == 'original' && $generateCertificate->transmissions != '')
						@php
							$transID = implode(', ',json_decode($generateCertificate->transmissions));
						@endphp		
							Transmisson ID: {{ $transID }}
					@endif
				</span>
				<span> 
					{{ date('M d, Y') }} 
					<br><br>
					{{ \Auth::user()->name }}
				</span>
			</td>
		</tr>
	</table>
</body>
</html>