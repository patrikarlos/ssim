<?php
	session_start();

	if(isset($_SESSION['usersession']))
	{
		echo "<font size='1'>You are signed in as: " . $_SESSION['usersession'] . "</font>";
	}
	else
	{
		header("location:Login.php");
	}



	echo "<center>";
	include 'DatabaseTable.php';
	echo "<input type='button' value='LogOut' onclick=location='LogOut.php' />";
	echo "<input type='button' value='Registered Users' onclick=location='Registered_Users.php' />";

	
	
	if($_POST['submit'] && !empty($_POST['port']))
	{
		$port= strip_tags($_POST['port']);
		if($port != "1111")
		{
			mysql_select_db($Database);
			$ipcheck = mysql_query("SELECT IP FROM $DEVICES WHERE IP='$_POST[Sim_IP]' && PORT='$port'");
			$count=mysql_num_rows($ipcheck);
	
			if ($_POST[Sim_IP]&&$port && $count==0)
			{
				mysql_select_db($Database);
				$queryreg = mysql_query("INSERT INTO $DEVICES VALUES('','$_POST[Sim_IP]','$port','','')");
				echo "<br>Simulator Added\n";
			}
			else
			{
				echo "<center> <br> <font size='2' color='red'>" . "Please check if the simulator you are trying to add doesn't exist already\n"."</font>";
			}
		}
		else
		{
			echo "<center> <br> <font size='2' color='red'>" . "The port number '1111' is used by the system for another purpose, please choose another port number.\n"."</font>";
		}
	}

?>

<html>
<body>
<form action="Private.php" method="post">
<center>
<h4> Add Simulator Below </h4>

<?php
$Available_IP = shell_exec("/sbin/ifconfig | grep 'inet addr:' | cut -d ':' -f2 | cut -d ' ' -f1");
$nrOfIp= shell_exec("/sbin/ifconfig | grep 'inet addr:' | cut -d ':' -f2 | cut -d ' ' -f1 | wc -l");
$counter=0;
$i=0;

NextIP:
while(!ctype_space($Available_IP[$i]))
{
	$IP[$counter] .= $Available_IP[$i];
	$i++;
}

$i++;
$counter++;
if($counter != $nrOfIp)
{
	goto NextIP;
}

echo'<label for="IP">IP:</label> <select name="Sim_IP">';
foreach($IP as $Value)
{
	if($Value != "127.0.0.1")
	{
		echo '<option value="' . $Value . '">' . $Value . '</option>';
	}
}
echo'</select>';


?>

Port: <input type="text" name="port">
<input type='submit' name='submit' value='Add Simulator'><center>
</form>

<br>
<br>
</body>
</html>

<html>
<body>
<h4> Add Agent Below </h4>
<form action="UploadFile.php" method="POST" enctype="multipart/form-data">
	<label for="file">Filename:</label>
	<input type="file" name="file">

	<?php

	echo "Or &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Choose from the recorded agents:";

	
	$Agents = scandir("$SNMP_PATH/$Recorded_Agents/");
	
	echo'<select name="Recordagent">';
	echo '<option value="">Select file</option>';
	$count=0;
	
	foreach($Agents as $a)
	{
		if($count > 1)
		{
			echo '<option value="' . $a . '">' . $a . '</option>';
		}
		$count++;
	}
	echo'</select>';

	?>

	<br>

<?php



$Query = mysql_query("SELECT * FROM $DEVICES");
$sim=array(1=>"Select Simulator");
while($row = mysql_fetch_array($Query))
{
	array_push($sim, "$row[IP]:$row[Port]");
}
echo "Name: <input type='text' name='Name' size=5>  &nbsp &nbsp &nbsp";
echo "Community: <input type='text' name='CC' size=5> &nbsp &nbsp &nbsp";
echo'Simulator: <select name="sim">';
foreach($sim as $Values)
{
	echo '<option value="' . $Values . '">' . $Values . '</option>';
}
echo'</select>';

?>

	<input type="submit" value="Initiate Agent File">
</form>

<br>
</body>
</html>


<html>
<body>
<form action="RemoveDevice.php" method="post"> 
<center> 
<h4> Remove Simulator Below </h4> 
<?php
$Query = mysql_query("SELECT * FROM $DEVICES");
$sims=array(1=>"Select Simulator");
while($row = mysql_fetch_array($Query))
{
	array_push($sims, "$row[IP]:$row[Port]");
}

echo'Simulator: <select name="sims">';
foreach($sims as $Values)
{
	echo '<option value="' . $Values . '">' . $Values . '</option>';
}
echo'</select>';

?>
<input type="submit" name="deletesimulator" value="Remove Simulator">
<center> 
</form>

<br>
</body>
</html>

<html>
	<body>
