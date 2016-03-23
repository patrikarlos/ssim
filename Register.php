<center>

<?php
include 'DatabaseTable.php';
echo "<h2>Register</h2>\n";

$submit= $_POST['submit'];
$username=strtolower( strip_tags ($_POST['username']));
$password= strip_tags($_POST['password']);
if($submit)
{

	if(!empty($username) && !empty($password))
	{
		mysql_select_db($Database);
		$namecheck = mysql_query("SELECT username FROM $USERS WHERE username='$username'");
		$count=mysql_num_rows($namecheck);
		if($count!=0)
		{
			echo "<font size='1' color='red'>" . "Username already taken\n" . "</font>";
		}
		else
		{

			$password=md5($password);


			mysql_select_db($db);

			$rows = mysql_result(mysql_query("SELECT * FROM $USERS"), 0);
			if (!$rows) 
			{
				$queryreg = mysql_query("INSERT INTO $USERS VALUES('','$username','$password',1)");
			} 
			else 
			{
				$queryreg = mysql_query("INSERT INTO $USERS VALUES('','$username','$password',0)");
			}
			echo "<br>";
			die("You hav been registered  <a href='Homepage.php'>Return to home page </a> ");
		}
		
	}
	else
	{
		echo "<font size='1' color='red'>" . "Please fill in all fields" . "</font>";

	}

}
?>
<html>
	<form action='Register.php' method ='POST'>
		<center>
			<table>
				<tr>
					<td>
						Choose a UserName:
					</td>
					<td>
						<input type='text' name='username' ></center>
					</td>
				</tr>
				<tr>
					<td>
						Choose a Password:
					</td>
					<td>
						<input type='password' name='password' >
					</td>
				</tr>
			</table>
			<input type='submit' name='submit' value='Register'>
			<input type="button" value="Back" onclick="location='Homepage.php'" />
		</center>
	</form>
</html>
