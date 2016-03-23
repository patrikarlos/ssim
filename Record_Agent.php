<?php
include 'DatabaseTable.php';


if (!empty($_POST['Name']) && !empty($_POST['community']) && !empty($_POST['ip']) && !empty($_POST['port']))
{
	$Name= $_POST['Name'];
	$community= $_POST['community'];
	$ip= $_POST['ip'];
	$port= $_POST['port'];
	$namecheck = mysql_query("SELECT Community FROM $Record_Agent WHERE Community='$community' AND IP ='$ip' AND Port = '$port' ");
	$count=mysql_num_rows($namecheck);
	if($count!=0)
	{
		echo "<font size='1' color='red'>" . "This ip and port are already taken\n" . "</font>";
	}
	else
	{
		$Agents = scandir("$SNMP_PATH/$Recorded_Agents/");
		$Taken=0;
	
		foreach($Agents as $a)
		{
			if("$Name.snmprec" == $a)
			{
				echo "This name is already taken! Redirecting to private page";
				$Taken=1;
				header( "refresh:2;url=Private.php" );	
				
			}
		}
		if(!$Taken)
		{
			$queryreg = mysql_query("INSERT INTO $Record_Agent VALUES('','$Name', '$community','$ip','$port')");
		
			echo "Agent has been recorded";
			usleep(100000);	
			header("Location: Private.php"); 
		}
	}
	
}

		else 
		{
	 		echo "Please fill all boxes. Redirecting to private page";
			header( "refresh:2;url=Private.php" );
		}


?>
