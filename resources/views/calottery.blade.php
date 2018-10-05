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
	<form method="POST" action="{{ route('csv.get') }}">
		@csrf
		<br><br><br><br><br><br>
		<div class="scaled">
			<div align="center">
				From: <input type="date" name="fromDate" value="{{\Carbon\Carbon::now()->toDateString()}}">
				&nbsp;&nbsp;&nbsp;&nbsp;
				To: <input type="date" name="toDate" value="{{\Carbon\Carbon::now()->toDateString()}}">
			</div>
			<div align="center">or</div>
			<div align="center">
				From: <input type="number" name="fromId" placeholder="Id">
				&nbsp;&nbsp;&nbsp;&nbsp;
				To: <input type="number" name="toId" placeholder="Id">
			</div>
			<br>
			<div align="center">
				<select name="color">
					<option value="1">Xanh</option>
					<option value="2">Đỏ</option>
				</select>
			<div align="center">
			<br>
			<div align="center">
				<button>Download</button>
			<div align="center">
		</div>	
	</form>
</body>
</html>