<?
session_start();
$dbhost		= "127.0.0.1";
$dbuser		= "deneme";
$dbpass		= "dene";
$dbadi		= "dene";

$baglan		= mysql_connect($dbhost,$dbuser,$dbpass);
if(! $baglan)
  die("Mysql baglantısı sağlanamıyor");

mysql_select_db($dbadi,$baglan) or die("Veritabanı baglantısı sağlanamıyor");

mysql_query("SET NAMES 'uft8'");

?>
