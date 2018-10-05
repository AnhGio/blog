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
	<form method="POST" action="{{ route('login') }}">
		@csrf
		<br><br><br><br><br><br>
		<div class="scaled">
			<div align="center">
				Username: <input type="text" name="username">
				<br>
				Password: <input type="password" name="password">
				<br>
				<button>Login</button>
			</div>
		</div>	

		<br><br><br><br><br><br>
		<div align="center">
			<a href="/change-password">ChangePassword</a>	
		</div>	
		
	</form>
</body>
</html>