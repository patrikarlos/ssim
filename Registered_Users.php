<center>

<?php

session_start();

if(!isset($_SESSION['usersession']))
{
	header("location:Login.php");
}
?>




<h2>Registered Users</h2> <br>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

<?php

session_start();
if(!isset($_SESSION['username']))
{
	header("location:Login.php");
}

?>

<?php
	include 'DatabaseTable.php';
	$Query = mysql_query("SELECT * FROM $USERS", $con);
	
	echo "<table border='3'>
	<tr bgcolor='#81F7F3'>
	<th>Email</th>
	
	<th>Authentication</th>
	</tr>";


	$loop=1;
	while($row = mysql_fetch_array($Query))
	{
		session_start();
		$SignedInAs=$_SESSION['username'];	
		if($row['permission'] == '1')
		{
			$Color="#81F781";
			$Checked='checked';
		}
		else
		{
			$Color="#FA5858";
			$Checked='unchecked';
		}

		
		if($_POST["Check$loop"]=='checked')
		{
			$Color="#81F781";
			mysql_query("UPDATE $USERS SET permission='1' WHERE username='$row[username]'", $con);
			$Checked='checked';
			
		}
		elseif($_POST["Check$loop"]=='unchecked')
		{
			$Color="#FA5858";
			mysql_query("UPDATE $USERS SET permission='0' WHERE username='$row[username]'", $con);
			$Checked='unchecked';
			
		}
		
		echo "<tr bgcolor='$Color'>";
		echo "<td align='center'>" . $row['username'] . "</td>";
		if($row['username'] != $SignedInAs)
		{
			echo "<input type='hidden' value='unchecked' name='Check$loop'>";
			echo "<td align='center'>" . "<input $Checked type='checkbox' value='checked' name='Check$loop'>" . "</td>";
		}
		else
		{
			echo "<td align='center'> <font size='2'> This is you </font></td>";
		}
		
		
		echo "</tr>";
		$loop++;
		
	}
	
	echo "</table>";



	mysql_close($con); #Close the connection

?>


	
	<input type='submit' value='Save changes'>
	<input type="button" value="Back" onclick="location='Private.php'" /><br>


</form> 
</center>
