<?
function setSessionUser($user){
  $_SESSION['user'] = $user;
}

function getSessionUser(){
  $user = $_SESSION['user'];
  return $user;
}


?>
