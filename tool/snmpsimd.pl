#!/usr/bin/perl

use strict;
use warnings;
use DBI;
use Proc::ProcessTable;
use IPC::System::Simple qw(capture);
use File::Grep;
use Config::IniFiles;

########### Config Data #####################
my $cfg = Config::IniFiles->new( -file => "Project.ini" );

my $Database_Name=$cfg->val('Connection', 'Database_Name');
my $Database_IP=$cfg->val('Connection', 'Database_IP');
my $Database_Port=$cfg->val('Connection', 'Database_Port');
my $Database_Username=$cfg->val('Connection', 'Database_Username');
my $Database_Password=$cfg->val('Connection', 'Database_Password');

my $Simulator_Table=$cfg->val('Tables', 'Simulator_Table');
my $Agent_Table=$cfg->val('Tables', 'Agent_Table');
my $Record_Agent=$cfg->val('Tables', 'Record_Agent');

my $snmpsim_Path=$cfg->val('Folders', 'snmpsim_Path');
my $Recorded_Agents=$cfg->val('Folders', 'Recorded_Agents');
my $Localhost_Path=$cfg->val('Folders', 'Localhost_Path');
#---------------------------------------------------------#

my $Systemusername = capture("id -nu");
#print $Systemusername;
$Systemusername ="root";
system("sudo chmod -f -r 777 $Localhost_Path/*/*.*");						 
my @SystemIP = capture("ifconfig | grep 'inet addr:' | cut -d ':' -f2 | cut -d ' ' -f1");


my $MIBIP= "$SystemIP[0]";
chomp($MIBIP);

#print $MIBIP;

print "The script is running now!\n";
if(!-d "$snmpsim_Path/data/MIB/")
{
    system("mkdir $snmpsim_Path/data/MIB/");
    system("sudo chmod -R 777 $snmpsim_Path/data/MIB/");
}
#system("chmod -R 777 $snmpsim_Path/data/MIB/");
system("cat /dev/null > $snmpsim_Path/data/MIB/MIB.snmprec");

my $Tableee = new Proc::ProcessTable;
foreach my $Processss ( @{$Tableee->table} )
{
    if($Processss->cmndline =~ /$MIBIP:1111/)
    {
	my $pid = $Processss->pid;
	system("kill $pid");
    }
}	

