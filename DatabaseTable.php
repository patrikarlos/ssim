<?php

$config = parse_ini_file('Project.ini', 'Connection');

$host=$config['Connection']['Database_IP'];
$MYSQLPORT=$config['Connection']['Database_Port'];
$Database=$config['Connection']['Database_Name'];
$user=$config['Connection']['Database_Username'];
$passwd=$config['Connection']['Database_Password'];

$DEVICES=$config['Tables']['Simulator_Table'];
$AgentTable=$config['Tables']['Agent_Table'];
$USERS=$config['Tables']['USERS'];
$Record_Agent=$config['Tables']['Record_Agent'];
$SNMP_PATH=$config['Folders']['snmpsim_Path'];
$Recorded_Agents=$config['Folders']['Recorded_Agents'];
$Localhost_Path=$config['Folders']['Localhost_Path'];

$con = mysql_connect($host, $user, $passwd);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db($Database);
$query="CREATE TABLE IF NOT EXISTS $DEVICES (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `IP` tinytext NOT NULL,
    `Port` int(11) NOT NULL,
    `PID` int(11) NOT NULL,
    `NrOfAgents` int(11) NOT NULL,
     PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
   mysql_query($query);
  $query1="CREATE TABLE IF NOT EXISTS $AgentTable (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` tinytext NOT NULL,
  `Community` tinytext NOT NULL,
  `Simulator` tinytext NOT NULL,
  `Old_Community` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
  mysql_query($query1);
$query3 ="CREATE TABLE IF NOT EXISTS $USERS 
( `id` int(4) NOT NULL AUTO_INCREMENT, 
 `username` varchar(40) NOT NULL, 
`password` varchar(100) NOT NULL, 
`permission` tinyint(1) DEFAULT NULL,	 
PRIMARY KEY (`id`) 
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1"; 
mysql_query($query3);

$query4="CREATE TABLE IF NOT EXISTS $Record_Agent (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `Name` tinytext NOT NULL,
    `Community` tinytext NOT NULL,
    `IP` tinytext NOT NULL,
    `Port` int(11) NOT NULL,
     PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
mysql_query($query4);
?>
