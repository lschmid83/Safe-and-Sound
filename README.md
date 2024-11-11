# Safe and Sound / WAMP OsCommerce 2.2 Localhost Development Environment Setup

Safe and Sound is an osCommerce website I built for a car audio supplier using the PHP Language and MySQL database.

Here are some screenshots of the website:

<img align='left' src='https://drive.google.com/uc?id=1V62KnOZxXVRT-Es0XTFaP0xNsoa0799e' width='240'>
<img src='https://drive.google.com/uc?id=1iNk0kM733-TUwJX1glJVhtnbmJDh4KAb' width='240'>

These are the instructions to setup the website running locally using WAMP Server.

Download the required software here:

[WAMP Server 2.5](https://drive.google.com/file/d/1dZvYppg4sn7IBpMiJEWck6_hY2e_Txcc/view?usp=sharing)

[PHP Version 5.3.28](https://drive.google.com/file/d/1xcv6Oixf_y5hte_uAKlgMWPyGMnsTda4/view?usp=sharing) or [Mirror](http://windows.php.net/)

# Configure WAMP Server

1. Install wampserver2.5-Apache-2.4.9-Mysql-5.6.17-php5.5.12-32b.exe
2. Change WAMP port number to stop conflicts with IIS:
   1. Open: C:\wamp\bin\apache\apache2.4.9\conf\httpd.conf
   2. Search for: #Listen
   3. Change both ports to: 7080
   4. Restart Wamp

# Install PHP

This project requires you use PHP Version 5.3.28 for deprecated functions and register_globals.

1. Extract contents of php-5.3.28-Win32-VC9-x86.zip to C:\wamp\bin\php\php5.3.28\
2. Create a copy of C:\wamp\bin\php\php5.3.28\php.ini-development.ini and rename it php.ini
3. Make the following changes to php.ini
   
```
error_log = "c:/wamp/logs/php_error.log"  
extension_dir = "c:/wamp/bin/php/php5.3.28/ext/"  
upload_tmp_dir = "c:/wamp/tmp"  
date.timezone = 'Europe/London'  
session.save_path = "c:/wamp/tmp"  
register_globals = On  
register_long_arrays = On  
display_errors = Off  
extension=php_mbstring.dll  
extension=php_mysql.dll  
extension=php_mysqli.dll  
```

For convenience you can download the edited file [here](https://drive.google.com/file/d/13JSlSiJLU8kw_YQ2fKKP4xHRNDmVNL4q/view?usp=sharing)

4. Save a copy of php.ini as phpForApache.ini in the same directory. 
   WampServer copies this file to the Apache server when you select the version from the menu.
5. Copy the C:\wamp\bin\php\php5.5.12\wampserver.conf file from the existing PHP version into the newly installed version
6. Make the following changes to c:\wamp\wampmanager.ini
   
Search for [phpVersion] and add the following: 
```  
Type: item; Caption: "5.3.28"; Action: multi; Actions:switchPhp5.3.28 
``` 
At the end of the section (before ;WAMPPHPVERSIONEND), add the following code:  
```
[switchPhp5.3.28]  
Action: service; Service: wampapache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated  
Action: run; FileName: "c:/wamp/bin/php/php5.3.28/php-win.exe";Parameters: "switchPhpVersion.php php5.3.28";WorkingDir: "c:/wamp/scripts"; Flags: waituntilterminated  
Action: run; FileName: "c:/wamp/bin/php/php5.3.28/php-win.exe";Parameters: "-c . refresh.php";WorkingDir: "c:/wamp/scripts"; Flags: waituntilterminated  
Action: run; FileName: "net"; Parameters: "start wampapache"; ShowCmd: hidden; Flags: waituntilterminated  
Action: resetservices  
Action: readconfig;  
```

For convenience you can download the edited file [here](https://drive.google.com/file/d/19k5kwlw282KSKejsHhh-ha2xUQMuu8N3/view?usp=sharing)

Right click on the WampServer icon in the taskbar select exit and then restart the server.

Configure Apache Server
=======================

Enable mod_rewrite in Apache configuration.

1. Edit C:\wamp\bin\apache\apache2.4.9\conf\httpd.conf
2. Search for LoadModule rewrite_module modules/mod_rewrite.so
3. Remove the comment symbol '#' to activate the mod_rewrite.so module
4. Click the Wamp server icon in the task bar and Apache -> Service -> Restart Service

Import mySQL Databases in phpMyAdmin
====================================

1. Open phpMyAdmin (http://localhost:7080/phpmyadmin), select the SQL tab and enter the following commands and click Go to create the database and user account:
```
CREATE DATABASE `web13-sns`
CREATE USER 'web13-sns'@'localhost' IDENTIFIED BY 'admin';
USE `web13-sns`;
GRANT ALL PRIVILEGES ON `web13-sns`.* TO 'web13-sns'@'localhost';
FLUSH PRIVILEGES;`
```

2. Select the web13-sns database in the left column tree view, click the Import tab and choose the web13-sns.sql file from the source code folder and press Go 

Open the osCommerce Website from Localhost
==========================================

1. Copy the contents of the safensound folder to the C:\wamp\www\ folder
2. Open http://localhost:7080/safensound in a browser and you should see the homepage
3. The osCommerce website admin page can be accessed at http://localhost:7080/safensound/admin



