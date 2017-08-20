<?
ob_start();
session_start();

$mysql_hostname		= "94.73.170.208";
$mysql_user		    = "t3vakfimobil";
$mysql_password		= "YVdo88E4";
$mysql_database		= "t3vakfiMobil";

$connection	= mysqli_connect ($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

if(! $connection)
  die("Mysql baglantısı sağlanamıyor");

mysqli_query($connection, "SET NAMES 'uft8'");

?>
