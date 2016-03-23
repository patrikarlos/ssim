<center>

<html>
<body>
<input type="button" value="Login" onclick="location='Login.php'" />
<input type="button" value="Register" onclick="location='Register.php'"/>

<?php
include 'DatabaseTable.php';

$sql="SELECT * FROM $DEVICES";
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

echo "<h4> Simulator Running Currently Below</h4>";

echo "<table border=1>
<tr bgcolor='#948f24'>
<th><a href='Homepage.php?sortsim=IP'>IP</a></th>
<th><a href='Homepage.php?sortsim=Port'>Port</th>
<th><a href='Homepage.php?sortsim=NrOfAgents'>NrOfAgents</th> 
</tr>";
while($record=mysql_fetch_array($myData)){

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
$myData=mysql_query($sql,$con);

echo "<h4> Agents Running Currently Below</h4>";

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
<th><a href='Homepage.php?sortAgent=Community'>Community</th>
<th><a href='Homepage.php?sortAgent=Simulator'>Simulator</th>


</tr>";
while($record=mysql_fetch_array($myData)){

echo "<form action=Public.php method=post>";
echo "<tr>";
echo "<td align='center'>".$record['Community']. " </td>";
echo "<td align='center'>".$record['Simulator']. " </td>";

echo "</form>";
echo "</tr>";

}
echo "</table>";
?>
</body>
<html>
