<?php
session_start();

?>
<html>
	<body>

		<form action="LoginCheck.php" method="post">
			<center>
			   <p>Username:<input type="text" name="username" size="15" /> </p>
			   <p>Password:<input type="password" name="password" size="15" /></p>
			   <input type="submit" name="submit" value="Login" />
			   <input type="button" value="Back" onclick="location='Homepage.php'" />
			</center>
		</form>


	</body>
</html>
