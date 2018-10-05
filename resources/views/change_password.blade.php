<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>

<style>
	.scaled {
	  transform: scale(1.5);
	}
</style>

<body>
	@isset($message)
		<div align="center" style="color: red">
			{{ $message }}
		</div>
	@endisset
	<form method="POST" action="{{ route('change.password') }}">
		@csrf
		<br><br><br><br><br><br>
		<div class="scaled">
			<div align="center">
				Username: <input type="text" name="username">
				<br>
				Password: <input type="password" name="password">
				<br>
				New Password: <input type="password" name="newPassword">
				<br>
				Confirm Password: <input type="password" name="confirmPassword">
				<br>
				<button>Change</button>
			</div>
		</div>	
	</form>
</body>
</html>