<?php
include '../servletoperations.php';

$_SESSION["user"] = null;
session_destroy();

header("Location: login.php");
die();

 ?>