print localtime() . "Starting local snmpsim endpoint :1111 \n";
system("nohup snmpsimd.py --agent-udpv4-endpoint=$MIBIP:1111 --data-dir=$snmpsim_Path/data/MIB --process-user=$Systemusername --process-group=$Systemusername > /dev/null 2>&1& ");
my $kasidhfasd=0;
print localtime() . "Entering while\n";
while(1)
{
    sleep(1);
    if ( $kasidhfasd == (100-1) ) {
	print localtime() . "Still looping. \n"; 
	$kasidhfasd=0;
    } else {
	$kasidhfasd=$kasidhfasd+1;
    }
    #####################  From Database  #########################
    my $dbh = DBI->connect("dbi:mysql:$Database_Name; host=$Database_IP; port=$Database_Port", $Database_Username, $Database_Password) or die "Connection could not establish: $DBI::errstr\n";
    
    
    $dbh->do("CREATE TABLE IF NOT EXISTS $Simulator_Table (
		  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  `IP` tinytext NOT NULL,
		  `Port` int(11) NOT NULL,
		  `PID` int(11) NOT NULL,
		  `NrOfAgents` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");
    
    
    
    my @Simulator_Port=();
    my @Simulator_IP=();
    my @PID=();
    my @NrOfAgents=();
    
    my $Query = $dbh->prepare("SELECT * FROM $Simulator_Table");
    
    $Query->execute();
    
    my @RowsArray;
    my $SimulatorCounter=0;
    while(@RowsArray = $Query->fetchrow_array())
    {
	
	$Simulator_IP[$SimulatorCounter] = $RowsArray[1];
	$Simulator_Port[$SimulatorCounter] = $RowsArray[2];
	$PID[$SimulatorCounter] = $RowsArray[3]; 
	$NrOfAgents[$SimulatorCounter] = $RowsArray[4];
	$SimulatorCounter++;	
    }
    
    #---------------------------------------------------------------#
    
    
######################### Remove Simulator ##########################################
    if(capture("ls -A $snmpsim_Path/data/"))
    {
	my @OldSimulator = capture("ls -d $snmpsim_Path/data/*/ | awk -F/ '{print \$10}' ORS=' '");
	@OldSimulator = split(' ', "$OldSimulator[0]");
	
	if(scalar(@OldSimulator) != scalar(@Simulator_Port) )
	{
	    for(my $k=0; $k<scalar(@OldSimulator); $k++)
	    {	
		my $OK=0;
		for(my $n=0; $n<scalar(@Simulator_Port); $n++)
		{
		    my $NewSimulator =  "$Simulator_IP[$n]:$Simulator_Port[$n]";
		    if($OldSimulator[$k] eq $NewSimulator)
		    {
			$OK=1;	
		    }
		}
		if($OK == 0 && $OldSimulator[$k] ne "MIB")
		{
		    print localtime() . "Remove -f -r $snmpsim_Path/data/$OldSimulator[$k]\n";
		    system("rm -f -r $snmpsim_Path/data/$OldSimulator[$k]");
		    my $Table1 = new Proc::ProcessTable;
		    foreach my $Process1 ( @{$Table1->table} )
		    {
			if($Process1->cmndline =~ /$OldSimulator[$k]/)
			{
			    my $PID = $Process1->pid;
			    system("kill $PID");							
			}	
		    }
		}
	    }
	}
    }
#----------------------------------------------------------------------------------#
    
    for(my $i=0; $i<scalar(@Simulator_Port); $i++)
    {	
	
	
	if(!-d "$snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]")
	{
	    print localtime() . "Mkdir $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]; chmod -R \n"; 
	    system("mkdir $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]");
	    system("sudo chmod -R 777 $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]");
	}
	if(-d "$Localhost_Path/$Simulator_IP[$i]:$Simulator_Port[$i]/")
	{
	    my $FileFormat = capture("ls $Localhost_Path/$Simulator_IP[$i]:$Simulator_Port[$i] | cut -d'.' -f2");
	    my $FileName = capture("ls $Localhost_Path/$Simulator_IP[$i]:$Simulator_Port[$i] | cut -d'.' -f1");
	    chomp($FileFormat);
	    chomp($FileName);
	    
	    
	    if($FileFormat eq "snmprec")
	    {
		print localtime() . "Move $Localhost_Path/$Simulator_IP[$i]:$Simulator_Port[$i]/*.* $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]/";
		system("mv $Localhost_Path/$Simulator_IP[$i]:$Simulator_Port[$i]/*.* $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]/");
		
	    }
	    else
	    {
		my $Query23 = $dbh->prepare("SELECT * FROM $Agent_Table WHERE Community='$FileName' AND Simulator='$Simulator_IP[$i]:$Simulator_Port[$i]'");
		$Query23->execute();
		my @row23=();
		my $Old_CC;
		
		while(@row23 = $Query23->fetchrow_array())
		{
		    $Old_CC = $row23[4];
		}
		if($Old_CC)
		{
		    print localtime() . "Copying $snmpsim_Path/$Recorded_Agents/$Old_CC.snmprec $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]/$FileName.snmprec";
		    system("cp $snmpsim_Path/$Recorded_Agents/$Old_CC.snmprec $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]/$FileName.snmprec");
		}
		else
		{
		    print localtime() . "Copying $snmpsim_Path/$Recorded_Agents/$FileName.snmprec $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]/ \n";
		    system("cp $snmpsim_Path/$Recorded_Agents/$FileName.snmprec $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]/");
		}
	    }
	    
	    print localtime() . "Remove(190) $Localhost_Path/$Simulator_IP[$i]:$Simulator_Port[$i]";
	    system("rm -r $Localhost_Path/$Simulator_IP[$i]:$Simulator_Port[$i]");
	}
	

	my $NrAgents = capture("find $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]/ -type f | wc -l");	
#	print localtime() . " Agents for $Simulator_IP[$i]:$Simulator_Port[$i] = $NrAgents ";	
	
	my $Found=0;
	my $Table = new Proc::ProcessTable;
	foreach my $Process ( @{$Table->table} ){
	    if($Process->cmndline =~ /$Simulator_IP[$i]:$Simulator_Port[$i]/){
		$Found=1;
	    }
	}		
	
	if($Found == 0 || $NrOfAgents[$i] != $NrAgents)	{
	    if($Found == 1)	{
		print localtime() . "Killing  pid " .$PID[$i] . "\n";
		system("kill $PID[$i]");
	    }
	    print localtime() . "Starting snmpsimd: --agent-udpv4-endpoint=$Simulator_IP[$i]:$Simulator_Port[$i] --data-dir=$snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i] --process-user=$Systemusername --process-group=$Systemusername \n";
	    system("nohup snmpsimd --agent-udpv4-endpoint=$Simulator_IP[$i]:$Simulator_Port[$i] --data-dir=$snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i] --process-user=$Systemusername --process-group=$Systemusername > /var/log/snmpsim.log 2>&1&");
	    foreach my $Process ( @{$Table->table} ){
		if($Process->cmndline =~ /$Simulator_IP[$i]:$Simulator_Port[$i]/){
		    $PID[$i] = $Process->pid;
		    $dbh->do("UPDATE $Simulator_Table SET PID='$PID[$i]', NrOfAgents='$NrAgents' WHERE IP='$Simulator_IP[$i]' AND Port='$Simulator_Port[$i]'");
		}	
	    }
	}
	
	my $Query = $dbh->prepare("SELECT * FROM $Agent_Table WHERE Simulator='$Simulator_IP[$i]:$Simulator_Port[$i]'");
	$Query->execute();
	my @Agent_Community=();
	my @Agent_Record=();
	my @RowsArray;
	my $AgentCounter=0;
	while(@RowsArray = $Query->fetchrow_array()){
	    $Agent_Community[$AgentCounter] = $RowsArray[2];
	    $AgentCounter++;	
	}
	
	
	
################################### Remove agant ##################################
	my @OldAgents = capture("ls -f $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]/ | awk -F. '{print \$1}' ORS=' '");
	@OldAgents = split(' ', "$OldAgents[0]");
	
	if(scalar(@OldAgents) != scalar(@Agent_Community)){
	    for(my $f=0; $f<scalar(@OldAgents); $f++){
		my $OK2=0;
		for(my $m=0; $m<scalar(@Agent_Community); $m++){
		    if($OldAgents[$f] eq $Agent_Community[$m]){
			$OK2=1;	
		    }
		}
		if($OK2 == 0){
		    print localtime() . " Remove $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]/$OldAgents[$f].snmprec .\n";
		    system("rm $snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i]/$OldAgents[$f].snmprec");
		    my $Table2 = new Proc::ProcessTable;
		    foreach my $Process2 ( @{$Table2->table} ) {
			if($Process2->cmndline =~ /$Simulator_IP[$i]:$Simulator_Port[$i]/){
			    my $PID = $Process2->pid;
			    print localtime() . " kill $PID \n";
			    system("kill $PID");
			    print localtime() . "Starting snmpsim: nohup snmpsimd --agent-udpv4-endpoint=$Simulator_IP[$i]:$Simulator_Port[$i] --data-dir=$snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i] --process-user=$Systemusername --process-group=$Systemusername > /dev/null 2>&1& \n";
			    system("nohup snmpsimd --agent-udpv4-endpoint=$Simulator_IP[$i]:$Simulator_Port[$i] --data-dir=$snmpsim_Path/data/$Simulator_IP[$i]:$Simulator_Port[$i] --process-user=$Systemusername --process-group=$Systemusername > /dev/null 2>&1&");
			    foreach my $Process2 ( @{$Table2->table} ) {
				if($Process2->cmndline =~ /$Simulator_IP[$i]:$Simulator_Port[$i]/)
				{
				    $PID[$i] = $Process2->pid;
				    $dbh->do("UPDATE $Simulator_Table SET PID='$PID[$i]', NrOfAgents='$NrAgents' WHERE IP='$Simulator_IP[$i]' AND Port='$Simulator_Port[$i]'");
				}	
			    }														
			}	
		    }
		}
	    }
	}
	
    }
    
########################  Recording  #########################################
    $dbh->do("CREATE TABLE IF NOT EXISTS $Record_Agent (
				  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `Name` tinytext NOT NULL,
				  `Community` tinytext NOT NULL,
				  `IP` tinytext NOT NULL,
				  `Port` int(1) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");
    
    my $Query22 = $dbh->prepare("SELECT * FROM $Record_Agent");
    $Query22->execute();
    my $Record_Community='0';
    my $Record_IP='0';
    my $Record_Port=0;
    my $Record_Name="";
    
    my @RowsArray22;
    my $record=0;
    while(@RowsArray22 = $Query22->fetchrow_array())
    {
	$Record_Name = $RowsArray22[1];
	$Record_Community = $RowsArray22[2];
	$Record_IP = $RowsArray22[3];
	$Record_Port = $RowsArray22[4];
	$record=1;
    }
    
    if(!-d "$snmpsim_Path/$Recorded_Agents")
    {
	print "mkdir $snmpsim_Path/$Recorded_Agents; chmod -R 777 $snmpsim_Path/$Recorded_Agents/ ";
	system("mkdir $snmpsim_Path/$Recorded_Agents");
	system("sudo chmod -R 777 $snmpsim_Path/$Recorded_Agents/");
    }
    if($record == 1)
    {	
	print "Recording .";
	system("snmprec.py --agent-udpv4-endpoint=$Record_IP:$Record_Port --community=$Record_Community --output-file=$snmpsim_Path/$Recorded_Agents/$Record_Name.snmprec");
	#nohup  > /dev/null 2>&1&
	my $sti = $dbh->prepare("drop table $Record_Agent");
	$sti->execute();
	
    }
    
    $dbh->do("CREATE TABLE IF NOT EXISTS $Agent_Table (
				  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `Name` tinytext NOT NULL,
				  `Community` tinytext NOT NULL,
				  `Simulator` tinytext NOT NULL,
				  `Old_Community` tinytext NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
		");
    
    
    
############### MIB ############################
    my @Rows=();
    my @IP=();
    my @PORT=();
    
    my $Query2 = $dbh->prepare("SELECT * FROM $Simulator_Table");
    
    $Query2->execute();
    
    my $Counter=1;
    while(@Rows = $Query2->fetchrow_array())
    {
	
	$IP[$Counter] = $Rows[1];
	$PORT[$Counter] = $Rows[2];
	$Counter++;	
    }
    my $MIB_FILE= "$snmpsim_Path/data/MIB/MIB.snmprec";
    
    for(my $s=1; $s<scalar(@IP); $s++)
    {
	my $Hit=0;
	my $numberOfSim=1;
	open FILE,"<$MIB_FILE";
	while (my $line = <FILE>)
	{
	    if ($line =~ m/$IP[$s]:$PORT[$s]/)
	    {
		$Hit =1;
	    }
	    $numberOfSim++;
	}
	if($Hit == 0 || $numberOfSim > scalar(@IP))
	{
	    print "Remove $MIB_FILE ";
	    system("rm -f $MIB_FILE");
	    for(my $l=1; $l<scalar(@IP); $l++)
	    {
		my $data="1.3.6.1.2.1.1.6.0.1.1.1.1.$l|4|$IP[$l]:$PORT[$l]";
		system("printf '$data\n' >> $MIB_FILE");
	    }
	    
	    my $Table3 = new Proc::ProcessTable;
	    foreach my $Process3 ( @{$Table3->table} )
	    {
		if($Process3->cmndline =~ /$MIBIP:1111/)
		{
		    my $PID3 = $Process3->pid;
		    "Kill $PID3 ";
		    system("kill $PID3");
		    system("nohup snmpsimd --agent-udpv4-endpoint=$MIBIP:1111 --data-dir=$snmpsim_Path/data/MIB --process-user=$Systemusername --process-group=$Systemusername > /dev/null 2>&1&");						
		}	
	    }
	    
	}
	
    }
    my $sth = $dbh->prepare("SELECT * FROM $Simulator_Table");
    $sth->execute;
    unless($sth->fetch())
    {
	system("cat /dev/null > $snmpsim_Path/data/MIB/MIB.snmprec");
    }
    
#---------------------------------------------------------------------#
}


















