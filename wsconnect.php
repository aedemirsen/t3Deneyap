<?
session_start();
$mysql_hostname		= "0.0.0.1";
$mysql_user		    = "dede";
$mysql_password		= "dede";
$mysql_database		= "dede";

$connection	= mysqli_connect ($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

if(! $connection)
  die("Mysql baglantısı sağlanamıyor");

mysqli_query($connection, "SET NAMES 'uft8'");

?>
