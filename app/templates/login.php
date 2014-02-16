<html>
	<head>
		<title>Login</title>
	</head>
	
	<form action="/login" method="POST">
		<input type="hidden" name="_method" value="POST" />
		<label for="username">Username:</label>
		<input type="text" name="username">
		<br />
		<label for="password">Password:</label>
		<input type="password" name="password">
		<br />
		<input type="submit" value="Login!">
	</form>
	
</html>
