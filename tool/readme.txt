Project  - Version 3.0  09/09/2014 


Included files
--------------

1-  DatabaseTable.php
2-  Homepage.php
3-  Login.php
4-  LoginCheck.php
5-  LogOut.php
6-  Private.php
7-  Record_Agent.php
8-  Register.php
9-  Registered_Users.php
10- RemoveDevice.php
11- UploadFile.php
12- Project.ini
13- script.pl
14- readme.txt



System requirements
-------------------

0- Ubuntu 14.04 LTS - Latest version of Ubuntu needed to run this project.
1- SNMPsim	- The SNMP simulator (snmpsim) must be installed on the OS, and all default files/folders under "snmpsim/data/" should be deleted, so the folder "snmpsim/data/" should be empty when the system starts for the first time.
2- Python	- Since the SNMPsim is based on python, the python must be installed on the OS. This project has been tested on python 2.7.
3- Perl		- Perl must be installed on the OS, to be able to run the back-end (script.pl), if any perl module needed you can install it with this command: "perl -MCPAN -e 'install MODULE::NAME'".
4- SNMP		- Since the back-end is using SNMP, you must install the SNMP on your system. 
5- MySQL	- Needed for the databases, which is used to store the data for the users, running simulator instances and running agents
6- HTTP server	- A server to serve the website. Apache HTTP Server is recommended.
7- OS		- You must of course have an operative system to run this system. This system has been tested on Ubuntu 14.04 LTS
8- Browser	- Needed for viewing the website.

OBS: To prevent any problem, latest version of all requirements should be chosen, if not other version has been recomended.

Installation Commands
----------------------

SERVER AND DATABASE:
1- sudo apt-get install apache2
2- sudo apt-get install mysql-server
3- sudo apt-get install libapache2-mod-auth-mysql php5-mysql phpmyadmin

4- if phpmyadmin not found >>> sudo add-apt-repository "deb http://archive.ubuntu.com/ubuntu $(lsb_release -sc) universe"



SNMPSIM
1- sudo apt-get install python-setuptools (for installing easy_install)
2- sudo easy_install snmpsim


PERLMODULS:
1- sudo perl -MCPAN -e 'install Proc::ProcessTable'
2- sudo perl -MCPAN -e 'install IPC::System::Simple'
3- sudo perl -MCPAN -e 'install File::Grep'
4- sudo perl -MCPAN -e 'install Config::IniFiles'

If there are any errors while installing modules above user should run " sudo apt-get install build-essential". Then reinstall modules above.

START THE SYSTEM
1- sudo chmod -R 777 /var/www (for the permission)
2- Put all included files in Localhost folder
3- Edit the Project.ini
4- sudo perl script.pl
5- Go to your localhost/Homepage.php



Installation instructions
-------------------------

1-	If you are using Linux with Apache HTTP Server, then you should have a folder called "www" under "var" folder on your linux filesystem. Put all the included files in the "www" folder. Then start your server, if you have not already done it, with "sudo /etc/init.d/apache2 start".

2-	Change the parameters in the "Project.ini" file to the desired values. Dont forget to set the snmpsim path in this 		file.The "Recorded_Agents" parameter is only a name of the folder which will be created under snmpsim path for saving the 		recorded agents.

3-	Run the back-end by running the perl file "script.pl" with "sudo /var/www/script.pl". OBS: YOU MUST RUN THIS WITH SUDO, 	otherwise this system will not run.

4	Done! The website should be up now. You can reach it by entering "localhost/Homepage.php" on your browser on the "system 	 machine". OBS: You can also access the web interface from the 'out side' by you entering "your_external_IP/Homepage.php".


Contact information
-------------------
Name:	Omar Alidani, Jaswinder Singh
Email:	omaralidani8@hotmail.com, nkd.jesse@gmail.com







