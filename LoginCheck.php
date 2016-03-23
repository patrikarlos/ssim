<center>
<?php
include 'DatabaseTable.php';
mysql_select_db($Database);
if(isset($_POST['username'])){
	$username=$_POST['username'];
	$password=md5($_POST['password']);
	session_start();
	$_SESSION['username']=$username;
	$sql="SELECT * FROM $USERS WHERE username='" . $username . "' AND password='" . $password . "' LIMIT 1 ";
	$res=mysql_query($sql);
	$row = mysql_fetch_row($res);

	if (mysql_num_rows($res)==1 && $row[3]==1)
	{
		echo "Login succesful. You are authenticated\n";
		$_SESSION['usersession']=$username;
		header("Location:Private.php");
	}
	else if (mysql_num_rows($res)==1)
	{
		echo "You have been registered but not authenticated";
		echo "<br>";
		session_destroy();
		echo "<a href='Homepage.php'>Return to home page </a>";
	}
 	else
	{
		echo "<center><font size='1' color='red'> Invalid login, please try again</font></center>";
		include 'Login.php';
		exit();
	}
}


?>
