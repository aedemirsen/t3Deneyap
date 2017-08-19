<?
session_start();
$mysql_hostname		= "0000";
$mysql_user		    = "0000";
$mysql_password		= "0000";
$mysql_database		= "0000";

$connection	= mysqli_connect ($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

if(! $connection)
  die("Mysql baglantısı sağlanamıyor");

mysqli_query($connection, "SET NAMES 'uft8'");

?>
