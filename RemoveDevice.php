<?php
session_start();
if(!isset($_SESSION['usersession']))
{
	header("location:Login.php");
}


include 'DatabaseTable.php';


if (isset($_POST['deletesimulator']))
{
	$simulator=$_POST['sims'];
	$simulators = explode(':', $_POST['sims']);
	$simulatorip = $simulators[0];
	$simulatorport = $simulators[1];


	$DeleteQuery = "DELETE from $DEVICES WHERE IP='$simulatorip' AND  Port='$simulatorport'"; 
	$DeleteQuery2 = "DELETE from $AgentTable WHERE Simulator='$simulator'"; 
	mysql_query($DeleteQuery,$con);
	mysql_query($DeleteQuery2,$con);
	header("location:Private.php");
}

else if (isset($_POST['deleteagent']))
{
	session_start();

	$DeleteQuery = "DELETE from $AgentTable WHERE Community='$_POST[simsss]' AND  Simulator='$_SESSION[sims]'"; 
	mysql_query($DeleteQuery,$con);

	header("location:Private.php");


}
?>