<h4> Record Agent Below </h4>
		<form action="Record_Agent.php" method="post">
			<center>
			   Name:<input type="text" name="Name" size="5" /> 		
			   Community:<input type="text" name="community" size="5" /> 
			   IP:<input type="text" name="ip" size="15" />
			  Port:<input type="text" name="port" size="2" />
			   <input type="submit" name="submit" value="Record" />
			   
			</center>
		</form>


	</body>
</html>


<html>
<body>

<form action="<?PHP $_SERVER['PHP_SELF'] ?>" method="GET"> 
<center> 


<h4> Remove Agent Below </h4> 



<?php


$Query = mysql_query("SELECT * FROM $DEVICES");
$simss=array(1=>"Select Simulator");
while($row = mysql_fetch_array($Query))
{
	array_push($simss, "$row[IP]:$row[Port]");
}

echo"Simulator: <select name='simss' onchange='submit();'>";

foreach($simss as $Values)
{
	if($_GET['simss'] == $Values ) 
	{ 
		echo '<option selected="selected" value="' . $Values . '">' . $Values . '</option>';
	}
	else
	{
		echo '<option value="' . $Values . '">' . $Values . '</option>';
	}

}
echo'</select>';

?>
</form>
</body>
</html>



<html>
<body>

<form action=RemoveDevice.php method="POST"> 

<?php
	$Query2 = mysql_query("SELECT * FROM $AgentTable WHERE Simulator='$_GET[simss]'");
	$simsss=array(1=>"Select Agent");
	while($row = mysql_fetch_array($Query2))
	{
		array_push($simsss, "$row[Community]");
	}
	
	session_start();
	$_SESSION['sims'] = $_GET['simss'];
	$_SESSION['Community'] = $simsss;
	echo"Agent: <select name='simsss'>";
	foreach($simsss as $Values)
	{
		
		echo '<option value="' . $Values . '">' . $Values . '</option>';
	}
	echo'</select>';

	echo "<input type='hidden' name='sims2' value='$_GET[simss]'/>";
?>

<input type="submit" name="deleteagent" value="Remove Agent"><center> </form>

</body>
</html>




<html>
<body>

<?php
include 'DatabaseTable.php';

$sql="SELECT *FROM $DEVICES";
if($_GET['sortsim'] == 'IP')
{
	$sql .= " ORDER BY IP";
}
else if($_GET['sortsim'] == 'Port')
{
	$sql .= " ORDER BY Port";
}
else if($_GET['sortsim'] == 'NrOfAgents')
{
	$sql .= " ORDER BY NrOfAgents";
}

$myData=mysql_query($sql,$con);

echo "<h3> Simulator Running Currently Below</h3>";

echo "<table border=1>
<tr bgcolor='#948f24'>
<th><a href='Private.php?sortsim=IP'>IP</th>
<th><a href='Private.php?sortsim=Port'>Port</th>
<th><a href='Private.php?sortsim=NrOfAgents'>NrOfAgents</th> 
</tr>";
while($record=mysql_fetch_array($myData))
{

echo "<form action=Public.php method=post>";
echo "<tr>";
echo "<td align='center'>".$record['IP']. " </td>";
echo "<td align='center'>".$record['Port']. " </td>";
echo "<td align='center'>".$record['NrOfAgents']. " </td>";

echo "</form>";
echo "</tr>";

}
echo "</table>";
$sql="SELECT * FROM $AgentTable";

if($_GET['sortAgent'] == 'Community')
{
	$sql .= " ORDER BY Community";
}
else if($_GET['sortAgent'] == 'Simulator')
{
	$sql .= " ORDER BY Simulator";
}
else if($_GET['sortAgent'] == 'Name')
{
	$sql .= " ORDER BY Name";
}

$myData=mysql_query($sql,$con);

echo "<h3> Agents Running Currently Below</h3>";

echo "<form method=POST>";

echo "Community: <input type='text' value='Write agent community here' name='search2'/>";
echo "<input type='submit' name=search value='Search' onclick='location=Homepage.php'/>";
echo "</form>";

if(!empty($_POST['search2']))
{
	$sql="SELECT * FROM $AgentTable WHERE Community='$_POST[search2]'";
	$myData=mysql_query($sql,$con);
}

echo "<table border=1>
<tr bgcolor='#948f24'>
<th><a href='Private.php?sortAgent=Name'>Name</th>
<th><a href='Private.php?sortAgent=Community'>Community</th>
<th><a href='Private.php?sortAgent=Simulator'>Simulator</th>

</tr>";

while($record=mysql_fetch_array($myData))
{

	echo "<form action=Private.php method=post>";
	echo "<tr>";
	echo "<td align='center'>".$record['Name']. " </td>";
	echo "<td align='center'>".$record['Community']. " </td>";
	echo "<td align='center'>".$record['Simulator']. " </td>";

	echo "</form>";
	echo "</tr>";

}
echo "</table>";


?>
</body>
</html>
