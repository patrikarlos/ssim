<?php 
include 'DatabaseTable.php';
$name = $_FILES['file'] ['name'];
$tmp_name = $_FILES['file']['tmp_name'];

if(!empty($name) && !empty($_POST['Name']) && $_POST['sim'] != 'Select Simulator') 
{
		$Name=$_POST['Name'];
		$community = explode(".",$name);
		if(move_uploaded_file ($tmp_name, $location.$name) && $community[1] == 'snmprec') 
		{ 

			if(empty($_POST['CC']))
			{
				$foldername= $_POST['sim'];
				
				
				$sql = mysql_query("SELECT * FROM $AgentTable WHERE Community='$community[0]' AND Simulator='$foldername'");
				$count=mysql_num_rows($sql);
		
		
				if($count!=0)
				{
					echo "<center>Agent already exists!";
				
					echo"<br>";
					echo "<input type='button' value='Back' onclick=location='Private.php' />";
			
				}
				else
				{
					$queryreg = mysql_query("INSERT INTO $AgentTable VALUES('', '$Name', '$community[0]','$foldername','')");
					echo "/bin/mkdir -p $Localhost_Path/$foldername <br>\n";
					echo "/bin/mv $Localhost_Path/$name $Localhost_Path/$foldername/ <br>\n";
				shell_exec("/bin/mkdir -p $Localhost_Path/$foldername");
				shell_exec("/bin/mv $Localhost_Path/$name $Localhost_Path/$foldername/");
				chmod("$Localhost_Path/$foldername", 0777);
		
				usleep(100000);
				header("location:Private.php");
				}
			}
			else
			{
				$CC= $_POST['CC'];
				$foldername= $_POST['sim'];
						
				
				$sql = mysql_query("SELECT * FROM $AgentTable WHERE Community='$CC' AND Simulator='$foldername'");
				$count=mysql_num_rows($sql);
		
		
				if($count!=0)
				{
					
					echo "<center>Agent already exists!";
					echo"<br>";
					echo "<input type='button' value='Back' onclick=location='Private.php' />";
			
				}
				else
				{
					$queryreg = mysql_query("INSERT INTO $AgentTable VALUES('', '$Name', '$CC','$foldername','')");
					shell_exec("/bin/mkdir -p $Localhost_Path/$foldername");
					shell_exec("/bin/mv $Localhost_Path/$name $Localhost_Path/$foldername/");
					shell_exec("/bin/mv $Localhost_Path/$foldername/$name $Localhost_Path/$foldername/$CC.snmprec");
					chmod("$Localhost_Path/$foldername", 0777);
		
					usleep(100000);
					header("location:Private.php");
				}

			}
	
		

		} 
		else 
		{
			echo "<center> Not uploaded, the reason may be that the file you uploaded does not have the correct format (.snmprec) <br>";
			echo "<input type='button' value='Back' onclick=location='Private.php' />";
			shell_exec("/bin/rm $Localhost_Path/$name");
		
		}
	

}
else if(!empty($_POST['Recordagent']) && !empty($_POST['Name']) && $_POST['sim'] != 'Select Simulator')  
{
	$Name=$_POST['Name'];
	if(empty($_POST['CC']))
	{
		$ChoosenFile = $_POST['Recordagent'];
		$foldername= $_POST['sim'];
	
		$community = explode(".",$ChoosenFile); 
		$foldername= $_POST['sim'];

		

		$sql = mysql_query("SELECT * FROM $AgentTable WHERE Community='$community[0]' AND Simulator='$foldername'");
		$count=mysql_num_rows($sql);
		
		
		if($count!=0)
		{
			echo "<center>Agent already exists!";
			echo"<br>";
			echo "<input type='button' value='Back' onclick=location='Private.php' />";
		}
		else 
		{
			$queryreg = mysql_query("INSERT INTO $AgentTable VALUES('','$Name', '$community[0]','$foldername','')");
			echo "/bin/mkdir -p $Localhost_Path/$foldername && echo $community[0] > $Localhost_Path/$foldername/$community[0].txt <br>\n";
			shell_exec("/bin/mkdir -p $Localhost_Path/$foldername && echo $community[0] > $Localhost_Path/$foldername/$community[0].txt");
			chmod("$Localhost_Path/$foldername", 0777);

			usleep(100000);
			header("location:Private.php"); 
		}
	}
	else
	{
		$CC= $_POST['CC'];
		$ChoosenFile = $_POST['Recordagent'];
		$foldername= $_POST['sim'];
	

		$community = explode(".",$ChoosenFile);

		$foldername= $_POST['sim'];

		$sql = mysql_query("SELECT * FROM $AgentTable WHERE Community='$CC' AND Simulator='$foldername'");
		$count=mysql_num_rows($sql);
			
		
		if($count!=0)
		{
			echo "<center>Agent already exists!";
			echo"<br>";
			echo "<input type='button' value='Back' onclick=location='Private.php' />";
		}
		else 
		{
			$queryreg = mysql_query("INSERT INTO $AgentTable VALUES('','$Name', '$CC','$foldername','$community[0]')");
			print "/bin/mkdir -p $Localhost_Path/$foldername && echo '$CC' > $Localhost_Path/$foldername/$CC.txt <br>\n";
			shell_exec("/bin/mkdir -p $Localhost_Path/$foldername && echo '$CC' > $Localhost_Path/$foldername/$CC.txt");
			chmod("$Localhost_Path/$foldername", 0777);

			usleep(100000);
			header("location:Private.php"); 
		}
	}
} 
else
{
	echo "<center>";
	if(empty($_POST['Name']))
	{
		echo "You must choose name for the agent! Redirecting to private page.";
	}
	else if($_POST['sim'] == 'Select Simulator')
	{
		echo "Choose a simulator! Redirecting to private page.";
	}
	else
	{
		echo "You must choose agent file or select from the recorded agent! Redirecting to private page.";
	}
	echo "</center>";
	header("refresh:2;Private.php");
}
 
?>
