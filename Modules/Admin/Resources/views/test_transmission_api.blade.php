<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	
	<form action="{{ route('transmissions.transmissionData') }}" method="post">
	<!-- <form action="{{ route('transmissions.transmissionDataDevice') }}" method="post"> -->
	<!-- <form action="{{ route('transmissions.transmissionDataPhotographer') }}" method="post"> -->
		@csrf
		@method('POST')

		<div class="form-group">
			<textarea name="data"></textarea>
			
		</div>

		<div class="form-group">
			<button type="submit">Submit</button>
			
		</div>
		
	</form>
</body>
</html>