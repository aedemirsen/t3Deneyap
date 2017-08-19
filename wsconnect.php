<?
session_start();
$mysql_hostname		= "0.0.0.0";
$mysql_user		    = "ddddd";
$mysql_password		= "ddddd";
$mysql_database		= "ddddd";

$connection	= mysqli_connect ($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

if(! $connection)
  die("Mysql baglantısı sağlanamıyor");

mysqli_query($connection, "SET NAMES 'uft8'");

?>
