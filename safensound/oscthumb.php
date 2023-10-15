<?php

function urlsafe_b64decode($string) {
  $data = str_replace(array('-','_','.'),array('+','/','='),$string);
  $mod4 = strlen($data) % 4;
  if ($mod4) {
    $data .= substr('====', $mod4);
  }
  return base64_decode($data);
}

if (file_exists('includes/local/configure.php')) {
  // Use local dev params if available.
  include('includes/local/configure.php');
}

// Include server parameters.
require('includes/configure.php');

chdir (DIR_FS_CATALOG);

$server=DB_SERVER;              # host name of server running MySQL
$user=DB_SERVER_USERNAME;       # existing login username for mysql
$password=DB_SERVER_PASSWORD;	# login password for mysql username
$dbname=DB_DATABASE;            # name of existing database to use


// Connect to database and get all config values.
// RIGADIN2: faster to ask only the 2 var needed, instead of asking the whole group?
$config_values="";
$dbconn=@mysql_connect($server,$user,$password) or http_headers('','Error,Database Connection');
@mysql_select_db($dbname,$dbconn) or http_headers('','Error,Database Connection');
$sql="select configuration_key as cfgKey, configuration_value as cfgValue from configuration where configuration_group_id='333' or configuration_group_id='4'";
$result=@mysql_query($sql,$dbconn) or http_headers('','Error,Database Connection');
while ($row = @mysql_fetch_array($result)) {
  if ($row['cfgKey'] != "LAST_HASH") $config_values.=$row['cfgKey'].'='.$row['cfgValue'];  // To be fed to hashing function.
    define($row['cfgKey'], $row['cfgValue']);
}


// Decrypt the image filename if switched on.
if (CFG_ENCRYPT_FILENAMES == "True" && CFG_ENCRYPTION_KEY !="") {
  $result = '';
  $key=CFG_ENCRYPTION_KEY;
  $string = urlsafe_b64decode($_GET['src']);
  for ($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)-ord($keychar));
    $result.=$char;
    }
  $_GET['src']= $result;
}

include ('phpThumb/phpThumb.php');

?>